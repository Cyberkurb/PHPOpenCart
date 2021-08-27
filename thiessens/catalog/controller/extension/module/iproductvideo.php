<?php  
class ControllerExtensionModuleiProductVideo extends Controller {

	public function __construct($registry) {
		parent::__construct($registry);
	}

	public function controllerProductProductBefore(&$route, &$data) {
		$this->document->addScript('system/library/vendor/isenselabs/iproductvideo/iproductvideo.js?v=5.3.2');
        $this->document->addStyle('system/library/vendor/isenselabs/iproductvideo/iproductvideo.css?v=5.3.2');
	}

	public function viewProductProductBefore(&$route, &$data, &$template) {
		$this->load->model('extension/module/iproductvideo');
		$data = $this->model_extension_module_iproductvideo->init((int)$data['product_id'], $data);
	}
}
?>
