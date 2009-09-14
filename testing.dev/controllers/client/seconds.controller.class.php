<?php
final class seconds_controller extends moojon_base_controller {
	public function index() {
		$this->seconds = second::read();
	}
	
	public function show() {
		$this->second = second::read_by_id(moojon_uri::key('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->second = second::create();
	}
	
	public function create() {
		$this->second = second::create(moojon_post::key('second'));
		if ($this->second->save()) {
			moojon_flash::set('notification', $this->second." created");
			$this->redirect(second_uri($this->second));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->second = second::read_by_id(moojon_uri::key('id'));
	}
	
	public function update() {
		$columns = moojon_post::key('second');
		$this->second = second::read_by_id($columns['id']);
		$this->second->set($columns);
		if ($this->second->save()) {
			moojon_flash::set('notification', $this->second." updated");
			$this->redirect(second_uri($this->second));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->second = second::read_by_id(moojon_uri::key('id'));
	}
	
	public function destroy() {
		$second = second::read_by_id(moojon_uri::key('id'));
		$second->delete();
		moojon_flash::set('notification', "$second deleted");
		$this->redirect(seconds_uri());
	}
}
?>