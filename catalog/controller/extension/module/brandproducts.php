<?php
class ControllerExtensionModuleBrandproducts extends Controller {
	public function index() {
		$this->load->language('extension/module/brandproducts');

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model('catalog/product');
				
		if(isset($product_id)){
			$product = $this->model_catalog_product->getProduct($product_id);
			if($product['manufacturer_id']){
				$limit=$this->config->get('module_brandproducts_limit');
				$results = $this->model_catalog_product->getBrandproducts($product['manufacturer_id'],$limit);
				foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('module_brandproducts_width'), $this->config->get('module_brandproducts_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_brandproducts_width'), $this->config->get('module_brandproducts_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special."test",
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
				return $this->load->view('extension/module/brandproducts', $data);
			}
		}
	}
}
