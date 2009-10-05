<?php
final class tester {
	private $growl;
	private $notification_file = 'notification';
	
	final public function __construct($path) {
		if ($directory_handler = opendir($path)) {
			while (!(($object = readdir($directory_handler)) === false)) {
				if (is_file("$path$object")) {
					$pathinfo = pathinfo("$path$object");
					if ($pathinfo['extension'] == 'php') {
						require_once("$path$object");
					}
				}
			}
		}
		closedir($directory_handler);
		$this->growl =& Net_Growl::singleton('Net_Growl', array('Messages'));
		$failure = null;
		foreach (get_declared_classes() as $class_name) {
			if (is_subclass_of($class_name, 'base_test_suite')) {
				$test_suite = new $class_name;
				foreach (get_class_methods($test_suite) as $method_name) {
					$failure = null;
					if (strtolower(substr($method_name, 0, 4)) == 'test') {
						$test_suite->$method_name();
						$failure = $test_suite->get_failure();
					}
					if ($failure) {
						break;
					}
				}
				unset($test_suite);
				if ($failure) {
					break;
				}
			}
		}
		if (!$failure) {
			/*$notification_file = $this->notification_file;
			if (file_exists($notification_file)) {
				unlink($notification_file);*/
				$this->growl->notify('Messages', 'Moojon tester', 'Success, all unit tests passed!');
			//}
		} else {
			$this->growl->notify('Messages', 'Moojon tester', "Failure! ($failure)");
			echo "$failre\n";
		}
	}
	
	final private function notify($message) {
		/*$notification_file = $this->notification_file;
		if (!file_exists($notification_file)) {
			touch($notification_file);
			chmod($notification_file, 0666);
			if (!$handle = fopen($notification_file, 'w')) {
				fclose($handle);
				throw new moojon_exception("Unable to open / create notification file ($notification_file)");
			}
			if (!fwrite($handle, $message)) {
				fclose($handle);
				throw new moojon_exception("Unable to write to notification file ($notification_file)");
			}
			fclose($handle);*/
			$this->growl->notify('Messages', 'Moojon tester', $message);
		//} else {
			echo "\n".file_get_contents($notification_file)."\n";
		//}
	}
}
?>