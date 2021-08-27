<?php
class ControllerMailProduct extends Controller {
	public function index(&$route, &$args, &$output) {
		$this->load->language('mail/register');

		$data['text_welcome'] = "Thank you for Registering your grill. Below is your coupon code for 25% off.";
		$data['text_login'] = $this->language->get('text_login');
		$data['text_approval'] = "Coupon Code: ";
		$data['text_service'] = $this->language->get('text_service');
		$data['text_thanks'] = $this->language->get('text_thanks');

		$this->load->model('account/product');
        $this->load->model('account/customer');
        if ($this->customer->isLogged()) {
			$customer_id = $this->customer->getId();
		} else {
			$customer_id = $args[0]['customer_id'];
		}
					
		$product_info = $this->model_account_product->getLastProductReg($customer_id);
		$customer_info = $this->model_account_customer->getCustomer($args[0]);
        $data['approval'] = $product_info['coupon_code'];
			
		$data['login'] = $this->url->link('account/login', '', true);		
		$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($customer_info['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject("Product Registration");
		$mail->setText($this->load->view('mail/product', $data));
		$mail->send(); 
	}
	
	public function alert(&$route, &$args, &$output) {
		// Send to main admin email if new account email is enabled
		if (in_array('account', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/register');
			$data['text_welcome'] = "Thank you for Registering your grill. Below is your coupon code for 25% off.";
			$data['text_signup'] = $this->language->get('text_signup');
			$data['text_firstname'] = $this->language->get('text_firstname');
			$data['text_lastname'] = $this->language->get('text_lastname');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');
			$data['text_approval'] = "Coupon Code: ";
			
			
			$this->load->model('account/customer_group');
			
			if ($this->customer->isLogged()) {
                $customer_id = $this->customer->getId();
            } else {
                $customer_id = $args[0];
            }

			$this->load->model('account/customer');
            $product_info = $this->model_account_product->getLastProductReg();
			$customer_info = $this->model_account_customer->getCustomer($args[0]);
            $data['approval'] = $product_info['coupon_code'];
			
			$data['email'] = $customer_info['email'];
			$data['telephone'] = $customer_info['telephone'];
			$data['firstname'] = $customer_info['firstname'];
			$data['lastname'] = $customer_info['lastname'];

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->load->view('mail/product_alert', $data));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}	
	}
}		