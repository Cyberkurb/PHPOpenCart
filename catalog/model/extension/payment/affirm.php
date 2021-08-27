<?php 
class ModelExtensionPaymentAffirm extends Model {
    public function getPublicAPI(){
            $sql = "SELECT public_api FROM " . DB_PREFIX . "affirm_payment";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id') ."";
            $sql .= " AND status = 1;";
            $query = $this->db->query($sql);
        
        return $query->row['public_api'];
    }

    public function getPrivateAPI(){
            $sql = "SELECT private_api FROM " . DB_PREFIX . "affirm_payment";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id');
            $sql .= " AND status = 1;";
            $query = $this->db->query($sql);
        return $query->row['private_api'];
    }

    public function getProductID(){
            $sql = "SELECT fin_product_id FROM " . DB_PREFIX . "affirm_payment";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id');
            $sql .= " AND status = 1;";
            $query = $this->db->query($sql);
        return $query->row['fin_product_id'];
    }

    public function getTransactionID($transactionid){
        $sql = "SELECT transactionkey FROM " . DB_PREFIX . "payfabric_transaction";
        $sql .= " WHERE payfabric_transaction_id = " . (int)$transactionid . ";";
        $query = $this->db->query($sql);
        return $query->row['transactionkey'];
    }

    public function getTransactionStatus($transactionid){
        $sql = "SELECT " . DB_PREFIX . "payfabric_transaction.Status AS transaction_status FROM " . DB_PREFIX . "payfabric_transaction";
        $sql .= " WHERE payfabric_transaction_id = " . (int)$transactionid . ";";
        $query = $this->db->query($sql);
        return $query->row['transaction_status'];
    }

    public function userValidation($username, $order_id){
		$this->db->query("UPDATE " . DB_PREFIX . "order SET username = '" . $this->db->escape($username) . "' WHERE order_id = '" . (int)$order_id . "';");
		
		return 1;
	}

    public function addTransaction($trans){
        $sql = "INSERT INTO " . DB_PREFIX . "affirm_transactions SET";
        $sql .= " order_id = " . (int)$this->session->data['order_id'];
        $sql .= ", checkout_token = '" . $this->db->escape($trans['checkout_token']) . "'";
        $sql .= ", env = '" . $this->db->escape($trans['env']) . "'";
        $sql .= ", date_added = now();";
        $query = $this->db->query($sql);
        return $this->db->getLastId();
    }


  	public function getMethod($address, $total) { 
		$this->load->language('extension/payment/payfabric');
		
      		$method_data = array( 
        		'code'       => 'affirm',
        		'title'      => "<img src='https://cdn-assets.affirm.com/images/black_logo-transparent_bg.png' style='max-width:150px;'><br>Affirm Payments - <a href='/how-does-affirm-work' target='_blank'>Learn More about Affirm</a>",
				'terms'      => '',	
				'sort_order' => 3
      		);
    	return $method_data;
      }
}
?>