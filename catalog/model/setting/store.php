<?php
class ModelSettingStore extends Model {
	public function getStores($data = array()) {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

			$store_data = $query->rows;

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}

	public function getBadIPAddress() {
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$ip_address = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$ip_address = $this->request->server['REMOTE_ADDR'];
		}

		$query = $this->db->query("SELECT COUNT(id) AS total FROM " . DB_PREFIX . "block_ips WHERE ipaddress = '" . $ip_address . "';");

		return $query->row['total'];
	}

	public function getUserLocation() {
		
		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$ip_address = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$ip_address = $this->request->server['REMOTE_ADDR'];
		}

		$location_details = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip_address));
		$country_code = $location_details['geoplugin_countryCode'];
		
		if(strpos(strtolower($this->request->server['HTTP_ACCEPT_LANGUAGE']), '-ca') !== false){
    		$country_code = 'CA';
		} else{
			$country_code = $country_code;
		}
		
		return $country_code;
	}
}