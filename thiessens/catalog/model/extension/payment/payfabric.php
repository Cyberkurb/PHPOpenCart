<?php 
class ModelExtensionPaymentPayfabric extends Model {
    public function getDeviceID(){
            $sql = "SELECT deviceid FROM " . DB_PREFIX . "payfabric_device";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id') ."";
            $sql .= " AND status = 1 AND currency = '" . $this->db->escape($this->session->data['currency']) . "';";
            $query = $this->db->query($sql);
        
        return $query->row['deviceid'];
    }

    public function getDevicePass(){
            $sql = "SELECT devicepass FROM " . DB_PREFIX . "payfabric_device";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id');
            $sql .= " AND status = 1 AND currency = '" . $this->db->escape($this->session->data['currency']) . "';";
            $query = $this->db->query($sql);
        return $query->row['devicepass'];
    }

    public function getCustomerId(){
            $sql = "SELECT account_id_gp FROM " . DB_PREFIX . "payfabric_device";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id');
            $sql .= " AND status = 1 AND currency = '" . $this->db->escape($this->session->data['currency']) . "';";
            $query = $this->db->query($sql);
        return $query->row['account_id_gp'];
    }

    public function getCurrency(){
            $sql = "SELECT currency FROM " . DB_PREFIX . "payfabric_device";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id');
            $sql .= " AND status = 1 AND currency = '" . $this->db->escape($this->session->data['currency']) . "';";
            $query = $this->db->query($sql);
        
        return $query->row['currency'];
    }
    
    public function getpayfabricdeviceid(){
            $sql = "SELECT payfabric_device_id FROM " . DB_PREFIX . "payfabric_device";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id') ."";
            $sql .= " AND status = 1 AND currency = '" . $this->db->escape($this->session->data['currency']) . "';";
            $query = $this->db->query($sql);

        return $query->row['payfabric_device_id'];
    }

    public function getSetupid(){
            $sql = "SELECT setupid FROM " . DB_PREFIX . "payfabric_device";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id') ."";
            $sql .= " AND status = 1 AND currency = '" . $this->db->escape($this->session->data['currency']) . "';";
            $query = $this->db->query($sql);
        return $query->row['setupid'];
    }

    public function getCountry(){
            $sql = "SELECT country FROM " . DB_PREFIX . "payfabric_device";
            $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id');
            $sql .= " AND status = 1 AND currency = '" . $this->db->escape($this->session->data['currency']) . "';";
            $query = $this->db->query($sql);
        
        return $query->row['country'];
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
        $sql = "INSERT INTO " . DB_PREFIX . "payfabric_transaction SET";
        $sql .= " store_id = " . (int)$this->config->get('config_store_id');
        $sql .= ", payfabric_device_id = " . (int)$this->getpayfabricdeviceid();
        $sql .= ", customer_id = " . (int)$this->customer->getId();
        $sql .= ", order_id = " . (int)$this->session->data['order_id'];
        $sql .= ", AVSAddressResponse = '" . $this->db->escape($trans['AVSAddressResponse']) . "'";
        $sql .= ", AVSZipResponse = '" . $this->db->escape($trans['AVSZipResponse']) . "'";
        $sql .= ", AuthCode = '" . $this->db->escape($trans['AuthCode']) . "'";
        $sql .= ", CVV2Response = '" . $this->db->escape($trans['CVV2Response']) . "'";
        $sql .= ", IAVSAddressResponse = '" . $this->db->escape($trans['IAVSAddressResponse']) . "'";
        $sql .= ", Message = '" . $this->db->escape($trans['Message']) . "'";
        $sql .= ", OriginationID = '" . $this->db->escape($trans['OriginationID']) . "'";
        $sql .= ", PayFabricErrorCode = '" . $this->db->escape($trans['PayFabricErrorCode']) . "'";
        $sql .= ", RespTrxTag = '" . $this->db->escape($trans['RespTrxTag']) . "'";
        $sql .= ", ResultCode = '" . $this->db->escape($trans['ResultCode']) . "'";
        $sql .= ", Status = '" . $this->db->escape($trans['Status']) . "'";
        $sql .= ", TAXml = '" . $this->db->escape($trans['TAXml']) . "'";
        $sql .= ", TrxDate = '" . $this->db->escape($trans['TrxDate']) . "'";
        $sql .= ", TrxKey = '" . $this->db->escape($trans['TrxKey']) . "'";
        $sql .= ", rawresponse = '" . $this->db->escape($trans) . "'";
        $sql .= ", date_added = now();";
        $query = $this->db->query($sql);
        return $this->db->getLastId();
    }
/*
    public function updateTransaction($trans_id, $trans){
        $sql = "UPDATE " . DB_PREFIX . "payfabric_transaction SET";
        $sql .= " AVSAddressResponse = '" . $this->db->escape($trans['AVSAddressResponse']) . "'";
        $sql .= ", AVSZipResponse = '" . $this->db->escape($trans['AVSZipResponse']) . "'";
        $sql .= ", AuthCode = '" . $this->db->escape($trans['AuthCode']) . "'";
        $sql .= ", CVV2Response = '" . $this->db->escape($trans['CVV2Response']) . "'";
        $sql .= ", IAVSAddressResponse = '" . $this->db->escape($trans['IAVSAddressResponse']) . "'";
        $sql .= ", Message = '" . $this->db->escape($trans['Message']) . "'";
        $sql .= ", OriginationID = '" . $this->db->escape($trans['OriginationID']) . "'";
        $sql .= ", PayFabricErrorCode = '" . $this->db->escape($trans['PayFabricErrorCode']) . "'";
        $sql .= ", RespTrxTag = '" . $this->db->escape($trans['RespTrxTag']) . "'";
        $sql .= ", ResultCode = '" . $this->db->escape($trans['ResultCode']) . "'";
        $sql .= ", Status = '" . $this->db->escape($trans['Status']) . "'";
        $sql .= ", TAXml = '" . $this->db->escape($trans['TAXml']) . "'";
        $sql .= ", TrxDate = '" . $this->db->escape($trans['TrxDate']) . "'";
        $sql .= ", TrxKey = '" . $this->db->escape($trans['TrxKey']) . "'";
        $sql .= ", rawresponse = '" . $this->db->escape(var_dump($trans)) . "'";
        $sql .= ", date_modified = now()";
        $sql .= " WHERE payfabric_transaction_id = " . (int)$trans_id . ";";

        $query = $this->db->query($sql);
        return 1;
    }

*/


  	public function getMethod($address, $total) { 
		$this->load->language('extension/payment/payfabric');
		$status = true;
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'payfabric',
        		'title'      => $this->language->get('text_title'),
				'terms'      => '',	
				'sort_order' => 1
      		);
    	}
   
    	return $method_data;
      }
      
      public function orderDone($order_id){
        $sql = "SELECT count(payfabric_transaction_id) AS ordersapproved FROM " . DB_PREFIX . "payfabric_transaction";
        $sql .= " WHERE order_id = " . $order_id ." AND message = 'Approved'";

        $query = $this->db->query($sql);
        $orders_complete = $query->row['transaction_status'];
        if($orders_complete > 0){
            $completed = 0;
        }
        else{
            $completed = 1;
        }
        return $completed;
      }
}
?>