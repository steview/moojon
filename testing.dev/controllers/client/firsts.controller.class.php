<?php
final class firsts_controller extends moojon_base_controller {
	public function index() {
		$this->firsts = first::read();
	}
	
	public function create() {
		$this->first = first::create($_POST['first']);
		if (moojon_server::is_post()) {
			if ($_POST['submit_button'] == 'Create') {
				if ($this->first->save()) {
					$this->redirect('firsts');
				}
			} elseif ($_POST['submit_button'] == 'Cancel') {
				$this->redirect('firsts');
			}
		}
	}
	
	public function read() {
		$this->read_from_id();
	}
	
	public function update() {
		$this->read_from_id();
		if (moojon_server::is_post()) {
			if ($_POST['submit_button'] == 'Update') {
				$this->first->set($_POST['first']);
				if ($this->first->save()) {
					$this->redirect('firsts/read/id/'.$this->get_id());
				}
			}
			if ($_POST['submit_button'] == 'Cancel') {
				$this->redirect('firsts/read/id/'.$this->get_id());
			}
		}
	}
	
	public function destroy() {
		if (moojon_server::is_post()) {
			if ($_POST['submit_button'] == 'Destroy') {
				first::destroy('id = '.$this->get_id());
				$this->redirect('firsts');
			} elseif ($_POST['submit_button'] == 'Cancel') {
				$this->redirect('firsts/read/id/'.$this->get_id());
			} else {
				$this->redirect('firsts');
			}
		}
		$this->read_from_id();
	}
	
	private function read_from_id() {
		$this->first = first::read('id = '.$this->get_id())->first;
	}
	
	private function get_id() {
		$first = $_POST['first'];
		if ($first[moojon_primary_key::NAME]) {
			return $first[moojon_primary_key::NAME];
		} else {
			return moojon_uri::get(moojon_primary_key::NAME);
		}
	}
}
?>