<?php
class ModelExtensionShippingDansons extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/item');
		$method_data = array();
			$items = 0;
			$weight = 0;

			foreach ($this->cart->getProducts() as $product) {
			    $free_q = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_free_ship WHERE product_id = '" . (int)$product['product_id'] . "' AND start_date <= DATE_SUB(now(), INTERVAL 7 HOUR) AND end_date >= DATE_SUB(now(), INTERVAL 7 HOUR);");
			    if($free_q->num_rows > 0){
			        $items += 0;
			        $weight += 0;
			    }
			    else{
			        $weight_q = $this->db->query("SELECT weight FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product['product_id'] . "' LIMIT 1;");

				    if($weight_q->row['weight'] > 0){
				        $weight += $weight_q->row['weight']*$product['quantity'];
				    }
					else{
					    $weight += 1*$product['quantity'];
					}
					$items += $product['quantity'];
			    }
			    
			}
            
            //$weight_cost_q = $this->db->query("SELECT * FROM " . DB_PREFIX . "ship_weight_rules WHERE store_id = '" . (int)$this->config->get('config_store_id') . "' AND weight >= '" . $weight . "' ORDER BY weight ASC LIMIT 1;");
            $weight_cost_q = $this->db->query("SELECT * FROM " . DB_PREFIX . "ship_weight_rules WHERE store_id = 0 AND weight >= '" . $weight . "' ORDER BY weight ASC LIMIT 1;");
            $shipping_price = $weight_cost_q->row['price'];
			$quote_data = array();

			$quote_data['dansons'] = array(
				'code'         => 'dansons.dansons',
				'title'        => 'Shipping',
				'cost'         => $shipping_price,
				'text'         => $this->currency->format($this->tax->calculate($shipping_price, 0, $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'dansons',
				'title'      => 'Shipping',
				'quote'      => $quote_data,
				'sort_order' => 0,
				'error'      => false
			);
			
		return $method_data;
		}

}