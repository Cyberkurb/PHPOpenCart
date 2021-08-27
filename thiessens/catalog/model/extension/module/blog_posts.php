<?php
class ModelExtensionModuleBlogPosts extends Model {
	
	public function isEnabled(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'blog_posts'");
		return $query->num_rows? true : false;
	}
}