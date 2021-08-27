<?php 
class ControllerStoreStoreLocator extends Controller{
    public function index()
    {
      
        $this->load->model('store/setting');
        $this->load->language('store/store_locator');
         $this->document->setTitle($this->language->get('heading_title'));
        $search_radius_string = $this->config->get('module_store_locator_radius');

        $data['search_radius_values'] = explode(',', $search_radius_string); 
        $store_lists = $this->model_store_setting->getStorelist(); 
        if(isset( $store_lists ) && !empty($store_lists)){
        foreach ($store_lists as $store_list) {  
              $data['stores_data'][] = array( 'lat' => $store_list['store_lat'],
                                               'long' => $store_list['store_long'],
                                               'address' => $store_list['store_address'],
                                             );
          }   }
        $data['api_key']  = $this->config->get('module_store_locator_google_api_key');    
        $data['store_name']  = $this->config->get('module_store_locator_name');
        $data['not_avail_text'] = trim($this->config->get('module_store_locator_not_avail_text'));
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('store/store_locator', $data));
    }

    public function getDistance()
    {    
      
       $this->load->model('store/setting');  
       if(!isset($this->request->post['longitude']) && !isset( $this->request->post['latitude']))
        {
         json_decode(array('error' => true ));
         die();
        }
        $origin_long = $this->request->post['longitude']; 
        $origin_lat  = $this->request->post['latitude'];  
        $search_radius  = $this->request->post['search_radius']; 
        $key = $this->config->get('module_store_locator_google_api_key');
        //$search_radius = 1000 * $search_radius; 
        $storecollection = $this->model_store_setting->getStorelist();
        $store_available = array();
        $storecount = 0;   
        foreach($storecollection as $storekey)
        {
                $storename  = $storekey['store_title'];
                $storeaddress = $storekey['store_address'];
                $storelongitude = $storekey['store_long'];
                $storelatitude = $storekey['store_lat'];  
                $storemobile = $storekey['store_mobile_no'];        
                $storecoordinates[] = array($storename.' '.$storeaddress.' '. $storemobile, $storelatitude, $storelongitude );
                $storecoordinates2[] = array($storelatitude, $storelongitude);

                if (($origin_long == $storelongitude) && ($origin_lat == $storelatitude)) {
                    $store_dist = 0;
                  }
                  else {
                    $theta = $origin_long - $storelongitude;
                    $dist = sin(deg2rad($origin_lat)) * sin(deg2rad($storelatitude)) +  cos(deg2rad($origin_lat)) * cos(deg2rad($storelatitude)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;
                    $unit = strtoupper($unit);
                
                    if ($unit == "K") {
                        $store_dist = ($miles * 1.609344);
                    } else if ($unit == "N") {
                        $store_dist = ($miles * 0.8684);
                    } else {
                        $store_dist = $miles;
                    }
                  }

                  if($store_dist <= $search_radius){
                    $store_available[] = array(
                                            'storedistence' => $store_dist,
                                            'storename'     => $storename,
                                            'storemobile'   => $storemobile,
                                            'storewebsite'  => $storewebsite,
                                            'storeaddress'  => $storeaddress,
                                            'storelat'      => $storelatitude,
                                            'storelong'     => $storelongitude,
                                            'storecount'    => $storecount
                                        );
                    $storecount++;
                  }
        }
        //$store_available = array_multisort($store_available);
        echo json_encode($store_available);
    }
}

 

?>
