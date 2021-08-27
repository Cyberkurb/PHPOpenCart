<?php
class ControllerExtensionDSEOModuleBlogDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module_blog/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $error = array();
			
	/*
	*	Functions for SEO Module Blog.
	*/	
	public function category_form_tab_general_language() {		
		$this->load->model($this->route);
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($field_info['sheet']['blog_category']['field'])) {
			$data['fields'] = $field_info['sheet']['blog_category']['field'];
		} else {
			$data['fields'] = array();
		}
		
		$data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();
										
		if (isset($this->request->post['meta_data'])) {
			$data['meta_data'] = $this->request->post['meta_data'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['meta_data'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getCategoryMetaData($this->request->get['category_id']);
		} else {
			$data['meta_data'] = array();
		}
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['category_id'])) {	
			$data['target_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getCategoryTargetKeyword($this->request->get['category_id']);
		} else {
			$data['target_keyword'] = array();
		}
		
		if (isset($this->request->post['url_keyword'])) {
			$data['url_keyword'] = $this->request->post['url_keyword'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['url_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getCategoryURLKeyword($this->request->get['category_id']);
		} else {
			$data['url_keyword'] = array();
		}
		
		$data['adviser_elements'] = array();
		$data['rating'] = array();
		
		if (isset($this->request->get['category_id'])) {
			$route = 'bm_category_id=' . $this->request->get['category_id'];
			
			$adviser_info = $this->load->controller('extension/module/d_seo_module_adviser/getAdviserInfo', $route);
		
			if (isset($adviser_info['adviser_elements']) && isset($adviser_info['rating'])) {
				$data['adviser_elements'] = $adviser_info['adviser_elements'];
				$data['rating'] = $adviser_info['rating'];
			}
		}
		
		$data['store_id'] = 0;
			
		$html_tab_general_language = array();
				
		foreach ($languages as $language) {
			$data['language_id'] = $language['language_id'];
			
			if (isset($data['target_keyword'][$data['store_id']][$data['language_id']])) {
				foreach ($data['target_keyword'][$data['store_id']][$data['language_id']] as $sort_order => $keyword) {
					$field_data = array(
						'field_code' => 'target_keyword',
						'filter' => array(
							'store_id' => $data['store_id'],
							'keyword' => $keyword
						)
					);
			
					$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
					
					if ($target_keywords) {
						$store_target_keywords = reset($target_keywords);
							
						if ((count($target_keywords) > 1) || (count(reset($store_target_keywords)) > 1)) {
							$data['target_keyword_duplicate'][$data['store_id']][$data['language_id']][$sort_order] = 1;
						}
					}
				}				
			}
		
			$html_tab_general_language[$data['language_id']] = $this->load->view($this->route . '/category_form_tab_general_language', $data);
		}
				
		return $html_tab_general_language;
	}
	
	public function category_form_tab_general_store_language() {
		$this->load->model($this->route);
		$this->load->model('extension/module/' . $this->codename);
		
		$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($field_info['sheet']['blog_category']['field'])) {
			$data['fields'] = $field_info['sheet']['blog_category']['field'];
		} else {
			$data['fields'] = array();
		}
		
		$data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();
										
		if (isset($this->request->post['meta_data'])) {
			$data['meta_data'] = $this->request->post['meta_data'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['meta_data'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getCategoryMetaData($this->request->get['category_id']);
		} else {
			$data['meta_data'] = array();
		}
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['category_id'])) {	
			$data['target_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getCategoryTargetKeyword($this->request->get['category_id']);
		} else {
			$data['target_keyword'] = array();
		}
		
		if (isset($this->request->post['url_keyword'])) {
			$data['url_keyword'] = $this->request->post['url_keyword'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['url_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getCategoryURLKeyword($this->request->get['category_id']);
		} else {
			$data['url_keyword'] = array();
		}
		
		$data['adviser_elements'] = array();
		$data['rating'] = array();
		
		if (isset($this->request->get['category_id'])) {
			$route = 'bm_category_id=' . $this->request->get['category_id'];
			
			$adviser_info = $this->load->controller('extension/module/d_seo_module_adviser/getAdviserInfo', $route);
		
			if (isset($adviser_info['adviser_elements']) && isset($adviser_info['rating'])) {
				$data['adviser_elements'] = $adviser_info['adviser_elements'];
				$data['rating'] = $adviser_info['rating'];
			}
		}
				
		$html_tab_general_store_language = array();
		
		foreach ($stores as $store) {
			$data['store_id'] = $store['store_id'];		
		
			foreach ($languages as $language) {
				$data['language_id'] = $language['language_id'];
				
				if (isset($data['target_keyword'][$data['store_id']][$data['language_id']])) {
					foreach ($data['target_keyword'][$data['store_id']][$data['language_id']] as $sort_order => $keyword) {
						$field_data = array(
							'field_code' => 'target_keyword',
							'filter' => array(
								'store_id' => $data['store_id'],
								'keyword' => $keyword
							)
						);
			
						$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
						
						if ($target_keywords) {
							$store_target_keywords = reset($target_keywords);
							
							if ((count($target_keywords) > 1) || (count(reset($store_target_keywords)) > 1)) {
								$data['target_keyword_duplicate'][$data['store_id']][$data['language_id']][$sort_order] = 1;
							}
						}
					}				
				}
				
				$html_tab_general_store_language[$data['store_id']][$data['language_id']] = $this->load->view($this->route . '/category_form_tab_general_store_language', $data);
			}
		}
		
		return $html_tab_general_store_language;
	}
	
	public function category_form_style() {		
		return $this->load->view($this->route . '/category_form_style');
	}
	
	public function category_form_script() {			
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		$data['route'] = $this->route;
		$data['url_token'] = $url_token;
		
		return $this->load->view($this->route . '/category_form_script', $data);
	}
	
	public function category_validate_form($error) {
		$_language = new Language();
		$_language->load($this->route);
			
		if (isset($this->request->post['meta_data'])) {		
			foreach ($this->request->post['meta_data'] as $store_id => $language_meta_data) {
				foreach ($language_meta_data as $language_id => $meta_data) {
					if ($store_id) {
						if (isset($meta_data['title']) && ((utf8_strlen($meta_data['title']) < 2) || (utf8_strlen($meta_data['title']) > 255))) {
							$error['meta_data'][$store_id][$language_id]['title'] = $_language->get('error_category_title');
						}

						if (isset($meta_data['meta_title']) && ((utf8_strlen($meta_data['meta_title']) < 3) || (utf8_strlen($meta_data['meta_title']) > 255))) {
							$error['meta_data'][$store_id][$language_id]['meta_title'] = $_language->get('error_meta_title');
						}
					}
				}
			}
		}
		
		if (isset($this->request->post['url_keyword'])) {			
			foreach ($this->request->post['url_keyword'] as $store_id => $language_url_keyword) {
				foreach ($language_url_keyword as $language_id => $url_keyword) {
					if (trim($url_keyword)) {								
						$field_data = array(
							'field_code' => 'url_keyword',
							'filter' => array(
							'store_id' => $store_id,
							'keyword' => $url_keyword
							)
						);
			
						$url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
									
						if ($url_keywords) {
							if (isset($this->request->get['category_id'])) {
								foreach ($url_keywords as $route => $store_url_keywords) {
									if ($route != 'bm_category_id=' . $this->request->get['category_id']) {
										$error['url_keyword'][$store_id][$language_id] = sprintf($_language->get('error_url_keyword_exists'), $url_keyword);
									}
								}
							} else {
								$error['url_keyword'][$store_id][$language_id] = sprintf($_language->get('error_url_keyword_exists'), $url_keyword);
							}
						}
					}
				}
			}
		}
		
		$this->config->set($this->codename . '_error', $error);
				
		return $error;
	}	
	
	public function category_add_category($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveCategoryMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveCategoryTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveCategoryURLKeyword($data);
	}
	
	public function category_edit_category($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveCategoryMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveCategoryTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveCategoryURLKeyword($data);
	}
	
	public function category_delete_category($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deleteCategoryMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deleteCategoryTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deleteCategoryURLKeyword($data);
	}
	
	public function post_form_tab_general_language() {
		$this->load->model($this->route);
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($field_info['sheet']['blog_post']['field'])) {
			$data['fields'] = $field_info['sheet']['blog_post']['field'];
		} else {
			$data['fields'] = array();
		}
		
		$data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();
				
		if (isset($this->request->post['meta_data'])) {
			$data['meta_data'] = $this->request->post['meta_data'];
		} elseif (isset($this->request->get['post_id'])) {
			$data['meta_data'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getPostMetaData($this->request->get['post_id']);
		} else {
			$data['meta_data'] = array();
		}
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['post_id'])) {	
			$data['target_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getPostTargetKeyword($this->request->get['post_id']);
		} else {
			$data['target_keyword'] = array();
		}
		
		if (isset($this->request->post['url_keyword'])) {
			$data['url_keyword'] = $this->request->post['url_keyword'];
		} elseif (isset($this->request->get['post_id'])) {
			$data['url_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getPostURLKeyword($this->request->get['post_id']);
		} else {
			$data['url_keyword'] = array();
		}
		
		$data['adviser_elements'] = array();
		$data['rating'] = array();
				
		if (isset($this->request->get['post_id'])) {
			$route = 'bm_post_id=' . $this->request->get['post_id'];
			
			$adviser_info = $this->load->controller('extension/module/d_seo_module_adviser/getAdviserInfo', $route);
		
			if (isset($adviser_info['adviser_elements']) && isset($adviser_info['rating'])) {
				$data['adviser_elements'] = $adviser_info['adviser_elements'];
				$data['rating'] = $adviser_info['rating'];
			}
		}
		
		$data['store_id'] = 0;
		
		$html_tab_general_language = array();
				
		foreach ($languages as $language) {
			$data['language_id'] = $language['language_id'];
			
			if (isset($data['target_keyword'][$data['store_id']][$data['language_id']])) {
				foreach ($data['target_keyword'][$data['store_id']][$data['language_id']] as $sort_order => $keyword) {
					$field_data = array(
						'field_code' => 'target_keyword',
						'filter' => array(
							'store_id' => $data['store_id'],
							'keyword' => $keyword
						)
					);
			
					$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
					
					if ($target_keywords) {
						$store_target_keywords = reset($target_keywords);
							
						if ((count($target_keywords) > 1) || (count(reset($store_target_keywords)) > 1)) {
							$data['target_keyword_duplicate'][$data['store_id']][$data['language_id']][$sort_order] = 1;
						}
					}
				}				
			}
		
			$html_tab_general_language[$data['language_id']] = $this->load->view($this->route . '/post_form_tab_general_language', $data);
		}
				
		return $html_tab_general_language;
	}
	
	public function post_form_tab_general_store_language() {
		$this->load->model($this->route);
		$this->load->model('extension/module/' . $this->codename);
		
		$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($field_info['sheet']['blog_post']['field'])) {
			$data['fields'] = $field_info['sheet']['blog_post']['field'];
		} else {
			$data['fields'] = array();
		}
		
		$data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();
										
		if (isset($this->request->post['meta_data'])) {
			$data['meta_data'] = $this->request->post['meta_data'];
		} elseif (isset($this->request->get['post_id'])) {
			$data['meta_data'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getPostMetaData($this->request->get['post_id']);
		} else {
			$data['meta_data'] = array();
		}
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['post_id'])) {	
			$data['target_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getPostTargetKeyword($this->request->get['post_id']);
		} else {
			$data['target_keyword'] = array();
		}
		
		if (isset($this->request->post['url_keyword'])) {
			$data['url_keyword'] = $this->request->post['url_keyword'];
		} elseif (isset($this->request->get['post_id'])) {
			$data['url_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getPostURLKeyword($this->request->get['post_id']);
		} else {
			$data['url_keyword'] = array();
		}
		
		$data['adviser_elements'] = array();
		$data['rating'] = array();
				
		if (isset($this->request->get['post_id'])) {
			$route = 'bm_post_id=' . $this->request->get['post_id'];
			
			$adviser_info = $this->load->controller('extension/module/d_seo_module_adviser/getAdviserInfo', $route);
		
			if (isset($adviser_info['adviser_elements']) && isset($adviser_info['rating'])) {
				$data['adviser_elements'] = $adviser_info['adviser_elements'];
				$data['rating'] = $adviser_info['rating'];
			}
		}
		
		$html_tab_general_store_language = array();
		
		foreach ($stores as $store) {
			$data['store_id'] = $store['store_id'];		
		
			foreach ($languages as $language) {
				$data['language_id'] = $language['language_id'];
				
				if (isset($data['target_keyword'][$data['store_id']][$data['language_id']])) {
					foreach ($data['target_keyword'][$data['store_id']][$data['language_id']] as $sort_order => $keyword) {
						$field_data = array(
							'field_code' => 'target_keyword',
							'filter' => array(
								'store_id' => $data['store_id'],
								'keyword' => $keyword
							)
						);
			
						$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
						
						if ($target_keywords) {
							$store_target_keywords = reset($target_keywords);
							
							if ((count($target_keywords) > 1) || (count(reset($store_target_keywords)) > 1)) {
								$data['target_keyword_duplicate'][$data['store_id']][$data['language_id']][$sort_order] = 1;
							}
						}
					}				
				}
		
				$html_tab_general_store_language[$data['store_id']][$data['language_id']] = $this->load->view($this->route . '/post_form_tab_general_store_language', $data);
			}
		}
		
		return $html_tab_general_store_language;
	}
	
	public function post_form_tab_links() {
		$this->load->model($this->route);
				
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($field_info['sheet']['blog_post']['field'])) {
			$data['fields'] = $field_info['sheet']['blog_post']['field'];
		} else {
			$data['fields'] = array();
		}
		
		$data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();
		
		if (isset($this->request->get['post_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$category_info = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getPostCategory($this->request->get['post_id']);
		}
		
		if (isset($this->request->post['category_id'])) {
			$data['category_id'] = $this->request->post['category_id'];
		} elseif (!empty($category_info)) {
			$data['category_id'] = $category_info['category_id'];
		} else {
			$data['category_id'] = 0;
		}
		
		if (isset($this->request->post['category_path'])) {
			$data['category_path'] = $this->request->post['category_path'];
		} elseif (!empty($category_info)) {
			$data['category_path'] = $category_info['category_path'];
		} else {
			$data['category_path'] = '';
		}
		
		return $this->load->view($this->route . '/post_form_tab_links', $data);
	}
	
	public function post_form_style() {		
		return $this->load->view($this->route . '/post_form_style');
	}
	
	public function post_form_script() {
		$_language = new Language();
		$_language->load($this->route);
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		$data['route'] = $this->route;
		$data['url_token'] = $url_token;
		
		return $this->load->view($this->route . '/post_form_script', $data);
	}
	
	public function post_validate_form($error) {
		if (isset($this->request->post['meta_data'])) {		
			$_language = new Language();
			$_language->load($this->route);
			
			foreach ($this->request->post['meta_data'] as $store_id => $language_meta_data) {
				foreach ($language_meta_data as $language_id => $meta_data) {
					if ($store_id) {
						if (isset($meta_data['title']) && ((utf8_strlen($meta_data['title']) < 3) || (utf8_strlen($meta_data['title']) > 255))) {
							$error['meta_data'][$store_id][$language_id]['title'] = $_language->get('error_post_title');
						}

						if (isset($meta_data['meta_title']) && ((utf8_strlen($meta_data['meta_title']) < 3) || (utf8_strlen($meta_data['meta_title']) > 255))) {
							$error['meta_data'][$store_id][$language_id]['meta_title'] = $_language->get('error_meta_title');
						}
					}
				}
			}
		}
		
		if (isset($this->request->post['url_keyword'])) {						
			foreach ($this->request->post['url_keyword'] as $store_id => $language_url_keyword) {
				foreach ($language_url_keyword as $language_id => $url_keyword) {
					if (trim($url_keyword)) {								
						$field_data = array(
							'field_code' => 'url_keyword',
							'filter' => array(
							'store_id' => $store_id,
							'keyword' => $url_keyword
							)
						);
			
						$url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
									
						if ($url_keywords) {
							if (isset($this->request->get['post_id'])) {
								foreach ($url_keywords as $route => $store_url_keywords) {
									if ($route != 'bm_post_id=' . $this->request->get['post_id']) {
										$error['url_keyword'][$store_id][$language_id] = sprintf($_language->get('error_url_keyword_exists'), $url_keyword);
									}
								}
							} else {
								$error['url_keyword'][$store_id][$language_id] = sprintf($_language->get('error_url_keyword_exists'), $url_keyword);
							}
						}
					}
				}
			}
		}
		
		$this->config->set($this->codename . '_error', $error);
				
		return $error;
	}
		
	public function post_add_post($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->savePostMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->savePostTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->savePostURLKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->savePostCategory($data);
	}
	
	public function post_edit_post($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->savePostMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->savePostTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->savePostURLKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->savePostCategory($data);
	}
	
	public function post_delete_post($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deletePostMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deletePostTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deletePostURLKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deletePostCategory($data);
	}
	
	public function author_form_tab_general_language() {
		$this->load->model($this->route);
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($field_info['sheet']['blog_author']['field'])) {
			$data['fields'] = $field_info['sheet']['blog_author']['field'];
		} else {
			$data['fields'] = array();
		}
		
		$data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();
					
		if (isset($this->request->post['meta_data'])) {
			$data['meta_data'] = $this->request->post['meta_data'];
		} elseif (isset($this->request->get['author_id'])) {
			$data['meta_data'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getAuthorMetaData($this->request->get['author_id']);
		} else {
			$data['meta_data'] = array();
		}
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['author_id'])) {
			$data['target_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getAuthorTargetKeyword($this->request->get['author_id']);
		} else {
			$data['target_keyword'] = array();
		}
		
		if (isset($this->request->post['url_keyword'])) {
			$data['url_keyword'] = $this->request->post['url_keyword'];
		} elseif (isset($this->request->get['author_id'])) {
			$data['url_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getAuthorURLKeyword($this->request->get['author_id']);
		} else {
			$data['url_keyword'] = array();
		}
		
		$data['adviser_elements'] = array();
		$data['rating'] = array();
				
		if (isset($this->request->get['author_id'])) {
			$route = 'bm_author_id=' . $this->request->get['author_id'];
			
			$adviser_info = $this->load->controller('extension/module/d_seo_module_adviser/getAdviserInfo', $route);
		
			if (isset($adviser_info['adviser_elements']) && isset($adviser_info['rating'])) {
				$data['adviser_elements'] = $adviser_info['adviser_elements'];
				$data['rating'] = $adviser_info['rating'];
			}
		}
		
		$data['store_id'] = 0;
			
		$html_tab_general_language = array();
				
		foreach ($languages as $language) {
			$data['language_id'] = $language['language_id'];
			
			if (isset($data['target_keyword'][$data['store_id']][$data['language_id']])) {
				foreach ($data['target_keyword'][$data['store_id']][$data['language_id']] as $sort_order => $keyword) {
					$field_data = array(
						'field_code' => 'target_keyword',
						'filter' => array(
							'store_id' => $data['store_id'],
							'keyword' => $keyword
						)
					);
			
					$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
					
					if ($target_keywords) {
						$store_target_keywords = reset($target_keywords);
							
						if ((count($target_keywords) > 1) || (count(reset($store_target_keywords)) > 1)) {
							$data['target_keyword_duplicate'][$data['store_id']][$data['language_id']][$sort_order] = 1;
						}
					}
				}				
			}
			
			$html_tab_general_language[$data['language_id']] = $this->load->view($this->route . '/author_form_tab_general_language', $data);
		}
				
		return $html_tab_general_language;
	}
	
	public function author_form_tab_general_store_language() {		
		$this->load->model($this->route);
		$this->load->model('extension/module/' . $this->codename);
		
		$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($field_info['sheet']['blog_author']['field'])) {
			$data['fields'] = $field_info['sheet']['blog_author']['field'];
		} else {
			$data['fields'] = array();
		}
		
		$data['error'] = ($this->config->get($this->codename . '_error')) ? $this->config->get($this->codename . '_error') : array();
										
		if (isset($this->request->post['meta_data'])) {
			$data['meta_data'] = $this->request->post['meta_data'];
		} elseif (isset($this->request->get['author_id'])) {
			$data['meta_data'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getAuthorMetaData($this->request->get['author_id']);
		} else {
			$data['meta_data'] = array();
		}
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['author_id'])) {	
			$data['target_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getAuthorTargetKeyword($this->request->get['author_id']);
		} else {
			$data['target_keyword'] = array();
		}
		
		if (isset($this->request->post['url_keyword'])) {
			$data['url_keyword'] = $this->request->post['url_keyword'];
		} elseif (isset($this->request->get['author_id'])) {
			$data['url_keyword'] = $this->{'model_extension_d_seo_module_blog_' . $this->codename}->getAuthorURLKeyword($this->request->get['author_id']);
		} else {
			$data['url_keyword'] = array();
		}
		
		$data['adviser_elements'] = array();
		$data['rating'] = array();
				
		if (isset($this->request->get['author_id'])) {
			$route = 'bm_author_id=' . $this->request->get['author_id'];
			
			$adviser_info = $this->load->controller('extension/module/d_seo_module_adviser/getAdviserInfo', $route);
		
			if (isset($adviser_info['adviser_elements']) && isset($adviser_info['rating'])) {
				$data['adviser_elements'] = $adviser_info['adviser_elements'];
				$data['rating'] = $adviser_info['rating'];
			}
		}
		
		$html_tab_general_store_language = array();
		
		foreach ($stores as $store) {
			$data['store_id'] = $store['store_id'];		
		
			foreach ($languages as $language) {
				$data['language_id'] = $language['language_id'];
				
				if (isset($data['target_keyword'][$data['store_id']][$data['language_id']])) {
					foreach ($data['target_keyword'][$data['store_id']][$data['language_id']] as $sort_order => $keyword) {
						$field_data = array(
							'field_code' => 'target_keyword',
							'filter' => array(
								'store_id' => $data['store_id'],
								'keyword' => $keyword
							)
						);
			
						$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
						
						if ($target_keywords) {
							$store_target_keywords = reset($target_keywords);
							
							if ((count($target_keywords) > 1) || (count(reset($store_target_keywords)) > 1)) {
								$data['target_keyword_duplicate'][$data['store_id']][$data['language_id']][$sort_order] = 1;
							}
						}
					}				
				}
		
				$html_tab_general_store_language[$data['store_id']][$data['language_id']] = $this->load->view($this->route . '/author_form_tab_general_store_language', $data);
			}
		}
		
		return $html_tab_general_store_language;
	}
		
	public function author_form_style() {		
		return $this->load->view($this->route . '/author_form_style');
	}
	
	public function author_form_script() {
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		$data['route'] = $this->route;
		$data['url_token'] = $url_token;
		
		return $this->load->view($this->route . '/author_form_script', $data);
	}
	
	public function author_validate_form($error) {
		$_language = new Language();
		$_language->load($this->route);
			
		if (isset($this->request->post['meta_data'])) {					
			foreach ($this->request->post['meta_data'] as $store_id => $language_meta_data) {
				foreach ($language_meta_data as $language_id => $meta_data) {
					if (isset($meta_data['name']) && ((utf8_strlen($meta_data['name']) < 2) || (utf8_strlen($meta_data['name']) > 255))) {
						$error['meta_data'][$store_id][$language_id]['name'] = $_language->get('error_author_name');
						$error['warning'] = $_language->get('error_warning');
					}

					if (isset($meta_data['meta_title']) && ((utf8_strlen($meta_data['meta_title']) < 3) || (utf8_strlen($meta_data['meta_title']) > 255))) {
						$error['meta_data'][$store_id][$language_id]['meta_title'] = $_language->get('error_meta_title');
						$error['warning'] = $_language->get('error_warning');
					}
				}
			}
		}
		
		if (isset($this->request->post['url_keyword'])) {			
			foreach ($this->request->post['url_keyword'] as $store_id => $language_url_keyword) {
				foreach ($language_url_keyword as $language_id => $url_keyword) {
					if (trim($url_keyword)) {								
						$field_data = array(
							'field_code' => 'url_keyword',
							'filter' => array(
							'store_id' => $store_id,
							'keyword' => $url_keyword
							)
						);
			
						$url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
									
						if ($url_keywords) {
							if (isset($this->request->get['author_id'])) {
								foreach ($url_keywords as $route => $store_url_keywords) {
									if ($route != 'bm_author_id=' . $this->request->get['author_id']) {
										$error['url_keyword'][$store_id][$language_id] = sprintf($_language->get('error_url_keyword_exists'), $url_keyword);
										$error['warning'] = $_language->get('error_warning');
									}
								}
							} else {
								$error['url_keyword'][$store_id][$language_id] = sprintf($_language->get('error_url_keyword_exists'), $url_keyword);
								$error['warning'] = $_language->get('error_warning');
							}
						}
					}
				}
			}
		}
		
		$this->config->set($this->codename . '_error', $error);
				
		return $error;
	}
		
	public function author_add_author($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveAuthorMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveAuthorTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveAuthorURLKeyword($data);
	}
	
	public function author_edit_author($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveAuthorMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveAuthorTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->saveAuthorURLKeyword($data);
	}
	
	public function author_delete_author($data) {
		$this->load->model($this->route);
		
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deleteAuthorMetaData($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deleteAuthorTargetKeyword($data);
		$this->{'model_extension_d_seo_module_blog_' . $this->codename}->deleteAuthorURLKeyword($data);
	}
	
	public function save($data) {
		if ($data['new_setting']['module_d_seo_module_blog_setting']['sheet']['blog_category']['short_url'] != $data['old_setting']['module_d_seo_module_blog_setting']['sheet']['blog_category']['short_url']) {
			$cache_data = array(
				'route' => 'bm_category_id=%',
				'store_id' => $data['store_id']
			);
			
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
		
		if ($data['new_setting']['module_d_seo_module_blog_setting']['sheet']['blog_post']['short_url'] != $data['old_setting']['module_d_seo_module_blog_setting']['sheet']['blog_post']['short_url']) {
			$cache_data = array(
				'route' => 'bm_post_id=%',
				'store_id' => $data['store_id']
			);
			
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
	}
}
