<?php
class ModelExtensionRCIExtensionFaq extends Model {

    public function addFaq($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "faq SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

        $faq_id = $this->db->getLastId();

        foreach ($data['faq_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET faq_id = '" . (int)$faq_id . "', language_id = '" . (int)$language_id . "', question = '" . $this->db->escape($value['question']) . "', answer = '" . $this->db->escape($value['answer']) . "'");
        }

        return $faq_id;
    }

    public function editFaq($faq_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "faq SET status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE faq_id = '" . (int)$faq_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");

        foreach ($data['faq_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET faq_id = '" . (int)$faq_id . "', language_id = '" . (int)$language_id . "', question = '" . $this->db->escape($value['question']) . "', answer = '" . $this->db->escape($value['answer']) . "'");
        }
    }

    public function getTotalFaqs($data = array()) {
        $sql = "SELECT COUNT(f.faq_id) AS total FROM " . DB_PREFIX . "faq f";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getFaq($faq_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq f LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.faq_id = fd.faq_id) WHERE f.faq_id = '" . (int)$faq_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getFaqDescriptions($faq_id) {
        $faq_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");

        foreach ($query->rows as $result) {
            $faq_description_data[$result['language_id']] = array(
                'question'    => $result['question'],
                'answer'      => $result['answer']
            );
        }

        return $faq_description_data;
    }

    public function getFaqs($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "faq f LEFT JOIN " . DB_PREFIX . "faq_description fd ON (f.faq_id = fd.faq_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'fd.question',
            'f.status',
            'f.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY f.faq_id";
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

    public function deleteFaq($faq_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "faq WHERE faq_id = '" . (int)$faq_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");
    }

    public function install() {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "faq` (
				`faq_id` int(11) NOT NULL AUTO_INCREMENT,
                `status` tinyint(1) NOT NULL,
                `sort_order` int(3) NOT NULL,
				PRIMARY KEY (`faq_id`)
			)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "faq_description` (
				`faq_id` int(11) NOT NULL AUTO_INCREMENT,
                `language_id` int(11) NOT NULL,
                `question` varchar(255) NOT NULL,
                `answer` text NOT NULL,
				PRIMARY KEY (`faq_id`)
			)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "faq`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "faq_description`");
    }
}