<?php
class ControllerAccountCharity extends Controller {
	private $error = array();

	public function index() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/charity', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/address');

		$this->document->setTitle($this->language->get('Charity Request'));

		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/customer');
        $this->load->model('account/address');
		$this->load->model('account/sponsor');
		
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$address_id = $this->model_account_address->addAddress($this->customer->getId(), $this->request->post);
            $sponsor_id = $this->model_account_sponsor->addCharity($this->customer->getId(), $address_id, $this->request->post);

			$this->response->redirect($this->url->link('account/success/charity'));
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

        if (isset($this->error['charity'])) {
			$data['error_charity'] = $this->error['charity'];
		} else {
			$data['error_charity'] = '';
        }
        if (isset($this->error['designation'])) {
			$data['error_designation'] = $this->error['designation'];
		} else {
			$data['error_designation'] = '';
        }
        
        if (isset($this->error['designationcomment'])) {
			$data['error_designationcomment'] = $this->error['designationcomment'];
		} else {
			$data['error_designationcomment'] = '';
        }
        if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
        }
        
        if (isset($this->error['eventname'])) {
			$data['error_eventname'] = $this->error['eventname'];
		} else {
			$data['error_eventname'] = '';
		}
        
        if (isset($this->error['eventdate'])) {
			$data['error_eventdate'] = $this->error['eventdate'];
		} else {
			$data['error_eventdate'] = '';
		}
        
        if (isset($this->error['eventbriefdesc'])) {
			$data['error_eventbriefdesc'] = $this->error['eventbriefdesc'];
		} else {
			$data['error_eventbriefdescd'] = '';
		}
        
        if (isset($this->error['whoelse'])) {
			$data['error_whoelse'] = $this->error['whoelse'];
		} else {
			$data['error_whoelse'] = '';
		}
        
        if (isset($this->error['comment'])) {
			$data['error_comment'] = $this->error['comment'];
		} else {
			$data['error_comment'] = '';
		}
        
        if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['address_1'])) {
			$data['error_address_1'] = $this->error['address_1'];
		} else {
			$data['error_address_1'] = '';
		}

		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}

		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}

		$data['action'] = $this->url->link('account/charity', '', true);

		if (isset($this->request->get['sponsor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $sponsor_info = $this->model_account_sponsor->getSponsor($this->request->get['sponsor_id']);
			$address_info = $this->model_account_address->getAddress($sponsor_info['address_id']);
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($address_info)) {
			$data['firstname'] = $address_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($address_info)) {
			$data['email'] = $address_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['address_1'])) {
			$data['address_1'] = $this->request->post['address_1'];
		} elseif (!empty($address_info)) {
			$data['address_1'] = $address_info['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
			$data['address_2'] = $this->request->post['address_2'];
		} elseif (!empty($address_info)) {
			$data['address_2'] = $address_info['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
			$data['postcode'] = $this->request->post['postcode'];
		} elseif (!empty($address_info)) {
			$data['postcode'] = $address_info['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} elseif (!empty($address_info)) {
			$data['city'] = $address_info['city'];
		} else {
			$data['city'] = '';
        }
        
        if (isset($this->request->post['designation'])) {
			$data['designation'] = $this->request->post['designation'];
		} elseif (!empty($address_info)) {
			$data['designation'] = $address_info['designation'];
		} else {
			$data['designation'] = '';
        }

        if (isset($this->request->post['designationcomment'])) {
			$data['designationcomment'] = $this->request->post['designationcomment'];
		} elseif (!empty($address_info)) {
			$data['designationcomment'] = $address_info['designationcomment'];
		} else {
			$data['designationcomment'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = (int)$this->request->post['country_id'];
		}  elseif (!empty($address_info)) {
			$data['country_id'] = $address_info['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

        if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		}  elseif (!empty($sponsor_info)) {
			$data['telephone'] = $sponsor_info['telephone'];
		} else {
			$data['telephone'] = '';
		}
        
        if (isset($this->request->post['charity'])) {
			$data['charity'] = (int)$this->request->post['charity'];
		}  elseif (!empty($sponsor_info)) {
			$data['charity'] = $sponsor_info['charity'];
		} else {
			$data['charity'] = '';
		}
        
        if (isset($this->request->post['eventname'])) {
			$data['eventname'] = $this->request->post['eventname'];
		}  elseif (!empty($sponsor_info)) {
			$data['eventname'] = $sponsor_info['eventname'];
		} else {
			$data['eventname'] = '';
		}
        
        if (isset($this->request->post['eventdate'])) {
			$data['eventdate'] = $this->request->post['eventdate'];
		}  elseif (!empty($sponsor_info)) {
			$data['eventdate'] = $sponsor_info['eventdate'];
		} else {
			$data['eventdate'] = '';
		}
        
        if (isset($this->request->post['eventbriefdesc'])) {
			$data['eventbriefdesc'] = $this->request->post['eventbriefdesc'];
		}  elseif (!empty($sponsor_info)) {
			$data['eventbriefdesc'] = $sponsor_info['eventbriefdesc'];
		} else {
			$data['eventbriefdesc'] = '';
		}
        
        if (isset($this->request->post['whoelse'])) {
			$data['whoelse'] = $this->request->post['whoelse'];
		}  elseif (!empty($sponsor_info)) {
			$data['whoelse'] = $sponsor_info['whoelse'];
		} else {
			$data['whoelse'] = '';
		}
        
        if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		}  elseif (!empty($sponsor_info)) {
			$data['comment'] = $sponsor_info['comment'];
		} else {
			$data['comment'] = '';
		}
        
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/charity', $data));
	}

	protected function validateForm() {
		
        if ((utf8_strlen(trim($this->request->post['charity'])) < 1) || (utf8_strlen(trim($this->request->post['charity'])) > 32)) {
			$this->error['charity'] = $this->language->get('error_charity');
        }
        
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
        }

		if ((utf8_strlen(trim($this->request->post['telephone'])) < 1) || (utf8_strlen(trim($this->request->post['telephone'])) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}


		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '' || !is_numeric($this->request->post['country_id'])) {
			$this->error['country'] = $this->language->get('error_country');
		}
		
		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
			$this->error['zone'] = $this->language->get('error_zone');
		}
		

		return !$this->error;
	}

	public function customfield() {
		
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}