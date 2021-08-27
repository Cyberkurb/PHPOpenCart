<?php
class ModelExtensionRCIExtensionFaq extends Model {

    public function getFaqs(){
        $query = $this->db->query("SELECT *  FROM `" . DB_PREFIX . "faq` f LEFT JOIN `" . DB_PREFIX . "faq_description` fd ON (f.faq_id = fd.faq_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND f.status = '1' ORDER BY f.sort_order ASC");

        return $query->rows;
    }

}