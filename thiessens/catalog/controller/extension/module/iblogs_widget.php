<?php
class ControllerExtensionModuleiBlogsWidget extends Controller
{
    protected $data   = array();
    protected $module = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        // Module configuration
        $this->config->load('isenselabs/iblogs');
        $this->module = $this->config->get('iblogs');

        $this->load->model('setting/setting');
        $this->load->model($this->module['path']);

        $this->module['model'] = $this->{$this->module['model']};

        // Module db setting
        $setting = $this->model_setting_setting->getSetting($this->module['code'], $this->config->get('config_store_id'));
        $this->module['setting'] = $this->prepareData(array_replace_recursive(
            $this->module['setting'],
            !empty($setting[$this->module['code'] . '_setting']) ? $setting[$this->module['code'] . '_setting'] : array()
        ));

        // Template variables
        $this->data['store_id'] = $this->config->get('config_store_id');
        $this->data['lang_id']  = $this->config->get('config_language_id');
        $this->data['setting']  = $this->module['setting'];
        $this->data['theme']    = $this->config->get('config_theme') ? $this->config->get('config_theme') : $this->config->get('config_template');

        // Theme identifier
        $this->data['theme'] = str_replace('theme_', '', $this->data['theme']);
        if ($this->data['theme'] == 'default' && $this->config->get('theme_default_directory') != $this->data['theme']) {
            $this->data['theme'] = $this->config->get('theme_default_directory');
        }

        $language_vars = $this->load->language($this->module['path'], $this->module['name']);
        $this->data = array_replace_recursive(
            $this->data,
            $language_vars[$this->module['name']]->all()
        );
    }

    public function index($setting)
    {
        if (!in_array($setting['type'], array('search', 'category', 'post_recent', 'post_popular', 'post_tags', 'post_tabs'))) {
            return '';
        }

        $this->load->model('tool/image');

        $setting = $this->prepareData(array_replace_recursive(
            $this->module['setting']['widget'],
            $setting
        ));

        $data = $this->data;
        $data['widget_heading'] = $setting['title'];
        $data['css_class']      = 'iblogs-widget-' . $setting['type'] . ' iblogs-widget-' . $setting['widget_id'];
        $data['custom_css']     = trim(htmlspecialchars_decode($setting['custom_css']));
        $data['no_image']       = $this->model_tool_image->resize('no_image.png', 48, 48);

        if ($setting['type'] == 'search') {
            $data['search']      = '';
            $data['placeholder'] = $setting['search_placeholder'];
            $data['url_search']  = $this->url->link($this->module['path'], '', true);

            if ($this->request->get['route'] == $this->module['path'] && isset($this->request->get['search'])) {
                $data['search'] = $this->request->get['search'];
            }
        }

        if ($setting['type'] == 'category') {
            $path        = isset($this->request->get['path']) ? $this->request->get['path'] : 0;
            $parts       = explode('_', $path);
            $categories  = $this->module['model']->getCategories(0);

            $data['categories']  = array();
            $data['category_id'] = isset($parts[0]) ? $parts[0] : 0;
            $data['child_id']    = isset($parts[1]) ? $parts[1] : 0;

            foreach ($categories as $category) {
                $data['categories'][] = array(
                    'category_id' => $category['category_id'],
                    'title'       => $category['title'],
                    'children'    => $this->categoryChild($category['category_id'], $data['category_id']),
                    'href'        => $this->url->link($this->module['path'], 'path=' . $category['category_id'], true)
                );
            }
        }

        if (in_array($setting['type'], array('post_recent', 'post_tabs'))) {
            $params = array(
                'start' => 0,
                'limit' => $setting['post_limit'],
            );

            $posts = $this->module['model']->getPosts($params);

            $data['posts_recent'] = array();
            foreach ($posts as $key => $post) {
                $data['posts_recent'][$key] = $post;
                $data['posts_recent'][$key]['image'] = $post['image'] ? $this->model_tool_image->resize($post['image'], 48, 48) : $data['no_image'];
            }
        }

        if (in_array($setting['type'], array('post_popular', 'post_tabs'))) {
            $params = array(
                'popular' => 1,
                'start'   => 0,
                'limit'   => $setting['post_limit'],
            );

            $posts = $this->module['model']->getPosts($params);

            $data['posts_popular'] = array();
            foreach ($posts as $key => $post) {
                $data['posts_popular'][$key] = $post;
                $data['posts_popular'][$key]['image'] = $post['image'] ? $this->model_tool_image->resize($post['image'], 48, 48) : $data['no_image'];
            }
        }

        if (in_array($setting['type'], array('post_tags', 'post_tabs'))) {
            $tags = $this->module['model']->getTags();

            $data['posts_tags'] = array();
            foreach ($tags as $key => $tag) {
                $data['posts_tags'][$key] = array(
                    'title' => $tag,
                    'url'   => $this->url->link($this->module['path'], 'tags=' . $tag, true)
                );
            }
        }

        if ($setting['type'] == 'post_tabs') {
        }

        return $this->load->view($this->module['path'] . '/widget_' . $setting['type'], $data);
    }

    protected function categoryChild($parent_id, $category_id)
    {
        $data = array();

        if ($parent_id == $category_id) {
            $categories  = $this->module['model']->getCategories($parent_id);

            foreach ($categories as $category) {
                $data[] = array(
                    'category_id' => $category['category_id'],
                    'title'       => $category['title'],
                    'children'    => $this->categoryChild($category['category_id'], $category_id),
                    'href'        => $this->url->link($this->module['path'], 'path=' . $parent_id . '_' . $category['category_id'], true)
                );
            }
        }

        return $data;
    }

    /**
     * Standarize data value
     */
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

    protected function infoFormat($format, $param = array())
    {
        $template       = array_keys($param);
        $replacement    = array_values($param);

        return html_entity_decode(str_replace($template, $replacement, $format), ENT_QUOTES, 'UTF-8');
    }
}
