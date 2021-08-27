<?php
class ModelExtensionDSEOModuleURLDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	
	/*
	*	Generate Fields.
	*/
	public function generateFields($data) {		
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (file_exists(DIR_APPLICATION . 'model/extension/module/d_translit.php')) {
			$this->load->model('extension/module/d_translit');
			
			$translit_data = true;
		} else {
			$translit_data = false;
		}
						
		if (isset($data['sheet']['blog_category']['field'])) {											
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_category']['field']['url_keyword']) && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['url_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) ? "uk2.keyword as url_keyword" : "uk.keyword as url_keyword";
			}
			
			if ($data['store_id'] && isset($field_info['sheet']['blog_category']['field']['title']['multi_store']) && $field_info['sheet']['blog_category']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['title']['multi_store_status']) {
				$implode[] = "md.title";
				$add = "LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_category_id=', c.category_id) AND md.store_id = '" . (int)$data['store_id'] . "' AND md.language_id = cd.language_id)";
			} else {
				$implode[] = "cd.title";
				$add = "";
			}
			
			$categories = array();
			
			if ($field) {
				$field_template = isset($field['template']) ? $field['template'] : '';
				$field_overwrite = isset($field['overwrite']) ? $field['overwrite'] : 1;
			
				if ($translit_data) {
					$translit_data = array(
						'translit_symbol_status' => isset($field['translit_symbol_status']) ? $field['translit_symbol_status'] : 1,
						'translit_language_symbol_status' => isset($field['translit_language_symbol_status']) ? $field['translit_language_symbol_status'] : 1,
						'transform_language_symbol_id' => isset($field['transform_language_symbol_id']) ? $field['transform_language_symbol_id'] : 0
					);
				}
							
				$field_data = array(
					'field_code' => 'target_keyword',
					'filter' => array('store_id' => $data['store_id'])
				);
			
				$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
								
				if (VERSION >= '3.0.0.0') {
					$query = $this->db->query("SELECT cd.category_id, cd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id) " . $add . " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '0' AND uk.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_category_id=', c.category_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = cd.language_id) GROUP BY c.category_id, cd.language_id");
				} else {
					$query = $this->db->query("SELECT cd.category_id, cd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id) " . $add . " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '0' AND uk.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_category_id=', c.category_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = cd.language_id) GROUP BY c.category_id, cd.language_id");
				}
										
				foreach ($query->rows as $result) {
					$categories[$result['category_id']]['category_id'] = $result['category_id'];
				
					foreach ($result as $field => $value) {
						if (($field != 'category_id') && ($field != 'language_id')) {
							$categories[$result['category_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}
			
			foreach ($categories as $category) {
				foreach ($languages as $language) {
					if (isset($target_keywords['bm_category_id=' . $category['category_id']][$data['store_id']][$language['language_id']])) {
						$target_keyword = $target_keywords['bm_category_id=' . $category['category_id']][$data['store_id']][$language['language_id']];
					} else {
						$target_keyword = array();
					}
					
					if (is_array($field_template)) {
						$field_new = $field_template[$language['language_id']]; 
					} else {
						$field_new = $field_template; 
					}
					
					$field_new = strtr($field_new, array('[title]' => $category['title'][$language['language_id']]));
					$field_new = $this->replaceTargetKeyword($field_new, $target_keyword);
					
					if ($translit_data) {
						$field_new = $this->model_extension_module_d_translit->translit($field_new, $translit_data);
					}
					
					if (isset($data['sheet']['blog_category']['field']['url_keyword']) && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status'])) {
						if ((isset($category['url_keyword'][$language['language_id']]) && ($field_new != $category['url_keyword'][$language['language_id']]) && $field_overwrite) || !isset($category['url_keyword'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
								$url_keyword_store_id = $data['store_id'];
							} else {
								$url_keyword_store_id = 0;
							}
							
							if (VERSION >= '3.0.0.0') {
								$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (trim($field_new)) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_category_id=" . (int)$category['category_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
									
								if (trim($field_new)) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_category_id=" . (int)$category['category_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
								}	
							
								if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
									$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_category_id=" . (int)$category['category_id'] . "'");
									
									if (trim($field_new)) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_category_id=" . (int)$category['category_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
									}
								}
							}
						}
					}
				}		
			}
			
			$cache_data = array(
				'route' => 'bm_category_id=%',
				'store_id' => $data['store_id']
			);
				
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
		
		if (isset($data['sheet']['blog_post']['field'])) {
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_post']['field']['url_keyword']) && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['url_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) ? "uk2.keyword as url_keyword" : "uk.keyword as url_keyword";
			}
			
			if ($data['store_id'] && isset($field_info['sheet']['blog_post']['field']['title']['multi_store']) && $field_info['sheet']['blog_post']['field']['title']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['title']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['title']['multi_store_status']) {
				$implode[] = "md.title";
				$add = "LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_post_id=', p.post_id) AND md.store_id = '" . (int)$data['store_id'] . "' AND md.language_id = pd.language_id)";
			} else {
				$implode[] = "pd.title";
				$add = "";
			}
			
			$posts = array();
			
			if ($field) {
				$field_template = isset($field['template']) ? $field['template'] : '';
				$field_overwrite = isset($field['overwrite']) ? $field['overwrite'] : 1;
			
				if ($translit_data) {
					$translit_data = array(
						'translit_symbol_status' => isset($field['translit_symbol_status']) ? $field['translit_symbol_status'] : 1,
						'translit_language_symbol_status' => isset($field['translit_language_symbol_status']) ? $field['translit_language_symbol_status'] : 1,
						'transform_language_symbol_id' => isset($field['transform_language_symbol_id']) ? $field['transform_language_symbol_id'] : 0
					);
				}
				
				$field_data = array(
					'field_code' => 'target_keyword',
					'filter' => array('store_id' => $data['store_id'])
				);
			
				$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
								
				if (VERSION >= '3.0.0.0') {
					$query = $this->db->query("SELECT pd.post_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id) " . $add . " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '0' AND uk.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_post_id=', p.post_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = pd.language_id) GROUP BY p.post_id, pd.language_id");
				} else {
					$query = $this->db->query("SELECT pd.post_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id) " . $add . " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '0' AND uk.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_post_id=', p.post_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = pd.language_id) GROUP BY p.post_id, pd.language_id");
				}
						
				foreach ($query->rows as $result) {
					$posts[$result['post_id']]['post_id'] = $result['post_id'];
				
					foreach ($result as $field => $value) {
						if (($field != 'post_id') && ($field != 'language_id')) {
							$posts[$result['post_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}
			
			foreach ($posts as $post) {
				foreach ($languages as $language) {
					if (isset($target_keywords['bm_post_id=' . $post['post_id']][$data['store_id']][$language['language_id']])) {
						$target_keyword = $target_keywords['bm_post_id=' . $post['post_id']][$data['store_id']][$language['language_id']];
					} else {
						$target_keyword = array();
					}
					
					if (is_array($field_template)) {
						$field_new = $field_template[$language['language_id']]; 
					} else {
						$field_new = $field_template; 
					}
										
					$field_new = strtr($field_new, array('[title]' => $post['title'][$language['language_id']]));
					$field_new = $this->replaceTargetKeyword($field_new, $target_keyword);
					
					if ($translit_data) {
						$field_new = $this->model_extension_module_d_translit->translit($field_new, $translit_data);
					}
					
					if (isset($data['sheet']['blog_post']['field']['url_keyword']) && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status'])) {
						if ((isset($post['url_keyword'][$language['language_id']]) && ($field_new != $post['url_keyword'][$language['language_id']]) && $field_overwrite) || !isset($post['url_keyword'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
								$url_keyword_store_id = $data['store_id'];
							} else {
								$url_keyword_store_id = 0;
							}
							
							if (VERSION >= '3.0.0.0') {
								$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (trim($field_new)) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_post_id=" . (int)$post['post_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
									
								if (trim($field_new)) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_post_id=" . (int)$post['post_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
								}	
							
								if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
									$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_post_id=" . (int)$post['post_id'] . "'");
									
									if (trim($field_new)) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_post_id=" . (int)$post['post_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
									}
								}
							}	
						}
					}
				}		
			}
			
			$cache_data = array(
				'route' => 'bm_post_id=%',
				'store_id' => $data['store_id']
			);
				
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
		
		if (isset($data['sheet']['blog_author']['field'])) {
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_author']['field']['url_keyword']) && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['url_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) ? "uk2.keyword as url_keyword" : "uk.keyword as url_keyword";
			}
			
			if ($data['store_id'] && isset($field_info['sheet']['blog_author']['field']['name']['multi_store']) && $field_info['sheet']['blog_author']['field']['name']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['name']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['name']['multi_store_status']) {
				$implode[] = "md.name";
				$add = "LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_author_id=', a.author_id) AND md.store_id = '" . (int)$data['store_id'] . "' AND md.language_id = ad.language_id)";
			} else {
				$implode[] = "ad.name";
				$add = "";
			}
			
			$authors = array();
			
			if ($field) {
				$field_template = isset($field['template']) ? $field['template'] : '';
				$field_overwrite = isset($field['overwrite']) ? $field['overwrite'] : 1;
			
				if ($translit_data) {
					$translit_data = array(
						'translit_symbol_status' => isset($field['translit_symbol_status']) ? $field['translit_symbol_status'] : 1,
						'translit_language_symbol_status' => isset($field['translit_language_symbol_status']) ? $field['translit_language_symbol_status'] : 1,
						'transform_language_symbol_id' => isset($field['transform_language_symbol_id']) ? $field['transform_language_symbol_id'] : 0
					);
				}
				
				$field_data = array(
					'field_code' => 'target_keyword',
					'filter' => array('store_id' => $data['store_id'])
				);
			
				$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
								
				if (VERSION >= '3.0.0.0') {
					$query = $this->db->query("SELECT ad.author_id, ad.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id) " . $add . " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '0' AND uk.language_id = ad.language_id) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_author_id=', a.author_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = ad.language_id) GROUP BY a.author_id, ad.language_id");
				} else {
					$query = $this->db->query("SELECT ad.author_id, ad.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id) " . $add . " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '0' AND uk.language_id = ad.language_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_author_id=', a.author_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = ad.language_id) GROUP BY a.author_id, ad.language_id");
				}
						
				foreach ($query->rows as $result) {
					$authors[$result['author_id']]['author_id'] = $result['author_id'];
				
					foreach ($result as $field => $value) {
						if (($field != 'author_id') && ($field != 'language_id')) {
							$authors[$result['author_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}
			
			foreach ($authors as $author) {
				foreach ($languages as $language) {
					if (isset($target_keywords['bm_author_id=' . $author['author_id']][$data['store_id']][$language['language_id']])) {
						$target_keyword = $target_keywords['bm_author_id=' . $author['author_id']][$data['store_id']][$language['language_id']];
					} else {
						$target_keyword = array();
					}
					
					if (is_array($field_template)) {
						$field_new = $field_template[$language['language_id']]; 
					} else {
						$field_new = $field_template; 
					}
										
					$field_new = strtr($field_new, array('[name]' => $author['name'][$language['language_id']]));
					$field_new = $this->replaceTargetKeyword($field_new, $target_keyword);
					
					if ($translit_data) {
						$field_new = $this->model_extension_module_d_translit->translit($field_new, $translit_data);
					}
					
					if (isset($data['sheet']['blog_author']['field']['url_keyword']) && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status'])) {
						if ((isset($author['url_keyword'][$language['language_id']]) && ($field_new != $author['url_keyword'][$language['language_id']]) && $field_overwrite) || !isset($author['url_keyword'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
								$url_keyword_store_id = $data['store_id'];
							} else {
								$url_keyword_store_id = 0;
							}
							
							if (VERSION >= '3.0.0.0') {
								$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (trim($field_new)) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_author_id=" . (int)$author['author_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
									
								if (trim($field_new)) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_author_id=" . (int)$author['author_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
								}	
							
								if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
									$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_author_id=" . (int)$author['author_id'] . "'");
									
									if (trim($field_new)) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_author_id=" . (int)$author['author_id'] . "', keyword = '" . $this->db->escape($field_new) . "'");
									}
								}
							}
						}
					}
				}		
			}
			
			$cache_data = array(
				'route' => 'bm_author_id=%',
				'store_id' => $data['store_id']
			);
				
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
	}
	
	/*
	*	Clear Fields.
	*/
	public function clearFields($data) {		
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
										
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
						
		if (isset($data['sheet']['blog_category'])) {
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_category']['field']['url_keyword']) && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['url_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) ? "uk2.keyword as url_keyword" : "uk.keyword as url_keyword";
			}
			
			$categories = array();
			
			if ($field) {				
				if (VERSION >= '3.0.0.0') {
					$query = $this->db->query("SELECT cd.category_id, cd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '0' AND uk.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_category_id=', c.category_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = cd.language_id) GROUP BY c.category_id, cd.language_id");
				} else {
					$query = $this->db->query("SELECT cd.category_id, cd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '0' AND uk.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_category_id=', c.category_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = cd.language_id) GROUP BY c.category_id, cd.language_id");
				}
										
				foreach ($query->rows as $result) {
					$categories[$result['category_id']]['category_id'] = $result['category_id'];
				
					foreach ($result as $field => $value) {
						if (($field != 'category_id') && ($field != 'language_id')) {
							$categories[$result['category_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}
			
			foreach ($categories as $category) {
				foreach ($languages as $language) {					
					if (isset($data['sheet']['blog_category']['field']['url_keyword']) && isset($category['url_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
							
						if (VERSION >= '3.0.0.0') {
							$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
		
							if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
								$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_category_id=" . (int)$category['category_id'] . "'");	
							}
						}
					}	
				}		
			}

			$cache_data = array(
				'route' => 'bm_category_id=%',
				'store_id' => $data['store_id']
			);
				
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);	
		}
		
		if (isset($data['sheet']['blog_post'])) {
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_post']['field']['url_keyword']) && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['url_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) ? "uk2.keyword as url_keyword" : "uk.keyword as url_keyword";
			}
									
			$posts = array();
			
			if ($field) {		
				if (VERSION >= '3.0.0.0') {
					$query = $this->db->query("SELECT pd.post_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '0' AND uk.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_post_id=', p.post_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = pd.language_id) GROUP BY p.post_id, pd.language_id");
				} else {
					$query = $this->db->query("SELECT pd.post_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '0' AND uk.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_post_id=', p.post_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = pd.language_id) GROUP BY p.post_id, pd.language_id");
				}
						
				foreach ($query->rows as $result) {
					$posts[$result['post_id']]['post_id'] = $result['post_id'];
				
					foreach ($result as $field => $value) {
						if (($field != 'post_id') && ($field != 'language_id')) {
							$posts[$result['post_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}	
				
			foreach ($posts as $post) {
				foreach ($languages as $language) {
					if (isset($data['sheet']['blog_post']['field']['url_keyword']) && isset($post['url_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['post']['field']['url_keyword']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
							
						if (VERSION >= '3.0.0.0') {
							$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
		
							if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
								$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_post_id=" . (int)$post['post_id'] . "'");	
							}
						}	
					}
				}		
			}
			
			$cache_data = array(
				'route' => 'bm_post_id=%',
				'store_id' => $data['store_id']
			);
				
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
		
		if (isset($data['sheet']['blog_author'])) {
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_author']['field']['url_keyword']) && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['url_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) ? "uk2.keyword as url_keyword" : "uk.keyword as url_keyword";;
			}
												
			$authors = array();

			if ($field) {
				if (VERSION >= '3.0.0.0') {
					$query = $this->db->query("SELECT ad.author_id, ad.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id) " . $add . " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '0' AND uk.language_id = ad.language_id) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_author_id=', a.author_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = ad.language_id) GROUP BY a.author_id, ad.language_id");
				} else {
					$query = $this->db->query("SELECT ad.author_id, ad.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '0' AND uk.language_id = ad.language_id) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_author_id=', a.author_id) AND uk2.store_id = '" . (int)$data['store_id'] . "' AND uk2.language_id = ad.language_id) GROUP BY a.author_id, ad.language_id");
				}
						
				foreach ($query->rows as $result) {
					$authors[$result['author_id']]['author_id'] = $result['author_id'];
				
					foreach ($result as $field => $value) {
						if (($field != 'author_id') && ($field != 'language_id')) {
							$authors[$result['author_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}

			foreach ($authors as $author) {
				foreach ($languages as $language) {
					if (isset($data['sheet']['blog_author']['field']['url_keyword']) && isset($author['url_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
							
						if (VERSION >= '3.0.0.0') {
							$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
		
							if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
								$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_author_id=" . (int)$author['author_id'] . "'");	
							}
						}
					}
				}		
			}
			
			$cache_data = array(
				'route' => 'bm_author_id=%',
				'store_id' => $data['store_id']
			);
				
			$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
		}
	}
	
	/*
	*	Return URL Elements.
	*/	
	public function getURLElements($data) {
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
		
		$url_elements = array();	
						
		if ($data['sheet_code'] == 'blog_category') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
				$url_keyword_store_id = $data['store_id'];
			} else {
				$url_keyword_store_id = 0;
			}
			
			if (VERSION >= '3.0.0.0') {
				$sql = "SELECT uk.query as route, uk.language_id, uk.keyword, c.category_id FROM " . DB_PREFIX . "seo_url uk LEFT JOIN " . DB_PREFIX . "bm_category c ON (CONCAT('bm_category_id=', c.category_id) = uk.query) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = uk.query AND uk2.store_id = '" . (int)$url_keyword_store_id . "') WHERE uk.query LIKE 'bm_category_id=%' AND uk.store_id = '" . (int)$url_keyword_store_id . "'";
			} else {
				$sql = "SELECT uk.route, uk.language_id, uk.keyword, c.category_id FROM " . DB_PREFIX . "d_url_keyword uk LEFT JOIN " . DB_PREFIX . "bm_category c ON (CONCAT('bm_category_id=', c.category_id) = uk.route) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = uk.route AND uk2.store_id = '" . (int)$url_keyword_store_id . "') WHERE uk.route LIKE 'bm_category_id=%' AND uk.store_id = '" . (int)$url_keyword_store_id . "'";
			}

			$implode = array();
			$implode_language = array();
						
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'route') {
						if (VERSION >= '3.0.0.0') {
							$implode[] = "uk2.query = '" . $this->db->escape($filter) . "'";
						} else {
							$implode[] = "uk2.route = '" . $this->db->escape($filter) . "'";
						}
					}
										
					if ($field_code == 'url_keyword') {
						foreach ($filter as $language_id => $value) {
							if (!empty($value)) {
								$implode_language[] = "(uk2.language_id = '" . (int)$language_id . "' AND uk2.keyword LIKE '%" . $this->db->escape($value) . "%')";
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

			if (VERSION >= '3.0.0.0') {
				$sql .= " GROUP BY uk.query, uk.language_id";
			} else {
				$sql .= " GROUP BY uk.route, uk.language_id";
			}
			
			$query = $this->db->query($sql);
						
			foreach ($query->rows as $result) {
				$url_elements[$result['route']]['route'] = $result['route'];
				$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
					
				if ($result['category_id']) {
					$url_elements[$result['route']]['link'] = $this->url->link('extension/d_blog_module/category/edit', $url_token . '&category_id=' . $result['category_id'], true);
				}
			}
					
			return $url_elements;
		}
				
		if ($data['sheet_code'] == 'blog_post') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
				$url_keyword_store_id = $data['store_id'];
			} else {
				$url_keyword_store_id = 0;
			}
						
			if (VERSION >= '3.0.0.0') {
				$sql = "SELECT uk.query as route, uk.language_id, uk.keyword, p.post_id FROM " . DB_PREFIX . "seo_url uk LEFT JOIN " . DB_PREFIX . "bm_post p ON (CONCAT('bm_post_id=', p.post_id) = uk.query) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = uk.query AND uk2.store_id = '" . (int)$url_keyword_store_id . "') WHERE uk.query LIKE 'bm_post_id=%' AND uk.store_id = '" . (int)$url_keyword_store_id . "'";
			} else {
				$sql = "SELECT uk.route, uk.language_id, uk.keyword, p.post_id FROM " . DB_PREFIX . "d_url_keyword uk LEFT JOIN " . DB_PREFIX . "bm_post p ON (CONCAT('bm_post_id=', p.post_id) = uk.route) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = uk.route AND uk2.store_id = '" . (int)$url_keyword_store_id . "') WHERE uk.route LIKE 'bm_post_id=%' AND uk.store_id = '" . (int)$url_keyword_store_id . "'";
			}
			
			$implode = array();
			$implode_language = array();
						
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'route') {
						if (VERSION >= '3.0.0.0') {
							$implode[] = "uk2.query = '" . $this->db->escape($filter) . "'";
						} else {
							$implode[] = "uk2.route = '" . $this->db->escape($filter) . "'";
						}
					}
										
					if ($field_code == 'url_keyword') {
						foreach ($filter as $language_id => $value) {
							if (!empty($value)) {
								$implode_language[] = "(uk2.language_id = '" . (int)$language_id . "' AND uk2.keyword LIKE '%" . $this->db->escape($value) . "%')";
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

			if (VERSION >= '3.0.0.0') {
				$sql .= " GROUP BY uk.query, uk.language_id";
			} else {
				$sql .= " GROUP BY uk.route, uk.language_id";
			}
			
			$query = $this->db->query($sql);
						
			foreach ($query->rows as $result) {
				$url_elements[$result['route']]['route'] = $result['route'];
				$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
					
				if ($result['post_id']) {
					$url_elements[$result['route']]['link'] = $this->url->link('extension/d_blog_module/post/edit', $url_token . '&post_id=' . $result['post_id'], true);
				}
			}
					
			return $url_elements;	
		}
		
		if ($data['sheet_code'] == 'blog_author') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
				$url_keyword_store_id = $data['store_id'];
			} else {
				$url_keyword_store_id = 0;
			}
						
			if (VERSION >= '3.0.0.0') {
				$sql = "SELECT uk.query as route, uk.language_id, uk.keyword, a.author_id FROM " . DB_PREFIX . "seo_url uk LEFT JOIN " . DB_PREFIX . "bm_author a ON (CONCAT('bm_author_id=', a.author_id) = uk.query) LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = uk.query AND uk2.store_id = '" . (int)$url_keyword_store_id . "') WHERE uk.query LIKE 'bm_author_id=%' AND uk.store_id = '" . (int)$url_keyword_store_id . "'";
			} else {
				$sql = "SELECT uk.route, uk.language_id, uk.keyword, a.author_id FROM " . DB_PREFIX . "d_url_keyword uk LEFT JOIN " . DB_PREFIX . "bm_author a ON (CONCAT('bm_author_id=', a.author_id) = uk.route) LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = uk.route AND uk2.store_id = '" . (int)$url_keyword_store_id . "') WHERE uk.route LIKE 'bm_author_id=%' AND uk.store_id = '" . (int)$url_keyword_store_id . "'";
			}
			
			$implode = array();
			$implode_language = array();
						
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'route') {
						if (VERSION >= '3.0.0.0') {
							$implode[] = "uk2.query = '" . $this->db->escape($filter) . "'";
						} else {
							$implode[] = "uk2.route = '" . $this->db->escape($filter) . "'";
						}
					}
										
					if ($field_code == 'url_keyword') {
						foreach ($filter as $language_id => $value) {
							if (!empty($value)) {
								$implode_language[] = "(uk2.language_id = '" . (int)$language_id . "' AND uk2.keyword LIKE '%" . $this->db->escape($value) . "%')";
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

			if (VERSION >= '3.0.0.0') {
				$sql .= " GROUP BY uk.query, uk.language_id";
			} else {
				$sql .= " GROUP BY uk.route, uk.language_id";
			}
			
			$query = $this->db->query($sql);
						
			foreach ($query->rows as $result) {
				$url_elements[$result['route']]['route'] = $result['route'];
				$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
				
				if ($result['author_id']) {
					$url_elements[$result['route']]['link'] = $this->url->link('extension/d_blog_module/author/edit', $url_token . '&author_id=' . $result['author_id'], true);
				}
			}
					
			return $url_elements;	
		}
		
		if ($data['sheet_code'] == 'custom_page') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
				$url_keyword_store_id = $data['store_id'];
			} else {
				$url_keyword_store_id = 0;
			}
			
			if (VERSION >= '3.0.0.0') {
				$sql = "SELECT uk.query as route, uk.language_id, uk.keyword FROM " . DB_PREFIX . "seo_url uk LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = uk.query AND uk2.store_id = '" . (int)$url_keyword_store_id . "') WHERE uk.query = 'extension/d_blog_module/search' AND uk.store_id = '" . (int)$url_keyword_store_id . "'";
			} else {
				$sql = "SELECT uk.route, uk.language_id, uk.keyword FROM " . DB_PREFIX . "d_url_keyword uk LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = uk.route AND uk2.store_id = '" . (int)$url_keyword_store_id . "') WHERE uk.route = 'extension/d_blog_module/search' AND uk.store_id = '" . (int)$url_keyword_store_id . "'";
			}
			
			$implode = array();
			$implode_language = array();
						
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'route') {
						if (VERSION >= '3.0.0.0') {
							$implode[] = "uk2.query = '" . $this->db->escape($filter) . "'";
						} else {
							$implode[] = "uk2.route = '" . $this->db->escape($filter) . "'";
						}
					}
										
					if ($field_code == 'url_keyword') {
						foreach ($filter as $language_id => $value) {
							if (!empty($value)) {
								$implode_language[] = "(uk2.language_id = '" . (int)$language_id . "' AND uk2.keyword LIKE '%" . $this->db->escape($value) . "%')";
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

			if (VERSION >= '3.0.0.0') {
				$sql .= " GROUP BY uk.query, uk.language_id";
			} else {
				$sql .= " GROUP BY uk.route, uk.language_id";
			}
			
			$query = $this->db->query($sql);
						
			foreach ($query->rows as $result) {
				$url_elements[$result['route']]['route'] = $result['route'];
				$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
			}
									
			return $url_elements;
		}
	}
					
	/*
	*	Add URL Element.
	*/
	public function addURLElement($data) {
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($data['route']) && isset($data['store_id']) && isset($data['url_keyword'])) {
			if ((strpos($data['route'], 'bm_category_id') === 0) || (strpos($data['route'], 'bm_post_id') === 0) || (strpos($data['route'], 'bm_author_id') === 0) || ($data['route'] == 'extension/d_blog_module/search')) {	
				$url_keyword_store_id = 0;
				
				if (strpos($data['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if ($data['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
								
				foreach ($data['url_keyword'] as $language_id => $url_keyword) {
					if (trim($url_keyword)) {
						if (VERSION >= '3.0.0.0') {
							$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language_id . "'");
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = '" . $this->db->escape($data['route']) . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
						} else {
							$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language_id . "'");
							$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = '" . $this->db->escape($data['route']) . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
					
							if (($url_keyword_store_id == 0) && ($language_id == (int)$this->config->get('config_language_id'))) {
								$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($data['route']) . "'");
								$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($data['route']) . "', keyword = '" . $this->db->escape($url_keyword) . "'");
							}
						}
					}
				}
				
				$cache_data = array(
					'route' => $data['route'],
					'store_id' => $data['store_id']
				);
				
				$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
			}
		}
	}
	
	/*
	*	Edit URL Element.
	*/
	public function editURLElement($data) {
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($data['route']) && isset($data['store_id']) && isset($data['url_keyword'])) {
			if ((strpos($data['route'], 'bm_category_id') === 0) || (strpos($data['route'], 'bm_post_id') === 0) || (strpos($data['route'], 'bm_author_id') === 0) || ($data['route'] == 'extension/d_blog_module/search')) {	
				$url_keyword_store_id = 0;
				
				if (strpos($data['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if ($data['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
						
				if (trim($data['url_keyword'])) {
					if (VERSION >= '3.0.0.0') {
						$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = '" . $this->db->escape($data['route']) . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$data['language_id'] . "', keyword = '" . $this->db->escape($data['url_keyword']) . "'");
					} else {
						$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$data['language_id'] . "'");
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = '" . $this->db->escape($data['route']) . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$data['language_id'] . "', keyword = '" . $this->db->escape($data['url_keyword']) . "'");
		
						if (($url_keyword_store_id == 0) && ($data['language_id'] == (int)$this->config->get('config_language_id'))) {
							$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($data['route']) . "'");
							$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($data['route']) . "', keyword = '" . $this->db->escape($data['url_keyword']) . "'");
						}
					}
				}
				
				$cache_data = array(
					'route' => $data['route'],
					'store_id' => $data['store_id'],
					'language_id' => $data['language_id']
				);
				
				$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
			}
		}
	}
	
	/*
	*	Delete URL Element.
	*/
	public function deleteURLElement($data) {
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($data['route']) && isset($data['store_id']) && isset($data['url_keyword'])) {
			if ((strpos($data['route'], 'bm_category_id') === 0) || (strpos($data['route'], 'bm_post_id') === 0) || (strpos($data['route'], 'bm_author_id') === 0) || ($data['route'] == 'extension/d_blog_module/search')) {	
				$url_keyword_store_id = 0;
				
				if (strpos($data['route'], 'bm_category_id') === 0) {
					if (isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_post_id') === 0) {
					if (isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if (strpos($data['route'], 'bm_author_id') === 0) {
					if (isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if ($data['route'] == 'extension/d_blog_module/search') {
					if (isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) {
						$url_keyword_store_id = $data['store_id'];
					} else {
						$url_keyword_store_id = 0;
					}
				}
				
				if (VERSION >= '3.0.0.0') {
					$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "'");
				} else {
					$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = '" . $this->db->escape($data['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "'");
			
					if ($url_keyword_store_id == 0) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($data['route']) . "'");
					}
				}
				
				$cache_data = array(
					'route' => $data['route'],
					'store_id' => $data['store_id']
				);
				
				$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
			}
		}
	}
	
	/*
	*	Return Export URL Elements.
	*/
	public function getExportURLElements($data) {	
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		$url_elements = array();	
		
		if ($data['sheet_code'] == 'blog_category') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
				$url_keyword_store_id = $data['store_id'];
			} else {
				$url_keyword_store_id = 0;
			}
			
			if (VERSION >= '3.0.0.0') {
				$query = $this->db->query("SELECT query as route, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'bm_category_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY query, language_id");
			} else {
				$query = $this->db->query("SELECT route, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'bm_category_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY route, language_id");
			}
							
			foreach ($query->rows as $result) {
				$url_elements[$result['route']]['route'] = $result['route'];
				$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
			}
					
			return $url_elements;
		}
		
		if ($data['sheet_code'] == 'blog_post') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
				$url_keyword_store_id = $data['store_id'];
			} else {
				$url_keyword_store_id = 0;
			}
						
			if (VERSION >= '3.0.0.0') {
				$query = $this->db->query("SELECT query as route, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'bm_post_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY query, language_id");
			} else {
				$query = $this->db->query("SELECT route, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'bm_post_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY route, language_id");
			}
							
			foreach ($query->rows as $result) {
				$url_elements[$result['route']]['route'] = $result['route'];
				$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
			}
					
			return $url_elements;	
		}
		
		if ($data['sheet_code'] == 'blog_author') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
				$url_keyword_store_id = $data['store_id'];
			} else {
				$url_keyword_store_id = 0;
			}
						
			if (VERSION >= '3.0.0.0') {
				$query = $this->db->query("SELECT query as route, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'bm_author=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY query, language_id");
			} else {
				$query = $this->db->query("SELECT route, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'bm_author_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY route, language_id");
			}	
							
			foreach ($query->rows as $result) {
				$url_elements[$result['route']]['route'] = $result['route'];
				$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
			}
					
			return $url_elements;	
		}
		
		if ($data['sheet_code'] == 'custom_page') {
			if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
				$url_keyword_store_id = $data['store_id'];
			} else {
				$url_keyword_store_id = 0;
			}
			
			if (VERSION >= '3.0.0.0') {
				$query = $this->db->query("SELECT query as route, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'extension/d_blog_module/search' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY query, language_id");
			} else {
				$query = $this->db->query("SELECT route, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'extension/d_blog_module/search' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY route, language_id");
			}
									
			foreach ($query->rows as $result) {
				$url_elements[$result['route']]['route'] = $result['route'];
				$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
			}
									
			return $url_elements;
		}
	}
	
	/*
	*	Save Import URL Elements.
	*/
	public function saveImportURLElements($data) {		
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
				
		$url_elements = array();	
		
		if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) {
			$url_keyword_store_id = $data['store_id'];
		} else {
			$url_keyword_store_id = 0;
		}
		
		if (VERSION >= '3.0.0.0') {
			$query = $this->db->query("SELECT query as route, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'bm_category_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY query, language_id");
		} else {
			$query = $this->db->query("SELECT route, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'bm_category_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY route, language_id");	
		}
			
		foreach ($query->rows as $result) {
			$url_elements[$result['route']]['route'] = $result['route'];
			$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
		}
				
		if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) {
			$url_keyword_store_id = $data['store_id'];
		} else {
			$url_keyword_store_id = 0;
		}
						
		if (VERSION >= '3.0.0.0') {
			$query = $this->db->query("SELECT query as route, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'bm_post_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY query, language_id");
		} else {
			$query = $this->db->query("SELECT route, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'bm_post_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY route, language_id");	
		}
							
		foreach ($query->rows as $result) {
			$url_elements[$result['route']]['route'] = $result['route'];
			$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
		}
		
		if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) {
			$url_keyword_store_id = $data['store_id'];
		} else {
			$url_keyword_store_id = 0;
		}
						
		if (VERSION >= '3.0.0.0') {
			$query = $this->db->query("SELECT query as route, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query LIKE 'bm_author_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY query, language_id");
		} else {
			$query = $this->db->query("SELECT route, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route LIKE 'bm_author_id=%' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY route, language_id");	
		}
				
		foreach ($query->rows as $result) {
			$url_elements[$result['route']]['route'] = $result['route'];
			$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
		}
		
		if ($data['store_id'] && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store'] && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) {
			$url_keyword_store_id = $data['store_id'];
		} else {
			$url_keyword_store_id = 0;
		}
					
		if (VERSION >= '3.0.0.0') {
			$query = $this->db->query("SELECT query as route, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'extension/d_blog_module/search' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY query, language_id");
		} else {
			$query = $this->db->query("SELECT route, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'extension/d_blog_module/search' AND store_id = '" . (int)$url_keyword_store_id . "' GROUP BY route, language_id");	
		}
									
		foreach ($query->rows as $result) {
			$url_elements[$result['route']]['route'] = $result['route'];
			$url_elements[$result['route']]['url_keyword'][$result['language_id']] = $result['keyword'];
		}
				
		foreach ($data['url_elements'] as $url_element) {
			$sheet_code = '';
			
			if (strpos($url_element['route'], 'bm_category_id') === 0) $sheet_code = 'blog_category';			
			if (strpos($url_element['route'], 'bm_post_id') === 0) $sheet_code = 'blog_post';
			if (strpos($url_element['route'], 'bm_author_id') === 0) $sheet_code = 'blog_author';
			if ($url_element['route'] == 'extension/d_blog_module/search') $sheet_code = 'custom_page';
			
			if ($sheet_code) {
				foreach ($languages as $language) {
					if (isset($url_element['url_keyword'][$language['language_id']])) {
						if ((isset($url_elements[$url_element['route']]['url_keyword'][$language['language_id']]) && ($url_element['url_keyword'][$language['language_id']] != $url_elements[$url_element['route']]['url_keyword'][$language['language_id']])) || !isset($url_elements[$url_element['route']]['url_keyword'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet'][$sheet_code]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$sheet_code]['field']['url_keyword']['multi_store_status']) {
								$url_keyword_store_id = $data['store_id'];
							} else {
								$url_keyword_store_id = 0;
							}
						
							if (VERSION >= '3.0.0.0') {
								$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($url_element['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
										
								if (trim($element['url_keyword'][$language['language_id']])) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = '" . $this->db->escape($url_element['route']) . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($url_element['url_keyword'][$language['language_id']]) . "'");
								}
							} else {
								$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = '" . $this->db->escape($url_element['route']) . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
									
								if (trim($url_element['url_keyword'][$language['language_id']])) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = '" . $this->db->escape($url_element['route']) . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($url_element['url_keyword'][$language['language_id']]) . "'");
								}	
							
								if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
									$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" . $this->db->escape($url_element['route']) . "'");
											
									if (trim($url_element['url_keyword'][$language['language_id']])) {	
										$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($url_element['route']) . "', keyword = '" . $this->db->escape($url_element['url_keyword'][$language['language_id']]) . "'");
									}
								}
							}
						}
					}
				}	
			}
		}
		
		$cache_data = array(
			'store_id' => $data['store_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Replace Target Keyword.
	*/		
	private function replaceTargetKeyword($field_template, $target_keyword) {
		$field_template = preg_replace_callback('/\[target_keyword[^]]+\]/', function($matches) use ($target_keyword) {
			$replacement_target_keyword = '';
			
			if (preg_match('/#number=[0-9]+/', $matches[0], $matches_number)) {
				$explode = explode('=', $matches_number[0]);
				$number = $explode[1]; 
				
				if (isset($target_keyword[$number])) {
					$replacement_target_keyword = $target_keyword[$number];
				}
			}
			
			return $replacement_target_keyword;
			
		}, $field_template);
		
		return $field_template;
	}
}
?>