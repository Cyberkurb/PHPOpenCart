<?php
class ControllerExtensionPaymentPayfabric extends Controller {
	public function index() {
            
        //ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);
    	$this->language->load('extension/payment/payfabric');
		
		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_start_date'] = $this->language->get('text_start_date');
		$data['text_issue'] = $this->language->get('text_issue');
		$data['text_wait'] = $this->language->get('text_wait');
		
		$data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_start_date'] = $this->language->get('entry_cc_start_date');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$data['entry_cc_issue'] = $this->language->get('entry_cc_issue');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['help_issue'] = $this->language->get('help_issue');
		$data['help_start_date'] = $this->language->get('help_start_date');
		
		
		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$data['cards'] = array();

		$data['cards'][] = array(
			'text'  => 'Visa', 
			'value' => 'VISA'
		);

		$data['cards'][] = array(
			'text'  => 'MasterCard', 
			'value' => 'MASTERCARD'
		);
/*
		$data['cards'][] = array(
			'text'  => 'Discover Card', 
			'value' => 'DISCOVER'
		);
		
		$data['cards'][] = array(
			'text'  => 'American Express', 
			'value' => 'AMEX'
		);

		$data['cards'][] = array(
			'text'  => 'Maestro', 
			'value' => 'SWITCH'
		);
		
		$data['cards'][] = array(
			'text'  => 'Solo', 
			'value' => 'SOLO'
		);	*/	
	
		$data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();
		
		$data['year_valid'] = array();
		
		for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {	
			$data['year_valid'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)), 
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
        }
        $data['action'] = $this->url->link('extension/payment/payfabric/send', '', true);

