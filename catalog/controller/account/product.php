<?php
class ControllerAccountProduct extends Controller {
	private $error = array();

	public function index() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/product', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/product');

		$this->document->setTitle($this->language->get('Product Reg'));

		$this->load->model('account/product');

		$this->getList();
	}

	public function add() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/product', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/product');

		$this->document->setTitle($this->language->get('Product Reg'));

		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/product');
        

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$add_reg = $this->model_account_product->addProduct($this->customer->getId(), $this->request->post);
			//$add_coupon = $this->model_account_product->newProductCoupon($this->customer->getId(), $product_id);
            //$update_product = $this->model_account_product->addCouponForReg($product_id, $add_coupon);
			if($add_reg != 0){
                $this->session->data['success'] = $this->language->get('text_add');    
            }
			else{
                $this->session->data['warning'] = $this->language->get('text_warning');
            }

			$this->response->redirect($this->url->link('account/product', '', true));
		}

		$this->getForm();
	}

	public function edit() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/product', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/product');

		$this->document->setTitle($this->language->get('Product Reg'));

		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/product');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$entry = $this->model_account_product->editProduct($this->request->get['product_id'], $this->request->post);
            if($entry != 0){
				$this->session->data['success'] = $this->language->get('text_edit');
            }
            else{
                $this->session->data['warning'] = $this->language->get('text_edit');
            }

			$this->response->redirect($this->url->link('account/product', '', true));
		}

		$this->getForm();
	}

	public function delete() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/product', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/product');

		$this->document->setTitle($this->language->get('Product Reg'));

		$this->load->model('account/product');

		if (isset($this->request->get['product_id']) && $this->validateDelete()) {
			$this->model_account_product->deleteProduct($this->request->get['product_id']);

			$this->session->data['success'] = "Registration was Deleted";

			$this->response->redirect($this->url->link('account/product', '', true));
		}

		$this->getList();
	}

	protected function getList() {
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/product', '', true)
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = "There was a problem";
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = "Registration Added / Updated";

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
        if (isset($this->session->data['warning'])) {
			$data['warning'] = "Your Serial Number wasn't valid.";

			unset($this->session->data['warning']);
		} else {
			$data['warning'] = '';
		}

		$data['products'] = array();

		$results = $this->model_account_product->getProducts();

		foreach ($results as $result) {
			
			$format = '{serialnumber}' . "\n" . '{purchasedate}' . "\n" . '{dateregistered}' . "\n" . '{purchaselocation}' . "\n <b>Coupon: ". '{coupon_code}' . "</b>\n" ;

			$find = array(
				'{serialnumber}',
				'{purchasedate}',
				'{purchaselocation}',
				'{dateregistered}',
                '{coupon_code}'
			);

			$replace = array(
				'serialnumber' => $result['serialnumber'],
				'purchasedate'  => $result['purchasedate'],
				'purchaselocation'   => $result['purchaselocation'],
				'dateregistered' => $result['dateregistered'],
                'coupon_code' => $result['coupon_code']
			);

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'product'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
				'update'     => $this->url->link('account/product/edit', 'product_id=' . $result['product_id'], true),
				'delete'     => $this->url->link('account/product/delete', 'product_id=' . $result['product_id'], true)
			);
		}

		$data['add'] = $this->url->link('account/product/add', '', true);
		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/product_list', $data));
	}

	protected function getForm() {
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/product', '', true)
		);

		if (!isset($this->request->get['product_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_product_add'),
				'href' => $this->url->link('account/product/add', '', true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_product_edit'),
				'href' => $this->url->link('account/product/edit', 'product_id=' . $this->request->get['product_id'], true)
			);
		}

		$data['text_product'] = !isset($this->request->get['product_id']) ? $this->language->get('text_product_add') : $this->language->get('text_product_edit');

		if (isset($this->error['serialnumber'])) {
			$data['error_serialnumber'] = $this->error['serialnumber'];
		} else {
			$data['error_serialnumber'] = '';
		}

		if (isset($this->error['purchasedate'])) {
			$data['error_purchasedate'] = $this->error['purchasedate'];
		} else {
			$data['error_purchasedate'] = '';
		}

		if (isset($this->error['purchaselocation'])) {
			$data['error_purchaselocation'] = $this->error['purchaselocation'];
		} else {
			$data['error_purchaselocation'] = '';
		}
        
        if (isset($this->error['purchaseproduct_id'])) {
			$data['error_purchaseproduct_id'] = $this->error['purchaseproduct_id'];
		} else {
			$data['error_purchaseproduct_id'] = '';
		}
		
		if (!isset($this->request->get['product_id'])) {
			$data['action'] = $this->url->link('account/product/add', '', true);
		} else {
			$data['action'] = $this->url->link('account/product/edit', 'product_id=' . $this->request->get['product_id'], true);
		}

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_account_product->getProduct($this->request->get['product_id']);
		}

		if (isset($this->request->post['serialnumber'])) {
            $serialval = $this->model_account_product->validateSerial($this->request->get['serialnumber']);
            if($serialval == 1){
                $data['serialnumber'] = $this->request->post['serialnumber'];
            }
		} elseif (!empty($product_info)) {
                $data['serialnumber'] = $product_info['serialnumber'];
		} else {
			$data['serialnumber'] = '';
		}

		if (isset($this->request->post['purchasedate'])) {
			$data['purchasedate'] = $this->request->post['purchasedate'];
		} elseif (!empty($product_info)) {
			$data['purchasedate'] = $product_info['purchasedate'];
		} else {
			$data['purchasedate'] = '';
		}

		if (isset($this->request->post['purchaselocation'])) {
			$data['purchaselocation'] = $this->request->post['purchaselocation'];
		} elseif (!empty($product_info)) {
			$data['purchaselocation'] = $product_info['purchaselocation'];
		} else {
			$data['purchaselocation'] = '';
		}
        
        if (isset($this->request->post['purchaseproduct_id'])) {
			$data['purchaseproduct_id'] = $this->request->post['purchaseproduct_id'];
		} elseif (!empty($product_info)) {
			$data['purchaseproduct_id'] = $product_info['purchaseproduct_id'];
		} else {
			$data['purchaseproduct_id'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom fields
		$data['custom_fields'] = array();
		
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'product') {
				$data['custom_fields'][] = $custom_field;
			}
		}

		if (isset($this->request->post['default'])) {
			$data['default'] = $this->request->post['default'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['default'] = $this->request->get['product_id'];
		} else {
			$data['default'] = false;
		}
        if($this->config->get('config_store_id') == 14){
			$data['availproduct'] = $this->model_account_product->availProductsCRM();
		}
		elseif($this->config->get('config_store_id') == 16){
			$data['availproduct'] = $this->model_account_product->availProductsCRM();
		}
		elseif($this->config->get('config_store_id') == 7){
			$data['availproduct'] = $this->model_account_product->availProductsCRM();
		}
		else{
			$data['availproduct'] = $this->model_account_product->availProducts();
		}
        

		$data['back'] = $this->url->link('account/product', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/product_form', $data));
	}

	protected function validateForm() {
		
		if ((utf8_strlen(trim($this->request->post['serialnumber'])) < 1)) {
			$this->error['serialnumber'] = $this->language->get('error_serialnumber');
		}

		if ((utf8_strlen(trim($this->request->post['purchasedate'])) < 1)) {
			$this->error['purchasedate'] = $this->language->get('error_purchasedate');
		}

		if ((utf8_strlen(trim($this->request->post['purchaselocation'])) < 3)) {
			$this->error['purchaselocation'] = $this->language->get('error_purchaselocation');
		}

		$this->load->model('localisation/country');


		return !$this->error;
	}

	protected function validateDelete() {
		
		if ($this->model_account_product->getTotalProducts() == 1) {
			$this->error['warning'] = $this->language->get('error_delete');
		}

		return !$this->error;
	}
    
    public function addWifiGrill() {
		
		$this->load->language('checkout/cart');

		$json = array();

		if (isset($this->request->post['deviceID'])) {
			$deviceID = (int)$this->request->post['deviceID'];
		} else {
			$deviceID = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$sort_order = array();

					$results = $this->model_setting_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get('total_' . $result['code'] . '_status')) {
							$this->load->model('extension/total/' . $result['code']);

							// We have to put the totals in an array so that they pass by reference.
							$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
						}
					}

					$sort_order = array();

					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $totals);
				}

				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		
		$this->load->model('account/product');
		$json = array();
			$search_term = $this->request->post['search'];
			if(strlen($search_term) > 3){
				$results = $this->model_account_product->getAllProductsSearch($search_term);
			
				foreach ($results as $result) {
					$json[] = array(
						'id'       	=> $result['id'],
						'text' 		=> $result['text']
					);
				}
			}
			else{
				$json[] = array();
			}
			
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}