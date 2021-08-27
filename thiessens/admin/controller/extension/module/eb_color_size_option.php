<?php
class ControllerExtensionModuleEbcolorsizeoption extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/eb_color_size_option');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_eb_color_size_option', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title1');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_color'] = $this->language->get('text_color');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_round'] = $this->language->get('text_round');
		$data['text_square'] = $this->language->get('text_square');
		$data['tab_front'] = $this->language->get('tab_front');
		$data['tab_language'] = $this->language->get('tab_language');

		$data['entry_status'] = $this->language->get('entry_status');
		
		
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_configure'] = $this->language->get('tab_configure');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		

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
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/module/eb_color_size_option', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/eb_color_size_option', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		
		$this->load->model('catalog/option');
		$data['sizeoptions']=array();
		$options = $this->model_catalog_option->getOptions(array());
		foreach($options as $option){
			if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
				$data['sizeoptions'][]=array(
				  'name' => $option['name'],
				  'option_id' => $option['option_id']
				);
			}
		}
		
		$options = $this->model_catalog_option->getOptions(array());
		$data['coloroptions']=array();
		foreach($options as $option){
			if ($option['type'] == 'color') {
				$data['coloroptions'][]=array(
				  'name' => $option['name'],
				  'option_id' => $option['option_id']
				);
			}
		}
		
		
		

		if (isset($this->request->post['module_eb_color_size_option_model'])) {
			$data['module_eb_color_size_option_model'] = $this->request->post['module_eb_color_size_option_model'];
		} else {
			$data['module_eb_color_size_option_model'] = $this->config->get('module_eb_color_size_option_model');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_model_status'])) {
			$data['module_eb_color_size_option_model_status'] = $this->request->post['module_eb_color_size_option_model_status'];
		} else {
			$data['module_eb_color_size_option_model_status'] = $this->config->get('module_eb_color_size_option_model_status');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_sku_status'])) {
			$data['module_eb_color_size_option_sku_status'] = $this->request->post['module_eb_color_size_option_sku_status'];
		} else {
			$data['module_eb_color_size_option_sku_status'] = $this->config->get('module_eb_color_size_option_sku_status');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_upc_status'])) {
			$data['module_eb_color_size_option_upc_status'] = $this->request->post['module_eb_color_size_option_upc_status'];
		} else {
			$data['module_eb_color_size_option_upc_status'] = $this->config->get('module_eb_color_size_option_upc_status');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_ean_status'])) {
			$data['module_eb_color_size_option_ean_status'] = $this->request->post['module_eb_color_size_option_ean_status'];
		} else {
			$data['module_eb_color_size_option_ean_status'] = $this->config->get('module_eb_color_size_option_ean_status');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_jan_status'])) {
			$data['module_eb_color_size_option_jan_status'] = $this->request->post['module_eb_color_size_option_jan_status'];
		} else {
			$data['module_eb_color_size_option_jan_status'] = $this->config->get('module_eb_color_size_option_jan_status');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_sku'])) {
			$data['module_eb_color_size_option_sku'] = $this->request->post['module_eb_color_size_option_sku'];
		} else {
			$data['module_eb_color_size_option_sku'] = $this->config->get('module_eb_color_size_option_sku');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_upc'])) {
			$data['module_eb_color_size_option_upc'] = $this->request->post['module_eb_color_size_option_upc'];
		} else {
			$data['module_eb_color_size_option_upc'] = $this->config->get('module_eb_color_size_option_upc');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_ean'])) {
			$data['module_eb_color_size_option_ean'] = $this->request->post['module_eb_color_size_option_ean'];
		} else {
			$data['module_eb_color_size_option_ean'] = $this->config->get('module_eb_color_size_option_ean');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_jan'])) {
			$data['module_eb_color_size_option_jan'] = $this->request->post['module_eb_color_size_option_jan'];
		} else {
			$data['module_eb_color_size_option_jan'] = $this->config->get('module_eb_color_size_option_jan');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_status'])) {
			$data['module_eb_color_size_option_status'] = $this->request->post['module_eb_color_size_option_status'];
		} else {
			$data['module_eb_color_size_option_status'] = $this->config->get('module_eb_color_size_option_status');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_required'])) {
			$data['module_eb_color_size_option_required'] = $this->request->post['module_eb_color_size_option_required'];
		} else {
			$data['module_eb_color_size_option_required'] = $this->config->get('module_eb_color_size_option_required');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_auto_trigger'])) {
			$data['module_eb_color_size_option_auto_trigger'] = $this->request->post['module_eb_color_size_option_auto_trigger'];
		} else {
			$data['module_eb_color_size_option_auto_trigger'] = $this->config->get('module_eb_color_size_option_auto_trigger');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_setting'])) {
			$data['module_eb_color_size_option_setting'] = $this->request->post['module_eb_color_size_option_setting'];
		} else {
			$data['module_eb_color_size_option_setting'] = $this->config->get('module_eb_color_size_option_setting');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_bullet_style'])) {
			$data['module_eb_color_size_option_bullet_style'] = $this->request->post['module_eb_color_size_option_bullet_style'];
		} else {
			$data['module_eb_color_size_option_bullet_style'] = $this->config->get('module_eb_color_size_option_bullet_style');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_bullet_width'])) {
			$data['module_eb_color_size_option_bullet_width'] = $this->request->post['module_eb_color_size_option_bullet_width'];
		} else {
			$data['module_eb_color_size_option_bullet_width'] = $this->config->get('module_eb_color_size_option_bullet_width');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_bullet_height'])) {
			$data['module_eb_color_size_option_bullet_height'] = $this->request->post['module_eb_color_size_option_bullet_height'];
		} else {
			$data['module_eb_color_size_option_bullet_height'] = $this->config->get('module_eb_color_size_option_bullet_height');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_swap_image'])) {
			$data['module_eb_color_size_option_swap_image'] = $this->request->post['module_eb_color_size_option_swap_image'];
		} else {
			$data['module_eb_color_size_option_swap_image'] = $this->config->get('module_eb_color_size_option_swap_image');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_swap_addtional_image'])) {
			$data['module_eb_color_size_option_swap_addtional_image'] = $this->request->post['module_eb_color_size_option_swap_addtional_image'];
		} else {
			$data['module_eb_color_size_option_swap_addtional_image'] = $this->config->get('module_eb_color_size_option_swap_addtional_image');
		}
		
		if (isset($this->request->post['module_eb_color_size_option_bullet_type'])) {
			$data['module_eb_color_size_option_bullet_type'] = $this->request->post['module_eb_color_size_option_bullet_type'];
		} else {
			$data['module_eb_color_size_option_bullet_type'] = $this->config->get('module_eb_color_size_option_bullet_type');
		}
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (isset($this->request->post['module_eb_color_size_option_model_title' . $language['language_id']])) {
				$data['module_eb_color_size_option_model_title'][$language['language_id']] = $this->request->post['module_eb_color_size_option_model_title' . $language['language_id']];
			} else {
				$data['module_eb_color_size_option_model_title'][$language['language_id']] = $this->config->get('module_eb_color_size_option_model_title' . $language['language_id']);
			}
			
			
			if (isset($this->request->post['module_eb_color_size_option_sku_title' . $language['language_id']])) {
				$data['module_eb_color_size_option_sku_title'][$language['language_id']] = $this->request->post['module_eb_color_size_option_sku_title' . $language['language_id']];
			}  else {
				$data['module_eb_color_size_option_sku_title'][$language['language_id']] = $this->config->get('module_eb_color_size_option_sku_title' . $language['language_id']);
			}
			
			if (isset($this->request->post['module_eb_color_size_option_upc_title' . $language['language_id']])) {
				$data['module_eb_color_size_option_upc_title'][$language['language_id']] = $this->request->post['module_eb_color_size_option_upc_title' . $language['language_id']];
			}else {
				$data['module_eb_color_size_option_upc_title'][$language['language_id']] = $this->config->get('module_eb_color_size_option_upc_title' . $language['language_id']);
			}
			
			if (isset($this->request->post['module_eb_color_size_option_ean_title' . $language['language_id']])) {
				$data['module_eb_color_size_option_ean_title'][$language['language_id']] = $this->request->post['module_eb_color_size_option_ean_title' . $language['language_id']];
			} else {
				$data['module_eb_color_size_option_ean_title'][$language['language_id']] = $this->config->get('module_eb_color_size_option_ean_title' . $language['language_id']);
			}
			
			if (isset($this->request->post['module_eb_color_size_option_jan_title' . $language['language_id']])) {
				$data['module_eb_color_size_option_jan_title'][$language['language_id']] = $this->request->post['module_eb_color_size_option_jan_title' . $language['language_id']];
			}  else {
				$data['module_eb_color_size_option_jan_title'][$language['language_id']] = $this->config->get('module_eb_color_size_option_jan_title' . $language['language_id']);
			}
		}

		$data['languages'] = $languages;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/eb_color_size_option', $data));
	}
	
	public function optionsize(){
		$this->load->model('catalog/option');
		$json=array();
		if(isset($this->request->get['product_option_value_id'])){
			$product_option_value_id = $this->request->get['product_option_value_id'];
		}else{
			$product_option_value_id = 0;
		}
		
		if(isset($this->request->get['product_id'])){
			$product_id = $this->request->get['product_id'];
		}else{
			$product_id = 0;
		}
		
		if(isset($this->request->get['option_id'])){
			$option_id = $this->request->get['option_id'];
		}else{
			$option_id = 0;
		}
		
		//Get Save Data AGST Product
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."ebproduct_option_combination WHERE product_id = '".(int)$product_id."' AND product_option_value_id = '".(int)$product_option_value_id."'");
		
		$savesizedata=array();
		foreach($query->rows as $row){
			$savesizedata[$row['size_option_value_id']] = array(
				'quantity'	=> $row['quantity'],
				'sku'		=> $row['sku'],
				'model'		=> $row['model'],
				'upc'		=> $row['upc'],
				'ean'		=> $row['ean'],
				'jan'		=> $row['jan'],
			);
		}
		
		
		
		//Set Size Option Value
		if($this->config->get('module_eb_color_size_option_setting')){
		  $combineoptions = $this->config->get('module_eb_color_size_option_setting');
		}else{
		  $combineoptions = array();
		}
		
		$option_size=array();
		foreach($combineoptions as $option){
			if($option['color']==$option_id){
				$size = $option['size'];
				$option_size =  $this->model_catalog_option->getOptionValues($size);
				Break;
			}
		}
		
	foreach($option_size as $size){
		$json['option_size'][]=array(
		  'option_value_id'	=> $size['option_value_id'],
		  'name'			=> $size['name'],
		  'quantity'		=> (isset($savesizedata[$size['option_value_id']]['quantity']) ? $savesizedata[$size['option_value_id']]['quantity'] : 0),
		  'model'			=> (isset($savesizedata[$size['option_value_id']]['model']) ? $savesizedata[$size['option_value_id']]['model'] : ''),
		  'sku'				=> (isset($savesizedata[$size['option_value_id']]['sku']) ? $savesizedata[$size['option_value_id']]['sku'] : ''),
		  'upc'				=> (isset($savesizedata[$size['option_value_id']]['upc']) ? $savesizedata[$size['option_value_id']]['upc'] : ''),
		  'ean'				=> (isset($savesizedata[$size['option_value_id']]['ean']) ? $savesizedata[$size['option_value_id']]['ean'] : ''),
		  'jan'				=> (isset($savesizedata[$size['option_value_id']]['jan']) ? $savesizedata[$size['option_value_id']]['jan'] : ''),
		);
	}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/eb_color_size_option')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}