<?php
final class moojon_mailer extends moojon_base {
	private $header;
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
	private $reply_to;
	private $return_path;
	private $attachments = array();
	private $attachments_img = array();
	
	public function __construct() {
		$this->mix = "=-moojon_mix_" . md5(uniqid(rand()));
		$this->rel = "=-moojon_rel_" . md5(uniqid(rand()));
		$this->alt = "=-moojon_alt_" . md5(uniqid(rand()));
		$this->charset = moojon_config::key('charset');
		$this->subject = moojon_config::key('mail_subject');
		$this->email = moojon_config::key('mail_from_email');
		$this->name = moojon_config::key('mail_from_name');
		$this->from = $this->name.' <'.$this->email.'>';
	}
	
	private function render_view($action, $controller) {
		$app_class = APP.'_app';
		$return = new $app_class($action, $controller);;
		return $return->render(false);
	}
	
	static public function from_html_view($action, $controller) {
		$instance = new moojon_mailer();
		$instance->set_html(self::render_view($action, $controller));
		return $instance;
	}
	
	static public function from_text_view($action, $controller, $app) {
		$instance = new moojon_mailer();
		$instance->set_text(self::render_view($action, $controller, $app));
		return $instance;
	}
	
	public function set_from($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->from = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	public function set_to($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->to = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	public function set_cc($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->cc = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	public function set_bcc($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->bcc = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	public function set_reply_to($email, $name = '') {
		if ($this->validate_email($email) == true) {
			if (empty($name) == true) {
				$name = $this->name;
			}
			$this->reply_to = "$name <$email>";
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	public function set_return_path($email) {
		if ($this->validate_email($email) == true) {
			$this->return_path = $email;
		} else {
			throw new moojon_exception("Invalid email ($email)");
		}
	}
	
	public function add_to($email, $name = '') {
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
	
	public function add_cc($email, $name = '') {
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
	
	public function add_bcc($email, $name = '') {
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
	
	public function set_subject($subject) {
		$this->subject = $subject;
	}
	
	public function set_text($text) {
		$this->text = $text;
	}
	
	public function set_html($html) {
		$this->html = $html;
	}
	
	public function add_attachment($name, $path) {
		$attachment = array(
			'name' => $name,
			'type' => shell_exec('file -bi '.escapeshellarg($path)),
			'content' => chunk_split(base64_encode(moojon_path::get_file_contents($path)), 76, "\n"),
			'embedded' => false
		);
		$this->attachments[] = $attachment;
	}
	
	public function send() {
		$elements = $this->parse_elements();
		$header = $this->build_header($elements);
		$body = $this->build_body($elements);
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
			$this->reply_to = '';
			$this->return_path = '';
			$this->attachments = array();
			$this->attachments_img = array();
		}
		return $return;
	}
	
	private function build_body($elements) {
		$body = '';
		switch ($elements) {
			case 1:
				$body = $this->text;
				break;
			case 3:
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
	
	private function build_header($elements) {
		switch ($elements) {
			case 1:
				$type = 'Content-Type: text/plain';
				break;
			case 3:
				$type = 'Content-Type: multipart/alternative; boundary="'.$this->alt.'"';
				break;
			case 5:
				$type = 'Content-Type: multipart/mixed; boundary="'.$this->mix.'"';
				break;
			case 7:
				$type = 'Content-Type: multipart/mixed; boundary="'.$this->mix.'"';
				break;
			case 11:
				$type = 'Content-Type: multipart/related; type="multipart/alternative"; boundary="'.$this->rel.'"';
				break;
			case 15:
				$type = 'Content-Type: multipart/mixed; boundary="'.$this->mix.'"';
				break;
			default:
				throw new moojon_exception('Unknown header type ('.$elements.')');
				break;
		}
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
		$header .= $type;
		return $header;
	}
	
	private function parse_elements() {
		$type = 0;
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
			$type ++;
		}
		if (empty($this->html) == false) {
			$type += 2;
			if (empty($this->text)) {
				$this->text = strip_tags(eregi_replace('<br>', "\n", $this->html));
				$type ++;
			}
		}
		if (count($this->attachments) > 0) {
			if (count($this->attachments_img) != 0) {
				$type += 8;
			}
			if ((count($this->attachments) - count($this->attachments_img)) >= 1) {
				$type += 4;
			}
		}
		return $type;
	}
	
	private function validate_email($email) {
		if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email) == true) {
			return true;
		}
		return false;
	}
}
?>