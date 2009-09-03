<?php
final class firsts_controller extends moojon_base_controller {
	public function index() {
		$this->firsts = first::read();
	}
	
	public function create() {
		//$this->first = first::read(moojon_uri::get('id'));
	}
	
	public function read() {
		$this->first = first::read(moojon_uri::get('id'));
	}
	
	public function update() {
		$this->first = first::read(moojon_uri::get('id'));
	}
	
	public function destroy() {
		$this->first = first::read(moojon_uri::get('id'));
		if (moojon_server::is_delete()) {
			$this->first->destroy();
		}
	}
	
	public function save() {
		$this->first = first::read(moojon_uri::get('id'));
		$this->first->update(moojon_post::key_or_null('first'));
		if (!$this->first->save()) {
			if (moojon_server::is_post()) {
				$this->redirect('http://testing.dev:8888/index.php/create');
			} else {
				$this->redirect('http://testing.dev:8888/index.php/'.moojon_uri::get('id').'/edit');
			}
		}
	}
}
?>