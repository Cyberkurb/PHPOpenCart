<?php
class ModelAccountDevice extends Model {
	public function getDeviceCount($customer_id) {
		$sql = "SELECT count(*) AS active_devices FROM " . DB_PREFIX . "device WHERE customer_id = '" . (int)$customer_id . "'";
		$query = $this->db->query($sql);
		$active_devices = $query->row['active_devices'];
		$sql2 = "SELECT count(*) AS pending_devices FROM " . DB_PREFIX . "device_pending WHERE customer_id = '" . (int)$customer_id . "'";
		$query2 = $this->db->query($sql2);
		$pending_devices = $query2->row['pending_devices'];

		return $active_devices+$pending_devices;
	}
	public function getDevices($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "device WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}
	public function getDevice($device_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "device WHERE device_id = '" . (int)$device_id . "'");

		return $query->row;
	}
	public function getDeviceStatus($deviceID) {
		$query = $this->db->query("SELECT oc_device.deviceID, TIMESTAMPDIFF(SECOND, oc_device_state.date_added, now()) as timechange FROM oc_device JOIN oc_device_state ON oc_device_state.deviceID = oc_device.deviceID WHERE oc_device.deviceID = '" . $this->db->escape($deviceID) . "' ORDER BY oc_device_state.date_added DESC LIMIT 1");

		if ($query->num_rows) {
			$diff  = $query->timechange;

			if($diff > 30){
				$status = 'Offline';
			}
			else{
				$status = 'Online';
			}

			return $status;
		} else {
			return 'Offline';
		}
		//return $diff;
    }
    public function getDeviceStatusDetail($deviceID) {
		$query = $this->db->query("SELECT status FROM " . DB_PREFIX . "device_state WHERE deviceID = '" . $this->db->escape($deviceID) . "' ORDER BY status_id DESC LIMIT 1");

		return $query->row['status'];
    }
    public function addPhone($data) {
		$device_token = $data['token_id'];
		$customer_id = $this->customer->getId();
		$store_id = $this->config->get('config_store_id');
		$phoneid = $data['phoneid'];
		
		$check_sql1 = "SELECT * FROM " . DB_PREFIX . "customer_app WHERE token = '" . $this->db->escape($device_token) . "';";
		$check_q1 = $this->db->query($check_sql1);

		if($check_q1->num_rows){
			$app_id = $check_q1->row['app_id'];
			$query_sql = "UPDATE " . DB_PREFIX . "customer_app SET ";
			$query_sql .= "store_id = " . (int)$store_id . " ";
			$query_sql .= ", customer_id = " . (int)$customer_id . "";
			$query_sql .= ", phoneid = '" . $this->db->escape($data['phoneid']) . "'";
			if(isset($data['app_type_id'])){
				$query_sql .= ", app_type_id = " . (int)$data['app_type_id'] . " ";
			}
			
			if(isset($data['token_id'])){
				$query_sql .= ", token = '" . $this->db->escape($device_token) . "' ";
			}
			
			$query_sql .= ", date_modified = now() ";
			$query_sql .= "WHERE app_id = " . (int)$app_id . "; ";
		}
		else{
			$query_sql = "INSERT INTO " . DB_PREFIX . "customer_app ";
			$query_sql .= "(phoneid, store_id, customer_id, app_type_id, token, date_added) ";
			$query_sql .= "VALUES ('" . $this->db->escape($data['phoneid']) . "', " . (int)$this->config->get('config_store_id') . " ";
			$query_sql .= ", " . (int)$this->customer->getId() . " ";
			$query_sql .= ", " . (int)$data['app_type_id'] . " ";
			$query_sql .= ", '" . $this->db->escape($data['token_id']) . "' ";
			$query_sql .= ", now());";
		}
		
		$query = $this->db->query($query_sql);
    }
    public function updatePhone($data) {
		$query_sql = "UPDATE " . DB_PREFIX . "customer_app SET customer_id = " . (int)$this->customer->getId() . " WHERE session_id = '" . $this->db->escape($this->session->getId()) . "';";
		$query = $this->db->query($query_sql);
	}
	public function getPendingDevices($customer_id){
		$query_sql = "SELECT * FROM " . DB_PREFIX . "device WHERE customer_id = '" . (int)$customer_id . "';";
		$query = $this->db->query($query_sql);
		return $query->rows;
	}
	public function getPendingDevice($id){
		$query_sql = "SELECT * FROM " . DB_PREFIX . "device WHERE deviceID = '" . (int)$id . "';";
		$query = $this->db->query($query_sql);
		return $query->row;
	}
	public function addPasswordDevice($id, $data){
		$query_sql = "UPDATE " . DB_PREFIX . "device SET ssid = '" . $this->db->escape($data['ssid']) . "', password = '" . $this->db->escape($data['networkpassword']) . "', date_modified = now() WHERE deviceID = '" . $this->db->escape($id)  . "';";
		$query = $this->db->query($query_sql);
		return $id;
	}
	public function getPendingPasssword(){
		$query_sql = "SELECT ssid, password AS password2 FROM " . DB_PREFIX . "device WHERE customer_id = '" . (int)$this->customer->getId() . "';";
		$query = $this->db->query($query_sql);
		return $query->row;
	}

