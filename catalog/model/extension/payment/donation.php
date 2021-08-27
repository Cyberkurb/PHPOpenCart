<?php 
class ModelExtensionPaymentDonation extends Model {
  	public function getMethod($address, $total) { 
		$this->load->language('extension/payment/payfabric');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_payfabric_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('payment_payfabric_total') > 0 && $this->config->get('payment_payfabric_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_payfabric_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'donation',
        		'title'      => 'Donation Order',
				'terms'      => '',	
				'sort_order' => 3
      		);
    	}
   
    	return $method_data;
	  }
	public function userValidation($username, $casenumber, $order_id){
        $casenumber = 'DWS'.$order_id;
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND status = 1;");
		if($casenumber != ''){

			$ch = curl_init("https://crmorder.pitboss-grills.com/case-accountv2.php?contact_id=".(int)$this->customer->getId()."&casenumber=" . $this->db->escape($casenumber));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ch);
			curl_close($ch);
		}

		$this->db->query("UPDATE " . DB_PREFIX . "order SET username = '" . $this->db->escape($username) . "', casenumber = '" . $this->db->escape($casenumber) . "' WHERE order_id = '" . (int)$order_id . "';");
		
			$approved = 1;
		return $approved;
	}

	public function userValidationOnly($username){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND status = 1;");
		
			$approved = 1;
		return $approved;
	}

	public function userGroup($username){
		$query = $this->db->query("SELECT user_group_id FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND status = 1;");
		
		return $query->row['user_group_id'];
	}
}
?>