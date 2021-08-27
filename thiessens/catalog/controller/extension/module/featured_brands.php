<?php
class ControllerExtensionModuleFeaturedBrands extends Controller {
	
	public function index($setting) {
		
		static $module = 0;
		
		$this->load->language('extension/module/featured_brands');

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
		
		$data['featured_brands'] = array();
		
		if ($setting['featured_brands']) {
			foreach ($setting['featured_brands'] as $featured_brand) {
				
				$result = $this->model_catalog_manufacturer->getManufacturer($featured_brand);
				
				if(empty($result['image'])) {
					continue;
				}
				
				$image = $result['image'];
				
				// if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 178, 103);
				// } else {
					// $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				// }
				
				$data['featured_brands'][] = array(
					'brand_id'    => $result['manufacturer_id'],
					'image'       => $image,
					'name'        => $result['name'],
					'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
				);
			}
		}
		
		return $this->load->view('extension/module/featured_brands', $data);
	}
}