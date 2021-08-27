<?php
class ControllerStoreStoreLists extends Controller {
  
  public function index() {
    //$this->install();
    $this->load->language('store/store_lists');
    $this->load->model('store/locator');

    $this->document->setTitle($this->language->get('heading_title'));
    $this->getList();

    


  }





	
  protected function getList() {

    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }

    $url = '';
    
    if (isset($this->request->get['filter_phone'])) {
      $filter_phone = $this->request->get['filter_phone'];
    } else {
      $filter_phone = '';
    }

    if (isset($this->request->get['filter_brand'])) {
      $filter_brand = $this->request->get['filter_brand'];
    } else {
      $filter_brand = 9999999;
    }

    if (isset($this->request->get['filter_dealer'])) {
      $filter_dealer = $this->request->get['filter_dealer'];
    } else {
      $filter_dealer = '';
    }

    if (isset($this->request->get['filter_address'])) {
      $filter_address = $this->request->get['filter_address'];
    } else {
      $filter_address = '';
    }

    $url = '';

    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }
    if (isset($this->request->get['filter_dealer'])) {
      $url .= '&filter_dealer=' . urlencode(html_entity_decode($this->request->get['filter_dealer'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_brand'])) {
      $url .= '&filter_brand=' . $this->request->get['filter_brand'];
    }
    if (isset($this->request->get['filter_address'])) {
      $url .= '&filter_address=' . urlencode(html_entity_decode($this->request->get['filter_address'], ENT_QUOTES, 'UTF-8'));
    }
    if (isset($this->request->get['filter_phone'])) {
      $url .= '&filter_phone=' . urlencode(html_entity_decode($this->request->get['filter_phone'], ENT_QUOTES, 'UTF-8'));
    }
    
    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('store/store_lists', 'user_token=' . $this->session->data['user_token'] . $url, true)
    );

    $data['store_data'] = array();
    
    $filter_data = array(
      'filter_dealer'=> $filter_dealer,
      'filter_brand'=> $filter_brand,
      'filter_address'=> $filter_address,
      'filter_phone' => $filter_phone,
      'start' => ($page - 1) * $this->config->get('config_limit_admin'),
      'limit' => $this->config->get('config_limit_admin')
    );

    $store_lists_total = $this->model_store_locator->getTotalstoreList($filter_data);
    
    $store_results = $this->model_store_locator->getFilteredStoreList($filter_data);
    
    foreach ($store_results as $store_result) {
      $data['store_data'][] = array(
        'store_title' => $store_result['store_title'],
        'store_address' => $store_result['store_address'],
        'store_phone' => $store_result['store_mobile_no'],
        'store_lat' => $store_result['store_lat'],
        'store_long' => $store_result['store_long'],
        'store_status' => $store_result['status'],
        'edit_link'  => $this->url->link('store/form', 'user_token=' . $this->session->data['user_token'] . '&storelist_id='.$store_result['storelist_id'], true), 
        'delete_link'  => $this->url->link('store/store_lists', 'user_token=' . $this->session->data['user_token'] . '&delete_store_id='.$store_result['storelist_id'], true),  
        'store_id'  => $store_result['store_id'], 
        'storelist_id'  => $store_result['storelist_id']
  
      );
    }

    $data['user_token'] = $this->session->data['user_token'];

    $pagination = new Pagination();
    $pagination->total = $store_lists_total;
    $pagination->page = $page;
    $pagination->limit = $this->config->get('config_limit_admin');
    $pagination->url = $this->url->link('store/store_lists', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

    $data['pagination'] = $pagination->render();

    $data['results'] = sprintf($this->language->get('text_pagination'), ($store_lists_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($store_lists_total - $this->config->get('config_limit_admin'))) ? $store_lists_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $store_lists_total, ceil($store_lists_total / $this->config->get('config_limit_admin')));

    $data['filter_brand'] = $filter_brand;
    $data['filter_address'] = $filter_address;
    $data['filter_dealer'] = $filter_dealer;
    $data['filter_phone'] = $filter_phone;

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');




    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    $data['edit'] = $this->url->link('store/form', 'user_token=' . $this->session->data['user_token'] . '&type=stores', true);

  
   


    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=stores', true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('extension/module/store_lists', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['btn_reg_form'] = $this->url->link('store/form', 'user_token=' . $this->session->data['user_token'] . '&type=stores', true);

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');







    $this->response->setOutput($this->load->view('store/store_lists', $data));
  }


	public function gpscords(){
		$this->load->model('store/locator');
		$this->model_store_locator->storesMissing();
	}

 
 
}