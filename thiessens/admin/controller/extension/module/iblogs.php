<?php
class ControllerExtensionModuleiBlogs extends Controller
{
    protected $data   = array();
    protected $module = array();
    protected $error  = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        // Module configuration
        $this->config->load('isenselabs/iblogs');
        $this->module = $this->config->get('iblogs');

        $this->load->model('setting/setting');
        $this->load->model($this->module['path']);

        if (isset($this->request->post['store_id'])) {
            $this->module['store_id'] = (int)$this->request->post['store_id'];
        } elseif (isset($this->request->get['store_id'])) {
            $this->module['store_id'] = (int)$this->request->get['store_id'];
        }

        $this->module['model']         = $this->{$this->module['model']};
        $this->module['url_token']     = sprintf($this->module['url_token'], $this->session->data['user_token']);
        $this->module['url_extension'] = $this->url->link($this->module['ext_link'], $this->module['url_token'] . $this->module['ext_type'], true);
        $this->module['url_module']    = $this->url->link($this->module['path'], 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'], true);
        $this->module['url_module_x']  = $this->url->link($this->module['path'] . '/{x}', 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'], true);

        // Module db setting
        $setting = $this->model_setting_setting->getSetting($this->module['code'], $this->module['store_id']);
        $this->module['setting'] = array_replace_recursive(
            $this->module['setting'],
            !empty($setting[$this->module['code'] . '_setting']) ? $setting[$this->module['code'] . '_setting'] : array()
        );

        // Language variables
        $language_vars = $this->load->language($this->module['path'], $this->module['name']);
        $this->data = $language_vars[$this->module['name']]->all();
    }

    // Standarize pages
    protected function initPage($data)
    {
        $this->load->model('setting/store');
        $this->load->model('localisation/language');
        $this->load->model('tool/image');

        $this->document->setTitle($this->module['title'] . ' ' . $this->module['version']);

        $this->document->addStyle('view/stylesheet/iblogs/summernote/summernote.css');
        $this->document->addStyle('view/stylesheet/iblogs/style.css?v=' .  $this->module['version']);
        $this->document->addScript('view/stylesheet/iblogs/summernote/summernote.min.js');
        $this->document->addScript('view/stylesheet/iblogs/script.js?v=' .  $this->module['version']);

        // ===

        $data['heading_title']     = $this->document->getTitle();

        $data['setting']           = $this->module['setting'];
        $data['store_id']          = $this->module['store_id'];
        $data['language_id']       = $this->config->get('config_language_id');

        $data['url_token']         = $this->module['url_token'];
        $data['url_extension']     = $this->module['url_extension'];
        $data['url_module']        = $this->module['url_module'];
        $data['url_post_form']     = str_replace('{x}', 'post', $this->module['url_module_x']);
        $data['url_category_form'] = str_replace('{x}', 'category', $this->module['url_module_x']);

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', $this->module['url_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text'  => $data['text_modules'],
            'href'  => $this->module['url_extension']
        );
        $data['breadcrumbs'][] = array(
            'text'  => $this->module['title'],
            'href'  => $this->module['url_module']
        );

        $data['error_warning'] = '';
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        $data['success'] = '';
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        // ===

        $store_default = array(
            'store_id' => '0',
            'name'     => $this->config->get('config_name') . ' <b>' .$this->language->get('text_default') . '</b>',
            'url'      => HTTPS_CATALOG
        );
        $data['store']  = $this->module['store_id'] != 0 ? $this->model_setting_store->getStore($this->module['store_id']) : $store_default;
        $data['stores'] = array_merge(
            array($store_default),
            $this->model_setting_store->getStores()
        );

        $data['languages'] = $this->model_localisation_language->getLanguages();
        foreach ($data['languages'] as $key => $value) {
            $data['languages'][$key]['flag_url'] = 'language/'.$data['languages'][$key]['code'].'/'.$data['languages'][$key]['code'].'.png';
        }

        $data['no_image']  = $this->model_tool_image->resize('no_image.png', 100, 100);
        $data['islConfig'] = array_replace_recursive(
            $this->module,
            array(
                'store_id'      => $data['store_id'],
                'language_id'   => $data['language_id'],
                'url_token'     => $data['url_token'],
                'url_extension' => $data['url_extension'],
                'url_module'    => $data['url_module'],
            )
        );

        return $data;
    }

    public function index()
    {
        $this->setup();

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validatePermission()) {
            $module_status = $this->request->post[$this->module['code'] . '_setting']['status'];

            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->module['code'] . '_setting']['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }
            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->module['code'] . '_setting']['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }

            $form = array_replace_recursive(
                $this->request->post,
                array($this->module['code'] . '_status' => $module_status)
            );

            $this->model_setting_setting->editSetting($this->module['code'], $form, $this->module['store_id']);

            $this->session->data['success'] = $this->data['text_success'];
            $this->response->redirect($this->module['url_module']);
        }

