<?php
class ModelExtensionModuleContentVideo extends Model {
	
	public function isEnabled(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'content_video'");
		return $query->num_rows? true : false;
	}
}