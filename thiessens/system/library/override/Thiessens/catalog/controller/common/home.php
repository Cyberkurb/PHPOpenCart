<?php
class Thiessens_ControllerCommonHome extends ControllerCommonHome {
	
	public function index() {
		
		$this->load->language('common/home');
		
		parent::index();
	}
}
