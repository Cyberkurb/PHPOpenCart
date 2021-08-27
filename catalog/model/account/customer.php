<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape(preg_replace('/[^A-Za-z0-9\-]/', '',$data['telephone'])) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW();");

		$customer_id = $this->db->getLastId();
		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_approval SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = NOW()");
		}

		$email = $this->db->escape($data['email']);
		$key = "NZiSC2bXrkfz3i77Xmmzt";
		$url = "https://apps.emaillistverify.com/api/verifyEmail?secret=".$key."&email=".$email;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		$response = curl_exec($ch);
		// echo $response;
		curl_close($ch);
	
		if ($response == "ok") {$verificationStat = 1;}
		elseif ($response == "ok_for_all") {$verificationStat = 1;} 
		elseif ($response == "accept_all") {$verificationStat = 1;}
		else {$verificationStat = 0;}

		if($verificationStat == 1){
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET safe = 1 WHERE customer_id = '" . (int)$customer_id . "'");
			$ch = curl_init("https://newcrm.dansonscorp.com/integration/website/activecamp_add.php?customer_id=".$customer_id);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_exec($ch);
			curl_close($ch);
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET safe = 0 WHERE customer_id = '" . (int)$customer_id . "'");
		}

		$ch = curl_init("https://newcrm.dansonscorp.com/integration/website/transfer-accountv2.php?customer_id=".$customer_id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);

		return $customer_id;
	}

	public function addCustomer_dealer($data) {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		$query_q = "INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', ";
		$query_q .= "store_id = '" . (int)$this->config->get('config_store_id') . "', ";
		$query_q .= "language_id = '" . (int)$this->config->get('config_language_id') . "', ";
		$query_q .= "gpaccount = '" . $this->db->escape($data['gpaccount']) . "', ";
		$query_q .= "company = '" . $this->db->escape($data['company']) . "', ";
		$query_q .= "website = '" . $this->db->escape($data['website']) . "', ";
		$query_q .= "dba = '" . $this->db->escape($data['dba']) . "', ";
		$query_q .= "firstname = '" . $this->db->escape($data['firstname']) . "', ";
		$query_q .= "lastname = '" . $this->db->escape($data['lastname']) . "', ";
		$query_q .= "email = '" . $this->db->escape($data['email']) . "', ";
		$query_q .= "telephone = '" . $this->db->escape(preg_replace('/[^A-Za-z0-9\-]/', '',$data['telephone'])) . "', ";
		$query_q .= "custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', ";
		$query_q .= "salt = '" . $this->db->escape($salt = token(9)) . "', ";
		$query_q .= "password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', ";
		$query_q .= "newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ";
		$query_q .= "ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', ";
		$query_q .= "status = '" . (int)!$customer_group_info['approval'] . "', ";
		$query_q .= "date_added = NOW();";
		
		
		$this->db->query($query_q);

		$customer_id = $this->db->getLastId();
		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_approval SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = NOW()");
		}

		$email = $this->db->escape($data['email']);
		$key = "NZiSC2bXrkfz3i77Xmmzt";
		$url = "https://apps.emaillistverify.com/api/verifyEmail?secret=".$key."&email=".$email;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		$response = curl_exec($ch);
		// echo $response;
		curl_close($ch);
	
		if ($response == "ok") {$verificationStat = 1;}
		elseif ($response == "ok_for_all") {$verificationStat = 1;} 
		elseif ($response == "accept_all") {$verificationStat = 1;}
		else {$verificationStat = 0;}

		if($verificationStat == 1){
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET safe = 1 WHERE customer_id = '" . (int)$customer_id . "'");
			$ch = curl_init("https://newcrm.dansonscorp.com/integration/website/activecamp_add.php?customer_id=".$customer_id);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_exec($ch);
			curl_close($ch);
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET safe = 0 WHERE customer_id = '" . (int)$customer_id . "'");
		}

		$ch = curl_init("https://newcrm.dansonscorp.com/integration/website/transfer-accountv2.php?customer_id=".$customer_id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);

		return $customer_id;
	}

	public function editCustomer($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");
		
		$ch = curl_init("https://newcrm.dansonscorp.com/integration/website/transfer-accountv2.php?customer_id=".$customer_id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);
	}

	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editAddressId($customer_id, $address_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}
	
	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByCode($code) {
		$query = $this->db->query("SELECT customer_id, firstname, lastname, email FROM `" . DB_PREFIX . "customer` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}
	
	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function addTransaction($customer_id, $description, $amount = '', $order_id = 0) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (float)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");
	}

	public function deleteTransactionByOrderId($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getTransactionTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
	
	public function getRewardTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
		}
	}

	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}
	
	public function addAffiliate($customer_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_affiliate SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($data['company']) . "', `website` = '" . $this->db->escape($data['website']) . "', `tracking` = '" . $this->db->escape(token(64)) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape($data['tax']) . "', `payment` = '" . $this->db->escape($data['payment']) . "', `cheque` = '" . $this->db->escape($data['cheque']) . "', `paypal` = '" . $this->db->escape($data['paypal']) . "', `bank_name` = '" . $this->db->escape($data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape($data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape($data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($data['bank_account_number']) . "', `status` = '" . (int)!$this->config->get('config_affiliate_approval') . "'");
		
		if ($this->config->get('config_affiliate_approval')) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'affiliate', date_added = NOW()");
		}		
	}
		
	public function editAffiliate($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer_affiliate SET `company` = '" . $this->db->escape($data['company']) . "', `website` = '" . $this->db->escape($data['website']) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape($data['tax']) . "', `payment` = '" . $this->db->escape($data['payment']) . "', `cheque` = '" . $this->db->escape($data['cheque']) . "', `paypal` = '" . $this->db->escape($data['paypal']) . "', `bank_name` = '" . $this->db->escape($data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape($data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape($data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($data['bank_account_number']) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}
	
	public function getAffiliate($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->row;
	}
	
	public function getAffiliateByTracking($tracking) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `tracking` = '" . $this->db->escape($tracking) . "'");

		return $query->row;
	}
	
	public function getAllCustomers(){
		$query = $this->db->query("SELECT email AS id, CONCAT_WS(' - ', firstname, lastname, email, gpaccount) AS text FROM " . DB_PREFIX . "customer WHERE status = 1 AND YEAR(date_added) >= 2018 ORDER BY email;");
		return $query->rows;
	}
	public function getAllCustomersSearch($search_term = 'lonsway'){
		$query = $this->db->query("SELECT email AS id, CONCAT_WS(' ', firstname, lastname, email, gpaccount) AS text FROM " . DB_PREFIX . "customer WHERE CONCAT_WS(' ', firstname, lastname, email, gpaccount) LIKE '%" . $this->db->escape($search_term) . "%' AND status = 1 ORDER BY email;");
		return $query->rows;
	}
}