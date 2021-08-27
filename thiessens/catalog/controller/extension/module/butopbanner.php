<?php
class ControllerExtensionModuleButopbanner extends Controller {
	public function index($setting) {                
		if (isset($setting['butopbanner_module']) && $setting['status']) {
                    
                    //$this->document->addStyle('catalog/view/theme/default/stylesheet/style_common.css');
                    //$this->document->addStyle('catalog/view/theme/default/stylesheet/style4.css');
                    $data['status'] = $setting['status'];
                    $data['name'] = $setting['name'];
                    if(!empty($setting['butopbanner_module']))
                    {
                        foreach ($setting['butopbanner_module'] as $key=>$value)
                        {
                            $description = html_entity_decode($value['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                            $description = str_replace('<p><br></p>', '', $description);
                            $heading = html_entity_decode($value['heading'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                            $slider_image = html_entity_decode($value['slider_image'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                            $link = html_entity_decode($value['link'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
                            $data['butopbanner'][]=array(
                                        'heading'=>$heading,
                                        'description'=>$description,
                                        'image'=>$slider_image,
                                        'link'=>$link,
                            );
                        }
                    }
                    
                    return $this->load->view('extension/module/butopbanner', $data);
		}
	}
        
}