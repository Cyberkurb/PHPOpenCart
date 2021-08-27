<?php
class ControllerProductCategory extends Controller {
	public function index() {
		if($this->config->get('config_store_id') != 14){
			$seconds_to_cache = 86400;
			$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
			header("Expires: $ts");
			header("Pragma: cache");
			header("Cache-Control: max-age=$seconds_to_cache");
		}
		$this->load->language('product/category');
        $this->load->model('account/customer');
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
        
        $limit = 900;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}
		if(isset($this->request->get['category'])){
			if($this->request->get['category'] == 'wood-pellet-grills'){
				$category_id = 60;
			}
		}
        
        
		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$parent_info = $this->model_catalog_category->getParentCategory($category_id);
			$data['top_cat'] = $parent_info;

			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);
            
			if ($category_info['image']) {
                $data['heading_image'] = $category_info['image'];
				$data['thumb'] = $category_info['image'];
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results_cat = $this->model_catalog_category->getCategories($category_id);

			$data['products'] = array();
			
			
            
            if(count($results_cat) > 1){
			foreach ($results_cat as $catresult) {
				$filter_data = array(
					'filter_category_id'  => $catresult['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'name' => $catresult['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $catresult['category_id'] . $url)
				);
                
                $filter_data = array(
				'filter_category_id' => $catresult['category_id'],
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);
            
			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
            if($catresult['category_id'] == 137){
                $results = $this->model_catalog_product->getProducts_downloads($filter_data);
            }
            else{
                $results = $this->model_catalog_product->getProducts($filter_data);
            }

			foreach ($results as $result) {
                if ($result['image']) {
                    if(substr($result['image'],0,4) == "http"){
                            $image = $result['image'];
                            $other_image = $result['image'];
                    }
                    else{
                            $image = $result['image'];
                            $other_image = $this->model_tool_image->resize($result['image'], '350', '350');
                    }
                } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                    }
                
                $product_exclusive = $this->model_catalog_product->exclusiveProduct($result['product_id']);
				$hover_image = $this->model_catalog_product->getProductHover($result['product_id']);
				if(isset($hover_image['image'])){
					$hoverover_image = $hover_image['image'];
				}
				else{
					$hoverover_image = $result['image'];
				}
				
                if($product_exclusive['name']){
                    $execlusive_partner = $product_exclusive['name'];
                }
                else{
                    $execlusive_partner = false;
                }
                
               $product_documents = $this->model_catalog_product->downloadsProduct($result['product_id']);
                
                if($product_documents != 0){
                    $attached_docs = $product_documents;
                }
                else{
                    $attached_docs = false;
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

				if ((float)$result['special']) {
					$discounted_amount = ((float)$result['price']-(float)$result['special']);
					if($discounted_amount > 0){
						$savings = $this->currency->format($this->tax->calculate($discounted_amount, $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					}
					else{
						$savings = false;
					}
				} else {
					$savings = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				
				if($this->config->get('config_store_id') == 10){
					$quantity = $result['quantity_ca'];	
				}
				elseif($this->config->get('config_store_id') == 11){
					$quantity = $result['quantity_ca'];	
				}
				else{
					$quantity = $result['quantity'];
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
                    'p_image'     => $hoverover_image,
                    'quanity'	  => $quantity,
					'name'        => $result['name'],
					'model'        => $result['model'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 500) . '..',
					'price'       => $price,
					'special'     => $special,
					's_savings'	  => $savings,
                    'exclusive'   => $execlusive_partner,
					'tax'         => $tax,
					'preptime'	  => $this->model_catalog_product->getPrepTime($result['product_id']),
					'cooktime'	  => $this->model_catalog_product->getCookTime($result['product_id']),
					'difficulty'  => $this->model_catalog_product->getDifficulty($result['product_id']),
					'servings'	  => $this->model_catalog_product->getServings($result['product_id']),
                    'docs'        => $attached_docs,
                    'catname'     => $catresult['name'],
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}
                }
            }
            else{
                foreach ($results_cat as $catresult) {
				$filter_data = array(
					'filter_category_id'  => $catresult['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'name' => $catresult['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $catresult['category_id'] . $url)
				);
                }

            
                $filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);
            
			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			if($category_id == 137){
                $results = $this->model_catalog_product->getProducts_downloads($filter_data);
            }
            else{
                $results = $this->model_catalog_product->getProducts($filter_data);
            }

			foreach ($results as $result) {
				if ($result['image']) {
                    if(substr($result['image'],0,4) == "http"){
                            $image = $result['image'];
                            $other_image = $result['image'];
                    }
                    else{
                            $image = $result['image'];
                            $other_image = $this->model_tool_image->resize($result['image'], '350', '350');
                    }
                } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
                    }
                
                $product_exclusive = $this->model_catalog_product->exclusiveProduct($result['product_id']);
                
                if($product_exclusive['name']){
                    $execlusive_partner = $product_exclusive['name'];
                }
                else{
                    $execlusive_partner = false;
                }
               
                $product_documents = $this->model_catalog_product->downloadsProduct($result['product_id']);
                
                if($product_documents != 0){
                    $attached_docs = $product_documents;
                }
                else{
                    $attached_docs = false;
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

				if ((float)$result['special']) {
					$discounted_amount = ((float)$result['price']-(float)$result['special']);
					$savings = $this->currency->format($this->tax->calculate($discounted_amount, $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$savings = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				
				if($this->config->get('config_store_id') == 10){
					$quantity = $result['quantity_ca'];	
				}
				elseif($this->config->get('config_store_id') == 11){
					$quantity = $result['quantity_ca'];	
				}
				else{
					$quantity = $result['quantity'];
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
                    'p_image'     => $other_image,
                    'quanity'	  => $quantity,
					'name'        => $result['name'],
					'model'        => $result['model'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 500) . '..',
					'price'       => $price,
					's_savings'	  => $savings,
                    'docs'        => $attached_docs,
					'special'     => $special,
					'preptime'	  => $this->model_catalog_product->getPrepTime($result['product_id']),
					'cooktime'	  => $this->model_catalog_product->getCookTime($result['product_id']),
					'difficulty'  => $this->model_catalog_product->getDifficulty($result['product_id']),
					'servings'	  => $this->model_catalog_product->getServings($result['product_id']),
                    'exclusive'   => $execlusive_partner,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}
            }

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id']), 'canonical');
			} else {
				$this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. $page), 'canonical');
			}
			
			if ($page > 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1)), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;
			$data['current_date'] = date('Y-m-d', strtotime('-7 hours'));
			$data['continue'] = $this->url->link('common/home');
			$detailcustomer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			if(isset($detailcustomer_info['customer_group_id'])){
				$data['user_group_id'] = $detailcustomer_info['customer_group_id'];
			}
			else{
				$data['user_group_id'] = 1;
			}
            $data['store_id'] = $this->config->get('config_store_id');
            $data['banners'] = $this->load->controller('extension/module/firebar');
			$data['filter_display'] = $this->load->controller('extension/module/filter');
			$data['column_left'] = $this->load->controller('common/');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
            $data['current_url'] = $this->request->server['REQUEST_URI'];
			$this->response->setOutput($this->load->view('product/category', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');
			$data['store_id'] = $this->config->get('config_store_id');
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
			$data['filter_display'] = $this->load->controller('extension/module/filter');
			$data['current_date'] = date('Y-m-d', strtotime('-7 hours'));
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
            $data['current_url'] = $this->request->server['REQUEST_URI'];
			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}
