<?php
final class firsts_controller extends moojon_base_controller {
	public function index() {
		$this->firsts = first::read();
	}
	
	public function show() {
		$this->first = first::read_by_id(moojon_uri::key('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->first = first::create();
	}
	
	public function create() {
		$this->first = first::create(moojon_post::key('first'));
		if ($this->first->save()) {
			moojon_flash::set('notification', $this->first." created");
			$this->redirect(first_uri($this->first));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->first = first::read_by_id(moojon_uri::key('id'));
	}
	
	public function update() {
		$columns = moojon_post::key('first');
		$this->first = first::read_by_id($columns['id']);
		$this->first->set($columns);
		if ($this->first->save()) {
			moojon_flash::set('notification', $this->first." updated");
			$this->redirect(first_uri($this->first));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->first = first::read_by_id(moojon_uri::key('id'));
	}
	
	public function destroy() {
		$first = first::read_by_id(moojon_uri::key('id'));
		$first->delete();
		moojon_flash::set('notification', "$first deleted");
		$this->redirect(firsts_uri());
	}
}
?>