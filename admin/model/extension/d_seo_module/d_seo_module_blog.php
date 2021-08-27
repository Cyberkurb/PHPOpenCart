<?php
class ModelExtensionDSEOModuleDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module/d_seo_module_blog';
	
	/*
	*	Add Language.
	*/
	public function addLanguage($data) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_meta_data WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
		
		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = '" . $this->db->escape($result['route']) . "', store_id = '" . (int)$result['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', name = '" . $this->db->escape($result['name']) . "', title = '" . $this->db->escape($result['title']) . "', short_description = '" . $this->db->escape($result['short_description']) . "', description = '" . $this->db->escape($result['description']) . "', meta_title = '" . $this->db->escape($result['meta_title']) . "', meta_description = '" . $this->db->escape($result['meta_description']) . "', meta_keyword = '" . $this->db->escape($result['meta_keyword']) . "', tag = '" . $this->db->escape($result['tag']) . "', custom_title_1 = '" . $this->db->escape($result['custom_title_1']) . "', custom_title_2 = '" . $this->db->escape($result['custom_title_2']) . "', custom_image_title = '" . $this->db->escape($result['custom_image_title']) . "', custom_image_alt = '" . $this->db->escape($result['custom_image_alt']) . "', meta_robots = '" . $this->db->escape($result['meta_robots']) . "'");
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_target_keyword WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
			
		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = '" . $this->db->escape($result['route']) . "', store_id = '" . (int)$result['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', sort_order = '" . (int)$result['sort_order'] . "', keyword = '" . $this->db->escape($result['keyword']) . "'");
		}
		
		if (VERSION >= '3.0.0.0') {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND (query LIKE 'bm_category_id=%' OR query LIKE 'bm_post_id=%' OR query LIKE 'bm_author_id=%' OR query = 'extension/d_blog_module/search')");
			
			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = '" . $this->db->escape($result['query']) . "', store_id = '" . (int)$result['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', keyword = '" . $this->db->escape($result['keyword']) . "'");
			}
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_url_keyword WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
			
			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = '" . $this->db->escape($result['route']) . "', store_id = '" . (int)$result['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', keyword = '" . $this->db->escape($result['keyword']) . "'");
			}
		}
		
		$cache_data = array(
			'language_id' => $data['language_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Delete Language.
	*/
	public function deleteLanguage($data) {		
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE language_id = '" . (int)$data['language_id'] . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE language_id = '" . (int)$data['language_id'] . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
		
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE language_id = '" . (int)$data['language_id'] . "' AND (query LIKE 'bm_category_id=%' OR query LIKE 'bm_post_id=%' OR query LIKE 'bm_author_id=%' OR query = 'extension/d_blog_module/search')");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE language_id = '" . (int)$data['language_id'] . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
		}
				
		$cache_data = array(
			'language_id' => $data['language_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Delete Store.
	*/
	public function deleteStore($data) {	
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE store_id = '" . (int)$data['store_id'] . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE store_id = '" . (int)$data['store_id'] . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
		
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . (int)$data['store_id'] . "' AND (query LIKE 'bm_category_id=%' OR query LIKE 'bm_post_id=%' OR query LIKE 'bm_author_id=%' OR query = 'extension/d_blog_module/search')");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE store_id = '" . (int)$data['store_id'] . "' AND (route LIKE 'bm_category_id=%' OR route LIKE 'bm_post_id=%' OR route LIKE 'bm_author_id=%' OR route = 'extension/d_blog_module/search')");
		}
	}
	
	/*
	*	Return Target Elements.
	*/	
	public function getTargetElements($data) {
		$this->load->model('extension/module/' . $this->codename);
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .=  'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .=  'user_token=' . $this->session->data['user_token'];
		}
		
		$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		$target_elements = array();	
		
		if ($data['sheet_code'] == 'blog_category') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
				$target_keyword_store_id = $data['store_id'];
			} else {
				$target_keyword_store_id = 0;
			}
						
			$sql = "SELECT tk.route, tk.language_id, tk.sort_order, tk.keyword, c.category_id FROM " . DB_PREFIX . "d_target_keyword tk LEFT JOIN " . DB_PREFIX . "bm_category c ON (CONCAT('bm_category_id=', c.category_id) = tk.route) LEFT JOIN " . DB_PREFIX . "d_target_keyword tk2 ON (tk2.route = tk.route AND tk2.store_id = '" . (int)$target_keyword_store_id . "') WHERE tk.route LIKE 'bm_category_id=%' AND tk.store_id = '" . (int)$target_keyword_store_id . "'";
			
			$implode = array();
			$implode_language = array();
						
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'route') {
						$implode[] = "tk2.route = '" . $this->db->escape($filter) . "'";
					}
										
					if ($field_code == 'target_keyword') {
						foreach ($filter as $language_id => $value) {
							if (!empty($value)) {
								$implode_language[] = "(tk2.language_id = '" . (int)$language_id . "' AND tk2.keyword LIKE '%" . $this->db->escape($value) . "%')";
							}
						}
					}
				}
			}
			
			if ($implode_language) {
				$implode[] = '(' . implode(' OR ', $implode_language) . ')';
			}
			
			if ($implode) {
				$sql .= " AND " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY tk.route, tk.language_id, tk.sort_order";
			
			$query = $this->db->query($sql);
						
			foreach ($query->rows as $result) {
				$target_elements[$result['route']]['route'] = $result['route'];
				$target_elements[$result['route']]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
					
				if ($result['category_id']) {
					$target_elements[$result['route']]['link'] = $this->url->link('extension/d_blog_module/category/edit', $url_token . '&category_id=' . $result['category_id'], true);
				}
			}
					
			return $target_elements;
		}
				
		if ($data['sheet_code'] == 'blog_post') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
				$target_keyword_store_id = $data['store_id'];
			} else {
				$target_keyword_store_id = 0;
			}
						
			$sql = "SELECT tk.route, tk.language_id, tk.sort_order, tk.keyword, p.post_id FROM " . DB_PREFIX . "d_target_keyword tk LEFT JOIN " . DB_PREFIX . "bm_post p ON (CONCAT('bm_post_id=', p.post_id) = tk.route) LEFT JOIN " . DB_PREFIX . "d_target_keyword tk2 ON (tk2.route = tk.route AND tk2.store_id = '" . (int)$target_keyword_store_id . "') WHERE tk.route LIKE 'bm_post_id=%' AND tk.store_id = '" . (int)$target_keyword_store_id . "'";
			
			$implode = array();
			$implode_language = array();
						
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'route') {
						$implode[] = "tk2.route = '" . $this->db->escape($filter) . "'";
					}
										
					if ($field_code == 'target_keyword') {
						foreach ($filter as $language_id => $value) {
							if (!empty($value)) {
								$implode_language[] = "(tk2.language_id = '" . (int)$language_id . "' AND tk2.keyword LIKE '%" . $this->db->escape($value) . "%')";
							}
						}
					}
				}
			}
			
			if ($implode_language) {
				$implode[] = '(' . implode(' OR ', $implode_language) . ')';
			}
			
			if ($implode) {
				$sql .= " AND " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY tk.route, tk.language_id, tk.sort_order";
			
			$query = $this->db->query($sql);
						
			foreach ($query->rows as $result) {
				$target_elements[$result['route']]['route'] = $result['route'];
				$target_elements[$result['route']]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
					
				if ($result['post_id']) {
					$target_elements[$result['route']]['link'] = $this->url->link('extension/d_blog_module/post/edit', $url_token . '&post_id=' . $result['post_id'], true);
				}
			}
					
			return $target_elements;	
		}
		
		if ($data['sheet_code'] == 'blog_author') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
				$target_keyword_store_id = $data['store_id'];
			} else {
				$target_keyword_store_id = 0;
			}
						
			$sql = "SELECT tk.route, tk.language_id, tk.sort_order, tk.keyword, a.author_id FROM " . DB_PREFIX . "d_target_keyword tk LEFT JOIN " . DB_PREFIX . "bm_author a ON (CONCAT('bm_author_id=', a.author_id) = tk.route) LEFT JOIN " . DB_PREFIX . "d_target_keyword tk2 ON (tk2.route = tk.route AND tk2.store_id = '" . (int)$target_keyword_store_id . "') WHERE tk.route LIKE 'bm_author_id=%' AND tk.store_id = '" . (int)$target_keyword_store_id . "'";
			
			$implode = array();
			$implode_language = array();
						
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'route') {
						$implode[] = "tk2.route = '" . $this->db->escape($filter) . "'";
					}
										
					if ($field_code == 'target_keyword') {
						foreach ($filter as $language_id => $value) {
							if (!empty($value)) {
								$implode_language[] = "(tk2.language_id = '" . (int)$language_id . "' AND tk2.keyword LIKE '%" . $this->db->escape($value) . "%')";
							}
						}
					}
				}
			}
			
			if ($implode_language) {
				$implode[] = '(' . implode(' OR ', $implode_language) . ')';
			}
			
			if ($implode) {
				$sql .= " AND " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY tk.route, tk.language_id, tk.sort_order";
			
			$query = $this->db->query($sql);
						
			foreach ($query->rows as $result) {
				$target_elements[$result['route']]['route'] = $result['route'];
				$target_elements[$result['route']]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
				
				if ($result['author_id']) {
					$target_elements[$result['route']]['link'] = $this->url->link('extension/d_blog_module/author/edit', $url_token . '&author_id=' . $result['author_id'], true);
				}
			}
					
			return $target_elements;	
		}
		
		if ($data['sheet_code'] == 'custom_page') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
				$target_keyword_store_id = $data['store_id'];
			} else {
				$target_keyword_store_id = 0;
			}
			
			$sql = "SELECT tk.route, tk.language_id, tk.sort_order, tk.keyword FROM " . DB_PREFIX . "d_target_keyword tk LEFT JOIN " . DB_PREFIX . "d_target_keyword tk2 ON (tk2.route = tk.route AND tk2.store_id = '" . (int)$target_keyword_store_id . "') WHERE tk.route = 'extension/d_blog_module/search' AND tk.store_id = '" . (int)$target_keyword_store_id . "'";
			
			$implode = array();
			$implode_language = array();
						
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'route') {
						$implode[] = "tk2.route = '" . $this->db->escape($filter) . "'";
					}
										
					if ($field_code == 'target_keyword') {
						foreach ($filter as $language_id => $value) {
							if (!empty($value)) {
								$implode_language[] = "(tk2.language_id = '" . (int)$language_id . "' AND tk2.keyword LIKE '%" . $this->db->escape($value) . "%')";
							}
						}
					}
				}
			}
			
			if ($implode_language) {
				$implode[] = '(' . implode(' OR ', $implode_language) . ')';
			}
			
			if ($implode) {
				$sql .= " AND " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY tk.route, tk.language_id, tk.sort_order";
			
			$query = $this->db->query($sql);
						
			foreach ($query->rows as $result) {
				$target_elements[$result['route']]['route'] = $result['route'];
				$target_elements[$result['route']]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
			}
									
			return $target_elements;
		}
	}
					
	/*
	*	Add Target Element.
	*/
	public function addTargetElement($data) {
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
				
		if (isset($data['route']) && isset($data['store_id']) && isset($data['target_keyword'])) {
			if ((strpos($data['route'], 'bm_category_id') === 0) || (strpos($data['route'], 'bm_post_id') === 0) || (strpos($data['route'], 'bm_author_id') === 0) || ($data['route'] == 'extension/d_blog_module/search')) {	
				$target_keyword_store_id = 0;
				
				if (strpos($data['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if ($data['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
										
				foreach ($data['target_keyword'] as $language_id => $target_keyword) {
					preg_match_all('/\[[^]]+\]/', $target_keyword, $keywords);
				
					$sort_order = 1;
		
					foreach ($keywords[0] as $keyword) {
						$keyword = substr($keyword, 1, strlen($keyword) - 2);
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = '" . $this->db->escape($data['route']) . "', store_id = '" . (int)$target_keyword_store_id . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
						$sort_order++;
					}
				}
			}
		}
	}
	
	/*
	*	Edit Target Element.
	*/
	public function editTargetElement($data) {
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
				
		if (isset($data['route']) && isset($data['store_id']) && isset($data['language_id']) && isset($data['target_keyword'])) {
			if ((strpos($data['route'], 'bm_category_id') === 0) || (strpos($data['route'], 'bm_post_id') === 0) || (strpos($data['route'], 'bm_author_id') === 0) || ($data['route'] == 'extension/d_blog_module/search')) {	
				$target_keyword_store_id = 0;
				
				if (strpos($data['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if ($data['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
						
				$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
				
				if ($data['target_keyword']) {
					preg_match_all('/\[[^]]+\]/', $data['target_keyword'], $keywords);
				
					$sort_order = 1;
		
					foreach ($keywords[0] as $keyword) {
						$keyword = substr($keyword, 1, strlen($keyword) - 2);
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = '" . $this->db->escape($data['route']) . "', store_id = '" . (int)$target_keyword_store_id . "', language_id = '" . (int)$data['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
						$sort_order++;
					}
				}
			}
		}
	}
	
	/*
	*	Delete Target Element.
	*/
	public function deleteTargetElement($data) {
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
				
		if (isset($data['route']) && isset($data['store_id'])) {
			if ((strpos($data['route'], 'bm_category_id') === 0) || (strpos($data['route'], 'bm_post_id') === 0) || (strpos($data['route'], 'bm_author_id') === 0) || ($data['route'] == 'extension/d_blog_module/search')) {	
				$target_keyword_store_id = 0;
				
				if (strpos($data['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				if ($data['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) {
						$target_keyword_store_id = $data['store_id'];
					} else {
						$target_keyword_store_id = 0;
					}
				}
				
				$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$target_keyword_store_id . "'");
			}
		}
	}
	
	/*
	*	Return Export Target Elements.
	*/
	public function getExportTargetElements($data) {		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		$target_elements = array();	
		
		if ($data['sheet_code'] == 'blog_category') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
				$target_keyword_store_id = $data['store_id'];
			} else {
				$target_keyword_store_id = 0;
			}
						
			$query = $this->db->query("SELECT route, language_id, CONCAT('[', GROUP_CONCAT(DISTINCT keyword ORDER BY sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'bm_category_id=%' AND store_id = '" . (int)$target_keyword_store_id . "' GROUP BY route, language_id");	
							
			foreach ($query->rows as $result) {
				$target_elements[$result['route']]['route'] = $result['route'];
				$target_elements[$result['route']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
			}
					
			return $target_elements;
		}
				
		if ($data['sheet_code'] == 'blog_post') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
				$target_keyword_store_id = $data['store_id'];
			} else {
				$target_keyword_store_id = 0;
			}
						
			$query = $this->db->query("SELECT route, language_id, CONCAT('[', GROUP_CONCAT(DISTINCT keyword ORDER BY sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'bm_post_id=%' AND store_id = '" . (int)$target_keyword_store_id . "' GROUP BY route, language_id");	
							
			foreach ($query->rows as $result) {
				$target_elements[$result['route']]['route'] = $result['route'];
				$target_elements[$result['route']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
			}
					
			return $target_elements;	
		}
		
		if ($data['sheet_code'] == 'blog_author') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
				$target_keyword_store_id = $data['store_id'];
			} else {
				$target_keyword_store_id = 0;
			}
						
			$query = $this->db->query("SELECT route, language_id, CONCAT('[', GROUP_CONCAT(DISTINCT keyword ORDER BY sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'bm_author_id=%' AND store_id = '" . (int)$target_keyword_store_id . "' GROUP BY route, language_id");	
							
			foreach ($query->rows as $result) {
				$target_elements[$result['route']]['route'] = $result['route'];
				$target_elements[$result['route']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
			}
					
			return $target_elements;	
		}
		
		if ($data['sheet_code'] == 'custom_page') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
				$target_keyword_store_id = $data['store_id'];
			} else {
				$target_keyword_store_id = 0;
			}
			
			$query = $this->db->query("SELECT route, language_id, CONCAT('[', GROUP_CONCAT(DISTINCT keyword ORDER BY sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'extension/d_blog_module/search' AND store_id = '" . (int)$target_keyword_store_id . "' GROUP BY route, language_id");
									
			foreach ($query->rows as $result) {
				$target_elements[$result['route']]['route'] = $result['route'];
				$target_elements[$result['route']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
			}
									
			return $target_elements;
		}
	}
	
	/*
	*	Save Import Target Elements.
	*/
	public function saveImportTargetElements($data) {		
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
				
		$target_elements = array();	
		
		if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) {
			$target_keyword_store_id = $data['store_id'];
		} else {
			$target_keyword_store_id = 0;
		}
						
		$query = $this->db->query("SELECT route, language_id, CONCAT('[', GROUP_CONCAT(DISTINCT keyword ORDER BY sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'bm_category_id=%' AND store_id = '" . (int)$target_keyword_store_id . "' GROUP BY route, language_id");	
							
		foreach ($query->rows as $result) {
			$target_elements[$result['route']]['route'] = $result['route'];
			$target_elements[$result['route']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
		}
				
		if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) {
			$target_keyword_store_id = $data['store_id'];
		} else {
			$target_keyword_store_id = 0;
		}
						
		$query = $this->db->query("SELECT route, language_id, CONCAT('[', GROUP_CONCAT(DISTINCT keyword ORDER BY sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'bm_post_id=%' AND store_id = '" . (int)$target_keyword_store_id . "' GROUP BY route, language_id");
							
		foreach ($query->rows as $result) {
			$target_elements[$result['route']]['route'] = $result['route'];
			$target_elements[$result['route']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
		}
		
		if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) {
			$target_keyword_store_id = $data['store_id'];
		} else {
			$target_keyword_store_id = 0;
		}
						
		$query = $this->db->query("SELECT route, language_id, CONCAT('[', GROUP_CONCAT(DISTINCT keyword ORDER BY sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'bm_author=%' AND store_id = '" . (int)$target_keyword_store_id . "' GROUP BY route, language_id");	
							
		foreach ($query->rows as $result) {
			$target_elements[$result['route']]['route'] = $result['route'];
			$target_elements[$result['route']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
		}
		
		if ($data['store_id'] && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store'] && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) {
			$target_keyword_store_id = $data['store_id'];
		} else {
			$target_keyword_store_id = 0;
		}
		
		$query = $this->db->query("SELECT route, language_id, CONCAT('[', GROUP_CONCAT(DISTINCT keyword ORDER BY sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "d_target_keyword WHERE route LIKE 'extension/d_blog_module/search' AND store_id = '" . (int)$target_keyword_store_id . "' GROUP BY route, language_id");
									
		foreach ($query->rows as $result) {
			$target_elements[$result['route']]['route'] = $result['route'];
			$target_elements[$result['route']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
		}
				
		foreach ($data['target_elements'] as $target_element) {
			$sheet_code = '';
			
			if (strpos($target_element['route'], 'bm_category_id') === 0) $sheet_code = 'blog_category';			
			if (strpos($target_element['route'], 'bm_post_id') === 0) $sheet_code = 'blog_post';
			if (strpos($target_element['route'], 'bm_author_id') === 0) $sheet_code = 'blog_author';
			if ($target_element['route'] == 'extension/d_blog_module/search') $sheet_code = 'custom_page';
			
			if ($sheet_code) {
				foreach ($languages as $language) {
					if (isset($target_element['target_keyword'][$language['language_id']])) {
						if ((isset($target_elements[$target_element['route']]['target_keyword'][$language['language_id']]) && ($target_element['target_keyword'][$language['language_id']] != $target_elements[$target_element['route']]['target_keyword'][$language['language_id']])) || !isset($target_elements[$target_element['route']]['target_keyword'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet'][$sheet_code]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$sheet_code]['field']['target_keyword']['multi_store_status']) {
								$target_keyword_store_id = $data['store_id'];
							} else {
								$target_keyword_store_id = 0;
							}
															
							$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = '" . $this->db->escape($target_element['route']) . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");	
								
							if ($target_element['target_keyword'][$language['language_id']]) {
								preg_match_all('/\[[^]]+\]/', $target_element['target_keyword'][$language['language_id']], $keywords);
									
								$sort_order = 1;
									
								foreach ($keywords[0] as $keyword) {
									$keyword = substr($keyword, 1, strlen($keyword) - 2);
									
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = '" . $this->db->escape($target_element['route']) . "', store_id = '" . (int)$target_keyword_store_id . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
									
									$sort_order++;
								}
							}
						}
					}
				}	
			}
		}
	}
		
	/*
	*	Return Field Elements.
	*/
	public function getFieldElements($data) {				
		if ($data['field_code'] == 'target_keyword') {
			$this->load->model('extension/module/' . $this->codename);
		
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		
			$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
			
			$field_elements = array();
					
			$sql = "SELECT * FROM " . DB_PREFIX . "d_target_keyword";
			
			$implode = array();
				
			foreach ($data['filter'] as $filter_code => $filter) {
				if (!empty($filter)) {
					if ($filter_code == 'route') {
						if (strpos($filter, '%') !== false) {
							$implode[] = "route LIKE '" . $this->db->escape($filter) . "'";
						} else {
							$implode[] = "route = '" . $this->db->escape($filter) . "'";
						}
					}
													
					if ($filter_code == 'language_id' ) {
						$implode[] = "language_id = '" . (int)$filter . "'";
					}
						
					if ($filter_code == 'sort_order') {
						$implode[] = "sort_order = '" . (int)$filter . "'";
					}
						
					if ($filter_code == 'keyword') {
						$implode[] = "keyword = '" . $this->db->escape($filter) . "'";
					}
				}
			}
		
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}
		
			$sql .= " ORDER BY sort_order";
				
			$query = $this->db->query($sql);
										
			foreach ($query->rows as $result) {
				if (strpos($result['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
							}
						}
					}
				}
					
				if (strpos($result['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
							}
						}
					}
				}
										
				if (strpos($result['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
							}
						}
					}
				}
				
				if ($result['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
							}
						}
					}
				}
			}
				
			return $field_elements;
		}
		
		if ($data['field_code'] == 'url_keyword') {
			$this->load->model('extension/module/' . $this->codename);
		
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		
			$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
			
			$field_elements = array();
			
			if (VERSION >= '3.0.0.0') {
				$sql = "SELECT query as route, store_id, language_id, keyword FROM " . DB_PREFIX . "seo_url";
			} else {
				$sql = "SELECT route, store_id, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword";
			}
			
			$implode = array();
				
			foreach ($data['filter'] as $filter_code => $filter) {
				if (!empty($filter)) {
					if ($filter_code == 'route') {
						if (strpos($filter, '%') !== false) {
							if (VERSION >= '3.0.0.0') {
								$implode[] = "query LIKE '" . $this->db->escape($filter) . "'";
							}else {
								$implode[] = "route LIKE '" . $this->db->escape($filter) . "'";
							}
						} else {
							if (VERSION >= '3.0.0.0') {
								$implode[] = "query = '" . $this->db->escape($filter) . "'";
							}else {
								$implode[] = "route = '" . $this->db->escape($filter) . "'";
							}
						}
					}
														
					if ($filter_code == 'language_id' ) {
						$implode[] = "language_id = '" . (int)$filter . "'";
					}
												
					if ($filter_code == 'keyword') {
						$implode[] = "keyword = '" . $this->db->escape($filter) . "'";
					}
				}
			}
		
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}
							
			$query = $this->db->query($sql);
									
			foreach ($query->rows as $result) {				
				if (strpos($result['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']] = $result['keyword'];
							}
						}
					}
				}
					
				if (strpos($result['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']] = $result['keyword'];
							}
						}
					}
				}
									
				if (strpos($result['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']] = $result['keyword'];
							}
						}
					}
				}
				
				if ($result['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) {
						if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
							$field_elements[$result['route']][$result['store_id']][$result['language_id']] = $result['keyword'];
						}
					} elseif ($result['store_id'] == 0) {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$store['store_id']][$result['language_id']] = $result['keyword'];
							}
						}
					}
				}
			}
				
			return $field_elements;
		}
		
		if ($data['field_code'] == 'meta_data') {
			$this->load->model('extension/module/' . $this->codename);
		
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
			
			$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
						
			$field_elements = array();
			
			if ((isset($data['filter']['route']) && (strpos($data['filter']['route'], 'bm_category_id') === 0)) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "bm_category_description";
			
				$implode = array();
				
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							$route_arr = explode('bm_category_id=', $filter);
			
							if (isset($route_arr[1]) && ($route_arr[1] != '%')) {
								$category_id = $route_arr[1];
								$implode[] = "category_id = '" . (int)$category_id . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'title') {
							$implode[] = "title = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'short_description') {
							$implode[] = "short_description = '" . $this->db->escape($filter) . "'";
						}
										
						if ($filter_code == 'description') {
							$implode[] = "description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_title') {
							$implode[] = "meta_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_description') {
							$implode[] = "meta_description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_keyword') {
							$implode[] = "meta_keyword = '" . $this->db->escape($filter) . "'";
						}
					}
				}
					
				if ($implode) {
					$sql .= " WHERE " . implode(' AND ', $implode);
				}
						
				$query = $this->db->query($sql);
					
				foreach ($query->rows as $result) {
					$route = 'bm_category_id=' . $result['category_id'];
				
					if ((isset($field_info['sheet']['blog_category']['field']['title']['multi_store']) && $field_info['sheet']['blog_category']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['title'] = $result['title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_category']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['short_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['short_description'] = $result['short_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					}
				
					if ((isset($field_info['sheet']['blog_category']['field']['description']['multi_store']) && $field_info['sheet']['blog_category']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['description'] = $result['description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_title'] = $result['meta_title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_description'] = $result['meta_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
					}					
				}
			}
			
			if ((isset($data['filter']['route']) && (strpos($data['filter']['route'], 'bm_post_id') === 0)) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "bm_post_description";
			
				$implode = array();
								
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							$route_arr = explode('bm_post_id=', $filter);
			
							if (isset($route_arr[1]) && ($route_arr[1] != '%')) {
								$post_id = $route_arr[1];
								$implode[] = "post_id = '" . (int)$post_id . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'title') {
							$implode[] = "title = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'short_description') {
							$implode[] = "short_description = '" . $this->db->escape($filter) . "'";
						}
										
						if ($filter_code == 'description') {
							$implode[] = "description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_title') {
							$implode[] = "meta_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_description') {
							$implode[] = "meta_description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_keyword') {
							$implode[] = "meta_keyword = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'tag') {
							$implode[] = "tag = '" . $this->db->escape($filter) . "'";
						}
					}
				}
					
				if ($implode) {
					$sql .= " WHERE " . implode(' AND ', $implode);
				}
						
				$query = $this->db->query($sql);
										
				foreach ($query->rows as $result) {
					$route = 'bm_post_id=' . $result['post_id'];
							
					if ((isset($field_info['sheet']['blog_post']['field']['title']['multi_store']) && $field_info['sheet']['blog_post']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['title'] = $result['title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_post']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['short_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['short_description'] = $result['short_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					}
				
					if ((isset($field_info['sheet']['blog_post']['field']['description']['multi_store']) && $field_info['sheet']['blog_post']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['description'] = $result['description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_title'] = $result['meta_title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_description'] = $result['meta_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					}

					if ((isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_post']['field']['tag']['multi_store']) && $field_info['sheet']['blog_post']['field']['tag']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['tag']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['tag'] = $result['tag'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['tag'] = $result['tag'];
							}
						}
					}
				}
			}
			
			if ((isset($data['filter']['route']) && (strpos($data['filter']['route'], 'bm_author_id') === 0)) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "bm_author_description";
			
				$implode = array();
				
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							$route_arr = explode('bm_author_id=', $filter);
			
							if (isset($route_arr[1]) && ($route_arr[1] != '%')) {
								$author_id = $route_arr[1];
								$implode[] = "author_id = '" . (int)$author_id . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'name') {
							$implode[] = "name = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'short_description') {
							$implode[] = "short_description = '" . $this->db->escape($filter) . "'";
						}
										
						if ($filter_code == 'description') {
							$implode[] = "description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_title') {
							$implode[] = "meta_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_description') {
							$implode[] = "meta_description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_keyword') {
							$implode[] = "meta_keyword = '" . $this->db->escape($filter) . "'";
						}
					}
				}
			
				if ($implode) {
					$sql .= " WHERE " . implode(' AND ', $implode);
				}
						
				$query = $this->db->query($sql);
										
				foreach ($query->rows as $result) {
					$route = 'bm_author_id=' . $result['author_id'];
							
					if ((isset($field_info['sheet']['blog_author']['field']['name']['multi_store']) && $field_info['sheet']['blog_author']['field']['name']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['name']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['name']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['name'] = $result['name'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['name'] = $result['name'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_author']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['short_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['short_description'] = $result['short_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					}
				
					if ((isset($field_info['sheet']['blog_author']['field']['description']['multi_store']) && $field_info['sheet']['blog_author']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['description'] = $result['description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_title'] = $result['meta_title'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_description'] = $result['meta_description'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					}
					
					if ((isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status'])) {
						if ((isset($data['filter']['store_id']) && ($data['filter']['store_id'] == 0)) || !isset($data['filter']['store_id'])) {
							$field_elements[$route][0][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
						}
					} else {
						foreach ($stores as $store) {
							if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$route][$store['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
					}
				}
			}
			
			if ((isset($data['filter']['route']) && ((strpos($data['filter']['route'], 'bm_category_id') === 0) || (strpos($data['filter']['route'], 'bm_post_id') === 0) || (strpos($data['filter']['route'], 'bm_author_id') === 0))) || !isset($data['filter']['route'])) {
				$sql = "SELECT * FROM " . DB_PREFIX . "d_meta_data";
				
				$implode = array();
								
				foreach ($data['filter'] as $filter_code => $filter) {
					if (!empty($filter)) {
						if ($filter_code == 'route') {
							if (strpos($filter, '%') !== false) {
								$implode[] = "route LIKE '" . $this->db->escape($filter) . "'";
							} else {
								$implode[] = "route = '" . $this->db->escape($filter) . "'";
							}
						}
													
						if ($filter_code == 'language_id' ) {
							$implode[] = "language_id = '" . (int)$filter . "'";
						}
											
						if ($filter_code == 'name') {
							$implode[] = "name = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'title') {
							$implode[] = "title = '" . $this->db->escape($filter) . "'";
						}
						
						if ($filter_code == 'short_description') {
							$implode[] = "short_description = '" . $this->db->escape($filter) . "'";
						}
										
						if ($filter_code == 'description') {
							$implode[] = "description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_title') {
							$implode[] = "meta_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_description') {
							$implode[] = "meta_description = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_keyword') {
							$implode[] = "meta_keyword = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'tag') {
							$implode[] = "tag = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'custom_title_1') {
							$implode[] = "custom_title_1 = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'custom_title_2') {
							$implode[] = "custom_title_2 = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'custom_image_title') {
							$implode[] = "custom_image_title = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'custom_image_alt') {
							$implode[] = "custom_image_alt = '" . $this->db->escape($filter) . "'";
						}
					
						if ($filter_code == 'meta_robots') {
							$implode[] = "meta_robots = '" . $this->db->escape($filter) . "'";
						}
					}
				}
					
				if ($implode) {
					$sql .= " WHERE " . implode(' AND ', $implode);
				}
						
				$query = $this->db->query($sql);
			
				foreach ($query->rows as $result) {
					if (strpos($result['route'], 'bm_category_id') === 0) {
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['title']['multi_store']) && $field_info['sheet']['blog_category']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_category']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['short_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['description']['multi_store']) && $field_info['sheet']['blog_category']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store']) && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store']) && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store']) && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store']) && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_category']['field']['meta_robots']['multi_store']) && $field_info['sheet']['blog_category']['field']['meta_robots']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['meta_robots']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['meta_robots']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
								}
							}
						}
					}
				
					if (strpos($result['route'], 'bm_post_id') === 0) {
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['title']['multi_store']) && $field_info['sheet']['blog_post']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['title'] = $result['title'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_post']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['short_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['description']['multi_store']) && $field_info['sheet']['blog_post']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store']) && $field_info['sheet']['blog_post']['field']['tag']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['tag'] = $result['tag'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store']) && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store']) && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store']) && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store']) && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_post']['field']['meta_robots']['multi_store']) && $field_info['sheet']['blog_post']['field']['meta_robots']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['meta_robots']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['meta_robots']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
								}
							}
						}
					}
												
					if (strpos($result['route'], 'bm_author_id') === 0) {
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['name']['multi_store']) && $field_info['sheet']['blog_author']['field']['name']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['name']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['name']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['name'] = $result['name'];
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store']) && $field_info['sheet']['blog_author']['field']['short_description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['short_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['short_description'] = $result['short_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['description']['multi_store']) && $field_info['sheet']['blog_author']['field']['description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['description'] = $result['description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_title'] = $result['meta_title'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_description'] = $result['meta_description'];
							}
						}
					
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_keyword'] = $result['meta_keyword'];
							}
						}
												
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store']) && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_1'] = $result['custom_title_1'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store']) && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_title_2'] = $result['custom_title_2'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store']) && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_title'] = $result['custom_image_title'];
								}
							}
						}
						
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store']) && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['custom_image_alt'] = $result['custom_image_alt'];
								}
							}
						}
												
						if ($result['store_id'] && isset($field_info['sheet']['blog_author']['field']['meta_robots']['multi_store']) && $field_info['sheet']['blog_author']['field']['meta_robots']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['meta_robots']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['meta_robots']['multi_store_status']) {
							if ((isset($data['filter']['store_id']) && ($result['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
								$field_elements[$result['route']][$result['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
							}
						} elseif ($result['store_id'] == 0) {
							foreach ($stores as $store) {
								if ((isset($data['filter']['store_id']) && ($store['store_id'] == $data['filter']['store_id'])) || !isset($data['filter']['store_id'])) {
									$field_elements[$result['route']][$store['store_id']][$result['language_id']]['meta_robots'] = $result['meta_robots'];
								}
							}
						}
					}
				}
			}
			
			return $field_elements;
		}
				
		return false;
	}
}
?>