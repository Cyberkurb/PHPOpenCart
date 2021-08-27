<?php
class ModelExtensionDSEOModuleURLKeywordDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	
	/*
	*	Edit URL Element.
	*/
	public function editURLElement($data) {
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if (isset($data['route']) && isset($data['store_id']) && isset($data['language_id']) && isset($data['url_keyword']) && trim($data['url_keyword'])) {
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
	*	Return URL Elements.
	*/
	public function getURLElements() {
		$this->load->model('extension/module/' . $this->codename);
		
		$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		$url_elements = array();
		
		if (VERSION >= '3.0.0.0') {
			$query = $this->db->query("SELECT c.category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id))");
		} else {
			$query = $this->db->query("SELECT c.category_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id))");
		}
																
		foreach ($query->rows as $result) {
			$route = 'bm_category_id=' . $result['category_id'];
			$url_elements[$route]['route'] = $route;
			
			if (!isset($url_elements[$route]['url_keyword'])) {
				$url_elements[$route]['url_keyword'] = array();
			}
			
			if ((isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['url_keyword']['multi_store_status'])) {
				$url_elements[$route]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
			} elseif ($result['store_id'] == 0) {
				foreach ($stores as $store) {
					$url_elements[$route]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
				}
			}
		}
						
		if (VERSION >= '3.0.0.0') {		
			$query = $this->db->query("SELECT p.post_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_post_id=', p.post_id))");
		} else {
			$query = $this->db->query("SELECT p.post_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_post_id=', p.post_id))");
		}
		
		foreach ($query->rows as $result) {
			$route = 'bm_post_id=' . $result['post_id'];
			$url_elements[$route]['route'] = $route;
			
			if (!isset($url_elements[$route]['url_keyword'])) {
				$url_elements[$route]['url_keyword'] = array();
			}
			
			if ((isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['url_keyword']['multi_store_status'])) {
				$url_elements[$route]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
			} elseif ($result['store_id'] == 0) {
				foreach ($stores as $store) {
					$url_elements[$route]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
				}
			}
		}
		
		if (VERSION >= '3.0.0.0') {
			$query = $this->db->query("SELECT a.author_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_author_id=', a.author_id))");
		} else {
			$query = $this->db->query("SELECT a.author_id, uk.store_id, uk.language_id, uk.keyword FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_author_id=', a.author_id))");
		}
		
		foreach ($query->rows as $result) {
			$route = 'bm_author_id=' . $result['author_id'];
			$url_elements[$route]['route'] = $route;
			
			if (!isset($url_elements[$route]['url_keyword'])) {
				$url_elements[$route]['url_keyword'] = array();
			}
			
			if ((isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['url_keyword']['multi_store_status'])) {
				$url_elements[$route]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
			} elseif ($result['store_id'] == 0) {
				foreach ($stores as $store) {
					$url_elements[$route]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
				}
			}
		}
		
		if (VERSION >= '3.0.0.0') {
			$query = $this->db->query("SELECT query as route, store_id, language_id, keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'extension/d_blog_module/search'");
		} else {
			$query = $this->db->query("SELECT route, store_id, language_id, keyword FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'extension/d_blog_module/search'");
		}
		
		foreach ($query->rows as $result) {			
			$url_elements[$result['route']]['route'] = $result['route'];
			
			if ((isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['url_keyword']['multi_store_status'])) {
				$url_elements[$route]['url_keyword'][$result['store_id']][$result['language_id']] = $result['keyword'];
			} elseif ($result['store_id'] == 0) {
				foreach ($stores as $store) {
					$url_elements[$route]['url_keyword'][$store['store_id']][$result['language_id']] = $result['keyword'];
				}
			}
		}
							
		return $url_elements;
	}
}
?>