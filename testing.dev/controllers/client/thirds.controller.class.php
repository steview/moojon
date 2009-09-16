<?php
final class thirds_controller extends moojon_base_controller {
	public function index() {
		$this->thirds = third::read();
	}
	
	public function show() {
		$this->third = third::read_by_id(moojon_uri::key('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->third = third::create();
	}
	
	public function create() {
		$this->third = third::create(moojon_post::key('third'));
		if ($this->third->save()) {
			moojon_flash::set('notification', $this->third." created");
			$this->redirect(third_uri($this->third));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->third = third::read_by_id(moojon_uri::key('id'));
	}
	
	public function update() {
		$columns = moojon_post::key('third');
		$this->third = third::read_by_id($columns['id']);
		$this->third->set($columns);
		if ($this->third->save()) {
			moojon_flash::set('notification', $this->third." updated");
			$this->redirect(third_uri($this->third));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->third = third::read_by_id(moojon_uri::key('id'));
	}
	
	public function destroy() {
		$third = third::read_by_id(moojon_uri::key('id'));
		$third->delete();
		moojon_flash::set('notification', "$third deleted");
		$this->redirect(thirds_uri());
	}
}
?>