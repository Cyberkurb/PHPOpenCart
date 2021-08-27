<?php

/**
* location: admin/controller
*/
class ControllerExtensionModuleDBlogModuleFeaturedPosts extends Controller
{
    private $codename = 'd_blog_module_featured_posts';
    private $route = 'extension/module/d_blog_module_featured_posts';
    private $config_file = '';
    private $store_id = 0;
    private $error = array(); 

    public function __construct($registry) {
        parent::__construct($registry);

        $this->load->model('extension/d_opencart_patch/url');
        $this->load->model('extension/d_opencart_patch/user');
        $this->load->model('extension/d_opencart_patch/load');

        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/d_shopunity/extension/d_shopunity.json'));
        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/d_shopunity/extension/d_blog_module_pack.json'), true);
        
        if (isset($this->request->get['store_id'])) { 
            $this->store_id = $this->request->get['store_id']; 
        }
    }

    public function index() {
        
        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->validateDependencies('d_blog_module_pack');
        }

        if($this->d_twig_manager){
            $this->load->model('extension/module/d_twig_manager');
            if(!$this->model_extension_module_d_twig_manager->isCompatible()){
                $this->model_extension_module_d_twig_manager->installCompatibility();
                $this->load->language('extension/module/d_blog_module');
                $this->session->data['success'] = $this->language->get('success_twig_compatible');
                $this->load->model('extension/d_opencart_patch/url');
                $this->response->redirect($this->model_extension_d_opencart_patch_url->getExtensionLink('module'));
            } 
        }

        $this->load->language($this->route);
        $this->load->model($this->route);
        $this->load->model('extension/d_shopunity/setting');
        $this->load->model('setting/setting');
        $this->load->model('extension/d_blog_module/category');
        $this->load->model('extension/d_blog_module/post');
        $this->load->model('extension/d_opencart_patch/module');

        if (isset($this->request->get['module_id'])) {
            $module_id = $this->request->get['module_id'];
        } else {
            $module_id = 0;
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!$module_id) {
                $this->model_extension_d_opencart_patch_module->addModule($this->codename, $this->request->post[$this->codename]);
            } else {
                $this->model_extension_d_opencart_patch_module->editModule($module_id, $this->request->post[$this->codename]);
            }
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->model_extension_d_opencart_patch_url->getExtensionLink('module'));
        }

        $this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
        $this->document->addScript('view/javascript/d_bootstrap_switch/js/bootstrap-switch.min.js');
        $this->document->addStyle('view/javascript/d_bootstrap_switch/css/bootstrap-switch.css');

        $url_params = array();
        $url = '';

        if(isset($this->response->get['store_id'])){
            $url_params['store_id'] = $this->store_id;
        }
        if(isset($this->request->get['module_id'])){
            $url_params['module_id'] = $module_id;
        }
        if(isset($this->response->get['config'])){
            $url_params['config'] = $this->response->get['config'];
        }

        $url = ((!empty($url_params)) ? '&' : '' ) . http_build_query($url_params);

        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_posts'] = $this->language->get('entry_posts');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['help_category'] = $this->language->get('help_category');

        // Button
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_cancel'] = $this->language->get('button_cancel');

        // Variable
        $data['codename'] = $this->codename;
        $data['route'] = $this->route;
        $data['store_id'] = $this->store_id;
        $data['stores'] = $this->model_extension_d_shopunity_setting->getStores();
        $data['config'] = $this->config_file;
        $data['support_url'] = $this->extension['support']['url'];
        $data['version'] = $this->extension['version'];
        $data['token'] =  $this->model_extension_d_opencart_patch_user->getToken();
        $data['module_id'] = $module_id;

        $data['posts_autocomplete'] = $this->model_extension_d_opencart_patch_url->ajax('extension/d_blog_module/post/autocomplete');
        $data['category_autocomplete'] = $this->model_extension_d_opencart_patch_url->ajax('extension/d_blog_module/category/autocomplete');

        //support
        $data['tab_support'] = $this->language->get('tab_support');
        $data['text_support'] = $this->language->get('text_support');
        $data['entry_support'] = $this->language->get('entry_support');
        $data['button_support'] = $this->language->get('button_support');

        $data['entry_name'] = $this->language->get('entry_name');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }


        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        // Breadcrumbs
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->model_extension_d_opencart_patch_url->link('common/dashboard')
            );
    
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->model_extension_d_opencart_patch_url->getExtensionLink('module')
            );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->model_extension_d_opencart_patch_url->link($this->route, $url)
            );

        //action
        $data['module_link'] = $this->model_extension_d_opencart_patch_url->link($this->route);
        $data['action'] = $this->model_extension_d_opencart_patch_url->link($this->route, $url);
        $data['cancel'] = $this->model_extension_d_opencart_patch_url->getExtensionLink('module');

        $setting = $this->model_extension_d_opencart_patch_module->getModule($module_id);
        
        if (isset($this->request->post[$this->codename.'_status'])) {
            $data['status'] = $this->request->post[$this->codename]['status'];
        } elseif(isset($setting['status'])) {
            $data['status'] = $setting['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post[$this->codename]['name'])) {
            $data['name'] = $this->request->post[$this->codename]['name'];
        } elseif (isset($setting['name'])) {
            $data['name'] = $setting['name'];
        } else{
            $data['name'] = '';
        }

        if (isset($this->request->post[$this->codename]['posts'])) {
            $data['posts'] = $this->request->post[$this->codename]['posts'];
        } elseif (isset($setting['posts'])) {
            $data['posts'] = $setting['posts'];
        } else{
            $data['posts'] = array();
        }
        foreach ($data['posts'] as $key => $post_id) {
            $post_info = $this->model_extension_d_blog_module_post->getPost($post_id);
            $data['posts'][$key] = array(
                'title' => $post_info['title'],
                'post_id' => $post_id
            );
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->model_extension_d_opencart_patch_load->view($this->route, $data));
    }
    
    private function validate($permission = 'modify') {

        if (isset($this->request->post['config'])) {
            return false;
        }

        $this->language->load($this->route);
        
        if (!$this->user->hasPermission($permission, $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        if ((utf8_strlen($this->request->post[$this->codename]['name']) < 3) || (utf8_strlen($this->request->post[$this->codename]['name']) > 64)) {
            $this->error['warning'] = $this->language->get('error_warning');
            $this->error['name'] = $this->language->get('error_name');
            return false;
        }

        return true;
    }

    public function install() {
        if($this->d_shopunity){
            $this->load->model('extension/d_shopunity/mbooth');
            $this->model_extension_d_shopunity_mbooth->installDependencies('d_blog_module_pack');
        }
    }
    public function uninstall(){
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting($this->codename);
        $this->model_setting_setting->deleteSetting('module_'.$this->codename);
    }
}