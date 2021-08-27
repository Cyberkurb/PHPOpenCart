<?php
require_once(DIR_SYSTEM . 'library/equotix/colourpicker/equotix.php');
class ControllerExtensionModuleColourPicker extends Equotix {
	protected $code = 'colourpicker';
	protected $extension_id = '7';

	public function dependentoption() {
		$this->load->model( 'catalog/product');
		
		$this->load->model( 'tool/image');
		
		if (isset($this->request->get['parent_id'])) {
			$product_option_id = (int)$this->request->get['parent_id'];
		} else {
			$product_option_id = 0;
		}
		
		if (isset($this->request->get['value'])) {
			$product_option_value_id = (int)$this->request->get['value'];
		} else {
			$product_option_value_id = 0;
		}
		
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		$json = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_option_id = '" . (int)$product_option_id . "'");
		
		if ($query->num_rows) {
			$parent_option = (int)$query->row['option_id'];
		} else {
			$parent_option = 0;
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
		
		if ($query->num_rows) {
			$option_value_id = (int)$query->row['option_value_id'];
		} else {
			$option_value_id = 0;
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON o.option_id = po.option_id LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) LEFT JOIN " . DB_PREFIX . "product_option_parent pop ON pop.product_option_id = po.product_option_id WHERE pop.parent_option = '" . (int)$parent_option . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$json['option'] = array();
			
			foreach ($query->rows as $result) {
				$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "product_option_value_to_option_value pov2ov ON pov2ov.product_option_value_id = pov.product_option_value_id LEFT JOIN " . DB_PREFIX . "option_value ov ON ov.option_value_id = pov.option_value_id LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON ovd.option_value_id = ov.option_value_id WHERE ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pov2ov.option_value_id = '" . (int)$option_value_id . "' AND pov.product_option_id = '" . (int)$result['product_option_id'] . "' ORDER BY ov.sort_order ASC");
			
				$option_value_data = array();
				
				if ($query1->num_rows) {
					foreach ($query1->rows as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
							} else {
								$price = false;
							}
							
							$option_value_data[] = array(
								'product_option_value_id' => $option_value['product_option_value_id'],
								'option_value_id'         => $option_value['option_value_id'],
								'name'                    => $option_value['name'],
								'price'                   => $price,
								'price_prefix'            => $option_value['price_prefix']
							);
						}
					}
				}
				
				$json['option'][] = array(
					'product_option_id' => $result['product_option_id'],
					'option_id'         => $result['option_id'],
					'name'              => $result['name'],
					'type'              => $result['type'],
					'option_value'      => $option_value_data,
					'required'          => $result['required']
				);
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function eventPreControllerProductProduct($route, &$data) {
		$this->document->addScript('catalog/view/javascript/colourpicker.js'); 
		$this->document->addStyle('catalog/view/javascript/colourpicker.css');
	}
	
	public function eventPreViewProductProduct($route, &$data) {
		$data['module_colourpicker_feature'] = $this->config->get('module_colourpicker_feature');
		
		if ($this->validated()) {
			$this->load->model('tool/image');
				
			$cp_option_width = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width');
			$cp_option_height = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height');
			$cp_option_large_width = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width');
			$cp_option_large_height = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height');
			
			$cp_colours = $this->config->get('module_colourpicker_colour') ? $this->config->get('module_colourpicker_colour') : array();
			$cp_sizes = $this->config->get('module_colourpicker_size') ? $this->config->get('module_colourpicker_size') : array();
		
			foreach ($data['options'] as $key => $option) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_parent WHERE product_option_id = '" . (int)$option['product_option_id'] . "'");
				
				$data['options'][$key]['main'] = $query->num_rows ? $query->row['parent_option'] : '';
				
				if (in_array($option['option_id'], $cp_colours)) {
					$data['options'][$key]['cp_colour'] = true;
				} else {
					$data['options'][$key]['cp_colour'] = false;
				}
				
				if (in_array($option['option_id'], $cp_sizes)) {
					$data['options'][$key]['cp_size'] = true;
				} else {
					$data['options'][$key]['cp_size'] = false;
				}
				
				foreach ($option['product_option_value'] as $key1 => $product_option_value) {
					if ($this->config->get('module_colourpicker_feature') == 1 || $this->config->get('module_colourpicker_feature') == 3) {
						$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value WHERE option_value_id = '" . (int)$product_option_value['option_value_id'] . "'");
						
						$image = $query1->num_rows ? $query1->row['image'] : '';
					} else {
						$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_image WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "'");
					
						$image = $query1->num_rows ? $query1->row['option_image'] : '';
					}
					
					$data['options'][$key]['product_option_value'][$key1]['imagemedium'] = $this->model_tool_image->resize($image, $cp_option_width, $cp_option_height);
					$data['options'][$key]['product_option_value'][$key1]['imagelarge'] = $this->model_tool_image->resize($image, $cp_option_large_width, $cp_option_large_height);
				}
			}
		}
	}
}