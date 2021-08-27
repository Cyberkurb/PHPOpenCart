<?php
class ModelExtensionDSEOModuleAdviserDSEOModuleBlog extends Model {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module_adviser/d_seo_module_blog';
	
	/*
	*	Return Elements for Adviser.
	*/
	public function getAdviserElements($route) {
		$_language = new Language();
		$_language->load($this->route);
		
		$this->load->model('extension/module/' . $this->codename);
		
		$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_SERVER;
			$catalog = HTTPS_CATALOG;
		} else {
			$server = HTTP_SERVER;
			$catalog = HTTP_CATALOG;
		}
		
		$field_info = $this->load->controller('extension/module/d_seo_module/getFieldInfo');
							
		$adviser_elements = array();
		
		if ((strpos($route, 'bm_category_id') === 0) || (strpos($route, 'bm_post_id') === 0) || (strpos($route, 'bm_author_id') === 0) || (($route == 'd_blog_module/search'))) {						
			$file_robots = str_replace('system/', '', DIR_SYSTEM) . 'robots.txt';
		
			if (file_exists($file_robots) && file_exists(DIR_SYSTEM . 'library/d_robots_txt_parser.php')) { 
				$robots_txt_parser = new d_robots_txt_parser(file_get_contents($file_robots));
			}
				
			$field_data = array(
				'field_code' => 'target_keyword',
				'filter' => array('route' => $route)
			);
			
			$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
			if ($target_keywords) $target_keyword = reset($target_keywords);
			
			$field_data = array(
				'field_code' => 'url_keyword',
				'filter' => array('route' => $route)
			);
			
			$url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);			
			if ($url_keywords) $url_keyword = reset($url_keywords);
			
			$field_data = array(
				'field_code' => 'meta_data',
				'filter' => array('route' => $route)
			);
			
			$meta_data = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
												
