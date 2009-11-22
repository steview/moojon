<?php
final class comments_controller extends moojon_base_controller {
	public function index() {
		$this->comments = comment::read();
	}
	
	public function show() {
		$this->comment = comment::read_by_id(moojon_uri::get('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->comment = comment::create();
	}
	
	public function create() {
		$this->comment = comment::create(moojon_post::get('comment'));
		if ($this->comment->save()) {
			moojon_flash::set('notification', $this->comment." created");
			$this->redirect(comment_uri($this->comment));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->comment = comment::read_by_id(moojon_uri::get('id'));
	}
	
	public function update() {
		$columns = moojon_post::get('comment');
		$this->comment = comment::read_by_id($columns['id']);
		$this->comment->set($columns);
		if ($this->comment->save()) {
			moojon_flash::set('notification', $this->comment." updated");
			$this->redirect(comment_uri($this->comment));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->comment = comment::read_by_id(moojon_uri::get('id'));
	}
	
	public function destroy() {
		$comment = comment::read_by_id(moojon_uri::get('id'));
		$comment->delete();
		moojon_flash::set('notification', "$comment deleted");
		$this->redirect(comments_uri());
	}
}
?>