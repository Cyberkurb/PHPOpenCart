<?php
class ModelExtensionModuleFeaturedBrands extends Model {
	
	public function isEnabled(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'featured_brands'");
		return $query->num_rows? true : false;
	}
}