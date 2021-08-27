<?php
class ControllerExtensionRCIExtensionFaq extends Controller {

    public function index() {
        $this->load->language('extension/rciextension/faq');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/rciextension/faq')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('extension/rciextension/faq');

        $data['faqs'] = array();

        if($this->config->get('faq_status')){
            $faqs = $this->model_extension_rciextension_faq->getFaqs();
        } else {
            $faqs = array();
        }

        foreach ($faqs as $faq) {
            $data['faqs'][] = array(
                'faq_id' => $faq['faq_id'],
                'question'  => $faq['question'],
                'answer'    => $faq['answer']
            );
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/rciextension/faq', $data));
    }

}