<?php

// Pull in Better Together Admin if available 
$admin_file = "controller/extension/total/better_together_admin.php.inc";
if (file_exists($admin_file)) { 
   require($admin_file); 
}

class ControllerExtensionTotalBetterTogether extends Controller {
  private $error = array(); 

  // BT Admin functions
  public function showlist() {
     bt_admin_showlist($this); 
  }
  public function add() {
     bt_admin_add($this); 
  }
  public function disable() {
     bt_admin_disable($this); 
  }
  public function enable() {
     bt_admin_enable($this); 
  }
  public function delete() {
     bt_admin_delete($this); 
  }
  public function autocomplete() {
     bt_admin_autocomplete($this); 
  }
  // end BT Admin functions
  public function index() { 
    $this->load->language('extension/total/better_together');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('total_better_together', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', 'SSL'));
    }

    $data['heading_title'] = $this->language->get('heading_title');

    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_tax_recalculation_standard'] = $this->language->get('text_tax_recalculation_standard');
    $data['text_tax_recalculation_none'] = $this->language->get('text_tax_recalculation_none');

    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_tax_recalculation'] = $this->language->get('entry_tax_recalculation');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_total'),
      'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      'separator' => ' :: '
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/total/better_together', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      'separator' => ' :: '
    );

    $data['action'] = $this->url->link('extension/total/better_together', 'user_token=' . $this->session->data['user_token'], 'SSL');

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', 'SSL');

    if (isset($this->request->post['total_better_together_status'])) {
      $data['total_better_together_status'] = $this->request->post['total_better_together_status'];
    } else {
      $data['total_better_together_status'] = $this->config->get('total_better_together_status');
    }

    if (isset($this->request->post['total_better_together_tax_recalculation'])) {
      $data['total_better_together_tax_recalculation'] = $this->request->post['total_better_together_tax_recalculation'];
    } else {
      $data['total_better_together_tax_recalculation'] = $this->config->get('total_better_together_tax_recalculation');
    }

    if (isset($this->request->post['total_better_together_sort_order'])) {
      $data['total_better_together_sort_order'] = $this->request->post['total_better_together_sort_order'];
    } else {
      $data['total_better_together_sort_order'] = $this->config->get('total_better_together_sort_order');
    }

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/total/better_together', $data));
  }

  public function install() {
    try { 
       $this->load->model('extension/total/better_together');
       $this->load->model('user/user_group');
       $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/total/better_together');
       $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/total/better_together');
    } catch(Exception $e) {
      // BT Admin not installed
    }

    try { 
      $this->load->model('extension/total/better_together');
      $this->model_extension_total_better_together->install();
    } catch(Exception $e) {
      // BT Admin not installed
    }
  }

  public function uninstall() {
    try { 
       $this->load->model('extension/total/better_together');
       $this->model_extension_total_better_together->uninstall();
    } catch(Exception $e) {
      // BT Admin not installed
    }
  }

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'extension/total/better_together')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return (!$this->error);
  }
}
?>
