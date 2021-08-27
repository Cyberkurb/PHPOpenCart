<?php
class ControllerExtensionModuleActivity extends Controller {
	private $error = array();

	public function install() {

	$this->db->query("CREATE TABLE `" . DB_PREFIX . "user_activity` (
 `activity_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `key` varchar(64) NOT NULL,
 `data` text NOT NULL,
 `ip` varchar(40) NOT NULL,
 `date_added` datetime NOT NULL,
 PRIMARY KEY (`activity_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
	}
	public function index() {
		$language_strings = $this->load->language('extension/module/activity');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('user_activity', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$language_strings = $this->load->language('extension/module/activity');

		foreach ($language_strings as $code => $languageVariable) {
		     $data[$code] = $languageVariable;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/activity', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/activity', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['user_activity_status'])) {
			$data['user_activity_status'] = $this->request->post['user_activity_status'];
		} else {
			$data['user_activity_status'] = $this->config->get('user_activity_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/activity', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/activity')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}