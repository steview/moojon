<?php
final class firsts_controller extends moojon_base_controller {
	public function index() {
		$this->firsts = first::read();
	}
	
	public function show() {
		$this->first = first::read('id = '.moojon_uri::key('id'))->first;
	}
	
	public function _new() {
		$this->view = 'new';
		$this->first = first::create();
	}
	
	public function create() {
		$this->first = first::create(moojon_post::key('first'));
		if (!$this->first->save()) {
			die('redirecting...'.first_uri($this->first));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->first = first::read('id = '.moojon_uri::key('id'))->first;
	}
	
	public function update() {
		$columns = moojon_post::key('first');
		$this->first = first::read('id = '.$columns['id'])->first;
		$this->first->set($columns);
		if ($this->first->save()) {
			die('redirecting...'.first_uri($this->first));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->first = first::read('id = '.moojon_uri::key('id'))->first;
	}
	
	public function destroy() {
		first::destroy('id = '.moojon_uri::key('id'));
		die('redirecting...'.firsts_uri());
	}
}
?>