			if ($meta_data) {
				$meta_info = reset($meta_data);

				foreach ($stores as $store) {					
					foreach ($languages as $language) {
						$target_keyword_duplicate = 0;
			
						if (isset($target_keyword[$store['store_id']][$language['language_id']])) {
							foreach ($target_keyword[$store['store_id']][$language['language_id']] as $keyword) {						
								$field_data = array(
									'field_code' => 'target_keyword',
									'filter' => array(
										'store_id' => $store['store_id'],
										'keyword' => $keyword
									)
								);
			
								$target_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
							
								if ($target_keywords) {
									foreach ($target_keywords as $target_route => $store_target_keywords) {
										foreach ($store_target_keywords[$store['store_id']] as $target_language_id => $keywords) {
											if (($target_route != $route) || ($target_language_id != $language['language_id'])) {
												$target_keyword_duplicate++;
											}
										}
									}
								}
							}
						}
						
						$url_keyword_duplicate = 0;
			
						if (isset($url_keyword[$store['store_id']][$language['language_id']]) && $url_keyword[$store['store_id']][$language['language_id']]) {
							$field_data = array(
								'field_code' => 'url_keyword',
								'filter' => array(
									'store_id' => $store['store_id'],
									'keyword' => $url_keyword[$store['store_id']][$language['language_id']]
								)
							);
				
							$url_keywords = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
						
							if ($url_keywords) {
								foreach ($url_keywords as $url_route => $store_url_keywords) {
									foreach ($store_url_keywords[$store['store_id']] as $url_language_id => $keyword) {
										if (($url_route != $route) || ($url_language_id != $language['language_id'])) {
											$url_keyword_duplicate++;
										}
									}
								}
							}
						}
					
						$meta_title_duplicate = 0;
			
						if (isset($meta_info[$store['store_id']][$language['language_id']]['meta_title']) && $meta_info[$store['store_id']][$language['language_id']]['meta_title']) {
							$field_data = array(
								'field_code' => 'meta_data',
								'filter' => array(
									'store_id' => $store['store_id'],
									'meta_title' => $meta_info[$store['store_id']][$language['language_id']]['meta_title']
								)
							);
			
							$meta_titles = $this->load->controller('extension/module/d_seo_module/getFieldElements', $field_data);
							
							foreach ($meta_titles as $meta_route => $store_meta_titles) {
								foreach ($store_meta_titles[$store['store_id']] as $meta_language_id => $meta_title) {
									if (($meta_route != $route) || ($meta_language_id != $language['language_id'])) {
										$meta_title_duplicate++;
									}
								}
							}
						}
						
						$robots_empty_rating = 0;
			
						if (isset($robots_txt_parser) && $robots_txt_parser->getRules()) {
							$robots_empty_rating = 1;
						}
			
						$robots_no_index_rating = 1;
			
						if (isset($url_keyword[$store['store_id']][$language['language_id']]) && $url_keyword[$store['store_id']][$language['language_id']]) {
							if (isset($robots_txt_parser) && $robots_txt_parser->isUrlDisallow('/' . $url_keyword[$store['store_id']][$language['language_id']])) {
								$robots_no_index_rating = 0;
							}
						}
						
						$url_keyword_target_keyword_rating = 0;
			
						if (isset($url_keyword[$store['store_id']][$language['language_id']]) && isset($target_keyword[$store['store_id']][$language['language_id']])) {
							$url_keyword_target_keyword_count = 0;
				
							foreach ($target_keyword[$store['store_id']][$language['language_id']] as $keyword) {
								if (strpos(mb_strtolower($url_keyword[$store['store_id']][$language['language_id']], 'UTF-8'), mb_strtolower($keyword, 'UTF-8')) !== false) $url_keyword_target_keyword_count++;
							}
				
							$url_keyword_target_keyword_rating = $url_keyword_target_keyword_count / count($target_keyword[$store['store_id']][$language['language_id']]);
						}
						
						$meta_title_target_keyword_rating = 0;
						$meta_description_target_keyword_rating = 0;
						$custom_title_1_target_keyword_rating = 0;
						$custom_title_2_target_keyword_rating = 0;
			
						if (isset($target_keyword[$store['store_id']][$language['language_id']])) {
							$meta_title_target_keyword_count = 0;
							$meta_description_target_keyword_count = 0;
							$custom_title_1_target_keyword_count = 0;
							$custom_title_2_target_keyword_count = 0;
				
							foreach ($target_keyword[$store['store_id']][$language['language_id']] as $keyword) {
								if (isset($meta_info[$store['store_id']][$language['language_id']]['meta_title']) && strpos(mb_strtolower($meta_info[$store['store_id']][$language['language_id']]['meta_title'], 'UTF-8'), mb_strtolower($keyword, 'UTF-8')) !== false) $meta_title_target_keyword_count++;
								if (isset($meta_info[$store['store_id']][$language['language_id']]['meta_description']) && strpos(mb_strtolower($meta_info[$store['store_id']][$language['language_id']]['meta_description'], 'UTF-8'), mb_strtolower($keyword, 'UTF-8')) !== false) $meta_description_target_keyword_count++;
								if (isset($meta_info[$store['store_id']][$language['language_id']]['custom_title_1']) && strpos(mb_strtolower($meta_info[$store['store_id']][$language['language_id']]['custom_title_1'], 'UTF-8'), mb_strtolower($keyword, 'UTF-8')) !== false) $custom_title_1_target_keyword_count++;
								if (isset($meta_info[$store['store_id']][$language['language_id']]['custom_title_2']) && strpos(mb_strtolower($meta_info[$store['store_id']][$language['language_id']]['custom_title_2'], 'UTF-8'), mb_strtolower($keyword, 'UTF-8')) !== false) $custom_title_2_target_keyword_count++;
							}
				
							$meta_title_target_keyword_rating = $meta_title_target_keyword_count / count($target_keyword[$store['store_id']][$language['language_id']]);
							$meta_description_target_keyword_rating = $meta_description_target_keyword_count / count($target_keyword[$store['store_id']][$language['language_id']]);
							$custom_title_1_target_keyword_rating = $custom_title_1_target_keyword_count / count($target_keyword[$store['store_id']][$language['language_id']]);
							$custom_title_2_target_keyword_rating = $custom_title_2_target_keyword_count / count($target_keyword[$store['store_id']][$language['language_id']]);
						}
						
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'target_keyword_empty',
							'name'				=> $_language->get('text_target_keyword_empty'),
							'description'		=> $_language->get('help_target_keyword_empty'),
							'rating'			=> isset($target_keyword[$store['store_id']][$language['language_id']]) ? 1 : 0,
							'weight'			=> 1
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'target_keyword_duplicate',
							'name'				=> $_language->get('text_target_keyword_duplicate'),
							'description'		=> $_language->get('help_target_keyword_duplicate'),
							'rating'			=> $target_keyword_duplicate ? (1 / ($target_keyword_duplicate + 1)) : 1,
							'weight'			=> 0.8
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'robots_empty',
							'name'				=> $_language->get('text_robots_empty'),
							'description'		=> $_language->get('help_robots_empty'),
							'rating'			=> $robots_empty_rating,
							'weight'			=> 1
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'robots_no_index',
							'name'				=> $_language->get('text_robots_no_index'),
							'description'		=> $_language->get('help_robots_no_index'),
							'rating'			=> $robots_no_index_rating,
							'weight'			=> 1
						);
						
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'seo_url_disabled',
							'name'				=> $_language->get('text_seo_url_disabled'),
							'description'		=> $_language->get('help_seo_url_disabled'),
							'rating'			=> ($this->config->get('config_seo_url')) ? 1 : 0,
							'weight'			=> 1
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'url_keyword_empty',
							'name'				=> $_language->get('text_url_keyword_empty'),
							'description'		=> $_language->get('help_url_keyword_empty'),
							'rating'			=> (isset($url_keyword[$store['store_id']][$language['language_id']]) && $url_keyword[$store['store_id']][$language['language_id']]) ? 1 : 0,
							'weight'			=> 1
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'url_keyword_consistency',
							'name'				=> $_language->get('text_url_keyword_consistency'),
							'description'		=> $_language->get('help_url_keyword_consistency'),
							'rating'			=> (isset($url_keyword[$store['store_id']][$language['language_id']]) && $url_keyword[$store['store_id']][$language['language_id']] && filter_var($catalog . $url_keyword[$store['store_id']][$language['language_id']], FILTER_VALIDATE_URL) === false) ? 0 : 1,
							'weight'		=> 1
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'url_keyword_duplicate',
							'name'				=> $_language->get('text_url_keyword_duplicate'),
							'description'		=> $_language->get('help_url_keyword_duplicate'),
							'rating'			=> $url_keyword_duplicate ? (1 / ($url_keyword_duplicate + 1)) : 1,
							'weight'			=> 1
						);
									
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'url_keyword_target_keyword',
							'name'				=> $_language->get('text_url_keyword_target_keyword'),
							'description'		=> $_language->get('help_url_keyword_target_keyword'),
							'rating'			=> $url_keyword_target_keyword_rating,
							'weight'			=> 0.8
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'url_keyword_length',
							'name'				=> $_language->get('text_url_keyword_length'),
							'description'		=> $_language->get('help_url_keyword_length'),
							'rating'			=> (isset($url_keyword[$store['store_id']][$language['language_id']]) && (mb_strlen($url_keyword[$store['store_id']][$language['language_id']], 'UTF-8') > 100)) ? 0 : 1,
							'weight'			=> 0.2
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_title_empty',
							'name'				=> $_language->get('text_meta_title_empty'),
							'description'		=> $_language->get('help_meta_title_empty'),
							'rating'			=> (isset($meta_info[$store['store_id']][$language['language_id']]['meta_title']) && $meta_info[$store['store_id']][$language['language_id']]['meta_title']) ? 1 : 0,
							'weight'			=> 1
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_title_duplicate',
							'name'				=> $_language->get('text_meta_title_duplicate'),
							'description'		=> $_language->get('help_meta_title_duplicate'),
							'rating'			=> $meta_title_duplicate ? (1 / ($meta_title_duplicate + 1)) : 1,
							'weight'			=> 1
						);
									
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_title_target_keyword',
							'name'				=> $_language->get('text_meta_title_target_keyword'),
							'description'		=> $_language->get('help_meta_title_target_keyword'),
							'rating'			=> $meta_title_target_keyword_rating,
							'weight'			=> 0.8
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_title_length',
							'name'				=> $_language->get('text_meta_title_length'),
							'description'		=> $_language->get('help_meta_title_length'),
							'rating'			=> (isset($meta_info[$store['store_id']][$language['language_id']]['meta_title']) && mb_strlen($meta_info[$store['store_id']][$language['language_id']]['meta_title'], 'UTF-8') > 60) ? 0 : 1,
							'weight'			=> 0.2
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_description_empty',
							'name'				=> $_language->get('text_meta_description_empty'),
							'description'		=> $_language->get('help_meta_description_empty'),
							'rating'			=> (isset($meta_info[$store['store_id']][$language['language_id']]['meta_description']) && $meta_info[$store['store_id']][$language['language_id']]['meta_description']) ? 1 : 0,
							'weight'			=> 1
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_description_target_keyword',
							'name'				=> $_language->get('text_meta_description_target_keyword'),
							'description'		=> $_language->get('help_meta_description_target_keyword'),
							'rating'			=> $meta_description_target_keyword_rating,
							'weight'			=> 0.5
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_description_length',
							'name'				=> $_language->get('text_meta_description_length'),
							'description'		=> $_language->get('help_meta_description_length'),
							'rating'			=> (isset($meta_info[$store['store_id']][$language['language_id']]['meta_description']) && mb_strlen($meta_info[$store['store_id']][$language['language_id']]['meta_description'], 'UTF-8') > 160) ? 0 : 1,
							'weight'			=> 0.2
						);
						
						if ((strpos($route, 'bm_category_id') === 0) || (strpos($route, 'bm_post_id') === 0) || (strpos($route, 'bm_author_id') === 0)) {
							$adviser_elements[$store['store_id']][$language['language_id']][] = array(
								'extension_code'	=> $this->codename,
								'element_code'		=> 'custom_title_1_target_keyword',
								'name'				=> $_language->get('text_custom_title_1_target_keyword'),
								'description'		=> $_language->get('help_custom_title_1_target_keyword'),
								'rating'			=> $custom_title_1_target_keyword_rating,
								'weight'			=> 1
							);
			
							$adviser_elements[$store['store_id']][$language['language_id']][] = array(
								'extension_code'	=> $this->codename,
								'element_code'		=> 'custom_title_2_target_keyword',
								'name'				=> $_language->get('text_custom_title_2_target_keyword'),
								'description'		=> $_language->get('help_custom_title_2_target_keyword'),
								'rating'			=> $custom_title_2_target_keyword_rating,
								'weight'			=> 0.4
							);
						}
												
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_robots_no_index',
							'name'				=> $_language->get('text_meta_robots_no_index'),
							'description'		=> $_language->get('help_meta_robots_no_index'),
							'rating'			=> (isset($meta_info[$store['store_id']][$language['language_id']]['meta_robots']) && strpos($meta_info[$store['store_id']][$language['language_id']]['meta_robots'], 'noindex') !== false) ? 0 : 1,
							'weight'			=> 1
						);
			
						$adviser_elements[$store['store_id']][$language['language_id']][] = array(
							'extension_code'	=> $this->codename,
							'element_code'		=> 'meta_robots_no_follow',
							'name'				=> $_language->get('text_meta_robots_no_follow'),
							'description'		=> $_language->get('help_meta_robots_no_follow'),
							'rating'			=> (isset($meta_info[$store['store_id']][$language['language_id']]['meta_robots']) && strpos($meta_info[$store['store_id']][$language['language_id']]['meta_robots'], 'nofollow') !== false) ? 0 : 1,
							'weight'			=> 0.5
						);
					}
				}
			}
		}
		
		return $adviser_elements;
	}
}
?>