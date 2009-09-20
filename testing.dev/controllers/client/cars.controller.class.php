<?php
final class cars_controller extends moojon_base_controller {
	public function index() {
		$this->cars = car::read();
	}
	
	public function show() {
		$this->car = car::read_by_id(moojon_uri::key('id'));
	}
	
	public function _new() {
		$this->view = 'new';
		$this->car = car::create();
	}
	
	public function create() {
		$this->car = car::create(moojon_post::key('car'));
		if ($this->car->save()) {
			moojon_flash::set('notification', $this->car." created");
			$this->redirect(car_uri($this->car));
		} else {
			$this->view = 'new';
		}
	}
	
	public function edit() {
		$this->car = car::read_by_id(moojon_uri::key('id'));
	}
	
	public function update() {
		$columns = moojon_post::key('car');
		$this->car = car::read_by_id($columns['id']);
		$this->car->set($columns);
		if ($this->car->save()) {
			moojon_flash::set('notification', $this->car." updated");
			$this->redirect(car_uri($this->car));
		} else {
			$this->view = 'edit';
		}
	}
	
	public function delete() {
		$this->car = car::read_by_id(moojon_uri::key('id'));
	}
	
	public function destroy() {
		$car = car::read_by_id(moojon_uri::key('id'));
		$car->delete();
		moojon_flash::set('notification', "$car deleted");
		$this->redirect(cars_uri());
	}
}
?>