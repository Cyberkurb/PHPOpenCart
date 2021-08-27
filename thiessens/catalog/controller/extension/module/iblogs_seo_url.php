<?php
class ControllerExtensionModuleiBlogsSeoUrl extends Controller
{
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
        $this->module['setting'] = array_replace_recursive(
            $this->module['setting'],
            !empty($setting[$this->module['code'] . '_setting']) ? $setting[$this->module['code'] . '_setting'] : array()
        );
    }

    public function index()
    {
        $setting    = $this->module['setting'];
        $root_alias = isset($setting['blog_listing']['url_alias'][$this->config->get('config_language_id')]) ? $setting['blog_listing']['url_alias'][$this->config->get('config_language_id')] : $setting['blog_listing']['url_alias'][0];

        // Decode URL
        if (isset($this->request->get['_route_'])) {
            $parts = explode('/', $this->request->get['_route_']);

            // remove any empty arrays from trailing
            if (utf8_strlen(end($parts)) == 0) {
                array_pop($parts);
            }

            foreach ($parts as $part) {
                if ($part == $root_alias) {
                    $this->request->get['route'] = $this->module['path'];
                    continue;
                }
                if ($part == 'iblog_feed.xml') {
                    $this->request->get['route'] = $this->module['path'] . '/feed';
                    continue;
                }

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "iblogs_url_alias WHERE keyword = '" . $this->db->escape($part) . "' AND language_id = " . (int)$this->config->get('config_language_id'));

                if ($query->num_rows) {
                    $url = explode('=', $query->row['query']);

                    if ($url[0] == 'post_id') {
                        $this->request->get['post_id'] = $url[1];
                    }

                    if ($url[0] == 'category_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    }
                }
            }
        }

        return array(
            'redirect'  => isset($redirect) ? $redirect : '',
            'action'    => isset($this->request->get['route']) ? $this->request->get['route'] : '',
        );
    }

    public function rewrite($link)
    {
        $url        = '';
        $data       = array();
        $setting    = $this->module['setting'];
        $alias_fail = false;

        $url_info   = parse_url(str_replace('&amp;', '&', $link));
        $root_alias = isset($setting['blog_listing']['url_alias'][$this->config->get('config_language_id')]) ? $setting['blog_listing']['url_alias'][$this->config->get('config_language_id')] : $setting['blog_listing']['url_alias'][0];

        if (isset($url_info['query'])) {
            parse_str($url_info['query'], $data);
        }

        if (isset($data['route']) && $data['route'] == $this->module['path']) {
            unset($data['route']);
            $url .= '/' . $root_alias;

            foreach ($data as $key => $value) {
                if ($key == 'post_id') {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "iblogs_url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "' AND language_id = " . (int)$this->config->get('config_language_id'));
                    if ($query->num_rows && $query->row['keyword']) {
                        $url .= '/' . $query->row['keyword'];
                        unset($data[$key]);
                    }
                }

                if ($key == 'path') {
                    $categories = explode('_', $value);

                    foreach ($categories as $category) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "iblogs_url_alias WHERE `query` = 'category_id=" . (int)$category . "' AND language_id = " . (int)$this->config->get('config_language_id'));

                        if ($query->num_rows && $query->row['keyword']) {
                            $url .= '/' . $query->row['keyword'];
                        } else {
                            $alias_fail = true;
                            break 2;
                        }
                    }
                    unset($data[$key]);
                }
            }
        }

        // Feed
        if (isset($data['route']) && $data['route'] == $this->module['path'] . '/feed') {
            $url .= '/iblog_feed.xml';
        }

        if ($alias_fail) {
            $url = '';
        }

        if ($url) {
            unset($data['route']);

            $query = '';

            if ($data) {
                foreach ($data as $key => $value) {
                    $query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
                }

                if ($query) {
                    $query = '?' . str_replace('&', '&amp;', trim($query, '&'));
                }
            }

            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
        }

        return $url;
    }
}
