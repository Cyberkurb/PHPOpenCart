<?php
class Thiessens_ModelCatalogInformation extends ModelCatalogInformation {
	
	public function addInformation($data) {
		
		$information_id = parent::addInformation($data);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "information_to_layout_style SET layout_style = '" . $data['information_layout_style'] . "', information_id = '" . (int)$information_id . "'");
				
		return $information_id;
	}

	public function editInformation($information_id, $data) {
		
		parent::editInformation($information_id, $data);
		
		$this->db->query("UPDATE " . DB_PREFIX . "information_to_layout_style SET layout_style = '" . $data['information_layout_style'] . "' WHERE information_id = '" . (int)$information_id . "'");
	}

	public function deleteInformation($information_id) {
		
		parent::deleteInformation($information_id);
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "information_to_layout_style` WHERE information_id = '" . (int)$information_id . "'");
	}
	
	public function getInformationLayoutStyle($information_id) {
		
		$layout_style = "default";
		
		$style = $this->db->query("SELECT layout_style FROM " . DB_PREFIX . "information_to_layout_style WHERE information_id = '" . (int)$information_id . "' LIMIT 1");
		
		if(!empty($style->row) && isset($style->row["layout_style"]) && !empty($style->row["layout_style"])) {
			$layout_style = $style->row["layout_style"];
		}
		
		return $layout_style;
	}
}