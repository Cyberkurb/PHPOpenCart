<?php 
class ControllerExtensionPaymentPaymentech extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('extension/payment/paymentech');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_paymentech', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');
		
		$data['entry_test_merchant'] = $this->language->get('entry_test_merchant');
		$data['entry_pr_merchant'] = $this->language->get('entry_pr_merchant');
		$data['entry_payment_trace'] = $this->language->get('entry_payment_trace');
		
		$data['entry_payment_bin'] = $this->language->get('entry_payment_bin');
		$data['entry_payment_msgtype'] = $this->language->get('entry_payment_msgtype');
		$data['entry_payment_tz'] = $this->language->get('entry_payment_tz');
		$data['entry_debug'] = $this->language->get('entry_debug');
		
		
		
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}
		
 		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}
		
 		if (isset($this->error['signature'])) {
			$data['error_signature'] = $this->error['signature'];
		} else {
			$data['error_signature'] = '';
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/paymentech', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$data['action'] = $this->url->link('extension/payment/paymentech', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL');
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL');

		if (isset($this->request->post['payment_paymentech_test_merchant'])) {
			$data['payment_paymentech_test_merchant'] = $this->request->post['payment_paymentech_test_merchant'];
		} else {
			$data['payment_paymentech_test_merchant'] = $this->config->get('payment_paymentech_test_merchant');
		}
		
		if (isset($this->request->post['payment_paymentech_pr_merchant'])) {
			$data['payment_paymentech_pr_merchant'] = $this->request->post['payment_paymentech_pr_merchant'];
		} else {
			$data['payment_paymentech_pr_merchant'] = $this->config->get('payment_paymentech_pr_merchant');
		}
				
		if (isset($this->request->post['payment_paymentech_payment_trace'])) {
			$data['payment_paymentech_payment_trace'] = $this->request->post['payment_paymentech_payment_trace'];
		} else {
			$data['payment_paymentech_payment_trace'] = $this->config->get('payment_paymentech_payment_trace');
		}
		
		if (isset($this->request->post['payment_paymentech_bin'])) {
			$data['payment_paymentech_bin'] = $this->request->post['payment_paymentech_bin'];
		} else {
			$data['payment_paymentech_bin'] = $this->config->get('payment_paymentech_bin');
		}
		
		if (isset($this->request->post['payment_paymentech_msgtype'])) {
			$data['payment_paymentech_msgtype'] = $this->request->post['payment_paymentech_msgtype'];
		} else {
			$data['payment_paymentech_msgtype'] = $this->config->get('payment_paymentech_msgtype');
		}
		
		if (isset($this->request->post['payment_paymentech_payment_tz'])) {
			$data['payment_paymentech_payment_tz'] = $this->request->post['payment_paymentech_payment_tz'];
		} else {
			$data['payment_paymentech_payment_tz'] = $this->config->get('payment_paymentech_payment_tz');
		}
		
		if (isset($this->request->post['payment_paymentech_test'])) {
			$data['payment_paymentech_test'] = $this->request->post['payment_paymentech_test'];
		} else {
			$data['payment_paymentech_test'] = $this->config->get('payment_paymentech_test');
		}
		
		
		
		if (isset($this->request->post['payment_paymentech_total'])) {
			$data['payment_paymentech_total'] = $this->request->post['payment_paymentech_total'];
		} else {
			$data['payment_paymentech_total'] = $this->config->get('payment_paymentech_total'); 
		} 
				
		if (isset($this->request->post['payment_paymentech_order_status_id'])) {
			$data['payment_paymentech_order_status_id'] = $this->request->post['payment_paymentech_order_status_id'];
		} else {
			$data['payment_paymentech_order_status_id'] = $this->config->get('payment_paymentech_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payment_paymentech_geo_zone_id'])) {
			$data['payment_paymentech_geo_zone_id'] = $this->request->post['payment_paymentech_geo_zone_id'];
		} else {
			$data['payment_paymentech_geo_zone_id'] = $this->config->get('payment_paymentech_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['payment_paymentech_status'])) {
			$data['payment_paymentech_status'] = $this->request->post['payment_paymentech_status'];
		} else {
			$data['payment_paymentech_status'] = $this->config->get('payment_paymentech_status');
		}
		
		if (isset($this->request->post['payment_paymentech_sort_order'])) {
			$data['payment_paymentech_sort_order'] = $this->request->post['payment_paymentech_sort_order'];
		} else {
			$data['payment_paymentech_sort_order'] = $this->config->get('payment_paymentech_sort_order');
		}
		
		if (isset($this->request->post['payment_paymentech_username'])) {
			$data['payment_paymentech_username'] = $this->request->post['payment_paymentech_username'];
		} else {
			$data['payment_paymentech_username'] = $this->config->get('payment_paymentech_username');
		}
		
		if (isset($this->request->post['payment_paymentech_password'])) {
			$data['payment_paymentech_password'] = $this->request->post['payment_paymentech_password'];
		} else {
			$data['payment_paymentech_password'] = $this->config->get('payment_paymentech_password');
		}
		if (isset($this->request->post['payment_paymentech_debug'])) {
			$data['payment_paymentech_debug'] = $this->request->post['payment_paymentech_debug'];
		} else {
			$data['payment_paymentech_debug'] = $this->config->get('payment_paymentech_debug');
		}


		
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paymentech', $data));
		
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paymentech')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		/*if (!$this->request->post['payment_paymentech_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['payment_paymentech_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['payment_paymentech_signature']) {
			$this->error['signature'] = $this->language->get('error_signature');
		}*/
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>