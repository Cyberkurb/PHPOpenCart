<?php
class ControllerExtensionDSEOModuleDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $error = array();
		
	/*
	*	Functions for SEO Module.
	*/
	public function menu() {
		$_language = new Language();
		$_language->load($this->route);
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		$menu = array();

		if ($this->user->hasPermission('access', 'extension/module/' . $this->codename)) {
			$menu[] = array(
				'name'	   		=> $_language->get('heading_title_main'),
				'href'     		=> $this->url->link('extension/module/' . $this->codename, $url_token, true),
				'sort_order' 	=> 5,
				'children' 		=> array()
			);
		}

		return $menu;
	}

	public function language_add_language($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_' . $this->codename}->addLanguage($data);
	}
	
	public function language_delete_language($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_' . $this->codename}->deleteLanguage($data);
	}
	
	public function control_extensions() {
		$_language = new Language();
		$_language->load($this->route);
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		$control_extensions = array();

		$control_extensions[] = array(
			'code'				=> $this->codename,
			'name'	   			=> $_language->get('heading_title_main'),
			'image'				=> 'catalog/' . $this->codename . '/logo.svg',
			'href'     			=> $this->url->link('extension/module/' . $this->codename, $url_token, true),
			'sort_order' 		=> 5
		);
				
		return $control_extensions;
	}
	
	public function control_install_extension() {
		$this->load->controller('extension/module/' . $this->codename . '/installExtension');
			
		$json = $this->response->getOutput();
			
		if ($json) {
			$data = json_decode($json, true);
			
			return $data;
		}
		
		return false;
	}
		
	public function control_elements($data) {
		$_language = new Language();
		$_language->load($this->route);
		
		$this->load->model('extension/module/' . $this->codename);
		$this->load->model('setting/setting');
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		// Setting 						
		$setting = $this->model_setting_setting->getSetting('module_' . $this->codename, $data['store_id']);
		$status = isset($setting['module_' . $this->codename . '_status']) ? $setting['module_' . $this->codename . '_status'] : false;
		$setting = isset($setting['module_' . $this->codename . '_setting']) ? $setting['module_' . $this->codename . '_setting'] : array();
		
		$control_elements = array();
		
		if (!$status) {
			$control_elements[] = array(
				'extension_code' 		=> $this->codename,
				'extension_name' 		=> $_language->get('heading_title_main'),
				'element_code'			=> 'enable_status',
				'name'					=> $_language->get('text_enable_status'),
				'description'			=> $_language->get('help_enable_status'),
				'confirm'				=> false,
				'href'					=> $this->url->link('extension/module/' . $this->codename . '/setting', $url_token, true),
				'implemented'			=> isset($setting['control_element']['enable_status']['implemented']) ? 1 : 0,
				'weight'				=> 1
			);
		}
								
		return $control_elements;
	}
		
	public function control_execute_element($data) {
		$this->load->model('extension/module/' . $this->codename);
		$this->load->model('setting/setting');
		
		// Setting 						
		$setting = $this->model_setting_setting->getSetting('module_' . $this->codename, $data['store_id']);
		
		if ($data['element_code'] == 'enable_status') {
			$setting['module_' . $this->codename . '_status'] = 1;
			$setting['module_' . $this->codename . '_setting']['control_element']['enable_status']['implemented'] = 1;
			
			$this->model_setting_setting->editSetting('module_' . $this->codename, $setting, $data['store_id']);
		}
						
		$result['error'] = $this->error;
		
		return $result;
	}
	
	public function target_config() {
		$_language = new Language();
		$_language->load($this->route);
		
		$_config = new Config();
		$_config->load($this->config_file);
		$target_setting = ($_config->get($this->codename . '_target_setting')) ? $_config->get($this->codename . '_target_setting') : array();
		
		foreach ($target_setting['sheet'] as $sheet) {
			if (substr($sheet['name'], 0, strlen('text_')) == 'text_') {
				$target_setting['sheet'][$sheet['code']]['name'] = $_language->get($sheet['name']);
			}
		}
					
		return $target_setting;
	}
		
	public function target_elements($filter_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_' . $this->codename}->getTargetElements($filter_data);
	}
		
	public function add_target_element($target_element_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_' . $this->codename}->addTargetElement($target_element_data);
	}
	
	public function edit_target_element($target_element_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_' . $this->codename}->editTargetElement($target_element_data);
	}
	
	public function delete_target_element($target_element_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_' . $this->codename}->deleteTargetElement($target_element_data);
	}
		
	public function export_target_elements($export_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_' . $this->codename}->getExportTargetElements($export_data);
	}
	
	public function import_target_elements($import_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_' . $this->codename}->saveImportTargetElements($import_data);
	}
	
	public function save($data) {
		if ($data['new_setting']['module_d_seo_module_field_setting']['sheet']['blog_category']['field']['url_keyword']['multi_store_status'] != $data['old_setting']['module_d_seo_module_field_setting']['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
			$cache_data = array(
				'route' => 'bm_category_id=%',
				'store_id' => $data['store_id']
			);
			
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
		
		if ($data['new_setting']['module_d_seo_module_field_setting']['sheet']['blog_post']['field']['url_keyword']['multi_store_status'] != $data['old_setting']['module_d_seo_module_field_setting']['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
			$cache_data = array(
				'route' => 'bm_post_id=%',
				'store_id' => $data['store_id']
			);
			
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
		
		if ($data['new_setting']['module_d_seo_module_field_setting']['sheet']['blog_author']['field']['url_keyword']['multi_store_status'] != $data['old_setting']['module_d_seo_module_field_setting']['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
			$cache_data = array(
				'route' => 'bm_author_id=%',
				'store_id' => $data['store_id']
			);
			
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
	}
	
	public function field_config() {
		$_language = new Language();
		$_language->load($this->route);
		
		$_config = new Config();
		$_config->load($this->config_file);
		$field_setting = ($_config->get($this->codename . '_field_setting')) ? $_config->get($this->codename . '_field_setting') : array();

		foreach ($field_setting['sheet'] as $sheet) {				
			if (substr($sheet['name'], 0, strlen('text_')) == 'text_') {
				$field_setting['sheet'][$sheet['code']]['name'] = $_language->get($sheet['name']);
			}
			
			foreach ($sheet['field'] as $field) {
				if (substr($field['name'], 0, strlen('text_')) == 'text_') {
					$field_setting['sheet'][$sheet['code']]['field'][$field['code']]['name'] = $_language->get($field['name']);
				}
				
				if (substr($field['description'], 0, strlen('help_')) == 'help_') {
					$field_setting['sheet'][$sheet['code']]['field'][$field['code']]['description'] = $_language->get($field['description']);
				}
			}
		}
					
		return $field_setting;
	}
	
	public function field_elements($filter_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_' . $this->codename}->getFieldElements($filter_data);
	}
	
	public function custom_page_exception_routes() {	
		$_config = new Config();
		$_config->load($this->config_file);
		$config_setting = ($_config->get($this->codename . '_setting')) ? $_config->get($this->codename . '_setting') : array();
			
		return $config_setting['custom_page_exception_routes'];
	}
}
