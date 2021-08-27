<?php
class ModelExtensionShippingOneday extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/weight');

		$quote_data = array();
        $status = true;
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0') LIMIT 1");

		foreach ($query->rows as $result) {
			if ($status) {

                $cost = '';
                $weight = $this->cart->getWeight();
                $subtotal = $this->cart->getSubTotal();

                $sql1 = "SELECT price FROM " . DB_PREFIX . "shipping_amount";
                $sql1 .= " WHERE store_id = " . (int)$this->config->get('config_store_id') . " AND shipping_code = 'twoday' AND weight >= '" . $weight ."' ORDER BY weight LIMIT 1;" ;
                $rates1 = $this->db->query($sql1);

                $cost2day = $rates1->row['price'];

                $sql = "SELECT price FROM " . DB_PREFIX . "shipping_amount";
                $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id') . " AND shipping_code = 'oneday' AND weight >= '" . $weight ."' ORDER BY weight LIMIT 1;" ;
                $rates = $this->db->query($sql);
                if($subtotal < 99){
                    $cost = $rates->row['price'];
                }
                else{
                    $cost = ($rates->row['price']-$cost2day);
                }

				if ((string)$cost != '') {
					$quote_data['oneday_'.$result['geo_zone_id']] = array(
						'code'         => 'oneday.oneday_'.$result['geo_zone_id'],
						'title'        => 'One Day Shipping' . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('shipping_weight_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('shipping_weight_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}
        }
		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'oneday',
				'title'      => 'One Day Shipping',
				'quote'      => $quote_data,
				'sort_order' => 5,
				'error'      => false
			);
		}

		return $method_data;
	}
}
