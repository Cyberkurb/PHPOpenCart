<?php

class ControllerExtensionModuleiProductVideo extends Controller {

    static $iproductvideo_name = 'iProductVideo';
    static $iproductvideo_name_small = 'iproductvideo';
    static $iproductvideo_version = '5.3.2';
    static $iproductvideo_path = 'extension/module/iproductvideo';
    static $iproductvideo_path_video = 'image/catalog/iproductvideo/';
    static $iproductvideo_model_call = 'model_extension_module_iproductvideo';
    static $iproductvideo_extensions_link = 'marketplace/extension';
    static $iproductvideo_extensions_link_params = '&type=module';
    static $iproductvideo_token_string = 'user_token';

    private $eventGroup = 'isenselabs_iproductvideo';
    private $moduleName;
    private $moduleNameSmall;
    private $modulePath;
    private $extensionsLink;
    private $callModel;
    private $moduleModel;
    private $moduleVersion;
    private $tokenString;
    private $data = array();
    private $error = array();

    private $image_mime_types = array("image/bmp","image/cis-cod","image/gif","image/png","image/ief","image/jpeg","image/pipeg","image/svg+xml","image/tiff","image/x-cmu-raster","image/x-cmx","image/x-icon","image/x-portable-anymap","image/x-portable-bitmap","image/x-portable-graymap","image/x-portable-pixmap","image/x-rgb","image/x-xbitmap","image/x-xpixmap","image/x-xwindowdump");

    private $allowed_video_extensions = array("mp4","webm","ogg");

    private $module_events;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->config->load('isenselabs/iproductvideo');

        /* OC version-specific declarations - Begin */
        $this->moduleName      = static::$iproductvideo_name;
        $this->moduleNameSmall = static::$iproductvideo_name_small;
        $this->tokenString     = static::$iproductvideo_token_string;
        $this->extensionsLink  = $this->url->link(static::$iproductvideo_extensions_link, $this->tokenString . '=' . $this->session->data[$this->tokenString] . static::$iproductvideo_extensions_link_params, 'SSL');
        $this->modulePath      = static::$iproductvideo_path;
        $this->modulePathVideo = static::$iproductvideo_path_video;

        /* OC version-specific declarations - End */

        /* Module-specific declarations - Begin */
        $this->load->language($this->modulePath);
        $this->load->model($this->modulePath);
        $this->iconstruct();
        $this->callModel      = static::$iproductvideo_model_call;
        $this->moduleModel    = $this->{$this->callModel};
        $this->moduleVersion  = static::$iproductvideo_version;
        $this->module_events = array(
            'catalog/controller/product/product/before' => 'extension/module/iproductvideo/controllerProductProductBefore',
            'catalog/view/product/product/before' => 'extension/module/iproductvideo/viewProductProductBefore',
            'admin/controller/catalog/product/delete/before' => 'extension/module/iproductvideo/remove_product_video'
        );
        /* Module-specific declarations - End */


        // Multi-Store
        $this->load->model('setting/store');
        // Settings
        $this->load->model('setting/setting');
        // Multi-Lingual
        $this->load->model('localisation/language');

        // Variables
        $this->data['modulePath'] = $this->modulePath;
        $this->data['moduleName'] = $this->moduleName;
        $this->data['moduleNameSmall'] = $this->moduleNameSmall;
        $this->data['moduleModel'] = $this->moduleModel;
        $this->data['tokenString'] = $this->tokenString;
        $this->data['modulePathVideo'] = $this->modulePathVideo;
        /* Module-specific loaders - End */

