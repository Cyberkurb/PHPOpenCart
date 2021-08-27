<?php
/**
* @version     1.7.0
* @module      WebRotate 360 Product Viewer for OpenCart
* @author      WebRotate 360 LLC
* @copyright   Copyright (C) 2018 WebRotate 360 LLC. All rights reserved.
* @license     GNU General Public License version 2 or later (http://www.gnu.org/copyleft/gpl.html).
*/


// TODO: copy joomla css to svn first than here.

class WebRotate360HeaderHook
{
    private $_controller;
    private $_db;
    private $_scriptsPath;
    private $_headersArray = array();
    private $_popupGallery;
    private $_popupEnabled;
    private $_rootPath;
    private $_confFileURL;
    private $_viewEnabled;
    private $_masterConfigURL;
    private $_pagePlaceholder;
    private $_viewerWidth;
    private $_viewerHeight;
    private $_licensePath;
    private $_baseWidth;
    private $_minHeight;
    private $_useAnalytics;
    private $_apiCallback;
    private $_graphicsPath;
    private $_viewerSkin;
    private $_viewerInPopup;

    public function __construct($controller, $db)
    {
        $this->_controller = $controller;
        $this->_db = $db;

        $catalogPath = "catalog";
        if (defined("DIR_APPLICATION"))
            $catalogPath = preg_replace('/.*\/([^\/].*)\//is', '$1', DIR_APPLICATION);

        $this->_scriptsPath  = $catalogPath . "/view/javascript/webrotate360";
        $this->_rootPath     = "";
        $this->_confFileURL  = "";
        $this->_viewEnabled  = false;
    }

    public function isCanProceed()
    {
        if (defined("webrotate360_headers"))
            return (false);

        if (!isset($this->_controller->request->get["route"]))
            return (false);

        if (!isset($this->_controller->request->get["product_id"]))
            return (false);

        $moduleStatus = $this->_controller->config->get("module_webrotate360_status");
        if (empty($moduleStatus))
            return (false);

        return (true);
    }

    public function initProductRecord()
    {
        try
        {
            // $prodId = $GLOBALS["_REQUEST"]["product_id"];
            $prodId = $this->_controller->request->get["product_id"];
            $tableName = DB_PREFIX . "wr360product";
            $query = $this->_db->query("SELECT * FROM `$tableName` WHERE product_id = '" . $prodId . "'");

            foreach ($query->rows as $result)
            {
                $this->_rootPath    = $result["root_path"];
                $this->_confFileURL = $result["config_file_url"];
                $this->_viewEnabled = $result["wr360_enabled"];
                break;
            }
        }
        catch (Exception $exp)
        {
            return (false);
        }

        return (true);
    }

    public function initConfigSettings()
    {
        try
        {
            $this->_masterConfigURL = $this->_controller->config->get("module_webrotate360_configFileURL");
            $this->_pagePlaceholder = $this->_controller->config->get("module_webrotate360_divID");
            $this->_viewerWidth     = $this->_controller->config->get("module_webrotate360_viewerWidth");
            $this->_viewerHeight    = $this->_controller->config->get("module_webrotate360_viewerHeight");
            $this->_minHeight       = $this->_controller->config->get("module_webrotate360_minHeight");
            $this->_licensePath     = $this->_controller->config->get("module_webrotate360_licensePath");
            $this->_baseWidth       = $this->_controller->config->get("module_webrotate360_baseWidth");
            $this->_viewerSkin      = $this->_controller->config->get("module_webrotate360_skinCSS");
            $this->_viewerInPopup   = $this->_controller->config->get("module_webrotate360_viewerInPopup");
            $this->_graphicsPath    = $this->_scriptsPath . "/imagerotator/html/img/" . $this->_viewerSkin;
            $this->_popupGallery    = $this->_controller->config->get("module_webrotate360_prettyPhotoSkin");
            $this->_popupEnabled    = ($this->_popupGallery !== "disabled");
            $this->_useAnalytics    = $this->_controller->config->get("module_webrotate360_useAnalytics");
            $this->_apiCallback     = $this->_controller->config->get("module_webrotate360_apiCallback");

            if (!empty($this->_viewerWidth))
            {
                $this->_viewerWidth = str_ireplace("px", "", $this->_viewerWidth);
                if ((!$this->_viewerInPopup) && (strpos($this->_viewerWidth, "%") === FALSE))
                    $this->_viewerWidth .= "px";
            }

            if (!empty($this->_viewerHeight))
            {
                $this->_viewerHeight = str_ireplace("px", "", $this->_viewerHeight);
                if ((!$this->_viewerInPopup) && (strpos($this->_viewerHeight, "%") === FALSE))
                    $this->_viewerHeight .= "px";
            }

            if (!empty($this->_baseWidth))
                $this->_baseWidth = preg_replace("/[^0-9]/", "", $this->_baseWidth);

            if (empty($this->_baseWidth))
                $this->_baseWidth = 0;

            if (!empty($this->_minHeight))
                $this->_minHeight = preg_replace("/[^0-9]/", "", $this->_minHeight);

            if (empty($this->_minHeight))
                $this->_minHeight = 0;

            if (empty($this->_licensePath))
                $this->_licensePath = ($this->_viewerInPopup) ? "imagerotator/html/license.lic" : $this->_scriptsPath . "/imagerotator/html/license.lic";

            if (empty($this->_confFileURL))
                $this->_confFileURL = $this->_masterConfigURL;

            if (empty($this->_viewerSkin))
                $this->_viewerSkin = "basic.css";

            //if ($this->_popupGallery === "default")
            //    $this->_popupGallery = "pp_default";
        }
        catch (Exception $exp)
        {
            return (false);
        }

        return (true);
    }

