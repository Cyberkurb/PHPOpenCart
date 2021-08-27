<?php
class ControllerExtensionModuleDSEOModuleBlog extends Controller {
	private $codename = 'd_seo_module_blog';
	private $route = 'extension/module/d_seo_module_blog';
	private $config_file = 'd_seo_module_blog';
	private $extension = array();
	private $error = array();

	public function __construct($registry) {
		parent::__construct($registry);

		$this->d_shopunity = (file_exists(DIR_SYSTEM . 'library/d_shopunity/extension/d_shopunity.json'));
		$this->extension = json_decode(file_get_contents(DIR_SYSTEM . 'library/d_shopunity/extension/' . $this->codename . '.json'), true);
	}
	
	public function index() {
		$this->setting();
	}

	public function setting() {
		$this->load->language($this->route);

		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		if ($this->d_shopunity) {
			$this->load->model('extension/d_shopunity/mbooth');
				
			$this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
		}
		
		if (file_exists(DIR_APPLICATION . 'model/extension/module/d_twig_manager.php')) {
			$this->load->model('extension/module/d_twig_manager');
			
			$this->model_extension_module_d_twig_manager->installCompatibility();
		}
		
		if (isset($this->request->get['store_id'])) { 
			$store_id = $this->request->get['store_id']; 
		} else {  
			$store_id = 0;
		}
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .=  'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .=  'user_token=' . $this->session->data['user_token'];
		}
		
		$url_store = 'store_id=' . $store_id;
				
		// Styles and Scripts
		$this->document->addStyle('view/stylesheet/d_bootstrap_extra/bootstrap.css');
		$this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');
		$this->document->addStyle('view/stylesheet/d_seo_module.css');

		// Heading
		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');

		// Variable
		$data['codename'] = $this->codename;
		$data['route'] = $this->route;
		$data['version'] = $this->extension['version'];
		$data['config'] = $this->config_file;
		$data['d_shopunity'] = $this->d_shopunity;
		$data['store_id'] = $store_id;
		$data['stores'] = $this->{'model_extension_module_' . $this->codename}->getStores();
		$data['languages'] = $this->{'model_extension_module_' . $this->codename}->getLanguages();

		$installed_seo_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOExtensions();
		$data['installed'] = in_array($this->codename, $installed_seo_extensions) ? true : false;

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		// Action
		$data['href_setting'] = $this->url->link($this->route . '/setting', $url_token . '&' . $url_store, true);
		$data['href_instruction'] = $this->url->link($this->route . '/instruction', $url_token . '&' . $url_store, true);
		
		$data['module_link'] = $this->url->link($this->route, $url_token . '&' . $url_store, true);
		$data['action'] = $this->url->link($this->route . '/save', $url_token . '&' . $url_store, true);
		$data['install'] = $this->url->link($this->route . '/installExtension', $url_token, true);
		$data['uninstall'] = $this->url->link($this->route . '/uninstallExtension', $url_token, true);
		
		if (VERSION >= '3.0.0.0') {
			$data['cancel'] = $this->url->link('marketplace/extension', $url_token . '&type=module', true);
		} elseif (VERSION >= '2.3.0.0') {
			$data['cancel'] = $this->url->link('extension/extension', $url_token . '&type=module', true);
		} else {
			$data['cancel'] = $this->url->link('extension/module', $url_token, true);
		}
		
		// Tab
		$data['text_settings'] = $this->language->get('text_settings');
		$data['text_instructions'] = $this->language->get('text_instructions');
		$data['text_instructions_full'] = $this->language->get('text_instructions_full');
		$data['text_basic_settings'] = $this->language->get('text_basic_settings');
		$data['text_blog_category'] = $this->language->get('text_blog_category');
		$data['text_blog_post'] = $this->language->get('text_blog_post');
		$data['text_blog_author'] = $this->language->get('text_blog_author');
		$data['text_blog_search'] = $this->language->get('text_blog_search');
		
		// Button
		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		// Entry
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_uninstall'] = $this->language->get('entry_uninstall');
		$data['entry_unique_url'] = $this->language->get('entry_unique_url');
		$data['entry_exception_data'] = $this->language->get('entry_exception_data');
		$data['entry_short_url'] = $this->language->get('entry_short_url');
		$data['entry_custom_title_1_class'] = $this->language->get('entry_custom_title_1_class');
		$data['entry_custom_title_2_class'] = $this->language->get('entry_custom_title_2_class');
		$data['entry_custom_image_class'] = $this->language->get('entry_custom_image_class');
	
