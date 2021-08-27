<?php
class ModelExtensionRCIExtensionTechnology extends Model {

    public function addTechnology($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "technology SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

        $technology_id = $this->db->getLastId();

        foreach ($data['technology_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "technology_description SET technology_id = '" . (int)$technology_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', details = '" . $this->db->escape($value['details']) . "'");
        }

        return $technology_id;
    }

    public function editTechnology($technology_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "technology SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE technology_id = '" . (int)$technology_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "technology_description WHERE technology_id = '" . (int)$technology_id . "'");

        foreach ($data['technology_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "technology_description SET technology_id = '" . (int)$technology_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', details = '" . $this->db->escape($value['details']) . "'");
        }
    }

    public function getTotalTechnologies($data = array()) {
        $sql = "SELECT COUNT(f.technology_id) AS total FROM " . DB_PREFIX . "technology f";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTechnology($technology_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "technology f LEFT JOIN " . DB_PREFIX . "technology_description fd ON (f.technology_id = fd.technology_id) WHERE f.technology_id = '" . (int)$technology_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getTechnologyDescriptions($technology_id) {
        $technology_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "technology_description WHERE technology_id = '" . (int)$technology_id . "'");

        foreach ($query->rows as $result) {
            $technology_description_data[$result['language_id']] = array(
                'title'    => $result['title'],
                'details'      => $result['details']
            );
        }

        return $technology_description_data;
    }

    public function getTechnologies($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "technology f LEFT JOIN " . DB_PREFIX . "technology_description fd ON (f.technology_id = fd.technology_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
            $sql .= " ORDER BY f.technology_id";
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
	
    public function getProductTechnologiesLinks($product_id) {
		
		$technologies = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_technologies  pf
										LEFT JOIN " . DB_PREFIX . "technology f on f.technology_id = pf.technology_id
										WHERE product_id = '" . (int)$product_id . "' 
											AND status = '1'");

		foreach ($query->rows as $result) {
			$technologies[] = $result['technology_id'];
		}

		return $technologies;
    }
	
    public function getProductTechnologies($product_id) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "technology f 
					LEFT JOIN " . DB_PREFIX . "technology_description fd ON (f.technology_id = fd.technology_id) 
					LEFT JOIN " . DB_PREFIX . "product_technologies pf ON (f.technology_id = pf.technology_id)
					WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'
						AND product_id = '" . (int)$product_id . "'
						AND status = '0'";
						
						print_r($sql);
						die("#");

		$query = $this->db->query($sql);

        return $query->rows;
    }

    public function deleteTechnology($technology_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "technology WHERE technology_id = '" . (int)$technology_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "technology_description WHERE technology_id = '" . (int)$technology_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "_product_technologies WHERE technology_id = '" . (int)$technology_id . "'");
    }

    public function install() {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "technology` (
				`technology_id` int(11) NOT NULL AUTO_INCREMENT,
                `status` tinyint(1) NOT NULL,
                `sort_order` int(3) NOT NULL,
				PRIMARY KEY (`technology_id`)
			)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "technology_description` (
				`technology_id` int(11) NOT NULL AUTO_INCREMENT,
                `language_id` int(11) NOT NULL,
                `title` varchar(255) NOT NULL,
                `details` text NOT NULL,
				PRIMARY KEY (`technology_id`)
			)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
			
        $this->db->query("
			CREATE TABLE `" . DB_PREFIX . "product_technologies` (
				`product_id` int(11) NOT NULL,
				`technology_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "technology`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "technology_description`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "_product_technologies`");
    }
}