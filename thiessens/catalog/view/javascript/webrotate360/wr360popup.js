/**
* @version     1.7.0
* @module      WebRotate 360 Product Viewer for OpenCart
* @author      WebRotate 360 LLC
* @copyright   Copyright (C) 2018 WebRotate 360 LLC. All rights reserved.
* @license     GNU General Public License version 2 or later (http://www.gnu.org/copyleft/gpl.html).
*/

function wr360QueryGetParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

jQuery(document).ready(function() {
    var popup360Elm = jQuery("#wr360PlayerId20");
    if (popup360Elm.length == 1) {
        popup360Elm.rotator( {
            licenseFileURL: wr360QueryGetParameterByName("lic"),
            graphicsPath: wr360QueryGetParameterByName("grphpath"),
            configFileURL: wr360QueryGetParameterByName("config"),
            rootPath: wr360QueryGetParameterByName("root"),
            googleEventTracking: wr360QueryGetParameterByName("analyt") === "1"
        });
    }
});


