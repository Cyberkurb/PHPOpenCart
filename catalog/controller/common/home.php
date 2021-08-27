<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->load->language('common/home');
		$this->load->model('catalog/information');
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));
		$info_data = array();
		
		$infopull = $this->model_catalog_information->getInfoHome();
		$data['information2'] = $infopull;
		foreach ($infopull as $result) {
				$info_data[] = array(
					'title' => $result['title'],
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
				);
		}
		
		
		$data['informations'] = $info_data;
		
		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}
		$data['current_date'] = date('Y-m-d', strtotime('-7 hours'));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
        $data['f_pellets'] = $this->load->controller('common/f_pellets');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}