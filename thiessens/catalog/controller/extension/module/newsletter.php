<?php
class ControllerExtensionModuleNewsletter extends Controller {
	
	public function index($setting) {
		
		static $module = 0;
		
		$this->load->language('extension/module/newsletter');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/manufacturer');

		$this->load->model('tool/image');
		
		if ($setting['name']) {
			$data['heading_title'] = $setting['title'];
		}
		
		$data = array_merge($data, $setting);
		
		return $this->load->view('extension/module/newsletter', $data);
	}
}