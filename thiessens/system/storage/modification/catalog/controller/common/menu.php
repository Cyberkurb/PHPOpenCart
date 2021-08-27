<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
'sort_order' => $category['sort_order'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}


                //=== ISL iBlogs
                $this->load->model('setting/setting');
                $this->config->load('isenselabs/iblogs');
                $module = $this->config->get('iblogs');

                $setting = $this->model_setting_setting->getSetting($module['code'], $this->config->get('config_store_id'));
                $module['setting'] = array_replace_recursive(
                    $module['setting'],
                    !empty($setting[$module['code'] . '_setting']) ? $setting[$module['code'] . '_setting'] : array()
                );

                if ($module['setting']['status'] && $module['setting']['main_nav']) {
                        $page_url = HTTPS_SERVER . 'index.php?route=' . $module['path'];
                        if ($this->config->get('config_seo_url') && !empty($module['setting']['blog_listing']['url_alias'][$this->config->get('config_language_id')])) {
                            $page_url = HTTPS_SERVER . $module['setting']['blog_listing']['url_alias'][$this->config->get('config_language_id')];
                        }

                        $data['categories'][] = array(
                            'name'       => isset($module['setting']['title'][$this->config->get('config_language_id')]) ? $module['setting']['title'][$this->config->get('config_language_id')] : $module['setting']['title'][0],
                            'href'       => $page_url,
                            'column'     => 1,
                            'children'   => array(),
                            'sort_order' => $module['setting']['main_nav_order'] - 1
                        );

                        if (!function_exists('cmpCategoriesOrder')) {
                            function cmpCategoriesOrder($a, $b) {
                                if ($a['sort_order'] == $b['sort_order']) {
                                    return 0;
                                }
                                return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
                            }
                        }

                        uasort($data['categories'], 'cmpCategoriesOrder');
                    }
                //=== ISL iBlogs :: end
            
		return $this->load->view('common/menu', $data);
	}
}
