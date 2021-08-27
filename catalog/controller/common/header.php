<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');
		$this->load->model('setting/store');
		$this->load->model('account/customer');
		
		$ipblock = $this->model_setting_store->getBadIPAddress();
		if ($ipblock != 0) {
			header("location: https://e8vjxwgyl0.execute-api.us-west-2.amazonaws.com/ProdStage/");
			exit();
		 } 	
		/*
		if(isset($this->session->data['country_code'])){
			$data['user_country'] = $this->session->data['country_code'];
		 }
		 else{
			$country_alert = $this->model_setting_store->getUserLocation();
			$data['user_country'] = $country_alert;
			$this->session->data['country_code'] = $country_alert;
		 }
		 
		if($this->config->get('config_store_id') == 10){
			if($country_alert != 'CA'){
				$data['country_alert'] = 1;
			}
			else{
				$data['country_alert'] = 0;
			}
		}
		elseif($this->config->get('config_store_id') == 11){
			if($country_alert != 'CA'){
				$data['country_alert'] = 1;
			}
			else{
				$data['country_alert'] = 0;
			}
		}
		elseif($this->config->get('config_store_id') == 101){
			if($country_alert != 'CA'){
				$data['country_alert'] = 1;
			}
			else{
				$data['country_alert'] = 0;
			}
		}
		elseif($this->config->get('config_store_id') == 100){
			if($country_alert != 'CA'){
				$data['country_alert'] = 1;
			}
			else{
				$data['country_alert'] = 0;
			}
		}
		else{
			if($country_alert == 'CA'){
				$data['country_alert'] = 1;
			}
			else{
				$data['country_alert'] = 0;
			}
		}
		*/
		$data['country_alert'] = 0;
		if($this->customer->getId()){
            $detailcustomer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$data['sha_email'] = sha1($detailcustomer_info['email']);
			$data['user_group_id'] = $detailcustomer_info['customer_group_id'];
            $data['customer_id'] = $this->customer->getId();
        }
        else{
            $data['sha_email'] = '';
            $data['customer_id'] = '';
        }

		$data['url'] = $this->request->server['REQUEST_URI'];
          
		/*$data['analytics'] = array();
		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}
		*/
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$this->load->model('checkout/order');

		$current_cart_value = $this->model_checkout_order->cartValue();

		$data['item_count'] = $current_cart_value;

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}
		if($this->config->get('config_store_id') == 14){
			if(isset($this->session->data['order_entry_user'])){
				$data['user_needed'] = 0;
				$data['order_entry_user'] = $this->session->data['order_entry_user'];
			}
			else{
				$data['user_needed'] = 1;
				$data['order_entry_user'] = '';
			}
		}
		elseif($this->config->get('config_store_id') == 7){
			if(isset($this->session->data['order_entry_user'])){
				$data['user_needed'] = 0;
				$data['order_entry_user'] = $this->session->data['order_entry_user'];
			}
			else{
				$data['user_needed'] = 1;
				$data['order_entry_user'] = '';
			}
		}
		elseif($this->config->get('config_store_id') == 16){
			if(isset($this->session->data['order_entry_user'])){
				$data['user_needed'] = 0;
				$data['order_entry_user'] = $this->session->data['order_entry_user'];
			}
			else{
				$data['user_needed'] = 1;
				$data['order_entry_user'] = '';
			}
		}
		elseif($this->config->get('config_store_id') == 12){
		//elseif($this->config->get('config_store_id') == 16){
			if(!$this->customer->isLogged()){
				$this->response->redirect($this->url->link('account/login/dealer'));
			}
		}

		$this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['store_id'] = $this->config->get('config_store_id');
		//$data['menu'] = $this->load->controller('common/menu');
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['url'] = $this->request->server['REQUEST_URI'];


		return $this->load->view('common/header', $data);
	}

	public function crmorder_login(){
		$username = $this->request->post['username'];
		$this->load->model('extension/payment/warranty');
		$user_approved = $this->model_extension_payment_warranty->userValidationOnly($username);
		//echo $username;
		
		if($user_approved == 1){
			$user_group_id = $this->model_extension_payment_warranty->userGroup($username);
			$this->session->data['order_entry_user'] = $username;
			$this->session->data['user_admin_id'] = $user_group_id;
		}
		else{
			unset($this->session->data['order_entry_user']);
			unset($this->session->data['user_admin_id']);
		}

		$this->response->redirect($this->url->link('common/home'));
	}

	public function crmlogout(){
		unset($this->session->data['order_entry_user']);
		unset($this->session->data['user_admin_id']);
		$this->response->redirect($this->url->link('common/home'));
	}

	public function header_login() {
		// Analytics
		$this->load->model('setting/extension');
		$this->load->model('setting/store');
		$this->load->model('account/customer');
		
		$ipblock = $this->model_setting_store->getBadIPAddress();
		if ($ipblock != 0) {
			header("location: https://e8vjxwgyl0.execute-api.us-west-2.amazonaws.com/ProdStage/");
			exit();
		 } 	

		
		if($this->customer->getId()){
            $detailcustomer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$data['sha_email'] = sha1($detailcustomer_info['email']);
			$data['user_group_id'] = $detailcustomer_info['customer_group_id'];
            $data['customer_id'] = $this->customer->getId();
        }
        else{
            $data['sha_email'] = '';
            $data['customer_id'] = '';
        }

		$data['url'] = $this->request->server['REQUEST_URI'];
          
		/*$data['analytics'] = array();
		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}
		*/
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$this->load->model('checkout/order');

		$current_cart_value = $this->model_checkout_order->cartValue();

		$data['item_count'] = $current_cart_value;

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		//$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		$this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['store_id'] = $this->config->get('config_store_id');
		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['url'] = $this->request->server['REQUEST_URI'];


		return $this->load->view('common/header_login', $data);
	}
}
