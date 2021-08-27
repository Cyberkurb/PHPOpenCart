<?php 
class ModelStoreSetting extends Model{
	public function getStorelist() {
		if($this->config->get('config_store_id') == 9){
			$store_id = 0;
		}
		elseif($this->config->get('config_store_id') == 7){
			$store_id = 1;
		}
		elseif($this->config->get('config_store_id') == 11){
			$store_id = 1;
		}
		elseif($this->config->get('config_store_id') == 10){
			$store_id = 0;
		}
		elseif($this->config->get('config_store_id') == 100){
			$store_id = 13;
		}
		else{
			$store_id = $this->config->get('config_store_id');
		}
 	    $store = array();   
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_list WHERE store_id = '" . (int)$store_id . "' AND status = '1';");

		if ($query->num_rows) {
			 foreach($query->rows as $key => $result) {   
			 	$store[$key] = $result;
			 }  	 
		return $store;
		} else {
			return null;	
		}
	}
}





?>