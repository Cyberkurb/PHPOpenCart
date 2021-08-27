<?php
class ControllerExtensionModuleButopbanner extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/butopbanner');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
                $this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
                        if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('butopbanner', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}
                        $this->model_setting_setting->editSetting('butopbanner', $this->request->post);
                        
			$this->session->data['success'] = $this->language->get('text_success');

			if(isset($this->request->get['module_id']))
			{
				$this->response->redirect($this->url->link('extension/module/butopbanner', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL'));
			}
			else
			{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'].'&type=module', 'SSL'));
			}
		}
                
                if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_image'] = $this->language->get('entry_image');
                $data['entry_url'] = $this->language->get('entry_url');
                $data['entry_heading'] = $this->language->get('entry_heading');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_status'] = $this->language->get('entry_status');
                $data['entry_name'] = $this->language->get('entry_name');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['tab_module'] = $this->language->get('tab_module');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
                
                if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
                
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'].'&type=module', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/butopbanner', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/butopbanner', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/butopbanner', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'].'&type=module', 'SSL');
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
                
                if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
                
                if (isset($this->request->post['butopbanner_module'])) {
			$modules = $this->request->post['butopbanner_module'];
		} elseif (!empty($module_info)) {
                        $modules = $module_info['butopbanner_module'];
		} else {
			$modules = array();
		}
		
		
		$data['butopbanner_modules'] = array();
		
		foreach ($modules as $key => $module) {
			$data['butopbanner_modules'][] = array(
				'key'         => $key,
				'heading'     => $module['heading'],
				'description' => $module['description'],
                                'slider_image' => $module['slider_image'],
                                'link' => $module['link']
			);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/butopbanner', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/butopbanner')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
                if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 110)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		return !$this->error;
	}
}