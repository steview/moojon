<?php
abstract class moojon_base_mailer extends moojon_base {
	private $recipients;
	private $from;
	private $subject;
	private $body;
	private $charset = "ISO-8859-1";
	private $mail_subject = "No subject";
	private $from = "Anonymous <fake@mail.com>";
	private $mail_to;
	private $cc;
	private $bcc;
	private $mail_text;
	private $mail_html;
	private $mail_type;
	private $header;
	private $mail_body;
	private $mail_reply_to;
	private $return_path;
	private $attachments_index;
	private $attachments = array();
	private $attachments_img = array();
	private $boundary_mix;
	private $boundary_rel;
	private $boundary_alt;
	private $sended_index;
	
	final public function __construct() {
		$this->boundary_mix = "=-nxs_mix_" . md5(uniqid(rand()));
		$this->boundary_rel = "=-nxs_rel_" . md5(uniqid(rand()));
		$this->boundary_alt = "=-nxs_alt_" . md5(uniqid(rand()));
		$this->attachments_index = 0;
		$this->sended_index = 0 ;
		if(!defined('"\n"')){
			define('"\n"', "\n");
		}
	}
	
	final protected function set_recipients($recipients) {}
	
	final protected function set_subject($subject) {}
	
	final protected function set_body($body) {}
	
	final protected function set_from($from, $name = ""){
		if ($this->validate_mail($from)){
			$this->from = !empty($name) ? "$name <$from>" : $from;
		}
		else {
			$this->from = "Anonymous <fake@mail.com>";
		}
	}
	
	final protected function set_to($mail_to, $name = ""){
		if ($this->validate_mail($mail_to)){
			$this->mail_to = !empty($name) ? "$name <$mail_to>" : $mail_to;
			return true;
		}
		return false;
	}
	
	final protected function set_cc($cc, $name = ""){
		if ($this->validate_mail($cc)){
			$this->cc = !empty($name) ? "$name <$cc>" : $cc;
			return true;
		}
		return false;
	}
	
	final protected function set_bcc($bcc, $name = ""){
		if ($this->validate_mail($bcc)){
			$this->bcc = !empty($name) ? "$name <$bcc>" : $bcc;
			return true;
		}
		return false;
	}
	
	final protected function add_to($mail_to, $name = ""){
		if ($this->validate_mail($mail_to)){
			$mail_to = !empty($name) ? "$name <$mail_to>" : $mail_to;
			$this->mail_to = !empty($this->mail_to) ? $this->mail_to . ", " . $mail_to : $mail_to;
			return true;
		}
		return false;
	}
	
	final protected function add_cc($cc, $name = ""){
		if ($this->validate_mail($cc)){
			$cc = !empty($name) ? "$name <$cc>" : $cc;
			$this->cc = !empty($this->cc) ? $this->cc . ", " . $cc : $cc;
			return true;
		}
		return false;
	}
	
	final protected function add_bcc($bcc, $name = ""){
		if ($this->validate_mail($bcc)) {
			$bcc = !empty($name) ? "$name <$bcc>" : $bcc;
			$this->bcc = (empty($this->bcc) == false) ? $this->bcc.', '.$bcc : $bcc;
			return true;
		}
		return false;
	}
	
