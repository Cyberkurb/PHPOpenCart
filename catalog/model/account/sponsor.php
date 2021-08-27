<?php
class ModelAccountSponsor extends Model {
	public function addSponsor($customer_id, $address_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_sponsor SET customer_id = '" . (int)$customer_id . "', address_id = '" . (int)$address_id . "', webaddress = '" . $this->db->escape($data['webaddress']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', charity = '" . (int)$data['charity'] . "', eventname = '" . $this->db->escape($data['eventname']) . "', eventdate = '" . $this->db->escape($data['eventdate']) . "', eventaud = '" . $this->db->escape($data['eventaud']) . "', eventbriefdesc = '" . $this->db->escape($data['eventbriefdesc']) . "', donationtype = '" . $this->db->escape($data['donationtype']) . "', donvalue = '" . $this->db->escape($data['donvalue']) . "', recognized = '" . $this->db->escape($data['recognized']) . "', whoelse = '" . $this->db->escape($data['whoelse']) . "', comment = '" . $this->db->escape($data['comment']) . "', order_status_id = 1, date_added = now();");
		$sponsor_id = $this->db->getLastId();
		return $sponsor_id;
	}

	public function addCharity($customer_id, $address_id, $data) {
		$query_sql  = "INSERT INTO " . DB_PREFIX . "customer_sponsor SET";
		$query_sql .= " customer_id = " . (int)$customer_id;
		$query_sql .= ", address_id = " . (int)$address_id;
		$query_sql .= ", charity = '" . (int)$data['charity'] . "'";
		$query_sql .= ", telephone = '" . $this->db->escape($data['telephone']) . "'";		
		$query_sql .= ", desingation = '" . $this->db->escape($data['designation']) . "'";
		$query_sql .= ", desingationcomment ='" . $this->db->escape($data['designationcomment']) . "'";
		$query_sql .= ", eventname = '" . $this->db->escape($data['eventname']) . "'";
		$query_sql .= ", eventdate =  '" . $this->db->escape($data['eventdate']) . "'";
		$query_sql .= ", eventbriefdesc = '" . $this->db->escape($data['eventbriefdesc']) . "'";
		$query_sql .= ", whoelse = '" . $this->db->escape($data['whoelse']) . "'";
		$query_sql .= ", comment = '" . $this->db->escape($data['comment']) . "'";
		
		

		$this->db->query($query_sql);
		$sponsor_id = $this->db->getLastId();

		return $sponsor_id;
	}

	public function editSponsor($sponsor_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? json_encode($data['custom_field']['address']) : '') . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if (!empty($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}
	}

	public function getSponsorRequest($customer_id){
		$query_sql = "SELECT " . DB_PREFIX . "customer.firstname AS firstname, ";
		$query_sql .= DB_PREFIX . "customer.lastname AS lastname, ";
		$query_sql .= DB_PREFIX . "customer.email AS email, ";
		$query_sql .= DB_PREFIX . "customer.telephone AS phone, ";
		$query_sql .= DB_PREFIX . "address.address_1 AS address_1, ";
		$query_sql .= DB_PREFIX . "address.address_2 AS address_2, ";
		$query_sql .= DB_PREFIX . "address.city AS city, ";
		$query_sql .= DB_PREFIX . "address.postcode AS postcode, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.webaddress AS event_address, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.telephone AS event_phone, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.charity AS charity, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.eventdate AS event_date, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.eventaud AS event_audience, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.eventbriefdesc AS event_desc, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.donationtype AS event_donationtype, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.donvalue AS event_donationvalue, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.recognized AS recognized, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.whoelse AS event_whoelse, ";
		$query_sql .= DB_PREFIX . "customer_sponsor.comment AS event_comment ";
		$query_sql .= "FROM " . DB_PREFIX . "customer_sponsor ";
		$query_sql .= "JOIN " . DB_PREFIX . "address ON " . DB_PREFIX . "address.address_id = " . DB_PREFIX . "customer_sponsor.address_id ";
		$query_sql .= "JOIN " . DB_PREFIX . "customer ON " . DB_PREFIX . "customer.customer_id = " . DB_PREFIX . "customer_sponsor.customer_id ";
		$query_sql .= "WHERE " . DB_PREFIX . "customer_sponsor.customer_id = " . (int)$customer_id . ";";
		
		$query = $this->db->query($query_sql);

		return $query->row;
	}

	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getSponsor($address_id) {
		$sponsor_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "sponsor WHERE sponsor_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if ($sponsor_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$sponsor_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'designation'    => $address_query->row['designation'],
				'designationcomment' => $address_query->row['designationcomment'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($address_query->row['custom_field'], true)
			);

			return $address_data;
		} else {
			return false;
		}
	}

	public function getAddresses() {
		$address_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data[$result['address_id']] = array(
				'address_id'     => $result['address_id'],
				'designation'    => $address_query->row['designation'],
				'designationcomment' => $address_query->row['designationcomment'],
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'postcode'       => $result['postcode'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($result['custom_field'], true)

			);
		}

		return $address_data;
	}

	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
}
