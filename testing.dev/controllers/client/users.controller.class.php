<?php
final class users_controller extends moojon_base_controller {
	public function index() {
		$this->users = user::read();
	}
	
	public function show() {
		$this->user = user::read_by_id(moojon_uri::key('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->user = user::create();
	}
	
	public function create() {
		$this->user = user::create(moojon_post::key('user'));
		if ($this->user->save()) {
			moojon_flash::set('notification', $this->user." created");
			$this->redirect(user_uri($this->user));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->user = user::read_by_id(moojon_uri::key('id'));
	}
	
	public function update() {
		$columns = moojon_post::key('user');
		$this->user = user::read_by_id($columns['id']);
		$this->user->set($columns);
		if ($this->user->save()) {
			moojon_flash::set('notification', $this->user." updated");
			$this->redirect(user_uri($this->user));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->user = user::read_by_id(moojon_uri::key('id'));
	}
	
	public function destroy() {
		$user = user::read_by_id(moojon_uri::key('id'));
		$user->delete();
		moojon_flash::set('notification', "$user deleted");
		$this->redirect(users_uri());
	}
}
?>