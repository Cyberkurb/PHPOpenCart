<?php
class ControllerAccountDevice extends Controller {
	private $error = array();

	public function index() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/device', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->connect();
		
	}

	protected function getList() {
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['connected_devices'] = array();

		$results_connected = $this->model_account_device->getDevices($this->customer->getId());

		foreach ($results_connected as $result_connected) {
			$data['connected_devices'][] = array(
				'device_id' => $result_connected['deviceID'],
				'name'		=> $result_connected['name'],
				'status'   	=> $this->model_account_device->getDeviceStatus($result_connected['deviceID']),
				'connect'	=> $this->url->link('account/device/updateconnection', 'deviceID='. $result_connected['deviceID'], true),
				'control'   => $this->url->link('account/device/control', 'deviceID=' . $result_connected['deviceID'], true),
				'forget'   => $this->url->link('account/device/forgetDevice', 'deviceID=' . $result_connected['deviceID'], true)
			);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/device_list', $data));
	}

	public function forgetDevice(){
		
		$deviceID = $this->request->get['deviceID'];
		$this->load->model('account/device');
		
		$deviceStatus = $this->model_account_device->forgetDevice($deviceID);

		$this->response->redirect($this->url->link('account/device', '', true));
	}

	public function connect(){
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/device_connecting', $data));
	}
	public function checkconnect(){
		
		$this->load->model('account/device');

		$this->getList();
		
	}

	public function control(){
		
		$this->document->setTitle($this->language->get('Device Controller'));

		$this->load->model('account/device');
		
		$deviceStatus = $this->model_account_device->getDeviceStatusDetail($this->request->get['deviceID']);
		$lastUpdate = json_decode(urldecode($deviceStatus), true);
		$data['deviceID'] = $this->request->get['deviceID'];
		$data['currenttemp'] = $lastUpdate['temp']['grill_rtdtemp'];
		$data['settemp'] = $lastUpdate['temp']['grill_set'];
		$data['details'] = $lastUpdate;
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/device_control', $data));
	}

	public function updateconnection(){
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/device', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->setTitle($this->language->get('Device Connection'));

		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('https://images.pitboss-grills.com/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/device');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$entry = $this->model_account_device->addPasswordDevice($this->request->get['deviceID'], $this->request->post);
            if($entry !=0){
			 $this->session->data['success'] = 'Device Updated';
			 $this->response->redirect($this->url->link('account/device', '', true));
            }
            else{
                $this->session->data['warning'] = 'Error on Updating';
            }
		}
		$this->getForm();
	}

	protected function getForm() {
		
		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['ssid'])) {
			$data['error_ssid'] = $this->error['ssid'];
		} else {
			$data['error_ssid'] = '';
		}

		
		$data['action'] = $this->url->link('account/device/updateconnection', 'deviceID=' . $this->request->get['deviceID'], true);

		if (isset($this->request->get['deviceID']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$device_info = $this->model_account_device->getPendingDevice($this->request->get['deviceID']);
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} elseif (!empty($device_info)) {
			$data['password'] = $device_info['password'];
		} else {
			$data['password'] = '';
		}

		$data['back'] = $this->url->link('account/device', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/device_form', $data));
	}

	protected function validateForm() {
		
		if ((utf8_strlen(trim($this->request->post['networkpassword'])) < 8) || (utf8_strlen(trim($this->request->post['networkpassword'])) > 64)) {
			$this->error['password'] = "There is a problem with your password.";
		}

		return !$this->error;
	}

	public function addApp() {
		
		$this->load->model('account/device');

		if (isset($this->request->post['token_id'])) {
			$data['token_id'] = $this->request->post['token_id'];
		}
		elseif(isset($this->request->get['token_id'])){
			$data['token_id'] = $this->request->get['token_id'];
		}
		else{
			$data['token_id'] = "0";
		}
		if (isset($this->request->post['store_id'])) {
			$data['store_id'] = $this->request->post['store_id'];
		}elseif(isset($this->request->get['store_id'])){
			$data['store_id'] = $this->request->get['store_id'];
		}
		else{
			$data['store_id'] = "0";
		}
		if (isset($this->request->post['app_type_id'])) {
			$data['app_type_id'] = $this->request->post['app_type_id'];
		}elseif(isset($this->request->get['app_type_id'])){
			$data['app_type_id'] = $this->request->get['app_type_id'];
		}
		else{
			$data['app_type_id'] = "0";
		}
		if (isset($this->request->post['phoneid'])) {
			$data['phoneid'] = $this->request->post['phoneid'];
		}elseif(isset($this->request->get['phoneid'])){
			$data['phoneid'] = $this->request->get['phoneid'];
		}
		else{
			$data['phoneid'] = "0";
		}

		$add_phone = $this->model_account_device->addPhone($data);
	}
	public function addDevice() {
		
		$this->load->model('account/device');
		$data['session_id_new'] = $this->session->getId();
		$data['uuid'] = $this->request->post['uuid'];
		$data['name'] = $this->request->post['name'];
		$data['deviceID'] = $this->request->post['deviceid'];
		$details = $this->model_account_device->addDevice($data);
		//echo "Done";
		//exit();
		if(strlen($details)>0){
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput($details);
		}
		else{
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode("Error"));
		}
	}

	public function getPassword() {
		
		$this->load->model('account/device');
		$details = $this->model_account_device->getPendingPasssword();

		$details = "ee".$this->String2Hex('{"SID":"' . $details['ssid'] . '","PWD":"' . $details['password2'] . '"}')."ef";
		//var_dump($details);
		//exit;
		if(strlen($details)>0){
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput($details);
		}
		else{
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode("Error"));
		}
		
	}

	public function update() {
		
		$this->load->model('account/device');

		$this->response->setOutput($this->load->view('account/deviceloader', $data));
	}
	public function getTemp(){
		$data['deviceID'] = $this->request->get['deviceID'];
		// Totals
		$this->load->model('account/device');

		$current_cart_value = $this->model_account_device->cartValue();

		$json['item_count'] = $current_cart_value;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function currentstatus(){
		
		// Totals
		$this->load->model('account/device');
		$deviceID = $this->request->get['deviceID'];
		$current_device_status = $this->model_account_device->currentTemps($deviceID);

		$json['current_temps'] = $current_device_status;
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function bleTempAdd(){
		
		$this->load->model('account/device');
		if(isset($this->request->get['blefeed'])){
			$blefeed = $this->request->get['blefeed'];
		}
		else{
			$blefeed = $this->request->post['blefeed'];
		}
		$feedinfo = substr($blefeed, 2, -2);
		$this->model_account_device->bleGrillDataRaw($feedinfo);
		$translated_feed = $this->Hex2String(strtoupper(str_replace(" ", "", $feedinfo)));
		$tempature_feed = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $translated_feed);
		echo $tempature_feed;
		$json = json_decode($tempature_feed, true);
		$device_info = array(
			"deviceID"			=> $json["MAC"],
			"grill_acttemp"		=> substr($json['MSG'], 12, 3),
			"pc_settemp"		=> substr($json['MSG'], 0, 3),
			"probe1_acttemp"	=> substr($json['MSG'], 3, 3),
			"probe2_acttemp"	=> substr($json['MSG'], 6, 3),
			"probe3_acttemp"	=> substr($json['MSG'], 9, 3),
			"grill_degrees" 	=> substr($json['MSG'], 18,1),
			'cnt'				=> $json["CNT"]
		);
		//var_dump($device_info);
		$this->model_account_device->bleGrillData($device_info);
		//echo $details;
		echo "Done";
	}

	public function String2Hex($string){
		
		$hex='';
		for ($i=0; $i < strlen($string); $i++){
		   if(($i+1) <= 30){
				$hexpre = dechex(ord($string[$i])-($i+1));
			}
			elseif(($i+1) <= 61){
				$hexpre = dechex(ord($string[$i])-(($i+1)-30));
			}
			elseif(($i+1) <= 91){
				$hexpre = dechex(ord($string[$i])-(($i+1)-60));
			}
			elseif(($i+1) <= 121){
				$hexpre = dechex(ord($string[$i])-(($i+1)-90));
			}
			if(strlen($hexpre)==1){
				$hexpre = "0".$hexpre;
			}
			$hex .= $hexpre;
		}
		return $hex;
	}
	
	public function String2Hex2($string){
		
		$hex22 = '';
		for ($i=0; $i < strlen($string); $i++){
			$hexpre = dechex(ord($string[$i]));
			
			if(strlen($hexpre)==1){
				$hexpre = "0".$hexpre;
			}
			$hex22 .= $hexpre;
		}
		return $hex22;
	}
	 
	public function Hex2String($hex){
		
		$string='';
		$i2 = 1;
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			if(($i2+1) <= 31){
				//$hex .= dechex(ord($string[$i])-($i+1));
				$hexpre = (hexdec($hex[$i].$hex[$i+1])+($i2));
			}
			elseif(($i2+1) <= 61){
				//$hex .= dechex(ord($string[$i])-(($i+1)-30));
			   $hexpre = (hexdec($hex[$i].$hex[$i+1])+($i2-30));
			}
			elseif(($i2+1) < 91){
				$hexpre = (hexdec($hex[$i].$hex[$i+1])+($i2-60));
			}
			elseif(($i2+1) < 121){
				$hexpre = (hexdec($hex[$i].$hex[$i+1])+($i2-90));
			}
			if(strlen($hexpre)==1){
				//$hexpre = "0".$hexpre;
			}
			$string .= chr($hexpre);
			$i2++;
		}
		return $string;
	}

}
