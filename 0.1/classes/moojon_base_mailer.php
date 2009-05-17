<?php
abstract class moojon_base_mailer extends moojon_base {
	private $recipients;
	private $from;
	private $subject;
	private $body;
	private $charset = "ISO-8859-1";
	private $mail_subject = "No subject";
	private $mail_from = "Anonymous <fake@mail.com>";
	private $mail_to;
	private $mail_cc;
	private $mail_bcc;
	private $mail_text;
	private $mail_html;
	private $mail_type;
	private $mail_header;
	private $mail_body;
	private $mail_reply_to;
	private $mail_return_path;
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
		if(!defined('BR')){
			define('BR', "\n");
		}
	}
	
	final protected function set_recipients($recipients) {}
	
	final protected function set_subject($subject) {}
	
	final protected function set_body($body) {}
	
	final protected function set_from($mail_from, $name = ""){
		if ($this->validate_mail($mail_from)){
			$this->mail_from = !empty($name) ? "$name <$mail_from>" : $mail_from;
		}
		else {
			$this->mail_from = "Anonymous <fake@mail.com>";
		}
	}
	
	final protected function set_to($mail_to, $name = ""){
		if ($this->validate_mail($mail_to)){
			$this->mail_to = !empty($name) ? "$name <$mail_to>" : $mail_to;
			return true;
		}
		return false;
	}
	
	final protected function set_cc($mail_cc, $name = ""){
		if ($this->validate_mail($mail_cc)){
			$this->mail_cc = !empty($name) ? "$name <$mail_cc>" : $mail_cc;
			return true;
		}
		return false;
	}
	
	final protected function set_bcc($mail_bcc, $name = ""){
		if ($this->validate_mail($mail_bcc)){
			$this->mail_bcc = !empty($name) ? "$name <$mail_bcc>" : $mail_bcc;
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
	
	final protected function add_cc($mail_cc, $name = ""){
		if ($this->validate_mail($mail_cc)){
			$mail_cc = !empty($name) ? "$name <$mail_cc>" : $mail_cc;
			$this->mail_cc = !empty($this->mail_cc) ? $this->mail_cc . ", " . $mail_cc : $mail_cc;
			return true;
		}
		return false;
	}
	
	final protected function add_bcc($mail_bcc, $name = ""){
		if ($this->validate_mail($mail_bcc)){
			$mail_bcc = !empty($name) ? "$name <$mail_bcc>" : $mail_bcc;
			$this->mail_bcc = !empty($this->mail_bcc) ? $this->mail_bcc . ", " . $mail_bcc : $mail_bcc;
			return true;
		}
		return false;
	}
	
	final protected function set_reply_to($mail_reply_to, $name = ""){
		if ($this->validate_mail($mail_reply_to)){
			$this->mail_reply_to = !empty($name) ? "$name <$mail_reply_to>" : $mail_reply_to;
			return true;
		}
		return false;
	}
	
	final protected function set_return_path($mail_return_path){
		if ($this->validate_mail($mail_return_path)){
			$this->mail_return_path = $mail_return_path;
			return true;
		}
		return false;
	}
	
	final protected function set_subject($subject){
		$this->mail_subject = !empty($subject) ? $subject : "No subject";
	}
	
	final protected function set_text($text){
		if (!empty($text)){
			$this->mail_text = $text;
		}
	}
	
	final protected function set_html($html){
		if (!empty($html)){
			$this->mail_html = $html;
		}
	}
	
	final protected function add_attachment($name, $path){
		$attachment = array(
			'name' => $name,
			'type' => shell_exec('file -bi '.escapeshellarg($path));
			'content' => chunk_split(base64_encode(moojon_path::get_file_contents($path)), 76, BR),
			'embedded' => false
		);
		$this->attachments[] = $attachment;
	}
	
	final protected function send(){
		if ($this->sended_index == 0 && !$this->build_body()){
			return false;
    	}
		if (!empty($this->mail_return_path)){
			return mail($this->mail_to, $this->mail_subject, $this->mail_body, $this->mail_header, '-f'.$this->mail_return_path);
		} else {
			return mail($this->mail_to, $this->mail_subject, $this->mail_body, $this->mail_header);
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
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_html . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . "--" . BR;
				break;
			case 5:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				foreach($this->attachments as $value){
					$this->mail_body .= "--" . $this->boundary_mix . BR;
					$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
					$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
					$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
					$this->mail_body .= $value['content'] . BR . BR;
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . BR;
				break;
			case 7:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . BR;
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_html . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . "--" . BR . BR;
				foreach($this->attachments as $value){
					$this->mail_body .= "--" . $this->boundary_mix . BR;
					$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
					$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
					$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
					$this->mail_body .= $value['content'] . BR . BR;
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . BR;
				break;
			case 11:
				$this->build_header("Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->boundary_rel\"");
				$this->mail_body .= "--" . $this->boundary_rel . BR;
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_html . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . "--" . BR . BR;
				foreach($this->attachments as $value){
					if ($value['embedded']){
						$this->mail_body .= "--" . $this->boundary_rel . BR;
						$this->mail_body .= "Content-ID: <" . $value['embedded'] . ">" . BR;
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
						$this->mail_body .= $value['content'] . BR . BR;
					}
				}
				$this->mail_body .= "--" . $this->boundary_rel . "--" . BR;
				break;
			case 15:
				$this->build_header("Content-Type: multipart/mixed; boundary=\"$this->boundary_mix\"");
				$this->mail_body .= "--" . $this->boundary_mix . BR;
				$this->mail_body .= "Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->boundary_rel\"" . BR . BR;
				$this->mail_body .= "--" . $this->boundary_rel . BR;
				$this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->boundary_alt\"" . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/plain" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_text . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . BR;
				$this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . BR;
				$this->mail_body .= "Content-Transfer-Encoding: 7bit" . BR . BR;
				$this->mail_body .= $this->mail_html . BR . BR;
				$this->mail_body .= "--" . $this->boundary_alt . "--" . BR . BR;
				foreach($this->attachments as $value){
					if ($value['embedded']){
						$this->mail_body .= "--" . $this->boundary_rel . BR;
						$this->mail_body .= "Content-ID: <" . $value['embedded'] . ">" . BR;
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
						$this->mail_body .= $value['content'] . BR . BR;
					}
				}
				$this->mail_body .= "--" . $this->boundary_rel . "--" . BR . BR;
				foreach($this->attachments as $value){
					if (!$value['embedded']){
						$this->mail_body .= "--" . $this->boundary_mix . BR;
						$this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . BR;
						$this->mail_body .= "Content-Transfer-Encoding: base64" . BR . BR;
						$this->mail_body .= $value['content'] . BR . BR;
					}
				}
				$this->mail_body .= "--" . $this->boundary_mix . "--" . BR;
				break;
			default:
				return false;
		}
		$this->sended_index++;
		return true;
	}
	
	final protected function build_header($content_type){
		if (!empty($this->mail_from)){
			$this->mail_header .= "From: " . $this->mail_from . BR;
			$this->mail_header .= !empty($this->mail_reply_to) ? "Reply-To: " . $this->mail_reply_to . BR : "Reply-To: " . $this->mail_from . BR;
		}
		if (!empty($this->mail_cc)){
			$this->mail_header .= "Cc: " . $this->mail_cc . BR;
		}
		if (!empty($this->mail_bcc)){
			$this->mail_header .= "Bcc: " . $this->mail_bcc . BR;
		}
		if (!empty($this->mail_return_path)){
			$this->mail_header .= "Return-Path: " . $this->mail_return_path . BR;
		}
		$this->mail_header .= "MIME-Version: 1.0" . BR;
		$this->mail_header .= "X-Mailer: neXus MIME Mail - PHP/". phpversion() . BR;
		$this->mail_header .= $content_type;
	}
	
	final protected function parse_elements(){
		if (empty($this->mail_to)){
			return false;
		}
		$this->mail_type = 0;
		$this->search_images();
		if (!empty($this->mail_text)){
			$this->mail_type = $this->mail_type + 1;
		}
		if (!empty($this->mail_html)){
			$this->mail_type = $this->mail_type + 2;
			if (empty($this->mail_text)){
				$this->mail_text = strip_tags(eregi_replace("<br>", BR, $this->mail_html));
				$this->mail_type = $this->mail_type + 1;
			}
		}
		if ($this->attachments_index != 0){
			if (count($this->attachments_img) != 0){
				$this->mail_type = $this->mail_type + 8;
			}
			if ((count($this->attachments) - count($this->attachments_img)) >= 1){
				$this->mail_type = $this->mail_type + 4;
			}
		}
		return $this->mail_type;
	}
	
	final protected function search_images(){
		if ($this->attachments_index != 0){
			foreach($this->attachments as $key => $value){
				if (preg_match('/(css|image)/i', $value['type']) && preg_match('/\s(background|href|src)\s*=\s*[\"|\'](' . $value['name'] . ')[\"|\'].*>/is', $this->mail_html)) {
					$img_id = md5($value['name']) . ".nxs@mimemail";
					$this->mail_html = preg_replace('/\s(background|href|src)\s*=\s*[\"|\'](' . $value['name'] . ')[\"|\']/is', ' \\1="cid:' . $img_id . '"', $this->mail_html);
					$this->attachments[$key]['embedded'] = $img_id;
					$this->attachments_img[] = $value['name'];
				}
			}
		}
	}
	
	final protected function validate_mail($mail){
		if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',$mail)){
			return true;
		}
		return false;
}
?>