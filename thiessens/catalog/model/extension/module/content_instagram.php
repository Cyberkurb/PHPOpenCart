<?php
class ModelExtensionModuleContentInstagram extends Model {
	
	public function isEnabled(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'content_instagram'");
		return $query->num_rows? true : false;
	}
}