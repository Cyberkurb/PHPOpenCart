<?php
class ControllerExtensionModuleiBlogsWidget extends Controller
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
        $this->module['url_module']    = $this->url->link($this->module['path'] . '_widget', 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'], true);
        $this->module['url_module_x']  = $this->url->link($this->module['path'] . '_widget' . '/{x}', 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'], true);

        // Module db setting
        $setting = $this->model_setting_setting->getSetting($this->module['code'], $this->module['store_id']);
        $this->module['setting'] = array_replace_recursive(
            $this->module['setting'],
            !empty($setting[$this->module['code'] . '_setting']) ? $setting[$this->module['code'] . '_setting'] : array()
        );

        // Language variables
        $language_vars = $this->load->language($this->module['path'] . '_widget', $this->module['name']);
        $this->data = $language_vars[$this->module['name']]->all();

    }

    // Standarize pages
    protected function initPage($data)
    {
        $this->load->model('setting/store');
        $this->load->model('localisation/language');
        $this->load->model('tool/image');

        $this->document->setTitle($this->module['title'] . ' ' . $this->module['version'] . ' Widget');

        $this->document->addStyle('view/asset/iblogs/summernote/summernote.css');
        $this->document->addStyle('view/asset/iblogs/style.css?v=' .  $this->module['version']);
        $this->document->addScript('view/asset/iblogs/summernote/summernote.min.js');
        $this->document->addScript('view/asset/iblogs/script.js?v=' .  $this->module['version']);

        // ===

        $data['heading_title']     = $this->document->getTitle();

        $data['setting']           = $this->module['setting'];
        $data['store_id']          = $this->module['store_id'];
        $data['language_id']       = $this->config->get('config_language_id');

        $data['url_token']         = $this->module['url_token'];
        $data['url_extension']     = $this->module['url_extension'];
        $data['url_module']        = $this->module['url_module'];

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
            'href'  => $this->url->link($this->module['path'], 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'], true),
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
            'name'     => $this->config->get('config_name') . ' <b>(' .$this->language->get('text_default') . ')</b>',
            'url'      => HTTPS_CATALOG
        );
        $data['store']  = $this->module['store_id'] != 0 ? $this->model_setting_store->getStore($this->module['store_id']) : $store_default;
        $data['stores'] = array_merge(
            array(0 => $store_default),
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
        $this->load->model('setting/module');

        $module_id        = isset($this->request->get['module_id']) ? (int)$this->request->get['module_id'] : 0;
        $input_identifier = $this->module['code'] . '_widget';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $form = $this->request->post[$input_identifier];

            $form['widget_id'] = $module_id;
            $form['name']      = ucwords(str_replace('_', ' ', $form['type'])) . ' - ' . $form['name'];

            if (!$module_id) {
                $this->model_setting_module->addModule($this->module['code'] . '_widget', $form);
            } else {
                $this->model_setting_module->editModule($module_id, $form);
            }

            $this->session->data['success'] = $this->data['text_success'];
            $this->response->redirect($this->module['url_extension']);
        }

        // ===

        $data = $this->initPage($this->data);

        $data['module_id']          = $module_id;
        $data['url_form']           = $this->module['url_module'] . '&module_id=' . $module_id;
        $data['sub_title']          = $module_id ? $data['text_edit_widget'] . ' #' . $module_id : $data['text_add_widget'];
        $data['module_setting']     = $input_identifier;

        $this->document->setTitle($data['sub_title'] . ' - ' . $this->document->getTitle());

        $data['breadcrumbs'][]      = array(
            'text'  => $data['sub_title'],
            'href'  => $data['url_form']
        );

        // Support
        $data['unlincensedHtml']    = str_replace(
            "javascript:void(0)\" onclick=\"$('a[href=#isense_support]').trigger('click')",
            $this->url->link($this->module['path'], 'store_id=' . $this->module['store_id'] . '&' . $this->module['url_token'] . '&support=1', true),
            empty($this->module['setting']['LicensedOn']) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4NCiAgICAgICAgPGJ1dHRvbiB0eXBlPSJidXR0b24iIGNsYXNzPSJjbG9zZSIgZGF0YS1kaXNtaXNzPSJhbGVydCIgYXJpYS1oaWRkZW49InRydWUiPsOXPC9idXR0b24+DQogICAgICAgIDxoND5XYXJuaW5nISBVbmxpY2Vuc2VkIHZlcnNpb24gb2YgdGhlIG1vZHVsZSE8L2g0Pg0KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2Pg0KICAgICAgICA8YSBjbGFzcz0iYnRuIGJ0bi1kYW5nZXIiIGhyZWY9ImphdmFzY3JpcHQ6dm9pZCgwKSIgb25jbGljaz0iJCgnYVtocmVmPSNpc2Vuc2Vfc3VwcG9ydF0nKS50cmlnZ2VyKCdjbGljaycpIj5FbnRlciB5b3VyIGxpY2Vuc2UgY29kZTwvYT4NCiAgICA8L2Rpdj4=') : ''
        );

        // Content
        if (isset($this->request->post[$input_identifier])) {
            $data['setting']['widget'] = array_replace_recursive($data['setting']['widget'], $this->request->post[$input_identifier]);
        } elseif ($module_id) {
            $data['setting']['widget'] = array_replace_recursive($data['setting']['widget'], $this->model_setting_module->getModule($module_id));
        }

        $data['setting']['widget']['name_type'] = ucwords(str_replace('_', ' ', $data['setting']['widget']['type']));
        $data['setting']['widget']['name'] = str_replace($data['setting']['widget']['name_type'] . ' - ', '', $data['setting']['widget']['name']);

        $data['info_form_widget']   = $module_id ? $data['info_form_widget'] : $data['info_form_widget_type'];

        // Page element
        $data['header']             = $this->load->controller('common/header');
        $data['column_left']        = $this->load->controller('common/column_left');
        $data['footer']             = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->module['path'] . '_widget', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', $this->module['path'] . '_widget')) {
            $this->error['warning'] = $this->language->get('error_permission');

            return !$this->error;
        }

        $post = $this->request->post[$this->module['code'] . '_widget'];

        if ((utf8_strlen($post['name']) < 3) || (utf8_strlen($post['name']) > 64)) {
            $this->error['item']['name'] = $this->language->get('error_name');
        }

        if (!empty($this->error['item'])) {
            $message = $this->language->get('error_form');
            $message .= '<ul>';
            foreach ($this->error['item'] as $error) {
                $message .= '<li>' . $error . '</li>';
            }
            $message .= '</ul>';

            $this->error['warning'] = $message;
        }

        return !$this->error;
    }
}
