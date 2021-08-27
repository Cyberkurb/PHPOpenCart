<?php
class ModelExtensionShippingApickup extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/pickup');

		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$this->session->data['user_admin_id'] . "';");

		if (!$this->config->get('shipping_pickup_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

			$quote_data = array();

			$quote_data['amazen'] = array(
				'code'         => 'amazen.amazen',
				'title'        => "Pickup From your assigned warehouse **Only Use if Providing Product in Person",
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00, $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'amazen',
				'title'      => "Pickup From your assigned warehouse **Only Use if Providing Product in Person",
				'quote'      => $quote_data,
				'sort_order' => 99,
				'error'      => false
			);

		return $method_data;
	}
}