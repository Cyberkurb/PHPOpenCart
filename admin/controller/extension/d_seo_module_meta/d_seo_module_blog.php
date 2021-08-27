<?php
class ControllerExtensionDSEOModuleMetaDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module_meta/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $error = array(); 
		
	/*
	*	Functions for SEO Module Meta.
	*/	
	public function meta_generator_config() {
		$_language = new Language();
		$_language->load($this->route);
		
		$_config = new Config();
		$_config->load($this->config_file);
		$generator_setting = ($_config->get($this->codename . '_meta_generator_setting')) ? $_config->get($this->codename . '_meta_generator_setting') : array();
				
		foreach ($generator_setting['sheet'] as $sheet) {
			if (substr($sheet['name'], 0, strlen('text_')) == 'text_') {
				$generator_setting['sheet'][$sheet['code']]['name'] = $_language->get($sheet['name']);
			}
				
			foreach ($sheet['field'] as $field) {
				if (substr($field['name'], 0, strlen('text_')) == 'text_') {
					$generator_setting['sheet'][$sheet['code']]['field'][$field['code']]['name'] = $_language->get($field['name']);
				}
				
				if (substr($field['description'], 0, strlen('help_')) == 'help_') {
					$generator_setting['sheet'][$sheet['code']]['field'][$field['code']]['description'] = $_language->get($field['description']);
				}
			}
				
			foreach ($sheet['button_popup'] as $button_popup) {
				if (substr($button_popup['name'], 0, strlen('text_')) == 'text_') {
					$generator_setting['sheet'][$sheet['code']]['button_popup'][$button_popup['code']]['name'] = $_language->get($button_popup['name']);
				}
						
				foreach ($button_popup['field'] as $field) {
					if (substr($field['name'], 0, strlen('text_')) == 'text_') {
						$generator_setting['sheet'][$sheet['code']]['button_popup'][$button_popup['code']]['field'][$field['code']]['name'] = $_language->get($field['name']);
					}
				}
			}
		}
							
		return $generator_setting;
	}
	
	public function meta_generator_generate_fields($generator_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_meta_' . $this->codename}->generateFields($generator_data);
	}
	
	public function meta_generator_clear_fields($generator_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_meta_' . $this->codename}->clearFields($generator_data);
	}
}