		return $this->load->view('extension/payment/payfabric', $data);
	}

	public function send() {
            
		//ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);
		
		$this->load->model('checkout/order');
        $this->load->model('catalog/product');
        $this->load->model('extension/payment/payfabric');

        $paymentapproved = $this->model_extension_payment_payfabric->orderDone($this->session->data['order_id']);

        if($paymentapproved == 1){
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			$httpUrl = 'https://www.payfabric.com/payment/api/transaction/process?cvc='.$this->request->post['cc_cvv2']; 
            $my_merchantid = $this->model_extension_payment_payfabric->getDeviceID();
            $my_pass = $this->model_extension_payment_payfabric->getDevicePass();
            $my_customer = $this->model_extension_payment_payfabric->getCustomerId();	
            $my_currency = $this->model_extension_payment_payfabric->getCurrency();
            $setupid = $this->model_extension_payment_payfabric->getSetupid();
            $setup_country = $this->model_extension_payment_payfabric->getCountry();

        $transaction = array(
            "Amount" => ($order_info['total']*$order_info['currency_value']),
            "Card" => array(
              "Account" => str_replace(' ', '', $this->request->post['cc_number']),
              "CVC" => $this->request->post['cc_cvv2'],
              "Billto"  => array(
                "City"      => $order_info['payment_city'],
                "Country"   => $setup_country,
                "Email"     => $order_info['email'],
                "Line1"     => $order_info['payment_address_1'],
                "Line2"     => "",
                "Phone"     => $order_info['telephone'],
                "State"     => (($order_info['payment_iso_code_2'] != 'US') ? $order_info['payment_zone'] : $order_info['payment_zone_code']),
                "Zip"       => $order_info['payment_postcode']
              ),
              "CardHolder"  => array(
                "FirstName" => $order_info['payment_firstname'],
                "LastName"  => $order_info['payment_lastname']
              ),
              "Customer"    => $order_info['customer_id'],
              "ExpDate"     => $this->request->post['cc_expire_date_month']."".$this->request->post['cc_expire_date_year']
            ),
            "Currency"  => $my_currency,
            "Customer"  => $order_info['customer_id'],
            "Document"  => array("Head"  =>  array(
                    array("Name" => "InvoiceNumber", "Value" => "TWS".$order_info['order_id']),
                    array("Name" => "PONumber", "Value" => "TWS".$order_info['order_id'])
            )),
            "SetupId"   => $setupid,
            "Tender"    => "CreditCard",
            "Shipto"    => array(
                "City"      => $order_info['shipping_city'],
                "Country"   => $setup_country,
                "Email"     => $order_info['email'],
                "Line1"     => $order_info['shipping_address_1'],
                "Line2"     => "",
                "Phone"     => $order_info['telephone'],
                "State"     => (($order_info['shipping_iso_code_2'] != 'US') ? $order_info['shipping_zone'] : $order_info['shipping_zone_code']),
                "Zip"       => $order_info['payment_postcode']
            ),
            "Type"      => "Sale"
        );
 
        // Convert the data to JSON.
        $json = json_encode($transaction, TRUE);

        $httpHeader = array(
                        "Content-Type: application/json",
                        "authorization: " . $my_merchantid . "|" . $my_pass
                        );        
            
        // Execute the HTTP request.
        
        $ch = curl_init($httpUrl);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		
        $httpResponseBody = curl_exec($ch);
        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        $json = array();
        if ($httpResponseCode >= 300) {
            $this->session->data['error'] = "There was a problem ". $httpResponseCode;
        }
        
        // Convert the JSON into a multi-dimensional array.
        $responseArray = json_decode($httpResponseBody, TRUE);
        // Output the results of the request.
        $transaction_data = $this->model_extension_payment_payfabric->addTransaction($responseArray);
        //echo var_dump($responseArray);
        //exit();
        sleep(1);
        $transaction_status = $this->model_extension_payment_payfabric->getTransactionStatus($transaction_data);
        //echo $transaction_status;
        //exit();
        if($transaction_status == 'Approved'){
            
            //$json['success'] = $this->url->link('checkout/success');
            if($this->config->get('config_store_id') == 14){
                $username = $this->session->data['order_entry_user'];
                $this->model_extension_payment_payfabric->userValidation($username, $this->session->data['order_id']);
            }
            elseif($this->config->get('config_store_id') == 16){
                $this->load->model('extension/payment/cash_checkout');
                $username = $this->session->data['order_entry_user'];
                $rectype = $this->request->post['rectype'];
                $receipt_detail = $this->request->post['receipt_detail'];
                $this->model_extension_payment_cash_checkout->userValidation($username, '', $rectype, $receipt_detail, $this->session->data['order_id']);
                //$this->model_extension_payment_payfabric->userValidation($username, $this->session->data['order_id']);
            }
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 5, $transaction_data, false);
            
            $this->response->redirect($this->url->link('checkout/success', '', true));
        }
        else{
            $this->session->data['error'] = "Your credit card didn't process, please check your information and try again.";
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }
    }
    else{
        $this->session->data['error'] = "Your order has already been completed, and will not adjust.";
        $this->response->redirect($this->url->link('checkout/checkout', '', true));
    }
    }



    public function cancel() {
            
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
		
		$this->load->model('checkout/order');
        $this->load->model('catalog/product');
        $this->load->model('extension/payment/payfabric');

        $my_merchantid = $this->model_extension_payment_payfabric->getDeviceID();
        $my_pass = $this->model_extension_payment_payfabric->getDevicePass();
        $my_customer = $this->model_extension_payment_payfabric->getCustomerId();	
        $my_currency = $this->model_extension_payment_payfabric->getCurrency();
        $setupid = $this->model_extension_payment_payfabric->getSetupid();
        $setup_country = $this->model_extension_payment_payfabric->getCountry();

        // Setup the HTTP request.
        $httpUrl = "https://www.payfabric.com/payment/api/reference/19051000855078?trxtype=Void";
        $httpHeader = Array(
                "Content-Type: application/json",
                "authorization: " . $my_merchantid . "|" . $my_pass);        
        $curlOptions = Array(CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => $httpHeader);
        // Execute the HTTP request.
        $curlHandle = curl_init($httpUrl);
        curl_setopt_array($curlHandle, $curlOptions);
        $httpResponseBody = curl_exec($curlHandle);
        $httpResponseCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);
        if ($httpResponseCode >= 300) {
            // Handle errors.
        }          
        // Convert the JSON into a multi-dimensional array.
        $responseArray = json_decode($httpResponseBody, TRUE);
        $transaction_data = $this->model_extension_payment_payfabric->addTransaction($responseArray);
        // Output the results of the request.
        var_dump($httpResponseBody[0]["Message"]);
        return $responseArray;        
    }
}
?>