    public function parseHeadersAndReturnOutput($output)
    {
        if (empty($this->_headersArray))
            return (null);

        $headers = "\r\n" . implode("\r\n", $this->_headersArray) . "\r\n";
        if (strpos($output, "</head>"))
        {
            $output = preg_replace('/\<\/head\>/is', $headers . "</head>", $output, 1, $replacedCount);
            if ($replacedCount == 0)
                return (null);
        }
        else
            $output = $output . $headers;

        return ($output);
    }

    public function processHeaders()
    {
        try
        {
            if (($this->_popupEnabled == false) && ($this->_viewEnabled == false))
                return (false);

            $this->_headersArray[] = '<script type="text/javascript" src="' . $this->_scriptsPath . '/wr360hook.js"></script>';
            $this->_headersArray[] = '<link type="text/css" href="' . $this->_scriptsPath . '/wr360overrides.css" rel="stylesheet"/>';

            if ($this->_popupEnabled == true)
            {
                $this->_headersArray[] = '<link type="text/css" href="' . $this->_scriptsPath . '/prettyphoto/css/prettyphoto.css" rel="stylesheet"/>';
                $this->_headersArray[] = '<script type="text/javascript" src="' . $this->_scriptsPath . '/prettyphoto/js/jquery.prettyPhoto.js"></script>';
                $this->_headersArray[] = '<script type="text/javascript">jQuery(document).ready(function(){WR360InitGallery("'. $this->_popupGallery .'");});</script>';

                define("webrotate360_popupgallery", true);
            }

            if ($this->_viewEnabled == false)
                return (true);

            $this->_headersArray[] = '<link type="text/css" href="' . $this->_scriptsPath . '/imagerotator/html/css/' . $this->_viewerSkin . '.css" rel="stylesheet"/>';
            $this->_headersArray[] = '<script type="text/javascript" src="' . $this->_scriptsPath . '/imagerotator/html/js/imagerotator.js"></script>';

            if ($this->_viewerInPopup)
            {
                define("webrotate360_viewer_popup", true);

                $popupRefFmt = '%s?config=%s&root=%s&height=%s&lic=%s&grphpath=%s&analyt=%s&iframe=true&width=%s&height=%s';

                $popupRefHrf = sprintf(
                    $popupRefFmt,
                    $this->_scriptsPath . "/viewloader_" .$this->_viewerSkin . ".html",
                    urlencode($this->_confFileURL),
                    urlencode($this->_rootPath),
                    $this->_viewerHeight,
                    urlencode($this->_licensePath),
                    urlencode("imagerotator/html/img/" . $this->_viewerSkin),
                    $this->_useAnalytics,
                    str_ireplace("%", "", $this->_viewerWidth),
                    $this->_viewerHeight);

                $srcThumbPath = $this->_scriptsPath . "/360thumb.svg";

                $this->_headersArray[] = <<<HTML
                <script type="text/javascript">
                jQuery(document).ready(
                    function(){
                        WR360InitPopupViewer("$popupRefHrf", "$srcThumbPath", "$this->_pagePlaceholder");
                        WR360InitGallery("$this->_popupGallery");
                    });
                </script>
HTML;
            }
            else
            {
                define("webrotate360_viewer_embed", true);

                $this->_headersArray[] = <<<HTML
                    <style type="text/css">
                        $this->_pagePlaceholder{visibility: hidden;}
                    </style>
                    <script type="text/javascript">
                    jQuery(document).ready(
                        function(){
                            WR360InitEmbededViewer( {
                                licensePath: "$this->_licensePath",
                                graphicsPath: "$this->_graphicsPath",
                                confFileURL: "$this->_confFileURL",
                                pagePlaceholder: "$this->_pagePlaceholder",
                                viewerWidth: "$this->_viewerWidth",
                                viewerHeight: "$this->_viewerHeight",
                                rootPath: "$this->_rootPath",
                                baseWidth: "$this->_baseWidth",
                                minHeight: "$this->_minHeight",
                                useAnalytics: "$this->_useAnalytics",
                                apiCallback: "$this->_apiCallback"
                            });
                        });
                    </script>
HTML;
            }
        }
        catch (Exception $exp)
        {
            return (false);
        }

        return (true);
    }
}

function addWR360Headers($controller, $output, $db)
{
    $hook = new WebRotate360HeaderHook($controller, $db);
    $parsedOutput = null;

    if ($hook->isCanProceed() &&
        $hook->initProductRecord() &&
        $hook->initConfigSettings() &&
        $hook->processHeaders())
    {
        $parsedOutput = $hook->parseHeadersAndReturnOutput($output);
        if ($parsedOutput)
            $output = $parsedOutput;
    }

    define("webrotate360_headers", $parsedOutput !== null);

    if (!defined("webrotate360_popupgallery"))
        define("webrotate360_popupgallery", false);
    if (!defined("webrotate360_viewer_embed"))
        define("webrotate360_viewer_embed", false);
    if (!defined("webrotate360_viewer_popup"))
        define("webrotate360_viewer_popup", false);

    return ($output);
}

