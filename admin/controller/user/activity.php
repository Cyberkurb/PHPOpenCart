<?php  
class ControllerUserActivity extends Controller {  
	public function index() {
		$this->load->language('user/activity');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('user/activity', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['delete'] = $this->url->link('user/activity/delete', 'user_token=' . $this->session->data['user_token'], true);
		$data['download'] = $this->url->link('user/activity/download', 'user_token=' . $this->session->data['user_token'], true);

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['activities'] = array();

		$this->load->model('user/activity');

		$results = $this->model_user_activity->getActivities();

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_' . $result['key']), $result['data']);
			
			$find = array(
				'user=',
				'username=',
				'user_id=',
				'order_id=',
				'product_id=',
			);

			$replace = array(
				$result['user'],
				$result['username'],
				$this->url->link('user/user/update', 'user_token=' . $this->session->data['user_token'] . '&user_id='.$result['user_id'], 'SSL'),
				$this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=', 'SSL'),
				$this->url->link('catalog/product/update', 'user_token=' . $this->session->data['user_token'] . '&product_id=', 'SSL')
			);

			$data['activities'][] = array(
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/activity', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function download(){
		if($this->validate()){
			$query = $this->db->query("SELECT ua.key,u.username,ua.data,ua.date_added,ua.ip FROM `" . DB_PREFIX . "user_activity` ua LEFT JOIN `" . DB_PREFIX . "user` u ON (ua.user_id = u.user_id) ORDER BY ua.date_added DESC");

			$array = $query->rows;
			
			if (count($array) == 0) {
			    return null;
			}

			// Filename
			$date = date('Y-m-d');
			$filename = $date . '-user_activityUiD#' . $this->user->getId() . '.csv';
			// Response headers
			header( 'Content-Type: text/csv' );
			header( 'Content-Disposition: attachment;filename='.$filename);
			// Generate CSV
			$output = fopen('php://output', 'w');
			fputcsv($output, array('event','user','details','date_added','ip_adderss'));
			foreach($array as $product) {
			    fputcsv($output, $product);
			}
			fclose($output);

		}

	}

	public function delete(){
		$this->response->redirect($this->url->link('user/activity', 'user_token=' . $this->session->data['user_token'], true));
	}
}
?>