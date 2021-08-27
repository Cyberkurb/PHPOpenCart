<?php
class ControllerExtensionPaymentDonation extends Controller {
	public function index() {
		$data['continue'] = $this->url->link('checkout/success');
        $data['action'] = $this->url->link('extension/payment/donation/confirm', '', true);
		return $this->load->view('extension/payment/donation', $data);
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'donation') {


        $this->load->model('extension/payment/donation');
        $this->load->model('checkout/order');
        $rectype = $this->request->post['rectype'];
        $receipt_detail = $this->request->post['receipt_detail'];
		$username = $this->session->data['order_entry_user'];
        $casenumber = $this->request->post['casenumber'];

        $user_approved = $this->model_extension_payment_donation->userValidationOnly($username);

        if($user_approved == 1){
            //$json['success'] = $this->url->link('checkout/success');
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 5, '', false);
            $this->response->redirect($this->url->link('checkout/success', '', true));
        }
        else{
            $this->session->data['error'] = "Your Not Athorized for these types of orders";
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }

			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 5);
		}
	}
}