	public function deviceCheck($name, $customer_id){
		$query_sql = "SELECT device_id FROM " . DB_PREFIX . "device ";
		$query_sql .= "WHERE name = '" . $name ."' AND customer_id = " . $customer_id . ";";
		
		$query = $this->db->query($query_sql);
		$device_id = $query->row['device_id'];

		if($device_id > 0){
			return "wifi";
		}
		else{
			return "ble";
		}
	}

  public function addDevice($data) {
		$uuid = $data['uuid'];
		$d_name = $data['name'];
		$customer_id = $this->customer->getId();
		$store_id = $this->config->get('config_store_id');

		$responsive_response = $this->deviceCheck($d_name, $customer_id);
		if($responsive_response == 'ble'){
			$query_sql = "INSERT INTO " . DB_PREFIX . "device ";
			$query_sql .= "(customer_id, deviceID, uuid, name, date_added) ";
			$query_sql .= "VALUES ('" . (int)$customer_id . "'";
			$query_sql .= ", '" . $this->db->escape(substr($d_name, 7)) . "'";
			$query_sql .= ", '" . $this->db->escape($uuid) . "'";
			$query_sql .= ", '" . $this->db->escape($d_name). "'";
			$query_sql .= ", now());";
			
			return $this->db->query($query_sql);
		}
		else{
			return "Wifi Connected";
		}
		
	}
	public function getConnectionCheck($customer_id){
		// find the new devices
		$query_sql = "SELECT deviceID FROM " . DB_PREFIX . "device_state ";
		$query_sql .= "WHERE NOT EXISTS ";
		$query_sql .= "(SELECT * FROM " . DB_PREFIX . "device WHERE " . DB_PREFIX . "device.deviceID = " . DB_PREFIX . "device_state.deviceID) ";
		$query_sql .= "GROUP BY deviceID;";
		$query_find = $this->db->query($query_sql);
		$find_pending = $this->db->query("SELECT * FROM " . DB_PREFIX . "device_pending WHERE customer_id = " . (int)$customer_id . " LIMIT 1");
		if($query_find->num_rows){
			$deviceID = $query_find->row['deviceID'];

			$query_sql_insert = "INSERT INTO " . DB_PREFIX . "device (customer_id, deviceID, uuid, ip, date_added) ";
			$query_sql_insert .= "VALUES (" . (int)$customer_id . ", ";
			$query_sql_insert .= "'" . $this->db->escape($deviceID) . "', ";
			$query_sql_insert .= "'" . $this->db->escape($find_pending->row['uuid']) . "', ";
			$query_sql_insert .= "'" . $this->db->escape($find_pending->row['ip']) . "', now())";
			$query_insert = $this->db->query($query_sql_insert);
			//$query_remove_pending = $this->db->query("DELETE FROM " . DB_PREFIX . "device_pending WHERE id = " . (int)$find_pending->row['id'] . "");
			$cleared = 1;
		}
		else{
			$query_reconnect = $this->db->query("SELECT oc_device.deviceID, TIMESTAMPDIFF(SECOND, oc_device_state.date_added, now()) as timechange FROM oc_device_pending JOIN oc_device ON oc_device.uuid = oc_device_pending.uuid JOIN oc_device_state ON oc_device_state.deviceID = oc_device.deviceID ORDER BY oc_device_state.date_added DESC LIMIT 1");
			
			if($query_reconnect->row['timechange'] < 30){
				//query_remove_pending = $this->db->query("DELETE FROM " . DB_PREFIX . "device_pending WHERE id = " . (int)$find_pending->row['id'] . "");
				$cleared = 1;
			}
			else{
				$cleared = 0;
			}
		}

		return $cleared;
	}
	public function addDevicetoCustomer($customer_id, $store_id, $phoneid) {
		$query_sql = "UPDATE " . DB_PREFIX . "device SET ";
		$query_sql .= "customer_id = '" . (int)$customer_id . "'";
		$query_sql .= ", store_id = '" . (int)$store_id. "' ";
		$query_sql .= "WHERE token_id = '" . $this->db->escape($phoneid) . "'; ";

		$query = $this->db->query($query_sql);
		return $query;
	}
	public function addPhonetoCustomer($customer_id, $store_id, $phoneid) {
		$query_sql = "UPDATE " . DB_PREFIX . "customer_app SET ";
		$query_sql .= "customer_id = '" . (int)$customer_id . "'";
		$query_sql .= ", store_id = '" . (int)$store_id. "'";
		$query_sql .= ", date_modified = now() ";
		$query_sql .= "WHERE phoneid = '" . $this->db->escape($phoneid) . "'; ";

		$query = $this->db->query($query_sql);
		return $query;
	}

