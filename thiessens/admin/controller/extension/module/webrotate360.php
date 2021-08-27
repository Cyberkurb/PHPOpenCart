<?php
/**
* @version     1.7.0
* @module      WebRotate 360 Product Viewer for OpenCart
* @author      WebRotate 360 LLC
* @copyright   Copyright (C) 2018 WebRotate 360 LLC. All rights reserved.
* @license     GNU General Public License version 2 or later (http://www.gnu.org/copyleft/gpl.html).
*/

class ControllerExtensionModuleWebrotate360 extends Controller
{
	public function index()
    {
        $this->language->load('extension/module/webrotate360');
	    $this->load->model('setting/setting');
        $this->document->setTitle($this->language->get('heading_title'));
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
        {
            $settingsToSave = array();
            $submitProducts = null;

            foreach($this->request->post as $key => $value)
            {
                if ($key != "submitProducts")
                    $settingsToSave[$key] = $value;
                else
                    $submitProducts = $value;
            }

            $this->model_setting_setting->editSetting('module_webrotate360', $settingsToSave);
            
            if ($submitProducts != null && strlen($submitProducts) > 0)
            {
                $submitProducts = str_replace('&quot;', '"', $submitProducts);
                $submitProducts = json_decode($submitProducts, true);
                if ($submitProducts != null)
                {
                    foreach($submitProducts as &$product)
                    {
                        if ($product['wr360_enabled'] == 'Yes')
                        {
                            $product['wr360_enabled'] = '1';
                        }
                        else
                        {
                            $product['wr360_enabled'] = '0';
                        }
                    }

			        $this->load->model('catalog/webrotate360');
                    $this->model_catalog_webrotate360->saveProducts($submitProducts);
                }
            }

			$this->session->data['success'] = $this->language->get('text_success');
            //$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			//$this->response->redirect($this->url->link('extension/module/webrotate360', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_enabled']  = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['button_save']   = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
        $data['token'] = $this->session->data['user_token'];

 		if (isset($this->error['warning']))
        {
			$data['error_warning'] = $this->error['warning'];
		}
        else
        {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['image']))
        {
			$data['error_image'] = $this->error['image'];
		}
        else
        {
			$data['error_image'] = array();
		}

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/webrotate360', 'user_token=' . $this->session->data['user_token'], true)
        );

        if (!isset($this->request->get['module_id']))
        {
            $data['action'] = $this->url->link('extension/module/webrotate360', 'user_token=' . $this->session->data['user_token'], true);
        }
        else
        {
            $data['action'] = $this->url->link('extension/module/webrotate360', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);


		$this->loadSetting($data, 'module_webrotate360_status');
        $this->loadSetting($data, 'module_webrotate360_configFileURL');
        $this->loadSetting($data, 'module_webrotate360_graphicsPath', 'html/img/basic');
        $this->loadSetting($data, 'module_webrotate360_divID', '#wr360embed');
        $this->loadSetting($data, 'module_webrotate360_viewerWidth', '100%');
        $this->loadSetting($data, 'module_webrotate360_viewerHeight', '400px');
        $this->loadSetting($data, 'module_webrotate360_skinCSS', 'basic.css');
        $this->loadSetting($data, 'module_webrotate360_baseWidth');
        $this->loadSetting($data, 'module_webrotate360_licensePath');
        $this->loadSetting($data, 'module_webrotate360_prettyPhotoSkin');
        $this->loadSetting($data, 'module_webrotate360_viewerInPopup');
        $this->loadSetting($data, 'module_webrotate360_minHeight');
        $this->loadSetting($data, 'module_webrotate360_useAnalytics');
        $this->loadSetting($data, 'module_webrotate360_apiCallback');

        $this->load->model('catalog/webrotate360');
        $this->model_catalog_webrotate360->ensureTableExists();
        $this->template = 'extension/module/webrotate360.tpl';
		
  	    $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/webrotate360', $data));
	}
    
    private function loadSetting(&$data, $name, $defaultIfConfigEmpty = null)
    {
        if (isset($this->request->post[$name]))
        {
            $data[$name] = $this->request->post[$name];
        }
        else
        {
            $data[$name] = $this->config->get($name);
            
            if (!isset($data[$name]) && $defaultIfConfigEmpty)
            {
                $data[$name] = $defaultIfConfigEmpty;
            }
        }
    }
	
	protected function validate()
    {
		if (!$this->user->hasPermission('modify', 'extension/module/webrotate360'))
        {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error)
        {
			return true;
		}
        else
        {
			return false;
		}	
	}
    
    public function getproducts()
    {
        $this->load->model('catalog/webrotate360');
        $products = $this->model_catalog_webrotate360->getProducts();

        foreach($products as &$product)
        {
            if ($product['wr360_enabled'] == null || $product['wr360_enabled'] == '0')
                $product['wr360_enabled'] = "No";
            else
                $product['wr360_enabled'] = "Yes";

            if ($product['root_path'] == null)
                $product['root_path'] = "";
            
            if ($product['config_file_url'] == null)
                $product['config_file_url'] = "";
        }

        $resp = json_encode($products);
        $this->response->setOutput($resp);
    }

    public function install()
    {
        $this->load->model('setting/event');
        $this->model_setting_event->addEvent(
            'webrotate360', 'admin/view/extension/module/webrotate360/before', 'extension/module/webrotate360/override', 1, 0 );
    }

    public function uninstall() {
        $this->load->model('setting/event');
        $code = 'webrotate360';
        $this->model_setting_event->deleteEventByCode($code);
    }

    public function override(&$route, &$data, &$template)
    {
        $this->config->set('template_engine', 'template');
        $this->config->set('template_directory', '');
        return null;
    }
}
