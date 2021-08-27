<?php
class ControllerExtensionOptioncolorsizeOptioncolorsize extends Controller {
	public function index() {
		$json=array();
		$this->load->model('tool/image');
		if(!empty($this->request->get['product_option_value_id'])){
			$product_option_value_id = $this->request->get['product_option_value_id'];
		}else{
			$product_option_value_id = 0;
		}
		
		$data['eboptioncolorpro_switch_addtional_image'] = $this->config->get('module_eb_color_size_option_swap_addtional_image');
		$data['eb_color_size_option_swap_image'] = $this->config->get('module_eb_color_size_option_swap_image');
		$eb_color_size_option_swap_image = $this->config->get('module_eb_color_size_option_swap_image');
		
		
		if(!empty($this->request->get['product_id'])){
			$product_id = $this->request->get['product_id'];
		}else{
			$product_id = 0;
		}
		
		if(!empty($this->request->get['option_id'])){
			$option_id = $this->request->get['option_id'];
		}else{
			$option_id = 0;
		}
		
		//SET DEFAULT MAIN IMAGE
		$mainimage ='';
		$this->load->model('catalog/product');
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info){
			
		//color image coding start
		$data['thumb'] = $this->model_tool_image->resize($product_info['image'],$this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_height'));
		$data['popup'] = $this->model_tool_image->resize($product_info['image'],$this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_height'));
		$data['heading_title'] = $product_info['name'];
		
		
		$json['model'] = $product_info['model'];
		
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_option_value WHERE product_option_value_id = '".(int)$this->request->get['product_option_value_id']."' AND product_id = '".(int)$product_id."'");
		$notimage = false;
		if(!empty($query->row['image']) && $eb_color_size_option_swap_image){
			$notimage = true;
			$data['thumb'] = $this->model_tool_image->resize($query->row['image'],$this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_height'));
			$data['popup'] = $this->model_tool_image->resize($query->row['image'],$this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_height'));
		}
		
		$squery = $this->db->query("SELECT product_image_id FROM ".DB_PREFIX."ebproduct_option_color_addtional_image WHERE product_option_value_id = '".(int)$this->request->get['product_option_value_id']."' AND product_id = '".(int)$product_id."'");
		
		$data['images']=array();
		foreach($squery->rows as $key => $row):
		$query2 = $this->db->query("SELECT image FROM ".DB_PREFIX."product_image WHERE product_image_id = '".(int)$row['product_image_id']."'");
		if($query2->row['image']){
			if(!$notimage && $eb_color_size_option_swap_image){
				$notimage = true;
				$data['thumb'] = $this->model_tool_image->resize($query2->row['image'],$this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_thumb_height'));
				
				$data['popup'] = $this->model_tool_image->resize($query2->row['image'],$this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_height'));
			}
			$data['images'][] = array(
				'popup' => $this->model_tool_image->resize($query2->row['image'],$this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_popup_height')),
				'thumb' => $this->model_tool_image->resize($query2->row['image'],$this->config->get('theme_'.$this->config->get('config_theme') . '_image_additional_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_additional_height')),
			);
		}
		endforeach;
		$json['html'] = $this->load->view('extension/optioncolor/optioncolor', $data,true);
		 //color image coding end
		 
		 //size coding start
		 $data1=array();
		 $data1['module_eb_color_size_option_required'] = $this->config->get('module_eb_color_size_option_required');
		 $data1['product_option_value_id'] = $product_option_value_id;
		 $query = $this->db->query("SELECT * FROM ".DB_PREFIX."ebproduct_option_combination WHERE product_option_value_id = '".(int)$product_option_value_id."' AND product_id = '".(int)$product_id."' AND quantity > 0");
		 
		 $data1['sizeoptionvalue'] = array();
		 foreach($query->rows as $row){
			$size_option_value = $this->getOptionValue($row['size_option_value_id']);
			
			if($size_option_value){
				$skutitle = $this->config->get('module_eb_color_size_option_sku_title'.$this->config->get('config_language_id'));
				$data1['sizeoptionvalue'][]=array(
				  'name' => $size_option_value['name'].($row['sku'] && $this->config->get('module_eb_color_size_option_sku_status') ? " ( ".$skutitle.' '.$row['sku']." )" : ''),
				  'option_value_id' => $size_option_value['option_value_id']
				);
			}
		}
		 
		if($this->config->get('module_eb_color_size_option_setting')){
			$combineoptions = $this->config->get('module_eb_color_size_option_setting');
		}else{
			$combineoptions = array();
		}
		$data1['text_select'] = $this->language->get('text_select');
		$data1['option'] = '';
		foreach($combineoptions as $option){
			if($option['color']==$option_id){
				$optionsizetype = $this->getOption($option['size']);
				if($optionsizetype){
					$data1['option'] = $optionsizetype;
				}
				Break;
			}
		}
		 
		 $json['sizedata'] = $this->load->view('extension/optioncolor/optionsize', $data1,true);
		  //size coding END
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getOptionValue($option_value_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = '" . (int)$option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getOption($option_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` o LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE o.option_id = '" . (int)$option_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getsizevalues(){
		$json=array();
		$json['model'] ='';
		if(!empty($this->request->get['product_id'])){
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
			if ($product_info){
				if($this->config->get('module_eb_color_size_option_model_status')!=2){
				 $json['model'] = $product_info['model'];
				}
			}
		}
		
		 $json['sku'] = '';
		 $json['upc'] = '';
		 $json['ean'] = '';
		 $json['jan'] = '';
		if(!empty($this->request->get['values'])){
			$part = explode('-',$this->request->get['values']);
			 $product_option_size_id = $part[0];
			 $product_option_value_id = $part[1];
			 
			 
			$query =  $this->db->query("SELECT * FROM ".DB_PREFIX."ebproduct_option_combination WHERE product_option_value_id = '".(int)$product_option_value_id."' AND size_option_value_id = '".(int)$product_option_size_id."'");
			if($query->row){
				
				if($this->config->get('module_eb_color_size_option_model_status')==1 || $this->config->get('module_eb_color_size_option_model_status')==2){	
				   $json['model'] .= $query->row['model'];
				}
							 
				if($this->config->get('module_eb_color_size_option_sku_status')){
				$json['sku'] = $query->row['sku'];
				}
				
				if($this->config->get('module_eb_color_size_option_upc_status')){
				$json['upc'] = $query->row['upc'];
				}
				
				if($this->config->get('module_eb_color_size_option_ean_status')){
				$json['ean'] = $query->row['ean'];
				}
				if($this->config->get('module_eb_color_size_option_jan_status')){
				$json['jan'] = $query->row['jan'];
				}
			}
		}else{
			$json['model'] = $product_info['model'];
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}