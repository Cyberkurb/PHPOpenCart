<?php
class ModelExtensionRCIExtensionTechnology extends Model {

    public function getTechnologies(){
        $query = $this->db->query("SELECT *  FROM `" . DB_PREFIX . "technology` f LEFT JOIN `" . DB_PREFIX . "technology_description` fd ON (f.technology_id = fd.technology_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND f.status = '1' ORDER BY f.sort_order ASC");

        return $query->rows;
    }
	
    public function getProductTechnologies($product_id) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "technology f 
					LEFT JOIN " . DB_PREFIX . "technology_description fd ON (f.technology_id = fd.technology_id) 
					LEFT JOIN " . DB_PREFIX . "product_technologies pf ON (f.technology_id = pf.technology_id)
					WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'
						AND product_id = '" . (int)$product_id . "'
						AND status = '1'";

		$query = $this->db->query($sql);

        $technologies = $query->rows;
		
		foreach($technologies as $key => $technology) {
			$technologies[$key]["details"] = html_entity_decode($technology["details"]);
		}
		
		return $technologies;
    }

}