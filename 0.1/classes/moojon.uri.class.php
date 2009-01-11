<?php
class moojon_uri {
	public $app;
	public $controller;
	public $action;
	public $querystring;
	
	function __construct($config) {
		$this->process($_SERVER['PATH_INFO'], $config);
	}
	
	public function process($uri = "", $config) {
		$uri = explode('/', $uri);
		while (empty($uri[(count($uri) - 1)])) {
			array_pop($uri);
			if (!count($uri)) {
				$uri[] = "";
				break;
			}
		}
		switch(count($uri)) {
			case 1:
				$homepage = explode('/', $config->homepage);
				switch(count($homepage)) {
					case 1:
						$this->app = $config->default_app;
						$this->controller = $homepage[0];
						$this->action = $config->default_action;
						break;
					case 2:
						$this->app = $config->default_app;
						$this->controller = $homepage[0];
						$this->action = $homepage[1];
						break;
					case 3:
						$this->set_all_parts($homepage);
						break;
					default:
						if (count($homepage) > 0) {
							$this->querystring = $this->set_all_parts($homepage);
						} else {
							echo '<p><em>Throwing error!!!</em></p>';
						}
						break;
				}
				break;
			case 2:
				$this->app = $config->default_app;
				$this->controller = $uri[1];
				$this->action = $config->default_action;
				break;
			case 3:
				$this->app = $config->default_app;
				$this->controller = $uri[1];
				$this->action = $uri[2];
				break;
			case 4:
				$this->app = $uri[1];
				$this->controller = $uri[2];
				$this->action = $uri[3];
				break;
			default:
				if (count($uri) > 0) {
					$this->querystring = $this->set_all_parts($uri);
				} else {
					echo '<p><em>throwing error!!!</em></p>';
				}
				break;
		}
		$this->process_mapped_querystring();
	}
	
	private function set_all_parts($uri) {
		array_shift($uri);
		$this->app = array_shift($uri);
		$this->controller = array_shift($uri);
		$this->action = array_shift($uri);
		return $uri;
	}
	
	private function process_mapped_querystring() {
		$querystring = array();
		if (count($this->querystring) % 2 != 0) {
			$this->querystring[] = null;
		}
		for ($i = 0; $i < count($this->querystring); $i += 2) {
			$querystring[$this->querystring[$i]] = $this->querystring[($i + 1)];
		}
		$this->querystring = $querystring;
	}
}
?>