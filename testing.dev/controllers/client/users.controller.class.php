<?php
final class users_controller extends moojon_base_controller {
	public function index() {
		$this->users = user::read();
	}
	
	public function show() {
		$this->user = user::read_by_id(moojon_uri::get('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->user = user::create();
	}
	
	public function create() {
		$this->user = user::create(moojon_request::get('user'));
		if ($this->user->save()) {
			moojon_files::move_uploaded_files(moojon_paths::get_column_upload_paths($this->user), moojon_files::get('user'));
			moojon_flash::set('notification', $this->user." created");
			$this->redirect(user_uri($this->user));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->user = user::read_by_id(moojon_uri::get('id'));
	}
	
	public function update() {
		$columns = moojon_request::get('user');
		$this->user = user::read_by_id($columns['id']);
		$this->user->set($columns);
		if ($this->user->save()) {
			foreach (moojon_paths::get_column_upload_paths($this->user) as $key => $value) {
				//if (moojon_request::has("clear_$key"))
			}
			moojon_files::move_uploaded_files(moojon_paths::get_column_upload_paths($this->user), moojon_files::get('user'));
			moojon_flash::set('notification', $this->user." updated");
			$this->redirect(user_uri($this->user));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->user = user::read_by_id(moojon_uri::get('id'));
	}
	
	public function destroy() {
		$user = user::read_by_id(moojon_uri::get('id'));
		$user->delete();
		moojon_flash::set('notification', "$user deleted");
		$this->redirect(users_uri());
	}
}
?>