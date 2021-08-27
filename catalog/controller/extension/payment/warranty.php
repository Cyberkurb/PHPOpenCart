<?php
class ControllerExtensionPaymentWarranty extends Controller {
	public function index() {
		
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    	/*$this->language->load('extension/payment/payfabric');
		
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
		);	
	
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
        }*/
        $data['action'] = $this->url->link('extension/payment/warranty/send', '', true);
		return $this->load->view('extension/payment/warranty', $data);
	}

	public function send() {
			
		//ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);
		
        $this->load->model('extension/payment/warranty');
        $this->load->model('checkout/order');
		$username = $this->session->data['order_entry_user'];
		$casenumber = $this->request->post['casenumber'];

        $user_approved = $this->model_extension_payment_warranty->userValidation($username, $casenumber, $this->session->data['order_id']);

        if($user_approved == 1){
            //$json['success'] = $this->url->link('checkout/success');
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 5, $transaction_data, false);
            $this->response->redirect($this->url->link('checkout/success', '', true));
        }
        else{
            $this->session->data['error'] = "Your Not Athorized for these types of orders";
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }
    }
}
?>