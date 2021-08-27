<?php
class ModelExtensionModuleDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	
	/*
	*	Refresh URL Cache.
	*/
	public function refreshURLCache($data) {	
		if (!$this->registry->has('d_cache')) return;
		
		$stores = $this->getStores();
		$languages = $this->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
				
		$blog_category_short_url = true;
		$blog_post_short_url = true;
		
		foreach ($stores as $store) {
			if (isset($data['store_id']) && ($data['store_id'] != $store['store_id'])) continue;
			if (!$data['setting'][$store['store_id']]['sheet']['blog_category']['short_url']) $blog_category_short_url = false;
			if (!$data['setting'][$store['store_id']]['sheet']['blog_post']['short_url']) $blog_post_short_url = false;
		}
				
		if (isset($data['route'])) {
			if (strpos($data['route'], 'bm_category_id') === 0) {
				$category_id = str_replace('bm_category_id=', '', $data['route']);
			}
			
			if (strpos($data['route'], 'bm_post_id') === 0) {
				$post_id = str_replace('bm_post_id=', '', $data['route']);
			}
			
			if (strpos($data['route'], 'bm_author_id') === 0) {
				$author_id = str_replace('bm_author_id=', '', $data['route']);
			}
			
			if ($data['route'] == 'extension/d_blog_module/search') {
				$route = $data['route'];
			}
		} else {
			$category_id = '%';
			$post_id = '%';
			$author_id = '%';
			$route = 'extension/d_blog_module/search';
		}
		
		if (isset($category_id)) {
			$add = '';
			
			if ($category_id != '%') {
				$add = " WHERE c.category_id = '" . (int)$category_id . "'";
			}
			
			if (($category_id != '%') && !$blog_category_short_url) {
				if (VERSION >= '3.0.0.0') {						
					$query = $this->db->query("SELECT cp.category_id AS category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category_path cp LEFT JOIN " . DB_PREFIX . "bm_category c2 ON (c2.category_id = cp.category_id) LEFT JOIN " . DB_PREFIX . "bm_category c ON (c.category_id = cp.path_id) LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', cp.category_id))" . $add);
				} else {
					$query = $this->db->query("SELECT cp.category_id AS category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category_path cp LEFT JOIN " . DB_PREFIX . "bm_category c2 ON (c2.category_id = cp.category_id) LEFT JOIN " . DB_PREFIX . "bm_category c ON (c.category_id = cp.path_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', cp.category_id))" . $add);
				}
			} else {
				if (VERSION >= '3.0.0.0') {
					$query = $this->db->query("SELECT c.category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id))" . $add);
				} else {
					$query = $this->db->query("SELECT c.category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id))" . $add);
				}
			}
			
			$categories = array();
			$sub_categories = array();
													
			foreach ($query->rows as $result) {
				$categories[$result['category_id']]['category_id'] = $result['category_id'];
							
				if (!isset($categories[$result['category_id']]['url_keyword'])) {
					$categories[$result['category_id']]['url_keyword'] = array();
				}
			
				if ((isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status'])) {
					$categories[$result['category_id']]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
				} elseif ($result['store_id'] == 0) {
					foreach ($stores as $store) {
						$categories[$result['category_id']]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
					}
				}
			}
			
			foreach ($categories as $category) {
				foreach ($stores as $store) {
					if (($category_id != '%') && ($category_id != $category['category_id']) && $data['setting'][$store['store_id']]['sheet']['blog_category']['short_url']) continue;
					if (isset($data['store_id']) && ($data['store_id'] != $store['store_id'])) continue;
					
					if (!$data['setting'][$store['store_id']]['sheet']['blog_category']['short_url']) {
						$category_path = $this->getCategoryPath($category['category_id']);
					} else {
						$category_path = $category['category_id'];						
					}
															
					foreach ($languages as $language) {					
						if (isset($data['language_id']) && ($data['language_id'] != $language['language_id'])) continue;
						
						$url_rewrite = '';
				
						if ($category_path != $category['category_id']) {
							if (!$sub_categories) {
								if (VERSION >= '3.0.0.0') {
									$query = $this->db->query("SELECT c.category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id))");
								} else {
									$query = $this->db->query("SELECT c.category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id))");
								}
																	
								foreach ($query->rows as $result) {
									$sub_categories[$result['category_id']]['category_id'] = $result['category_id'];
							
									if (!isset($sub_categories[$result['category_id']]['url_keyword'])) {
										$sub_categories[$result['category_id']]['url_keyword'] = array();
									}
			
									if ((isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status'])) {
										$sub_categories[$result['category_id']]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
									} elseif ($result['store_id'] == 0) {
										foreach ($stores as $store) {
											$sub_categories[$result['category_id']]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
										}
									}
								}
							}

							$sub_categories_id = explode('_', $category_path);
							
							foreach ($sub_categories_id as $sub_category_id) {
								if (isset($sub_categories[$sub_category_id]['url_keyword'][$store['store_id']][$language['language_id']]) && $sub_categories[$sub_category_id]['url_keyword'][$store['store_id']][$language['language_id']]) {
									$url_rewrite .= '/' . $sub_categories[$sub_category_id]['url_keyword'][$store['store_id']][$language['language_id']];
								} else {
									$url_rewrite = '';

									break;
								}
							}
						} else {								
							if (isset($categories[$category['category_id']]['url_keyword'][$store['store_id']][$language['language_id']]) && $categories[$category['category_id']]['url_keyword'][$store['store_id']][$language['language_id']]) {
								$url_rewrite .= '/' . $categories[$category['category_id']]['url_keyword'][$store['store_id']][$language['language_id']];
							} else {
								$url_rewrite = '';
							}
						}
												
						$this->d_cache->set($this->codename, 'url_rewrite.' . md5('extension/d_blog_module/category') . '.' . $category['category_id'] . '.' . $store['store_id'] . '.' . $language['language_id'], $url_rewrite);								
					}
				}
			}
		}	

		if (isset($post_id) || (isset($category_id) && !$blog_post_short_url)) {			
			if (isset($category_id)) {
				if ($category_id != '%') {
					if (VERSION >= '3.0.0.0') {						
						$query = $this->db->query("SELECT pc.post_id AS post_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "d_post_category pc LEFT JOIN " . DB_PREFIX . "bm_category_path cp ON (cp.category_id = pc.category_id) LEFT JOIN " . DB_PREFIX . "bm_category c2 ON (c2.category_id = cp.category_id) LEFT JOIN " . DB_PREFIX . "bm_category c ON (c.category_id = cp.path_id) LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_post_id=', pc.post_id)) WHERE c.category_id = '" . (int)$category_id . "'");
					} else {
						$query = $this->db->query("SELECT pc.post_id AS post_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "d_post_category pc LEFT JOIN " . DB_PREFIX . "bm_category_path cp ON (cp.category_id = pc.category_id) LEFT JOIN " . DB_PREFIX . "bm_category c2 ON (c2.category_id = cp.category_id) LEFT JOIN " . DB_PREFIX . "bm_category c ON (c.category_id = cp.path_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_post_id=', pc.post_id)) WHERE c.category_id = '" . (int)$category_id . "'");
					}
				} else {
					$post_id = '%';
				}
			} 
				
			if (isset($post_id)) {
				$add = '';
				
				if ($post_id != '%') {
					$add .= " WHERE p.post_id = '" . (int)$post_id . "'";
				}
						
				if (VERSION >= '3.0.0.0') {		
					$query = $this->db->query("SELECT p.post_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_post_id=', p.post_id))" . $add);
				} else {
					$query = $this->db->query("SELECT p.post_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_post_id=', p.post_id))" . $add);
				}
			}
			
			$posts = array();
			$sub_categories = array();
			
			foreach ($query->rows as $result) {
				$posts[$result['post_id']]['post_id'] = $result['post_id'];
			
				if (!isset($posts[$result['post_id']]['url_keyword'])) {
					$posts[$result['post_id']]['url_keyword'] = array();
				}
			
				if ((isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status'])) {
					$posts[$result['post_id']]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
				} elseif ($result['store_id'] == 0) {
					foreach ($stores as $store) {
						$posts[$result['post_id']]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
					}
				}
			}
			
			foreach ($posts as $post) {
				foreach ($stores as $store) {
					if (isset($data['store_id']) && ($data['store_id'] != $store['store_id'])) continue;
					
					if (!$data['setting'][$store['store_id']]['sheet']['blog_post']['short_url']) {
						$post_path = $this->getPostPath($post['post_id']);
					} else {
						$post_path = '';						
					}
					
					foreach ($languages as $language) {					
						if (isset($data['language_id']) && ($data['language_id'] != $language['language_id'])) continue;
						
						$url_rewrite = '';
					
						if ($post_path) {
							if (!$sub_categories) {
								if (VERSION >= '3.0.0.0') {
									$query = $this->db->query("SELECT c.category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id))");
								} else {
									$query = $this->db->query("SELECT c.category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id))");
								}
									
								foreach ($query->rows as $result) {
									$sub_categories[$result['category_id']]['category_id'] = $result['category_id'];
							
									if (!isset($sub_categories[$result['category_id']]['url_keyword'])) {
										$sub_categories[$result['category_id']]['url_keyword'] = array();
									}
			
									if ((isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status'])) {
										$sub_categories[$result['category_id']]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
									} elseif ($result['store_id'] == 0) {
										foreach ($stores as $store) {
											$sub_categories[$result['category_id']]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
										}
									}
								}
							}

							$sub_categories_id = explode('_', $post_path);
					
							foreach ($sub_categories_id as $sub_category_id) {									
								if (isset($sub_categories[$sub_category_id]['url_keyword'][$store['store_id']][$language['language_id']]) && $sub_categories[$sub_category_id]['url_keyword'][$store['store_id']][$language['language_id']]) {
									$url_rewrite .= '/' . $sub_categories[$sub_category_id]['url_keyword'][$store['store_id']][$language['language_id']];
								} else {
									$url_rewrite = '';

									break;
								}
							}
						}
						
						if (isset($posts[$post['post_id']]['url_keyword'][$store['store_id']][$language['language_id']]) && $posts[$post['post_id']]['url_keyword'][$store['store_id']][$language['language_id']]) {
							$url_rewrite .= '/' . $posts[$post['post_id']]['url_keyword'][$store['store_id']][$language['language_id']];
						} else {
							$url_rewrite = '';
						}
						
						$this->d_cache->set($this->codename, 'url_rewrite.' . md5('extension/d_blog_module/post') . '.' . $post['post_id'] . '.' . $store['store_id'] . '.' . $language['language_id'], $url_rewrite);
					}
				}
			}
		}
		
		if (isset($author_id)) {
			$add = '';
			
			if ($author_id != '%') {
				$add .= " WHERE a.author_id = '" . (int)$author_id . "'";
			}
			
			if (VERSION >= '3.0.0.0') {
				$query = $this->db->query("SELECT a.author_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_author_id=', a.author_id))" . $add);
			} else {
				$query = $this->db->query("SELECT a.author_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_author_id=', a.author_id))" . $add);
			}
			
			$authors = array();
		
			foreach ($query->rows as $result) {
				$authors[$result['author_id']]['author_id'] = $result['author_id'];
			
				if (!isset($authors[$result['author_id']]['url_keyword'])) {
					$authors[$result['author_id']]['url_keyword'] = array();
				}
			
				if ((isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status'])) {
					$authors[$result['author_id']]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
				} elseif ($result['store_id'] == 0) {
					foreach ($stores as $store) {
						$authors[$result['author_id']]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
					}
				}
			}
			
			foreach ($authors as $author) {
				foreach ($stores as $store) {
					if (isset($data['store_id']) && ($data['store_id'] != $store['store_id'])) continue;
					
					foreach ($languages as $language) {					
						if (isset($data['language_id']) && ($data['language_id'] != $language['language_id'])) continue;
						
						$url_rewrite = '';
						
						if (isset($authors[$author['author_id']]['url_keyword'][$store['store_id']][$language['language_id']]) && $authors[$author['author_id']]['url_keyword'][$store['store_id']][$language['language_id']]) {
							$url_rewrite .= '/' . $authors[$author['author_id']]['url_keyword'][$store['store_id']][$language['language_id']];
						}
													
						$this->d_cache->set($this->codename, 'url_rewrite.' . md5('extension/d_blog_module/author') . '.' . $author['author_id'] . '.' . $store['store_id'] . '.' . $language['language_id'], $url_rewrite);
					}
				}
			}
		}
		
		if (isset($route)) {			
			if (VERSION >= '3.0.0.0') {
				$query = $this->db->query("SELECT query as route, store_id, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($route) . "'");
			} else {
				$query = $this->db->query("SELECT route, store_id, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route = '" . $this->db->escape($route) . "'");
			}
			
			$custom_pages = array();
		
			foreach ($query->rows as $result) {
				$custom_pages[$result['route']]['route'] = $result['route'];
				
				if ((isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status'])) {
					$custom_pages[$result['route']]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
				} elseif ($result['store_id'] == 0) {
					foreach ($stores as $store) {
						$custom_pages[$result['route']]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
					}
				}
			}
			
			foreach ($custom_pages as $custom_page) {
				foreach ($stores as $store) {
					if (isset($data['store_id']) && ($data['store_id'] != $store['store_id'])) continue;
					
					foreach ($languages as $language) {					
						if (isset($data['language_id']) && ($data['language_id'] != $language['language_id'])) continue;
						
						$url_rewrite = '';
						
						if (isset($custom_pages[$custom_page['route']]['url_keyword'][$store['store_id']][$language['language_id']])) {
							$url_keyword = $custom_pages[$custom_page['route']]['url_keyword'][$store['store_id']][$language['language_id']];

							if ($url_keyword) {
								if (substr($url_keyword, 0, 1) == '/') {
									$url_keyword = substr($url_keyword, 1, strlen($url_keyword) - 1);
								}
						
								$url_rewrite .= '/' . $url_keyword;	
							}
						} 
																			
						$this->d_cache->set($this->codename, 'url_rewrite.' . md5($custom_page['route']) . '.' . $store['store_id'] . '.' . $language['language_id'], $url_rewrite);
					}
				}
			}
		}
	}
	
	/*
	*	Return Category Path.
	*/		
	public function getCategoryPath($category_id) {
		$path = false;
				
		$query = $this->db->query("SELECT GROUP_CONCAT(c.category_id ORDER BY level SEPARATOR '_') as category_path FROM " . DB_PREFIX . "bm_category_path cp LEFT JOIN " . DB_PREFIX . "bm_category c ON (cp.path_id = c.category_id) WHERE cp.category_id = '" . (int)$category_id . "' GROUP BY cp.category_id");
		
		if ($query->num_rows) {
			$path = $query->row['category_path'];
		}
				
		return $path;
	}

	/*
	*	Return Post Path.
	*/		
	public function getPostPath($post_id) {		
		$path = false;
		
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "d_post_category WHERE post_id = '" . (int)$post_id . "'");
		
		if ($query->num_rows) {
			if ($query->row['category_id']) {
				$path = $this->getCategoryPath($query->row['category_id']);
			}
		}
				
		return $path;
	}
	
	/*
	*	Save SEO extensions.
	*/
	public function saveSEOExtensions($seo_extensions) {
		$this->load->model('setting/setting');
		
		$setting['d_seo_extension_install'] = $seo_extensions;
		
		$this->model_setting_setting->editSetting('d_seo_extension', $setting);
	}
	
	/*
	*	Return list of installed SEO extensions.
	*/
	public function getInstalledSEOExtensions() {
		$this->load->model('setting/setting');
				
		$installed_extensions = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");
		
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}
		
		$installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
		$installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();
		
		$seo_extensions = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/d_seo_module/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$seo_extension = basename($file, '.php');
				
				if (in_array($seo_extension, $installed_extensions) && in_array($seo_extension, $installed_seo_extensions)) {
					$seo_extensions[] = $seo_extension;
				}
			}
		}
		
		return $seo_extensions;
	}
	
	/*
	*	Return list of installed SEO Blog extensions.
	*/
	public function getInstalledSEOBlogExtensions() {
		$this->load->model('setting/setting');
				
		$installed_extensions = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");
		
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}
		
		$installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
		$installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();
		
		$seo_blog_extensions = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/' . $this->codename . '/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$seo_blog_extension = basename($file, '.php');
				
				if (in_array($seo_blog_extension, $installed_extensions) && in_array($seo_blog_extension, $installed_seo_extensions)) {
					$seo_blog_extensions[] = $seo_blog_extension;
				}
			}
		}
		
		return $seo_blog_extensions;
	}
		
	/*
	*	Return list of languages.
	*/
	public function getLanguages() {
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $key => $language) {
            if (VERSION >= '2.2.0.0') {
                $languages[$key]['flag'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
            } else {
                $languages[$key]['flag'] = 'view/image/flags/' . $language['image'];
            }
        }
		
		return $languages;
	}
	
	/*
	*	Return list of stores.
	*/
	public function getStores() {
		$this->load->model('setting/store');
		
		$result = array();
		
		$result[] = array(
			'store_id' => 0, 
			'name' => $this->config->get('config_name')
		);
		
		$stores = $this->model_setting_store->getStores();
		
		if ($stores) {			
			foreach ($stores as $store) {
				$result[] = array(
					'store_id' => $store['store_id'],
					'name' => $store['name']	
				);
			}	
		}
		
		return $result;
	}
	
	/*
	*	Return store.
	*/
	public function getStore($store_id) {
		$this->load->model('setting/store');
		
		$result = array();
		
		if ($store_id == 0) {
			$result = array(
				'store_id' => 0, 
				'name' => $this->config->get('config_name'),
				'url' => HTTP_CATALOG,
				'ssl' => HTTPS_CATALOG
			);
		} else {
			$store = $this->model_setting_store->getStore($store_id);
			
			$result = array(
				'store_id' => $store['store_id'],
				'name' => $store['name'],
				'url' => $store['url'],
				'ssl' => $store['ssl']
			);
		}
				
		return $result;
	}
	
	/*
	*	Return URL Info.
	*/	
	public function getURLInfo($url) {						
		$url_info = parse_url(str_replace('&amp;', '&', $url));
		
		$url_info['scheme'] = isset($url_info['scheme']) ? $url_info['scheme'] . '://' : '';
		$url_info['user'] = isset($url_info['user']) ? $url_info['user'] : '';
		$url_info['pass'] = isset($url_info['pass']) ? ':' . $url_info['pass']  : '';
		$url_info['pass'] = ($url_info['user'] || $url_info['pass']) ? $url_info['pass'] . '@' : ''; 
		$url_info['host'] = isset($url_info['host']) ? $url_info['host'] : '';
		$url_info['port'] = isset($url_info['port']) ? ':' . $url_info['port'] : '';
		$url_info['path'] = isset($url_info['path']) ? $url_info['path'] : '';		
		
		$url_info['data'] = array();
		
		if (isset($url_info['query'])) {
			parse_str($url_info['query'], $url_info['data']);
		}
		
		$url_info['query'] = isset($url_info['query']) ? '?' . $url_info['query'] : '';
		$url_info['fragment'] = isset($url_info['fragment']) ? '#' . $url_info['fragment'] : '';
						
		return $url_info;
	}
	
	/*
	*	Install.
	*/	
	public function installExtension() {
		$stores = $this->getStores();
		$languages = $this->getLanguages();
		
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "d_meta_data (route VARCHAR(255) NOT NULL, store_id INT(11) NOT NULL, language_id INT(11) NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, short_description TEXT NOT NULL, meta_title VARCHAR(255) NOT NULL, meta_description TEXT NOT NULL, meta_keyword TEXT NOT NULL, tag TEXT NOT NULL, custom_title_1 VARCHAR(255) NOT NULL, custom_title_2 VARCHAR(255) NOT NULL, custom_image_title VARCHAR(255) NOT NULL, custom_image_alt VARCHAR(255) NOT NULL, meta_robots VARCHAR(32) NOT NULL DEFAULT 'index,follow', PRIMARY KEY (route, store_id, language_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "d_meta_data' ORDER BY ORDINAL_POSITION");
        
        $columns = array();
        
		foreach ($query->rows as $column) {
            $columns[] = $column['COLUMN_NAME'];
        }
		
		if (!in_array('short_description', $columns)) {
           $this->db->query("ALTER TABLE " . DB_PREFIX . "d_meta_data ADD COLUMN short_description TEXT NOT NULL AFTER description");
        }
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search'");
						
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_category_description");
		
		foreach ($query->rows as $result) {
			foreach ($stores as $store) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = 'bm_category_id=" . (int)$result['category_id'] . "', store_id = '" . (int)$store['store_id'] . "', language_id='" . (int)$result['language_id'] . "'");
			}
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_post_description");
		
		foreach ($query->rows as $result) {
			foreach ($stores as $store) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = 'bm_post_id=" . (int)$result['post_id'] . "', store_id = '" . (int)$store['store_id'] . "', language_id='" . (int)$result['language_id'] . "'");
			}
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bm_author_description");
		
		foreach ($query->rows as $result) {
			foreach ($stores as $store) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = 'bm_author_id=" . (int)$result['author_id'] . "', store_id = '" . (int)$store['store_id'] . "', language_id='" . (int)$result['language_id'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search'");
		
		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword (route, store_id, language_id, keyword) VALUES ('extension/d_blog_module/search', '" . (int)$store['store_id'] . "', '" . (int)$language['language_id'] . "', 'blog-search')");
			}
		}
		
		if (VERSION >= '3.0.0.0') {		
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'bm_category_id=%' OR query LIKE 'bm_post_id=%' OR query LIKE 'bm_author_id=%' OR query = 'extension/d_blog_module/search'");
			
			foreach ($stores as $store) {
				foreach ($languages as $language) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url (query, store_id, language_id, keyword) VALUES ('extension/d_blog_module/search', '" . (int)$store['store_id'] . "', '" . (int)$language['language_id'] . "', 'blog-search')");
				}
			}
		} else {
			$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "d_url_keyword (route VARCHAR(255) NOT NULL, store_id INT(11) NOT NULL, language_id INT(11) NOT NULL, keyword VARCHAR(255) NOT NULL, PRIMARY KEY (route, store_id, language_id), KEY keyword (keyword)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'bm_category_id=%' OR query LIKE 'bm_post_id=%' OR query LIKE 'bm_author_id=%' OR query = 'extension/d_blog_module/search'");
			
			foreach ($stores as $store) {
				foreach ($languages as $language) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword (route, store_id, language_id, keyword) VALUES ('extension/d_blog_module/search', '" . (int)$store['store_id'] . "', '" . (int)$language['language_id'] . "', 'blog-search')");
					
					if (($store['store_id'] == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias (query, keyword) VALUES ('extension/d_blog_module/search', 'blog-search')");
					}
				}
			}
		}
		
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "d_post_category");
		
		$this->db->query("CREATE TABLE " . DB_PREFIX . "d_post_category (post_id INT(11) NOT NULL, category_id INT(11) NOT NULL, PRIMARY KEY (post_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");		
		
		$query = $this->db->query("SELECT DISTINCT pc.post_id, pc.category_id, GROUP_CONCAT(cp.path_id ORDER BY cp.level SEPARATOR '_') AS category_path FROM " . DB_PREFIX . "bm_post_to_category pc LEFT JOIN " . DB_PREFIX . "bm_category_path cp ON (cp.category_id = pc.category_id) GROUP BY pc.post_id, cp.category_id ORDER BY category_path");
				
		$post_category = array();
		
		foreach ($query->rows as $result) {
			$post_category[$result['post_id']] = $result['category_id'];
		}
		
		foreach ($post_category as $post_id => $category_id) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "d_post_category SET post_id = '" . (int)$post_id . "', category_id = '" . (int)$category_id . "'");
		}
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache');
	}
	
	/*
	*	Uninstall.
	*/
	public function uninstallExtension() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search'");
						
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search'");
		
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'bm_category_id=%' OR query LIKE 'bm_post_id=%' OR query LIKE 'bm_author_id=%' OR query = 'extension/d_blog_module/search'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'bm_category_id=%' OR query LIKE 'bm_post_id=%' OR query LIKE 'bm_author_id=%' OR query = 'extension/d_blog_module/search'");
		}
		
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "d_post_category");
		
		if (file_exists(DIR_SYSTEM . 'library/d_cache.php')) {
			$cache = new d_cache();
			$cache->deleteAll($this->codename);
		}
	}
}
?>