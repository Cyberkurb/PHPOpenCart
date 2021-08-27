<?php
class ControllerExtensionModuleSlideshow extends Controller {
	public function index($setting) {
		static $module = 0;		

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/swiper/css/swiper.min.css');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/swiper/css/opencart.css');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/swiper/js/swiper.jquery.min.js');
		
		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
            if(substr($result['image'],0,4) == "http"){
                    $data['banners'][] = array(
                        'title'     => $result['title'],
                        'link'      => $result['link'],
                        'image'     => $result['image'],
                        'reg_img'   => $result['image'],
                        'subtitle'  => $result['subtitle'],
                        'adimage'   => $result['adimage'],
                        'side'      => $result['side'],
                        'descrip'   => $result['descrip']

                    );
            }
            else{
                if (is_file(DIR_IMAGE . $result['image'])) {
                    $data['banners'][] = array(
                        'title'     => $result['title'],
                        'link'      => $result['link'],
                        'image'     => $this->model_tool_image->resize($result['image'], 1200, 800),
                        'reg_img'   => $this->config->get('config_url') . 'image/' .$result['image'],
                        'subtitle'  => $result['subtitle'],
                        'adimage'   => $this->model_tool_image->resize($result['adimage'], 150, 150),
                        'side'      => $result['side'],
                        'descrip'   => $result['descrip']

                    );
                }
            }
			
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/slideshow', $data);
	}
}