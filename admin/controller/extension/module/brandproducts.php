<?php
class ControllerExtensionModuleBrandproducts extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/brandproducts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_brandproducts', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/brandproducts', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/brandproducts', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_brandproducts_status'])) {
			$data['module_brandproducts_status'] = $this->request->post['module_brandproducts_status'];
		} else {
			$data['module_brandproducts_status'] = $this->config->get('module_brandproducts_status');
		}
		
		if (isset($this->request->post['module_brandproducts_width'])) {
			$data['module_brandproducts_width'] = $this->request->post['module_brandproducts_width'];
		} else if($this->config->get('module_brandproducts_width')){
			$data['module_brandproducts_width'] = $this->config->get('module_brandproducts_width');
		} else
		{
			$data['module_brandproducts_width'] = 100;
		}
		
		if (isset($this->request->post['module_brandproducts_height'])) {
			$data['module_brandproducts_height'] = $this->request->post['module_brandproducts_height'];
		} else if($this->config->get('module_brandproducts_height')){
			$data['module_brandproducts_height'] = $this->config->get('module_brandproducts_height');
		} else
		{
			$data['module_brandproducts_height'] = 100;
		}
		
		if (isset($this->request->post['module_brandproducts_limit'])) {
			$data['module_brandproducts_limit'] = $this->request->post['module_brandproducts_limit'];
		} else if($this->config->get('module_brandproducts_limit')){
			$data['module_brandproducts_limit'] = $this->config->get('module_brandproducts_limit');
		} else {
			$data['module_brandproducts_limit'] = 5;
		}
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/brandproducts', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/brandproducts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
