<?php
require_once 'PEAR.php';
class Net_Growl_Application {
    var $_growlAppName;
    var $_growlAppPassword = '';
    var $_growlNotifications = array();

    function Net_Growl_Application($appName, $notifications, $password = '')  {
        $this->_growlAppName = $appName;
        $this->_growlAppPassword = (empty($password)) ? '' : $password;
        if (!empty($notifications) && is_array($notifications)) {
            $this->addGrowlNotifications($notifications);
        }
    }

    function addGrowlNotifications($notifications) {
        $default = $this->_getGrowlNotificationDefaultOptions();
        foreach ($notifications as $name => $options) {
            if (is_int($name)) {
                $name = $options;
                $options = $default;
            } elseif (!empty($options) && is_array($options)) {
                $options = array_merge($default, $options);
            }
            $this->_growlNotifications[$name] = $options;
        }
    }

    function _getGrowlNotificationDefaultOptions() {
        return array('enabled' => true);
    }

    function &getGrowlNotifications()  {
        return $this->_growlNotifications;
    }

    function getGrowlName() {
        return $this->_growlAppName;
    }

    function getGrowlPassword()
    {
        return $this->_growlAppPassword;
    }
}
if (!defined('GROWL_UDP_PORT')) define('GROWL_UDP_PORT', 9887);
if (!defined('GROWL_PROTOCOL_VERSION')) define('GROWL_PROTOCOL_VERSION', 1);
if (!defined('GROWL_TYPE_REGISTRATION')) define('GROWL_TYPE_REGISTRATION', 0);
if (!defined('GROWL_TYPE_NOTIFICATION')) define('GROWL_TYPE_NOTIFICATION', 1);
$GLOBALS['_NET_GROWL_NOTIFICATION_COUNT'] = 0;
if (!isset($GLOBALS['_NET_GROWL_NOTIFICATION_LIMIT'])) {
    $GLOBALS['_NET_GROWL_NOTIFICATION_LIMIT'] = 0;
}
class Net_Growl extends PEAR {
    var $_application;
    var $_socket;
    var $_isRegistered = false;
    var $_options = array('host' => '127.0.0.1', 'port' => GROWL_UDP_PORT, 'protocol' => 'udp');
    function &singleton($appName, $notifications, $password = '', $options = array()) {
        static $obj;
        if (!isset($obj)) {
            $obj = new Net_Growl($appName, $notifications, $password, $options);
        }
        return $obj;
    }

    function Net_Growl(&$application, $notifications = array(), $password = '', $options = array()) {
        foreach ($options as $k => $v) {
            if (isset($this->_options[$k])) {
                $this->_options[$k] = $v;
            }
        }
        if (is_string($application)) {
            $this->_application =& new Net_Growl_Application($application, $notifications, $password);
        } elseif (is_object($application)) {
            $this->_application =& $application;
        }
        parent::PEAR();
    }

    function setNotificationLimit($max) {
        $GLOBALS['_NET_GROWL_NOTIFICATION_LIMIT'] = $max;
    }

    function &getApplication() {
        return $this->_application;
    }

    function _sendRegister() {
        if (!isset($this->_socket)) {
            $socket = $this->_options['protocol'].'://'.$this->_options['host'];
            $this->_socket = fsockopen($socket, $this->_options['port'], $errno, $errstr);
            if ($this->_socket === false) {
                return PEAR::raiseError($errstr);
            }
        }
        $appName = utf8_encode($this->_application->getGrowlName());
        $password = $this->_application->getGrowlPassword();
        $nameEnc = $defaultEnc = '';
        $nameCnt = $defaultCnt = 0;
        $notifications = $this->_application->getGrowlNotifications();
        foreach($notifications as $name => $options) {
            if (is_array($options) && !empty($options['enabled'])) {
                $defaultEnc .= pack('c', $nameCnt);
                ++$defaultCnt;
            }
            $name = utf8_encode($name);
            $nameEnc .= pack('n', strlen($name)).$name;
            ++$nameCnt;
        }
        $data = pack('c2nc2', GROWL_PROTOCOL_VERSION, GROWL_TYPE_REGISTRATION, strlen($appName), $nameCnt, $defaultCnt);
        $data .= $appName.$nameEnc.$defaultEnc;
        if (!empty($password)) {
            $checksum = pack('H32', md5($data.$password));
        } else {
            $checksum = pack('H32', md5($data));
        }
        $data .= $checksum;
        $res = fwrite($this->_socket, $data, strlen($data));
        if ($res === false) {
            return PEAR::raiseError('Could not send registration to Growl Server.');
        }
        $this->_isRegistered = true;
        return true;
    }

    function notify($name, $title, $description = '', $options = array()) {
        if ($GLOBALS['_NET_GROWL_NOTIFICATION_LIMIT'] > 0 &&
            $GLOBALS['_NET_GROWL_NOTIFICATION_COUNT'] >= $GLOBALS['_NET_GROWL_NOTIFICATION_LIMIT']) {
            return true;
        }
        if (!$this->_isRegistered && ($res = $this->_sendRegister()) !== true) {
            return $res;
        }
        $appName     = utf8_encode($this->_application->getGrowlName());
        $password    = $this->_application->getGrowlPassword();
        $name        = utf8_encode($name);
        $title       = utf8_encode($title);
        $description = utf8_encode($description);
        $priority    = isset($options['priority']) ? $options['priority'] : 0;
        $flags = ($priority & 7) * 2;
        if ($priority < 0) {
            $flags |= 8;
        }
        if (isset($options['sticky']) && $options['sticky'] === true) {
            $flags = $flags | 1;
        }
        $data = pack('c2n5', GROWL_PROTOCOL_VERSION, GROWL_TYPE_NOTIFICATION, $flags, strlen($name), strlen($title), strlen($description), strlen($appName));
        $data .= $name . $title . $description . $appName;
        if (!empty($password)) {
            $checksum = pack('H32', md5($data . $password));
        } else {
            $checksum = pack('H32', md5($data));
        }
        $data .= $checksum;
		$res = fwrite($this->_socket, $data, strlen($data));
        if ($res === false) {
            return PEAR::raiseError('Could not send notification to Growl Server.');
        }
        ++$GLOBALS['_NET_GROWL_NOTIFICATION_COUNT'];
        return true;
    }

    function _Net_Growl() {
        if (is_resource($this->_socket)) {
            fclose($this->_socket);
            $this->_socket = null;
        }
    }
}
//$growl =& Net_Growl::singleton('Net_Growl', array('Messages'));
//$growl->notify('Messages', 'Hello', 'How are you ?');
?>