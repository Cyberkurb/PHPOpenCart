<?php
class ModelExtensionDSEOModuleTargetKeywordDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	
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
	*	Return Target Elements.
	*/
	public function getTargetElements() {
		$this->load->model('extension/module/' . $this->codename);
		
		$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		$target_elements = array();
				
		$query = $this->db->query("SELECT c.category_id, tk.store_id, tk.language_id, tk.sort_order, tk.keyword FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_category_id=', c.category_id))");
														
		foreach ($query->rows as $result) {
			$route = 'bm_category_id=' . $result['category_id'];
			$target_elements[$route]['route'] = $route;
			
			if (!isset($target_elements[$route]['target_keyword'])) {
				$target_elements[$route]['target_keyword'] = array();
			}
			
			if ((isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_category']['field']['target_keyword']['multi_store_status'])) {
				$target_elements[$route]['target_keyword'][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
			} elseif ($result['store_id'] == 0) {
				foreach ($stores as $store) {
					$target_elements[$route]['target_keyword'][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
				}
			}
		}
						
		$query = $this->db->query("SELECT p.post_id, tk.store_id, tk.language_id, tk.sort_order, tk.keyword FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_post_id=', p.post_id))");
		
		foreach ($query->rows as $result) {
			$route = 'bm_post_id=' . $result['post_id'];
			$target_elements[$route]['route'] = $route;
			
			if (!isset($target_elements[$route]['target_keyword'])) {
				$target_elements[$route]['target_keyword'] = array();
			}
			
			if ((isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_post']['field']['target_keyword']['multi_store_status'])) {
				$target_elements[$route]['target_keyword'][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
			} elseif ($result['store_id'] == 0) {
				foreach ($stores as $store) {
					$target_elements[$route]['target_keyword'][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
				}
			}
		}
		
		$query = $this->db->query("SELECT a.author_id, tk.store_id, tk.language_id, tk.sort_order, tk.keyword FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_author_id=', a.author_id))");
		
		foreach ($query->rows as $result) {
			$route = 'bm_author_id=' . $result['author_id'];
			$target_elements[$route]['route'] = $route;
			
			if (!isset($target_elements[$route]['target_keyword'])) {
				$target_elements[$route]['target_keyword'] = array();
			}
			
			if ((isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['blog_author']['field']['target_keyword']['multi_store_status'])) {
				$target_elements[$route]['target_keyword'][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
			} elseif ($result['store_id'] == 0) {
				foreach ($stores as $store) {
					$target_elements[$route]['target_keyword'][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
				}
			}
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'extension/d_blog_module/search'");
		
		foreach ($query->rows as $result) {
			$target_elements[$result['route']]['route'] = $result['route'];
			
			if ((isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store'] && isset($field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status']) && $field_info['sheet']['custom_page']['field']['target_keyword']['multi_store_status'])) {
				$target_elements[$result['route']]['target_keyword'][$result['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
			} elseif ($result['store_id'] == 0) {
				foreach ($stores as $store) {
					$target_elements[$result['route']]['target_keyword'][$store['store_id']][$result['language_id']][$result['sort_order']] = $result['keyword'];
				}
			}
		}
					
		return $target_elements;
	}
}
?>