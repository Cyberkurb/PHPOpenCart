<?php
class ModelExtensionDSEOModuleBlogDSEOModuleBlog extends Model {
		
	/*
	*	Save Category Meta Data.
	*/
	public function saveCategoryMetaData($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route = 'bm_category_id=" . (int)$data['category_id'] . "'");
		
		if (isset($data['meta_data'])) {
			foreach ($data['meta_data'] as $store_id => $language_meta_data) {
				foreach ($language_meta_data as $language_id => $meta_data) {
					$implode = array();
					
					if ($store_id) {
						if (isset($meta_data['title'])) {
							$implode[] = "title = '" . $this->db->escape($meta_data['title']) . "'";
						}
						
						if (isset($meta_data['short_description'])) {
							$implode[] = "short_description = '" . $this->db->escape($meta_data['short_description']) . "'";
						}
						
						if (isset($meta_data['description'])) {
							$implode[] = "description = '" . $this->db->escape($meta_data['description']) . "'";
						}
						
						if (isset($meta_data['meta_title'])) {
							$implode[] = "meta_title = '" . $this->db->escape($meta_data['meta_title']) . "'";
						}
						
						if (isset($meta_data['meta_description'])) {
							$implode[] = "meta_description = '" . $this->db->escape($meta_data['meta_description']) . "'";
						}
						
						if (isset($meta_data['meta_keyword'])) {
							$implode[] = "meta_keyword = '" . $this->db->escape($meta_data['meta_keyword']) . "'";
						}
					}
					
					if (isset($meta_data['custom_title_1'])) {
						$implode[] = "custom_title_1 = '" . $this->db->escape($meta_data['custom_title_1']) . "'";
					}
					
					if (isset($meta_data['custom_title_2'])) {
						$implode[] = "custom_title_2 = '" . $this->db->escape($meta_data['custom_title_2']) . "'";
					}
					
					if (isset($meta_data['custom_image_title'])) {
						$implode[] = "custom_image_title = '" . $this->db->escape($meta_data['custom_image_title']) . "'";
					}
					
					if (isset($meta_data['custom_image_alt'])) {
						$implode[] = "custom_image_alt = '" . $this->db->escape($meta_data['custom_image_alt']) . "'";
					}
					
					if (isset($meta_data['meta_robots'])) {
						$implode[] = "meta_robots = '" . $this->db->escape($meta_data['meta_robots']) . "'";
					}
					
					$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = 'bm_category_id=" . (int)$data['category_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', " . implode(', ', $implode));
				}
			}
		}
	}
	
	/*
	*	Save Post Meta Data.
	*/
	public function savePostMetaData($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route = 'bm_post_id=" . (int)$data['post_id'] . "'");
		
		if (isset($data['meta_data'])) {
			foreach ($data['meta_data'] as $store_id => $language_meta_data) {
				foreach ($language_meta_data as $language_id => $meta_data) {
					$implode = array();
					
					if ($store_id) {
						if (isset($meta_data['title'])) {
							$implode[] = "title = '" . $this->db->escape($meta_data['title']) . "'";
						}
						
						if (isset($meta_data['short_description'])) {
							$implode[] = "short_description = '" . $this->db->escape($meta_data['short_description']) . "'";
						}
						
						if (isset($meta_data['description'])) {
							$implode[] = "description = '" . $this->db->escape($meta_data['description']) . "'";
						}
						
						if (isset($meta_data['meta_title'])) {
							$implode[] = "meta_title = '" . $this->db->escape($meta_data['meta_title']) . "'";
						}
						
						if (isset($meta_data['meta_description'])) {
							$implode[] = "meta_description = '" . $this->db->escape($meta_data['meta_description']) . "'";
						}
						
						if (isset($meta_data['meta_keyword'])) {
							$implode[] = "meta_keyword = '" . $this->db->escape($meta_data['meta_keyword']) . "'";
						}
						
						if (isset($meta_data['tag'])) {
							$implode[] = "tag = '" . $this->db->escape($meta_data['tag']) . "'";
						}
					}
					
					if (isset($meta_data['custom_title_1'])) {
						$implode[] = "custom_title_1 = '" . $this->db->escape($meta_data['custom_title_1']) . "'";
					}
					
					if (isset($meta_data['custom_title_2'])) {
						$implode[] = "custom_title_2 = '" . $this->db->escape($meta_data['custom_title_2']) . "'";
					}
					
					if (isset($meta_data['custom_image_title'])) {
						$implode[] = "custom_image_title = '" . $this->db->escape($meta_data['custom_image_title']) . "'";
					}
					
					if (isset($meta_data['custom_image_alt'])) {
						$implode[] = "custom_image_alt = '" . $this->db->escape($meta_data['custom_image_alt']) . "'";
					}
					
					if (isset($meta_data['meta_robots'])) {
						$implode[] = "meta_robots = '" . $this->db->escape($meta_data['meta_robots']) . "'";
					}
					
					$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = 'bm_post_id=" . (int)$data['post_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', " . implode(', ', $implode));
				}
			}
		}
	}
	
