<?php
class Thiessens_ControllerCommonHeader extends ControllerCommonHeader {
	
	public function index() {
		
		// add fontawesome to the admin as well
		// changing the navigation arrows on frontend also reflected on the backend
		$this->document->addStyle('/admin/view/stylesheet/custom.css');
		
		return parent::index();
	}
}