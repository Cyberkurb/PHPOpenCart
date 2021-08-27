<?php
class ControllerAccountCase extends Controller {
	public function index() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/account');

		$this->document->setTitle('Support Cases');

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
			'text' => 'Support Cases',
			'href' => $this->url->link('account/case', '', true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 
		
		$data['new_case'] = $this->url->link('account/case/newcase', '', true);
        
        $this->load->model('account/case');
        $data['open_case'] = $this->model_account_case->getOpenCases($this->customer->getId());
        $data['close_case'] = $this->model_account_case->getClosedCases($this->customer->getId());
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('account/case', $data));
	}

	public function newcase() {
		$this->load->model('account/case');
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$this->load->language('account/register');

		$this->document->setTitle('New Case');

		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $case_id = $this->model_account_case->addCase($this->request->post);
            $this->session->data['case_id'] = $case_id;
			$this->response->redirect($this->url->link('account/case/newcase2'));
		}

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
			'text' => 'Cases',
			'href' => $this->url->link('account/case', '', true)
        );
        
        $data['breadcrumbs'][] = array(
			'text' => 'New Case',
			'href' => $this->url->link('account/case/newcase', '', true)
        );

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}

		if (isset($this->error['type'])) {
			$data['error_type'] = $this->error['type'];
		} else {
			$data['error_type'] = '';
        }
        
        if (isset($this->error['troubleshooting'])) {
			$data['error_troubleshooting'] = $this->error['troubleshooting'];
		} else {
			$data['error_troubleshooting'] = '';
		}
        $data['registered_products'] = $this->model_account_case->getRegProducts($this->customer->getId());
        $data['customer_id'] = $this->customer->getId();
		$data['action'] = $this->url->link('account/case/newcase', '', true);

		
		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} else {
			$data['title'] = $this->config->get('title');
		}

		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} else {
			$data['type'] = '';
        }
        if (isset($this->request->post['troubleshooting'])) {
			$data['troubleshooting'] = $this->request->post['troubleshooting'];
		} else {
			$data['troubleshooting'] = '';
        }
        
        $data['back'] = $this->url->link('account/case', '', true);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/case_1', $data));
    }
    
    public function newcase2() {
		$this->load->model('account/case');
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$this->load->language('account/register');

		$this->document->setTitle('New Case Part 2');

		if(!$this->session->data['case_id']){
			$case_id = $this->request->get['case_id'];
			$this->session->data['case_id'] = $this->request->get['case_id'];
		}
		else{
			$case_id = $this->session->data['case_id'];
		}

		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$filename = $this->request->get['file'];
			$case_id = $this->session->data['case_id'];
			$customer_id = $this->customer->getId();
            $this->model_account_case->addCaseImage($case_id, $this->customer->getId(), $filename);
			$this->response->redirect($this->url->link('account/case'));
		}
		$data['customer_id'] = $this->customer->getId();
        $data['case_id'] = $case_id;
        $data['current_case'] = $this->model_account_case->getCase($case_id);

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
			'text' => 'Cases',
			'href' => $this->url->link('account/case', '', true)
        );
        
        $data['breadcrumbs'][] = array(
			'text' => 'New Case',
			'href' => $this->url->link('account/case/newcase', '', true)
        );

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['current_images'] = $this->model_account_case->getImages($case_id);
		$data['view_case'] = $this->url->link('account/case');
		$data['action'] = $this->url->link('account/case/newcase2', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/case_2', $data));
	}

	public function viewcase(){
		unset($this->session->data['case_id']);
		$this->load->model('account/case');
		$case_id = $this->request->get['case_id'];
		$customer_id = $this->customer->getId();

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$case_id = $this->request->post['case_id'];
            $this->model_account_case->addCaseNote($this->request->post);
			$this->response->redirect($this->url->link('account/case/viewcase', 'case_id='.$case_id, true));
		}

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
			'text' => 'Cases',
			'href' => $this->url->link('account/case', '', true)
        );
        
        $data['breadcrumbs'][] = array(
			'text' => 'View Case',
			'href' => $this->url->link('account/case/caseview', 'case_id='.$case_id, true)
        );

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['case_id'] = $case_id;
		$data['customer_id'] = $customer_id;
		$data['current_images'] = $this->model_account_case->getImages($case_id);
		$data['current_notes'] = $this->model_account_case->getCaseNotes($case_id);
		$current_case = $this->model_account_case->getCase($case_id);
		if(!$current_case['title']){
			$data['title'] = "NOTHING!";
		}else{
			$data['title'] = $current_case['title'];
		}

		if ((int)$current_case['customer_id'] != (int)$this->customer->getId()) {
			$this->response->redirect($this->url->link('account/case', '', true));
		}
		
		$data['description'] = $current_case['description'];
		$data['case_number'] = $current_case['integration_id'];
		$data['status'] = $current_case['status'];

		$data['back'] = $this->url->link('account/case', '', true);
		$data['action'] = $this->url->link('account/case/viewcase', 'case_id='.$case_id, true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/case_view', $data));
	}
	
	public function imagedel(){
		$this->load->model('account/case');
		$image_id = $this->request->get['image_id'];
		$del_image = $this->model_account_case->delImage($image_id);

		$this->response->redirect($this->url->link('account/case/newcase2', '', true));
	}
}
