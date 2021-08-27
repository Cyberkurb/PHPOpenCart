<?php
class ControllerExtensionRCIExtensionTechnology extends Controller {

    public function index() {
        $this->load->language('extension/rciextension/technology');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/rciextension/technology')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('extension/rciextension/technology');

        $data['technologies'] = array();

        if($this->config->get('technology_status')){
            $technologies = $this->model_extension_rciextension_technology->getTechnologies();
        } else {
            $technologies = array();
        }

        foreach ($technologies as $technology) {
            $data['technologies'][] = array(
                'technology_id' => $technology['technology_id'],
                'title'  => $technology['title'],
                'details'    => $technology['details']
            );
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/rciextension/technology', $data));
    }

}