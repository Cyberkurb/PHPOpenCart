<?php
class ControllerCheckoutShippingMethod extends Controller {
	public function index() {
		
		$this->load->language('checkout/checkout');

		if (isset($this->session->data['shipping_address'])) {
			// Shipping Methods
			$method_data = array();
			$this->load->model('extension/shipping/dansons');
					$quote = $this->model_extension_shipping_dansons->getQuote($this->session->data['shipping_address']);
					if ($quote) {
						$method_data['dansons'] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensions('shipping');
	
			foreach ($results as $result) {
				if ($this->config->get('shipping_' . $result['code'] . '_status')) {
					$this->load->model('extension/shipping/' . $result['code']);

					$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}
			if($this->config->get('config_store_id') == 14){
				$this->load->model('extension/shipping/twoday');
				$this->load->model('extension/shipping/oneday');
					$quote = $this->model_extension_shipping_twoday->getQuote($this->session->data['shipping_address']);
					if ($quote) {
						$method_data['twoday'] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
					$quote = $this->model_extension_shipping_oneday->getQuote($this->session->data['shipping_address']);
					if ($quote) {
						$method_data['oneday'] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}

					if($this->session->data['user_admin_id'] == 15){
						$this->load->model('extension/shipping/apickup');
						$quote = $this->model_extension_shipping_apickup->getQuote($this->session->data['shipping_address']);
						if ($quote) {
							$method_data['amazen'] = array(
								'title'      => $quote['title'] . " **Only Use if Providing Product in Person",
								'quote'      => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							);
						}

						$this->load->model('extension/shipping/freeshippingstore');
						$quote = $this->model_extension_shipping_freeshippingstore->getQuote($this->session->data['shipping_address']);
						if ($quote) {
							$method_data['freeshippingstore'] = array(
								'title'      => $quote['title'],
								'quote'      => $quote['quote'],
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							);
						}
					}
			}
			if($this->config->get('config_store_id') == 7){
				$this->load->model('extension/shipping/twoday');
				$this->load->model('extension/shipping/oneday');
					$quote = $this->model_extension_shipping_twoday->getQuote($this->session->data['shipping_address']);
					if ($quote) {
						$method_data['twoday'] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
					$quote = $this->model_extension_shipping_oneday->getQuote($this->session->data['shipping_address']);
					if ($quote) {
						$method_data['oneday'] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
					$this->load->model('extension/shipping/pickup');
					$quote = $this->model_extension_shipping_pickup->getQuote($this->session->data['shipping_address']);
					if ($quote) {
						$method_data['pickup'] = array(
							'title'      => $quote['title'] . " **Only Use if Providing Product in Person",
							'quote'      => $quote['quote'],
							'sort_order' => 99,
							'error'      => $quote['error']
						);
					}
			}

			if($this->config->get('config_store_id') == 15){
				$this->load->model('extension/shipping/freeshippingstore');
				$quote = $this->model_extension_shipping_freeshippingstore->getQuote($this->session->data['shipping_address']);
				if ($quote) {
					$method_data['freeshippingstore'] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
			elseif($this->config->get('config_store_id') == 16){
				
				$this->load->model('extension/shipping/freeshippingstore');
				$quote = $this->model_extension_shipping_freeshippingstore->getQuote($this->session->data['shipping_address']);
				if ($quote) {
					$method_data['freeshippingstore'] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
				/*
				$this->load->model('extension/shipping/pickup');
				$quote = $this->model_extension_shipping_pickup->getQuote($this->session->data['shipping_address']);
				if ($quote) {
					$method_data['pickup'] = array(
						'title'      => $quote['title'] . " **Only Use if Providing Product in Person",
						'quote'      => $quote['quote'],
						'sort_order' => 8,
						'error'      => $quote['error']
					);
				}
				$this->load->model('extension/shipping/fiftydollar');
				$quote = $this->model_extension_shipping_fiftydollar->getQuote($this->session->data['shipping_address']);
				if ($quote) {
					$method_data['fiftydollar'] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => 0,
						'error'      => $quote['error']
					);
				}
				*/
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);

			$this->session->data['shipping_methods'] = $method_data;
		}

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods'];
		} else {
			$data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
		}
		
		$this->response->setOutput($this->load->view('checkout/shipping_method', $data));
	}

	public function save() {
		
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate if shipping address has been set.
		if (!isset($this->session->data['shipping_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}

		if (!isset($this->request->post['shipping_method'])) {
			$json['error']['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
			}
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			//$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			$this->session->data['comment'] = '';

			if (!empty($this->request->post['comment'])) {
				$this->session->data['comment'] = $this->request->post['comment'];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}