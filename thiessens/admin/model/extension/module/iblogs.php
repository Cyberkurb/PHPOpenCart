<?php
class ModelExtensionModuleiBlogs extends Model
{
    protected $module = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->config->load('isenselabs/iblogs');
        $this->module = $this->config->get('iblogs');

        if (isset($this->request->post['store_id'])) {
            $this->module['store_id'] = (int)$this->request->post['store_id'];
        } elseif (isset($this->request->get['store_id'])) {
            $this->module['store_id'] = (int)$this->request->get['store_id'];
        }
    }

    public function getPosts($param = array())
    {
        $items = array();
        $param['query']   = isset($param['query']) ? $param['query'] : '';
        $param['start']   = isset($param['start']) ? $param['start'] : 0;
        $param['limit']   = isset($param['limit']) ? $param['limit'] : 9999;
        $param['popular'] = !empty($param['popular']) ? $param['popular'] : 0;
        $param['order']   = isset($param['order']) ? $param['order'] : 'ORDER BY publish DESC, item_order ASC, title ASC';

        $param['popular_select'] = '';
        if ($param['popular']) {
            $days_range = 60;
            $param['popular_select'] = ", (SELECT COUNT(il.object_id) FROM `" . DB_PREFIX . "iblogs_log` il WHERE il.type = 'post' AND il.action = 'view' AND ip.post_id = il.object_id AND ip.store_id = " . (int)$this->config->get('config_store_id') . " AND il.created BETWEEN DATE_SUB(NOW(), INTERVAL " . $days_range . " DAY) AND NOW() GROUP BY object_id) AS total_view";
            $param['order']          = 'ORDER BY total_view DESC, publish DESC, title ASC';
        }

        $results = $this->db->query(
            "SELECT ip.*, ipc.*, iua.keyword AS url_alias, IF (ip.sort_order > 0, ip.sort_order, 99999) AS item_order
                " . $param['popular_select'] . "
            FROM `" . DB_PREFIX . "iblogs_post` ip
                LEFT JOIN `" . DB_PREFIX . "iblogs_post_content` ipc ON (ip.post_id = ipc.post_id)
                LEFT JOIN `" . DB_PREFIX . "iblogs_url_alias` iua ON (iua.`query` = CONCAT('post_id=', ip.post_id) AND iua.`language_id` = " . (int)$this->config->get('config_language_id') . ")
            WHERE store_id = " . (int)$this->module['store_id'] . "
                AND ipc.language_id = " . (int)$this->config->get('config_language_id') . "
                " . $param['query'] . "
            GROUP BY ip.post_id
            " . $param['order'] . "
            LIMIT " . (int)$param['start'] . "," . (int)$param['limit']
        );

        $items = $results->rows;

        foreach ($items as $key => $value) {
            $items[$key] = $value;

            $items[$key]['duration_start'] = $value['publish'] ? date('M d, Y', strtotime($value['publish'])) : '';
            $items[$key]['duration_end']   = $value['unpublish'] != '0000-00-00 00:00:00' ? date('M d, Y', strtotime($value['unpublish'])) : '';
        }

        return $items;
    }

    public function getCategories($param = array())
    {
        $items = array();
        $param['query'] = isset($param['query']) ? $param['query'] : '';
        $param['start'] = isset($param['start']) ? $param['start'] : 0;
        $param['limit'] = isset($param['limit']) ? $param['limit'] : 9999;

        $results = $this->db->query(
            "SELECT ic.*, icc.*, iua.keyword AS url_alias, IF (ic.sort_order > 0, ic.sort_order, 99999) AS item_order,
                (SELECT COUNT(*) FROM `" . DB_PREFIX . "iblogs_post` ip WHERE ip.category_id = ic.category_id OR ip.categories LIKE CONCAT('%', ic.category_id, '%')) AS post_count,
                (SELECT GROUP_CONCAT(icct.title ORDER BY level SEPARATOR '{x}') FROM " . DB_PREFIX . "iblogs_category_path icp LEFT JOIN " . DB_PREFIX . "iblogs_category_content icct ON (icp.path_id = icct.category_id) WHERE icp.category_id = icc.category_id AND icct.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY icp.category_id) AS path
            FROM `" . DB_PREFIX . "iblogs_category` ic
                LEFT JOIN `" . DB_PREFIX . "iblogs_category_content` icc ON (ic.category_id = icc.category_id)
                LEFT JOIN `" . DB_PREFIX . "iblogs_url_alias` iua ON (iua.`query` = CONCAT('category_id=', ic.category_id) AND iua.`language_id` = " . (int)$this->config->get('config_language_id') . ")
            WHERE store_id = " . (int)$this->module['store_id'] . "
                AND icc.language_id = " . (int)$this->config->get('config_language_id') . "
                " . $param['query'] . "
            GROUP BY ic.category_id
            ORDER BY path ASC, item_order ASC
            LIMIT " . (int)$param['start'] . "," . (int)$param['limit']
        );

        $items = $results->rows;

        foreach ($items as $key => $value) {
            $items[$key] = $value;

            $items[$key]['path_html'] = $items[$key]['path'];
            $items[$key]['path'] = str_replace('{x}', '&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $items[$key]['path']);

            // Path HTML
            $segments = explode('{x}', $items[$key]['path_html'], -1);
            if (count($segments)) {
                $separator = ' <span class="isl-cat-path-sp">></span> ';

                $items[$key]['path_html'] = '<span class="isl-cat-path">';
                foreach ($segments as $segment) {
                    $items[$key]['path_html'] .= $segment . $separator;
                }
                $items[$key]['path_html'] .= '</span>';
                $items[$key]['path_html'] .= $items[$key]['title'];
            }
        }

        return $items;
    }

    public function getTotal($table, $param = array())
    {
        $param['query'] = isset($param['query']) ? $param['query'] : '';

        $query = $this->db->query(
            "SELECT COUNT(DISTINCT i." . $table . "_id) AS total
            FROM `" . DB_PREFIX . "iblogs_" . $table . "` i
                LEFT JOIN `" . DB_PREFIX . "iblogs_" . $table . "_content` ic ON (i." . $table . "_id = ic." . $table . "_id)
            WHERE i.store_id = " . (int)$this->module['store_id']
                . $param['query']
        );

        if (!empty($query->row['total'])) {
            return $query->row['total'];
        }

        return 0;
    }

    public function getTags()
    {
        $tags  = array();
        $query = $this->db->query(
            "SELECT GROUP_CONCAT(DISTINCT ipc.meta_keyword, ',') AS tags
            FROM `" . DB_PREFIX . "iblogs_post` ip
                LEFT JOIN `" . DB_PREFIX . "iblogs_post_content` ipc ON (ip.post_id = ipc.post_id)
            WHERE ip.store_id = " . (int)$this->module['store_id'] . "
                AND ipc.language_id = " . (int)$this->config->get('config_language_id')
        );

        if (!empty($query->row['tags'])) {
            $tags = array_unique(array_filter(array_map('trim', explode(',', $query->row['tags']))));
        }

        return count($tags);
    }

    // ================================================================

    public function getPost($post_id)
    {
        $post         = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_post` WHERE post_id = " . (int)$post_id);
        $post_content = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_post_content` WHERE post_id = " . (int)$post_id);
        $post_alias   = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_url_alias` WHERE `query` = 'post_id=" . (int)$post_id . "'");

        return $this->prepareData(
            'post',
            $post->row,
            $post_content->rows,
            $post_alias->rows
        );
    }

    public function getCategory($category_id)
    {
        $category         = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_category` WHERE category_id = " . (int)$category_id);
        $category_content = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_category_content` WHERE category_id = " . (int)$category_id);
        $category_alias   = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_url_alias` WHERE `query` = 'category_id=" . (int)$category_id . "'");

        return $this->prepareData(
            'category',
            $category->row,
            $category_content->rows,
            $category_alias->rows
        );
    }

    protected function prepareData($type, $main, $contents = array(), $alias = array())
    {
        $item    = $main;
        $lang_id = $this->config->get('config_language_id');

        $item['meta'] = !empty($item['meta']) ? json_decode($item['meta'], true) : array();

        foreach ($contents as $content) {
            $item['title'][$content['language_id']]             = $content['title'];
            $item['content'][$content['language_id']]           = $content['content'];
            $item['meta_title'][$content['language_id']]        = $content['meta_title'];
            $item['meta_description'][$content['language_id']]  = $content['meta_description'];
            $item['meta_keyword'][$content['language_id']]      = trim($content['meta_keyword'], ',');
        }

        if (!empty($alias)) {
            $item['url_alias'] = array();
            foreach ($alias as $url) {
                $item['url_alias'][$url['language_id']] = $url['keyword'];
            }
        }

        if ($type == 'post') {
            $item['categories'] = !empty($item['categories']) ? json_decode($item['categories'], true) : array();

            foreach ($contents as $content) {
                $item['excerpt'][$content['language_id']]       = $content['excerpt'];
            }

            if ($item['unpublish'] == '0000-00-00 00:00:00') {
                $item['unpublish'] = '';
            }
        }

        return $item;
    }

    // ================================================================

    public function formPost($post_id, $data)
    {
        $this->load->model('localisation/language');

        $data = array_merge($this->module['setting']['post'], $data);
        $languages = $this->model_localisation_language->getLanguages();

        // ====== Add Post
        if (!$post_id) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_post` SET " . $this->queryForm('post', $data) . ", `created` = NOW()");

            $post_id = $data['post_id'] = $this->db->getLastId();

        // ====== Update Post
        } else {
            $data['post_id'] = $post_id;

            $this->db->query("UPDATE `" . DB_PREFIX . "iblogs_post` SET " . $this->queryForm('post', $data) . " WHERE post_id = '" . (int)$post_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_post_content` WHERE post_id = '" . (int)$post_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_url_alias` WHERE `query` = 'post_id=" . (int)$post_id . "'");
        }

        // Insert post data
        foreach ($languages as $lang) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_post_content` SET " . $this->queryForm('post_content', $data, $lang['language_id']) . "");

            if (isset($data['url_alias'][$lang['language_id']])) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_url_alias` SET `language_id` = " . (int)$lang['language_id'] . ", `query` = 'post_id=" . (int)$post_id . "', `keyword` = '" . $this->db->escape(strtolower(str_replace(' ', '-', $data['url_alias'][$lang['language_id']]))) . "'");
            }
        }

        return $post_id;
    }

    public function formCategory($category_id, $data)
    {
        $this->load->model('localisation/language');

        $data = array_merge($this->module['setting']['category'], $data);
        $languages = $this->model_localisation_language->getLanguages();

        // ====== Add Category
        if (!$category_id) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_category` SET " . $this->queryForm('category', $data) . ", `created` = NOW()");

            $category_id = $data['category_id'] = $this->db->getLastId();

            // === MySQL Hierarchical Data Closure Table Pattern
            $level = 0;
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

            foreach ($query->rows as $result) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
                $level++;
            }
            $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");


        // ====== Update Category
        } else {
            $data['category_id'] = $category_id;

            $this->db->query("UPDATE `" . DB_PREFIX . "iblogs_category` SET " . $this->queryForm('category', $data) . " WHERE category_id = '" . (int)$category_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_category_content` WHERE category_id = '" . (int)$category_id . "'");
            $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_url_alias` WHERE `query` = 'category_id=" . (int)$category_id . "'");

            // === MySQL Hierarchical Data Closure Table Pattern
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

            if ($query->rows) {
                foreach ($query->rows as $category_path) {
                    $path = array();

                    // Delete the path below the current one
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

                    // Get the nodes new parents
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
                    foreach ($query->rows as $result) {
                        $path[] = $result['path_id'];
                    }

                    // Get whats left of the nodes current path
                    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");
                    foreach ($query->rows as $result) {
                        $path[] = $result['path_id'];
                    }

                    // Combine the paths with a new level
                    $level = 0;
                    foreach ($path as $path_id) {
                        $this->db->query("REPLACE INTO `" . DB_PREFIX . "iblogs_category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");
                        $level++;
                    }
                }
            } else {
                // Delete the path below the current one
                $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_category_path` WHERE category_id = '" . (int)$category_id . "'");

                // Fix for records with no paths
                $level = 0;
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblogs_category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
                foreach ($query->rows as $result) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");
                    $level++;
                }
                $this->db->query("REPLACE INTO `" . DB_PREFIX . "iblogs_category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
            }
        }

        // Insert category data
        foreach ($languages as $lang) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_category_content` SET " . $this->queryForm('category_content', $data, $lang['language_id']) . "");

            if (!empty($data['url_alias'][$lang['language_id']])) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "iblogs_url_alias` SET `language_id` = " . (int)$lang['language_id'] . ", `query` = 'category_id=" . (int)$category_id . "', `keyword` = '" . $this->db->escape(strtolower(str_replace(' ', '-', $data['url_alias'][$lang['language_id']]))) . "'");
            }
        }

        return $category_id;
    }

    /**
     * Standarize insert and update query
     */
    protected function queryForm($table, $data, $lang_id = 0)
    {
        if ($table == 'post') {
            return "
                `author_id`         = '" . (int)$data['author_id'] . "',
                `category_id`       = '" . (int)$data['category_id'] . "',
                `categories`        = '" . $this->db->escape(json_encode($data['categories'])) . "',
                `image`             = '" . $this->db->escape($data['image']) . "',
                `sort_order`        = '" . (int)$data['sort_order'] . "',
                `store_id`          = '" . (int)$this->module['store_id'] . "',
                `meta`              = '" . $this->db->escape(json_encode($data['meta'])) . "',
                `is_featured`       = '" . (int)$data['is_featured'] . "',
                `status`            = '" . (int)$data['status'] . "',
                `publish`           = '" . $this->db->escape($data['publish']) . "',
                `unpublish`         = '" . $this->db->escape($data['unpublish']) . "',
                `updated`           = NOW()
            ";
        }

        if ($table == 'post_content') {
            return "
                `post_id`           = '" . (int)$data['post_id'] . "',
                `language_id`       = '" . (int)$lang_id . "',
                `title`             = '" . $this->db->escape(isset($data['title'][$lang_id]) ? $data['title'][$lang_id] : '') . "',
                `excerpt`           = '" . $this->db->escape(isset($data['excerpt'][$lang_id]) ? $data['excerpt'][$lang_id] : '') . "',
                `content`           = '" . $this->db->escape(isset($data['content'][$lang_id]) ? $data['content'][$lang_id] : '') . "',
                `meta_title`        = '" . $this->db->escape(isset($data['meta_title'][$lang_id]) ? $data['meta_title'][$lang_id] : '') . "',
                `meta_description`  = '" . $this->db->escape(isset($data['meta_description'][$lang_id]) ? $data['meta_description'][$lang_id] : '') . "',
                `meta_keyword`      = '" . $this->db->escape(isset($data['meta_keyword'][$lang_id]) ? $data['meta_keyword'][$lang_id] . ',' : '') . "'
            ";
        }

        if ($table == 'category') {
            return "
                `parent_id`         = '" . (int)$data['parent_id'] . "',
                `image`             = '" . $this->db->escape($data['image']) . "',
                `sort_order`        = '" . (int)$data['sort_order'] . "',
                `store_id`          = '" . (int)$this->module['store_id'] . "',
                `meta`              = '" . $this->db->escape(json_encode($data['meta'])) . "',
                `status`            = '" . (int)$data['status'] . "',
                `updated`           = NOW()
            ";
        }

        if ($table == 'category_content') {
            return "
                `category_id`       = '" . (int)$data['category_id'] . "',
                `language_id`       = '" . (int)$lang_id . "',
                `title`             = '" . $this->db->escape(isset($data['title'][$lang_id]) ? $data['title'][$lang_id] : '') . "',
                `content`           = '" . $this->db->escape(isset($data['content'][$lang_id]) ? $data['content'][$lang_id] : '') . "',
                `meta_title`        = '" . $this->db->escape(isset($data['meta_title'][$lang_id]) ? $data['meta_title'][$lang_id] : '') . "',
                `meta_description`  = '" . $this->db->escape(isset($data['meta_description'][$lang_id]) ? $data['meta_description'][$lang_id] : '') . "',
                `meta_keyword`      = '" . $this->db->escape(isset($data['meta_keyword'][$lang_id]) ? $data['meta_keyword'][$lang_id] : '') . "'
            ";
        }
    }

    public function deleteData($table, $id)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_" . $table . "` WHERE `" . $table . "_id` = " . (int)$id);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_" . $table . "_content` WHERE `" . $table . "_id` = " . (int)$id);
        $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_url_alias` WHERE `query` = '" . $table . "_id=" . (int)$id . "'");

        if ($table == 'category') {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "iblogs_" . $table . "_path` WHERE `category_id` = '" . (int)$id . "'");

            $query = $this->db->query("SELECT category_id FROM `" . DB_PREFIX . "iblogs_" . $table . "_path` WHERE `path_id` = '" . (int)$id . "'");

            foreach ($query->rows as $result) {
                $this->deleteData($table, $result['category_id']);
            }
        }
    }

    // ================================================================


    public function autocompletePost($param = array())
    {
        $items = array();
        $param['search'] = isset($param['search']) ? $param['search'] : '';
        $param['start']  = isset($param['start']) ? $param['start'] : 0;
        $param['limit']  = isset($param['limit']) ? $param['limit'] : 10;

        $results = $this->db->query(
            "SELECT post_id, title
            FROM `" . DB_PREFIX . "iblogs_post_content`
            WHERE language_id = " . (int)$this->config->get('config_language_id') . "
                AND title LIKE '%" . $this->db->escape($param['search']) . "%'
            ORDER BY title ASC
            LIMIT " . (int)$param['start'] . "," . (int)$param['limit']
        );

        foreach ($results->rows as $result) {
            $items[] = array(
                'post_id' => $result['post_id'],
                'name'    => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
            );
        }

        return $items;
    }

    public function getPostInId($ids)
    {
        if (!$ids || !is_array($ids)) {
            return array();
        }

        $results = $this->db->query("SELECT post_id, title FROM `" . DB_PREFIX . "iblogs_post_content` WHERE post_id IN (" . implode(',', $ids) . ") AND language_id = " . (int)$this->config->get('config_language_id'));

        return $results->rows;
    }

    public function getProductsInId($ids)
    {
        if (!$ids || !is_array($ids)) {
            return array();
        }

        $results = $this->db->query("SELECT product_id, name FROM `" . DB_PREFIX . "product_description` WHERE product_id IN (" . implode(',', $ids) . ") AND language_id = " . (int)$this->config->get('config_language_id'));

        return $results->rows;
    }

    // ================================================================

    public function install($drop = false)
    {
        if ($drop) {
            $this->uninstall();
        }

        if (!$this->checkTable('iblogs_post')) {
            $this->db->query(
                "CREATE TABLE `" . DB_PREFIX . "iblogs_category` (
                    `category_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `parent_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                    `image` VARCHAR(255) NULL DEFAULT NULL,
                    `sort_order` TINYINT(11) UNSIGNED NOT NULL DEFAULT '0',
                    `store_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                    `meta` TEXT NOT NULL COMMENT 'misc setting',
                    `status` TINYINT(1) UNSIGNED NOT NULL,
                    `created` DATETIME NOT NULL,
                    `updated` DATETIME NOT NULL,
                    PRIMARY KEY (`category_id`),
                    INDEX `parent_id` (`parent_id`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );
            $this->db->query(
                "CREATE TABLE `" . DB_PREFIX . "iblogs_category_content` (
                    `category_id` INT(11) UNSIGNED NOT NULL,
                    `language_id` INT(11) UNSIGNED NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `content` TEXT NOT NULL,
                    `meta_title` TEXT NOT NULL,
                    `meta_description` TEXT NOT NULL,
                    `meta_keyword` TEXT NOT NULL,
                    PRIMARY KEY (`category_id`, `language_id`),
                    INDEX `title` (`title`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );
            $this->db->query(
                "CREATE TABLE `" . DB_PREFIX . "iblogs_category_path` (
                    `category_id` INT(11) UNSIGNED NOT NULL,
                    `path_id` INT(11) UNSIGNED NOT NULL,
                    `level` INT(11) UNSIGNED NOT NULL,
                    PRIMARY KEY (`category_id`, `path_id`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );
            $this->db->query(
                "CREATE TABLE `" . DB_PREFIX . "iblogs_post` (
                    `post_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `author_id` INT(11) UNSIGNED NOT NULL,
                    `category_id` INT(11) UNSIGNED NOT NULL,
                    `categories` TEXT NOT NULL,
                    `image` VARCHAR(255) NULL DEFAULT NULL,
                    `sort_order` TINYINT(11) UNSIGNED NOT NULL DEFAULT '0',
                    `store_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                    `meta` TEXT NOT NULL COMMENT 'misc setting',
                    `is_featured` TINYINT(1) UNSIGNED NOT NULL,
                    `status` TINYINT(1) UNSIGNED NOT NULL,
                    `publish` DATETIME NOT NULL,
                    `unpublish` DATETIME NOT NULL,
                    `created` DATETIME NOT NULL,
                    `updated` DATETIME NOT NULL,
                    PRIMARY KEY (`post_id`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );
            $this->db->query(
                "CREATE TABLE `" . DB_PREFIX . "iblogs_post_content` (
                    `post_id` INT(11) UNSIGNED NOT NULL,
                    `language_id` INT(11) UNSIGNED NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `excerpt` TEXT NOT NULL,
                    `content` LONGTEXT NOT NULL,
                    `meta_title` TEXT NOT NULL,
                    `meta_description` TEXT NOT NULL,
                    `meta_keyword` TEXT NOT NULL,
                    PRIMARY KEY (`post_id`, `language_id`),
                    INDEX `title` (`title`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );
            $this->db->query(
                "CREATE TABLE `" . DB_PREFIX . "iblogs_url_alias` (
                    `alias_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `language_id` INT(11) NOT NULL,
                    `query` VARCHAR(255) NOT NULL,
                    `keyword` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`alias_id`),
                    INDEX `query` (`query`),
                    INDEX `keyword` (`keyword`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );
            $this->db->query(
                "CREATE TABLE `" . DB_PREFIX . "iblogs_log` (
                    `log_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `type` VARCHAR(255) NOT NULL,
                    `action` VARCHAR(255) NOT NULL,
                    `object_id` INT(11) UNSIGNED NOT NULL,
                    `store_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                    `meta` TEXT NOT NULL COMMENT 'misc setting',
                    `created` DATETIME NOT NULL,
                    PRIMARY KEY (`log_id`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );

            // Add layout
            $this->load->model('setting/store');
            $this->load->model('design/layout');

            $stores = array_merge(
                array(array('store_id' => '0')),
                $this->model_setting_store->getStores()
            );
            $layouts = array(
                'name'          => $this->module['title'] . ' 5',
                'layout_route'  => array()
            );

            foreach ($stores as $store) {
                $layouts['layout_route'][] = array(
                    'store_id'  => $store['store_id'],
                    'route'     => $this->module['path']
                );
            }

            $this->model_design_layout->addLayout($layouts);
        }
    }

    public function uninstall()
    {
        $this->db->query(
            "DROP TABLE IF EXISTS
                `" . DB_PREFIX . "iblogs_category`,
                `" . DB_PREFIX . "iblogs_category_content`,
                `" . DB_PREFIX . "iblogs_category_path`,
                `" . DB_PREFIX . "iblogs_post`,
                `" . DB_PREFIX . "iblogs_post_content`,
                `" . DB_PREFIX . "iblogs_url_alias`,
                `" . DB_PREFIX . "iblogs_log`"
        );

        // Remove layout
        $this->load->model('design/layout');

        $results = $this->db->query("SELECT DISTINCT layout_id FROM `" . DB_PREFIX . "layout_route` WHERE route LIKE '%" . $this->module['path'] . "%'")->rows;
        foreach ($results as $result) {
            $this->model_design_layout->deleteLayout($result['layout_id']);
        }
    }

    /**
     * Future database update
     */
    public function setup() {}

    /**
     * Migration from iBlog Legacy
     *
     * Important:
     * - Automatically detect if Legacy db table is available
     * - Incase iBlog legacy db not available, add them manually to migrate
     */
    public function migrate()
    {
        $pid = 0;
        $cid = 0;
        $category_map = array();

        if ($this->checkTable('iblog_categories_id')) {
            $categories = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblog_categories_id`");

            foreach ($categories->rows as $category) {
                $data = $category;

                $data['parent_id'] = 0;
                $data['url_alias'] = $data['slug'];
                // $data['status']    = 0;

                $category_content = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblog_categories` WHERE category_id = " . (int)$category['category_id']);

                foreach ($category_content->rows as $content) {
                    $data['title'][$content['language_id']]            = $content['name'];
                    $data['content'][$content['language_id']]          = $content['description'];
                    $data['meta_title'][$content['language_id']]       = $content['meta_title'];
                    $data['meta_description'][$content['language_id']] = $content['meta_description'];
                    $data['meta_keyword'][$content['language_id']]     = $content['meta_keywords'];
                }

                $data = array_merge($this->module['setting']['category'], $data);

                // Insert category
                $new_cat_id = $this->formCategory(0, $data);

                $category_map[$data['category_id']] = $new_cat_id;
                $cid++;
            }
        }

        if ($this->checkTable('iblog_post')) {
            $posts = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblog_post`");

            foreach ($posts->rows as $post) {
                $data = $post;

                $data['categories'] = array();
                $categories = unserialize($data['category_id']);
                foreach ($categories as $cat_id) {
                    if (isset($category_map[$cat_id])) {
                        $data['categories'][] = $category_map[$cat_id];
                    }
                }

                $data['category_id'] = 0;
                $data['url_alias']   = $data['slug'];
                $data['publish']     = $data['created'];
                $data['sort_order']  = $data['sort_order_post'];
                // $data['status']      = 0;

                $post_content = $this->db->query("SELECT * FROM `" . DB_PREFIX . "iblog_post_description` WHERE iblog_post_id = " . (int)$data['id']);

                foreach ($post_content->rows as $content) {
                    $data['title'][$content['language_id']]            = $content['title'];
                    $data['excerpt'][$content['language_id']]          = $content['excerpt'];
                    $data['content'][$content['language_id']]          = $content['body'];
                    $data['meta_title'][$content['language_id']]       = $content['title'];
                    $data['meta_description'][$content['language_id']] = $content['meta_description'];
                    $data['meta_keyword'][$content['language_id']]     = rtrim($content['meta_keywords'], ',');
                }

                $data = array_merge($this->module['setting']['post'], $data);

                // Insert post
                $new_cat_id = $this->formPost(0, $data);

                $pid++;
            }
        }

        return array(
            '{pid}' => $pid,
            '{cid}' => $cid,
        );
    }

    public function checkTable($table)
    {
        $tables = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "';");

        if ($tables->num_rows) {
            return true;
        }

        return false;
    }

    public function checkTableColumn($table, $column)
    {
        if ($this->checkTable($table)) {
            $results = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $column . "';");

            if ($results->num_rows) {
                return true;
            }
        }

        return false;
    }
}
