<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		
		$this->load->language('checkout/success');
		$order_id = $this->session->data['order_id'];

		//GA ECommerces get Order-details
		if(isset($order_id))
		{
			//LOAD MODEL
			$this->load->model('checkout/order');
			
			//GET ORDER DETAILS
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			//NEW MODEL TO COLLECT TAX
			$get_order_tax = $this->model_checkout_order->getOrderTax($order_id);
			
			if($get_order_tax){
					//ASSIGN TAX TO NEW VARIABLE
					$order_tax = $get_order_tax['value'];
			} else {
					//THERE WAS NO TAX COLLECTED
					$order_tax = '';
			}
			
			//NEW MODEL TO COLLECT SHIPPING
			$get_order_shipping = $this->model_checkout_order->getOrderShipping($order_id);
			$get_order_discounts = $this->model_checkout_order->getOrderDiscount($order_id);
			
			if($get_order_shipping){
					//ASSIGN SHIPPING TO NEW VARIABLE
					$order_shipping = $get_order_shipping['value'];
			} else {
					//THERE WAS NO SHIPPING COLLECTED
					$order_shipping = 0;
			}

			if($get_order_discounts){
				//ASSIGN SHIPPING TO NEW VARIABLE
				$order_discount_value = $get_order_discounts['value'];
				$order_discount_code = str_replace("Coupon (", "", $get_order_discounts['title']);
				$order_discount_code = str_replace(")", "", $order_discount_code);
			} else {
				//THERE WAS NO SHIPPING COLLECTED
				$order_discount_value = 0;
				$order_discount_code = '';
			}
			
			//NEW MODEL TO COLLECT ALL PRODUCTS ASSOCIATED WITH ORDER
			$get_order_products = $this->model_checkout_order->getOrderProducts($order_id);
			
			//CREATE ARRAY TO HOLD PRODUCTS
			$order_products = array();
			
			foreach($get_order_products as $prod){				
			
					$order_products[] = array(
							'order_id'  => $order_id,
							'model'     => $prod['model'],
							'name'      => $prod['name'],
							'category'  => '',
							'price'     => number_format($prod['price'], 2, '.', ','),
							'quantity'  => $prod['quantity']
					);
			
			}
			
			//NEW ORDER ARRAY
			$order_tracker = array(
					'order_id'    => $order_id,
					'customer_id' => $order_info['customer_id'],
					'store_name'  => $order_info['store_name'],
					'firstname'	  => $order_info['firstname'],
					'lastname'	  => $order_info['lastname'],
					'email'	  	  => $order_info['email'],
					'email_sha'   => sha1($order_info['email']),
					'clickid'     => $this->session->data['irclickid'],
					'total'       => $order_info['total'],
					'tax'         => $order_tax,
					'shipping'    => $order_shipping,
					'discount_c'  => $order_discount_code,
                    'discount_v'  => $order_discount_value,
					'city'        => $order_info['payment_city'],
					'state'       => $order_info['payment_zone'],
					'country'     => $order_info['payment_country'],
					'currency'    => $order_info['currency_code'],
					'products'    => $order_products
			);   
			$data['order_tracker'] = $order_tracker;
		}
		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);

			if($this->config->get('config_store_id') == 14){
				unset($this->session->data['customer_id']);
			}
			elseif($this->config->get('config_store_id') == 16){
				unset($this->session->data['customer_id']);
			}
			elseif($this->config->get('config_store_id') == 7){
				unset($this->session->data['customer_id']);
			}
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}