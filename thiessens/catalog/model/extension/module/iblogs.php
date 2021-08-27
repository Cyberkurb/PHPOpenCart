<?php
class ModelExtensionModuleiBlogs extends Model
{
    protected $module = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->config->load('isenselabs/iblogs');
        $this->module = $this->config->get('iblogs');

        $this->load->model('setting/setting');

        // Module setting
        $setting = $this->model_setting_setting->getSetting($this->module['code'], $this->config->get('config_store_id'));
        $this->module['setting'] = array_replace_recursive(
            $this->module['setting'],
            !empty($setting[$this->module['code'] . '_setting']) ? $setting[$this->module['code'] . '_setting'] : array()
        );
    }

    public function getPost($post_id)
    {
        $query = $this->db->query(
            "SELECT DISTINCT *,
                (SELECT CONCAT(u.firstname, ' ', u.lastname) FROM " . DB_PREFIX . "user u WHERE ip.author_id = u.user_id) AS author
            FROM `" . DB_PREFIX . "iblogs_post` ip
            LEFT JOIN `" . DB_PREFIX . "iblogs_post_content` ipc ON (ip.post_id = ipc.post_id)
            WHERE ip.post_id = '" . (int)$post_id . "'
                AND ipc.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND ip.store_id = '" . (int)$this->config->get('config_store_id') . "'
                AND ip.status = '1'"
        );

        $data = array();
        if ($query->row) {
            $data = $query->row;

            $path = '';
            if ($data['category_id']) {
                $path = 'path=' . $data['category_id'] . '&';
            }

            $data['meta']            = json_decode($data['meta'], true);
            $data['publish']         = $data['publish'] ? date('M d, Y', strtotime($data['publish'])) : '';
            $data['canonical']       = $this->url->link($this->module['path'], $path . 'post_id=' . $data['post_id'], true);
            $data['categories']      = !empty($data['categories']) ? json_decode($data['categories'], true) : array();
            $data['categories_html'] = array();

            if ($data['category_id'] || $data['categories']) {
                $categories = array_unique(array_merge($data['categories'], array($data['category_id'])));

                foreach ($categories as $category_id) {
                    if ($category_id) {
                        $result = $this->db->query("SELECT icc.category_id, icc.title,
                            (SELECT GROUP_CONCAT(icct.category_id ORDER BY level SEPARATOR '_') FROM " . DB_PREFIX . "iblogs_category_path icp LEFT JOIN " . DB_PREFIX . "iblogs_category_content icct ON (icp.path_id = icct.category_id) WHERE icp.category_id = icc.category_id AND icct.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY icp.category_id) AS path
                            FROM `" . DB_PREFIX . "iblogs_category_content` icc WHERE icc.category_id = " . (int)$category_id . " AND icc.language_id = " . (int)$this->config->get('config_language_id'));

                        if ($result->row) {
                            $data['categories_html'][] = '<a href="' . $this->url->link($this->module['path'], 'path=' . $result->row['path'], true) . '" title="' . $result->row['title'] . '">' . $result->row['title'] . '</a>';

                            if ($data['category_id'] == $category_id) {
                                $data['canonical'] = $this->url->link($this->module['path'], 'path=' . $result->row['path'] . '&post_id=' . $data['post_id'], true);
                            }
                        }
                    }
                }
            }

            if ($data['meta_keyword']) {
                $data['tags'] = array_filter(array_map('trim', explode(',', $data['meta_keyword'])));
            }

            $data = array_replace_recursive(
                $this->module['setting']['post'],
                $data
            );
        }

        return $data;
    }

    public function getPosts($param = array())
    {
        $items = array();
        $param['query']    = isset($param['query']) ? $param['query'] : ' ';
        $param['start']    = isset($param['start']) ? $param['start'] : 0;
        $param['limit']    = !empty($param['limit']) ? $param['limit'] : 9999;
        $param['popular']  = !empty($param['popular']) ? $param['popular'] : 0;
        $param['order']    = 'ORDER BY is_featured DESC, publish DESC, item_order ASC, title ASC';

        if (!empty($param['category_id'])) {
            $param['query'] .= ' AND (ip.category_id = ' . (int)$param['category_id'] . ' OR ip.categories LIKE "%' . (int)$param['category_id'] . '%") ';
        }
        if (!empty($param['tags'])) {
            $param['query'] .= ' AND ipc.meta_keyword LIKE "%' . $this->db->escape($param['tags'])  . ',%"';
        }
        if (!empty($param['search'])) {
            $param['query'] .= ' AND (ipc.title LIKE "%' . $this->db->escape($param['search'])  . '%" OR ipc.excerpt LIKE "%' . $this->db->escape($param['search']) . '%" OR ipc.content LIKE "%' . $this->db->escape($param['search']) . '%")';
        }

        // Widget posts by popular 60 days
        $param['popular_select'] = '';
        if ($param['popular']) {
            $days_range = 60;
            $param['popular_select'] = " (SELECT COUNT(il.object_id) FROM `" . DB_PREFIX . "iblogs_log` il WHERE il.type = 'post' AND il.action = 'view' AND ip.post_id = il.object_id AND ip.store_id = " . (int)$this->config->get('config_store_id') . " AND il.created BETWEEN DATE_SUB(NOW(), INTERVAL " . $days_range . " DAY) AND NOW() GROUP BY object_id) AS total_view,";
            $param['order']          = 'ORDER BY total_view DESC, publish DESC, item_order ASC, title ASC';
        }

        $results = $this->db->query(
            "SELECT ip.*, ipc.*,
                (SELECT CONCAT(u.firstname, ' ', u.lastname) FROM " . DB_PREFIX . "user u WHERE ip.author_id = u.user_id) AS author,
                " . $param['popular_select'] . "
                iua.keyword AS url_alias, IF (ip.sort_order > 0, ip.sort_order, 99999) AS item_order
            FROM `" . DB_PREFIX . "iblogs_post` ip
                LEFT JOIN `" . DB_PREFIX . "iblogs_post_content` ipc ON (ip.post_id = ipc.post_id)
                LEFT JOIN `" . DB_PREFIX . "iblogs_url_alias` iua ON (iua.`query` = CONCAT('post_id=', ip.post_id))
            WHERE ip.status = '1'
                AND ip.store_id = " . (int)$this->config->get('config_store_id') . "
                AND ipc.language_id = " . (int)$this->config->get('config_language_id') . "
                " . $param['query'] . "
                AND CURDATE() >= ip.publish
                AND IF (UNIX_TIMESTAMP(ip.unpublish) = 0 OR (UNIX_TIMESTAMP(ip.unpublish) != 0 AND CURDATE() <= ip.unpublish), TRUE, FALSE)
            GROUP BY ip.post_id
            " . $param['order'] . "
            LIMIT " . (int)$param['start'] . "," . (int)$param['limit']
        );

        $items = $results->rows;

        foreach ($items as $key => $value) {
            $items[$key] = $value;

            $path = '';
            if ($items[$key]['category_id']) {
                $path = 'path=' . $items[$key]['category_id'] . '&';
            }

            $items[$key]['publish']         = $value['publish'] ? date('M d, Y', strtotime($value['publish'])) : '';
            $items[$key]['url_more']        = $this->url->link($this->module['path'], $path . 'post_id=' . $items[$key]['post_id'], true);
            $items[$key]['categories']      = !empty($items[$key]['categories']) ? json_decode($items[$key]['categories'], true) : array();
            $items[$key]['categories_html'] = array();

            if ($items[$key]['category_id'] || $items[$key]['categories']) {
                $categories = array_unique(array_merge($items[$key]['categories'], array($items[$key]['category_id'])));

                foreach ($categories as $category_id) {
                    if ($category_id) {
                        $result = $this->db->query("SELECT icc.category_id, icc.title,
                            (SELECT GROUP_CONCAT(icct.category_id ORDER BY level SEPARATOR '_') FROM " . DB_PREFIX . "iblogs_category_path icp LEFT JOIN " . DB_PREFIX . "iblogs_category_content icct ON (icp.path_id = icct.category_id) WHERE icp.category_id = icc.category_id AND icct.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY icp.category_id) AS path
                            FROM `" . DB_PREFIX . "iblogs_category_content` icc WHERE icc.category_id = " . (int)$category_id . " AND icc.language_id = " . (int)$this->config->get('config_language_id'));

                        if ($result->row) {
                            $items[$key]['categories_html'][] = '<a href="' . $this->url->link($this->module['path'], 'path=' . $result->row['path'], true) . '" title="' . $result->row['title'] . '">' . $result->row['title'] . '</a>';

                            // Update post url_more with full category path
                            if ($items[$key]['category_id'] == $category_id) {
                                $items[$key]['path'] = $result->row['path'];
                                $items[$key]['url_more'] = $this->url->link($this->module['path'], 'path=' . $result->row['path'] . '&post_id=' . $items[$key]['post_id'], true);
                            }
                        }
                    }
                }
            }
        }

        return $items;
    }

    public function getCategory($category_id)
    {
        $query = $this->db->query(
            "SELECT DISTINCT *
            FROM `" . DB_PREFIX . "iblogs_category` ic
                LEFT JOIN `" . DB_PREFIX . "iblogs_category_content` icc ON (ic.category_id = icc.category_id)
            WHERE ic.category_id = '" . (int)$category_id . "'
                AND icc.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND ic.store_id = '" . (int)$this->config->get('config_store_id') . "'
                AND ic.status = '1'"
        );

        return $query->row;
    }

    public function getTotalPost($param)
    {
        $param['query'] = isset($param['query']) ? $param['query'] : ' ';

        if (!empty($param['category_id'])) {
            $param['query'] .= ' AND (ip.category_id = ' . (int)$param['category_id'] . ' OR ip.categories LIKE "%' . (int)$param['category_id'] . '%") ';
        }
        if (!empty($param['tags'])) {
            $param['query'] .= ' AND ipc.meta_keyword LIKE "%' . $this->db->escape($param['tags'])  . ',%"';
        }
        if (!empty($param['search'])) {
            $param['query'] .= ' AND (ipc.title LIKE "%' . $this->db->escape($param['search'])  . '%" OR ipc.excerpt LIKE "%' . $this->db->escape($param['search']) . '%" OR ipc.content LIKE "%' . $this->db->escape($param['search']) . '%")';
        }

        $query = $this->db->query(
            "SELECT COUNT(DISTINCT ip.post_id) AS total
            FROM `" . DB_PREFIX . "iblogs_post` ip
                LEFT JOIN `" . DB_PREFIX . "iblogs_post_content` ipc ON (ip.post_id = ipc.post_id)
            WHERE ip.status = '1'
                AND ipc.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND ip.store_id = " . (int)$this->config->get('config_store_id') . "
                " . $param['query'] . "
                AND CURDATE() >= ip.publish
                AND IF (UNIX_TIMESTAMP(ip.unpublish) = 0 OR (UNIX_TIMESTAMP(ip.unpublish) != 0 AND CURDATE() <= ip.unpublish), TRUE, FALSE)"
        );

        return $query->row['total'];
    }

    // For widget
    public function getTags()
    {
        $tags  = array();
        $query = $this->db->query(
            "SELECT GROUP_CONCAT(DISTINCT ipc.meta_keyword, ',') AS tags
            FROM `" . DB_PREFIX . "iblogs_post` ip
                LEFT JOIN `" . DB_PREFIX . "iblogs_post_content` ipc ON (ip.post_id = ipc.post_id)
            WHERE ip.store_id = " . (int)$this->config->get('config_store_id') . "
                AND ipc.language_id = " . (int)$this->config->get('config_language_id')
        );

        if (!empty($query->row['tags'])) {
            $tags = array_unique(array_filter(array_map('trim', explode(',', $query->row['tags']))));
        }

        return $tags;
    }

    // For widget
    public function getCategories($parent_id = 0)
    {
        $query = $this->db->query(
            "SELECT *
            FROM " . DB_PREFIX . "iblogs_category ic
                LEFT JOIN " . DB_PREFIX . "iblogs_category_content icc ON (ic.category_id = icc.category_id)
            WHERE ic.parent_id = '" . (int)$parent_id . "'
                AND icc.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND ic.store_id = '" . (int)$this->config->get('config_store_id') . "'
                AND ic.status = '1'
            ORDER BY ic.sort_order ASC, LCASE(icc.title) ASC"
        );

        return $query->rows;
    }

    public function getProducts($param)
    {
        $products = array();
        $param['query'] = isset($param['query']) ? $param['query'] : ' ';

        // Fetch products
        $query = $this->db->query("SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special
            FROM " . DB_PREFIX . "product p
                LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
                LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
            WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND p.status = '1'
                AND p.date_available <= NOW()
                AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
                " . $param['query'] . "
            GROUP BY p.product_id
            ORDER BY p.date_added DESC");

        // Products detail
        $results = array();
        if ($query->rows) {
            $this->load->model('catalog/product');
            foreach ($query->rows as $result) {
                $results[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
            }
        }

        // Products variables
        if ($results) {
            $this->load->model('tool/image');

            $image_width = $image_height = 120;
            $image = $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);

            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $image_width, $image_height);
                }

                $price = false;
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                }

                $special = false;
                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                }

                $tax = false;
                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                }

                $rating = false;
                if ($this->config->get('config_review_status')) {
                    $rating = $result['rating'];
                }

                $products[] = array(
                    'product_id'  => $result['product_id'],
                    'thumb'       => $image,
                    'name'        => $result['name'],
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                );
            }
        }

        return $products;
    }

    public function addLog($type, $action, $object_id, $meta = array())
    {
        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "iblogs_log`
                SET `type` = '" . $this->db->escape($type) . "',
                    `action` = '" . $this->db->escape($action) . "',
                    `object_id` = '" . (int)$object_id . "',
                    `store_id` = '" . (int)$this->config->get('config_store_id') . "',
                    `meta` = '" . $this->db->escape(json_encode($meta)) . "',
                    `created` = NOW()"
        );
    }
}
