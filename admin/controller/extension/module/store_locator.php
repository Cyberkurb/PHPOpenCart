<?php
class ControllerExtensionModuleStoreLocator extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/store_locator');

		$this->document->setTitle($this->language->get('heading_title')); 
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_store_locator', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


		if (isset($this->error['google_api_key'])) {
			$data['error_google_api_key'] = $this->error['google_api_key'];
		} else {
			$data['error_google_api_key'] = '';
		}

		if (isset($this->error['google_radius'])) {
			$data['error_google_radius'] = $this->error['google_radius'];
		} else {
			$data['error_google_radius'] = '';
		}	

		if (isset($this->error['store_name'])) {
			$data['error_store_name'] = $this->error['store_name'];
		} else {
			$data['error_store_name'] = '';
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
			'href' => $this->url->link('extension/module/store_locator', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/store_locator', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
 

	    if (isset($this->request->post['module_store_locator_status'])) {
	      $data['module_store_locator_status'] = $this->request->post['module_store_locator_status'];
	    } else {
	      $data['module_store_locator_status'] = $this->config->get('module_store_locator_status');
	    }

		if (isset($this->request->post['module_store_locator_google_api_key'])) {
			$data['module_store_locator_google_api_key'] = $this->request->post['module_store_locator_google_api_key'];
		} else {
			$data['module_store_locator_google_api_key'] = $this->config->get('module_store_locator_google_api_key');
		}

		if (isset($this->request->post['module_store_locator_radius'])) {
			$data['module_store_locator_radius'] = $this->request->post['module_store_locator_radius'];
		} elseif($this->config->get('module_store_locator_radius')) {
			$data['module_store_locator_radius'] = $this->config->get('module_store_locator_radius');
		}else {
			$data['module_store_locator_radius'] = $this->language->get('store_locator_default_radius');
		}

		if (isset($this->request->post['module_store_locator_not_avail_text'])) {
			$data['module_store_locator_not_avail_text'] = $this->request->post['module_store_locator_not_avail_text'];
		} elseif($this->config->get('module_store_locator_not_avail_text')) {
			$data['module_store_locator_not_avail_text'] = $this->config->get('module_store_locator_not_avail_text');
		}else {
			$data['module_store_locator_not_avail_text'] = $this->language->get('store_locator_default_no_store_avail');
		}

		if (isset($this->request->post['module_store_locator_name'])) {
			$data['module_store_locator_name'] = $this->request->post['module_store_locator_name'];
		} else {
			$data['module_store_locator_name'] = $this->config->get('module_store_locator_name');
		}		
 

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/store_locator', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/store_locator')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		if (!$this->request->post['module_store_locator_google_api_key']) {
			$this->error['google_api_key'] = $this->language->get('error_google_api_key');
		}

		if (!$this->request->post['module_store_locator_radius']) {
			$this->error['google_radius'] = $this->language->get('error_google_radius');
		}	

		if (!$this->request->post['module_store_locator_name']) {
			$this->error['store_name'] = $this->language->get('error_store_name');
		}		

		return !$this->error;
	}
}