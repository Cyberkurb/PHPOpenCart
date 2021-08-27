<?php
class ModelAccountActivity extends Model {
	public function addActivity($key, $data) {
		if (isset($data['customer_id'])) {
			$customer_id = $data['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$ip_address = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$ip_address = $this->request->server['REMOTE_ADDR'];
		}


		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_activity` SET `customer_id` = '" . (int)$customer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', `ip` = '" . $this->db->escape($ip_address) . "', `date_added` = NOW()");
	}
}