        /* Specific models required for iproductvideo */
        $this->load->model('tool/image');

    }

    public function iconstruct() {
        if (!defined('IMODULE_ROOT')) define('IMODULE_ROOT', substr(DIR_APPLICATION, 0, strrpos(DIR_APPLICATION, '/', -2)) . '/');
        if (!defined('IMODULE_SERVER_NAME')) define('IMODULE_SERVER_NAME', substr((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER), 7, strlen((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER)) - 8));
    }

    public function install() {
        $this->moduleModel->install();
        // Register Events
        $this->load->model('setting/event');
        foreach ($this->module_events as $event => $event_handler) {
            $this->model_setting_event->addEvent($this->eventGroup, $event, $event_handler);
        }
    }

    public function uninstall() {
        $this->moduleModel->uninstall();
        // Remove Events
        $this->load->model("setting/event");
        $this->model_setting_event->deleteEventByCode($this->eventGroup);
    }

    private function load_static_template_data() {
        $this->data['token'] = $this->session->data[$this->tokenString];

        // Set language data
        $variables = array(
            'text_enabled',
            'text_disabled',
            'text_content_top',
            'text_content_bottom',
            'text_column_left',
            'text_column_right',
            'text_activate',
            'text_not_activated',
            'text_click_activate',
            'entry_code',
            'button_save',
            'button_cancel',
            'entry_type',
            'text_type_image',
            'text_type_text',
            'entry_image',
            'text_max_size',
            'text_max_size_learn',
            'text_top_left',
            'text_top_right',
            'text_center',
            'text_bottom_left',
            'text_bottom_right',
            'entry_position',
            'entry_opacity',
            'entry_text',
            'text_default',
            'entry_video_limit_products',
            'entry_all_products',
            'entry_following_products',
            'entry_rotation'
        );

        foreach ($variables as $variable) $this->data[$variable] = $this->language->get($variable);
        $this->data['heading_title'] = $this->language->get('heading_title') . ' '. $this->moduleVersion;

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = false;
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = false;
        }

        $this->data['maxSize'] = $this->moduleModel->returnMaxUploadSize();
        $this->data['maxSizeReadable'] = $this->moduleModel->returnMaxUploadSize(true);

        $this->data['error_code'] = isset($this->error['code']) ? $this->error['code'] : '';

        $this->data['uploaded_videos'] = array();

        $uploaded_videos_folder = IMODULE_ROOT . $this->modulePathVideo . 'uploaded_videos/';

        // Uploaded Videos
        if (is_dir($uploaded_videos_folder)) {
            $uploaded_videos = $this->scan_dir($uploaded_videos_folder);
            foreach ($uploaded_videos as $uploaded_video) {
                if ($this->is_video(IMODULE_ROOT . $this->modulePathVideo . 'uploaded_videos/' . $uploaded_video)) {
                    $src = HTTP_CATALOG . $this->modulePathVideo . 'uploaded_videos/' . $uploaded_video;

                    $file = pathinfo($src);

                    if (!empty($file['extension']) && in_array($file['extension'], $this->allowed_video_extensions)) {
                        $type = 'video/' . $file['extension'];
                    } else {
                        $type = false;
                    }

                    $img = $this->model_tool_image->resize(str_replace('image', '', $this->modulePathVideo) . DIRECTORY_SEPARATOR . preg_replace('/\.\w+$/', '.jpg', $uploaded_video), 104, 104);

                    $this->data['uploaded_videos'][] = array(
                        'path'			=>	$this->modulePathVideo . 'uploaded_videos/' . $uploaded_video,
                        'basepath'      =>  basename($this->modulePathVideo . 'uploaded_videos/' . $uploaded_video),
                        'src'			=>	$src,
                        'type'			=>  $type,
                        'name'			=>  $uploaded_video,
                        'img'			=>  $img
                    );
                }
            }
        }

        // Languages

        $this->data['languages'] = array();

        $results = $this->model_localisation_language->getLanguages();

        foreach ($results as $result) {
            if ((int)$result['status'] == 1) {

                $flag = version_compare(VERSION, '2.2.0.0', "<") ? 'view/image/flags/' . $result['image'] : 'language/' . $result['code'] . '/' . $result['code'] . '.png';

                $this->data['languages'][] = array(
                    'language_id'	=> $result['language_id'],
                    'name'			=> $result['name'],
                    'flag'			=> $flag
                );
            }
        }

        $this->data['HTTP_CATALOG'] = HTTP_CATALOG;
    }

    public function get_iproductvideo_settings() {
        /* Load static template data */
        $this->load_static_template_data();

        /* Get current IDs */
        $store_id		= 	$this->request->get['active_store_id'];
        $video_count	= 	$this->request->get['video_count'];

        // Store Image sizes
        $this->data['store']['store_info'] = $this->model_setting_setting->getSetting('config', $store_id);

        $this->data['store']['store_id'] = $store_id;
        $this->data['video_id'] 		 = $video_count;

        // Auto assign product based on filter product
        $filter_product_id = !empty($this->request->get['filter_product_id']) ? $this->request->get['filter_product_id'] : 0;
        $this->data['assignProduct'] = array();

        if ($filter_product_id) {
            $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($filter_product_id);

            if (!empty($product_info)) {
                $this->data['assignProduct'] = array(
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name']
                );
            }
        }

        $this->response->setOutput($this->load->view($this->modulePath . '/iproductvideo_settings', $this->data));
    }

    public function index() {
        $this->upgradeIfNecessary();

        /* Load static template data */
        $this->load_static_template_data();

        /* Generate default index */
        $this->document->setTitle($this->language->get('heading_title'));

        /* jQuery UI */
        $this->document->addScript('view/javascript/iproductvideo/jquery-ui/jquery-ui.min.js');
        $this->document->addStyle('view/javascript/iproductvideo/jquery-ui/jquery-ui.min.css');

        /* Bootstrap Form Helpers */
        $this->document->addScript('view/javascript/iproductvideo/bootstrap/js/bootstrap-formhelpers-colorpicker.js');
        $this->document->addScript('view/javascript/iproductvideo/bootstrap/js/bootstrap-formhelpers-selectbox.js');

        /* jQuery File Upload Plugin */
        $this->document->addScript('view/javascript/iproductvideo/jquery-fileupload/jquery.ui.widget.js');
        $this->document->addScript('view/javascript/iproductvideo/jquery-fileupload/jquery.iframe-transport.js');
        $this->document->addScript('view/javascript/iproductvideo/jquery-fileupload/jquery.postmessage-transport.js');
        $this->document->addScript('view/javascript/iproductvideo/jquery-fileupload/jquery.xdr-transport.js');
        $this->document->addScript('view/javascript/iproductvideo/jquery-fileupload/jquery.fileupload.js');

        /* NProgress */
        $this->document->addScript('view/javascript/iproductvideo/nprogress/nprogress.js');

        /* iProductVideo */
        $this->document->addStyle('view/stylesheet/iproductvideo.css');

        /* Permissions and compatibility checks */
        $this->warning_check();

        // Stores
        $stores = array_merge(array
            (0 => array(
                'store_id' => '0',
                'name' => $this->config->get('config_name') . ' (' .$this->data['text_default'] . ')',
                'url' => NULL, 'ssl' => NULL)
            ),
            $this->model_setting_store->getStores()
        );

        $this->data['stores'] = $stores;

        $filter_name = !empty($this->request->get['filter_name']) ? $this->request->get['filter_name'] : '';
        $filter_product_id = !empty($this->request->get['filter_product_id']) ? $this->request->get['filter_product_id'] : 0;
        $page = !empty($this->request->get['page']) ? $this->request->get['page'] : 1;
        $limit = 5;

        $this->data['filter_name'] = $filter_name;
        $this->data['filter_product_id'] = $filter_product_id;

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (!$this->user->hasPermission('modify', $this->modulePath)) {
                $this->session->data['flash_error'][] = $this->language->get('error_permission');
                $this->response->redirect($this->url->link($this->modulePath, $this->tokenString . '=' . $this->session->data[$this->tokenString], 'SSL'));
            }

            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post['iproductvideo']['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
                unset($this->request->post['OaXRyb1BhY2sgLSBDb21']);
            }

            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post['iproductvideo']['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
                unset($this->request->post['cHRpbWl6YXRpb24ef4fe']);
            }

            // Data validation
            $this->validate();

            if ($this->user->hasPermission('modify', $this->modulePath)) {
                foreach ($stores as $store) {
                    $videos = array();
                    if (!empty($this->request->post['iProductVideo'][$store['store_id']]['Videos'])) {
                        $videos = $this->request->post['iProductVideo'][$store['store_id']]['Videos'];
                        unset($this->request->post['iProductVideo'][$store['store_id']]['Videos']);
                    }
                    $this->moduleModel->updateVideos($store['store_id'], $videos, $page, $limit, $filter_name, $filter_product_id);
                }

                $this->moduleModel->editSetting('iproductvideo', $this->request->post);

                if ($this->request->post[$this->moduleName][$store['store_id']]['Enabled'] == 'true'){
                    $this->model_setting_setting->editSetting('module_'.$this->moduleNameSmall, array('module_'.$this->moduleNameSmall.'_status' => 1));
                } else{
                    $this->model_setting_setting->editSetting('module_'.$this->moduleNameSmall, array('module_'.$this->moduleNameSmall.'_status' => 0));
                }

                $this->session->data['flash_success'][] = $this->language->get('text_success');
                //$this->response->redirect($this->url->link($this->modulePath, $this->tokenString . '=' . $this->session->data[$this->tokenString], 'SSL'));
                $this->response->redirect($this->request->server['HTTP_REFERER']);
            }
        }

        $this->data['breadcrumbs'] = array(
            array(
                'text'      => $this->language->get('text_home'),
                'href'      => $this->url->link('common/dashboard', $this->tokenString . '=' . $this->session->data[$this->tokenString], 'SSL'),
                'separator' => false
            ),
            array(
                'text'      => $this->language->get('text_module'),
                'href'      => $this->extensionsLink,
                'separator' => ' :: '
            ),
            array(
                'text'      => $this->language->get('heading_title'),
                'href'      => $this->url->link($this->modulePath, $this->tokenString . '=' . $this->session->data[$this->tokenString], 'SSL'),
                'separator' => ' :: '
            )
        );

        $get_params = '&page='.$page;
        $get_params .= '&filter_name=' . $filter_name;
        $get_params .= '&filter_product_id=' . $filter_product_id;
        $this->data['action'] = $this->url->link($this->modulePath, $this->tokenString . '=' . $this->session->data[$this->tokenString] . $get_params, 'SSL');
        $this->data['action_search'] = $this->url->link($this->modulePath, $this->tokenString . '=' . $this->session->data[$this->tokenString] . (!empty($filter_product_id) ? '&filter_product_id=' . $filter_product_id : ''), 'SSL');
        $this->data['action_filter'] = $this->url->link($this->modulePath, $this->tokenString . '=' . $this->session->data[$this->tokenString] . (!empty($filter_name) ? '&filter_name=' . $filter_name : ''), 'SSL');
        $this->data['cancel'] = $this->extensionsLink;

        if (!empty($filter_product_id)) {
            $this->load->model('catalog/product');
            $prod_info = $this->model_catalog_product->getProduct($filter_product_id);
            $this->data['filter_product_name'] = !empty($prod_info['name']) ? $prod_info['name'] : '';
        } else {
            $this->data['filter_product_name'] = '';
        }

        $configValue = $this->moduleModel->getSetting('iproductvideo');
        foreach ($stores as $store) {
            if (!empty($configValue['iProductVideo'][$store['store_id']])) {
                $configValue['iProductVideo'][$store['store_id']]['Videos'] = $this->moduleModel->getVideos($store['store_id'], $page, $limit, $filter_name, $filter_product_id);
            }
        }
        $this->data['data'] = $configValue;

        $languages = array();
        $this->data['store_languages'] = array();

        $results = $this->model_localisation_language->getLanguages();

        foreach ($results as $result) {
            if ((int)$result['status'] == 1) {

                $flag = version_compare(VERSION, '2.2.0.0', "<") ? 'view/image/flags/' . $result['image'] : 'language/' . $result['code'] . '/' . $result['code'] . '.png';

                $languages[] = array(
                    'language_id'	=> $result['language_id'],
                    'name'			=> $result['name'],
                    'flag'			=> $flag
                );
            }
        }

        $this->data['store_languages'] = $languages;

        // Products
        $this->load->model('catalog/product');
        $products = array();

        $this->data['products'] = array();

        $this->data['thumb_no_image_120_90'] = $this->model_tool_image->resize('no_image.png', 120, 90);
        $this->data['paginations'] = array();
        $this->data['total_videos'] = array();
        $this->data['last_video_ids'] = array();

        $start = ($page-1)*$limit;
        $end = $page*$limit;

        foreach ($stores as $store) {
            $this->data['total_videos'][$store['store_id']] = $this->moduleModel->getTotalVideos($store['store_id'], $filter_name, $filter_product_id);
            $this->data['last_video_ids'][$store['store_id']] = $this->moduleModel->getLastVideoId($store['store_id']);

            if (!empty($this->data['data']['iProductVideo']) && !empty($this->data['data']['iProductVideo'][$store['store_id']]['Videos'])) {

                foreach ($this->data['data']['iProductVideo'][$store['store_id']]['Videos'] as $iproductvideo_video_id => &$video) {

                    foreach ($languages as $language) {
                        if (!empty($video[$language['language_id']]['LimitProductsList'])) {
                            $products = $video[$language['language_id']]['LimitProductsList'];
                        } else {
                            $products = array();
                        }

                        $this->data['products'][$store['store_id']]['Videos'][$iproductvideo_video_id][$language['language_id']] = array();

                        foreach ($products as $product_id) {

                            $product_info = $this->model_catalog_product->getProduct($product_id);

                            if ($product_info) {
                                $this->data['products'][$store['store_id']]['Videos'][$iproductvideo_video_id][$language['language_id']][] = array(
                                    'product_id' 	=> $product_info['product_id'],
                                    'name'        	=> $product_info['name']
                                );
                            }
                        }

                        //Assign image thumbs
                        if (is_array($video)) {
                            if (empty($video['thumb'])) {
                                $video['thumb'] = $this->model_tool_image->resize('no_image.png', 120, 90);
                            }

                            if (!empty($video[$language['language_id']])) {
                                $video_info = $video[$language['language_id']];
                                if (!empty($video_info['VideoType']) && $video_info['VideoType'] == 'uploaded' && !empty($video_info['LocalVideo'])) {
                                    $img = str_replace('image', '', $this->modulePathVideo) . preg_replace('/\.\w+$/', '.jpg', basename($video_info['LocalVideo']));
                                    if (file_exists(DIR_IMAGE . $img)) {
                                        $video['thumb'] = $this->model_tool_image->resize($img, 120, 90);
                                    }
                                } else if(!empty($video_info['VideoURL'])) {
                                    if (strpos($video_info['VideoURL'], 'youtube.com/watch') !== false) {
                                        parse_str(parse_url($video_info['VideoURL'], PHP_URL_QUERY), $query_vars);
                                        $video_id = !empty($query_vars['v']) ? $query_vars['v'] : '';
                                        if ($video_id) {
                                            $video['thumb'] = "http://img.youtube.com/vi/$video_id/0.jpg";
                                        }
                                    }

                                    if (strpos($video_info['VideoURL'], 'youtu.be') !== false) {
                                        $video_id = basename(parse_url($video_info['VideoURL'], PHP_URL_PATH));
                                        if ($video_id) {
                                            $video['thumb'] = "http://img.youtube.com/vi/$video_id/0.jpg";
                                        }
                                    }

                                    if (strpos($video_info['VideoURL'], 'vimeo.com') !== false) {
                                        $video_meta = $this->cache->get('iproductvideo.' . md5($video_info['VideoURL']));

                                        if ($video_meta) {
                                            $video['thumb'] = $video_meta['thumbnail_url'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $page = !empty($this->request->get['page']) ? $this->request->get['page'] : 1;
        $get_params = "&filter_name=" . $filter_name;
        $this->data['page'] = $page;

        $this->data['paginations'] = array();
        foreach ($this->data['total_videos'] as $store_id => $count) {
            $pagination = new Pagination();
            $pagination->total = $count;
            $pagination->page = $page;
            $pagination->limit = 5;
            $pagination->url = $this->url->link($this->modulePath, $this->tokenString . '=' . $this->session->data[$this->tokenString] . $get_params . '&page={page}', 'SSL');

            $this->data['paginations'][$store_id] = $pagination->render();
        }

        // Store Image sizes

        foreach ($this->data['stores'] as $k => $store) {
            $this->data['stores'][$k]['store_info'] = $this->model_setting_setting->getSetting('config', $store['store_id']);
        }
        $this->data['unlicensedHtml'] 		= empty($this->data['data']['iproductvideo']['LicensedOn']) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4KICAgICAgICA8YnV0dG9uIHR5cGU9ImJ1dHRvbiIgY2xhc3M9ImNsb3NlIiBkYXRhLWRpc21pc3M9ImFsZXJ0IiBhcmlhLWhpZGRlbj0idHJ1ZSI+w5c8L2J1dHRvbj4KICAgICAgICA8aDQ+V2FybmluZyEgVW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoZSBtb2R1bGUhPC9oND4KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2PgogICAgICAgIDxhIGNsYXNzPSJidG4gYnRuLWRhbmdlciIgaHJlZj0iamF2YXNjcmlwdDp2b2lkKDApIiBvbmNsaWNrPSIkKCdhW2hyZWY9I3RhYl9TdXBwb3J0XScpLnRyaWdnZXIoJ2NsaWNrJykiPkVudGVyIHlvdXIgbGljZW5zZSBjb2RlPC9hPgogICAgPC9kaXY+') : '';
        $this->data['header'] 		= $this->load->controller('common/header');
        $this->data['column_left'] 	= $this->load->controller('common/column_left');
        $this->data['footer'] 		= $this->load->controller('common/footer');

        $dirname = DIR_APPLICATION . 'view/template/' . $this->modulePath . '/';

        $tab_files = scandir($dirname);
        $tabs = array();
        foreach ($tab_files as $key => $file) {
            if (strpos($file,'tab_') !== false) {
                $tabs[] = array(
                    'file' => $this->modulePath . '/' . $file,
                    'name' => ucwords(str_replace('.twig','',str_replace('_',' ',str_replace('tab_','',$file))))
                );
            }
        }
        foreach ($tabs as $key => $tab) {
            if ($tab['name'] == 'Support' && $key < count($tabs) - 1) {
                $temp = $tabs[count($tabs) - 1];
                $tabs[count($tabs) - 1] = $tab;
                $tabs[$key] = $temp;
                break;
            }
        }

        $this->data['tabs'] = $tabs;

        $this->data['base'] = preg_replace('/^https?\:\/\//', '//', HTTP_CATALOG);

        $this->data['html_decoded_action_search'] = html_entity_decode($this->data['action_search']);
        $this->data['html_decoded_action_filter'] = html_entity_decode($this->data['action_filter']);

        $this->data['timenow'] = time();
        $this->data['hostname'] = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '' ;
        $this->data['hostname'] = (strstr($this->data['hostname'],'http://') === false) ? 'http://'.$this->data['hostname']: $this->data['hostname'];
        $this->data['hostname_base64'] = base64_encode($this->data['hostname']);
        if (!empty($configValue['iProductVideo']['LicensedOn'])) {
            $this->data['cHRpbWl6YXRpb24ef4fe'] = base64_encode(json_encode($configValue['iProductVideo']['License']));
        }
        $this->data['open_ticket_url'] = 'http://isenselabs.com/tickets/open/'.base64_encode('Support Request').'/'.base64_encode('132').'/'.base64_encode($_SERVER['SERVER_NAME']);

        $this->response->setOutput($this->load->view($this->modulePath, $this->data));
    }

    private function upgradeIfNecessary() {
        $tables = $this->db->query("SHOW TABLES LIKE '%iproductvideo%'");
        if ($tables->num_rows == 0) {
            $this->moduleModel->install();
            $this->moduleModel->migrate_data();
        }

        $oldFolder = 'image/iproductvideo/';
        if (file_exists(IMODULE_ROOT . $oldFolder)) {
            $status = @rename(IMODULE_ROOT . $oldFolder, IMODULE_ROOT . $this->modulePathVideo);

            if ($status) {
                $this->session->data['success'] = 'Successfully update video path. Please save the module to update database.';
            } else {
                $this->error['warning'] = 'Fail to move video folder "' . $oldFolder . '" to "' . $this->modulePathVideo . '". Please contact our support for further information.';
            }
        }
    }

    private function warning_check() {
        $default_dirs = array('uploaded_videos');

        foreach ($default_dirs as $dir) {
            if (!file_exists(IMODULE_ROOT . $this->modulePathVideo . '' . $dir)) {
                mkdir(IMODULE_ROOT . $this->modulePathVideo . '' . $dir, 0777, true);
            }
        }

        $this->data['warning_modal'] = false;
        $iproductvideo_dirs = $this->scan_dir(IMODULE_ROOT . $this->modulePathVideo);

        foreach ($iproductvideo_dirs as $dir) {
            if (!is_readable(IMODULE_ROOT . $this->modulePathVideo . '' . $dir)) {
                $this->data['warning_modal']['errors'][] = 'No read permissions for <b>' . '/' . $this->modulePathVideo . $dir . '</b>';
            }
            if (!is_writable(IMODULE_ROOT . $this->modulePathVideo . '' . $dir)) {
                $this->data['warning_modal']['errors'][] = 'No write permissions for <b>' . '/' . $this->modulePathVideo . $dir . '</b>';
            }
        }
    }


    private function validate() {
        if (!empty($this->session->data['flash_error'])) {
            $this->response->redirect($this->url->link($this->modulePath, $this->tokenString . '=' . $this->session->data[$this->tokenString], 'SSL'));
        }
    }

    public function autocomplete_product() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 20;
            }

            $data = array(
                'filter_name'  => $filter_name,
                'filter_model' => $filter_model,
                'start'        => 0,
                'limit'        => $limit
            );

            $results = $this->model_catalog_product->getProducts($data);

            foreach ($results as $result) {
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model'      => $result['model'],
                    'price'      => $result['price']
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function upload_video() {
        $json = array();

        if (!$this->user->hasPermission('modify', $this->modulePath)) {
            $json['error'] = $this->language->get('error_permission');
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        } else {
            if (VERSION < '2.1') {
                $this->load->library('iProductVideoUploadHandler');
            }

            $file_upload_options = array(
                'upload_dir'	=> IMODULE_ROOT . $this->modulePathVideo . 'uploaded_videos/',
                'upload_url'	=> HTTP_CATALOG . $this->modulePathVideo . 'uploaded_videos/',
                'param_name'	=> $this->request->get['upload_param'],
                'max_file_size'	=> $this->moduleModel->returnMaxUploadSize()
            );

            $upload_handler = new iProductVideoUploadHandler($file_upload_options);
        }
    }

    public function upload_video_screenshot() {
        if ($this->user->hasPermission('modify', $this->modulePath) &&
            !empty($this->request->post['name']) &&
            !empty($this->request->post['img'])) {
            $destination = DIR_IMAGE . str_replace('image/', '', $this->modulePathVideo) . DIRECTORY_SEPARATOR . preg_replace('/\.\w+$/', '.jpg', $this->request->post['name']);
            if (!is_dir(dirname($destination)) && !mkdir(dirname($destination))) exit;
            $image = imagecreatefromstring(base64_decode(str_replace('data:image/jpeg;base64,', '', $this->request->post['img'])));
            imagejpeg($image, $destination, 100);
        }
        exit;
    }

    public function delete_video($video = false) {
        $json = array();

        if (!$this->user->hasPermission('modify', $this->modulePath)) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            if (isset($this->request->get['video'])) {
                $video = $this->request->get['video'];
            } else {
                exit;
            }

            if (file_exists(IMODULE_ROOT . $this->modulePathVideo . 'uploaded_videos/' . $video)) {
                @unlink(IMODULE_ROOT . $this->modulePathVideo . 'uploaded_videos/' . $video);
            }

            $img = DIR_IMAGE . 'iproductvideo' . DIRECTORY_SEPARATOR . preg_replace('/\.\w+$/', '.jpg', $video);
            if (file_exists($img)) {
                @unlink($img);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function load_uploaded_videos() {
        if (isset($this->request->get['store_id'])) {
            $this->data['store']['store_id'] = $this->request->get['store_id'];
        } else {
            exit;
        }

        if (isset($this->request->get['video_id'])) {
            $this->data['video_id'] = $this->request->get['video_id'];
        } else {
            exit;
        }

        if (isset($this->request->get['language_id'])) {
            $this->data['language']['language_id'] = $this->request->get['language_id'];
        } else {
            exit;
        }

        // Preserve Video
        $this->data['_video'] = array();

        if (isset($this->request->get['video'])) {
            $this->data['_video']['LocalVideo'] = $this->request->get['video'];
        }

        // ID Base Per Language
        $this->data['_id']	= 'store_' . $this->data['store']['store_id'] . '_video_' . $this->request->get['video_id'] . '_language_' . $this->data['language']['language_id'];

        // Video Name Base Per Language
        $this->data['_vnb']	= 'iProductVideo[' . $this->data['store']['store_id'] . '][Videos][' . $this->request->get['video_id'] . '][' . $this->data['language']['language_id'] . ']';

        $uploaded_videos_folder = IMODULE_ROOT . $this->modulePathVideo . 'uploaded_videos/';
        $uploaded_videos = $this->scan_dir($uploaded_videos_folder);

        foreach ($uploaded_videos as $uploaded_video) {
            if ($this->is_video(IMODULE_ROOT . $this->modulePathVideo . 'uploaded_videos/' . $uploaded_video)) {
                $src = HTTP_CATALOG . $this->modulePathVideo . 'uploaded_videos/' . $uploaded_video;

                $file = pathinfo($src);

                if (!empty($file['extension']) && in_array($file['extension'], $this->allowed_video_extensions)) {
                    $type = 'video/' . $file['extension'];
                } else {
                    $type = false;
                }

                $img = $this->model_tool_image->resize(str_replace('image/', '', $this->modulePathVideo) . DIRECTORY_SEPARATOR . preg_replace('/\.\w+$/', '.jpg', $uploaded_video), 104, 104);

                $this->data['uploaded_videos'][] = array(
                    'title'         =>  $uploaded_video,
                    'path'          =>  $this->modulePathVideo . 'uploaded_videos/' . $uploaded_video,
                    'src'           =>  $src,
                    'type'			=>  $type,
                    'name'			=>  $uploaded_video,
                    'img'           =>  $img
                );
            }
        }

        if (!empty($this->data['uploaded_videos'])) {
            $this->response->setOutput($this->load->view($this->modulePath . '/videos_loop', $this->data));
        } else {
            $this->response->setOutput('<span> No uploaded videos.<br /> Use the upload form below<br /> or put your videos in this server directory<br /> <b>/vendors/iproductvideo/uploaded_videos</b> </span>');
        }
    }

    public function remove_product_video(&$route, &$data) {
        if (!empty($data[0])) {
            $remove_product_ids = array($data[0]);
        } elseif (!empty($this->request->post['selected'])) {
            $remove_product_ids = $this->request->post['selected'];
        } else {
            return;
        }
        $this->moduleModel->remove_product_video($remove_product_ids);
    }

    private function scan_dir($dir) {
        $ignored = array('.', '..');

        $files = array();

        foreach (scandir($dir) as $file) {
            if (!in_array($file, $ignored)) {
                $files[$file] = filemtime($dir . '/' . $file);
            }
        }

        arsort($files);
        $files = array_keys($files);

        return ($files) ? $files : array();
    }

    private function clean_filename($name) {
        $filename = preg_replace("([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})", '', $name);

        if (function_exists('mb_convert_encoding')) {
            $filename = mb_convert_encoding($filename, 'UTF-8');
        } else {
            $filename = urlencode($filename);
        }

        $filename = preg_replace("([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})", '', $filename);

        return $filename;
    }

    private function is_video($path) {
        $file = pathinfo($path);

        if (!empty($file['extension']) && in_array($file['extension'], $this->allowed_video_extensions)) {
            return true;
        }

        return false;
    }

    private function is_image($path) {
        $a = getimagesize($path);

        if ($a !== false && !empty($a['mime']) && in_array($a['mime'], $this->image_mime_types)) {
            return true;
        }

        return false;
    }
}
?>
