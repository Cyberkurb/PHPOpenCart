<?php
class ModelExtensionDSEOModuleManagerDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	
	/*
	*	Return List Elements for Manager.
	*/
	public function getListElements($data) {
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if ($data['sheet_code'] == 'blog_category') {
			$implode = array();
			$implode[] = "c.category_id";
			$add = '';
			
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "cd.title";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "cd.short_description";
					}
				
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "cd.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "cd.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "cd.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "cd.meta_keyword";
					}
					
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}

					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}

					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}

					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
					
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
				
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_category_id=', c.category_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_target_keyword tk2 ON (tk2.route = CONCAT('bm_category_id=', c.category_id) AND tk2.store_id = '" . (int)$target_keyword_store_id . "')";				
					}
				
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
							
						if (VERSION >= '3.0.0.0') {
							$add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_category_id=', c.category_id) AND uk2.store_id = '" . (int)$url_keyword_store_id . "')";
						} else {
							$add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_category_id=', c.category_id) AND uk2.store_id = '" . (int)$url_keyword_store_id . "')";
						}
					}
				}
			}
			
			$sql = "SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id AND cd.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_category_id=', c.category_id) AND md.store_id = '0' AND md.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_category_id=', c.category_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "bm_category_description cd2 ON (cd2.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md3 ON (md3.route = CONCAT('bm_category_id=', c.category_id) AND md3.store_id = '0') LEFT JOIN " . DB_PREFIX . "d_meta_data md4 ON (md4.route = CONCAT('bm_category_id=', c.category_id) AND md4.store_id = '" . (int)$data['store_id'] . "')" . $add;
									
			$implode = array();
			
			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'category_id') {
						$implode[] = "c.category_id = '" . (int)($filter) . "'";
					}
					
					if ($field_code == 'target_keyword') {
						$implode[] = "ut2.keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'url_keyword') {
						$implode[] = "uk2.keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
				}
				
				if (!empty($filter) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field_code == 'title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.title LIKE '%" . $this->db->escape($filter) . "%'" : "cd2.title LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.short_description LIKE '%" . $this->db->escape($filter) . "%'" : "cd2.short_description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.description LIKE '%" . $this->db->escape($filter) . "%'" : "cd2.description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_title LIKE '%" . $this->db->escape($filter) . "%'" : "cd2.meta_title LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_description LIKE '%" . $this->db->escape($filter) . "%'" : "cd2.meta_description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_keyword LIKE '%" . $this->db->escape($filter) . "%'" : "cd2.meta_keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_title_1 LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_title_1 LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_title_2 LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_title_2 LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_image_title LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_image_title LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_image_alt LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_image_alt LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_robots LIKE '%" . $this->db->escape($filter) . "%'" : "md3.meta_robots LIKE '%" . $this->db->escape($filter) . "%'";
					}
				}
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY c.category_id";
			
			$query = $this->db->query($sql);
			
			$categories = array();
			
			foreach ($query->rows as $result) {
				$categories[$result['category_id']] = $result;
				$categories[$result['category_id']]['link'] = $this->url->link('extension/d_blog_module/category/edit', $url_token . '&category_id=' . $result['category_id'], true);
			}
			
			return $categories;	
		}
		
		if ($data['sheet_code'] == 'blog_post') {
			$implode = array();
			$implode[] = "p.post_id";
			$add = '';
			
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "pd.title";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "pd.short_description";
					}
				
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "pd.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
					}
				
					if ($field['code'] == 'tag') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.tag" : "pd.tag";
					}
					
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}
				
					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}
				
					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}
				
					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
				
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
					
					if ($field['code'] == 'category_id') {
						$implode[] = "pc.category_id";
					}
					
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_post_id=', p.post_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_target_keyword tk2 ON (tk2.route = CONCAT('bm_post_id=', p.post_id) AND tk2.store_id = '" . (int)$target_keyword_store_id . "')";						
					}
					
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
							
						if (VERSION >= '3.0.0.0') {
							$add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_post_id=', p.post_id) AND uk2.store_id = '" . (int)$url_keyword_store_id . "')";
						} else {
							$add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_post_id=', p.post_id) AND uk2.store_id = '" . (int)$url_keyword_store_id . "')";
						}
					}
				}
			}
			
			$sql = "SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id AND pd.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_post_id=', p.post_id) AND md.store_id = '0' AND md.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_post_id=', p.post_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "bm_post_description pd2 ON (pd2.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md3 ON (md3.route = CONCAT('bm_post_id=', p.post_id) AND md3.store_id = '0') LEFT JOIN " . DB_PREFIX . "d_meta_data md4 ON (md4.route = CONCAT('bm_post_id=', p.post_id) AND md4.store_id = '" . (int)$data['store_id'] . "') LEFT JOIN " . DB_PREFIX . "d_post_category pc ON (pc.post_id = p.post_id)" . $add;
			
			$implode = array();

			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'post_id') {
						$implode[] = "p.post_id = '" . (int)($filter) . "'";
					}
					
					if ($field_code == 'category_id') {
						$implode[] = "pc.category_id = '" . $this->db->escape($filter) . "'";
					}
					
					if ($field_code == 'target_keyword') {
						$implode[] = "ut2.keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
										
					if ($field_code == 'url_keyword') {
						$implode[] = "uk2.keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
				}
				
				if (!empty($filter) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {				
					if ($field_code == 'title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.title LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.title LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.short_description LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.short_description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.description LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_title LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.meta_title LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_description LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.meta_description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_keyword LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.meta_keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'tag') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.tag LIKE '%" . $this->db->escape($filter) . "%'" : "pd2.tag LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_title_1 LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_title_1 LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_title_2 LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_title_2 LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_image_title LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_image_title LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_image_alt LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_image_alt LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_robots LIKE '%" . $this->db->escape($filter) . "%'" : "md3.meta_robots LIKE '%" . $this->db->escape($filter) . "%'";
					}
				}
			}			
			
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY p.post_id";
			
			$query = $this->db->query($sql);
			
			$posts = array();
			
			foreach ($query->rows as $result) {
				$posts[$result['post_id']] = $result;
				$posts[$result['post_id']]['link'] = $this->url->link('extension/d_blog_module/post/edit', $url_token . '&post_id=' . $result['post_id'], true);
			}
			
			return $posts;	
		}
		
		if ($data['sheet_code'] == 'blog_author') {
			$implode = array();
			$implode[] = "a.author_id";
			$add = '';
			
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'name') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.name" : "ad.name";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "ad.short_description";
					}
					
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "ad.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "ad.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "ad.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "ad.meta_keyword";
					}
					
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}
				
					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}
					
					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}
					
					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
				
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
					
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_author_id=', a.author_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_target_keyword tk2 ON (tk2.route = CONCAT('bm_author_id=', a.author_id) AND tk2.store_id = '" . (int)$target_keyword_store_id . "')";					
					}
					
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
							
						if (VERSION >= '3.0.0.0') {
							$add .= "LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "seo_url uk2 ON (uk2.query = CONCAT('bm_author_id=', a.author_id) AND uk2.store_id = '" . (int)$url_keyword_store_id . "')";
						} else {
							$add .= "LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_url_keyword uk2 ON (uk2.route = CONCAT('bm_author_id=', a.author_id) AND uk2.store_id = '" . (int)$url_keyword_store_id . "')";
						}
					}
				}
			}
			
			$sql = "SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id AND ad.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_author_id=', a.author_id) AND md.store_id = '0' AND md.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_author_id=', a.author_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "bm_author_description ad2 ON (ad2.author_id = a.author_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md3 ON (md3.route = CONCAT('bm_author_id=', a.author_id) AND md3.store_id = '0') LEFT JOIN " . DB_PREFIX . "d_meta_data md4 ON (md4.route = CONCAT('bm_author_id=', a.author_id) AND md4.store_id = '" . (int)$data['store_id'] . "')" . $add;
			
			$implode = array();

			foreach ($data['filter'] as $field_code => $filter) {
				if (!empty($filter)) {
					if ($field_code == 'author_id') {
						$implode[] = "a.author_id = '" . (int)($filter) . "'";
					}
					
					if ($field_code == 'target_keyword') {
						$implode[] = "ut2.keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'url_keyword') {
						$implode[] = "uk2.keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
				}
				
				if (!empty($filter) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {				
					if ($field_code == 'name') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.name LIKE '%" . $this->db->escape($filter) . "%'" : "ad2.name LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.short_description LIKE '%" . $this->db->escape($filter) . "%'" : "ad2.short_description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.description LIKE '%" . $this->db->escape($filter) . "%'" : "ad2.description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_title LIKE '%" . $this->db->escape($filter) . "%'" : "ad2.meta_title LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_description LIKE '%" . $this->db->escape($filter) . "%'" : "ad2.meta_description LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_keyword LIKE '%" . $this->db->escape($filter) . "%'" : "ad2.meta_keyword LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_title_1 LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_title_1 LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_title_2 LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_title_2 LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_image_title LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_image_title LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.custom_image_alt LIKE '%" . $this->db->escape($filter) . "%'" : "md3.custom_image_alt LIKE '%" . $this->db->escape($filter) . "%'";
					}
					
					if ($field_code == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md4.meta_robots LIKE '%" . $this->db->escape($filter) . "%'" : "md3.meta_robots LIKE '%" . $this->db->escape($filter) . "%'";
					}
				}
			}			
			
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY a.author_id";
			
			$query = $this->db->query($sql);
			
			$authors = array();
			
			foreach ($query->rows as $result) {
				$authors[$result['author_id']] = $result;
				$authors[$result['author_id']]['link'] = $this->url->link('extension/d_blog_module/author/edit', $url_token . '&author_id=' . $result['author_id'], true);
			}
			
			return $authors;	
		}
	}
	
	/*
	*	Edit Element Field for Manager.
	*/
	public function editElementField($element) {		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
		
		if ($element['sheet_code'] == 'blog_category') {
			if (($element['field_code'] == 'title') || ($element['field_code'] == 'short_description') || ($element['field_code'] == 'description') || ($element['field_code'] == 'meta_title') || ($element['field_code'] == 'meta_description') || ($element['field_code'] == 'meta_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_category_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$element['store_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$element['element_id'] . "', store_id = '" . (int)$element['store_id'] . "', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE category_id = '" . (int)$element['element_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				}
			}
			
			if (($element['field_code'] == 'custom_title_1') || ($element['field_code'] == 'custom_title_2') || ($element['field_code'] == 'custom_image_title') || ($element['field_code'] == 'custom_image_alt') || ($element['field_code'] == 'meta_robots') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_category_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$element['store_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$element['element_id'] . "', store_id = '" . (int)$element['store_id'] . "', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_category_id=" . (int)$element['element_id'] . "' AND store_id = '0' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$element['element_id'] . "', store_id = '0', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				}
			}
			
			if (($element['field_code'] == 'target_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$target_keyword_store_id = $element['store_id'];	
				} else {
					$target_keyword_store_id = 0;
				}
					
				$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_category_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
				
				if ($element['value']) {
					preg_match_all('/\[[^]]+\]/', $element['value'], $keywords);
				
					$sort_order = 1;
					$this->request->post['value'] = '';
				
					foreach ($keywords[0] as $keyword) {
						$keyword = substr($keyword, 1, strlen($keyword) - 2);
						
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_category_id=" . (int)$element['element_id'] . "', store_id='" . (int)$target_keyword_store_id . "', language_id = '" . (int)$element['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
						$sort_order++;
						$this->request->post['value'] .= '[' . $keyword . ']';
					}
				}
			}
			
			if (($element['field_code'] == 'url_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$url_keyword_store_id = $element['store_id'];	
				} else {
					$url_keyword_store_id = 0;
				}
				
				if (VERSION >= '3.0.0.0') {
					$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_category_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (trim($element['value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_category_id=" . (int)$element['element_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$element['language_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_category_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (trim($element['value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_category_id=" . (int)$element['element_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$element['language_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
					}
					
					if (($url_keyword_store_id == 0) && ($element['language_id'] == (int)$this->config->get('config_language_id'))) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_category_id=" . (int)$element['element_id'] . "'");
						
						if (trim($element['value'])) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_category_id=" . (int)$element['element_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
						}
					}
				}

				$cache_data = array(
					'route' => 'bm_category_id=' . $element['element_id'],
					'store_id' => $element['store_id'],
					'language_id' => $element['language_id']
				);
				
				$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);
			}
		}
		
		if ($element['sheet_code'] == 'blog_post') {
			if (($element['field_code'] == 'title') || ($element['field_code'] == 'short_description') || ($element['field_code'] == 'description') || ($element['field_code'] == 'meta_title') || ($element['field_code'] == 'meta_description') || ($element['field_code'] == 'meta_keyword') || ($element['field_code'] == 'tag') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_post_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$element['store_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$element['element_id'] . "', store_id = '" . (int)$element['store_id'] . "', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE post_id = '" . (int)$element['element_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				}
			}
			
			if (($element['field_code'] == 'custom_title_1') || ($element['field_code'] == 'custom_title_2') || ($element['field_code'] == 'custom_image_title') || ($element['field_code'] == 'custom_image_alt') || ($element['field_code'] == 'meta_robots') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_post_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$element['store_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$element['element_id'] . "', store_id = '" . (int)$element['store_id'] . "', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_post_id=" . (int)$element['element_id'] . "' AND store_id = '0' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$element['element_id'] . "', store_id = '0', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				}
			}
			
			if ($element['field_code'] == 'category_id') {
				$this->db->query("DELETE FROM " . DB_PREFIX . "d_post_category WHERE post_id='" . (int)$element['element_id'] . "'");
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "d_post_category SET post_id = '" . (int)$element['element_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . (int)$element['value'] . "'");
			}
			
			if (($element['field_code'] == 'target_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$target_keyword_store_id = $element['store_id'];	
				} else {
					$target_keyword_store_id = 0;
				}
					
				$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_post_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
				
				if ($element['value']) {				
					preg_match_all('/\[[^]]+\]/', $element['value'], $keywords);
				
					$sort_order = 1;
					$this->request->post['value'] = '';
				
					foreach ($keywords[0] as $keyword) {
						$keyword = substr($keyword, 1, strlen($keyword) - 2);
						
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_post_id=" . (int)$element['element_id'] . "', 	store_id='" . (int)$target_keyword_store_id . "', language_id = '" . (int)$element['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
						$sort_order++;
						$this->request->post['value'] .= '[' . $keyword . ']';
					}
				}
			}
						
			if (($element['field_code'] == 'url_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$url_keyword_store_id = $element['store_id'];	
				} else {
					$url_keyword_store_id = 0;
				}
				
				if (VERSION >= '3.0.0.0') {
					$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_post_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (trim($element['value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_post_id=" . (int)$element['element_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$element['language_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_post_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (trim($element['value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_post_id=" . (int)$element['element_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$element['language_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
					}
					
					if (($url_keyword_store_id == 0) && ($element['language_id'] == (int)$this->config->get('config_language_id'))) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_post_id=" . (int)$element['element_id'] . "'");
						
						if (trim($element['value'])) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_post_id=" . (int)$element['element_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
						}
					}
				}

				$cache_data = array(
					'route' => 'bm_post_id=' . $element['element_id'],
					'store_id' => $element['store_id'],
					'language_id' => $element['language_id']
				);
				
				$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);				
			}
		}
		
		if ($element['sheet_code'] == 'blog_author') {
			if (($element['field_code'] == 'name') || ($element['field_code'] == 'short_description') || ($element['field_code'] == 'description') || ($element['field_code'] == 'meta_title') || ($element['field_code'] == 'meta_description') || ($element['field_code'] == 'meta_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_author_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$element['store_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$element['element_id'] . "', store_id = '" . (int)$element['store_id'] . "', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "bm_author_description SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE author_id = '" . (int)$element['element_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				}
			}
			
			if (($element['field_code'] == 'custom_title_1') || ($element['field_code'] == 'custom_title_2') || ($element['field_code'] == 'meta_robots') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_author_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$element['store_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$element['element_id'] . "', store_id = '" . (int)$element['store_id'] . "', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "' WHERE route='bm_author_id=" . (int)$element['element_id'] . "' AND store_id = '0' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (!$this->db->countAffected()) {			
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$element['element_id'] . "', store_id = '" . (int)$element['store_id'] . "', language_id = '" . (int)$element['language_id'] . "', " . $this->db->escape($element['field_code']) . " = '" . $this->db->escape($element['value']) . "'");
					}
				}
			}
			
			if (($element['field_code'] == 'target_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$target_keyword_store_id = $element['store_id'];	
				} else {
					$target_keyword_store_id = 0;
				}
					
				$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_author_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
				if ($element['value']) {
					preg_match_all('/\[[^]]+\]/', $element['value'], $keywords);
				
					$sort_order = 1;
					$this->request->post['value'] = '';
				
					foreach ($keywords[0] as $keyword) {
						$keyword = substr($keyword, 1, strlen($keyword)-2);
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_author_id=" . (int)$element['element_id'] . "', store_id='" . (int)$target_keyword_store_id . "', language_id = '" . (int)$element['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
						$sort_order++;
						$this->request->post['value'] .= '[' . $keyword . ']';
					}
				}
			}
			
			if (($element['field_code'] == 'url_keyword') && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store']) && isset($field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status'])) {
				if ($element['store_id'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store'] && $field_info['sheet'][$element['sheet_code']]['field'][$element['field_code']]['multi_store_status']) {
					$url_keyword_store_id = $element['store_id'];	
				} else {
					$url_keyword_store_id = 0;
				}
				
				if (VERSION >= '3.0.0.0') {
					$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_author_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (trim($element['value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_author_id=" . (int)$element['element_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$element['language_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
					}
				} else {
					$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_author_id=" . (int)$element['element_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$element['language_id'] . "'");
					
					if (trim($element['value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_author_id=" . (int)$element['element_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$element['language_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
					}
					
					if (($url_keyword_store_id == 0) && ($element['language_id'] == (int)$this->config->get('config_language_id'))) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_author_id=" . (int)$element['element_id'] . "'");
						
						if (trim($element['value'])) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_author_id=" . (int)$element['element_id'] . "', keyword = '" . $this->db->escape($element['value']) . "'");
						}
					}
				}

				$cache_data = array(
					'route' => 'bm_author_id=' . $element['element_id'],
					'store_id' => $element['store_id'],
					'language_id' => $element['language_id']
				);
				
				$this->load->controller('extension/module/d_seo_module_blog/refreshURLCache', $cache_data);			
			}
		}
	}
	
	/*
	*	Return Export Elements for Manager.
	*/
	public function getExportElements($data) {
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
				
		if ($data['sheet_code'] == 'blog_category') {
			$categories = array();
			$implode = array();
			$add = '';
						
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "cd.title";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "cd.short_description";
					}
							
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "cd.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "cd.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "cd.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "cd.meta_keyword";
					}
					
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}

					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}

					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}

					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
					
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
					
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_category_id=', c.category_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = cd.language_id)";					
					}
					
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
						
						if (VERSION >= '3.0.0.0') {
							$add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = cd.language_id)";
						} else {
							$add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = cd.language_id)";
						}				
					}
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT cd.category_id, cd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_category_id=', c.category_id) AND md.store_id = '0' AND md.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_category_id=', c.category_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = cd.language_id)" . $add . " GROUP BY c.category_id, cd.language_id");
								
				foreach ($query->rows as $result) {
					$categories[$result['category_id']]['category_id'] = $result['category_id'];
					
					foreach ($result as $field => $value) {
						if (($field != 'category_id') && ($field != 'language_id')) {
							$categories[$result['category_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}
					
			return $categories;	
		}
		
		if ($data['sheet_code'] == 'blog_post') {
			$posts = array();
			$implode = array();
			$add = '';
			
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "pd.title";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "pd.short_description";
					}
				
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "pd.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
					}
				
					if ($field['code'] == 'tag') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.tag" : "pd.tag";
					}
					
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}
				
					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}
				
					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}
				
					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
				
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
					
					if ($field['code'] == 'category_id') {
						$implode[] = "pc.category_id";
					}
					
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_post_id=', p.post_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = pd.language_id)";
					}
									
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
						
						if (VERSION >= '3.0.0.0') {
							$add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = pd.language_id)";
						} else {
							$add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = pd.language_id)";
						}
					}
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT pd.post_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_post_id=', p.post_id) AND md.store_id = '0' AND md.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_post_id=', p.post_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_post_category pc ON (pc.post_id = p.post_id)" . $add . "GROUP BY p.post_id, pd.language_id");
		
				foreach ($query->rows as $result) {
					$posts[$result['post_id']]['post_id'] = $result['post_id'];
					$posts[$result['post_id']]['category_id'] = $result['category_id'];
					
					foreach ($result as $field => $value) {
						if (($field != 'post_id') && ($field != 'language_id') && ($field != 'category_id')) {
							$posts[$result['post_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}
						
			return $posts;	
		}
		
		if ($data['sheet_code'] == 'blog_author') {
			$authors = array();
			$implode = array();
			$add = '';
			
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'name') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.name" : "ad.name";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "ad.short_description";
					}
					
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "ad.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "ad.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "ad.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "ad.meta_keyword";
					}
				
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}
					
					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}
					
					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}
					
					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
				
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
					
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_author_id=', a.author_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = ad.language_id)";
					}
					
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
						
						if (VERSION >= '3.0.0.0') {
							$add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = ad.language_id)";
						} else {
							$add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = ad.language_id)";
						}
					}
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT ad.author_id, ad.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_author_id=', a.author_id) AND md.store_id = '0' AND md.language_id = ad.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_author_id=', a.author_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = ad.language_id)" . $add . " GROUP BY a.author_id, ad.language_id");
				
				foreach ($query->rows as $result) {
					$authors[$result['author_id']]['author_id'] = $result['author_id'];
					
					foreach ($result as $field => $value) {
						if ($field != 'author_id' && $field != 'language_id') {
							$authors[$result['author_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}
						
			return $authors;	
		}
	}
	
	/*
	*	Save Import Elements for Manager.
	*/
	public function saveImportElements($data) {
		$this->load->model('extension/module/' . $this->codename);
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
				
		if ($data['sheet_code'] == 'blog_category') {
			$categories = array();
			$implode = array();
			$add = '';
						
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "cd.title";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "cd.short_description";
					}
							
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "cd.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "cd.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "cd.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "cd.meta_keyword";
					}
					
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}

					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}

					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}

					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
					
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
					
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_category_id=', c.category_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = cd.language_id)";					
					}
					
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
						
						if (VERSION >= '3.0.0.0') {
							$add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = cd.language_id)";
						} else {
							$add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_category_id=', c.category_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = cd.language_id)";
						}				
					}
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT cd.category_id, cd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_category c LEFT JOIN " . DB_PREFIX . "bm_category_description cd ON (cd.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_category_id=', c.category_id) AND md.store_id = '0' AND md.language_id = cd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_category_id=', c.category_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = cd.language_id)" . $add . " GROUP BY c.category_id, cd.language_id");
								
				foreach ($query->rows as $result) {
					$categories[$result['category_id']]['category_id'] = $result['category_id'];
					
					foreach ($result as $field => $value) {
						if (($field != 'category_id') && ($field != 'language_id')) {
							$categories[$result['category_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}	

			foreach ($data['elements'] as $element) {
				if (isset($categories[$element['category_id']])) {
					$category = $categories[$element['category_id']];
					
					foreach ($languages as $language) {
						$implode1 = array();
						$implode2 = array();
						$implode3 = array();
						
						foreach ($data['fields'] as $field) {
							if (($field['code'] == 'title') || ($field['code'] == 'short_description') || ($field['code'] == 'description') || ($field['code'] == 'meta_title') || ($field['code'] == 'meta_description') || ($field['code'] == 'meta_keyword')) {
								if (isset($element[$field['code']][$language['language_id']])) {
									if ((isset($category[$field['code']][$language['language_id']]) && ($element[$field['code']][$language['language_id']] != $category[$field['code']][$language['language_id']])) || !isset($category[$field['code']][$language['language_id']])) {
										if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
											$implode3[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										} else {
											$implode1[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										}
									}
								}
							}
							
							if (($field['code'] == 'custom_title_1') || ($field['code'] == 'custom_title_2') || ($field['code'] == 'custom_image_title') || ($field['code'] == 'custom_image_alt') || ($field['code'] == 'meta_robots')) {
								if (isset($element[$field['code']][$language['language_id']])) {
									if ((isset($category[$field['code']][$language['language_id']]) && ($element[$field['code']][$language['language_id']] != $category[$field['code']][$language['language_id']])) || !isset($category[$field['code']][$language['language_id']])) {
										if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
											$implode3[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										} else {
											$implode2[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										}
									}
								}
							}
						}
						
						if ($implode1) {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_category_description SET " . implode(', ', $implode1) . " WHERE category_id = '" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
						
						if ($implode2) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . implode(', ', $implode2) . " WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
							
							if (!$this->db->countAffected()) {			
								$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', " . implode(', ', $implode2));
							}
						}
						
						if ($implode3) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . implode(', ', $implode3) . " WHERE route='bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							
							if (!$this->db->countAffected()) {			
								$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', " . implode(', ', $implode3));
							}
						}
												
						if (isset($element['target_keyword'][$language['language_id']])) {
							if ((isset($category['target_keyword'][$language['language_id']]) && ($element['target_keyword'][$language['language_id']] != $category['target_keyword'][$language['language_id']])) || !isset($category['target_keyword'][$language['language_id']])) {
								if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
									$target_keyword_store_id = $data['store_id'];
								} else {
									$target_keyword_store_id = 0;
								}
								
								$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");	
								
								if ($element['target_keyword'][$language['language_id']]) {
									preg_match_all('/\[[^]]+\]/', $element['target_keyword'][$language['language_id']], $keywords);
									
									$sort_order = 1;
									
									foreach ($keywords[0] as $keyword) {
										$keyword = substr($keyword, 1, strlen($keyword)-2);
										$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_category_id=" . (int)$category['category_id'] . "', store_id = '" . (int)$target_keyword_store_id . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
										$sort_order++;
									}
								}
							}
						}
						
						if (isset($element['url_keyword'][$language['language_id']])) {
							if ((isset($category['url_keyword'][$language['language_id']]) && ($element['url_keyword'][$language['language_id']] != $category['url_keyword'][$language['language_id']])) || !isset($category['url_keyword'][$language['language_id']])) {
								if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
									$url_keyword_store_id = $data['store_id'];
								} else {
									$url_keyword_store_id = 0;
								}
								
								if (VERSION >= '3.0.0.0') {
									$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
										
									if (trim($element['url_keyword'][$language['language_id']])) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_category_id=" . (int)$category['category_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
									}
								} else {
									$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_category_id=" . (int)$category['category_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
									
									if (trim($element['url_keyword'][$language['language_id']])) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_category_id=" . (int)$category['category_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
									}	
								
									if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
										$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_category_id=" . (int)$category['category_id'] . "'");
											
										if (trim($element['url_keyword'][$language['language_id']])) {	
											$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_category_id=" . (int)$category['category_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
										}
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
		
		if ($data['sheet_code'] == 'blog_post') {
			$posts = array();
			$implode = array();
			$add = '';
			
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.title" : "pd.title";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "pd.short_description";
					}
				
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "pd.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "pd.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "pd.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "pd.meta_keyword";
					}
				
					if ($field['code'] == 'tag') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.tag" : "pd.tag";
					}
					
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}
				
					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}
				
					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}
				
					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
				
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
					
					if ($field['code'] == 'category_id') {
						$implode[] = "pc.category_id";
					}
					
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_post_id=', p.post_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = pd.language_id)";
					}
									
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
						
						if (VERSION >= '3.0.0.0') {
							$add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = pd.language_id)";
						} else {
							$add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_post_id=', p.post_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = pd.language_id)";
						}
					}
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT pd.post_id, pd.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_post p LEFT JOIN " . DB_PREFIX . "bm_post_description pd ON (pd.post_id = p.post_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_post_id=', p.post_id) AND md.store_id = '0' AND md.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_post_id=', p.post_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = pd.language_id) LEFT JOIN " . DB_PREFIX . "d_post_category pc ON (pc.post_id = p.post_id)" . $add . "GROUP BY p.post_id, pd.language_id");
		
				foreach ($query->rows as $result) {
					$posts[$result['post_id']]['post_id'] = $result['post_id'];
					$posts[$result['post_id']]['category_id'] = $result['category_id'];
					
					foreach ($result as $field => $value) {
						if (($field != 'post_id') && ($field != 'language_id') && ($field != 'category_id')) {
							$posts[$result['post_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}	
			
			foreach ($data['elements'] as $element) {
				if (isset($posts[$element['post_id']])) {
					$post = $posts[$element['post_id']];
										
					if (isset($element['category_id']) && isset($post['category_id'])) {
						if ($element['category_id'] != $post['category_id']) {							
							$this->db->query("DELETE FROM " . DB_PREFIX . "d_post_category WHERE post_id='" . (int)$post['post_id'] . "'");
			
							$this->db->query("INSERT INTO " . DB_PREFIX . "d_post_category SET post_id = '" . (int)$post['post_id'] . "', category_id = '" . (int)$element['category_id'] . "'");
						}
					}
					
					foreach ($languages as $language) {
						$implode1 = array();
						$implode2 = array();
						$implode3 = array();
							
						foreach ($data['fields'] as $field) {
							if (($field['code'] == 'title') || ($field['code'] == 'short_description') || ($field['code'] == 'description') || ($field['code'] == 'meta_title') || ($field['code'] == 'meta_description') || ($field['code'] == 'meta_keyword') || ($field['code'] == 'tag')) {
								if (isset($element[$field['code']][$language['language_id']])) {
									if ((isset($post[$field['code']][$language['language_id']]) && ($element[$field['code']][$language['language_id']] != $post[$field['code']][$language['language_id']])) || !isset($post[$field['code']][$language['language_id']])) {
										if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
											$implode3[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										} else {
											$implode1[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										}
									}
								}
							}
							
							if (($field['code'] == 'custom_title_1') || ($field['code'] == 'custom_title_2') || ($field['code'] == 'custom_image_title') || ($field['code'] == 'custom_image_alt') || ($field['code'] == 'meta_robots')) {
								if (isset($element[$field['code']][$language['language_id']])) {
									if ((isset($post[$field['code']][$language['language_id']]) && ($element[$field['code']][$language['language_id']] != $post[$field['code']][$language['language_id']])) || !isset($post[$field['code']][$language['language_id']])) {
										if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
											$implode3[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										} else {
											$implode2[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										}
									}
								}
							}
						}
						
						if ($implode1) {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_post_description SET " . implode(', ', $implode1) . " WHERE post_id = '" . (int)$post['post_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
						
						if ($implode2) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . implode(', ', $implode2) . " WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
							
							if (!$this->db->countAffected()) {			
								$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', " . implode(', ', $implode2));
							}
						}
						
						if ($implode3) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . implode(', ', $implode3) . " WHERE route='bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							
							if (!$this->db->countAffected()) {			
								$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', " . implode(', ', $implode3));
							}
						}
						
						if (isset($element['target_keyword'][$language['language_id']])) {
							if ((isset($post['target_keyword'][$language['language_id']]) && ($element['target_keyword'][$language['language_id']] != $post['target_keyword'][$language['language_id']])) || !isset($post['target_keyword'][$language['language_id']])) {
								if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
									$target_keyword_store_id = $data['store_id'];
								} else {
									$target_keyword_store_id = 0;
								}
								
								$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");	
								
								if ($element['target_keyword'][$language['language_id']]) {
									preg_match_all('/\[[^]]+\]/', $element['target_keyword'][$language['language_id']], $keywords);
									
									$sort_order = 1;
									
									foreach ($keywords[0] as $keyword) {
										$keyword = substr($keyword, 1, strlen($keyword) - 2);
										
										$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_post_id=" . (int)$post['post_id'] . "', store_id = '" . (int)$target_keyword_store_id . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
										
										$sort_order++;
									}
								}
							}
						}
						
						if (isset($element['url_keyword'][$language['language_id']])) {
							if ((isset($post['url_keyword'][$language['language_id']]) && ($element['url_keyword'][$language['language_id']] != $post['url_keyword'][$language['language_id']])) || !isset($post['url_keyword'][$language['language_id']])) {
								if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
									$url_keyword_store_id = $data['store_id'];
								} else {
									$url_keyword_store_id = 0;
								}
								
								if (VERSION >= '3.0.0.0') {
									$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
										
									if (trim($element['url_keyword'][$language['language_id']])) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_post_id=" . (int)$post['post_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
									}
								} else {
									$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_post_id=" . (int)$post['post_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
									
									if (trim($element['url_keyword'][$language['language_id']])) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_post_id=" . (int)$post['post_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
									}	
								
									if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
										$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_post_id=" . (int)$post['post_id'] . "'");
											
										if (trim($element['url_keyword'][$language['language_id']])) {	
											$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_post_id=" . (int)$post['post_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
										}
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
		
		if ($data['sheet_code'] == 'blog_author') {
			$authors = array();
			$implode = array();
			$add = '';
			
			foreach ($data['fields'] as $field) {
				if (isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store']) && isset($field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status'])) {
					if ($field['code'] == 'name') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.name" : "ad.name";
					}
					
					if ($field['code'] == 'short_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.short_description" : "ad.short_description";
					}
					
					if ($field['code'] == 'description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.description" : "ad.description";
					}
				
					if ($field['code'] == 'meta_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_title" : "ad.meta_title";
					}
				
					if ($field['code'] == 'meta_description') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_description" : "ad.meta_description";
					}
				
					if ($field['code'] == 'meta_keyword') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_keyword" : "ad.meta_keyword";
					}
				
					if ($field['code'] == 'custom_title_1') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_1" : "md.custom_title_1";
					}
					
					if ($field['code'] == 'custom_title_2') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_title_2" : "md.custom_title_2";
					}
					
					if ($field['code'] == 'custom_image_title') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_title" : "md.custom_image_title";
					}
					
					if ($field['code'] == 'custom_image_alt') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.custom_image_alt" : "md.custom_image_alt";
					}
				
					if ($field['code'] == 'meta_robots') {
						$implode[] = ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) ? "md2.meta_robots" : "md.meta_robots";
					}
					
					if ($field['code'] == 'target_keyword') {
						$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT tk.keyword ORDER BY tk.sort_order SEPARATOR ']['), ']') as target_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$target_keyword_store_id = $data['store_id'];
						} else {
							$target_keyword_store_id = 0;
						}
						
						$add .= " LEFT JOIN " . DB_PREFIX . "d_target_keyword tk ON (tk.route = CONCAT('bm_author_id=', a.author_id) AND tk.store_id = '" . (int)$target_keyword_store_id . "' AND tk.language_id = ad.language_id)";
					}
					
					if ($field['code'] == 'url_keyword') {
						$implode[] = "uk.keyword as url_keyword";
						
						if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
							$url_keyword_store_id = $data['store_id'];
						} else {
							$url_keyword_store_id = 0;
						}
						
						if (VERSION >= '3.0.0.0') {
							$add .= " LEFT JOIN " . DB_PREFIX . "seo_url uk ON (uk.query = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = ad.language_id)";
						} else {
							$add .= " LEFT JOIN " . DB_PREFIX . "d_url_keyword uk ON (uk.route = CONCAT('bm_author_id=', a.author_id) AND uk.store_id = '" . (int)$url_keyword_store_id . "' AND uk.language_id = ad.language_id)";
						}
					}
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT ad.author_id, ad.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "bm_author a LEFT JOIN " . DB_PREFIX . "bm_author_description ad ON (ad.author_id = a.author_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md ON (md.route = CONCAT('bm_author_id=', a.author_id) AND md.store_id = '0' AND md.language_id = ad.language_id) LEFT JOIN " . DB_PREFIX . "d_meta_data md2 ON (md2.route = CONCAT('bm_author_id=', a.author_id) AND md2.store_id = '" . (int)$data['store_id'] . "' AND md2.language_id = ad.language_id)" . $add . " GROUP BY a.author_id, ad.language_id");
				
				foreach ($query->rows as $result) {
					$authors[$result['author_id']]['author_id'] = $result['author_id'];
					
					foreach ($result as $field => $value) {
						if ($field != 'author_id' && $field != 'language_id') {
							$authors[$result['author_id']][$field][$result['language_id']] = $value;
						}
					}
				}
			}	
			
			foreach ($data['elements'] as $element) {
				if (isset($authors[$element['author_id']])) {
					$author = $authors[$element['author_id']];
					
					foreach ($languages as $language) {
						$implode1 = array();
						$implode2 = array();
						$implode3 = array();
							
						foreach ($data['fields'] as $field) {
							if (($field['code'] == 'name') || ($field['code'] == 'short_description') || ($field['code'] == 'description') || ($field['code'] == 'meta_title') || ($field['code'] == 'meta_description') || ($field['code'] == 'meta_keyword')) {
								if (isset($element[$field['code']][$language['language_id']])) {
									if ((isset($author[$field['code']][$language['language_id']]) && ($element[$field['code']][$language['language_id']] != $author[$field['code']][$language['language_id']])) || !isset($author[$field['code']][$language['language_id']])) {
										if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
											$implode3[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										} else {
											$implode1[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										}
									}
								}
							}
							
							if (($field['code'] == 'custom_title_1') || ($field['code'] == 'custom_title_2') || ($field['code'] == 'meta_robots')) {
								if (isset($element[$field['code']][$language['language_id']])) {
									if ((isset($author[$field['code']][$language['language_id']]) && ($element[$field['code']][$language['language_id']] != $author[$field['code']][$language['language_id']])) || !isset($author[$field['code']][$language['language_id']])) {
										if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field'][$field['code']]['multi_store_status']) {
											$implode3[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										} else {
											$implode2[] = $field['code'] . " = '" . $this->db->escape($element[$field['code']][$language['language_id']]) . "'";
										}
									}
								}
							}
						}
						
						if ($implode1) {
							$this->db->query("UPDATE " . DB_PREFIX . "bm_author_description SET " . implode(', ', $implode1) . " WHERE author_id = '" . (int)$author['author_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
						}
						
						if ($implode2) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . implode(', ', $implode2) . " WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '0' AND language_id = '" . (int)$language['language_id'] . "'");
							
							if (!$this->db->countAffected()) {			
								$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '0', language_id = '" . (int)$language['language_id'] . "', " . implode(', ', $implode2));
							}
						}
						
						if ($implode3) {
							$this->db->query("UPDATE " . DB_PREFIX . "d_meta_data SET " . implode(', ', $implode3) . " WHERE route='bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$data['store_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
							
							if (!$this->db->countAffected()) {			
								$this->db->query("INSERT INTO " . DB_PREFIX . "d_meta_data SET route='bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$language['language_id'] . "', " . implode(', ', $implode3));
							}
						}
						
						if (isset($element['target_keyword'][$language['language_id']])) {
							if ((isset($author['target_keyword'][$language['language_id']]) && ($element['target_keyword'][$language['language_id']] != $author['target_keyword'][$language['language_id']])) || !isset($author['target_keyword'][$language['language_id']])) {
								if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['target_keyword']['multi_store_status']) {
									$target_keyword_store_id = $data['store_id'];
								} else {
									$target_keyword_store_id = 0;
								}
								
								$this->db->query("DELETE FROM " . DB_PREFIX . "d_target_keyword WHERE route = 'bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$target_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");	
								
								if ($element['target_keyword'][$language['language_id']]) {
									preg_match_all('/\[[^]]+\]/', $element['target_keyword'][$language['language_id']], $keywords);
									
									$sort_order = 1;
									
									foreach ($keywords[0] as $keyword) {
										$keyword = substr($keyword, 1, strlen($keyword) - 2);
										
										$this->db->query("INSERT INTO " . DB_PREFIX . "d_target_keyword SET route = 'bm_author_id=" . (int)$author['author_id'] . "', store_id = '" . (int)$target_keyword_store_id . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
										
										$sort_order++;
									}
								}
							}
						}
						
						if (isset($element['url_keyword'][$language['language_id']])) {
							if ((isset($author['url_keyword'][$language['language_id']]) && ($element['url_keyword'][$language['language_id']] != $author['url_keyword'][$language['language_id']])) || !isset($author['url_keyword'][$language['language_id']])) {
								if ($data['store_id'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store'] && $field_info['sheet'][$data['sheet_code']]['field']['url_keyword']['multi_store_status']) {
									$url_keyword_store_id = $data['store_id'];
								} else {
									$url_keyword_store_id = 0;
								}
								
								if (VERSION >= '3.0.0.0') {
									$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
										
									if (trim($element['url_keyword'][$language['language_id']])) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = 'bm_author_id=" . (int)$author['author_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
									}
								} else {
									$this->db->query("DELETE FROM " . DB_PREFIX . "d_url_keyword WHERE route = 'bm_author_id=" . (int)$author['author_id'] . "' AND store_id = '" . (int)$url_keyword_store_id . "' AND language_id = '" . (int)$language['language_id'] . "'");
									
									if (trim($element['url_keyword'][$language['language_id']])) {
										$this->db->query("INSERT INTO " . DB_PREFIX . "d_url_keyword SET route = 'bm_author_id=" . (int)$author['author_id'] . "', store_id='" . (int)$url_keyword_store_id . "', language_id='" . (int)$language['language_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
									}	
								
									if (($url_keyword_store_id == 0) && ($language['language_id'] == (int)$this->config->get('config_language_id'))) {
										$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'bm_author_id=" . (int)$author['author_id'] . "'");
											
										if (trim($element['url_keyword'][$language['language_id']])) {	
											$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'bm_author_id=" . (int)$author['author_id'] . "', keyword = '" . $this->db->escape($element['url_keyword'][$language['language_id']]) . "'");
										}
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
}
?>