	/*
	*	Save Author Meta Data.
	*/
	public function saveAuthorMetaData($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route = 'bm_author_id=" . (int)$data['author_id'] . "'");
		
		if (isset($data['meta_data'])) {
			foreach ($data['meta_data'] as $store_id => $language_meta_data) {
				foreach ($language_meta_data as $language_id => $meta_data) {
					$implode = array();
					
					if ($store_id) {
						if (isset($meta_data['name'])) {
							$implode[] = "name = '" . $this->db->escape($meta_data['name']) . "'";
						}
						
						if (isset($meta_data['short_description'])) {
							$implode[] = "short_description = '" . $this->db->escape($meta_data['short_description']) . "'";
						}
						
						if (isset($meta_data['description'])) {
							$implode[] = "description = '" . $this->db->escape($meta_data['description']) . "'";
						}
						
						if (isset($meta_data['meta_title'])) {
							$implode[] = "meta_title = '" . $this->db->escape($meta_data['meta_title']) . "'";
						}
						
						if (isset($meta_data['meta_description'])) {
							$implode[] = "meta_description = '" . $this->db->escape($meta_data['meta_description']) . "'";
						}
						
						if (isset($meta_data['meta_keyword'])) {
							$implode[] = "meta_keyword = '" . $this->db->escape($meta_data['meta_keyword']) . "'";
						}
					}
					
					if (isset($meta_data['custom_title_1'])) {
						$implode[] = "custom_title_1 = '" . $this->db->escape($meta_data['custom_title_1']) . "'";
					}
					
					if (isset($meta_data['custom_title_2'])) {
						$implode[] = "custom_title_2 = '" . $this->db->escape($meta_data['custom_title_2']) . "'";
					}
					
					if (isset($meta_data['custom_image_title'])) {
						$implode[] = "custom_image_title = '" . $this->db->escape($meta_data['custom_image_title']) . "'";
					}
					
					if (isset($meta_data['custom_image_alt'])) {
						$implode[] = "custom_image_alt = '" . $this->db->escape($meta_data['custom_image_alt']) . "'";
					}
					
					if (isset($meta_data['meta_robots'])) {
						$implode[] = "meta_robots = '" . $this->db->escape($meta_data['meta_robots']) . "'";
					}
					
					$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route = 'bm_author_id=" . (int)$data['author_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', " . implode(', ', $implode));
				}
			}
		}
	}
	
	/*
	*	Save Category Target Keyword.
	*/
	public function saveCategoryTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_category_id=" . (int)$data['category_id'] . "'");
						
