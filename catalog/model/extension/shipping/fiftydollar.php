<?php
class ModelExtensionShippingFiftydollar extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/weight');

		$quote_data = array();
        $status = true;
        
        $quote_data['fiftydollar_'.$result['geo_zone_id']] = array(
            'code'         => 'fiftydollar.fiftydollar_'.$result['geo_zone_id'],
            'title'        => 'Special Event Shipping Rate',
            'cost'         => 50,
            'tax_class_id' => $this->config->get('shipping_weight_tax_class_id'),
            'text'         => $this->currency->format($this->tax->calculate(50, $this->config->get('shipping_weight_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
        );
		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'fiftydollar',
				'title'      => 'Special Event Shipping Rate',
				'quote'      => $quote_data,
				'sort_order' => 2,
				'error'      => false
			);
		}

		return $method_data;
	}
}
