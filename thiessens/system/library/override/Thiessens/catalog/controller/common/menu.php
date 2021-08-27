<?php
class Thiessens_ControllerCommonMenu extends ControllerCommonMenu {
	public function index() {
		$this->load->language('common/menu');

		// Menu
		$this->load->model('catalog/information');
		
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
		
		$data['categories'] = $this->get_categories(0);
		
        $this->load->model('setting/setting');
		$iblogsConfig = $this->config->get('iblogs');
		
		$setting = $this->model_setting_setting->getSetting($iblogsConfig['code'], $this->config->get('config_store_id'));
		
        $iblogsConfig['setting'] = $this->prepareData(array_replace_recursive(
            $iblogsConfig['setting'],
            !empty($setting[$iblogsConfig['code'] . '_setting']) ? $setting[$iblogsConfig['code'] . '_setting'] : array()
        ));
		
		
		$data["other"]["hunting"] = array(
			"title" => $iblogsConfig["setting"]["title"],
			"url" => $this->url->link($iblogsConfig['path'], '', true)
		);
		
		$pageData = $this->model_catalog_information->getInformation(9);
		$data["other"]["our_story"] = array(
			"title" => $pageData["title"],
			"url" => $this->url->link('information/information', 'information_id=9')
		);
		
		$pageData = $this->model_catalog_information->getInformation(10);
		$data["other"]["support"] = array(
			"title" => $pageData["title"],
			"url" => $this->url->link('information/support', 'information_id=10')
		);
		
		$pageData = $this->model_catalog_information->getInformation(13);
		$data["other"]["nation"] = array(
			"title" => $pageData["title"],
			"url" => $this->url->link('information/information', 'information_id=13')
		);
		
		$pageData = $this->model_catalog_information->getInformation(14);
		$data["other"]["advice"] = array(
			"title" => $pageData["title"],
			"url" => $this->url->link('information/information', 'information_id=14')
		);
	
		return $this->load->view('common/menu', $data);
	}
	
	protected function get_categories($parentCategoryId, $prevPath = '', $level = 0) {
			
		$categories = array();
		$children = $this->model_catalog_category->getCategories($parentCategoryId);
			
		foreach ($children as $category) {
			
			$filter = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);
					
			$name 			= $level <= 1 ? $category['name'] : $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter) . ')' : '');
			$path 			= ($prevPath ? $prevPath . '_' : '') . $category['category_id'];
			$categoryPath 	= $this->url->link('product/category', 'path=' . $path);
			$children 		= $this->get_categories($category['category_id'], $path , $level+1);
			
			$categories[] = array(
				'name'     => $name,
				'children' => $children,
				'column'   => $category['column'] ? $category['column'] : 1,
				'href'     => $categoryPath
			);
		}
		
		return $categories;
		
		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_l2_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {

					$children_l3_data = array();

					$children_l3 = $this->model_catalog_category->getCategories($child['category_id']);

					foreach ($children_l3 as $child_l3) {

						$filter_data_l3 = array(
							'filter_category_id'  => $child_l3['category_id'],
							'filter_sub_category' => true
						);

						$children_l3_data[] = array(
							'name'  => $child_l3['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data_l3) . ')' : ''),
							'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child_l3['category_id'])
						);
					}


					$filter_l2_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_l2_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_l2_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
						'children' => $children_l3_data,
						'column'   => $child['column'] ? $child['column'] : 1,
					);


				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_l2_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
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
