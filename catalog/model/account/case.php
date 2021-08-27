<?php
class ModelAccountCase extends Model {
	public function addCase($caseinfo) {

		if((int)$this->config->get('config_store_id') == 0){
			$brand_name = 'pit_boss';
		}
		elseif((int)$this->config->get('config_store_id') == 9){
			$brand_name = 'pit_boss';
		}
		elseif((int)$this->config->get('config_store_id') == 10){
			$brand_name = 'pit_boss';
		}
		elseif((int)$this->config->get('config_store_id') == 1){
			$brand_name = 'louisiana_grills';
		}
		elseif((int)$this->config->get('config_store_id') == 11){
			$brand_name = 'louisiana_grills';
		}
		elseif((int)$this->config->get('config_store_id') == 13){
			$brand_name = 'country_smoker';
		}
		elseif((int)$this->config->get('config_store_id') == 2){
			$brand_name = 'surelock';
		}
		elseif((int)$this->config->get('config_store_id') == 15){
			$brand_name = 'amazen';
		}
		else{
			$brand_name = 'pit_boss';
		}


		if($caseinfo['troubleshooting_issue'] == ''){
			$troubleshooting = 'Other';
		}
		elseif($caseinfo['troubleshooting_issue'] == 'Auger_Not_feeding_pellets'){
			$troubleshooting = 'Auger';
		}
		elseif($caseinfo['troubleshooting_issue'] == 'Controller_No_power_to_unit'){
			$troubleshooting = 'Controller';
		}
		elseif($caseinfo['troubleshooting_issue'] == 'Missing_Pieces_Other'){
			$troubleshooting = 'Other';
		}
		elseif($caseinfo['troubleshooting_issue'] == 'Fan_Other'){
			$troubleshooting = 'Fan';
		}
		elseif($caseinfo['troubleshooting_issue'] == 'Damage_Other'){
			$troubleshooting = 'Damage';
		}
		elseif($caseinfo['troubleshooting_issue'] == 'Controller_Other'){
			$troubleshooting = 'Controller';
		}
		elseif($caseinfo['troubleshooting_issue'] == 'Ignitor_Not_getting_power'){
			$troubleshooting = 'Ignitor';
		}
		elseif($caseinfo['troubleshooting_issue'] == 'Other_Other_Issue'){
			$troubleshooting = 'Other';
		}
		else{
			$troubleshooting = '';
		}
        
        $sql = "INSERT INTO " . DB_PREFIX . "case SET ";
		$sql .= "customer_id = '" . (int)$caseinfo['customer_id'] . "', ";
		$sql .= "store_id = '" . (int)$this->config->get('config_store_id') . "', ";
        $sql .= "title = '" . $this->db->escape($caseinfo['title']) . "', ";
        $sql .= "type = '" . $this->db->escape($caseinfo['type']) . "', ";
        $sql .= "status = 'Open', ";
        $sql .= "priority = 'Medium', ";
		$sql .= "brand = '" . $this->db->escape($brand_name) . "', ";
		$sql .= "product_id = '" . (int)$caseinfo['product_id'] . "', ";
		$sql .= "troubleshooting = '" . $this->db->escape($troubleshooting) . "', ";
		$sql .= "description = '" . $this->db->escape($caseinfo['description']) . "', ";
		$sql .= "troubleshooting_issue = '" . $this->db->escape($caseinfo['troubleshooting_issue']) . "', deleted = 0, date_added = now();";
		
		$query = $this->db->query($sql);
		
		$ch = curl_init("https://newcrm.dansonscorp.com/integration/website/wscasetocrm.php?case_id=".$this->db->getLastId());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);

		return $this->db->getLastId();
	}

	public function addCaseNote($note) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "case_note SET case_id = '" . (int)$note['case_id'] . "', note = '" . $this->db->escape($note['note']) . "', customer_id = '" . (int)$note['customer_id'] . "', date_added = NOW();");

		$ch = curl_init("https://newcrm.dansonscorp.com/integration/website/wsnotestocrm.php?case_note_id=".$this->db->getLastId());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);

		return $this->db->getLastId();
	}

	public function addCaseImage($case_id, $customer_id, $filename) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "case_image SET case_id = '" . (int)$case_id . "', filename = '" . $this->db->escape($filename) . "', customer_id = '" . (int)$customer_id . "', date_added = NOW(), deleted = 0;");

		$ch = curl_init("https://newcrm.dansonscorp.com/integration/website/wsimgtocrm.php?image_id=".$this->db->getLastId());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);
		return $this->db->getLastId();
	}

	public function getOpenCases($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case WHERE customer_id = '" . (int)$customer_id . "' AND status in ('Open')");

		return $query->rows;
	}

	public function getClosedCases($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case WHERE customer_id = '" . (int)$customer_id . "' AND status in ('Closed')");

		return $query->rows;
	}

	public function getCase($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case WHERE case_id = '" . (int)$case_id . "' AND deleted <> 1 AND customer_id = '" . (int)$this->customer->getId() . "';");

		return $query->row;
	}

	public function getCaseNotes($case_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_note WHERE case_id = '" . (int)$case_id . "';");

		return $query->rows;
	}

	public function getRegProducts($customer_id){
		$query = $this->db->query("SELECT cp.product_id AS product_id, cp.serialnumber AS serialnumber, pd.name AS productname FROM " . DB_PREFIX . "customer_product cp JOIN " . DB_PREFIX . "product_description pd ON pd.product_id = cp.purchaseproduct_id WHERE cp.customer_id = '" . (int)$customer_id . "';");

		return $query->rows;
	}

	public function getImages($case_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "case_image WHERE case_id = '" . (int)$case_id . "' AND deleted = 0;");

		return $query->rows;
	}

	public function delImage($image_id){
		$query = $this->db->query("UPDATE " . DB_PREFIX . "case_image SET deleted = 1 WHERE image_id = '" . (int)$image_id . "';");

		return $query->row;
	}
	
}
