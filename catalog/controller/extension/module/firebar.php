<?php
class ControllerExtensionModuleFireBar extends Controller {
	public function index() {
	    $this->load->model('catalog/information');
	    $infopull = $this->model_catalog_information->getInfoBanners();
		foreach ($infopull as $result) {
				$info_data[] = array(
					'title' => $result['title'],
					'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
				);
		}
		$data['informations'] = $info_data;
        return $this->load->view('common/firebar', $data);
    }
}