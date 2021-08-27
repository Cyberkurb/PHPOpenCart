<?php
class ModelExtensionShippingStandard extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/weight');

		$quote_data = array();
        $status = true;
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0') limit 1");

		foreach ($query->rows as $result) {
			if ($status) {
                $subtotal = $this->cart->getSubTotal();
                if((int)$address['zone_id'] == 3614){
                    $shipping_code = 'standardak';
                }
                elseif((int)$address['zone_id'] == 3633){
                    $shipping_code = 'standardak';
                }
                elseif((int)$address['country_id'] == 38){
                    $shipping_code = 'standardca';
                }
                else{
                    $shipping_code = 'standardus';
                }



				$cost = '';
                $weight = $this->cart->getWeight();
                if($weight < 1){
                    $weight = 1;
                }
                else{
                    $weight = (int)$weight;
                }

                $sql = "SELECT price FROM " . DB_PREFIX . "shipping_amount";
                $sql .= " WHERE store_id = " . (int)$this->config->get('config_store_id') . " AND shipping_code = '" . $shipping_code ."' AND weight >= '" . $weight ."' ORDER BY weight LIMIT 1;" ;
                $rates = $this->db->query($sql);
                if($subtotal < 99){
                    $cost = $rates->row['price'];
                }
                else{
                    $cost = 0;
                }

				if ((string)$cost != '') {
					$quote_data['standard_'.$result['geo_zone_id']] = array(
						'code'         => 'standard.standard_'.$result['geo_zone_id'],
						'title'        => 'Standard Shipping' . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
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
				'code'       => 'standard',
				'title'      => 'Standard Shipping',
				'quote'      => $quote_data,
				'sort_order' => 1,
				'error'      => false
			);
		}

		return $method_data;
	}
}
?>
