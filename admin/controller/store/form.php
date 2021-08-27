<?php
class ControllerStoreForm extends Controller {
	private $error = array();

	public function index() {
		$store_info['storelist_id'] = array(); 
		$this->load->language('store/form'); 

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('store/locator');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) { 
 
			$store_id = $this->model_store_locator->addStore($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('store/store_lists', 'user_token=' . $this->session->data['user_token'] . '&type=stores', true));
		}
		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
		 if(isset($this->request->get['storelist_id']))
		  {
		   $store_data = $this->model_store_locator->getstoreDetail($this->request->get['storelist_id']); 
		   if(is_array($store_data))
		   {
			   foreach ($store_data as  $value) {
					 $data['storelist_id']   = $value['storelist_id'];
					 $data['store_id']	= $value['store_id'];
			   	 $data['store_title'] =   $value['store_title'] ;
			   	 $data['store_address'] =   $value['store_address'] ;
			   	 $data['store_lat'] =   $value['store_lat'] ;
			   	 $data['store_long'] =   $value['store_long'] ;	
			   	 $data['store_status'] =   $value['status'] ;
			   	 $data['store_mobile_no'] =   $value['store_mobile_no'] ;		   	 	   	 
			   }
		   }
		  }
			// $this->model_store_store_setting->addStore($this->request->post);

			// $this->session->data['success'] = $this->language->get('text_success');

			// $this->response->redirect($this->url->link('store/store_lists', 'user_token=' . $this->session->data['user_token'] . '&type=stores', true));
		}


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
	    if (isset($this->error['store_long'])) {
	      $data['error_store_long'] = $this->error['store_long'];
	    } else {
	      $data['error_store_long'] = '';
	    } 

	    if (isset($this->error['store_lat'])) {
	      $data['error_store_lat'] = $this->error['store_lat'];
	    } else {
	      $data['error_store_lat'] = '';
	    }

	    if (isset($this->error['store_title'])) {
	      $data['error_store_title'] = $this->error['store_title'];
	    } else {
	      $data['error_store_title'] = '';
	    }

	    if (isset($this->error['store_address'])) {
	      $data['error_store_address'] = $this->error['store_address'];
	    } else {
	      $data['error_store_address'] = '';
	    }

	    if (isset($this->error['store_mobile_no'])) {
	      $data['error_mobile_no'] = $this->error['store_mobile_no'];
	    } else {
	      $data['error_mobile_no'] = '';
	    }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_store_lists'),
			'href' => $this->url->link('store/store_lists', 'user_token=' . $this->session->data['user_token'] . '&type=stores', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('store/form', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('store/form', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('store/store_lists', 'user_token=' . $this->session->data['user_token'] . '&type=stores', true);

 

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer'); 

		$this->response->setOutput($this->load->view('store/form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'store/form')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	    if ((utf8_strlen($this->request->post['store_title']) < 2) || (utf8_strlen($this->request->post['store_title']) > 64)) {
	      $this->error['store_title'] = $this->language->get('error_store_title');
	    }
	    if ((utf8_strlen($this->request->post['store_mobile_no']) < 2))  {
	      $this->error['store_mobile_no'] = $this->language->get('error_mobile_no');
	    }

	    if ((utf8_strlen($this->request->post['store_address']) < 5)) {
	      $this->error['store_address'] = $this->language->get('error_store_address');
	    }

		return !$this->error;
	}
}