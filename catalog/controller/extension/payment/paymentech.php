<?php
class ControllerExtensionPaymentPaymentech extends Controller {
	public function index() {
    	$this->language->load('extension/payment/paymentech');
		
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

		$data['cards'][] = array(
			'text'  => 'Discover Card', 
			'value' => 'DISCOVER'
		);
		
		$data['cards'][] = array(
			'text'  => 'American Express', 
			'value' => 'AMEX'
		);

		/*$data['cards'][] = array(
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

		return $this->load->view('extension/payment/paymentech', $data);
	}

	public function send() {
	
		//ini_set("display_errors",0);
		//ini_set("error_reporting",E_ERROR);
			
		require_once('catalog/controller/include/minixml.inc.php');
		
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');
			
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$today = date('HismdY'); 
				 
	  	if ($this->config->get('payment_paymentech_test') == '1') {
			define ('GATEWAY_URL', 'https://orbitalvar1.chasepaymentech.com/authorize'); 
			$my_merchantid = $this->config->get('payment_paymentech_test_merchant');
		} else {
			
			define ('GATEWAY_URL', 'https://orbital1.paymentech.net'); 
		}	$my_merchantid = $this->config->get('payment_paymentech_pr_merchant');	
		
		
		// Card Information for admin
		$this->session->data['ctype'] = $this->request->post['cc_type']; 
		$this->session->data['cnum']  = substr($this->request->post['cc_number'],-4,4); 
		$this->session->data['cexp']  = $this->request->post['cc_expire_date_month'].",".$this->request->post['cc_expire_date_year']; 
		// End
		//print_r($order_info);
		$cyear =  substr($this->request->post['cc_expire_date_year'], 2,4); 
		$CardSecValInd = '';
		if($this->request->post['cc_type']=='VISA' || $this->request->post['cc_type']=='DISCOVER'){
			$CardSecValInd = 1;
		}
		$curr_code_arr = array('USD'=>840,'CAD'=>124);
		$curr_code = $curr_code_arr[$order_info['currency_code']];
		if(!($curr_code)){
			$curr_code = 840;
		}
		$order_total = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
	  	$post_string = '<?xml version="1.0" encoding="UTF-8"?>
	            <Request>
	                        <NewOrder>
	                                    <OrbitalConnectionUsername>'.$this->config->get('payment_paymentech_username').'</OrbitalConnectionUsername>
	                                    <OrbitalConnectionPassword>'.$this->config->get('payment_paymentech_password').'</OrbitalConnectionPassword>
	                                    <IndustryType>MO</IndustryType>
	                                    <MessageType>'.$this->config->get('payment_paymentech_msgtype').'</MessageType>
	                                    <BIN>'.$this->config->get('payment_paymentech_bin').'</BIN>
	                                    <MerchantID>'.$my_merchantid.'</MerchantID>
	                                    <TerminalID>001</TerminalID>
	                                    <CardBrand></CardBrand>
	                                    <AccountNum>'.(str_replace(' ', '', $this->request->post['cc_number'])).'</AccountNum>
	                                    <Exp>'.($this->request->post['cc_expire_date_month'] .$cyear).'</Exp>
	                                   <CurrencyCode>'.$curr_code.'</CurrencyCode> 
	                                    <CurrencyExponent>2</CurrencyExponent>   
										<CardSecValInd>'.$CardSecValInd.'</CardSecValInd>
	                                   <CardSecVal>'.($this->request->post['cc_cvv2']).'</CardSecVal>
	                                    <AVSzip>'.($order_info['payment_postcode']).'</AVSzip>
	                                    <AVSaddress1>'.($order_info['payment_address_1']).'</AVSaddress1>
	                                    <AVScity>'.($order_info['payment_city']).'</AVScity>
	                                    <AVSstate>'.(($order_info['payment_iso_code_2'] != 'US') ? $order_info['payment_zone'] : $order_info['payment_zone_code']).'</AVSstate>
	                                    <AVSphoneNum>'.($order_info['telephone']).'</AVSphoneNum>
	                                    <AVSname>'.($this->request->post['card_name']).'</AVSname>
	                                    
	                                    <OrderID>'.$this->session->data['order_id'].'</OrderID>
	                                    <Amount>'.round(($order_total * 100), 0).'</Amount>
	                                    
	                                      <CustomerEmail>'.($order_info['email']).'</CustomerEmail>
	                        </NewOrder>
	            </Request>';
				// <Amount>'.$payment_info['amount'].'</Amount>
	            // <CardSecVal>'.$payment_info['cvv'].'</CardSecVal>
	          //  echo 'test: ';
	            
	            // Build header as array for cURL option
	            $header = array("POST /AUTHORIZE HTTP/1.0");
	            $header[] = "MIME-Version: 1.0";
	            $header[] = "Content-type: application/PTI56";
	            $header[] = "Content-length: ".strlen($post_string);
	            $header[] = "Content-transfer-encoding: text";
	            $header[] = "Request-number: 1";
	            $header[] = "Document-type: Request";
	            $header[] = "Interface-Version: 0.3";   
		
		
	 	
		
		// echo '<pre>'; print_r($post_string); echo '</pre>'; exit; 
	 	
		$ch = curl_init(GATEWAY_URL);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		
		$result = curl_exec($ch);
		

		
		curl_close($ch);
		
		$content = "Request: $post_string \n\n";
		$content.= serialize($result);

		if(!$result){
			$json['error'] = 'cURL Error';
		}
		
		if($this->config->get('payment_paymentech_debug')){
			if ($result) {
				$this->log->write('paymentech response: ' . $result . '');
			}
		}
		


	  	$returnedXMLDoc = new MiniXMLDoc();
	  	$returnedXMLDoc->fromString($result);
		$procstatus = $returnedXMLDoc->getElement('ProcStatus');
		$sorry = ' Sorry for this inconvenience. You may contact us for further assistance.';
		
		$json = array();
		

		
		if ($procstatus->getValue() === '0') {
				$response = '';
			$approvalstatus = $returnedXMLDoc->getElement('ApprovalStatus');
			$CVVrespcode = $returnedXMLDoc->getElement('CVV2RespCode');
			$AVSrespcode = $returnedXMLDoc->getElement('AVSRespCode');
			
	   		
			
			if ($approvalstatus->getValue() === '1') {

				$TxRefNum = $returnedXMLDoc->getElement('TxRefNum')->getValue();
					
				$message = '';
				if (isset($TxRefNum)) {
					$message .= 'TRANSACTIONID: ' . $TxRefNum . "\n";
				}	
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_paymentech_order_status_id'), $message, false);
		
				$json['success'] = $this->url->link('checkout/success');
		
			} elseif ($approvalstatus->getValue() === '0') {
			
				
			  	if(($CVVrespcode->getValue()!='N')&&($CVVrespcode->getValue()!='I')&&($CVVrespcode->getValue()!='Y')&&($AVSrespcode->getValue()!='2')&&($AVSrespcode->getValue()!='G')){					
				 	$response='Possible that card issuer does not participate with address OR card verification.'.$sorry;
					$json['error'] = $response;
				}
									
 			    if($AVSrespcode->getValue()=='6'){ 
					$response='System unavailable or timed-out.'.$sorry;
					$json['error'] = 'Credit Card Declined: '.$AVSrespcode->getValue().". ".$CVVrespcode->getValue().". ".$response;			   
				}
				
				if($AVSrespcode->getValue()==''){ 
					$response='AVS not passed.'.$sorry;
					$json['error'] = 'Credit Card Declined: '.$AVSrespcode->getValue().". ".$CVVrespcode->getValue().". ".$response;			   
				}
					
			}else {
				
				$json['error'] = 'Server_Error: '.$sorry;
	   		}
			
	   } else {
		   
	   	   $status = $returnedXMLDoc->getElement('StatusMsg');    	  
		   $json['error'] = 'System Error '.$procstatus->getValue().". ".$status->getValue().". ".$sorry;
       }
	  
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>