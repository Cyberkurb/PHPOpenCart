<?php
class ModelExtensionModuleiProductVideo extends Model {
    private $iproductvideo_settings;
    private $db_column = 'group';
    private $modulePathVideo = 'catalog/iproductvideo/';

    public function __construct($register) {
        if (!defined('IMODULE_ROOT')) define('IMODULE_ROOT', substr(DIR_APPLICATION, 0, strrpos(DIR_APPLICATION, '/', -2)) . '/');
        if (!defined('IMODULE_SERVER_NAME')) define('IMODULE_SERVER_NAME', substr((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER), 7, strlen((defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTP_SERVER)) - 8));
        parent::__construct($register);

        $db_column_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting LIMIT 1");

        if (isset($db_column_query->row['code'])) {
            $this->db_column = 'code';
        }

        $store_id = $this->config->get('config_store_id');
        $language_id = $this->config->get('config_language_id');
        $product_id = !empty($this->request->get['product_id']) ? $this->request->get['product_id'] : -1;

        $this->iproductvideo_settings = $this->getSetting('iproductvideo');

        if (!empty($this->iproductvideo_settings['iProductVideo'][$store_id])) {
            $this->iproductvideo_settings['iProductVideo'][$store_id]['Videos'] = $this->getVideos((int)$store_id, (int)$language_id, (int)$product_id);
        }
    }

    private function getVideos($store_id, $language_id, $product_id) {
        $results = $this->db->query("SELECT ipv.*, ipvd.* FROM (SELECT * FROM " . DB_PREFIX . "iproductvideo WHERE store_id=" . (int)$store_id . ") AS ipv LEFT JOIN " . DB_PREFIX . "iproductvideo_description AS ipvd ON (ipv.video_id=ipvd.video_id) LEFT JOIN " . DB_PREFIX . "iproductvideo_meta AS ipvm ON (ipvd.description_id=ipvm.description_id) WHERE ipvd.language_id=" . (int)$language_id . " AND (ipvd.link_to_products='all' OR ipvm.product_id=" . (int)$product_id . ")");

        $videos = array();
        if (!empty($results)) {
            foreach ($results->rows as $row) {
                if (empty($videos[$row['video_id']])) {
                    $videos[$row['video_id']] = array(
                        'title' => $row['title']
                    );
                }
                $videos[$row['video_id']][$row['language_id']] = array(
                    'VideoType' => $row['type'],
                    'VideoURL' => $row['url'],
                    'MainImage' => $row['main_image'],
                    'SortOrder' => $row['sort_order'],
                    'LimitProducts' => $row['link_to_products']
                );

                if (!empty($row['local_video'])) {
                    $videos[$row['video_id']][$row['language_id']]['LocalVideo'] = $row['local_video'];
                }

                if ($row['link_to_products'] == 'specific') {
                    $products = $this->db->query("SELECT * FROM " . DB_PREFIX . "iproductvideo_meta WHERE description_id=" . (int)$row['description_id']);

                    $videos[$row['video_id']][$row['language_id']]['LimitProductsList'] = array();
                    foreach ($products->rows as $product) {
                        $videos[$row['video_id']][$row['language_id']]['LimitProductsList'][] = $product['product_id'];
                    }
                }
            }
        }
        return $videos;
    }

