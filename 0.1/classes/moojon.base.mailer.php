<?php
abstract class moojon_base_mailer extends moojon_base {
	private $recipients;
	private $from;
	private $subject;
	private $body;
	private $charset;
	private $subject;
	private $name;
	private $email;
	private $to;
	private $cc;
	private $bcc;
	private $text;
	private $html;
	private $type;
	private $header;
	private $body;
	private $reply_to;
	private $return_path;
	private $attachments_index;
	private $attachments = array();
	private $attachments_img = array();
	private $mix;
	private $rel;
	private $alt;
	private $sended_index;
	
	final public function __construct() {
		$this->charset = moojon_config::get('charset'),
		$this->subject = moojon_config::get('mail_subject'),
		$this->email = moojon_config::get('mail_from_email');
		$this->name = moojon_config::get('mail_from_name');
		$this->from = $this->name.' <'.$this->email.'>';
		$this->mix = "=-moojon_mix_" . md5(uniqid(rand()));
		$this->rel = "=-moojon_rel_" . md5(uniqid(rand()));
		$this->alt = "=-moojon_alt_" . md5(uniqid(rand()));
		$this->attachments_index = 0;
		$this->sended_index = 0;
	}
	
	final protected function set_from($email, $name = '') {
		if ($this->validate_mail($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->from = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_to($email, $name = '') {
		if ($this->validate_mail($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->to = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_cc($email, $name = '') {
		if ($this->validate_mail($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->cc = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_bcc($email, $name = '') {
		if ($this->validate_mail($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->bcc = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function add_to($to, $name = ''){
		if ($this->validate_mail($to)){
			$to = (empty($name) == false) ? "$name <$to>" : $to;
			$this->to = (empty($this->to) == false) ? $this->to.', '.$to : $to;
			return true;
		}
		return false;
	}
	
	final protected function add_cc($cc, $name = ''){
		if ($this->validate_mail($cc)){
			$cc = !empty($name) ? "$name <$cc>" : $cc;
			$this->cc = (empty($this->cc) == false) ? $this->cc.', '.$cc : $cc;
			return true;
		}
		return false;
	}
	
	final protected function add_bcc($bcc, $name = ''){
		if ($this->validate_mail($bcc)) {
			$bcc = !empty($name) ? "$name <$bcc>" : $bcc;
			$this->bcc = (empty($this->bcc) == false) ? $this->bcc.', '.$bcc : $bcc;
			return true;
		}
		return false;
	}
	
	final protected function set_reply_to($reply_to, $name = ''){
		if ($this->validate_mail($reply_to)) {
			$this->reply_to = (empty($name) == false) ? "$name <$reply_to>" : $reply_to;
			return true;
		}
		return false;
	}
	
	final protected function set_return_path($return_path){
		if ($this->validate_mail($return_path)) {
			$this->return_path = $return_path;
			return true;
		}
		return false;
	}
	
	final protected function set_subject($subject){
		$this->subject = (empty($subject) == false) ? $subject : "No subject";
	}
	
	final protected function set_text($text){
		if (empty($text) == false) {
			$this->text = $text;
		}
	}
	
	final protected function set_html($html){
		if (empty($html) == false) {
			$this->html = $html;
		}
	}
	
	final protected function add_attachment($name, $path){
		$attachment = array(
			'name' => $name,
			'type' => shell_exec('file -bi '.escapeshellarg($path));
			'content' => chunk_split(base64_encode(moojon_path::get_file_contents($path)), 76, "\n"),
			'embedded' => false
		);
		$this->attachments[] = $attachment;
	}
	
	final protected function send(){
		if ($this->sended_index == 0 && $this->build_body() == false) {
			return false;
    	}
		if (empty($this->return_path) == false) {
			return mail($this->to, $this->subject, $this->body, $this->header, '-f'.$this->return_path);
		} else {
			return mail($this->to, $this->subject, $this->body, $this->header);
		}
	}
	
	final protected function build_body(){
		switch ($this->parse_elements()){
			case 1:
				$this->build_header("Content-Type: text/plain");
				$this->body = $this->text;
				break;
			case 3:
				$this->build_header("Content-Type: multipart/alternative; boundary=\"$this->alt\"");
				$this->body .= "--" . $this->alt . "\n";
				$this->body .= "Content-Type: text/plain" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->text . "\n" . "\n";
				$this->body .= "--" . $this->alt . "\n";
				$this->body .= "Content-Type: text/html; charset=\"$this->charset\"" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->html . "\n" . "\n";
				$this->body .= "--" . $this->alt . "--" . "\n";
				break;
			case 5:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->mix\"");
				$this->body .= "--" . $this->mix . "\n";
				$this->body .= "Content-Type: text/plain" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->text . "\n" . "\n";
				foreach($this->attachments as $value){
					$this->body .= "--" . $this->mix . "\n";
					$this->body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
					$this->body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
					$this->body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
					$this->body .= $value['content'] . "\n" . "\n";
				}
				$this->body .= "--" . $this->mix . "--" . "\n";
				break;
			case 7:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->mix\"");
				$this->body .= "--" . $this->mix . "\n";
				$this->body .= "Content-Type: multipart/alternative; boundary=\"$this->alt\"" . "\n" . "\n";
				$this->body .= "--" . $this->alt . "\n";
				$this->body .= "Content-Type: text/plain" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->text . "\n" . "\n";
				$this->body .= "--" . $this->alt . "\n";
				$this->body .= "Content-Type: text/html; charset=\"$this->charset\"" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->html . "\n" . "\n";
				$this->body .= "--" . $this->alt . "--" . "\n" . "\n";
				foreach($this->attachments as $value){
					$this->body .= "--" . $this->mix . "\n";
					$this->body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
					$this->body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
					$this->body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
					$this->body .= $value['content'] . "\n" . "\n";
				}
				$this->body .= "--" . $this->mix . "--" . "\n";
				break;
			case 11:
				$this->build_header("Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->rel\"");
				$this->body .= "--" . $this->rel . "\n";
				$this->body .= "Content-Type: multipart/alternative; boundary=\"$this->alt\"" . "\n" . "\n";
				$this->body .= "--" . $this->alt . "\n";
				$this->body .= "Content-Type: text/plain" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->text . "\n" . "\n";
				$this->body .= "--" . $this->alt . "\n";
				$this->body .= "Content-Type: text/html; charset=\"$this->charset\"" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->html . "\n" . "\n";
				$this->body .= "--" . $this->alt . "--" . "\n" . "\n";
				foreach($this->attachments as $value){
					if ($value['embedded']){
						$this->body .= "--" . $this->rel . "\n";
						$this->body .= "Content-ID: <" . $value['embedded'] . ">" . "\n";
						$this->body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
						$this->body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
						$this->body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
						$this->body .= $value['content'] . "\n" . "\n";
					}
				}
				$this->body .= "--" . $this->rel . "--" . "\n";
				break;
			case 15:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->mix\"");
				$this->body .= "--" . $this->mix . "\n";
				$this->body .= "Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->rel\"" . "\n" . "\n";
				$this->body .= "--" . $this->rel . "\n";
				$this->body .= "Content-Type: multipart/alternative; boundary=\"$this->alt\"" . "\n" . "\n";
				$this->body .= "--" . $this->alt . "\n";
				$this->body .= "Content-Type: text/plain" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->text . "\n" . "\n";
				$this->body .= "--" . $this->alt . "\n";
				$this->body .= "Content-Type: text/html; charset=\"$this->charset\"" . "\n";
				$this->body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->body .= $this->html . "\n" . "\n";
				$this->body .= "--" . $this->alt . "--" . "\n" . "\n";
				foreach($this->attachments as $value){
					if ($value['embedded']){
						$this->body .= "--" . $this->rel . "\n";
						$this->body .= "Content-ID: <" . $value['embedded'] . ">" . "\n";
						$this->body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
						$this->body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
						$this->body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
						$this->body .= $value['content'] . "\n" . "\n";
					}
				}
				$this->body .= "--" . $this->rel . "--" . "\n" . "\n";
				foreach($this->attachments as $value){
					if (!$value['embedded']){
						$this->body .= "--" . $this->mix . "\n";
						$this->body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
						$this->body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
						$this->body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
						$this->body .= $value['content'] . "\n" . "\n";
					}
				}
				$this->body .= "--" . $this->mix . "--" . "\n";
				break;
			default:
				return false;
		}
		$this->sended_index++;
		return true;
	}
	
	final protected function build_header($content_type){
		$this->header .= 'From: '.$this->from."\n";
		$this->header .= (!empty($this->reply_to)) ? 'Reply-To: '.$this->reply_to."\n" : 'Reply-To: '. $this->from."\n";
		}
		if (empty($this->cc) == false) {
			$this->header .= 'Cc: '.$this->cc."\n";
		}
		if (empty($this->bcc) == false) {
			$this->header .= 'Bcc: '.$this->bcc."\n";
		}
		if (empty($this->return_path) == false) {
			$this->header .= 'Return-Path: '.$this->return_path."\n";
		}
		$this->header .= 'MIME-Version: 1.0'."\n";
		$this->header .= 'X-Mailer: neXus MIME Mail - PHP/'.phpversion()."\n";
		$this->header .= $content_type;
	}
	
	final protected function parse_elements(){
		$this->type = 0;
		if ($this->attachments_index != 0) {
			foreach($this->attachments as $key => $value) {
				if (preg_match('/(css|image)/i', $value['type']) && preg_match('/\s(background|href|src)\s*=\s*[\"|\']('.$value['name'].')[\"|\'].*>/is', $this->html)) {
					$img_id = md5($value['name']).".moojon@mimemail";
					$this->html = preg_replace('/\s(background|href|src)\s*=\s*[\"|\']('.$value['name'].')[\"|\']/is', ' \\1="cid:'.$img_id.'"', $this->html);
					$this->attachments[$key]['embedded'] = $img_id;
					$this->attachments_img[] = $value['name'];
				}
			}
		}
		if (!empty($this->text)){
			$this->type ++;
		}
		if (!empty($this->html)){
			$this->type += 2;
			if (empty($this->text)){
				$this->text = strip_tags(eregi_replace("<br>", "\n", $this->html));
				$this->type ++;
			}
		}
		if ($this->attachments_index != 0){
			if (count($this->attachments_img) != 0){
				$this->type += 8;
			}
			if ((count($this->attachments) - count($this->attachments_img)) >= 1){
				$this->type += 4;
			}
		}
		return $this->type;
	}
	
	final protected function validate_mail($mail){
		if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',$mail)) {
			return true;
		}
		return false;
}
?>