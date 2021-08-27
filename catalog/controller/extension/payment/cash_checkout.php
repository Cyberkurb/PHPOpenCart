<?php
class ControllerExtensionPaymentCashCheckout extends Controller {
	public function index() {
		$data['continue'] = $this->url->link('checkout/success');
        $data['action'] = $this->url->link('extension/payment/cash_checkout/confirm', '', true);
		return $this->load->view('extension/payment/cash_checkout', $data);
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'cash_checkout') {


        $this->load->model('extension/payment/cash_checkout');
        $this->load->model('checkout/order');
        $rectype = $this->request->post['rectype'];
        $receipt_detail = $this->request->post['receipt_detail'];
		$username = $this->session->data['order_entry_user'];
        $casenumber = $this->request->post['casenumber'];

        $user_approved = $this->model_extension_payment_cash_checkout->userValidation($username, $casenumber, $rectype, $receipt_detail, $this->session->data['order_id']);

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

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_free_checkout_order_status_id'));
		}
	}
}