	public function forgetDevice($deviceID){
		$query_sql = "DELETE from " . DB_PREFIX . "device WHERE ";
		$query_sql .= "deviceID = '" . $this->db->escape($deviceID) . "' ";
		$query_sql .= "AND customer_id = " . (int)$this->customer->getId() . ";";
		
		$this->db->query($query_sql);
		return 1;
	}

	public function currentTemps($deviceID){
		$query_sql = "SELECT grill_settemp, grill_acttemp, pc_settemp, probe1_acttemp, ";
		$query_sql .= "probe2_acttemp, probe3_acttemp, IF(grill_degrees = 1, 'F', 'C') AS grill_degrees ";
		$query_sql .= "FROM " . DB_PREFIX . "device_temp WHERE deviceID = '" . $deviceID . "' ";
		$query_sql .= "AND date_added >= DATE_SUB(now(), INTERVAL 1 MINUTE) AND cnt <> 9999 ORDER BY cnt DESC LIMIT 0, 1;";
		$query = $this->db->query($query_sql);
		return $query->row;
	}

	public function bleGrillData($data) {
		$query_sql = "INSERT INTO oc_device_temp ";
		$query_sql .= "(deviceID, ";
		$query_sql .= "grill_acttemp, ";
		$query_sql .= "pc_settemp, ";
		$query_sql .= "probe1_acttemp, ";
		$query_sql .= "probe2_acttemp, ";
		$query_sql .= "probe3_acttemp, ";
		$query_sql .= "grill_degrees, ";
		$query_sql .= "cnt, date_added) ";
		$query_sql .= "VALUES ('" . $this->db->escape($data['deviceID']) . "', ";
		$query_sql .= "'" . $this->db->escape($data['grill_acttemp']) . "', ";
		$query_sql .= "'" . $this->db->escape($data['pc_set']) . "', ";
		$query_sql .= "'" . $this->db->escape($data['p1_temp']) ."', ";
		$query_sql .= "'" . $this->db->escape($data['p2_temp']) ."', ";
		$query_sql .= "'" . $this->db->escape($data['p3_temp']) ."', ";
		$query_sql .= "'" . $this->db->escape($data['grill_degrees']) . "', ";
		$query_sql .= "'" . $this->db->escape($data['cnt']) . "', now());";
		return $this->db->query($query_sql);
	}
	public function bleGrillDataRaw($data) {
		$query_sql = "INSERT INTO oc_device_raw	 ";
		$query_sql .= "(feed, ";
		$query_sql .= "date_added) ";
		$query_sql .= "VALUES ('" . $this->db->escape($data) . "', ";
		$query_sql .= "now());";
		return $this->db->query($query_sql);
	}
}