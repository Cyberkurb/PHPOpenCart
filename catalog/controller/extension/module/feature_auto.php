<?php
class ControllerExtensionModuleFeaturedAuto extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');
        
        $this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/swiper/js/swiper.jquery.min.js');

		$this->load->model('catalog/product');
        $this->load->model('catalog/category');
		$this->load->model('tool/image');

		$data['products'] = array();
        $data['grills'] = array();
		$setting['limit'] = 90;
        $data['related_cats_p'] = array();
        $data['related_cats_g'] = array();
        
        
        
        
		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $product_info['image'];
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
                    
                    $product_exclusive = $this->model_catalog_product->exclusiveProduct($product_info['product_id']);
                
                    if($product_exclusive != 0){
                        $execlusive_partner = $product_exclusive['name'];
                    }
                    else{
                        $execlusive_partner = false;
                    }

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}
                    $catinfo = array();
                    $cats_info = $this->model_catalog_product->getCategories($product_info['product_id']);
                    
                    
                    foreach ($cats_info as $catinfo) {
                        $catdetail = $this->model_catalog_category->getCategory($catinfo['category_id']);
                        
                        $parentcatdetail = $this->model_catalog_category->getCategory($catdetail['parent_id']);
                        
                        if(array_key_exists('name', $parentcatdetail)){
                            $cat_id = $catinfo['category_id'];
                            $parent_name = $parentcatdetail['name'];
                            $cat_name = $catdetail['name'];
                        }
                        else{
                            $cat_id = $catinfo['category_id'];
                            $parent_name = $catdetail['name'];
                            $cat_name = $catdetail['name'];
                        }
                                    
                    }
                    
					if($parent_name == 'Recipes'){
                        
                        if(!in_array(array(
                                    'category_id'   => $cat_id,
                                    'category_name' => $cat_name,
                                    'parent_name'   => $parent_name
                                    ), $data['related_cats_r'])){
                                    $data['related_cats_r'][] = array(
                                    'category_id'   => $cat_id,
                                    'category_name' => $cat_name,
                                    'parent_name'   => $parent_name
                                    );
                                }
                        
                        $data['recipes'][] = array(
                            'product_id'  => $product_info['product_id'],
                            'thumb'       => $image,
                            'name'        => $product_info['name'],
                            'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                            'price'       => $price,
                            'special'     => $special,
                            'tax'         => $tax,
                            'rating'      => $rating,
                            'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                            'cat_id'      => $cat_id,
                            'parent_name' => $parent_name,
                            'cat_name'    => $cat_name
                        );
                    }
                    elseif($parent_name == 'Grills'){
                        
                        if(!in_array(array(
                                    'category_id'   => $cat_id,
                                    'category_name' => $cat_name,
                                    'parent_name'   => $parent_name
                                    ), $data['related_cats_g'])){
                                    $data['related_cats_g'][] = array(
                                    'category_id'   => $cat_id,
                                    'category_name' => $cat_name,
                                    'parent_name'   => $parent_name
                                    );
                                }
                        
                        $data['grills'][] = array(
                            'product_id'  => $product_info['product_id'],
                            'thumb'       => $image,
                            'name'        => $product_info['name'],
                            'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                            'price'       => $price,
                            'special'     => $special,
                            'exclusive'   => $execlusive_partner,
                            'tax'         => $tax,
                            'rating'      => $rating,
                            'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                            'cat_id'      => $cat_id,
                            'parent_name' => $parent_name,
                            'cat_name'    => $cat_name
                        );
                    }
                    else{
                        
                        if(!in_array(array(
                                    'category_id'   => $cat_id,
                                    'category_name' => $cat_name,
                                    'parent_name'   => $parent_name
                                    ), $data['related_cats_p'])){
                                    $data['related_cats_p'][] = array(
                                    'category_id'   => $cat_id,
                                    'category_name' => $cat_name,
                                    'parent_name'   => $parent_name
                                    );


                                }
                        
                        $data['products'][] = array(
                            'product_id'  => $product_info['product_id'],
                            'thumb'       => $image,
                            'name'        => $product_info['name'],
                            'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                            'price'       => $price,
                            'special'     => $special,
                            'exclusive'   => $execlusive_partner,
                            'tax'         => $tax,
                            'rating'      => $rating,
                            'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                            'cat_id'      => $cat_id,
                            'parent_name' => $parent_name,
                            'cat_name'    => $cat_name
					   );
                    }
                        
				}
			}
		}

		if ($data['products'] OR $data['recipes'] OR $data['grills'] ) {
			return $this->load->view('extension/module/featured', $data);
		}
	}
}