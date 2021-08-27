<?php 
class ModelExtensionPaymentCashCheckout extends Model {
  	public function getMethod($address, $total) { 
		$this->load->language('extension/payment/payfabric');
		
      		$method_data = array( 
        		'code'       => 'cash_checkout',
        		'title'      => 'Cash Checkout',
				'terms'      => '',	
				'sort_order' => 999
      		);
    	return $method_data;
	  }
      public function userValidation($username, $casenumber, $rectype, $receipt_detail, $order_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND status = 1;");
        
        if($rectype == 1){
            $this->db->query("UPDATE " . DB_PREFIX . "order SET username = '" . $this->db->escape($username) . "', casenumber = '" . $this->db->escape($casenumber) . "', telephone = '" . $this->db->escape($receipt_detail) . "', special_receipt = " . (int)$rectype . " WHERE order_id = '" . (int)$order_id . "';");
        }
        elseif($rectype == 2){
            $this->db->query("UPDATE " . DB_PREFIX . "order SET username = '" . $this->db->escape($username) . "', casenumber = '" . $this->db->escape($casenumber) . "', email = '" . $this->db->escape($receipt_detail) . "', special_receipt = " . (int)$rectype . " WHERE order_id = '" . (int)$order_id . "';");
        }
        else{
            $this->db->query("UPDATE " . DB_PREFIX . "order SET username = '" . $this->db->escape($username) . "', casenumber = '" . $this->db->escape($casenumber) . "' WHERE order_id = '" . (int)$order_id . "';");
        }
		
		if ($query->num_rows) {
			$approved = 1;
		}
		else{
			$approved = 0;
		}
		return $approved;
	}
}
?>