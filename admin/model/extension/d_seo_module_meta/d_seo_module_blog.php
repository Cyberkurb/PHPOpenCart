<?php
class ModelExtensionDSEOModuleMetaDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	
	/*
	*	Generate Fields.
	*/
	public function generateFields($data) {		
		$this->load->model('extension/module/' . $this->codename);
		
		$store = $this->{'model_extension_module_' . $this->codename}->getStore($data['store_id']);
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
								
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		$field_data = array(
			'field_code' => 'meta_data',
			'filter' => array(
				'route' => 'common/home',
				'store_id' => $data['store_id']
			)
		);
			
		$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
		
		if (file_exists(DIR_APPLICATION . 'model/extension/module/d_translit.php')) {
			$this->load->model('extension/module/d_translit');
			
			$translit_data = true;
		} else {
			$translit_data = false;
		}
										
		if (isset($data['sheet']['blog_category']['field'])) {
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_category']['field']['meta_title']) && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['meta_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) ? "md2.meta_title" : "cd.meta_title";
			}
			
			if (isset($data['sheet']['blog_category']['field']['meta_description']) && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['meta_description'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) ? "md2.meta_description" : "cd.meta_description";
			}
			
			if (isset($data['sheet']['blog_category']['field']['meta_keyword']) && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['meta_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) ? "md2.meta_keyword" : "cd.meta_keyword";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_title_1']) && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['custom_title_1'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_title_2']) && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['custom_title_2'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_image_name'])) {
				$field = $data['sheet']['blog_category']['field']['custom_image_name'];
				$implode[] = "c.image";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_image_title']) && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['custom_image_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_image_alt']) && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['custom_image_alt'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
			}
			
			if (isset($field_info['sheet']['blog_category']['field']['title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['title']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['title']['multi_store'] && $field_info['sheet']['blog_category']['field']['title']['multi_store_status']) ? "md2.title" : "cd.title";
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['title']['multi_store'] && $field_info['sheet']['blog_category']['field']['title']['multi_store_status']) ? "md3.title as parent_title" : "cd2.title as parent_title";
			}
			
			if (isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['short_description']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['short_description']['multi_store'] && $field_info['sheet']['blog_category']['field']['short_description']['multi_store_status']) ? "md2.short_description" : "cd.short_description";
			}
			
			if (isset($field_info['sheet']['blog_category']['field']['description']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['description']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['description']['multi_store'] && $field_info['sheet']['blog_category']['field']['description']['multi_store_status']) ? "md2.description" : "cd.description";
			}
			
			if (isset($field_info['sheet']['blog_post']['field']['title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['title']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['title']['multi_store'] && $field_info['sheet']['blog_post']['field']['title']['multi_store']['title']['multi_store_status']) ? "GROUP_CONCAT(DISTINCT md4.title ORDER BY pc.post_id SEPARATOR '|') as post_sample" : "GROUP_CONCAT(DISTINCT pd.title ORDER BY pc.post_id SEPARATOR '|') as post_sample";
			}
			
			$implode[] = "COUNT(DISTINCT pc.post_id) as post_total";
			
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
																
				$query = $this->db->query("SELECT cd.category_id, cd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_category_id=', c.category_id) AND md.store_id = '0' AND md.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_category_id=', c.category_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "bm_category_description cd2 ON (cd2.category_id = c.parent_id AND cd2.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md3 ON (md3.route = CONCAT('bm_category_id=', c.parent_id) AND md3.store_id = '" . (int)$data['store_id'] . "' AND md3.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "bm_post_to_category pc ON (pc.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = pc.post_id AND pd.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md4 ON (md4.route = CONCAT('bm_post_id=', pc.post_id) AND md4.store_id = '" . (int)$data['store_id'] . "' AND md4.language_id = cd.language_id) GROUP BY c.category_id, cd.language_id");
					
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
					
					if (isset($meta_data['common/home'][$data['store_id']][$language['language_id']]['meta_title'])) {
						$store_title = $meta_data['common/home'][$data['store_id']][$language['language_id']]['meta_title'];
					} else {
						$store_title = '';
					}
					
					if (is_array($field_template)) {
						$field_new = $field_template[$language['language_id']]; 
					} else {
						$field_new = $field_template; 
					}
					
					$field_new = strtr($field_new, array(
						'[title]' => $category['title'][$language['language_id']], 
						'[parent_title]' => $category['parent_title'][$language['language_id']], 
						'[total_posts]' => $category['post_total'][$language['language_id']],
						'[store_name]' => $store['name'],
						'[store_title]' => $store_title
					));
					$field_new = $this->replaceShortDescription($field_new, $category['short_description'][$language['language_id']]);
					$field_new = $this->replaceDescription($field_new, $category['description'][$language['language_id']]);
					$field_new = $this->replaceSamplePosts($field_new, $category['post_sample'][$language['language_id']]);
					$field_new = $this->replaceTargetKeyword($field_new, $target_keyword);
					
					if ($translit_data) {
						$field_new = $this->model_extension_module_d_translit->translit($field_new, $translit_data);
					}
					
					$implode = array();
					
					if (isset($data['sheet']['blog_category']['field']['custom_image_name']) && isset($category['image'][$language['language_id']]) && ($category['image'][$language['language_id']]) && file_exists(DIR_IMAGE . $category['image'][$language['language_id']]) && ($language == reset($languages))) {
						$file_info = pathinfo(DIR_IMAGE . $category['image'][$language['language_id']]);
						
						if (($field_new != $file_info['filename']) && ($field_overwrite)) {
							rename(DIR_IMAGE . $category['image'][$language['language_id']], $file_info['dirname'] . '/' . $this->db->escape($field_new) . '.' . $file_info['extension']);
							
							$this->db->query("UPDATE " . DB_PREFIX . "bm_category SET image = '" . str_replace(DIR_IMAGE, '', $file_info['dirname']) . '/' . $this->db->escape($field_new) . '.' . $file_info['extension'] . "' WHERE category_id = '" . (int)$category['category_id'] . "'");
						}
					}
																									
					if (isset($data['sheet']['blog_category']['field']['meta_title']) && isset($category['meta_title'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status'])) {
						if (($field_new != $category['meta_title'][$language['language_id']]) && ($field_overwrite || !$category['meta_title'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_title = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET meta_title = '" . $this->db->escape($field_new) . "' WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['meta_description']) && isset($category['meta_description'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status'])) {
						if (($field_new != $category['meta_description'][$language['language_id']]) && ($field_overwrite || !$category['meta_description'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_description = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_description = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET meta_description = '" . $this->db->escape($field_new) . "' WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['meta_keyword']) && isset($category['meta_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status'])) {
						if (($field_new != $category['meta_keyword'][$language['language_id']]) && ($field_overwrite || !$category['meta_keyword'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_keyword = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_keyword = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET meta_keyword = '" . $this->db->escape($field_new) . "' WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['custom_title_1']) && isset($category['custom_title_1'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status'])) {
						if (($field_new != $category['custom_title_1'][$language['language_id']]) && ($field_overwrite || !$category['custom_title_1'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_title_1 = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_title_1 = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['custom_title_2']) && isset($category['custom_title_2'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status'])) {
						if (($field_new != $category['custom_title_2'][$language['language_id']]) && ($field_overwrite || !$category['custom_title_2'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_title_2 = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_title_2 = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['custom_image_title']) && isset($category['custom_image_title'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status'])) {
						if (($field_new != $category['custom_image_title'][$language['language_id']]) && ($field_overwrite || !$category['custom_image_title'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_image_title = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_image_title = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['custom_image_alt']) && isset($category['custom_image_alt'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status'])) {
						if (($field_new != $category['custom_image_alt'][$language['language_id']]) && ($field_overwrite || !$category['custom_image_alt'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_image_alt = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '" . $this->db->escape($field_new) . "' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_image_alt = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
				}
			}
		}
		
		if (isset($data['sheet']['blog_post']['field'])) {
			$field = array();
			$implode = array();
			
			if (isset($data['sheet']['blog_post']['field']['meta_title']) && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['meta_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
			}
			
			if (isset($data['sheet']['blog_post']['field']['meta_description']) && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['meta_description'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
			}
			
			if (isset($data['sheet']['blog_post']['field']['meta_keyword']) && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['meta_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
			}
			
			if (isset($data['sheet']['blog_post']['field']['tag']) && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['tag'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['tag']['multi_store'] && $field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) ? "md2.tag" : "pd.tag";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_title_1']) && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['custom_title_1'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_title_2']) && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['custom_title_2'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_image_name'])) {
				$field = $data['sheet']['blog_post']['field']['custom_image_name'];
				$implode[] = "p.image";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_image_title']) && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['custom_image_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_image_alt']) && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['custom_image_alt'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
			}
			
			if (isset($field_info['sheet']['blog_post']['field']['title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['title']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['title']['multi_store'] && $field_info['sheet']['blog_post']['field']['title']['multi_store_status']) ? "md2.title" : "pd.title";
			}
			
			if (isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['short_description']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['short_description']['multi_store'] && $field_info['sheet']['blog_post']['field']['short_description']['multi_store_status']) ? "md2.short_description" : "pd.short_description";
			}
			
			if (isset($field_info['sheet']['blog_post']['field']['description']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['description']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['description']['multi_store'] && $field_info['sheet']['blog_post']['field']['description']['multi_store_status']) ? "md2.description" : "pd.description";
			}
			
			if (isset($field_info['sheet']['blog_category']['field']['title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['title']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['title']['multi_store'] && $field_info['sheet']['category']['field']['title']['multi_store_status']) ? "md3.title as category_title" : "cd.title as category_title";
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
							
				$query = $this->db->query("SELECT pd.post_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_post_id=', p.post_id) AND md.store_id = '0' AND md.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_post_id=', p.post_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "bm_post_to_category pc ON (pc.post_id = pd.post_id) LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = pc.category_id AND cd.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md3 ON (md3.route = CONCAT('bm_category_id=', pc.category_id) AND md3.store_id = '" . (int)$data['store_id'] . "' AND md3.language_id = pd.language_id) GROUP BY p.post_id, pd.language_id");
				
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
					if (isset($target_keywords['post_id=' . $post['post_id']][$data['store_id']][$language['language_id']])) {
						$target_keyword = $target_keywords['post_id=' . $post['post_id']][$data['store_id']][$language['language_id']];
					} else {
						$target_keyword = array();
					}
					
					if (isset($store['title'][$language['language_id']])) {
						$store_title = $store['title'][$language['language_id']];
					} else {
						$store_title = '';
					}
					
					if (is_array($field_template)) {
						$field_new = $field_template[$language['language_id']]; 
					} else {
						$field_new = $field_template; 
					}
										
					$field_new = strtr($field_new, array(
						'[title]' => $post['title'][$language['language_id']], 
						'[category]' => $post['category_title'][$language['language_id']], 
						'[store_name]' => $store['name'],
						'[store_title]' => $store_title
					));
					$field_new = $this->replaceShortDescription($field_new, $post['short_description'][$language['language_id']]);
					$field_new = $this->replaceDescription($field_new, $post['description'][$language['language_id']]);
					$field_new = $this->replaceTargetKeyword($field_new, $target_keyword);
					
					if ($translit_data) {
						$field_new = $this->model_extension_module_d_translit->translit($field_new, $translit_data);
					}
																																			
					if (isset($data['sheet']['blog_post']['field']['custom_image_name']) && isset($post['image'][$language['language_id']]) && ($post['image'][$language['language_id']]) && file_exists(DIR_IMAGE . $post['image'][$language['language_id']]) && ($language == reset($languages))) {
						$file_info = pathinfo(DIR_IMAGE . $post['image'][$language['language_id']]);
						
						if (($field_new != $file_info['filename']) && ($field_overwrite)) {
							rename(DIR_IMAGE . $post['image'][$language['language_id']], $file_info['dirname'] . '/' . $this->db->escape($field_new) . '.' . $file_info['extension']);
							
							$this->db->query("UPDATE " . DB_PREFIX . "bm_post SET image = '" . str_replace(DIR_IMAGE, '', $file_info['dirname']) . '/' . $this->db->escape($field_new) . '.' . $file_info['extension'] . "' WHERE post_id = '" . (int)$post['post_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['meta_title']) && isset($post['meta_title'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status'])) {
						if (($field_new != $post['meta_title'][$language['language_id']]) && ($field_overwrite || !$post['meta_title'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_title = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET meta_title = '" . $this->db->escape($field_new) . "' WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}					
										
					if (isset($data['sheet']['blog_post']['field']['meta_description']) && isset($post['meta_description'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status'])) {
						if (($field_new != $post['meta_description'][$language['language_id']]) && ($field_overwrite || !$post['meta_description'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_description = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_description = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET meta_description = '" . $this->db->escape($field_new) . "' WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['meta_keyword']) && isset($post['meta_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status'])) {
						if (($field_new != $post['meta_keyword'][$language['language_id']]) && ($field_overwrite || !$post['meta_keyword'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_keyword = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_keyword = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET meta_keyword = '" . $this->db->escape($field_new) . "' WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['tag']) && isset($post['tag'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store_status'])) {
						if (($field_new != $post['tag'][$language['language_id']]) && ($field_overwrite || !$post['tag'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['tag']['multi_store'] && $field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET tag = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', tag = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET tag = '" . $this->db->escape($field_new) . "' WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['custom_title_1']) && isset($post['custom_title_1'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status'])) {
						if (($field_new != $post['custom_title_1'][$language['language_id']]) && ($field_overwrite || !$post['custom_title_1'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_title_1 = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_title_1 = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['custom_title_2']) && isset($post['custom_title_2'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status'])) {
						if (($field_new != $post['custom_title_2'][$language['language_id']]) && ($field_overwrite || !$post['custom_title_2'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_title_2 = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_title_2 = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
				
					if (isset($data['sheet']['blog_post']['field']['custom_image_title']) && isset($post['custom_image_title'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status'])) {
						if (($field_new != $post['custom_image_title'][$language['language_id']]) && ($field_overwrite || !$post['custom_image_title'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_image_title = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_image_title = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
										
					if (isset($data['sheet']['blog_post']['field']['custom_image_alt']) && isset($post['custom_image_alt'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status'])) {
						if (($field_new != $post['custom_image_alt'][$language['language_id']]) && ($field_overwrite || !$post['custom_image_alt'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_image_alt = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '" . $this->db->escape($field_new) . "' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_image_alt = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
				}		
			}	
		}
		
		if (isset($data['sheet']['blog_author']['field'])) {
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_author']['field']['meta_title']) && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['meta_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) ? "md2.meta_title" : "ad.meta_title";
			}
			
			if (isset($data['sheet']['blog_author']['field']['meta_description']) && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['meta_description'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) ? "md2.meta_description" : "ad.meta_description";
			}
			
			if (isset($data['sheet']['blog_author']['field']['meta_keyword']) && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['meta_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) ? "md2.meta_keyword" : "ad.meta_keyword";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_title_1']) && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['custom_title_1'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_title_2']) && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['custom_title_2'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_image_name'])) {
				$field = $data['sheet']['blog_author']['field']['custom_image_name'];
				$implode[] = "a.image";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_image_title']) && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['custom_image_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_image_alt']) && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['custom_image_alt'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
			}
			
			if (isset($field_info['sheet']['blog_author']['field']['name']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['name']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['name']['multi_store'] && $field_info['sheet']['blog_author']['field']['name']['multi_store_status']) ? "md2.name" : "ad.name";
			}
			
			if (isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['short_description']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['short_description']['multi_store'] && $field_info['sheet']['blog_author']['field']['description']['multi_store_status']) ? "md2.short_description" : "ad.short_description";
			}
			
			if (isset($field_info['sheet']['blog_author']['field']['description']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['description']['multi_store_status'])) {
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['description']['multi_store'] && $field_info['sheet']['blog_author']['field']['description']['multi_store_status']) ? "md2.description" : "ad.description";
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
				
				$query = $this->db->query("SELECT ad.author_id, ad.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_author_id=', a.author_id) AND md.store_id = '0' AND md.language_id = ad.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_author_id=', a.author_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = ad.language_id) GROUP BY a.author_id, ad.language_id");
				
				foreach ($query->rows as $result) {
					$authors[$result['author_id']]['author_id'] = $result['author_id'];
				
					foreach ($result as $field => $value) {
						if ($field != 'author_id' && $field != 'language_id') {
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
					
					if (isset($store['title'][$language['language_id']])) {
						$store_title = $store['title'][$language['language_id']];
					} else {
						$store_title = '';
					}
					
					if (is_array($field_template)) {
						$field_new = $field_template[$language['language_id']]; 
					} else {
						$field_new = $field_template; 
					}
					
					$field_new = strtr($field_new, array(
						'[name]' => $author['name'][$language['language_id']], 
						'[store_name]' => $store['name'],
						'[store_title]' => $store_title
					));
					$field_new = $this->replaceShortDescription($field_new, $author['short_description'][$language['language_id']]);
					$field_new = $this->replaceDescription($field_new, $author['description'][$language['language_id']]);
					$field_new = $this->replaceTargetKeyword($field_new, $target_keyword);
					
					if ($translit_data) {
						$field_new = $this->model_extension_module_d_translit->translit($field_new, $translit_data);
					}
					
					$implode = array();
					
					if (isset($data['sheet']['blog_author']['field']['custom_image_name']) && isset($author['image'][$language['language_id']]) && ($author['image'][$language['language_id']]) && file_exists(DIR_IMAGE . $author['image'][$language['language_id']]) && ($language == reset($languages))) {
						$file_info = pathinfo(DIR_IMAGE . $author['image'][$language['language_id']]);
						
						if (($field_new != $file_info['filename']) && ($field_overwrite)) {
							rename(DIR_IMAGE . $author['image'][$language['language_id']], $file_info['dirname'] . '/' . $this->db->escape($field_new) . '.' . $file_info['extension']);
							
							$this->db->query("UPDATE " . DB_PREFIX . "user SET image = '" . str_replace(DIR_IMAGE, '', $file_info['dirname']) . '/' . $this->db->escape($field_new) . '.' . $file_info['extension'] . "' WHERE user_id = '" . (int)$author['user_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['meta_title']) && isset($author['meta_title'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status'])) {
						if (($field_new != $author['meta_title'][$language['language_id']]) && ($field_overwrite || !$author['meta_title'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_title = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_author_description SET meta_title = '" . $this->db->escape($field_new) . "' WHERE author_id = '" . (int)$author['author_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
										
					if (isset($data['sheet']['blog_author']['field']['meta_description']) && isset($author['meta_description'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status'])) {
						if (($field_new != $author['meta_description'][$language['language_id']]) && ($field_overwrite || !$author['meta_description'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_description = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_description = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_author_description SET meta_description = '" . $this->db->escape($field_new) . "' WHERE author_id = '" . (int)$author['author_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['meta_keyword']) && isset($author['meta_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status'])) {
						if (($field_new != $author['meta_keyword'][$language['language_id']]) && ($field_overwrite || !$author['meta_keyword'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_keyword = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', meta_keyword = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "bm_author_description SET meta_keyword = '" . $this->db->escape($field_new) . "' WHERE author_id = '" . (int)$author['author_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							}
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['custom_title_1']) && isset($author['custom_title_1'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status'])) {
						if (($field_new != $author['custom_title_1'][$language['language_id']]) && ($field_overwrite || !$author['custom_title_1'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_title_1 = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_title_1 = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['custom_title_2']) && isset($author['custom_title_2'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status'])) {
						if (($field_new != $author['custom_title_2'][$language['language_id']]) && ($field_overwrite || !$author['custom_title_2'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_title_2 = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_title_2 = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['custom_image_title']) && isset($author['custom_image_title'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status'])) {
						if (($field_new != $author['custom_image_title'][$language['language_id']]) && ($field_overwrite || !$author['custom_image_title'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_image_title = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_image_title = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['custom_image_alt']) && isset($author['custom_image_alt'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status'])) {
						if (($field_new != $author['custom_image_alt'][$language['language_id']]) && ($field_overwrite || !$author['custom_image_alt'][$language['language_id']])) {
							if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status']) {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', custom_image_alt = '" . $this->db->escape($field_new) . "'");
								}
							} else {
								$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '" . $this->db->escape($field_new) . "' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
								
								if (!$this->db->countAffected()) {			
									$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', custom_image_alt = '" . $this->db->escape($field_new) . "'");
								}
							}
						}
					}
				}		
			}	
		}
	}
	
	/*
	*	Clear Fields.
	*/
	public function clearFields($data) {		
		$this->load->model('extension/module/' . $this->codename);
		
		$store = $this->{'model_extension_module_' . $this->codename}->getStore($data['store_id']);
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
								
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
												
		if (isset($data['sheet']['blog_category']['field'])) {
			if (isset($data['sheet']['blog_category']['field']['meta_title']) && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['meta_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) ? "md2.meta_title" : "cd.meta_title";
			}
			
			if (isset($data['sheet']['blog_category']['field']['meta_description']) && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['meta_description'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) ? "md2.meta_description" : "cd.meta_description";
			}
			
			if (isset($data['sheet']['blog_category']['field']['meta_keyword']) && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['meta_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) ? "md2.meta_keyword" : "cd.meta_keyword";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_title_1']) && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['custom_title_1'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_title_2']) && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['custom_title_2'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_image_name'])) {
				$field = $data['sheet']['blog_category']['field']['custom_image_name'];
				$implode[] = "c.image";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_image_title']) && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['custom_image_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
			}
			
			if (isset($data['sheet']['blog_category']['field']['custom_image_alt']) && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status'])) {
				$field = $data['sheet']['blog_category']['field']['custom_image_alt'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
			}
						
			$categories = array();
			
			$query = $this->db->query("SELECT cd.category_id, cd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_category_id=', c.category_id) AND md.store_id = '0' AND md.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_category_id=', c.category_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = cd.language_id) GROUP BY c.category_id, cd.language_id");
						
			foreach ($query->rows as $result) {
				$categories[$result['category_id']]['category_id'] = $result['category_id'];
				
				foreach ($result as $field => $value) {
					if (($field != 'category_id') && ($field != 'language_id')) {
						$categories[$result['category_id']][$field][$result['language_id']] = $value;
					}
				}
			}
								
			foreach ($categories as $category) {
				foreach ($languages as $language) {
					if (isset($data['sheet']['blog_category']['field']['custom_image_name']) && isset($category['image'][$language['language_id']]) && ($category['image'][$language['language_id']]) && ($language == reset($languages))) {
						$this->db->query("UPDATE " . DB_PREFIX . "bm_category SET image = '' WHERE category_id = '" . (int)$category['category_id'] . "'");
					}
					
					if (isset($data['sheet']['blog_category']['field']['meta_title']) && isset($category['meta_title'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_title']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET meta_title = '' WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['meta_description']) && isset($category['meta_description'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_description']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_description = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET meta_description = '' WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['meta_keyword']) && isset($category['meta_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_category']['field']['meta_keyword']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_keyword = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET meta_keyword = '' WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['custom_title_1']) && isset($category['custom_title_1'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_title_1']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['custom_title_2']) && isset($category['custom_title_2'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_title_2']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['custom_image_title']) && isset($category['custom_image_title'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_image_title']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_category']['field']['custom_image_alt']) && isset($category['custom_image_alt'][$language['language_id']]) && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_category']['field']['custom_image_alt']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '' WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
				}		
			}
		}
		
		if (isset($data['sheet']['blog_post']['field'])) {
			$field = array();
			$implode = array();
			
			if (isset($data['sheet']['blog_post']['field']['meta_title']) && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['meta_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
			}
			
			if (isset($data['sheet']['blog_post']['field']['meta_description']) && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['meta_description'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
			}
			
			if (isset($data['sheet']['blog_post']['field']['meta_keyword']) && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['meta_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
			}
			
			if (isset($data['sheet']['blog_post']['field']['tag']) && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['tag'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['tag']['multi_store'] && $field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) ? "md2.tag" : "pd.tag";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_title_1']) && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['custom_title_1'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_title_2']) && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['custom_title_2'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_image_name'])) {
				$field = $data['sheet']['blog_post']['field']['custom_image_name'];
				$implode[] = "p.image";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_image_title']) && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['custom_image_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
			}
			
			if (isset($data['sheet']['blog_post']['field']['custom_image_alt']) && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status'])) {
				$field = $data['sheet']['blog_post']['field']['custom_image_alt'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
			}
															
			$posts = array();
			
			$query = $this->db->query("SELECT pd.post_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_post_id=', p.post_id) AND md.store_id = '0' AND md.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_post_id=', p.post_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = pd.language_id) GROUP BY p.post_id, pd.language_id");
				
			foreach ($query->rows as $result) {
				$posts[$result['post_id']]['post_id'] = $result['post_id'];
				
				foreach ($result as $field => $value) {
					if (($field != 'post_id') && ($field != 'language_id')) {
						$posts[$result['post_id']][$field][$result['language_id']] = $value;
					}
				}
			}
			
			foreach ($posts as $post) {
				foreach ($languages as $language) {
					if (isset($data['sheet']['blog_post']['field']['custom_image_name']) && isset($post['image'][$language['language_id']]) && ($post['image'][$language['language_id']]) && ($language == reset($languages))) {
						$this->db->query("UPDATE " . DB_PREFIX . "bm_post SET image = '' WHERE post_id = '" . (int)$post['post_id'] . "'");
					}
					
					if (isset($data['sheet']['blog_post']['field']['meta_title']) && isset($post['meta_title'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_title']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET meta_title = '' WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}					
										
					if (isset($data['sheet']['blog_post']['field']['meta_description']) && isset($post['meta_description'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_description']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_description = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET meta_description = '' WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['meta_keyword']) && isset($post['meta_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_post']['field']['meta_keyword']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_keyword = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET meta_keyword = '' WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['tag']) && isset($post['tag'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['tag']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['tag']['multi_store'] && $field_info['sheet']['blog_post']['field']['tag']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET tag = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET tag = '' WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['custom_title_1']) && isset($post['custom_title_1'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_title_1']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_post']['field']['custom_title_2']) && isset($post['custom_title_2'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_title_2']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
				
					if (isset($data['sheet']['blog_post']['field']['custom_image_title']) && isset($post['custom_image_title'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_image_title']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
										
					if (isset($data['sheet']['blog_post']['field']['custom_image_alt']) && isset($post['custom_image_alt'][$language['language_id']]) && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_post']['field']['custom_image_alt']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '' WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
				}		
			}	
		}
		
		if (isset($data['sheet']['blog_author']['field'])) {
			$field = array();
			$implode = array();
						
			if (isset($data['sheet']['blog_author']['field']['meta_title']) && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['meta_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) ? "md2.meta_title" : "ad.meta_title";
			}
			
			if (isset($data['sheet']['blog_author']['field']['meta_description']) && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['meta_description'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) ? "md2.meta_description" : "ad.meta_description";
			}
			
			if (isset($data['sheet']['blog_author']['field']['meta_keyword']) && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['meta_keyword'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) ? "md2.meta_keyword" : "ad.meta_keyword";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_title_1']) && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['custom_title_1'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_title_2']) && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['custom_title_2'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_image_name'])) {
				$field = $data['sheet']['blog_author']['field']['custom_image_name'];
				$implode[] = "a.image";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_image_title']) && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['custom_image_title'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
			}
			
			if (isset($data['sheet']['blog_author']['field']['custom_image_alt']) && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status'])) {
				$field = $data['sheet']['blog_author']['field']['custom_image_alt'];
				$implode[] = ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
			}
						
			$authors = array();
			
			$query = $this->db->query("SELECT ad.author_id, ad.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_author_id=', a.author_id) AND md.store_id = '0' AND md.language_id = ad.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_author_id=', a.author_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = ad.language_id) GROUP BY a.author_id, ad.language_id");
				
			foreach ($query->rows as $result) {
				$authors[$result['author_id']]['author_id'] = $result['author_id'];
				
				foreach ($result as $field => $value) {
					if ($field != 'author_id' && $field != 'language_id') {
						$authors[$result['author_id']][$field][$result['language_id']] = $value;
					}
				}
			}
			
			foreach ($authors as $author) {
				foreach ($languages as $language) {
					if (isset($data['sheet']['blog_author']['field']['custom_image_name']) && isset($author['image'][$language['language_id']]) && ($author['image'][$language['language_id']]) && ($language == reset($languages))) {
						$this->db->query("UPDATE " . DB_PREFIX . "user SET image = '' WHERE user_id = '" . (int)$author['user_id'] . "'");
					}
					
					if (isset($data['sheet']['blog_author']['field']['meta_title']) && isset($author['meta_title'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_title']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_title = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_author_description SET meta_title = '' WHERE author_id = '" . (int)$author['author_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
										
					if (isset($data['sheet']['blog_author']['field']['meta_description']) && isset($author['meta_description'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_description']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_description = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_author_description SET meta_description = '' WHERE author_id = '" . (int)$author['author_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['meta_keyword']) && isset($author['meta_keyword'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store'] && $field_info['sheet']['blog_author']['field']['meta_keyword']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET meta_keyword = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_author_description SET meta_keyword = '' WHERE author_id = '" . (int)$author['author_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['custom_title_1']) && isset($author['custom_title_1'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_title_1']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_1 = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['custom_title_2']) && isset($author['custom_title_2'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_title_2']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_title_2 = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['custom_image_title']) && isset($author['custom_image_title'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_image_title']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_title = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
					
					if (isset($data['sheet']['blog_author']['field']['custom_image_alt']) && isset($author['custom_image_alt'][$language['language_id']]) && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store']) && isset($field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status'])) {
						if ($data['store_id'] && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store'] && $field_info['sheet']['blog_author']['field']['custom_image_alt']['multi_store_status']) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						} else {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET custom_image_alt = '' WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
						}
					}
				}		
			}	
		}
	}
	
	/*
	*	Replace Short Description.
	*/		
	private function replaceShortDescription($field_template, $short_description) {
		$field_template = preg_replace_callback('/\[short_description[^]]+\]/', function($matches) use ($short_description) {
			$replacement_short_description = '';
			
			if (preg_match('/#sentences=[0-9]+/', $matches[0], $matches_sentences)) {
				$explode = explode('=', $matches_sentences[0]);
				$sentence_total = $explode[1]; 
				$sentences = preg_split('/[.!?]/', htmlentities(strip_tags(html_entity_decode($short_description)))); 
				$i = 0;
				
				foreach ($sentences as $sentence) {
					$replacement_short_description .= $sentence . '.';
					$i++;
					if ($i>=$sentence_total) break;
				}
			}
			
			return $replacement_short_description;
			
		}, $field_template);
		
		return $field_template;
	}
		
	/*
	*	Replace Description.
	*/		
	private function replaceDescription($field_template, $description) {
		$field_template = preg_replace_callback('/\[description[^]]+\]/', function($matches) use ($description) {
			$replacement_description = '';
			
			if (preg_match('/#sentences=[0-9]+/', $matches[0], $matches_sentences)) {
				$explode = explode('=', $matches_sentences[0]);
				$sentence_total = $explode[1]; 
				$sentences = preg_split('/[.!?]/', htmlentities(strip_tags(html_entity_decode($description)))); 
				$i = 0;
				
				foreach ($sentences as $sentence) {
					$replacement_description .= $sentence . '.';
					$i++;
					if ($i>=$sentence_total) break;
				}
			}
			
			return $replacement_description;
			
		}, $field_template);
		
		return $field_template;
	}
		
	/*
	*	Replace Sample Posts.
	*/		
	private function replaceSamplePosts($field_template, $post_sample) {
		$field_template = preg_replace_callback('/\[sample_posts[^]]+\]/', function ($matches) use ($post_sample) {
			$replacement_sample_posts = '';
						
			if (preg_match('/#total=[0-9]+/', $matches[0], $matches_total)) {
				$explode = explode('=', $matches_total[0]);
				$post_total = $explode[1]; 
				
				if (preg_match('/#separator=[^]]+/', $matches[0], $matches_separator)) {
					$explode = explode('=', $matches_separator[0]);
					$post_separator = $explode[1]; 
					$sample_posts = explode('|', $post_sample);
					$i = 0;
					
					foreach ($sample_posts as $sample_post) {
						$replacement_sample_posts .= $sample_post;
						$i++;
						if ($i>=$post_total) break;
						$replacement_sample_posts .= $post_separator;
					}
				}
			}
			
			return $replacement_sample_posts;
			
		}, $field_template);
		
		return $field_template;
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