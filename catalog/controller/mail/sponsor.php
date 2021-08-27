<?php
class ControllerMailSponsor extends Controller {
	public function index(&$route, &$args, &$output) {
		$this->load->language('mail/register');

		$data['text_welcome'] = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$data['text_login'] = $this->language->get('text_login');
		$data['text_approval'] = $this->language->get('text_approval');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_thanks'] = $this->language->get('text_thanks');

		$this->load->model('account/sponsor');
		$customer_id = $this->customer->getId();
		$sponser_form = $this->model_account_sponsor->getSponsorRequest($customer_id);

		if($sponser_form['charity'] == 1){
			$notification_email = 'julie.day@dansons.com';
		}
		else{
			$notification_email = 'partnerships@dansons.com';
		}

		$data['firstname'] = html_entity_decode($sponser_form['firstname'], ENT_QUOTES, 'UTF-8');
		$data['lastname'] = html_entity_decode($sponser_form['lastname'], ENT_QUOTES, 'UTF-8');
		$data['email'] = html_entity_decode($sponser_form['email'], ENT_QUOTES, 'UTF-8');
		$data['phone'] = html_entity_decode($sponser_form['phone'], ENT_QUOTES, 'UTF-8');
		$data['address_1'] = html_entity_decode($sponser_form['address_1'], ENT_QUOTES, 'UTF-8');
		$data['address_2'] = html_entity_decode($sponser_form['address_2'], ENT_QUOTES, 'UTF-8');
		$data['city'] = html_entity_decode($sponser_form['city'], ENT_QUOTES, 'UTF-8');
		$data['postcode'] = html_entity_decode($sponser_form['postcode'], ENT_QUOTES, 'UTF-8');
		$data['event_address'] = html_entity_decode($sponser_form['event_address'], ENT_QUOTES, 'UTF-8');
		$data['event_phone'] = html_entity_decode($sponser_form['event_phone'], ENT_QUOTES, 'UTF-8');
		$data['charity'] = html_entity_decode($sponser_form['charity'], ENT_QUOTES, 'UTF-8');
		$data['event_date'] = html_entity_decode($sponser_form['event_date'], ENT_QUOTES, 'UTF-8');
		$data['event_audience'] = html_entity_decode($sponser_form['event_audience'], ENT_QUOTES, 'UTF-8');
		$data['event_desc'] = html_entity_decode($sponser_form['event_desc'], ENT_QUOTES, 'UTF-8');
		$data['event_donationvalue'] = html_entity_decode($sponser_form['event_donationvalue'], ENT_QUOTES, 'UTF-8');
		$data['event_whoelse'] = html_entity_decode($sponser_form['event_whoelse'], ENT_QUOTES, 'UTF-8');
		$data['event_comment'] = html_entity_decode($sponser_form['event_comment'], ENT_QUOTES, 'UTF-8');
		
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($notification_email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode('Donation Request', ENT_QUOTES, 'UTF-8'));
		$mail->setText($this->load->view('mail/sponsor', $data));
		$mail->send();

		$mail->setTo($sponser_form['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode('Donation/Sponsorship Request Submitted', ENT_QUOTES, 'UTF-8'));
		$mail->setText($this->load->view('mail/sponsor', $data));
		$mail->send();
	}
}		