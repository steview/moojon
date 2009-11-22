<?php
final class posts_controller extends moojon_base_controller {
	public function index() {
		$this->posts = post::read();
	}
	
	public function show() {
		$this->post = post::read_by_id(moojon_uri::get('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->post = post::create();
	}
	
	public function create() {
		$this->post = post::create(moojon_post::get('post'));
		if ($this->post->save()) {
			moojon_flash::set('notification', $this->post." created");
			$this->redirect(post_uri($this->post));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->post = post::read_by_id(moojon_uri::get('id'));
	}
	
	public function update() {
		$columns = moojon_post::get('post');
		$this->post = post::read_by_id($columns['id']);
		$this->post->set($columns);
		if ($this->post->save()) {
			moojon_flash::set('notification', $this->post." updated");
			$this->redirect(post_uri($this->post));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->post = post::read_by_id(moojon_uri::get('id'));
	}
	
	public function destroy() {
		$post = post::read_by_id(moojon_uri::get('id'));
		$post->delete();
		moojon_flash::set('notification', "$post deleted");
		$this->redirect(posts_uri());
	}
}
?>