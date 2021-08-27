<?php
require_once(DIR_SYSTEM . 'library/equotix/colourpicker/equotix.php');
class ControllerExtensionModuleColourPicker extends Equotix {
	protected $version = '5.0.0';
	protected $code = 'colourpicker';
	protected $extension = 'Colour Picker';
	protected $extension_id = '7';
	protected $purchase_url = 'colour-picker';
	protected $purchase_id = '6787';
	protected $error = array();
	
	public function index() {   
		$this->load->language('extension/module/colourpicker');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_colourpicker', $this->request->post);

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
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true)
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/colourpicker', 'user_token=' . $this->session->data['user_token'], true)
   		);
		
		$data['action'] = $this->url->link('extension/module/colourpicker', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->post['module_colourpicker_feature'])) {
			$data['module_colourpicker_feature'] = $this->request->post['module_colourpicker_feature'];
		} else {
			$data['module_colourpicker_feature'] = $this->config->get('module_colourpicker_feature');
		}
		
		if (isset($this->request->post['module_colourpicker_colour'])) {
			$data['module_colourpicker_colour'] = $this->request->post['module_colourpicker_colour'];
		} elseif ($this->config->get('module_colourpicker_colour')) {
			$data['module_colourpicker_colour'] = $this->config->get('module_colourpicker_colour');
		} else {
			$data['module_colourpicker_colour'] = array();
		}
		
		if (isset($this->request->post['module_colourpicker_size'])) {
			$data['module_colourpicker_size'] = $this->request->post['module_colourpicker_size'];
		} elseif ($this->config->get('module_colourpicker_size')) {
			$data['module_colourpicker_size'] = $this->config->get('module_colourpicker_size');
		} else {
			$data['module_colourpicker_size'] = array();
		}
		
		$this->load->model('catalog/option');
		
		$options = $this->model_catalog_option->getOptions();
		
		$data['options'] = array();
		
		foreach ($options as $option) {
			if ($option['type'] == 'select') {
				$data['options'][] = $option;
			}
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->generateOutput('extension/module/colourpicker', $data);
	}
	
	public function install() {
		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			return;
		}
		
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_option_parent` (
			  `product_option_id` int(11) NOT NULL,
			  `product_id` int(11) NOT NULL,
			  `parent_option` int(11) NOT NULL,
			  PRIMARY KEY (`product_option_id`)
			)
		");
		
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_option_value_image` (
			  `product_option_value_id` int(11) NOT NULL,
			  `product_id` int(11) NOT NULL,
			  `option_image` varchar(255) NOT NULL,
			  PRIMARY KEY (`product_option_value_id`)
			)
		");
		
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_option_value_to_option_value` (
			  `product_option_value_id` int(11) NOT NULL,
			  `option_value_id` int(11) NOT NULL,
			  `product_id` int(11) NOT NULL,
			  PRIMARY KEY (`product_option_value_id`,`option_value_id`)
			)
		");
		
		$this->load->model('setting/event');
		
		$this->model_setting_event->addEvent('module_colourpicker', 'admin/view/catalog/product_form/before', 'extension/module/colourpicker/eventPreViewCatalogProductForm');
		$this->model_setting_event->addEvent('module_colourpicker', 'catalog/controller/product/product/before', 'extension/module/colourpicker/eventPreControllerProductProduct');
		$this->model_setting_event->addEvent('module_colourpicker', 'catalog/view/product/product/before', 'extension/module/colourpicker/eventPreViewProductProduct');
	}
	
	public function uninstall() {
		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			return;
		}

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_option_parent`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_option_value_image`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_option_value_to_option_value`");
		
		$this->load->model('setting/event');
		
		$this->model_setting_event->deleteEventByCode('module_colourpicker');
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/colourpicker') || !$this->validated()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function eventPreViewCatalogProductForm($route, &$data) {
		$this->load->model('tool/image');
		
		$data['module_colourpicker_feature'] = $this->config->get('module_colourpicker_feature');
		
		$this->load->language('extension/module/colourpicker');
		
		$data['text_parent_option_value'] = $this->language->get('text_parent_option_value');
		$data['text_parent_option'] = $this->language->get('text_parent_option');
		
		foreach ($data['product_options'] as $key => $product_option) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_parent WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");
			
			$data['product_options'][$key]['parent_option'] = $query->num_rows ? $query->row['parent_option'] : '';
			
			$parent_option_values_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON ovd.option_value_id = ov.option_value_id WHERE ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ov.option_id = '" . (int)$data['product_options'][$key]['parent_option'] . "'");
		
			$data['product_options'][$key]['parent_option_values'] = $parent_option_values_query->rows;
		
			foreach ($product_option['product_option_value'] as $key1 => $product_option_value) {
				$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_image WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "'");
				
				$data['product_options'][$key]['product_option_value'][$key1]['option_image'] = $query1->num_rows ? $query1->row['option_image'] : '';
				$data['product_options'][$key]['product_option_value'][$key1]['thumb'] = $this->model_tool_image->resize(!empty($query1->row['option_image']) ? $query1->row['option_image'] : 'no_image.png', 100, 100);
				
				$product_option_value_to_option_value_query = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "product_option_value_to_option_value WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "'");
			
				$linked_option_values = array();
				
				foreach ($product_option_value_to_option_value_query->rows as $linked_option_value_id) {
					$linked_option_values[] = $linked_option_value_id['option_value_id'];
				}
				
				$data['product_options'][$key]['product_option_value'][$key1]['linked_option_value_id'] = $linked_option_values ? $linked_option_values : 0;
			}
		}
		
		$parent_options_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN `" . DB_PREFIX . "option_description` od ON od.option_id = o.option_id WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "' AND o.type = 'select'");
			
		$data['parent_options'] = $parent_options_query->rows;
	}
	
	public function dependentoptionvalues() {
		$json = array();
		
		if (isset($this->request->get['parent_id'])) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON ovd.option_value_id = ov.option_value_id WHERE ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ov.option_id = '" . (int)$this->request->get['parent_id'] . "'");
		
			if ($query->num_rows) {
				$json['value'] = $query->rows;
			} else {
				$json['value'] = '';
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
}