		if (isset($data['target_keyword'])) {
			foreach ($data['target_keyword'] as $store_id => $language_target_keyword) {
				foreach ($language_target_keyword as $language_id => $keywords) {
					$sort_order = 1;
				
					foreach ($keywords as $keyword) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_category_id=" . (int)$data['category_id'] . "', store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
						$sort_order++;
					}
				}
			}
		}
	}
	
	/*
	*	Save Post Target Keyword.
	*/
	public function savePostTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_post_id=" . (int)$data['post_id'] . "'");
						
		if (isset($data['target_keyword'])) {
			foreach ($data['target_keyword'] as $store_id => $language_target_keyword) {
				foreach ($language_target_keyword as $language_id => $keywords) {
					$sort_order = 1;
				
					foreach ($keywords as $keyword) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_post_id=" . (int)$data['post_id'] . "', store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
						$sort_order++;
					}
				}
			}
		}
	}
	
	/*
	*	Save Author Target Keyword.
	*/
	public function saveAuthorTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_author_id=" . (int)$data['author_id'] . "'");
						
		if (isset($data['target_keyword'])) {
			foreach ($data['target_keyword'] as $store_id => $language_target_keyword) {
				foreach ($language_target_keyword as $language_id => $keywords) {
					$sort_order = 1;
				
					foreach ($keywords as $keyword) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_author_id=" . (int)$data['author_id'] . "', store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
						$sort_order++;
					}
				}
			}
		}
	}
	
	/*
	*	Save Category URL Keyword.
	*/
	public function saveCategoryURLKeyword($data) {
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_category_id=" . (int)$data['category_id'] . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_category_id=" . (int)$data['category_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_category_id=" . (int)$data['category_id'] . "'");
		}
		
		if (isset($data['url_keyword'])) {	
			foreach ($data['url_keyword'] as $store_id => $language_url_keyword) {
				foreach ($language_url_keyword as $language_id => $url_keyword) {
					if ($url_keyword) {
						if (VERSION >= '3.0.0.0') {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_category_id=" . (int)$data['category_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
						} else {
							$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_category_id=" . (int)$data['category_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
							
							if (($store_id == 0) && ($language_id == (int)$this->config->get('config_language_id'))) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_category_id=" . (int)$data['category_id'] . "', keyword = '" . $this->db->escape($url_keyword) . "'");
							}
						}
					}
				}
			}
		}
		
		$cache_data = array(
			'route' => 'bm_category_id=' . $data['category_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Save Post URL Keyword.
	*/
	public function savePostURLKeyword($data) {
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_post_id=" . (int)$data['post_id'] . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_post_id=" . (int)$data['post_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_post_id=" . (int)$data['post_id'] . "'");
		}
		
		if (isset($data['url_keyword'])) {	
			foreach ($data['url_keyword'] as $store_id => $language_url_keyword) {
				foreach ($language_url_keyword as $language_id => $url_keyword) {
					if ($url_keyword) {
						if (VERSION >= '3.0.0.0') {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_post_id=" . (int)$data['post_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
						} else {
							$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_post_id=" . (int)$data['post_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
				
							if (($store_id == 0) && ($language_id == (int)$this->config->get('config_language_id'))) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_post_id=" . (int)$data['post_id'] . "', keyword = '" . $this->db->escape($url_keyword) . "'");
							}
						}
					}
				}
			}
		}
		
		$cache_data = array(
			'route' => 'bm_post_id=' . $data['post_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Save Author URL Keyword.
	*/
	public function saveAuthorURLKeyword($data) {
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_author_id=" . (int)$data['author_id'] . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_author_id=" . (int)$data['author_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_author_id=" . (int)$data['author_id'] . "'");
		}
		
		if (isset($data['url_keyword'])) {	
			foreach ($data['url_keyword'] as $store_id => $language_url_keyword) {
				foreach ($language_url_keyword as $language_id => $url_keyword) {
					if ($url_keyword) {
						if (VERSION >= '3.0.0.0') {
							$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_author_id=" . (int)$data['author_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
						} else {
							$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_author_id=" . (int)$data['author_id'] . "', store_id='" . (int)$store_id . "', language_id='" . (int)$language_id . "', keyword = '" . $this->db->escape($url_keyword) . "'");
				
							if (($store_id == 0) && ($language_id == (int)$this->config->get('config_language_id'))) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_author_id=" . (int)$data['author_id'] . "', keyword = '" . $this->db->escape($url_keyword) . "'");
							}
						}
					}
				}
			}
		}
		
		$cache_data = array(
			'route' => 'bm_author_id=' . $data['author_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Save Post Category.
	*/
	public function savePostCategory($data) {
		if (isset($data['category_id'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_post_category WHERE post_id='" . (int)$data['post_id'] . "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "d_post_category SET post_id = '" . (int)$data['post_id'] . "', category_id = '" . (int)$data['category_id'] . "'");
		}
		
		$cache_data = array(
			'route' => 'bm_post_id=' . $data['post_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Delete Category Meta Data.
	*/
	public function deleteCategoryMetaData($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route = 'bm_category_id=" . (int)$data['category_id'] . "'");
	}

	/*
	*	Delete Post Meta Data.
	*/
	public function deletePostMetaData($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route = 'bm_post_id=" . (int)$data['post_id'] . "'");
	}

	/*
	*	Delete Author Meta Data.
	*/
	public function deleteAuthorMetaData($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_meta_data WHERE route = 'bm_author_id=" . (int)$data['author_id'] . "'");
	}
	
	/*
	*	Delete Category Target Keyword.
	*/
	public function deleteCategoryTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_category_id=" . (int)$data['category_id'] . "'");
	}
	
	/*
	*	Delete Post Target Keyword.
	*/
	public function deletePostTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_post_id=" . (int)$data['post_id'] . "'");
	}
	
	/*
	*	Delete Author Target Keyword.
	*/
	public function deleteAuthorTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_author_id=" . (int)$data['author_id'] . "'");
	}
	
	/*
	*	Delete Category URL Keyword.
	*/
	public function deleteCategoryURLKeyword($data) {
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_category_id=" . (int)$data['category_id'] . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_category_id=" . (int)$data['category_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_category_id=" . (int)$data['category_id'] . "'");
		}
		
		$cache_data = array(
			'route' => 'bm_category_id=' . $data['category_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Delete Post URL Keyword.
	*/
	public function deletePostURLKeyword($data) {
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_post_id=" . (int)$data['post_id'] . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_post_id=" . (int)$data['post_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_post_id=" . (int)$data['post_id'] . "'");
		}
		
		$cache_data = array(
			'route' => 'bm_post_id=' . $data['post_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Delete Author URL Keyword.
	*/
	public function deleteAuthorURLKeyword($data) {
		if (VERSION >= '3.0.0.0') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_author_id=" . (int)$data['author_id'] . "'");
		} else {
			$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_author_id=" . (int)$data['author_id'] . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_author_id=" . (int)$data['author_id'] . "'");
		}
		
		$cache_data = array(
			'route' => 'bm_author_id=' . $data['author_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Delete Post Category.
	*/
	public function deletePostCategory($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "d_post_category WHERE post_id='" . (int)$data['post_id'] . "'");
		
		$cache_data = array(
			'route' => 'bm_post_id=' . $data['post_id']
		);
		
		$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
	}
	
	/*
	*	Return Category Meta Data.
	*/
	public function getCategoryMetaData($category_id) {
		$meta_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_meta_data WHERE route='bm_category_id=" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			$meta_data[$result['store_id']][$result['language_id']] = array(
				'title'					=> $result['title'],
				'short_description'		=> $result['short_description'],
				'description'			=> $result['description'],
				'meta_title'			=> $result['meta_title'],
				'meta_description'		=> $result['meta_description'],
				'meta_keyword'			=> $result['meta_keyword'],
				'custom_title_1'       	=> $result['custom_title_1'],
				'custom_title_2'       	=> $result['custom_title_2'],
				'custom_image_title'  	=> $result['custom_image_title'],
				'custom_image_alt' 		=> $result['custom_image_alt'],
				'meta_robots'			=> $result['meta_robots']
			);
		}
		
		return $meta_data;
	}
	
	/*
	*	Return Post Meta Data.
	*/
	public function getPostMetaData($post_id) {
		$meta_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_meta_data WHERE route='bm_post_id=" . (int)$post_id . "'");
		
		foreach ($query->rows as $result) {
			$meta_data[$result['store_id']][$result['language_id']] = array(
				'title'					=> $result['title'],
				'short_description'		=> $result['short_description'],
				'description'			=> $result['description'],
				'meta_title'			=> $result['meta_title'],
				'meta_description'		=> $result['meta_description'],
				'meta_keyword'			=> $result['meta_keyword'],
				'tag'					=> $result['tag'],
				'custom_title_1'       	=> $result['custom_title_1'],
				'custom_title_2'       	=> $result['custom_title_2'],
				'custom_image_title'  	=> $result['custom_image_title'],
				'custom_image_alt' 		=> $result['custom_image_alt'],
				'meta_robots'			=> $result['meta_robots']
			);
		}
		
		return $meta_data;
	}
	
	/*
	*	Return Author Meta Data.
	*/
	public function getAuthorMetaData($author_id) {
		$meta_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_meta_data WHERE route='bm_author_id=" . (int)$author_id . "'");
		
		foreach ($query->rows as $result) {
			$meta_data[$result['store_id']][$result['language_id']] = array(
				'name'					=> $result['name'],
				'short_description'		=> $result['short_description'],
				'description'			=> $result['description'],
				'meta_title'			=> $result['meta_title'],
				'meta_description'		=> $result['meta_description'],
				'meta_keyword'			=> $result['meta_keyword'],
				'custom_title_1'       	=> $result['custom_title_1'],
				'custom_title_2'       	=> $result['custom_title_2'],
				'custom_image_title'  	=> $result['custom_image_title'],
				'custom_image_alt' 		=> $result['custom_image_alt'],
				'meta_robots'			=> $result['meta_robots']
			);
		}
		
		return $meta_data;
	}
	
	/*
	*	Return Category Target Keyword.
	*/
	public function getCategoryTargetKeyword($category_id) {
		$target_keyword = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_category_id=" . (int)$category_id . "' ORDER BY sort_order");
		
		foreach($query->rows as $result) {
			$target_keyword[$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
		}
		
		return $target_keyword;
	}
	
	/*
	*	Return Post Target Keyword.
	*/
	public function getPostTargetKeyword($post_id) {
		$target_keyword = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_post_id=" . (int)$post_id . "' ORDER BY sort_order");
		
		foreach($query->rows as $result) {
			$target_keyword[$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
		}
		
		return $target_keyword;
	}
	
	/*
	*	Return Author Target Keyword.
	*/
	public function getAuthorTargetKeyword($author_id) {
		$target_keyword = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_author_id=" . (int)$author_id . "' ORDER BY sort_order");
		
		foreach($query->rows as $result) {
			$target_keyword[$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
		}
		
		return $target_keyword;
	}
	
	/*
	*	Return Category URL Keyword.
	*/
	public function getCategoryURLKeyword($category_id) {
		$url_keyword = array();
		
		if (VERSION >= '3.0.0.0') {	
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_category_id=" . (int)$category_id . "'");
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_category_id=" . (int)$category_id . "'");
		}
		
		foreach ($query->rows as $result) {
			$url_keyword[$result['store_id']][$result['language_id']] = $result['keyword'];
		}
						
		return $url_keyword;
	}
			
	/*
	*	Return Post URL Keyword.
	*/
	public function getPostURLKeyword($post_id) {
		$url_keyword = array();
		
		if (VERSION >= '3.0.0.0') {	
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_post_id=" . (int)$post_id . "'");
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_post_id=" . (int)$post_id . "'");
		}
		
		foreach ($query->rows as $result) {
			$url_keyword[$result['store_id']][$result['language_id']] = $result['keyword'];
		}
						
		return $url_keyword;
	}
	
	/*
	*	Return Author URL Keyword.
	*/
	public function getAuthorURLKeyword($author_id) {
		$url_keyword = array();
		
		if (VERSION >= '3.0.0.0') {	
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_author_id=" . (int)$author_id . "'");
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_author_id=" . (int)$author_id . "'");
		}
		
		foreach ($query->rows as $result) {
			$url_keyword[$result['store_id']][$result['language_id']] = $result['keyword'];
		}
					
		return $url_keyword;
	}
	
	/*
	*	Return Post Category.
	*/
	public function getPostCategory($post_id) {
		$query = $this->db->query("SELECT DISTINCT pc.category_id, GROUP_CONCAT(cd.title ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') as category_path FROM " . DB_PREFIX . "d_post_category pc LEFT JOIN " . DB_PREFIX . "bm_category_path cp ON (cp.category_id = pc.category_id) LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = cp.path_id AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE pc.post_id = '" . (int)$post_id . "' GROUP BY cp.category_id");
		
		return $query->row;
	}	
}
?>