		// Text
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_install'] = $this->language->get('text_install');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_uninstall_confirm'] = $this->language->get('text_uninstall_confirm');
		
		$data['text_info_setting_blog_category'] = $this->language->get('text_info_setting_blog_category');
		$data['text_info_setting_blog_post'] = $this->language->get('text_info_setting_blog_post');
		$data['text_info_setting_blog_author'] = $this->language->get('text_info_setting_blog_author');
		
		// Help
		$data['help_install'] = $this->language->get('help_install');
		$data['help_unique_url'] = $this->language->get('help_unique_url');
		$data['help_exception_data'] = $this->language->get('help_exception_data');
		$data['help_short_url'] = $this->language->get('help_short_url');
				
		// Notification
		foreach ($this->error as $key => $error) {
			$data['error'][$key] = $error;
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		// Breadcrumbs
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $url_token, true)
		);

		if (VERSION >= '3.0.0.0') {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('marketplace/extension', $url_token . '&type=module', true)
			);
		} elseif (VERSION >= '2.3.0.0') {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('extension/extension', $url_token . '&type=module', true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('extension/module', $url_token, true)
			);
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, $url_token . '&' . $url_store, true)
		);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($data['installed']) {
			// Setting
			$this->config->load($this->config_file);
			$data['setting'] = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();
		
			$setting = $this->model_setting_setting->getSetting('module_' . $this->codename, $store_id);
			$status = isset($setting['module_' . $this->codename . '_status']) ? $setting['module_' . $this->codename . '_status'] : false;
			$setting = isset($setting['module_' . $this->codename . '_setting']) ? $setting['module_' . $this->codename . '_setting'] : array();

			$data['status'] = $status;
		
			if (!empty($setting)) {
				$data['setting'] = array_replace_recursive($data['setting'], $setting);
			}
			
			$this->response->setOutput($this->load->view($this->route . '/setting', $data));
		} else {
			// Setting
			$this->config->load($this->config_file);
			$config_feature_setting = ($this->config->get($this->codename . '_feature_setting')) ? $this->config->get($this->codename . '_feature_setting') : array();
		
			$data['features'] = array();
		
			foreach ($config_feature_setting as $feature) {
				if (substr($feature['name'], 0, strlen('text_')) == 'text_') {
					$feature['name'] = $this->language->get($feature['name']);
				}
						
				$data['features'][] = $feature;
			}
			
			$this->response->setOutput($this->load->view($this->route . '/install', $data));
		}
	}
	
	public function instruction() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		if ($this->d_shopunity) {		
			$this->load->model('extension/d_shopunity/mbooth');
				
			$this->model_extension_d_shopunity_mbooth->validateDependencies($this->codename);
		}
		
		if (file_exists(DIR_APPLICATION . 'model/extension/module/d_twig_manager.php')) {
			$this->load->model('extension/module/d_twig_manager');
			
			$this->model_extension_module_d_twig_manager->installCompatibility();
		}
				
		if (isset($this->request->get['store_id'])) { 
			$store_id = $this->request->get['store_id']; 
		} else {  
			$store_id = 0;
		}
		
		$url_token = '';
		
		if (isset($this->session->data['token'])) {
			$url_token .= 'token=' . $this->session->data['token'];
		}
		
		if (isset($this->session->data['user_token'])) {
			$url_token .= 'user_token=' . $this->session->data['user_token'];
		}
		
		$url_store = 'store_id=' . $store_id;
		
		// Styles and Scripts
		$this->document->addStyle('view/stylesheet/d_bootstrap_extra/bootstrap.css');
		$this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');
		$this->document->addStyle('view/stylesheet/d_seo_module.css');
				
		// Heading
		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');
		
		// Variable
		$data['codename'] = $this->codename;
		$data['route'] = $this->route;
		$data['version'] = $this->extension['version'];
		$data['config'] = $this->config_file;
		$data['d_shopunity'] = $this->d_shopunity;
		$data['store_id'] = $store_id;
						
		$installed_seo_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOExtensions();
		$data['installed'] = in_array($this->codename, $installed_seo_extensions) ? true : false;
						
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
				
		// Action
		$data['href_setting'] = $this->url->link($this->route . '/setting', $url_token . '&' . $url_store, true);
		$data['href_instruction'] = $this->url->link($this->route . '/instruction', $url_token . '&' . $url_store, true);
		
		$data['install'] = $this->url->link($this->route . '/installExtension', $url_token, true);
		
		if (VERSION >= '3.0.0.0') {
			$data['cancel'] = $this->url->link('marketplace/extension', $url_token . '&type=module', true);
		} elseif (VERSION >= '2.3.0.0') {
			$data['cancel'] = $this->url->link('extension/extension', $url_token . '&type=module', true);
		} else {
			$data['cancel'] = $this->url->link('extension/module', $url_token, true);
		}
				
		// Tab
		$data['text_settings'] = $this->language->get('text_settings');
		$data['text_instructions'] = $this->language->get('text_instructions');
						
		// Button
		$data['button_cancel'] = $this->language->get('button_cancel');	
		$data['button_install'] = $this->language->get('button_install');
										
		// Text
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_install'] = $this->language->get('text_install');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_instructions_full'] = $this->language->get('text_instructions_full');
		
		// Help
		$data['help_install'] = $this->language->get('help_install');
								
		// Notification
		foreach ($this->error as $key => $error) {
			$data['error'][$key] = $error;
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		// Breadcrumbs
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $url_token, true)
		);

		if (VERSION >= '3.0.0.0') {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('marketplace/extension', $url_token . '&type=module', true)
			);
		} elseif (VERSION >= '2.3.0.0') {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('extension/extension', $url_token . '&type=module', true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_modules'),
				'href' => $this->url->link('extension/module', $url_token, true)
			);
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, $url_token . '&' . $url_store, true)
		);
								
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($data['installed']) {
			$this->response->setOutput($this->load->view($this->route . '/instruction', $data));
		} else {
			// Setting
			$this->config->load($this->config_file);
			$config_feature_setting = ($this->config->get($this->codename . '_feature_setting')) ? $this->config->get($this->codename . '_feature_setting') : array();
		
			$data['features'] = array();
		
			foreach ($config_feature_setting as $feature) {
				if (substr($feature['name'], 0, strlen('text_')) == 'text_') {
					$feature['name'] = $this->language->get($feature['name']);
				}
						
				$data['features'][] = $feature;
			}
			
			$this->response->setOutput($this->load->view($this->route . '/install', $data));
		}
	}

	public function save() {
		$this->load->language($this->route);

		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		if (isset($this->request->get['store_id'])) { 
			$store_id = $this->request->get['store_id']; 
		} else {  
			$store_id = 0;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$old_setting = $this->model_setting_setting->getSetting('module_' . $this->codename, $store_id);
			
			$new_setting = array_replace_recursive($old_setting, $this->request->post);
						
			if (isset($this->request->post['module_' . $this->codename . '_status']) && $this->request->post['module_' . $this->codename . '_status']) {
				$new_setting['module_' . $this->codename . '_setting']['control_element']['enable_status']['implemented'] = 1;
			}
						
			$this->model_setting_setting->editSetting('module_' . $this->codename, $new_setting, $store_id);
			
			$save_data = array(
				'old_setting'		=> $old_setting,
				'new_setting'		=> $new_setting,
				'store_id'			=> $store_id
			);			

			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
		
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/save', $save_data);
			}
						
			$data['success'] = $this->language->get('success_save');
		}

		$data['error'] = $this->error;

		$this->response->setOutput(json_encode($data));
	}
	
	public function installExtension() {
		$this->load->language($this->route);

		$this->load->model($this->route);
		$this->load->model('user/user_group');		

		if ($this->validateInstall()) {
			$this->{'model_extension_module_' . $this->codename}->installExtension();
			
			if (file_exists(DIR_APPLICATION . 'model/extension/module/d_event_manager.php')) {
				$this->load->model('extension/module/d_event_manager');
				
				$this->model_extension_module_d_event_manager->installCompatibility();		
				$this->model_extension_module_d_event_manager->deleteEvent($this->codename);
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/view/extension/d_blog_module/category_form/after', 'extension/module/d_seo_module_blog/category_form_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/category/addCategory/after', 'extension/module/d_seo_module_blog/category_add_category_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/category/editCategory/after', 'extension/module/d_seo_module_blog/category_edit_category_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/category/deleteCategory/after', 'extension/module/d_seo_module_blog/category_delete_category_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/view/extension/d_blog_module/post_form/after', 'extension/module/d_seo_module_blog/post_form_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/post/addPost/after', 'extension/module/d_seo_module_blog/post_add_post_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/post/editPost/after', 'extension/module/d_seo_module_blog/post_edit_post_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/post/deletePost/after', 'extension/module/d_seo_module_blog/post_delete_post_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/view/extension/d_blog_module/author_form/after', 'extension/module/d_seo_module_blog/author_form_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/author/addAuthor/after', 'extension/module/d_seo_module_blog/author_add_author_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/author/editAuthor/after', 'extension/module/d_seo_module_blog/author_edit_author_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'admin/model/extension/d_blog_module/author/deleteAuthor/after', 'extension/module/d_seo_module_blog/author_delete_author_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/extension/d_blog_module/category/before', 'extension/module/d_seo_module_blog/category_before');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/extension/d_blog_module/category/after', 'extension/module/d_seo_module_blog/category_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/category/getCategory/after', 'extension/module/d_seo_module_blog/category_get_category_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/category/getCategories/after', 'extension/module/d_seo_module_blog/category_get_categories_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/category/getAllCategories/after', 'extension/module/d_seo_module_blog/category_get_all_categories_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/category/getCategoryParents/after', 'extension/module/d_seo_module_blog/category_get_category_parents_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/category/getCategoryByPostId/after', 'extension/module/d_seo_module_blog/category_get_category_by_post_id_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/d_blog_module/post/before', 'extension/module/d_seo_module_blog/post_before');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/d_blog_module/post/after', 'extension/module/d_seo_module_blog/post_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/post/getPost/after', 'extension/module/d_seo_module_blog/post_get_post_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/post/getPosts/after', 'extension/module/d_seo_module_blog/post_get_posts_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/post/getPostsByCategoryId/after', 'extension/module/d_seo_module_blog/post_get_posts_by_category_id_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/post/getPrevPost/after', 'extension/module/d_seo_module_blog/post_get_prev_post_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/post/getNextPost/after', 'extension/module/d_seo_module_blog/post_get_next_post_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/d_blog_module/author/before', 'extension/module/d_seo_module_blog/author_before');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/d_blog_module/author/after', 'extension/module/d_seo_module_blog/author_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/model/extension/d_blog_module/author/getAuthor/after', 'extension/module/d_seo_module_blog/author_get_author_after');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/d_blog_module/search/before', 'extension/module/d_seo_module_blog/search_before');
				$this->model_extension_module_d_event_manager->addEvent($this->codename, 'catalog/view/d_blog_module/search/after', 'extension/module/d_seo_module_blog/search_after');
			}
			
			if (file_exists(DIR_APPLICATION . 'model/extension/d_opencart_patch/modification.php')) {
				$this->load->model('extension/d_opencart_patch/modification');
		
				$this->model_extension_d_opencart_patch_modification->setModification($this->codename . '.xml', 1);
				$this->model_extension_d_opencart_patch_modification->refreshCache();
			}
			
			if (file_exists(DIR_APPLICATION . 'model/extension/d_opencart_patch/extension.php') && file_exists(DIR_APPLICATION . 'model/extension/d_opencart_patch/user.php')) {
				$this->load->model('extension/d_opencart_patch/extension');			
				$this->load->model('extension/d_opencart_patch/user');	
				
				$user_group_id = $this->model_extension_d_opencart_patch_user->getGroupId();
				
				// Install SEO Module Blog
				if (!$this->model_extension_d_opencart_patch_extension->isInstalled('d_seo_module_blog')) {
					$this->model_extension_d_opencart_patch_extension->install('module', 'd_seo_module_blog');
				
					$this->model_user_user_group->addPermission($user_group_id, 'access', 'extension/module/d_seo_module_blog');
					$this->model_user_user_group->addPermission($user_group_id, 'modify', 'extension/module/d_seo_module_blog');
				}
			}
			
			$data['success'] = $this->language->get('success_install');
		}

		$data['error'] = $this->error;

		$this->response->setOutput(json_encode($data));
	}

	public function uninstallExtension() {
		$this->load->language($this->route);

		$this->load->model($this->route);
					
		if ($this->validateUninstall()) {
			$this->{'model_extension_module_' . $this->codename}->uninstallExtension();
			
			if (file_exists(DIR_APPLICATION . 'model/extension/module/d_event_manager.php')) {
				$this->load->model('extension/module/d_event_manager');
				
				$this->model_extension_module_d_event_manager->deleteEvent($this->codename);
			}

			if (file_exists(DIR_APPLICATION . 'model/extension/d_opencart_patch/modification.php')) {
				$this->load->model('extension/d_opencart_patch/modification');
		
				$this->model_extension_d_opencart_patch_modification->setModification($this->codename . '.xml', 0);
				$this->model_extension_d_opencart_patch_modification->refreshCache();
			}
			
			$data['success'] = $this->language->get('success_uninstall');
		}

		$data['error'] = $this->error;

		$this->response->setOutput(json_encode($data));
	}
	
	public function install() {
		if ($this->d_shopunity) {
			$this->load->model('extension/d_shopunity/mbooth');
			
			$this->model_extension_d_shopunity_mbooth->installDependencies($this->codename);
		}
	}

	public function category_form_after($route, $data, &$output) {
		$this->load->language($this->route);

		$this->load->model($this->route);
		
		if (file_exists(DIR_SYSTEM . 'library/d_simple_html_dom.php')) {
			$html_tab_general = '';
			$html_tab_general_language = array();
			$html_tab_general_store = array();
			$html_tab_general_store_language = array();
			$html_tab_data = '';
			$html_style = '';
			$html_script = '';

			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
			unset($stores[0]);
			
			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$html_tab_general .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_form_tab_general');
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_form_tab_general_language');
				
				foreach ($languages as $language) {
					if (!isset($html_tab_general_language[$language['language_id']])) $html_tab_general_language[$language['language_id']] = '';
					
					if (isset($info[$language['language_id']])) {
						$html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
					}
				}
				
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_form_tab_general_store');
				
				foreach ($stores as $store) {
					if (!isset($html_tab_general_store[$store['store_id']])) $html_tab_general_store[$store['store_id']] = '';
					
					if (isset($info[$store['store_id']])) {
						$html_tab_general_store[$store['store_id']] .= $info[$store['store_id']];
					}
				}
				
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_form_tab_general_store_language');
				
				foreach ($stores as $store) {					
					foreach ($languages as $language) {
						if (!isset($html_tab_general_store_language[$store['store_id']][$language['language_id']])) $html_tab_general_store_language[$store['store_id']][$language['language_id']] = '';
						
						if (isset($info[$store['store_id']][$language['language_id']])) {
							$html_tab_general_store_language[$store['store_id']][$language['language_id']] .= $info[$store['store_id']][$language['language_id']];
						}
					}
				}
				
				$html_tab_data .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_form_tab_data');
				$html_style .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_form_style');
				$html_script .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_form_script');
			}
		
			$html_dom = new d_simple_html_dom();
			$html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

			if ($html_tab_general) {
				$html_dom->find('#tab-general', 0)->innertext .= $html_tab_general;
			}
			
			if (reset($html_tab_general_language)) {
				foreach ($languages as $language) {
					$html_dom->find('#tab-general #language' . $language['language_id'], 0)->innertext .= $html_tab_general_language[$language['language_id']];
				}
			}
			
			$html_tab_general_language = reset($html_tab_general_store_language);
			
			if ((count($stores)) && (reset($html_tab_general_store) || reset($html_tab_general_language))) {
				$html_stores = '<ul class="nav nav-tabs" id="store">';
								
				foreach ($stores as $store) {
					$html_stores .= '<li' . (($store == reset($stores)) ? ' class="active"' : '') . '><a href="#store_' . $store['store_id'] . '" data-toggle="tab">' . $store['name'] . '</a></li>';
				}
				
				$html_stores .= '</ul>';
				$html_stores .= '<div class="tab-store tab-content">';
				
				foreach ($stores as $store) {
					$html_store_languages = '';
						
					if (reset($html_tab_general_store_language[$store['store_id']])) {
						$html_store_languages = '<ul class="nav nav-tabs" id="store_' . $store['store_id'] . '_language">';
				
						foreach ($languages as $language) {
							$html_store_languages .= '<li' . (($language == reset($languages)) ? ' class="active"' : '') . '><a href="#store_' . $store['store_id'] . '_language_' . $language['language_id'] . '" data-toggle="tab"><img src="' . $language['flag'] . '" title="' . $language['name'] . '" /> ' . $language['name'] . '</a></li>';
						}
				
						$html_store_languages .= '</ul>';
						$html_store_languages .= '<div class="tab-language tab-content">';
				
						foreach ($languages as $language) {
							$html_store_languages .= '<div class="tab-pane' . (($language == reset($languages)) ? ' active' : '') . '" id="store_' . $store['store_id'] . '_language_' . $language['language_id'] . '">' . $html_tab_general_store_language[$store['store_id']][$language['language_id']] . '</div>';
						}
						
						$html_store_languages .= '</div>';
					}
									
					$html_stores .= '<div class="tab-pane' . (($store == reset($stores)) ? ' active' : '') . '" id="store_' . $store['store_id'] . '">' . $html_tab_general_store[$store['store_id']] . $html_store_languages . '</div>';
				}
				
				$html_stores .= '</div>';
				
				$html_dom->find('#tab-general', 0)->innertext .= $html_stores;
				$html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
			}
			
			if ($html_tab_data) {
				$html_dom->find('#tab-data', 0)->innertext .= $html_tab_data;
			}
						
			if ($html_style) {
				$html_dom->find('#content', 0)->innertext .= $html_style;
			}
			
			if ($html_script) {
				$html_dom->find('#content', 0)->innertext .= $html_script;
			}

			$output = (string)$html_dom;
		}
	}
	
	public function category_validate_form($error) {
		$this->load->model($this->route);
				
		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_validate_form', $error);
			if ($info != '') $error = $info;
		}
		
		return $error;
	}

	public function category_add_category_after($route, $data, $output) {
		$this->load->model($this->route);

		$data = $data[0];
		$data['category_id'] = $output;

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_add_category', $data);
		}
	}

	public function category_edit_category_after($route, $data, $output) {
		$this->load->model($this->route);

		$category_id = $data[0];
		$data = $data[1];
		$data['category_id'] = $category_id;

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_edit_category', $data);
		}
	}
	
	public function category_delete_category_after($route, $data, $output) {
		$this->load->model($this->route);

		$data['category_id'] = $data[0];

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/category_delete_category', $data);
		}
	}

	public function post_form_after($route, $data, &$output) {
		$this->load->language($this->route);

		$this->load->model($this->route);

		if (file_exists(DIR_SYSTEM . 'library/d_simple_html_dom.php')) {
			$html_tab_general = '';
			$html_tab_general_language = array();
			$html_tab_general_store = array();
			$html_tab_general_store_language = array();
			$html_tab_data = '';
			$html_tab_links = '';
			$html_style = '';
			$html_script = '';

			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
			unset($stores[0]);

			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$html_tab_general .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_form_tab_general');
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_form_tab_general_language');
				
				foreach ($languages as $language) {
					if (!isset($html_tab_general_language[$language['language_id']])) $html_tab_general_language[$language['language_id']] = '';
					
					if (isset($info[$language['language_id']])) {
						$html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
					}
				}
				
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_form_tab_general_store');
				
				foreach ($stores as $store) {
					if (!isset($html_tab_general_store[$store['store_id']])) $html_tab_general_store[$store['store_id']] = '';
					
					if (isset($info[$store['store_id']])) {
						$html_tab_general_store[$store['store_id']] .= $info[$store['store_id']];
					}
				}
				
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_form_tab_general_store_language');
				
				foreach ($stores as $store) {					
					foreach ($languages as $language) {
						if (!isset($html_tab_general_store_language[$store['store_id']][$language['language_id']])) $html_tab_general_store_language[$store['store_id']][$language['language_id']] = '';
						
						if (isset($info[$store['store_id']][$language['language_id']])) {
							$html_tab_general_store_language[$store['store_id']][$language['language_id']] .= $info[$store['store_id']][$language['language_id']];
						}
					}
				}
				
				$html_tab_data .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_form_tab_data');
				$html_tab_links .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_form_tab_links');
				$html_style .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_form_style');
				$html_script .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_form_script');
			}
			
			$html_dom = new d_simple_html_dom();
			$html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

			if ($html_tab_general) {
				$html_dom->find('#tab-general', 0)->innertext .= $html_tab_general;
			}
			
			if (reset($html_tab_general_language)) {
				foreach ($languages as $language) {
					$html_dom->find('#tab-general #language' . $language['language_id'], 0)->innertext .= $html_tab_general_language[$language['language_id']];
				}
			}
			
			$html_tab_general_language = reset($html_tab_general_store_language);
			
			if ((count($stores)) && (reset($html_tab_general_store) || reset($html_tab_general_language))) {
				$html_stores = '<ul class="nav nav-tabs" id="store">';
								
				foreach ($stores as $store) {
					$html_stores .= '<li' . (($store == reset($stores)) ? ' class="active"' : '') . '><a href="#store_' . $store['store_id'] . '" data-toggle="tab">' . $store['name'] . '</a></li>';
				}
				
				$html_stores .= '</ul>';
				$html_stores .= '<div class="tab-store tab-content">';
				
				foreach ($stores as $store) {
					$html_store_languages = '';
						
					if (reset($html_tab_general_store_language[$store['store_id']])) {
						$html_store_languages = '<ul class="nav nav-tabs" id="store_' . $store['store_id'] . '_language">';
				
						foreach ($languages as $language) {
							$html_store_languages .= '<li' . (($language == reset($languages)) ? ' class="active"' : '') . '><a href="#store_' . $store['store_id'] . '_language_' . $language['language_id'] . '" data-toggle="tab"><img src="' . $language['flag'] . '" title="' . $language['name'] . '" /> ' . $language['name'] . '</a></li>';
						}
				
						$html_store_languages .= '</ul>';
						$html_store_languages .= '<div class="tab-language tab-content">';
				
						foreach ($languages as $language) {
							$html_store_languages .= '<div class="tab-pane' . (($language == reset($languages)) ? ' active' : '') . '" id="store_' . $store['store_id'] . '_language_' . $language['language_id'] . '">' . $html_tab_general_store_language[$store['store_id']][$language['language_id']] . '</div>';
						}
						
						$html_store_languages .= '</div>';
					}
									
					$html_stores .= '<div class="tab-pane' . (($store == reset($stores)) ? ' active' : '') . '" id="store_' . $store['store_id'] . '">' . $html_tab_general_store[$store['store_id']] . $html_store_languages . '</div>';
				}
				
				$html_stores .= '</div>';
				
				$html_dom->find('#tab-general', 0)->innertext .= $html_stores;
				$html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
			}
			
			if ($html_tab_data) {
				$html_dom->find('#tab-data', 0)->innertext .= $html_tab_data;
			}
			
			if ($html_tab_links) {
				$html_dom->find('#tab-links', 0)->innertext .= $html_tab_links;
			}
						
			if ($html_style) {
				$html_dom->find('#content', 0)->innertext .= $html_style;
			}
			
			if ($html_script) {
				$html_dom->find('#content', 0)->innertext .= $html_script;
			}

			$output = (string)$html_dom;
		}
	}

	public function post_validate_form($error) {
		$this->load->model($this->route);
				
		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_validate_form', $error);
			if ($info != '') $error = $info;
		}
		
		return $error;
	}

	public function post_add_post_after($route, $data, $output) {
		$this->load->model($this->route);

		$data = $data[0];
		$data['post_id'] = $output;

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_add_post', $data);
		}
	}

	public function post_edit_post_after($route, $data, $output) {
		$this->load->model($this->route);

		$post_id = $data[0];
		$data = $data[1];
		$data['post_id'] = $post_id;

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_edit_post', $data);
		}
	}
	
	public function post_delete_post_after($route, $data, $output) {
		$this->load->model($this->route);

		$data['post_id'] = $data[0];

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/post_delete_post', $data);
		}
	}
	
	public function author_form_after($route, $data, &$output) {
		$this->load->language($this->route);

		$this->load->model($this->route);

		if (file_exists(DIR_SYSTEM . 'library/d_simple_html_dom.php')) {
			$html_tab_general = '';
			$html_tab_general_language = array();
			$html_tab_general_store = array();
			$html_tab_general_store_language = array();
			$html_tab_data = '';
			$html_tab_links = '';
			$html_style = '';
			$html_script = '';

			$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();
			$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
			$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
			unset($stores[0]);

			foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
				$html_tab_general .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_form_tab_general');
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_form_tab_general_language');
				
				foreach ($languages as $language) {
					if (!isset($html_tab_general_language[$language['language_id']])) $html_tab_general_language[$language['language_id']] = '';
					
					if (isset($info[$language['language_id']])) {
						$html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
					}
				}
				
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_form_tab_general_store');
				
				foreach ($stores as $store) {
					if (!isset($html_tab_general_store[$store['store_id']])) $html_tab_general_store[$store['store_id']] = '';
					
					if (isset($info[$store['store_id']])) {
						$html_tab_general_store[$store['store_id']] .= $info[$store['store_id']];
					}
				}
				
				$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_form_tab_general_store_language');
				
				foreach ($stores as $store) {					
					foreach ($languages as $language) {
						if (!isset($html_tab_general_store_language[$store['store_id']][$language['language_id']])) $html_tab_general_store_language[$store['store_id']][$language['language_id']] = '';
						
						if (isset($info[$store['store_id']][$language['language_id']])) {
							$html_tab_general_store_language[$store['store_id']][$language['language_id']] .= $info[$store['store_id']][$language['language_id']];
						}
					}
				}
				
				$html_tab_data .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_form_tab_data');
				$html_style .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_form_style');
				$html_script .= $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_form_script');
			}
			
			$html_dom = new d_simple_html_dom();
			$html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

			if ($html_tab_general) {
				$html_dom->find('#tab-general', 0)->innertext .= $html_tab_general;
			}
			
			if (reset($html_tab_general_language)) {
				foreach ($languages as $language) {
					$html_dom->find('#tab-general #language' . $language['language_id'], 0)->innertext .= $html_tab_general_language[$language['language_id']];
				}
			}
			
			$html_tab_general_language = reset($html_tab_general_store_language);
			
			if ((count($stores)) && (reset($html_tab_general_store) || reset($html_tab_general_language))) {
				$html_stores = '<ul class="nav nav-tabs" id="store">';
								
				foreach ($stores as $store) {
					$html_stores .= '<li' . (($store == reset($stores)) ? ' class="active"' : '') . '><a href="#store_' . $store['store_id'] . '" data-toggle="tab">' . $store['name'] . '</a></li>';
				}
				
				$html_stores .= '</ul>';
				$html_stores .= '<div class="tab-store tab-content">';
				
				foreach ($stores as $store) {
					$html_store_languages = '';
						
					if (reset($html_tab_general_store_language[$store['store_id']])) {
						$html_store_languages = '<ul class="nav nav-tabs" id="store_' . $store['store_id'] . '_language">';
				
						foreach ($languages as $language) {
							$html_store_languages .= '<li' . (($language == reset($languages)) ? ' class="active"' : '') . '><a href="#store_' . $store['store_id'] . '_language_' . $language['language_id'] . '" data-toggle="tab"><img src="' . $language['flag'] . '" title="' . $language['name'] . '" /> ' . $language['name'] . '</a></li>';
						}
				
						$html_store_languages .= '</ul>';
						$html_store_languages .= '<div class="tab-language tab-content">';
				
						foreach ($languages as $language) {
							$html_store_languages .= '<div class="tab-pane' . (($language == reset($languages)) ? ' active' : '') . '" id="store_' . $store['store_id'] . '_language_' . $language['language_id'] . '">' . $html_tab_general_store_language[$store['store_id']][$language['language_id']] . '</div>';
						}
						
						$html_store_languages .= '</div>';
					}
									
					$html_stores .= '<div class="tab-pane' . (($store == reset($stores)) ? ' active' : '') . '" id="store_' . $store['store_id'] . '">' . $html_tab_general_store[$store['store_id']] . $html_store_languages . '</div>';
				}
				
				$html_stores .= '</div>';
				
				$html_dom->find('#tab-general', 0)->innertext .= $html_stores;
				$html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
			}
			
			if ($html_tab_data) {
				$html_dom->find('#tab-data', 0)->innertext .= $html_tab_data;
			}
			
			if ($html_tab_links) {
				$html_dom->find('#tab-links', 0)->innertext .= $html_tab_links;
			}
						
			if ($html_style) {
				$html_dom->find('#content', 0)->innertext .= $html_style;
			}
			
			if ($html_script) {
				$html_dom->find('#content', 0)->innertext .= $html_script;
			}

			$output = (string)$html_dom;
		}
	}

	public function author_validate_form($error) {
		$this->load->model($this->route);
				
		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$info = $this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_validate_form', $error);
			if ($info != '') $error = $info;
		}
		
		return $error;
	}

	public function author_add_author_after($route, $data, $output) {
		$this->load->model($this->route);

		$data = $data[0];
		$data['author_id'] = $output;

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_add_author', $data);
		}
	}

	public function author_edit_author_after($route, $data, $output) {
		$this->load->model($this->route);

		$author_id = $data[0];
		$data = $data[1];
		$data['author_id'] = $author_id;

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_edit_author', $data);
		}
	}
	
	public function author_delete_author_after($route, $data, $output) {
		$this->load->model($this->route);

		$data['author_id'] = $data[0];

		$installed_seo_blog_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOBlogExtensions();

		foreach ($installed_seo_blog_extensions as $installed_seo_blog_extension) {
			$this->load->controller('extension/' . $this->codename . '/' . $installed_seo_blog_extension . '/author_delete_author', $data);
		}
	}
	
	/*
	*	Refresh URL Cache.
	*/
	public function refreshURLCache($data = array()) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		$stores = $this->{'model_extension_module_' . $this->codename}->getStores();
		
		// Setting 		
		$this->config->load($this->config_file);
				
		foreach ($stores as $store) {
			$data['setting'][$store['store_id']] = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();
			
			$setting = $this->model_setting_setting->getSetting('module_' . $this->codename, $store['store_id']);
			$setting = isset($setting['module_' . $this->codename . '_setting']) ? $setting['module_' . $this->codename . '_setting'] : array();
		
			if (!empty($setting)) {
				$data['setting'][$store['store_id']] = array_replace_recursive($data['setting'][$store['store_id']], $setting);
			}
		}
		
		// Register Cache
		if (!$this->registry->has('d_cache') && file_exists(DIR_SYSTEM . 'library/d_cache.php')) {
			$this->registry->set('d_cache', new d_cache());
		}
		
		$this->{'model_extension_module_' . $this->codename}->refreshURLCache($data);
	}
	
	/*
	*	Validator Functions.
	*/		
	private function validate($permission = 'modify') {
		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			
			return false;
		}

		return true;
	}

	private function validateInstall($permission = 'modify') {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		$installed_seo_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOExtensions();
		
		if (in_array($this->codename, $installed_seo_extensions)) {
			$this->error['warning'] = $this->language->get('error_installed');
			
			return false;
		}
						
		if (file_exists(DIR_APPLICATION . 'model/extension/d_opencart_patch/extension.php') && file_exists(DIR_APPLICATION . 'model/extension/d_opencart_patch/user.php') && file_exists(DIR_APPLICATION . 'controller/extension/module/d_blog_module.php')) {
			$this->load->model('extension/d_opencart_patch/extension');			
			$this->load->model('extension/d_opencart_patch/user');	
				
			$user_group_id = $this->model_extension_d_opencart_patch_user->getGroupId();
				
			// Install Blog Module
			if (!$this->model_extension_d_opencart_patch_extension->isInstalled('d_blog_module')) {
				$this->model_extension_d_opencart_patch_extension->install('module', 'd_blog_module');
				
				$this->model_user_user_group->addPermission($user_group_id, 'access', 'extension/module/d_blog_module');
				$this->model_user_user_group->addPermission($user_group_id, 'modify', 'extension/module/d_blog_module');
					
				$this->load->controller('extension/module/d_blog_module/install');
			}
		} else {
			$this->error['warning'] = $this->language->get('error_dependence_d_blog_module');
				
			return false;
		}

		if (!in_array('d_seo_module', $installed_seo_extensions)) {
			$info = $this->load->controller('extension/d_seo_module/d_seo_module/control_install_extension');
			$this->load->language($this->route);
			
			if ($info) {
				$data = $info;
						
				if ($data['error']) {
					$this->error = $data['error'];
				
					return false;
				} else {
					$installed_seo_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOExtensions();
				} 
			} else {
				$this->error['warning'] = $this->language->get('error_dependence_d_seo_module');
				
				return false;
			}
		}
		
		$installed_seo_extensions[] = $this->codename;
		
		$this->{'model_extension_module_' . $this->codename}->saveSEOExtensions($installed_seo_extensions);
										
		return true;
	}

	private function validateUninstall($permission = 'modify') {
		$this->load->model($this->route);

		$installed_seo_extensions = $this->{'model_extension_module_' . $this->codename}->getInstalledSEOExtensions();
		
		$key = array_search($this->codename, $installed_seo_extensions);
		if ($key !== false) unset($installed_seo_extensions[$key]);
		
		$this->{'model_extension_module_' . $this->codename}->saveSEOExtensions($installed_seo_extensions);

		return true;
	}
}
