<?php
class ControllerExtensionDSEOModuleMetaTitleDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/d_seo_module_meta_title/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $error = array();
	
	/*
	*	Functions for SEO Module Meta Title.
	*/	
	public function edit_meta_element($meta_element_data) {
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_meta_title_' . $this->codename}->editMetaElement($meta_element_data);
	}
	
	public function meta_elements() {	
		$this->load->model($this->route);
		
		return $this->{'model_extension_d_seo_module_meta_title_' . $this->codename}->getMetaElements();
	}
		
	public function store_meta_elements_links($store_meta_elements) {	
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		foreach ($store_meta_elements as $store_id => $meta_elements) {
			foreach ($meta_elements as $meta_element_key => $meta_element) {
				if (strpos($meta_element['route'], 'bm_category_id') === 0) {
					$route_arr = explode("bm_category_id=", $meta_element['route']);
				
					if (isset($route_arr[1])) {
						$category_id = $route_arr[1];
						$store_meta_elements[$store_id][$meta_element_key]['link'] = $this->url->link('extension/d_blog_module/category/edit', $url_token . '&category_id=' . $category_id, true);
					}
				} elseif (strpos($meta_element['route'], 'bm_post_id') === 0) {
					$route_arr = explode("bm_post_id=", $meta_element['route']);
				
					if (isset($route_arr[1])) {
						$post_id = $route_arr[1];
						$store_meta_elements[$store_id][$meta_element_key]['link'] = $this->url->link('extension/d_blog_module/post/edit', $url_token . '&post_id=' . $post_id, true);
					}
				} elseif (strpos($meta_element['route'], 'bm_author_id') === 0) {
					$route_arr = explode("bm_author_id=", $meta_element['route']);
				
					if (isset($route_arr[1])) {
						$author_id = $route_arr[1];
						$store_meta_elements[$store_id][$meta_element_key]['link'] = $this->url->link('extension/d_blog_module/author/edit', $url_token . '&author_id=' . $author_id, true);
					}
				}
			}
		}
		
		return $store_meta_elements;
	}
}
