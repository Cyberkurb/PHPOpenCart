<?php
class ControllerExtensionPaymentAffirm extends Controller {
	public function index() {
			
        $this->load->language('extension/payment/pp_express');
        
        $this->load->model('extension/payment/affirm');
        $publicAPI = $this->model_extension_payment_affirm->getPublicAPI();
        $financial_id = $this->model_extension_payment_affirm->getProductID();
		
        $data['public_api'] = $publicAPI;
        $data['financial_id'] = $financial_id;
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $order_totals = $this->model_checkout_order->getOrderTotals($this->session->data['order_id']);
        $get_order_products = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
			
			//CREATE ARRAY TO HOLD PRODUCTS
			$order_products = array();
			
			foreach($get_order_products as $prod){				
			
					$order_products[] = array(
							'order_id'  => $$this->session->data['order_id'],
							'model'     => $prod['model'],
							'name'      => $prod['name'],
							'category'  => '',
							'price'     => (int)round($prod['price'],2)*100,
							'quantity'  => $prod['quantity']
					);
			
            }
        $get_order_shipping = $this->model_checkout_order->getOrderShipping($this->session->data['order_id']);
        $get_order_tax = $this->model_checkout_order->getOrderTax($this->session->data['order_id']);
        $get_order_discount = $this->model_checkout_order->getOrderDiscount($this->session->data['order_id']);
        

        
        $data['products'] = $order_products;
        

        if($get_order_tax['value'] > 0){
            $data['taxes'] = (int)round($get_order_tax['value'],2)*100;
        }
        else{
            $data['taxes'] = 0;
        }

        if($get_order_discount['value'] > 0){
            $data['discount'] = (int)round($get_order_discount['value'],2)*100;
        }
        else{
            $data['discount'] = 0;
        }
        if($get_order_shipping['value'] > 0){
            $data['shipping_cost'] = (int)round($get_order_shipping['value'],2)*100;
        }
        else{
            $data['shipping_cost'] = 0;
        }
        
        
        $data['order_id'] = $this->session->data['order_id'];
        $data['firstname'] = $order_info['firstname'];
        $data['lastname'] = $order_info['lastname'];
        $data['payment_address_1'] = $order_info['payment_address_1'];
        $data['payment_address_2'] = $order_info['payment_address_2'];
        $data['payment_postcode'] = $order_info['payment_postcode'];
        $data['payment_city'] = $order_info['payment_city'];
        $data['payment_zone'] = $order_info['payment_zone'];
        $data['shipping_address_1'] = $order_info['shipping_address_1'];
        $data['shipping_address_2'] = $order_info['shipping_address_2'];
        $data['shipping_postcode'] = $order_info['shipping_postcode'];
        $data['shipping_city'] = $order_info['shipping_city'];
        $data['shipping_zone'] = $order_info['shipping_zone'];
        $data['total'] = (int)round($order_info['total'],2)*100;
        $data['email'] = $order_info['email'];
        $data['telephone'] = $order_info['telephone'];
        $data['order_info'] = $order_info;
		return $this->load->view('extension/payment/affirm', $data);
	}

	public function send() {
            
		//ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);
        $this->load->model('extension/payment/affirm');
        $this->load->model('checkout/order');
        $this->load->model('catalog/product');
        $checkout_token = $_REQUEST['checkout_token'];
        //$env = $_REQUEST['env'];

        
        $checkout_token = $_REQUEST["checkout_token"];
        //$checkout_token = 'GEKR23GC5F6XX2T1';

		$endpoint = "charges/";
		$method = "POST";
		$data = array("checkout_token" => $checkout_token);
		$env = $_REQUEST["env"];

        $authorization = $this->request($endpoint, $method, $data, $env);
        
        $charge_id = $authorization['id'];

        $tansaction_data = array(
            "checkout_token" => $checkout_token,
            "env" => $charge_id
        );

        $transaction_data = $this->model_extension_payment_affirm->addTransaction($tansaction_data);

		$endpoint = "charges/" . $charge_id . "/capture";
		$method = "POST";
		$data = "";
		$env = $_REQUEST["env"];

        $authorization1 = $this->request($endpoint, $method, $data, $env);
        //print_r($authorization);
        //exit();

		if($this->config->get('config_store_id') == 16){
                $username = $this->session->data['order_entry_user'];
                $this->model_extension_payment_affirm->userValidation($username, $this->session->data['order_id']);
            }

        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 5, $checkout_token, false);
        
        $this->response->redirect($this->url->link('checkout/success', '', true));

        //$this->response->redirect($this->url->link('payment/affirm/capture', 'charge_id=' . $charge_id . '&checkout_token=' . $checkout_token, true));
        
    }
    public function request($a, $b, $c, $d) {

		//global $sandbox_public_key, $sandbox_private_key, $live_public_key, $live_private_key;

		//$live_base_url = "https://sandbox.affirm.com/api/v2/";
		$live_base_url = "https://api.affirm.com/api/v2/";
		
        $public_key = $this->model_extension_payment_affirm->getPublicAPI();
        $private_key = $this->model_extension_payment_affirm->getPrivateAPI();

        //$public_key = 'PIRVRINK36KYQ6XX';
        //$private_key = 'zOagNqidUaNmGOWrdHjWoQHtiX9YQ4Jj';
        $base_url = $live_base_url;

		$url = $base_url . $a;
		$json = json_encode($c);
		$header = array('Content-Type: application/json','Content-Length: ' . strlen($json));
		$keypair = $public_key . ":" . $private_key;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $b);
		curl_setopt($curl, CURLOPT_USERPWD, $keypair);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

		$response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);
		http_response_code($status); 
		return json_decode($response, true);
	}
}