<?php
class ControllerExtensionModuleBlogPosts extends Controller {
	
	public function index($setting) {
		
		static $module = 0;
		
		$this->load->language('extension/module/blog_posts');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/manufacturer');

		$this->load->model('tool/image');
		
		if ($setting['name']) {
			$data['heading_title'] = $setting['title'];
		}
		
		$setting["description"] = html_entity_decode($setting["description"]);
		
		$data = array_merge($data, $setting);
		
		/* get blog posts */
		
        $this->load->model('setting/setting');
		$iblogsConfig = $this->config->get('iblogs');
	
		$setting = $this->model_setting_setting->getSetting($iblogsConfig['code'], $this->config->get('config_store_id'));
		
        $iblogsConfig['setting'] = $this->prepareData(array_replace_recursive(
            $iblogsConfig['setting'],
            !empty($setting[$iblogsConfig['code'] . '_setting']) ? $setting[$iblogsConfig['code'] . '_setting'] : array()
        ));
		
        $blog_listing = $this->prepareData($iblogsConfig['setting']['blog_listing']);
		
        $this->load->model('setting/setting');
        $this->load->model($iblogsConfig['path']);

        $iblogsConfig['model'] = $this->{$iblogsConfig['model']};
		
		$params = array(
            // 'category_id' => $category_id,
            // 'tags'        => $tags,
            // 'search'      => $search,
            'start'       => 0,
            'limit'       => 3,
        );
		
		$data['no_image'] = $this->model_tool_image->fit('no_image.png', $blog_listing['image_width'], $blog_listing['image_height']);
		
		$posts = $iblogsConfig['model']->getPosts($params);

        foreach ($posts as $key => $post) {
            $data['posts'][$key] = $post;
            $data['posts'][$key]['image'] = $post['image'] ? $this->model_tool_image->fit($post['image'], $blog_listing['image_width'], $blog_listing['image_height']) : $data['no_image'];
			
            $data['posts'][$key]['info_format'] = $this->infoFormat($blog_listing['info_format'], array(
                '{author}'   => $post['author'],
                '{date}'     => $post['publish'],
                '{category}' => implode(', ', $post['categories_html'])
            ));
        }

		$data['posts_grid'] = array_chunk($data['posts'], 2);
		$data['button_href'] = $this->url->link($iblogsConfig['path'], '', true);
		
		return $this->load->view('extension/module/blog_posts', $data);
		
	}
	
    protected function infoFormat($format, $param = array())
    {
        $template       = array_keys($param);
        $replacement    = array_values($param);

        return html_entity_decode(str_replace($template, $replacement, $format), ENT_QUOTES, 'UTF-8');
    }
	
    protected function prepareData($settings)
    {
        $lang_id = $this->config->get('config_language_id');
        $keys    = array('title', 'excerpt', 'content', 'meta_title', 'meta_description', 'meta_keyword', 'url_alias', 'info_format', 'search_placeholder');

        foreach ($settings as $key => $value) {
            if (in_array($key, $keys) && is_array($value)) {
                $settings[$key] = isset($value[$lang_id]) ? $value[$lang_id] : $value[0];

                if (in_array($key, array('excerpt', 'content'))) {
                    $settings[$key] = html_entity_decode($settings[$key], ENT_QUOTES, 'UTF-8');
                }
            }
        }

        return $settings;
    }
}