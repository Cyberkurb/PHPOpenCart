<?php
class ModelExtensionRCIExtensionSizing extends Model {

    public function getSizings(){
        $query = $this->db->query("SELECT *  FROM `" . DB_PREFIX . "sizing` f LEFT JOIN `" . DB_PREFIX . "sizing_description` fd ON (f.sizing_id = fd.sizing_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND f.status = '1' ORDER BY f.sort_order ASC");

        return $query->rows;
    }
	
    public function getProductSizings($product_id) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "sizing f 
					LEFT JOIN " . DB_PREFIX . "sizing_description fd ON (f.sizing_id = fd.sizing_id) 
					LEFT JOIN " . DB_PREFIX . "product_sizing_guides pf ON (f.sizing_id = pf.sizing_id)
					WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'
						AND product_id = '" . (int)$product_id . "'
						AND status = '1'";

		$query = $this->db->query($sql);

        $sizings = $query->rows;
		
		foreach($sizings as $key => $sizing) {
			$sizings[$key]["details"] = html_entity_decode($sizing["details"]);
		}
		
		return $sizings;
    }

}