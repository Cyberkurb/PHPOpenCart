<?php
class ModelExtensionShippingFreeshippingstore extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/weight');

		$quote_data = array();
        $status = true;
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		foreach ($query->rows as $result) {
			if ($status) {
				$cost = '';
                $weight = $this->cart->getWeight();
                $subtotal = $this->cart->getSubTotal();
                $sql = "SELECT subtotal FROM " . DB_PREFIX . "shipping_free";
                $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id') . " AND start_date <= date(now()) AND end_date >= date(now()) LIMIT 1;" ;
                $rates = $this->db->query($sql);
                $cost = $rates->row['subtotal'];

				if ($subtotal >= $cost) {
					$quote_data['freeshippingstore_'.$result['geo_zone_id']] = array(
						'code'         => 'freeshippingstore.freeshippingstore_'.$result['geo_zone_id'],
						'title'        => 'Free Shipping' . '  (Subtotal: ' . $this->currency->format($this->tax->calculate($subtotal, $this->config->get('shipping_weight_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']) . ')',
						'cost'         => 0,
						'tax_class_id' => $this->config->get('shipping_weight_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate(0, $this->config->get('shipping_weight_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}
        }
		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'freeshippingstore',
				'title'      => 'Free Shipping',
				'quote'      => $quote_data,
				'sort_order' => 0,
				'error'      => false
			);
		}

		return $method_data;
	}
}