        // ===

        $data = $this->initPage($this->data);

        $data['module_setting']     = $this->module['code'] . '_setting';

        // Dashboard;
        $data['total_post']         = $this->module['model']->getTotal('post');
        $data['total_category']     = $this->module['model']->getTotal('category');
        $data['total_tags']         = $this->module['model']->getTags();

        $data['url_post_edit']      = $this->url->link($this->module['path'] . '/post', 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'], true);
        $data['post_recent_update'] = $this->module['model']->getPosts(array(
            'order' => 'ORDER BY updated DESC, title ASC',
            'limit' => 10
        ));
        $data['post_popular']       = $this->module['model']->getPosts(array(
            'popular' => 1,
            'limit' => 10
        ));

        // Support
        $data['unlincensedHtml']    = empty($this->module['setting']['LicensedOn']) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4NCiAgICAgICAgPGJ1dHRvbiB0eXBlPSJidXR0b24iIGNsYXNzPSJjbG9zZSIgZGF0YS1kaXNtaXNzPSJhbGVydCIgYXJpYS1oaWRkZW49InRydWUiPsOXPC9idXR0b24+DQogICAgICAgIDxoND5XYXJuaW5nISBVbmxpY2Vuc2VkIHZlcnNpb24gb2YgdGhlIG1vZHVsZSE8L2g0Pg0KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2Pg0KICAgICAgICA8YSBjbGFzcz0iYnRuIGJ0bi1kYW5nZXIiIGhyZWY9ImphdmFzY3JpcHQ6dm9pZCgwKSIgb25jbGljaz0iJCgnYVtocmVmPSNpc2Vuc2Vfc3VwcG9ydF0nKS50cmlnZ2VyKCdjbGljaycpIj5FbnRlciB5b3VyIGxpY2Vuc2UgY29kZTwvYT4NCiAgICA8L2Rpdj4=') : '';
        $data['licenseDataBase64']  = !empty($this->module['setting']['License']) ? base64_encode(json_encode($this->module['setting']['License'])) : '';
        $data['supportTicketLink']  = 'http://isenselabs.com/tickets/open/' . base64_encode('Support Request').'/'.base64_encode('414').'/'. base64_encode($_SERVER['SERVER_NAME']);
        $data['islConfig']['support']  = !empty($this->request->get['support']) ? $this->request->get['support'] : '';

        // Content
        $data['tab_dashboard']      = $this->load->view($this->module['path'] .'/tab_dashboard', $data);
        $data['tab_post']           = $this->load->view($this->module['path'] .'/tab_post', $data);
        $data['tab_category']       = $this->load->view($this->module['path'] .'/tab_category', $data);
        $data['tab_setting']        = $this->load->view($this->module['path'] .'/tab_setting', $data);
        $data['tab_support']        = $this->load->view($this->module['path'] .'/tab_support', $data);

        $data['is_migrate'] = false;
        if ($this->module['model']->checkTable('iblog_post')) {
            $data['is_migrate']     = true;
            $data['url_migrate']    = str_replace('{x}', 'migrate', $this->module['url_module_x']);
            $data['tab_migrate']    = $this->load->view($this->module['path'] .'/tab_migrate', $data);
        }

        // Page element
        $data['header']             = $this->load->controller('common/header');
        $data['column_left']        = $this->load->controller('common/column_left');
        $data['footer']             = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->module['path'], $data));
    }

    public function listTable()
    {
        $channel    = $this->request->get['channel'];
        $total_item = 0;
        $limit      = 25;
        $page       = isset($this->request->get['page']) && (int)$this->request->get['page'] > 0 ? (int)$this->request->get['page'] : 1;
        $search     = !empty($this->request->get['search']) ? $this->request->get['search'] : '';
        $data       = array(
            'items'     => array(),
            'output'    => ''
        );

        $data['url_item_edit']   = $this->url->link($this->module['path'] . '/' . $channel, 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'], true);
        $data['url_item_update'] = $this->url->link($this->module['path'] . '/listUpdate', 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'], true);

        $params = array(
            'query'     => $search ? " AND `title` LIKE '%" . $this->db->escape($search) . "%'" : '',
            'limit'     => $limit,
            'start'     => ($page - 1) * $limit,
        );

        if ($channel == 'post') {
            if ($search) {
                $params['query'] = $params['query'] . " OR `excerpt` LIKE '%" . $this->db->escape($search) . "%'";
            }
            $data['items']      = $this->module['model']->getPosts($params);
        }
        if ($channel == 'category') {
            $data['items']      = $this->module['model']->getCategories($params);
        }
        $total_item = $this->module['model']->getTotal($channel, $params);

        $pagination         = new Pagination();
        $pagination->total  = $total_item;
        $pagination->page   = $page;
        $pagination->limit  = $limit;
        $pagination->url    = $this->url->link($this->module['path'] . '/listTable', 'channel=' . $channel . '&store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'] . '&page={page}', true);

        $data['output']     = $this->load->view($this->module['path'] . '/list_' . $channel, $data);
        $data['pagination'] = $pagination->render();
        $data['pagination_info'] = sprintf($this->language->get('text_pagination'), ($total_item) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_item - $limit)) ? $total_item : ((($page - 1) * $limit) + $limit), $total_item, ceil($total_item / $limit));

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function listUpdate()
    {
        $channel = $this->request->get['channel'];
        $post    = $this->request->post;
        $data    = array();

        // Sort order
        if (!empty($post['sort_order'])) {
            foreach ($post['sort_order'] as $key => $value) {
                $this->db->query("UPDATE `" . DB_PREFIX . "iblogs_" . $channel . "` SET `sort_order` = '" . (int)$value . "' WHERE `" . $channel . "_id` = " . (int)$key);
            }
        }

        // Update status
        if (!empty($post['action']) && in_array($post['action'], array('status'))) {
            $this->db->query("UPDATE `" . DB_PREFIX . "iblogs_" . $channel . "` SET `" . $post['action'] . "` = '" . (int)$post['value'] . "' WHERE `" . $channel . "_id` = " . (int)$post['id']);
        }

        // Delete
        if (!empty($post['action']) && $post['action'] == 'delete') {
            $this->module['model']->deleteData($channel, $post['id']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    // ================================================================

    // Add/edit post form
    public function post()
    {
        $post_id          = isset($this->request->get['post_id']) ? (int)$this->request->get['post_id'] : 0;
        $input_identifier = $this->module['code'] . '_post';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validatePermission() && $this->validatePost()) {
            $this->module['model']->formPost($post_id, $this->request->post[$input_identifier]);

            $this->session->data['success'] = $this->data['text_success'];
            $this->response->redirect($this->module['url_module']);
        }

        // ===

        $data = $this->initPage($this->data);

        $data['url_form']           = str_replace('{x}', 'post', $this->module['url_module_x']) . '&post_id=' . $post_id;
        $data['sub_title']          = $post_id ? $data['text_edit_post'] . ' #' . $post_id : $data['text_add_post'];
        $data['module_setting']     = $input_identifier;

        $this->document->setTitle($data['sub_title'] . ' - ' . $this->document->getTitle());

        $data['breadcrumbs'][]      = array(
            'text'  => $data['sub_title'],
            'href'  => $data['url_form']
        );

        $data['users'] = $this->db->query("SELECT user_id AS author_id, CONCAT(`firstname`, ' ', `lastname`) AS `name` FROM " . DB_PREFIX . "user")->rows;
        $data['categories'] = $this->module['model']->getCategories(array('query' => ' AND status = 1'));

        // ===

        if (isset($this->request->post[$input_identifier])) {
            $data['setting']['post'] = array_replace_recursive($data['setting']['post'], $this->request->post[$input_identifier]);
        } elseif ($post_id) {
            $data['setting']['post'] = array_replace_recursive($data['setting']['post'], $this->module['model']->getPost($post_id));
        }

        $data['setting']['post']['post_id']   = $post_id;
        $data['setting']['post']['author_id'] = $this->user->getId();
        $data['setting']['post']['image_thumb'] = $data['no_image'];

        if ($data['setting']['post']['image'] && is_file(DIR_IMAGE . $data['setting']['post']['image'])) {
            $data['setting']['post']['image_thumb'] = $this->model_tool_image->resize($data['setting']['post']['image'], 100, 100);
        }

        $data['related_post_items']    = $this->module['model']->getPostInId($data['setting']['post']['meta']['related_post_items']);
        $data['related_product_items'] = $this->module['model']->getProductsInId($data['setting']['post']['meta']['related_product_items']);

        // Page element
        $data['header']             = $this->load->controller('common/header');
        $data['column_left']        = $this->load->controller('common/column_left');
        $data['footer']             = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->module['path'] . '/form_post', $data));
    }

    // Add/edit category form
    public function category()
    {
        $category_id      = isset($this->request->get['category_id']) ? (int)$this->request->get['category_id'] : 0;
        $input_identifier = $this->module['code'] . '_category';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validatePermission() && $this->validateCategory()) {
            $this->module['model']->formCategory($category_id, $this->request->post[$input_identifier]);

            $this->session->data['success'] = $this->data['text_success'];
            $this->response->redirect($this->module['url_module']);
        }

        // ===

        $data = $this->initPage($this->data);

        $data['url_form']           = str_replace('{x}', 'category', $this->module['url_module_x']) . '&category_id=' . $category_id;
        $data['sub_title']          = $category_id ? $data['text_edit_category'] . ' #' . $category_id : $data['text_add_category'];
        $data['module_setting']     = $input_identifier;

        $this->document->setTitle($data['sub_title'] . ' - ' . $this->document->getTitle());

        $data['breadcrumbs'][]      = array(
            'text'  => $data['sub_title'],
            'href'  => $data['url_form']
        );

        $data['categories'] = $this->module['model']->getCategories(array('query' => ' AND status = 1'));

        // ===

        if (isset($this->request->post[$input_identifier])) {
            $data['setting']['category'] = array_replace_recursive($data['setting']['category'], $this->request->post[$input_identifier]);
        } elseif ($category_id) {
            $data['setting']['category'] = array_replace_recursive($data['setting']['category'], $this->module['model']->getCategory($category_id));
        }

        $data['setting']['post']['category_id']     = $category_id;
        $data['setting']['category']['image_thumb'] = $data['no_image'];

        if ($data['setting']['category']['image'] && is_file(DIR_IMAGE . $data['setting']['category']['image'])) {
            $data['setting']['category']['image_thumb'] = $this->model_tool_image->resize($data['setting']['category']['image'], 100, 100);
        }

        // Page element
        $data['header']             = $this->load->controller('common/header');
        $data['column_left']        = $this->load->controller('common/column_left');
        $data['footer']             = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->module['path'] . '/form_category', $data));
    }

    public function autocomplete()
    {
        $data   = array();
        $type   = isset($this->request->get['filter_type']) ? $this->request->get['filter_type'] : '';
        $search = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : '';
        $limit  = isset($this->request->get['filter_limit']) ? $this->request->get['filter_limit'] : 10;

        if ($type == 'post') {
            $data = $this->module['model']->autocompletePost(array(
                'search' => $search,
                'limit'  => $limit
            ));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    protected function validatePermission()
    {
        if (!$this->user->hasPermission('modify', $this->module['path'])) {
            $this->error['warning'] = $this->data['error_permission'];
        }

        return !$this->error;
    }

    protected function validatePost()
    {
        $post = $this->request->post[$this->module['code'] . '_post'];

        foreach ($post['title'] as $title) {
            if ((utf8_strlen($title) < 3) || (utf8_strlen($title) > 225)) {
                $this->error['item']['title'] = $this->data['error_title'];
            }
        }

        foreach ($post['excerpt'] as $excerpt) {
            if ((utf8_strlen($excerpt) < 3)) {
                $this->error['item']['excerpt'] = $this->data['error_excerpt'];
            }
        }

        foreach ($post['content'] as $content) {
            if ((utf8_strlen($content) < 3)) {
                $this->error['item']['content'] = $this->data['error_content'];
            }
        }

        if (!empty($this->error['item'])) {
            $message = $this->data['error_form'];
            $message .= '<ul>';
            foreach ($this->error['item'] as $error) {
                $message .= '<li>' . $error . '</li>';
            }
            $message .= '</ul>';

            $this->error['warning'] = $message;
        }

        return !$this->error;
    }

    protected function validateCategory()
    {
        $post = $this->request->post[$this->module['code'] . '_category'];

        foreach ($post['title'] as $title) {
            if ((utf8_strlen($title) < 3) || (utf8_strlen($title) > 225)) {
                $this->error['item']['title'] = $this->data['error_title'];
            }
        }

        if (!empty($this->error['item'])) {
            $message = $this->data['error_form'];
            $message .= '<ul>';
            foreach ($this->error['item'] as $error) {
                $message .= '<li>' . $error . '</li>';
            }
            $message .= '</ul>';

            $this->error['warning'] = $message;
        }

        return !$this->error;
    }

    // ================================================================

    /**
     * Future update
     */
    public function setup()
    {
        // $this->module['model']->setup();
    }

    public function install()
    {
        $this->module['model']->install(true);
    }

    public function uninstall()
    {
        $this->module['model']->uninstall();
    }

    public function migrate()
    {
        $data = $this->module['model']->migrate();

        $this->session->data['success'] = str_replace(array_keys($data), array_values($data), $this->data['text_success_migrate']);
        $this->response->redirect($this->module['url_module']);
    }
}
