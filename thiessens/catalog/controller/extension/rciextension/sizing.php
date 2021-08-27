<?php
class ControllerExtensionRCIExtensionSizing extends Controller {

    public function index() {
        $this->load->language('extension/rciextension/sizing');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/rciextension/sizing')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('extension/rciextension/sizing');

        $data['sizings'] = array();

        if($this->config->get('sizing_status')){
            $sizings = $this->model_extension_rciextension_sizing->getSizings();
        } else {
            $sizings = array();
        }

        foreach ($sizings as $sizing) {
            $data['sizings'][] = array(
                'sizing_id' => $sizing['sizing_id'],
                'title'  => $sizing['title'],
                'details'    => html_entity_decode($sizing['details'])
            );
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/rciextension/sizing', $data));
    }

}