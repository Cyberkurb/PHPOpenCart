<?php
class ModelExtensionRCIExtensionSizing extends Model {

    public function addSizing($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "sizing SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

        $sizing_id = $this->db->getLastId();

        foreach ($data['sizing_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "sizing_description SET sizing_id = '" . (int)$sizing_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', details = '" . $this->db->escape($value['details']) . "'");
        }

        return $sizing_id;
    }

    public function editSizing($sizing_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "sizing SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE sizing_id = '" . (int)$sizing_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "sizing_description WHERE sizing_id = '" . (int)$sizing_id . "'");

        foreach ($data['sizing_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "sizing_description SET sizing_id = '" . (int)$sizing_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', details = '" . $this->db->escape($value['details']) . "'");
        }
    }

    public function getTotalSizings($data = array()) {
        $sql = "SELECT COUNT(f.sizing_id) AS total FROM " . DB_PREFIX . "sizing f";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getSizing($sizing_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sizing f LEFT JOIN " . DB_PREFIX . "sizing_description fd ON (f.sizing_id = fd.sizing_id) WHERE f.sizing_id = '" . (int)$sizing_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getSizingDescriptions($sizing_id) {
        $sizing_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sizing_description WHERE sizing_id = '" . (int)$sizing_id . "'");

        foreach ($query->rows as $result) {
            $sizing_description_data[$result['language_id']] = array(
                'title'    => $result['title'],
                'details'      => $result['details']
            );
        }

        return $sizing_description_data;
    }

    public function getSizings($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "sizing f LEFT JOIN " . DB_PREFIX . "sizing_description fd ON (f.sizing_id = fd.sizing_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'fd.title',
            'f.status',
            'f.sort_order'
        );

        if (isset($data['filter_title']) && !empty($data['filter_title'])) {
			$sql .= " AND fd.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
        }
		
        if (isset($data['status']) && !empty($data['status'])) {
			$sql .= " AND f.status = '" . $this->db->escape($data['status']) . "'";
        }
		
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY f.sizing_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
		
        $query = $this->db->query($sql);

        return $query->rows;
    }
	
    public function getProductSizingsLinks($product_id) {
		
		$sizing_guides = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_sizing_guides  pf
										LEFT JOIN " . DB_PREFIX . "sizing f on f.sizing_id = pf.sizing_id
										WHERE product_id = '" . (int)$product_id . "' 
											AND status = '1'");

		foreach ($query->rows as $result) {
			$sizing_guides[] = $result['sizing_id'];
		}

		return $sizing_guides;
    }
	
    public function getProductSizings($product_id) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "sizing f 
					LEFT JOIN " . DB_PREFIX . "sizing_description fd ON (f.sizing_id = fd.sizing_id) 
					LEFT JOIN " . DB_PREFIX . "product_sizing_guides pf ON (f.sizing_id = pf.sizing_id)
					WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'
						AND product_id = '" . (int)$product_id . "'
						AND status = '0'";
						
						print_r($sql);
						die("#");

		$query = $this->db->query($sql);

        return $query->rows;
    }

    public function deleteSizing($sizing_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "sizing WHERE sizing_id = '" . (int)$sizing_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "sizing_description WHERE sizing_id = '" . (int)$sizing_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "_product_sizing_guides WHERE sizing_id = '" . (int)$sizing_id . "'");
    }

    public function install() {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sizing` (
				`sizing_id` int(11) NOT NULL AUTO_INCREMENT,
                `status` tinyint(1) NOT NULL,
                `sort_order` int(3) NOT NULL,
				PRIMARY KEY (`sizing_id`)
			)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sizing_description` (
				`sizing_id` int(11) NOT NULL AUTO_INCREMENT,
                `language_id` int(11) NOT NULL,
                `title` varchar(255) NOT NULL,
                `details` text NOT NULL,
				PRIMARY KEY (`sizing_id`)
			)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
			
        $this->db->query("
			CREATE TABLE `" . DB_PREFIX . "_product_sizing_guides` (
				`product_id` int(11) NOT NULL,
				`sizing_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sizing`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sizing_description`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "_product_sizing_guides`");
    }
}