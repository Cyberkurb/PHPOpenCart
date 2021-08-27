<?php 
class ModelExtensionModuleiProductVideo extends Model {
    private $db_column = 'group';
    private $modulePathVideo = 'image/catalog/iproductvideo/';

    public function __construct($register) {
        parent::__construct($register);

        $db_column_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting LIMIT 1");

        if (isset($db_column_query->row['code'])) {
            $this->db_column = 'code';
        }
    }

    public function getSetting($group, $store_id = 0) {
        $data = array(); 

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `" . $this->db_column . "` = '" . $this->db->escape($group) . "'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $data[$result['key']] = $result['value'];
            } else {
                $data[$result['key']] = unserialize($result['value']);
            }
        }

        return $data;
    }

    public function updateVideos($store_id = 0, $videos = array(), $page = 1, $limit = 5, $filter_name = '', $filter_product_id = 0) {
        $page_videos = $this->getVideos($store_id, $page, $limit, $filter_name, $filter_product_id);
        $deleted_video_ids = array();
        $updated_videos = array();
        $new_videos = array();

        foreach ($page_videos as $video_id => $video) {
            if (empty($videos[$video_id])) {
                $deleted_video_ids[] = $video_id;
            } else {
                $updated_videos[$video_id] = $videos[$video_id];
            }
        }

        foreach ($videos as $video_id => $video) {
            if (empty($page_videos[$video_id])) {
                $new_videos[] = $video;
            }
        }

        if (!empty($deleted_video_ids)) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "iproductvideo WHERE video_id IN (".implode(',', $deleted_video_ids).")");
            $this->db->query("DELETE FROM " . DB_PREFIX . "iproductvideo_description WHERE video_id IN (".implode(',', $deleted_video_ids).")");
            $this->db->query("DELETE FROM " . DB_PREFIX . "iproductvideo_meta WHERE video_id IN (".implode(',', $deleted_video_ids).")");
        }

        foreach ($updated_videos as $video_id => $video) {
            $this->updateVideo($video_id, $video);
        }

        foreach ($new_videos as $video) {
            $this->insertVideo($store_id, $video);
        }
    }

    public function updateVideo($video_id, $video) {
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        $video_title = $video['title'];
        $this->db->query("UPDATE " . DB_PREFIX . "iproductvideo SET title='$video_title' WHERE video_id=" . (int)$video_id);

        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            if (!empty($video[$language_id])) {
                $info = $video[$language_id];

                $res = $this->db->query("SELECT description_id FROM " . DB_PREFIX . "iproductvideo_description WHERE video_id=" . (int)$video_id . " AND language_id=" . (int)$language_id);
                $description_id = !empty($res->row['description_id']) ? $res->row['description_id'] : 0;

                if (!empty($description_id)) {
                    $sql = "UPDATE " . DB_PREFIX . "iproductvideo_description SET main_image=" . (!empty($info['MainImage']) ? (int)$info['MainImage'] : 0) . ", sort_order=" . (int)$info['SortOrder'] . ", type='" . $this->db->escape((!empty($info['VideoType']) ? $info['VideoType'] : 'internet')) . "', url='" . $this->db->escape((!empty($info['VideoURL']) ? $info['VideoURL'] : '')) . "', local_video='" . $this->db->escape((!empty($info['LocalVideo']) ? $info['LocalVideo'] : '')) . "', link_to_products='" . $this->db->escape($info['LimitProducts']) . "' WHERE description_id=" . (int)$description_id;
                    $this->db->query($sql);
                } else {
                    $sql = "INSERT INTO " . DB_PREFIX . "iproductvideo_description(video_id, language_id, main_image, sort_order, type, url, local_video, link_to_products) VALUES(" . (int)$video_id . ", " . (int)$language_id . ", " . (!empty($info['MainImage']) ? (int)$info['MainImage'] : 0) . ", " . (int)$info['SortOrder'] . ", '" . $this->db->escape((!empty($info['VideoType']) ? $info['VideoType'] : 'internet')) .  "', '" . $this->db->escape((!empty($info['VideoURL']) ? $info['VideoURL'] : '')) . "', '" . $this->db->escape((!empty($info['LocalVideo']) ? $info['LocalVideo'] : '')) . "', '" . $this->db->escape($info['LimitProducts']) . "')";
                    $this->db->query($sql);
                    $description_id = $this->db->getLastId();
                }

                $this->db->query("DELETE FROM " . DB_PREFIX . "iproductvideo_meta WHERE description_id=" . (int)$description_id);
                if (!empty($info['LimitProductsList']) && is_array($info['LimitProductsList'])) {
                    foreach ($info['LimitProductsList'] as $product_id) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "iproductvideo_meta(description_id, video_id, product_id) VALUES(" . (int)$description_id . ", " . (int)$video_id . ", " . (int)$product_id . ")");
                    }
                }
            }
        }
    }

    public function insertVideo($store_id, $video) {
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        $video_title = !empty($video['title']) ? $video['title'] : ('Video ' . date('Y-m-d H:i:s'));

        $sql = "INSERT INTO " . DB_PREFIX . "iproductvideo(store_id, title) VALUES(" . (int)$store_id . ", '" . $this->db->escape($video_title) . "')";
        $this->db->query($sql);
        $new_video_id = $this->db->getLastId();

        foreach ($languages as $language) {
            $language_id = $language['language_id'];
            if (!empty($video[$language_id])) {
                $info = $video[$language_id];

                $sql = "INSERT INTO " . DB_PREFIX . "iproductvideo_description(video_id, language_id, main_image, sort_order, type, url, local_video, link_to_products) VALUES(" . (int)$new_video_id . ", " . (int)$language_id . ", " . (!empty($info['MainImage']) ? (int)$info['MainImage'] : 0) . ", " . (int)$info['SortOrder'] . ", '" . $this->db->escape((!empty($info['VideoType']) ? $info['VideoType'] : 'internet')) .  "', '" . $this->db->escape((!empty($info['VideoURL']) ? $info['VideoURL'] : '')) . "', '" . $this->db->escape((!empty($info['LocalVideo']) ? $info['LocalVideo'] : '')) . "', '" . $this->db->escape($info['LimitProducts']) . "')";
                $this->db->query($sql);
                $new_descr_id = $this->db->getLastId();

                if (!empty($info['LimitProductsList']) && is_array($info['LimitProductsList'])) {
                    foreach ($info['LimitProductsList'] as $product_id) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "iproductvideo_meta(description_id, video_id, product_id) VALUES(" . (int)$new_descr_id . ", " . (int)$new_video_id . ", " . (int)$product_id . ")");
                    }
                }
            }
        }
    }

    public function getVideos($store_id = 0, $page = 1, $limit = 5, $filter_name = '', $filter_product_id) {
        $start = ($page-1)*$limit;
        $query = "SELECT * FROM (SELECT tmp.* FROM " . DB_PREFIX . "iproductvideo AS tmp";

        if (!empty($filter_product_id)) {
            $query .= " RIGHT JOIN " . DB_PREFIX . "iproductvideo_meta tmp2 ON (tmp.video_id=tmp2.video_id AND tmp2.product_id = " . (int)$filter_product_id . ")";
        }

        $query .= " WHERE store_id =". (int)$store_id;

        if (!empty($filter_name)) {
            $query .= " AND title LIKE '%" . $this->db->escape($filter_name) . "%'";
        }

        $query .= " LIMIT " . (int)$start . ", " . (int)$limit . ") AS ipv LEFT JOIN " . DB_PREFIX . "iproductvideo_description AS ipvd ON (ipv.video_id=ipvd.video_id)";
        $results = $this->db->query($query);

        $oldVideoPath = false;
        if (file_exists(substr(DIR_APPLICATION, 0, strrpos(DIR_APPLICATION, '/', -2)) . '/' . 'image/iproductvideo/')) {
            $oldVideoPath = true;
        }

        $videos = array();
        foreach ($results->rows as $row) {
            if (empty($videos[$row['video_id']])) {
                $videos[$row['video_id']] = array(
                    'title' => $row['title']
                );
            }
            $videos[$row['video_id']][$row['language_id']] = array(
                'VideoType' => $row['type'],
                'VideoURL' => $row['url'],
                'MainImage' => $row['main_image'],
                'SortOrder' => $row['sort_order'],
                'LimitProducts' => $row['link_to_products']
            );

            if (!empty($row['local_video'])) {

                $videos[$row['video_id']][$row['language_id']]['LocalVideo'] = $oldVideoPath ? $row['local_video'] : str_replace('image/iproductvideo/', $this->modulePathVideo, $row['local_video']);
            }

            if ($row['link_to_products'] == 'specific') {
                $products = $this->db->query("SELECT * FROM " . DB_PREFIX . "iproductvideo_meta WHERE description_id=" . (int)$row['description_id']);

                $videos[$row['video_id']][$row['language_id']]['LimitProductsList'] = array();
                foreach ($products->rows as $product) {
                    $videos[$row['video_id']][$row['language_id']]['LimitProductsList'][] = $product['product_id'];
                }
            }
        }
        return $videos;
    }

    public function getTotalVideos($store_id = 0, $filter_name = '', $filter_product_id = 0) {
        $query = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "iproductvideo AS tmp";

        if (!empty($filter_product_id)) {
            $query .= " RIGHT JOIN " . DB_PREFIX . "iproductvideo_meta tmp2 ON (tmp.video_id=tmp2.video_id AND tmp2.product_id = " . (int)$filter_product_id . ")";
        }

        $query .= " WHERE store_id=" . $store_id;

        if (!empty($filter_name)) {
            $query .= " AND title LIKE '%" . $this->db->escape($filter_name) . "%'";
        }
        $res = $this->db->query($query);
        return (int)$res->row['total'];
    }

    public function getLastVideoId($store_id = 0) {
        $res = $this->db->query("SELECT MAX(video_id) AS max_id FROM " . DB_PREFIX . "iproductvideo WHERE store_id=" . (int)$store_id);
        return (int)$res->row['max_id'];
    }

    public function editSetting($group, $data, $store_id = 0) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `" . $this->db_column . "` = '" . $this->db->escape($group) . "'");

        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `" . $this->db_column . "` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `" . $this->db_column . "` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
            }
        }
    }

    public function deleteSetting($group, $store_id = 0) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `" . $this->db_column . "` = '" . $this->db->escape($group) . "'");
    }

    public function returnMaxUploadSize($readable = false) {
        $upload = $this->return_bytes(ini_get('upload_max_filesize'));
        $post = $this->return_bytes(ini_get('post_max_size'));

        if ($upload >= $post) return $readable ? $this->sizeToString($post - 524288) : $post - 524288;
        else return $readable ? $this->sizeToString($upload) : $upload;
    }

    private function return_bytes($val) { //from http://php.net/manual/en/function.ini-get.php
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);

        switch($last) {
            case 'g':
                $val = substr($val, 0, strlen($val)-1) * (1024 * 1024 * 1024);
            case 'm':
                $val = substr($val, 0, strlen($val)-1) * (1024 * 1024);
            case 'k':
                $val = substr($val, 0, strlen($val)-1) * 1024;
        }

        return $val;
    }

    private function sizeToString($size) {
        $count = 0;
        for ($i = $size; $i >= 1024; $i /= 1024) $count++;

        switch ($count) {
            case 0 : $suffix = ' B'; break;
            case 1 : $suffix = ' KB'; break;
            case 2 : $suffix = ' MB'; break;
            case 3 : $suffix = ' GB'; break;
            case ($count >= 4) : $suffix = ' TB'; break;
        }
        return round($i, 2).$suffix;
    }

    public function install() {
        $this->create_db();
    }

    public function uninstall() {
        $this->drop_db();
    }

    public function create_db() {
        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "iproductvideo (
            video_id int AUTO_INCREMENT,
            PRIMARY KEY(video_id),
            store_id int NOT NULL,
            title varchar(255) NOT NULL
        )");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "iproductvideo_description (
            description_id int AUTO_INCREMENT,
            PRIMARY KEY(description_id),
            video_id int NOT NULL,
            language_id int NOT NULL,
            main_image tinyint(1) DEFAULT 0,
            sort_order int DEFAULT 0,
            type varchar(255) NOT NULL DEFAULT 'internet',
            url varchar(255),
            local_video varchar(255),
            link_to_products varchar(64) DEFAULT 'all',
            thumb varchar(255)
        )");

        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "iproductvideo_meta (
            id int AUTO_INCREMENT,
            PRIMARY KEY(id),
            description_id int NOT NULL,
            video_id int NOT NULL,
            product_id int
        )");
    }

    public function drop_db() {
        $this->db->query("DROP TABLE " . DB_PREFIX . "iproductvideo");
        $this->db->query("DROP TABLE " . DB_PREFIX . "iproductvideo_description");
        $this->db->query("DROP TABLE " . DB_PREFIX . "iproductvideo_meta");
    }

    public function migrate_data() {
        $this->load->model('setting/store');
        $stores = array_merge(array
            (0 => array(
                'store_id' => '0',
                'name' => $this->config->get('config_name') . ' (' .$this->data['text_default'] . ')',
                'url' => NULL, 'ssl' => NULL)
            ),
            $this->model_setting_store->getStores()
        );

        $store_id = 0;
        $data = array(); 
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `" . $this->db_column . "` = 'iproductvideo'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $data[$result['key']] = $result['value'];
            } else {
                $data[$result['key']] = unserialize($result['value']);
            }
        }

        foreach ($stores as $store) {
            $store_id = $store['store_id'];
            if (!empty($data['iProductVideo'][$store_id]['Videos'])) {
                $videos = $data['iProductVideo'][$store_id]['Videos'];
                foreach ($videos as $video) {
                    $this->insertVideo($store_id, $video);
                }
            }
        }
    }

    public function remove_product_video($product_ids) {
    	if (count($product_ids) != 0) {
    		$this->db->query("DELETE FROM " . DB_PREFIX . "iproductvideo_meta WHERE product_id IN (" . implode(',', $product_ids) . ')');
    	}
    }
}
