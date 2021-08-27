<?php 
class ModelStoreLocator extends Model{
	//add store list 
 	public function addStore($data) { 
		$prepAddr = str_replace(' ','+',$data['store_address']);
        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyBDmAg2UBGsTtql7dj-aKxu8lfsxtADdzI&address='.$prepAddr.'&sensor=false');
		$output = json_decode($geocode);

        $latitude = $output->results[0]->geometry->location->lat;
		$longitude = $output->results[0]->geometry->location->lng;
		$formatted_address = $output->results[0]->formatted_address;
		if(isset($data['storelist_id']) && $data['storelist_id'] !== ''){
			$this->db->query("UPDATE " . DB_PREFIX . "store_list SET `store_address` = '" . $this->db->escape($formatted_address) . "', store_title= '" . $this->db->escape($data['store_title']) . "', store_mobile_no= '" . $this->db->escape($data['store_mobile_no']) . " ', `store_lat` = '" . $this->db->escape($latitude) . "', `store_long` = '" . $this->db->escape($longitude) . "', `date_added` = NOW(),  status = '" . $this->db->escape($data['store_status']) . "' WHERE storelist_id = " . (int)$data['storelist_id'] . ";");
		}
		else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_list SET store_id = " . (int)$data['store_id'] . ", store_title = '" . $this->db->escape($data['store_title']) . "', store_mobile_no= '" . $this->db->escape($data['store_mobile_no']) . "', store_address = '" . $this->db->escape($formatted_address) . "', store_lat = '" . $this->db->escape($latitude) . "', store_long = '" . $this->db->escape($longitude) . "', date_added = NOW() ,  status = '" . $this->db->escape($data['store_status']) . "';");
		}
		$store_id = $this->db->getLastId();
		$update_all_missing = $this->storesMissing();

		return $store_id;
	}
	//get all of the stores
	public function storesMissing() {
		$store = array();   
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_list WHERE store_lat is null");

	if ($query->num_rows) {
			foreach($query->rows as $key => $result) {   
				$prepAddr = str_replace(' ','+',$result['store_address']);
				$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyBDmAg2UBGsTtql7dj-aKxu8lfsxtADdzI&address='.$prepAddr.'&sensor=false');
				$output= json_decode($geocode);
				
				$latitude = $output->results[0]->geometry->location->lat;
				$longitude = $output->results[0]->geometry->location->lng;
				$formatted_address = $output->results[0]->formatted_address;
				
				$this->db->query("UPDATE " . DB_PREFIX . "store_list SET `store_address` = '" . $this->db->escape($formatted_address) . "', `store_lat` = '" . $this->db->escape($latitude) . "', `store_long` = '" . $this->db->escape($longitude) . "', `date_added` = NOW() WHERE storelist_id = " . (int)$result['storelist_id'] . ";");
				
			}  	 
	return $store;
	} else {
		return null;	
	}
	}

	
    public function getFilteredStoreList($data) {
        $sql = "SELECT * FROM " . DB_PREFIX . "store_list sl";
        $sql .= " WHERE sl.store_lat <> ''";
        
        if (!empty($data['filter_dealer'])) {
            $sql .= " AND sl.store_title LIKE '" . $this->db->escape($data['filter_dealer']) . "%'";
        }
        if ($data['filter_brand'] != 9999999) {
            $sql .= " AND sl.store_id = " . (int)$data['filter_brand'];
        }
        if (!empty($data['filter_address'])) {
            $sql .= " AND sl.store_address LIKE '" . $this->db->escape($data['filter_address']) . "%'";
        }
        if (!empty($data['filter_phone'])) {
            $sql .= " AND sl.store_mobile_no LIKE '" . $this->db->escape($data['filter_phone']) . "%'";
        }
        

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);
        //return $sql;
        return $query->rows;
    }




   //get all of the stores
 	public function getStorelist($data = array()) {
 	    $store = array();   
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_list");

		if ($query->num_rows) {
			 foreach($query->rows as $key => $result) {   
			 	$store[$key] = $result;
			 }  	 
		return $store;
		} else {
			return null;	
		}
	}	

	//get store details
 	public function getstoreDetail($id) {
 	    $store = array();   
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_list WHERE storelist_id = ".$id);

		if ($query->num_rows) {
			 foreach($query->rows as $key => $result) {   
			 	$store[$key] = $result;
			 } 	 
		return $store;
		} else {
			return null;	
		}
	}

	//delete store 
	public function deleteStore($id) { 
		 $query = $this->db->query("DELETE FROM `" . DB_PREFIX . "store_list` WHERE store_id = '" . (int)$this->db->escape($id). "'"); 
		 if($query){ return true; }
	}

    public function getTotalstoreList($data) {
        $sql = "SELECT COUNT(*) as total"; 
        $sql .= " FROM " . DB_PREFIX . "store_list sl";
        $sql .= " WHERE sl.store_lat <> ''";
        if (!empty($data['filter_dealer'])) {
            $sql .= " AND sl.store_title LIKE '" . $this->db->escape($data['filter_dealer']) . "%'";
        }
        if ($data['filter_brand'] != 9999999) {
            $sql .= " AND sl.store_id = " . (int)$data['filter_brand'];
        }
        if (!empty($data['filter_address'])) {
            $sql .= " AND sl.store_address LIKE '" . $this->db->escape($data['filter_address']) . "%'";
        }
        if (!empty($data['filter_phone'])) {
            $sql .= " AND sl.store_mobile_no LIKE '" . $this->db->escape($data['filter_phone']) . "%'";
        }
        $query = $this->db->query($sql);
        return $query->row['total'];
    }










}







?>