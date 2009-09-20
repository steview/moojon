<?php
final class car_users_controller extends moojon_base_controller {
	public function index() {
		$this->car_users = car_user::read();
	}
	
	public function show() {
		$this->car_user = car_user::read_by_id(moojon_uri::key('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->car_user = car_user::create();
	}
	
	public function create() {
		$this->car_user = car_user::create(moojon_post::key('car_user'));
		if ($this->car_user->save()) {
			moojon_flash::set('notification', $this->car_user." created");
			$this->redirect(car_user_uri($this->car_user));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->car_user = car_user::read_by_id(moojon_uri::key('id'));
	}
	
	public function update() {
		$columns = moojon_post::key('car_user');
		$this->car_user = car_user::read_by_id($columns['id']);
		$this->car_user->set($columns);
		if ($this->car_user->save()) {
			moojon_flash::set('notification', $this->car_user." updated");
			$this->redirect(car_user_uri($this->car_user));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->car_user = car_user::read_by_id(moojon_uri::key('id'));
	}
	
	public function destroy() {
		$car_user = car_user::read_by_id(moojon_uri::key('id'));
		$car_user->delete();
		moojon_flash::set('notification', "$car_user deleted");
		$this->redirect(car_users_uri());
	}
}
?>