<?php
class ControllerExtensionDSEOModuleTargetKeywordDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module_target_keyword/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $error = array();
	
	/*
	*	Functions for SEO Module Target Keyword.
	*/	
	public function edit_target_element($target_element_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_target_keyword_' . $this->codename}->editTargetElement($target_element_data);
	}
	
	public function target_elements() {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_target_keyword_' . $this->codename}->getTargetElements();
	}
	
	public function store_target_elements_links($store_target_elements) {	
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		foreach ($store_target_elements as $store_id => $target_elements) {
			foreach ($target_elements as $target_element_key => $target_element) {
				if (strpos($target_element['route'], 'bm_category_id') === 0) {
					$route_arr = explode("bm_category_id=", $target_element['route']);
				
					if (isset($route_arr[1])) {
						$category_id = $route_arr[1];
						$store_target_elements[$store_id][$target_element_key]['link'] = $this->url->link('extension/d_blog_module/category/edit', $url_token . '&category_id=' . $category_id, true);
					}
				} elseif (strpos($target_element['route'], 'bm_post_id') === 0) {
					$route_arr = explode("bm_post_id=", $target_element['route']);
				
					if (isset($route_arr[1])) {
						$post_id = $route_arr[1];
						$store_target_elements[$store_id][$target_element_key]['link'] = $this->url->link('extension/d_blog_module/post/edit', $url_token . '&post_id=' . $post_id, true);
					}
				} elseif (strpos($target_element['route'], 'bm_author_id') === 0) {
					$route_arr = explode("bm_author_id=", $target_element['route']);
				
					if (isset($route_arr[1])) {
						$author_id = $route_arr[1];
						$store_target_elements[$store_id][$target_element_key]['link'] = $this->url->link('extension/d_blog_module/author/edit', $url_token . '&author_id=' . $author_id, true);
					}
				}
			}
		}
		
		return $store_target_elements;
	}
}
