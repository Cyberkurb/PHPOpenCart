<?php
class ControllerExtensionModuleFeatured extends Controller {
    
	public function index($setting) {
        
		$this->load->language('extension/module/featured');
        
        $this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/swiper/js/swiper.jquery.min.js');

		$this->load->model('catalog/product');
        $this->load->model('catalog/category');
		$this->load->model('tool/image');

		$data['products'] = array();
        $data['recipes'] = array();
        $data['grills'] = array();
        $data['availableitems'] = array();
		$setting['limit'] = 90;
        $data['related_cats_p'] = array();
        $data['related_cats_r'] = array();
        $data['related_cats_g'] = array();
        $data['wood_pellets_grills'] = array();
        $data['charcoal_grills'] = array();
        $data['gas_grills'] = array();
        $data['smoker_grills'] = array();
        //$data['newest_products'] = array();
        //$data['top_sellers'] = array();
        $data['accessories_found'] = array();
        $data['au_grills'] = array();
        $data['firebar'] = $this->load->controller('extension/module/firebar');
        
		if (!empty($setting['product'])) {
            //$products = array_slice($setting['product'], 0, (int)$setting['limit']);
            
            if($this->config->get('config_store_id') == 14){
                $all_products = $this->model_catalog_product->getAllDansonsProducts();
                $products = array();
                $wood_pellets = array();
                $charcoal_grills = array();
                $gas_grills = array();
                $accessories_found = array();
                $smoker_grills = array();
            }
            elseif($this->config->get('config_store_id') == 16){
                $all_products = $this->model_catalog_product->getAllDansonsProducts();
                $products = array();
                $wood_pellets = array();
                $charcoal_grills = array();
                $gas_grills = array();
                $accessories_found = array();
                $smoker_grills = array();
            }
            elseif($this->config->get('config_store_id') == 7){
                $all_products = $this->model_catalog_product->getAllDansonsProducts();
                $products = array();
                $wood_pellets = array();
                $charcoal_grills = array();
                $gas_grills = array();
                $accessories_found = array();
                $smoker_grills = array();
            }
            else{
                $all_products = array();
                $products = $this->model_catalog_product->getFeatured();
                $wood_pellets = $this->model_catalog_product->getWoodPellet();
                $charcoal_grills = $this->model_catalog_product->getCharcoalGrill();
                $gas_grills = $this->model_catalog_product->getGasGrill();
                $accessories_found = $this->model_catalog_product->getAccessories();
                $smoker_grills = $this->model_catalog_product->getSmokers();
                $au_grills = $this->model_catalog_product->getAuProducts();
            }
            //$latest_products = $this->model_catalog_product->getLatestProducts();
            //$topselling_products = $this->model_catalog_product->getTopSellers();
            foreach ($all_products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct_oe($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $product_info['image'];
					} else {
						$image = 'https://images.pitboss-grills.com/placeholder.png';
					}
                    

                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    
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

                    $product_exclusive = $this->model_catalog_product->exclusiveProduct($product_info['product_id']);
                
                    if($product_exclusive != 0){
                        $execlusive_partner = $product_exclusive['name'];
                    }
                    else{
                        $execlusive_partner = false;
                    }
    
                    if ((float)$product_info['special']) {
                        $discounted_amount = ((float)$product_info['price']-(float)$product_info['special']);
                        if($discounted_amount > 0){
                            $savings = $this->currency->format($this->tax->calculate($discounted_amount, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        }
                        else{
                            $savings = false;
                        }
                    } else {
                        $savings = false;
                    }
                        
                    $data['availableitems'][] = array(
                        'product_id'        => $product_info['product_id'],
                        'model_num'         => $product_info['model'],
                        'thumb'             => $image,
                        'name'              => $product_info['name'],
                        'description'       => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'             => $price,
                        'exclusive'         => $execlusive_partner,
                        'special'           => $special,
                        's_savings'	        => $savings,
                        'anoka_inventory'   => $product_info['quantity_anoka'],
                        'wa_inventory'      => $product_info['quantity_wa'],
                        'canada_inventory'  => $product_info['quantity_ca'],
                        'phoenix_inventory' => $product_info['quantity_phoenix'],
                        'phxdc_inventory'   => $product_info['quantity_phxdc'],
                        'europe_inventory'  => $product_info['quantity_europe'],
                        'onorder'           => $product_info['quantity_onorder'],
                        'onwater'           => $product_info['quantity_onwater'],
                        'amazen_inventory'  => $product_info['quantity_amazen'],
                        'fife_inventory'  => $product_info['quantity_fife'],
                        'expected_availablity' => $product_info['expected_availablity'],
                        'transit_inventory' => $product_info['quantity_transit'],
                        'quantity'          => $product_info['quantity'],
                        'href'              => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
			}

            foreach ($smoker_grills as $product_id) {
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
    
                    if ((float)$product_info['special']) {
                        $discounted_amount = ((float)$product_info['price']-(float)$product_info['special']);
                        $savings = $this->currency->format($this->tax->calculate($discounted_amount, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $savings = false;
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
                    
                        
                    $data['smoker_grills'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'             => $price,
                        'special'           => $special,
                        's_savings'	        => $savings,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
			}
			foreach ($au_grills as $product_id) {
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
    
                    if ((float)$product_info['special']) {
                        $discounted_amount = ((float)$product_info['price']-(float)$product_info['special']);
                        $savings = $this->currency->format($this->tax->calculate($discounted_amount, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $savings = false;
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
                    
                        
                    $data['au_grills'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'             => $price,
                        'special'           => $special,
                        's_savings'	        => $savings,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
			}
            foreach ($wood_pellets as $product_id) {
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
                    
                        
                    $data['wood_pellets_grills'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
			}
            foreach ($charcoal_grills as $product_id) {
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
                    
                        
                    $data['charcoal_grills'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
            }
            foreach ($gas_grills as $product_id) {
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
                    
                        
                    $data['gas_grills'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
            }
            foreach ($accessories_found as $product_id) {
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
                    
                        
                    $data['accessories_found'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
            }
            /*
            foreach ($latest_products as $product_id) {
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
                    
                        
                    $data['accessories_found'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
            }
            foreach ($topselling_products as $product_id) {
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
                    
                        
                    $data['accessories_found'][] = array(
                        'product_id'  => $product_info['product_id'],
                        'thumb'       => $image,
                        'name'        => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );   
				}
			}*/
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
                    $cat_name = '';
                    $parent_name = '';
                    $cat_id = '';
                    
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
                            'preptime'	  => $this->model_catalog_product->getPrepTime($product_info['product_id']),
					        'cooktime'	  => $this->model_catalog_product->getCookTime($product_info['product_id']),
					        'difficulty'  => $this->model_catalog_product->getDifficulty($product_info['product_id']),
					        'servings'	  => $this->model_catalog_product->getServings($product_info['product_id']),
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
        $data['current_date'] = date('Y-m-d', strtotime('-7 hours'));
		if ($data ) {
			return $this->load->view('extension/module/featured', $data);
		}
	}
}