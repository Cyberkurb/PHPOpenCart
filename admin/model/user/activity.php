<?php
class ModelUserActivity extends Model {
	public function addActivity($key, $data) {
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$ip_address = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$ip_address = $this->request->server['REMOTE_ADDR'];
		}


		$this->db->query("INSERT INTO `" . DB_PREFIX . "user_activity` SET `user_id` = '" . (int)$this->user->getId() . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape($data) . "', `ip` = '" . $this->db->escape($ip_address) . "', `date_added` = NOW()");
	}
	
	public function getActivities($start = 0,$limit = 50) {
		$query = $this->db->query("SELECT u.user_id, u.username,CONCAT(u.firstname,' ',u.lastname) AS user, ua.* FROM `" . DB_PREFIX . "user_activity` ua LEFT JOIN `" . DB_PREFIX . "user` u ON (u.user_id = ua.user_id) ORDER BY ua.date_added DESC LIMIT ".$start.",".$limit."");

		return $query->rows;
	}
}