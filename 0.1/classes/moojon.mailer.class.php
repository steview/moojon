<?php
abstract class moojon_mailer extends moojon_base {
	private $mix;
	private $rel;
	private $alt;
	private $charset;
	private $subject;
	private $name;
	private $email;
	private $from;
	private $to;
	private $cc;
	private $bcc;
	private $text;
	private $html;
	private $type;
	private $reply_to;
	private $return_path;
	private $attachments = array();
	private $attachments_img = array();
	
	final public function __construct() {
		$this->mix = "=-moojon_mix_" . md5(uniqid(rand()));
		$this->rel = "=-moojon_rel_" . md5(uniqid(rand()));
		$this->alt = "=-moojon_alt_" . md5(uniqid(rand()));
		$this->charset = moojon_config::get('charset');
		$this->subject = moojon_config::get('mail_subject');
		$this->email = moojon_config::get('mail_from_email');
		$this->name = moojon_config::get('mail_from_name');
		$this->from = $this->name.' <'.$this->email.'>';
	}
	
	final static public function send_view() {}
	
	final static public function send_text() {}
	
	final static public function send_html() {}
	
	final protected function set_from($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->from = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_to($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->to = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_cc($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->cc = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_bcc($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->bcc = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_reply_to($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->reply_to = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_return_path($email) {
		if ($this->validate_email($email) == true) {
			$this->return_path = $email;
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function add_to($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			if (empty($this->to) == true) {
				$this->to = "$name <$email>";;
			} else {
				$this->to .= "$name <$email>";
			}
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function add_cc($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			if (empty($this->cc) == true) {
				$this->cc = "$name <$email>";;
			} else {
				$this->cc .= "$name <$email>";
			}
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function add_bcc($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			if (empty($this->bcc) == true) {
				$this->bcc = "$name <$email>";;
			} else {
				$this->bcc .= "$name <$email>";
			}
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	final protected function set_subject($subject) {
		$this->subject = $subject;
	}
	
	final protected function set_text($text) {
		$this->text = $text;
	}
	
	final protected function set_html($html) {
		$this->html = $html;
	}
	
	final protected function add_attachment($name, $path) {
		$attachment = array(
			'name' => $name,
			'type' => shell_exec('file -bi '.escapeshellarg($path)),
			'content' => chunk_split(base64_encode(moojon_path::get_file_contents($path)), 76, "\n"),
			'embedded' => false
		);
		$this->attachments[] = $attachment;
	}
	
	final protected function send() {
		$header = $this->build_header();
		$body = $this->build_body();
		if (empty($this->return_path) == false) {
			$return = mail($this->to, $this->subject, $body, $header, '-f'.$this->return_path);
		} else {
			$return = mail($this->to, $this->subject, $body, $header);
		}
		if ($return == true) {
			$this->charset = '';
			$this->subject = '';
			$this->name = '';
			$this->email = '';
			$this->from = '';
			$this->to = '';
			$this->cc = '';
			$this->bcc = '';
			$this->text = '';
			$this->html = '';
			$this->type = '';
			$this->reply_to = '';
			$this->return_path = '';
			$this->attachments = array();
			$this->attachments_img = array();
		}
		return $return;
	}
	
	final protected function build_body() {
		$body = '';
		$elements = $this->parse_elements();
		switch ($elements) {
			case 1:
				$this->build_header('Content-Type: text/plain');
				$body = $this->text;
				break;
			case 3:
				$this->build_header('Content-Type: multipart/alternative; boundary=\"'.$this->alt.'\"');
				$body .= '--'.$this->alt."\n";
				$body .= "Content-Type: text/plain\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->text."\n\n";
				$body .= '--'.$this->alt."\n";
				$body .= 'Content-Type: text/html; charset="'.$this->charset."\"\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->html."\n\n";
				$body .= '--'.$this->alt."--\n";
				break;
			case 5:
				$this->build_header('Content-Type: multipart/mixed; boundary="'.$this->mix."\"");
				$body .= "--".$this->mix."\n";
				$body .= "Content-Type: text/plain\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->text."\n\n";
				foreach ($this->attachments as $value) {
					$body .= '--'.$this->mix."\n";
					$body .= 'Content-Type: '.$value['type'].'; name="'.$value['name']."\"\n";
					$body .= 'Content-Disposition: attachment; filename="'.$value['name']."\"\n";
					$body .= "Content-Transfer-Encoding: base64\n\n";
					$body .= $value['content']."\n\n";
				}
				$body .= '--'.$this->mix."--\n";
				break;
			case 7:
				$this->build_header('Content-Type: multipart/mixed; boundary="'.$this->mix.'"');
				$body .= '--'.$this->mix."\n";
				$body .= 'Content-Type: multipart/alternative; boundary="'.$this->alt."\n\n";
				$body .= '--'.$this->alt."\n";
				$body .= "Content-Type: text/plain\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->text."\n\n";
				$body .= '--'.$this->alt."\n";
				$body .= 'Content-Type: text/html; charset="'.$this->charset."\"\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->html."\n\n";
				$body .= '--'.$this->alt."--\n\n";
				foreach ($this->attachments as $value) {
					$body .= '--'.$this->mix."\n";
					$body .= 'Content-Type: '.$value['type'].'; name="'.$value['name']."\"\n";
					$body .= 'Content-Disposition: attachment; filename="'.$value['name']."\"\n";
					$body .= "Content-Transfer-Encoding: base64\n\n";
					$body .= $value['content']."\n\n";
				}
				$body .= '--'.$this->mix."--\n";
				break;
			case 11:
				$this->build_header('Content-Type: multipart/related; type="multipart/alternative"; boundary="'.$this->rel.'"');
				$body .= '--'.$this->rel."\n";
				$body .= 'Content-Type: multipart/alternative; boundary="'.$this->alt."\"\n";
				$body .= '--'.$this->alt."\n";
				$body .= "Content-Type: text/plain\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->text."\n\n";
				$body .= '--'.$this->alt."\n";
				$body .= 'Content-Type: text/html; charset="'.$this->charset."\"\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->html."\n\n";
				$body .= '--'.$this->alt."--\n\n";
				foreach ($this->attachments as $value) {
					if ($value['embedded']) {
						$body .= '--'.$this->rel."\n";
						$body .= 'Content-ID: <'.$value['embedded'].">\n";
						$body .= 'Content-Type: '.$value['type'].'; name="'.$value['name']."\"\n";
						$body .= 'Content-Disposition: attachment; filename="'.$value['name']."\"\n";
						$body .= "Content-Transfer-Encoding: base64\n\n";
						$body .= $value['content']."\n\n";
					}
				}
				$this->body .= '--'.$this->rel."--\n";
				break;
			case 15:
				$build_header('Content-Type: multipart/mixed; boundary="'.$this->mix.'"');
				$body .= '--'.$this->mix."\n";
				$body .= 'Content-Type: multipart/related; type="multipart/alternative"; boundary="'.$this->rel."\"\n\n";
				$body .= '--'.$this->rel."\n";
				$body .= 'Content-Type: multipart/alternative; boundary="'.$this->alt."\"\n\n";
				$body .= '--'.$this->alt."\n";
				$body .= "Content-Type: text/plain\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->text."\n\n";
				$body .= '--'.$this->alt."\n";
				$body .= 'Content-Type: text/html; charset="'.$this->charset."\"\n";
				$body .= "Content-Transfer-Encoding: 7bit\n\n";
				$body .= $this->html."\n\n";
				$body .= '--'.$this->alt."--\n\n";
				foreach ($this->attachments as $value) {
					if ($value['embedded']) {
						$body .= '--'.$this->rel."\n";
						$body .= 'Content-ID: <'.$value['embedded'].">\n";
						$body .= 'Content-Type: '.$value['type'].'; name="'.$value['name']."\"\n";
						$body .= 'Content-Disposition: attachment; filename="'.$value['name']."\"\n";
						$body .= "Content-Transfer-Encoding: base64\n\n";
						$body .= $value['content']."\n\n";
					}
				}
				$this->body .= '--'.$this->rel."--\n\n";
				foreach ($this->attachments as $value) {
					if (!$value['embedded']) {
						$body .= '--'.$this->mix."\n";
						$body .= 'Content-Type: '.$value['type'].'; name="'.$value['name']."\"\n";
						$body .= 'Content-Disposition: attachment; filename="'.$value['name']."\"\n";
						$body .= "Content-Transfer-Encoding: base64\n\n";
						$body .= $value['content']."\n\n";
					}
				}
				$body .= '--'.$this->mix."--\n";
				break;
			default:
				throw new moojon_exception('Unknown body type ('.$elements.')');
				break;
		}
		return $body;
	}
	
	final protected function build_header($content_type) {
		$header = '';
		$header .= 'From: '.$this->from."\n";
		if (empty($this->reply_to) == false) {
			$header .= 'Reply-To: '.$this->reply_to."\n";
		} else {
			$header .= 'Reply-To: '.$this->from."\n";
		}
		if (empty($this->cc) == false) {
			$header .= 'Cc: '.$this->cc."\n";
		}
		if (empty($this->bcc) == false) {
			$header .= 'Bcc: '.$this->bcc."\n";
		}
		if (empty($this->return_path) == false) {
			$header .= 'Return-Path: '.$this->return_path."\n";
		}
		$header .= 'MIME-Version: 1.0'."\n";
		$header .= 'X-Mailer: moojon MIME Mail - PHP/'.phpversion()."\n";
		$header .= $content_type;
		return $header;
	}
	
	final protected function parse_elements() {
		$this->type = 0;
		if (count($this->attachments) > 0) {
			foreach ($this->attachments as $key => $value) {
				if (preg_match('/(css|image)/i', $value['type']) && preg_match('/\s(background|href|src)\s*=\s*[\"|\']('.$value['name'].')[\"|\'].*>/is', $this->html)) {
					$img_id = md5($value['name']).'.moojon@mimemail';
					$this->html = preg_replace('/\s(background|href|src)\s*=\s*[\"|\']('.$value['name'].')[\"|\']/is', ' \\1="cid:'.$img_id.'"', $this->html);
					$this->attachments[$key]['embedded'] = $img_id;
					$this->attachments_img[] = $value['name'];
				}
			}
		}
		if (empty($this->text) == false) {
			$this->type ++;
		}
		if (empty($this->html) == false) {
			$this->type += 2;
			if (empty($this->text)) {
				$this->text = strip_tags(eregi_replace('<br>', "\n", $this->html));
				$this->type ++;
			}
		}
		if (count($this->attachments) > 0) {
			if (count($this->attachments_img) != 0) {
				$this->type += 8;
			}
			if ((count($this->attachments) - count($this->attachments_img)) >= 1) {
				$this->type += 4;
			}
		}
		return $this->type;
	}
	
	final protected function validate_email($email) {
		if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email) == true) {
			return true;
		}
		return false;
	}
}
?>