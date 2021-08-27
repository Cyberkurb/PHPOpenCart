<?php
class ControllerExtensionModuleiBlogs extends Controller
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

    /**
     * Main entrance, manage post and category page
     */
    public function index()
    {
        if (!$this->module['setting']['status']) {
            $this->response->redirect($this->url->link('common/home', '', true));
        }

        $this->load->model('tool/image');

        $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->module['name'] . '.css?v=' .  $this->module['version']);

        $data            = $this->data;
        $data['post_id'] = isset($this->request->get['post_id']) ? (int)$this->request->get['post_id'] : 0;
        $data['path']    = isset($this->request->get['path']) ? $this->request->get['path'] : 0;

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->module['setting']['title'],
            'href' => $this->url->link($this->module['path'], '', true)
        );

        if ($data['post_id']) {
            $this->post($data);
        } else {
            $this->category($data);
        }
    }

    protected function category($data)
    {
        $parts        = explode('_', $data['path']);
        $category_id  = end($parts);
        $tags         = isset($this->request->get['tags']) ? $this->request->get['tags'] : '';
        $search       = isset($this->request->get['search']) ? $this->request->get['search'] : '';

        $blog_listing = $this->prepareData($this->module['setting']['blog_listing']);
        $category     = $this->module['model']->getCategory($category_id);
        $url_path     = $data['path'] ? 'path=' . $data['path'] : '';

        if ($data['path'] && $category_id && !$category) {
            return $this->notFound('path=' . $data['path'], $data);
        }

        $meta_title    = $blog_listing['meta_title'] ? $blog_listing['meta_title'] : $this->module['setting']['title'];
        $heading_title = $this->module['setting']['title'];
        if ($category) {
            $meta_title    = !empty($category['meta_title']) ? $category['meta_title'] : $category['title'];
            $heading_title = $category['title'];
        }
        if ($tags || $search) {
            $blog_listing['layout'] = 'grid';
        }

        // Document
        $this->document->setTitle($meta_title);
        $this->document->setDescription($blog_listing['meta_description']);
        $this->document->setKeywords($blog_listing['meta_keyword']);
        $this->document->addLink($this->url->link($this->module['path'], 'path=' . $category_id, true), 'canonical');

        // Breadcrumb
        $path = '';
        foreach ($parts as $path_id) {
            if (!$path) {
                $path = (int)$path_id;
            } else {
                $path .= '_' . (int)$path_id;
            }

            if ($path_id) {
                $cat_info = $this->module['model']->getCategory($path_id);

                $data['breadcrumbs'][] = array(
                    'text' => $cat_info['title'],
                    'href' => $this->url->link($this->module['path'], 'path=' . $path, true)
                );
            }
        }

        if ($search) {
            $data['breadcrumbs'][] = array(
                'text' => $data['text_search'] . $search,
                'href' => $this->url->link($this->module['path'], 'search=' . $search, true)
            );
        }
        if ($tags) {
            $data['breadcrumbs'][] = array(
                'text' => $data['text_tags'] . $tags,
                'href' => $this->url->link($this->module['path'], 'tags=' . $tags, true)
            );
        }

        // Content
        $data['category_id']      = $category_id;
        $data['heading_title']    = $heading_title;
        $data['heading_feed']     = !$category_id ? $this->url->link($this->module['path'] . '/feed', '', true) : '';
        $data['css_class']        = ($category ? 'iblogs-category-' . $category_id : 'iblogs-home-listing') . ' iblogs-layout-' . $blog_listing['layout'];
        $data['custom_css']       = trim(htmlspecialchars_decode($blog_listing['custom_css']));
        $data['no_image']         = $this->model_tool_image->fit('no_image.png', $blog_listing['image_width'], $blog_listing['image_height']);
        $data['category_thumb']   = $category ? $this->model_tool_image->fit($category['image'], $blog_listing['image_width'], $blog_listing['image_height']) : $data['no_image'];
        $data['category_content'] = $category ? html_entity_decode($category['content'], ENT_QUOTES, 'UTF-8') : '';

        if ($search) {
            $data['category_content'] = '<p>' . $data['text_blog_search'] . $search . '</p>';
        }
        if ($tags) {
            $data['category_content'] = '<p>' . $data['text_blog_tags'] . $tags . '</p>';
        }

        // Post listing
        $data['layout']           = $blog_listing['layout'];
        $data['posts']            = array();
        $data['posts_lead']       = array();
        $data['posts_grid']       = array();

        $page   = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
        $limit  = $blog_listing['limit'];
        $params = array(
            'category_id' => $category_id,
            'tags'        => $tags,
            'search'      => $search,
            'start'       => ($page - 1) * $limit,
            'limit'       => $limit,
        );

        $posts = $this->module['model']->getPosts($params);

        foreach ($posts as $key => $post) {
            $data['posts'][$key] = $post;
            $data['posts'][$key]['image'] = $post['image'] ? $this->model_tool_image->fit($post['image'], $blog_listing['image_width'], $blog_listing['image_height']) : $data['no_image'];
$data['posts'][$key]['image_org'] = $post['image'] ? $post['image'] : $data['no_image'];

            $data['posts'][$key]['info_format'] = $this->infoFormat($blog_listing['info_format'], array(
                '{author}'   => $post['author'],
                '{date}'     => $post['publish'],
                '{category}' => implode(', ', $post['categories_html'])
            ));
        }

        if ($blog_listing['layout'] == 'leading_list') {
            $data['posts_lead'] = array_slice($data['posts'], 0, 2);
            $data['posts_grid'] = array_chunk(array_slice($data['posts'], 2), 2);
        }
        if ($blog_listing['layout'] == 'grid') {
            $data['posts_grid'] = array_chunk($data['posts'], 2);
        }

        $total_item = $this->module['model']->getTotalPost($params);

        $pagination         = new Pagination();
        $pagination->total  = $total_item;
        $pagination->page   = $page;
        $pagination->limit  = $limit;
        $pagination->url    = $this->url->link($this->module['path'], $url_path . '&page={page}', true);

        $data['pagination'] = $pagination->render();
        $data['pagination_info'] = sprintf($this->language->get('text_pagination'), ($total_item) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_item - $limit)) ? $total_item : ((($page - 1) * $limit) + $limit), $total_item, ceil($total_item / $limit));

        // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
        if ($page == 1) {
            $this->document->addLink($this->url->link($this->module['path'], 'path=' . $category_id, true), 'canonical');
        } elseif ($page == 2) {
            $this->document->addLink($this->url->link($this->module['path'], 'path=' . $category_id, true), 'prev');
        } else {
            $this->document->addLink($this->url->link($this->module['path'], 'path=' . $category_id . '&page='. ($page - 1), true), 'prev');
        }

        if ($limit && ceil($total_item / $limit) > $page) {
            $this->document->addLink($this->url->link($this->module['path'], 'path=' . $category_id . '&page='. ($page + 1), true), 'next');
        }

        // Page element
        $data['column_left']    = $this->load->controller('common/column_left');
        $data['column_right']   = $this->load->controller('common/column_right');
        $data['content_top']    = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer']         = $this->load->controller('common/footer');
        $data['header']         = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view($this->module['path'] . '/category', $data));
    }

    protected function post($data)
    {
        $post         = $this->module['model']->getPost($data['post_id']);
        $post_view    = $this->prepareData($this->module['setting']['post_view']);
        $blog_listing = $this->prepareData($this->module['setting']['blog_listing']);
        $parts        = explode('_', $data['path']);

        if (!$post) {
            return $this->notFound('path=' . $data['path'], $data);
        }

        $this->module['model']->addLog('post', 'view', $post['post_id']);

        // Document
        $this->document->setTitle($post['meta_title'] ? $post['meta_title'] : $post['title']);
        $this->document->setDescription($post['meta_description'] ? $post['meta_description'] : $post['excerpt']);
        $this->document->setKeywords($post['meta_keyword']);
        $this->document->addLink($post['canonical'], 'canonical');

        $this->session->data['iblogs_posts'] = $post;

        // Breadcrumb
        $path = '';
        foreach ($parts as $path_id) {
            if (!$path) {
                $path = (int)$path_id;
            } else {
                $path .= '_' . (int)$path_id;
            }

            if ($path_id) {
                $cat_info = $this->module['model']->getCategory($path_id);

                $data['breadcrumbs'][] = array(
                    'text' => $cat_info['title'],
                    'href' => $this->url->link($this->module['path'], 'path=' . $path, true)
                );
            }
        }
        $url_path = $data['path'] ? 'path=' . $data['path'] : '';
        $data['breadcrumbs'][] = array(
            'text' => $post['title'],
            'href' => $this->url->link($this->module['path'], $url_path . '&post_id=' . $data['post_id'], true)
        );

        // Content
        $data['post']             = $post;
        $data['heading_title']    = $post['title'];
        $data['post_content']     = html_entity_decode($post['content'], ENT_QUOTES, 'UTF-8');
        $data['info_format']      = $this->infoFormat($post_view['info_format'], array(
            '{author}'   => '<span itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name">' . $post['author'] . '</span></span>',
            '{date}'     => '<time datetime="' . date(DATE_ISO8601, strtotime($post['publish'])) . '" itemprop="datePublished">' . $post['publish'] . '</time>',
            '{category}' => implode(', ', $post['categories_html'])
        ));

        $data['post_view']        = $post_view;
        $data['css_class']        = 'iblogs-post-' . $post['post_id'];
        $data['custom_css']       = trim(htmlspecialchars_decode($post_view['custom_css']));
        $data['no_image']         = $this->model_tool_image->fit('no_image.png', $post_view['image_width'], $post_view['image_height']);

        $data['show_main_image'] = $post_view['main_image'] && $post['image'];
        $data['main_image'] = '';
        if ($post['image']) {
            $data['main_image'] = $this->model_tool_image->fit($post['image'], $post_view['image_width'], $post_view['image_height']);
$data['main_image_org'] = $post['image'];
        }

        // additional for Microdata :: https://search.google.com/structured-data/testing-tool
        $data['ogp'] = array(
            'publisher'      => $this->config->get('config_name'),
            'publisher_logo' => is_file(DIR_IMAGE . $this->config->get('config_logo')) ? $this->config->get('config_ssl') . 'image/' . $this->config->get('config_logo') : '',
            'dateModified'   => date(DATE_ISO8601, strtotime($post['updated']))
        );

        // Tags
        $tags = array();
        $data['post_tags'] = '';

        if ($post['tags']) {
            foreach ($post['tags'] as $tag) {
                $tags[] = '<a href="' . $this->url->link($this->module['path'], 'tags=' . $tag, true) . '" title="' . $tag . '" class="iblogs-post-tag" rel="tag">' . $tag . '</a>';
            }
            $data['post_tags'] = implode(' ', $tags);
        }

        // Related Post
        $related_asset          = false;
        $related_post           = array();
        $data['related_post']   = array();
        $data['rel_post_arrow'] = false;

        if ($post['meta']['related_post'] == 'tags') {

            $related_asset = true;
            $params = array(
                'limit' => $blog_listing['limit'],
                'query' => ''
            );

            if ($post['tags']) {
                $params['query'] .= ' AND (';
                foreach ($post['tags'] as $key => $tag) {
                    if ($key) { $params['query'] .= ' OR '; }
                    $params['query'] .= 'ipc.meta_keyword LIKE "%' . $this->db->escape($tag)  . ',%"';
                }
                $params['query'] .= ') ';
            }
            $params['query'] .= ' AND ip.post_id != ' . (int)$post['post_id'];

            $related_post = $this->module['model']->getPosts($params);

        } elseif ($post['meta']['related_post'] == 'custom' && $post['meta']['related_post_items']) {

            $related_asset = true;
            $params = array(
                'limit' => $blog_listing['limit'],
                'query' => " AND ip.post_id IN (" . implode(',', $post['meta']['related_post_items']) . ")",
            );
            $params['query'] .= ' AND ip.post_id  != ' . (int)$post['post_id'];

            $related_post = $this->module['model']->getPosts($params);
        }

        if ($related_post) {
            $data['rel_post_arrow'] = count($related_post) > 3 ? true : false;
            foreach ($related_post as $key => $rel_post) {
                $data['related_post'][$key] = $rel_post;
                $data['related_post'][$key]['image'] = $rel_post['image'] ? $this->model_tool_image->fit($rel_post['image'], $blog_listing['image_width'], $blog_listing['image_height']) : $data['no_image'];

                $data['related_post'][$key]['info_format'] = $this->infoFormat($blog_listing['info_format'], array(
                    '{author}'   => $rel_post['author'],
                    '{date}'     => $rel_post['publish'],
                    '{category}' => implode(', ', $post['categories_html'])
                ));
            }
        }

        // Related Product
        $data['related_products']  = array();
        $data['rel_product_arrow'] = false;
        if ($post['meta']['related_product'] && $post['meta']['related_product_items']) {
            $related_asset = true;
            $related_products = $this->module['model']->getProducts(array(
                'query' => " AND p.product_id IN (" . implode(',', $post['meta']['related_product_items']). ")"
            ));

            if ($related_products) {
                $data['rel_product_arrow'] = count($related_products) > 4 ? true : false;
                $data['related_products']  = $related_products;
            }
        }

        if ($related_asset) {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->module['name'] . '/swiper/css/swiper.min.css');
            $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->module['name'] . '/swiper/css/opencart.css');
            $this->document->addScript('catalog/view/theme/default/stylesheet/' . $this->module['name'] . '/swiper/js/swiper.jquery.min.js');
        }

        // Comments


        // Page element
        $data['column_left']    = $this->load->controller('common/column_left');
        $data['column_right']   = $this->load->controller('common/column_right');
        $data['content_top']    = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer']         = $this->load->controller('common/footer');
        $data['header']         = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view($this->module['path'] . '/post', $data));
    }

    protected function notFound($url, $data)
    {
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_not_found'),
            'href'      => $this->url->link($this->module['path'], $url, true),
            'separator' => $this->language->get('text_separator')
        );

        $this->document->setTitle($this->language->get('text_not_found'));

        $data['heading_title']   = $this->language->get('text_not_found');
        $data['text_error']      = $this->language->get('text_not_found');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['continue']        = $this->url->link('common/home', '', true);

        $data['column_left']     = $this->load->controller('common/column_left');
        $data['column_right']    = $this->load->controller('common/column_right');
        $data['content_top']     = $this->load->controller('common/content_top');
        $data['content_bottom']  = $this->load->controller('common/content_bottom');
        $data['footer']          = $this->load->controller('common/footer');
        $data['header']          = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('error/not_found', $data));
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

    // =====================================================

    /**
     * Add categories (not posts) to information/sitemap
     */
    public function sitemap()
    {
        if (!$this->module['setting']['status']) {
            return '';
        }

        $data = $this->data;

        $data['url_blog'] = $this->url->link($this->module['path'], '', true);

        $categories  = $this->module['model']->getCategories(0);

        $data['categories']  = array();

        foreach ($categories as $category) {
            $data['categories'][] = array(
                'category_id' => $category['category_id'],
                'title'       => $category['title'],
                'children'    => $this->categoryChild($category['category_id']),
                'href'        => $this->url->link($this->module['path'], 'path=' . $category['category_id'], true)
            );
        }

        return $this->load->view($this->module['path'] . '/sitemap', $data);
    }

    protected function categoryChild($parent_id)
    {
        $data = array();
        $categories = $this->module['model']->getCategories($parent_id);

        foreach ($categories as $category) {
            $data[] = array(
                'category_id' => $category['category_id'],
                'title'       => $category['title'],
                'children'    => $this->categoryChild($category['category_id']),
                'href'        => $this->url->link($this->module['path'], 'path=' . $parent_id . '_' . $category['category_id'], true)
            );
        }

        return $data;
    }

    public function feed()
    {
        if (!$this->module['setting']['status']) {
            $this->response->redirect($this->url->link('common/home', '', true));
        }

        $this->load->model('tool/image');

        // SEO Backpack compatible
        $config_meta_desc = $this->config->get('config_meta_description');
        if (is_array($config_meta_desc) && isset($config_meta_desc[$this->config->get('config_language_id')])) {
            $config_meta_desc = $config_meta_desc[$this->config->get('config_language_id')];
        }

        // https://validator.w3.org/feed/
        $output  = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $output .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
            $output .= '<channel>';

                $output .= '<atom:link href="' . $this->encode_url($this->url->link($this->module['path'] . '/feed')) . '" rel="self" type="application/rss+xml" />' . "\n";
                $output .= '<title>' . $this->module['setting']['title'] . ' - ' . $this->config->get('config_name') . '</title>' . "\n";
                $output .= '<description>' . $config_meta_desc . '</description>' . "\n";
                $output .= '<link>' . $this->encode_url($this->config->get('config_ssl')) . '</link>' . "\n";

                $posts = $this->module['model']->getPosts(array('limit' => 20)); // latest 20 post

                if (!empty($posts[0]['post_id'])) {
                    $output .= '<lastBuildDate><![CDATA[' . gmdate(DATE_RSS, strtotime($posts[0]['publish'])) . ']]></lastBuildDate>' . "\n";
                }

                foreach ($posts as $post) {
                    $guid = substr(md5($post['post_id'] . $this->module['name']), 0, 3);

                    $output .= '<item>' . "\n";
                        $output .= '<title><![CDATA[' . html_entity_decode($post['title']) . ']]></title>' . "\n";

                        $output .= '<guid isPermaLink="true"><![CDATA[';
                            $output .= $this->encode_url($this->url->link($this->module['path'], 'post_id=' . $post['post_id']), $guid);
                        $output .= ']]></guid>' . "\n";

                        $output .= '<link><![CDATA[';
                            $output .= $this->encode_url($post['url_more'], $guid);
                        $output .= ']]></link>' . "\n";

                        $output .= '<description><![CDATA[' . strip_tags(html_entity_decode($post['excerpt'])) . ']]></description>' . "\n";
                        $output .= '<pubDate><![CDATA[' . gmdate(DATE_RSS, strtotime($post['publish'])) . ']]></pubDate>' . "\n";

                        // Image
                        if ($post['image'] && file_exists(DIR_IMAGE . $post['image'])) {
                            $image_format = '<enclosure url="%s" length="%s" type="%s" />';
                            $image_url    = $this->model_tool_image->fit($post['image'], 250, 250);

                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mime_type = finfo_file($finfo, DIR_IMAGE . $post['image']);
                            $image_url = str_replace(' ', '%20', $image_url);

                            $output .= sprintf($image_format, $image_url, filesize(DIR_IMAGE . $post['image']), $mime_type) . "\n";
                        }

                        // Category
                        foreach ($post['categories_html'] as $category) {
                            $output .= '<category><![CDATA[' . strip_tags($category, '') . ']]></category>' . "\n";
                        }

                    $output .= '</item>' . "\n";
                }

            $output .= '</channel>' . "\n";
        $output .= '</rss>' . "\n";

        $this->response->addHeader('Content-Type: application/rss+xml');
        $this->response->setOutput($output);
    }

    protected function encode_url($url, $guid = '')
    {
        $guid = $guid ? '?' . $guid : '';
        $url  = HTTP_SERVER . str_replace(HTTP_SERVER, '', $url) . $guid;
        $url  = str_replace(' ', '%20', $url);

        return $url;
    }
}
