/**
* @version     1.7.0
* @module      WebRotate 360 Product Viewer for OpenCart
* @author      WebRotate 360 LLC
* @copyright   Copyright (C) 2018 WebRotate 360 LLC. All rights reserved.
* @license     GNU General Public License version 2 or later (http://www.gnu.org/copyleft/gpl.html).
*/

function WR360InitEmbededViewer(cfg) {

	var imageDiv = jQuery(cfg.pagePlaceholder);
	if (imageDiv.length != 1)
		return;

    imageDiv.click(function(e){
        e.stopPropagation();
        e.preventDefault();
    });

	if (cfg.viewerWidth.length > 0)
		imageDiv.css("width", cfg.viewerWidth);

	if (cfg.viewerHeight.length > 0)
		imageDiv.css("height", cfg.viewerHeight);
	
	imageDiv.css("padding", 0);
    imageDiv.html("<div id='wr360PlayerId'></div>");
    imageDiv.css("visibility", "visible");

    var rotator = WR360.ImageRotator.Create("wr360PlayerId");

    rotator.settings.graphicsPath         = cfg.graphicsPath;
    rotator.settings.configFileURL        = cfg.confFileURL;
    rotator.settings.rootPath             = cfg.rootPath;
    rotator.settings.responsiveBaseWidth  = parseInt(cfg.baseWidth);
    rotator.settings.responsiveMinHeight  = parseInt(cfg.minHeight);
    rotator.settings.googleEventTracking  = cfg.useAnalytics === "1";
    rotator.licenseFileURL                = cfg.licensePath;

    if (cfg.apiCallback.length > 0)
    {
        var fn = window[cfg.apiCallback];
        if (typeof (fn === "function"))
            rotator.settings.apiReadyCallback = fn;
    }

    rotator.runImageRotator();
}

function WR360InitGallery(skin) {
    jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({animation_speed:0, deeplinking:false, theme:skin});
}

function WR360InitPopupViewer(hrefParam, srcThumbPath, divID) {
    var popupElm = jQuery(divID);
    if (popupElm.length <= 0)
        return;

    var vqmodThumb = popupElm.find("a.thumbnail");
    if (vqmodThumb.length <= 0) {
        var newHtml = "<a href='" + hrefParam + "'" + "data-rel='prettyPhoto'><img class='wr360thumb' src='" + srcThumbPath + "'/></a>";
        popupElm.html(newHtml);
    }
    else {
        vqmodThumb.attr("href", hrefParam);
        var popupElmImg = vqmodThumb.find("img");
        if (popupElmImg.length > 0) {
            popupElmImg.attr("src", srcThumbPath);
            popupElmImg.addClass("wr360thumb");
        }
    }
}