    public function getSetting($group, $store_id = 0) {
        $data = array(); 

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `" . $this->db_column . "` = '" . $this->db->escape($group) . "'");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $data[$result['key']] = $result['value'];
            } else {
                $data[$result['key']] = unserialize($result['value']);
            }
        }

        return $data;
    }

    private function getImageConfigs($name) {

        if(version_compare(VERSION, '2.2.0.0', '<')) {
            return $this->config->get($name);
        } else if(version_compare(VERSION, '3.0.0.0', '<')) {
            if(strpos($name, 'config_image') !== false){
                $name = str_replace('config', '', $name);
                return $this->config->get($this->config->get('config_theme') . $name);
            }
        } else {
            /* OpenCart 3.0.x above */
            if(strpos($name, 'config_image') !== false){
                $name = str_replace('config', '', $name);
                return $this->config->get('theme_' . $this->config->get('config_theme') . $name);
            }
        }   
    }

    public function init($product_id = 0, $data) {
        $this->load->model('tool/image');

        // presetting avoid error
        $data['iproductvideo'] = array(
            'autoplay'    => false,
            'nocookie'    => false,
            'main_thumb'  => false,
            'main_source' => '', // youtube|vimeo|local
            'main_id'     => '',
            'main_ext'    => ''
        );

        if (!empty($product_id) && !empty($this->iproductvideo_settings['iProductVideo'][$this->config->get('config_store_id')])) {

            $settings = $this->iproductvideo_settings['iProductVideo'][$this->config->get('config_store_id')];

            if (!empty($settings['Enabled']) && $settings['Enabled'] == 'true' && !empty($settings['Videos'])) {
                $this->load->model('catalog/product');

                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info['image']) {
                    $original_thumb = $product_info['image'];
                } else {
                    $original_thumb = false;
                }

                $data['iproductvideo']['autoplay'] = !empty($settings['autoplay']) && $settings['autoplay'] == 'true';
                $data['iproductvideo']['nocookie'] = !empty($settings['nocookie']) && $settings['nocookie'] == 'true';

                foreach ($settings['Videos'] as $video_id => $video) {

                    $language_id = $this->config->get('config_language_id');

                    if (!empty($language_id) && !empty($video[(int)$language_id])) {
                        $video_settings = $video[(int)$language_id];

                        if (!empty($video_settings['LimitProducts']) && $video_settings['LimitProducts'] == 'specific') {
                            if (!empty($video_settings['LimitProductsList']) && in_array($product_id, $video_settings['LimitProductsList'])) {
                                $video_active = true;
                            } else {
                                $video_active = false;
                            }
                        } elseif (!empty($video_settings['LimitProducts']) && $video_settings['LimitProducts'] == 'all') {
                            $video_active = true;
                        } else {
                            $video_active = false;
                        }

                        if ($video_active) {
                            if (empty($video_settings['VideoType'])) $video_settings['VideoType'] = 'internet';//dirty fix for a bigger issue

                            if (!empty($video_settings['VideoType'])) {
                                $sort_order = (!empty($video_settings['SortOrder'])) ? (int)$video_settings['SortOrder'] : 0;

                                // Internet Video
                                if ($video_settings['VideoType'] == 'internet' && !empty($video_settings['VideoURL'])) {
                                    $parsed_video = $this->parse_video_url($video_settings['VideoURL']);

                                    // Main Image
                                    if (!empty($video_settings['MainImage']) && (bool)$video_settings['MainImage'] == true) {                                        
                                        $data['iproductvideo']['main_thumb']  = true;
                                        $data['iproductvideo']['main_source'] = $parsed_video['type'];
                                        $data['iproductvideo']['main_id']     = isset($parsed_video['external_id']) ? $parsed_video['external_id'] : '';

                                        $data['popup'] = (preg_match('/youtu\.?be/', $video_settings['VideoURL']) ? '//www.youtube.com/watch?v=' . $parsed_video['external_id'] : $video_settings['VideoURL']) . '#iproductvideo';
                                        $data['thumb'] = $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_thumb_width'), $this->getImageConfigs('config_image_thumb_height'));


                                        /* Journal */
                                        $data['original'] = $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height'));

                                        if (class_exists("Journal2Utils") && strpos($this->config->get('config_template'), 'journal2') === 0) {
                                            $data['thumb'] = $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height'));
                                            $data['popup_fixed'] = (preg_match('/youtu\.?be/', $video_settings['VideoURL']) ? '//www.youtube.com/watch?v=' . $parsed_video['external_id'] : $video_settings['VideoURL']) . '#iproductvideo';
                                            $data['thumb_fixed'] = $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height'));
                                        }
                                        /* Journal */

                                        if (class_exists('Journal3')) {
                                            $this->array_insert($data['images'], array(
                                                'popup'                => (preg_match('/youtu\.?be/', $video_settings['VideoURL']) ? '//www.youtube.com/watch?v=' . $parsed_video['external_id'] : $video_settings['VideoURL']) . '#iproductvideo',
                                                'thumb'                => $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height')),
                                                'original'             => $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height')),
                                                'iProductVideo'        => true,
                                                'iProductVideo_source' => $parsed_video['type'],
                                                'iProductVideo_id'     => isset($parsed_video['external_id']) ? $parsed_video['external_id'] : '',
                                                'iProductVideo_ext'    => '',

                                                'ipv_journal3'         => true,
                                                'galleryThumb'         => $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height')),
                                                'image'                => $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height')),
                                            ), 0);
                                        }
                                    }

                                    // Additional Images
                                    if (empty($video_settings['MainImage'])) {
                                        $this->array_insert($data['images'], array(
                                            'popup' => (preg_match('/youtu\.?be/', $video_settings['VideoURL']) ? '//www.youtube.com/embed/' . $parsed_video['external_id'] : $video_settings['VideoURL']) . '#iproductvideo',
                                            'thumb' => $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height')),
                                            'original' => $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height')),
                                            'iProductVideo'        => true,
                                            'iProductVideo_source' => isset($parsed_video['type']) ? $parsed_video['type'] : "",
                                            'iProductVideo_id'     => isset($parsed_video['external_id']) ? $parsed_video['external_id'] : '',
                                            'iProductVideo_ext'    => '',

                                            // Journal 3
                                            'galleryThumb' => $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height')),
                                            'image'        => $this->resize($parsed_video['thumb'], $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height')),
                                        ), $sort_order);
                                    }
                                }

                                // Uploaded Video
                                if ($video_settings['VideoType'] == 'uploaded' && !empty($video_settings['LocalVideo'])) {

                                    $img = $this->modulePathVideo . DIRECTORY_SEPARATOR . preg_replace('/\.\w+$/', '.jpg', basename($video_settings['LocalVideo']));

                                    // Main Image
                                    if (file_exists(DIR_IMAGE . $img)) {
                                        $thumb_poster = $img;
                                    } else if (!empty($original_thumb)) {
                                        $thumb_poster = $original_thumb;
                                    } else {
                                        $thumb_poster = $this->modulePathVideo . 'play_thumb.png';
                                    }

                                    if (!empty($video_settings['MainImage']) && (bool)$video_settings['MainImage'] == true) {
                                        $data['iproductvideo']['main_thumb']  = true;
                                        $data['iproductvideo']['main_source'] = 'local';
                                        $video_segments = explode('.', $video_settings['LocalVideo']);
                                        $data['iproductvideo']['main_ext']    = end($video_segments);

                                        $data['popup'] = $video_settings['LocalVideo'] . '#iproductvideo_local';
                                        $data['thumb'] = $this->resize($thumb_poster, $this->getImageConfigs('config_image_thumb_width'), $this->getImageConfigs('config_image_thumb_height'));

                                        /* Journal */
                                        $data['original'] = $this->resize($thumb_poster, $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height'));

                                        if (class_exists("Journal2Utils") && strpos($this->config->get('config_template'), 'journal2') === 0) {
                                            $data['thumb'] = $this->resize($thumb_poster, $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height'));
                                            $data['popup_fixed'] = $video_settings['LocalVideo'] . '#iproductvideo_local';
                                            $data['thumb_fixed'] = $this->resize($thumb_poster, $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height'));
                                        }
                                        /* Journal */

                                        if (class_exists('Journal3')) {
                                            $this->array_insert($data['images'], array(
                                                'popup'                => $video_settings['LocalVideo'] . '#iproductvideo_local',
                                                'thumb'                => $this->resize($thumb_poster, $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height')),
                                                'original'             => $this->resize($thumb_poster, $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height')),
                                                'iProductVideo'        => true,
                                                'iProductVideo_source' => 'local',
                                                'iProductVideo_id'     => '',
                                                'iProductVideo_ext'    => end($video_segments),

                                                'ipv_journal3'         => true,
                                                'galleryThumb'         => $this->resize($thumb_poster, $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height')),
                                                'image'                => $this->resize($thumb_poster, $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height')),
                                            ), 0);
                                        }
                                    }

                                    // Additional Images
                                    if (file_exists(DIR_IMAGE . $img)) {
                                        $additional_poster = $img;
                                    } else if (!empty($original_thumb)) {
                                        $additional_poster = $original_thumb;
                                    } else {
                                        $additional_poster = $this->modulePathVideo . 'play_thumb.png';
                                    }

                                    if (empty($video_settings['MainImage'])) {
                                        $video_segments = explode('.', $video_settings['LocalVideo']);

                                        $this->array_insert($data['images'], array(
                                            'popup' => $video_settings['LocalVideo'] . '#iproductvideo_local',
                                            'thumb' => $this->resize($additional_poster, $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height')),
                                            'original' => $this->resize($additional_poster, $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height')),
                                            'iProductVideo'        => true,
                                            'iProductVideo_source' => 'local',
                                            'iProductVideo_id'     => '',
                                            'iProductVideo_ext'    => end($video_segments),

                                            // Journal 3
                                            'galleryThumb' => $this->resize($additional_poster, $this->getImageConfigs('config_image_additional_width'), $this->getImageConfigs('config_image_additional_height')),
                                            'image'        => $this->resize($additional_poster, $this->getImageConfigs('config_image_popup_width'), $this->getImageConfigs('config_image_popup_height')),
                                        ), $sort_order);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function parse_video_url($video_url) {
        $result = array();

        // YouTube
        if (stripos($video_url, 'youtube.com') !== FALSE || stripos($video_url, 'youtu.be') !== FALSE) {
            $result['type'] = 'youtube';
            $video_info = $this->cache->get('iproductvideo.' . md5($video_url));
            if (!$video_info) {
                $video_info = json_decode($this->fetch_remote_content('http://www.youtube.com/oembed?url=' . urlencode($video_url)), true);
                $this->cache->set('iproductvideo.' . md5($video_url), $video_info);
            }

            if (preg_match('/\?v\=(.*?)(&|$)/i', $video_url, $matches)) {
                $result['external_id'] = $matches[1];
            } else if (stripos($video_url, 'youtu.be') !== FALSE) {
                $result['external_id'] = substr($video_url, strripos($video_url, '/') + 1);
            } else {
                $result['external_id'] = FALSE;
            }

            if (!empty($video_info['thumbnail_url'])) {
                $result['thumb'] = $video_info['thumbnail_url'];
            } else {
                $result['thumb'] = FALSE;
            }

            // Vimeo
        } else if (stripos($video_url, 'vimeo.com') !== FALSE) {
            $result['type'] = 'vimeo';
            $video_info = $this->cache->get('iproductvideo.' . md5($video_url));
            if (!$video_info) {
                $video_info = json_decode($this->fetch_remote_content('http://vimeo.com/api/oembed.json?url=' . urlencode($video_url)), true);
                $this->cache->set('iproductvideo.' . md5($video_url), $video_info);
            }

            if (!empty($video_info)) {
                $result['external_id'] = $video_info['video_id'];
            }

            if (!empty($video_info['thumbnail_url'])) {
                $result['thumb'] = $video_info['thumbnail_url'];
            } else {
                $result['thumb'] = FALSE;
            }
        }

        if (!empty($video_info['title'])) {
            $result['title'] = $video_info['title'];
        } else {
            $result['title'] = substr(md5(uniqid(rand(), true)), 0, 5);
        }

        if (!empty($result['thumb'])) {
            $thumb = pathinfo($result['thumb']);
            if (!empty($thumb['extension'])) {
                $thumb_temp = ((!empty($result['external_id'])) ? $result['external_id'] : $this->clean_filename($result['title'])) . '_thumb.png';

                $temp_dir = IMODULE_ROOT . 'image/' . $this->modulePathVideo . 'temp/';

                if (!file_exists($temp_dir)) {
                    @mkdir($temp_dir, 0777, true);
                }

                if (file_exists($temp_dir) && is_dir($temp_dir) && is_writable($temp_dir)) {
                    $thumb = $this->fetch_remote_content($result['thumb']);
                    imagepng(imagecreatefromstring($thumb), $temp_dir . $thumb_temp);

                    $result['thumb'] = $this->modulePathVideo . 'temp/' . $thumb_temp;
                }
            }
        }

        if (!empty($result['thumb'])) {
            $result['thumb'] = $result['thumb'];
        } elseif (file_exists(IMODULE_ROOT . 'image/' . $this->modulePathVideo . 'play_thumb.png')) {
            $result['thumb'] = $this->modulePathVideo . 'play_thumb.png';
        }

        return $result;
    }

    private function array_insert(&$array, $element, $position = null) {
        if (count($array) == 0) {
            $array[] = $element;
        } elseif (is_numeric($position) && $position < 0) {
            if((count($array)+$position) < 0) {
                $array = $this->array_insert($array,$element,0);
            } else {
                $array[count($array)+$position] = $element;
            }
        } elseif (is_numeric($position) && isset($array[$position])) {
            $part1 = array_slice($array,0,$position,true);
            $part2 = array_slice($array,$position,null,true);
            $array = array_merge($part1,array($position=>$element),$part2);
            foreach($array as $key=>$item) {
                if (is_null($item)) {
                    unset($array[$key]);
                }
            }
        } elseif (is_null($position)) {
            $array[] = $element;
        } elseif (!isset($array[$position])) {
            $array[$position] = $element;
        }
        $array = array_merge($array);
        return $array;
    }

    private function fetch_remote_content($url) {
        if (strpos($url, '//') === 0) {
            if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
                $url = 'https:'.$url;
            } else {
                $url = 'http:'.$url;
            }
        }

        if (ini_get('allow_url_fopen')) {
            $content = @file_get_contents($url);
            return $content;
        } else {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            $content = curl_exec($ch);
            curl_close($ch);
            return $content;
        }
        return false;
    }

    public function resize($filename, $width, $height) {
        if (!is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $old_image = $filename;
        $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-ipv-' . $width . 'x' . $height . '.' . $extension;

        $path = '';

        $directories = explode('/', dirname(str_replace('../', '', $new_image)));

        foreach ($directories as $directory) {
            $path = $path . '/' . $directory;

            if (!is_dir(DIR_IMAGE . $path)) {
                @mkdir(DIR_IMAGE . $path, 0777);
            }
        }

        list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

        if ($width_orig != $width || $height_orig != $height) {
            $image = new Image(DIR_IMAGE . $old_image);
            $image->resize($width, $height);
            $image->save(DIR_IMAGE . $new_image);

            // Base
            $base = imagecreatetruecolor($width, $height);
            $transparent = imagecolorallocatealpha($base, 255, 255, 255, 0);
            imagefill($base, 0, 0, $transparent);

            // Add Video Thumb
            if ($filename != $this->modulePathVideo . 'play_thumb.png') {
                $info = getimagesize(DIR_IMAGE . $new_image);

                $info = array(
                    'width'  => $info[0],
                    'height' => $info[1],
                    'bits'   => isset($info['bits']) ? $info['bits'] : '',
                    'mime'   => isset($info['mime']) ? $info['mime'] : ''
                );

                if ($info['mime'] == 'image/gif') {
                    $video_thumb = imagecreatefromgif(DIR_IMAGE . $new_image);
                } elseif ($info['mime'] == 'image/png') {
                    $video_thumb = imagecreatefrompng(DIR_IMAGE . $new_image);
                } elseif ($info['mime'] == 'image/jpeg') {
                    $video_thumb = imagecreatefromjpeg(DIR_IMAGE . $new_image);
                }

                imagecopyresampled($base, $video_thumb, 0, 0, 0, 0, $width, $height, $width, $height);
                imagedestroy($video_thumb);
            }

            // Add Play Thumb
            if (file_exists(DIR_IMAGE .$this->modulePathVideo . 'play_thumb.png')) {
                $watermark_thumb = imagecreatefrompng(DIR_IMAGE . $this->modulePathVideo . 'play_thumb.png');
                $thumb_width = (($width > 80) ? imagesx($watermark_thumb) : $width);
                $thumb_height = (($height > 80) ? imagesy($watermark_thumb) : $height);

                imagecopyresampled($base, $watermark_thumb, ($width/2) - ($thumb_width/2), ($height/2) - ($thumb_height/2), 0, 0, (($width < 80) ? $width : 80), (($height < 80) ? $height : 80), 80, 80);
                imagedestroy($watermark_thumb);
            }

            // Save Image
            imagepng($base, DIR_IMAGE . $new_image);
            imagedestroy($base);
        } else {
            copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
        }

        if ($this->request->server['HTTPS']) {
            return $this->config->get('config_ssl') . 'image/' . $new_image;
        } else {
            return $this->config->get('config_url') . 'image/' . $new_image;
        }
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
}
?>