	final protected function set_reply_to($mail_reply_to, $name = ""){
		if ($this->validate_mail($mail_reply_to)) {
			$this->mail_reply_to = !empty($name) ? "$name <$mail_reply_to>" : $mail_reply_to;
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
		$this->mail_subject = (empty($subject) == false) ? $subject : "No subject";
	}
	
	final protected function set_text($text){
		if (empty($text) == false) {
			$this->mail_text = $text;
		}
	}
	
	final protected function set_html($html){
		if (empty($html) == false) {
			$this->mail_html = $html;
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
			return mail($this->mail_to, $this->mail_subject, $this->mail_body, $this->header, '-f'.$this->return_path);
		} else {
			return mail($this->mail_to, $this->mail_subject, $this->mail_body, $this->header);
		}
	}
	
	final protected function build_body(){
		switch ($this->parse_elements()){
			case 1:
				$this->build_header("Content-Type: text/plain");
				$this->mail_body = $this->mail_text;
				break;
			case 3:
				$this->build_header("Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"");
				$this->mail_body .= "--" . $this->boundary_alt . "\n";
				$this->mail_body .= "Content-Type: text/plain" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_text . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "\n";
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_html . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "--" . "\n";
				break;
			case 5:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . "\n";
				$this->mail_body .= "Content-Type: text/plain" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_text . "\n" . "\n";
				foreach($this->attachments as $value){
					$this->mail_body .= "--" . $this->boundary_mix . "\n";
					$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
					$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
					$this->mail_body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
					$this->mail_body .= $value['content'] . "\n" . "\n";
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . "\n";
				break;
			case 7:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . "\n";
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "\n";
				$this->mail_body .= "Content-Type: text/plain" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_text . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "\n";
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_html . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "--" . "\n" . "\n";
				foreach($this->attachments as $value){
					$this->mail_body .= "--" . $this->boundary_mix . "\n";
					$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
					$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
					$this->mail_body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
					$this->mail_body .= $value['content'] . "\n" . "\n";
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . "\n";
				break;
			case 11:
				$this->build_header("Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->boundary_rel\"");
				$this->mail_body .= "--" . $this->boundary_rel . "\n";
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "\n";
				$this->mail_body .= "Content-Type: text/plain" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_text . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "\n";
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_html . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "--" . "\n" . "\n";
				foreach($this->attachments as $value){
					if ($value['embedded']){
						$this->mail_body .= "--" . $this->boundary_rel . "\n";
						$this->mail_body .= "Content-ID: <" . $value['embedded'] . ">" . "\n";
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
						$this->mail_body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
						$this->mail_body .= $value['content'] . "\n" . "\n";
					}
				}
				$this->mail_body .= "--" . $this->boundary_rel . "--" . "\n";
				break;
			case 15:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . "\n";
				$this->mail_body .= "Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->boundary_rel\"" . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_rel . "\n";
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "\n";
				$this->mail_body .= "Content-Type: text/plain" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_text . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "\n";
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . "\n";
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . "\n" . "\n";
				$this->mail_body .= $this->mail_html . "\n" . "\n";
				$this->mail_body .= "--" . $this->boundary_alt . "--" . "\n" . "\n";
				foreach($this->attachments as $value){
					if ($value['embedded']){
						$this->mail_body .= "--" . $this->boundary_rel . "\n";
						$this->mail_body .= "Content-ID: <" . $value['embedded'] . ">" . "\n";
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
						$this->mail_body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
						$this->mail_body .= $value['content'] . "\n" . "\n";
					}
				}
				$this->mail_body .= "--" . $this->boundary_rel . "--" . "\n" . "\n";
				foreach($this->attachments as $value){
					if (!$value['embedded']){
						$this->mail_body .= "--" . $this->boundary_mix . "\n";
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . "\n";
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . "\n";
						$this->mail_body .= "Content-Transfer-Encoding: base64" . "\n" . "\n";
						$this->mail_body .= $value['content'] . "\n" . "\n";
					}
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . "\n";
				break;
			default:
				return false;
		}
		$this->sended_index++;
		return true;
	}
	
	final protected function build_header($content_type){
		$this->header .= 'From: '.$this->from."\n";
		$this->header .= (!empty($this->mail_reply_to)) ? 'Reply-To: '.$this->mail_reply_to."\n" : 'Reply-To: '. $this->from."\n";
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
		$this->mail_type = 0;
		if ($this->attachments_index != 0) {
			foreach($this->attachments as $key => $value) {
				if (preg_match('/(css|image)/i', $value['type']) && preg_match('/\s(background|href|src)\s*=\s*[\"|\']('.$value['name'].')[\"|\'].*>/is', $this->mail_html)) {
					$img_id = md5($value['name']).".nxs@mimemail";
					$this->mail_html = preg_replace('/\s(background|href|src)\s*=\s*[\"|\']('.$value['name'].')[\"|\']/is', ' \\1="cid:'.$img_id.'"', $this->mail_html);
					$this->attachments[$key]['embedded'] = $img_id;
					$this->attachments_img[] = $value['name'];
				}
			}
		}
		if (!empty($this->mail_text)){
			$this->mail_type ++;
		}
		if (!empty($this->mail_html)){
			$this->mail_type += 2;
			if (empty($this->mail_text)){
				$this->mail_text = strip_tags(eregi_replace("<br>", "\n", $this->mail_html));
				$this->mail_type ++;
			}
		}
		if ($this->attachments_index != 0){
			if (count($this->attachments_img) != 0){
				$this->mail_type += 8;
			}
			if ((count($this->attachments) - count($this->attachments_img)) >= 1){
				$this->mail_type += 4;
			}
		}
		return $this->mail_type;
	}
	
	final protected function validate_mail($mail){
		if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',$mail)) {
			return true;
		}
		return false;
}
?>