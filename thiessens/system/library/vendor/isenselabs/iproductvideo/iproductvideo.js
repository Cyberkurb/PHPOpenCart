(function() {
  $(window).load(function() {
    if (typeof $.magnificPopup !== "undefined") {
      
      // Default OpenCart 3.0 Theme (magnificPopup video support)
      if ($('.thumbnails').length > 0 && typeof $('.thumbnails').data().magnificPopup !== "undefined") {
        var ipvSetting = $('.thumbnails').data('ipvsetting');

        // Replace youtube url if nocookie enabled
        if (ipvSetting && ipvSetting.nocookie) {
          $('.thumbnails').data().magnificPopup.iframe = {
            markup: '<div class="mfp-iframe-scaler">'+
                  '<div class="mfp-close"></div>'+
                  '<iframe class="mfp-iframe" frameborder="0" allowfullscreen allow="autoplay"></iframe>'+
                  '<div class="mfp-title"></div>'+
                  '<div class="mfp-counter"></div>'+
                '</div>',
            patterns: {
              youtube: {
                index: 'youtube.com/',
                id: 'v=',
                src: '//www.youtube-nocookie.com/embed/%id%?autoplay=1&rel=0&mute=1'
              }
            }
          }
        }

        // Extend callbacks per item
        $('.thumbnails').data().magnificPopup.callbacks = {
          elementParse: function(item) {

            // Internet
            if (item.src.indexOf('#iproductvideo') >= 0) {
              item.type = 'iframe';

              // Remove hashtag to start the autoplay
              if (ipvSetting && ipvSetting.autoplay) {
                item.src = item.src.split('#')[0];
              }
            }

            // Uploaded
            if (item.src.indexOf('#iproductvideo_local') >= 0) {
              item.type = 'inline';

              var rgx = /(?:\.([^.]+))?$/;
              var ext = rgx.exec(item.src.replace("#iproductvideo_local", ""))[1];
              var autoplay = ipvSetting && ipvSetting.autoplay ? 'autoplay muted' : '';

              var video_html =  '<div class="iproductvideo-popup">';
              video_html +=   '<button title="Close (Esc)" type="button" class="mfp-close">×</button>';
              video_html +=     '<div class="iproductvideo-wrap"><video class="iproductvideo-video" controls ' + autoplay + ' preload="auto">';
              video_html +=   '<source src="' + item.src + '" type="video/' + ext + '">';
              video_html +=   'Your browser does not support the HTML5 video tag.';
              video_html +=     '</video></div>';
              video_html +=   '</div>'

              item.src = video_html;
            }
          }
        }
      }
    }
  });

  // Journal
  $(document).ready(function () {
    if(typeof Journal == 'object') { 
      // Responsible to prepare local video for popup
      var ipvSetting = $('#product-gallery').data('ipvsetting');
      if (!ipvSetting) {
        ipvSetting = $('.container').data('ipvsetting')
      }

      var autoplay = ipvSetting && ipvSetting.autoplay ? 'autoplay muted' : '';
      var i = 0;
      $('.image-gallery a[href$="iproductvideo_local"], [data-ipvitem][data-gallery] img[data-largeimg$="iproductvideo_local"]').each(function() {
        i++;
        $(this).attr('data-html', '#iproductvideo_local' + i);

        var href = $(this).attr('href');
        var rgx = /(?:\.([^.]+))?$/;
        var ext = href ? rgx.exec(href.replace("#iproductvideo_local", ""))[1] : '';

        // Journal3 - change saved galleries
        if (href == undefined) { // Journal3
          var href = $(this).attr('data-largeimg'),
              ext  = rgx.exec(href.replace("#iproductvideo_local", ""))[1];
          var elParent = $(this).closest('.swiper-slide'),
              index    = $(elParent).data('index'),
              gallery  = $(elParent.data('gallery')).data('images');

          if (gallery) {
            delete gallery[index]['src'];
            gallery[index]['poster'] = $(this).attr('src');
            gallery[index]['html'] = '#iproductvideo_local' + i;
          }
        }

        $(this).removeAttr('href');

        var video_html =  '<div style="display:none;" id="iproductvideo_local' + i + '">';
            video_html +=   '<video class="lg-video-object lg-html5" controls ' + autoplay + ' preload="none">';
            video_html +=     '<source src="' + href + '" type="video/' + ext + '" autostart="false">';
            video_html +=     'Your browser does not support the HTML5 video tag.';
            video_html +=   '</video>';
            video_html += '</div>';

        $('body').append(video_html);
        if ($.fn.lightGallery == undefined) { 
          $(this).remove();
        }
      });
    }
  });
  $(window).load(function() {
    if(typeof Journal == 'object') { 
      var ipvSetting = $('#product-gallery').data('ipvsetting');

      if (!ipvSetting) {
        ipvSetting = $('.container').data('ipvsetting')
      }

      // Click thumbnail show video at main image and play
      if (ipvSetting && ipvSetting.autoplay) {

        var mainImage  = $('#image').parent();
            mainImage.after('<div class="iproductvideo-wrapper" style="display:none"></div>');
        
        $('.product-info .image-additional a, .additional-image').on('click', function(e) {
          e.preventDefault();

          var ipvData   = $(this).data('ipvitem'),
              ipvSrc    = $(this).attr('href'),
              ipvTarget = $('.iproductvideo-wrapper'),
              videoHtml = '';

              if (ipvData && ipvData.ipv == 'true') {
                mainImage.hide();

                if (ipvData.source == 'local') {
                  videoHtml += '<video class="lg-video-object lg-html5" controls autoplay muted preload="auto"><source src="' + ipvSrc + '" type="video/' + ipvData.ext + '"><i>Your browser does not support the HTML5 video tag.</i></video>';
                } else if (ipvData.source == 'youtube') {
                  videoHtml += '<iframe src="//www.youtube.com/embed/' + ipvData.id + '?rel=0&autoplay=1&mute=1" frameborder="0" allowfullscreen allow="autoplay"></iframe>';
                } else if (ipvData.source == 'vimeo') {
                  videoHtml += '<iframe src="https://player.vimeo.com/video/' + ipvData.id + '?autoplay=1&muted=1" frameborder="0" allowfullscreen allow="autoplay"></iframe>';
                }

                ipvTarget.html(videoHtml).show();

              } else {
                ipvTarget.html('').hide();
                mainImage.show();
              }

              // Journal3 - replace main image with video
              var ipvJ3i = $(this).data('index');
              if ($('.main-image [data-index="' + ipvJ3i + '"]').length > 0) {
                ipvContainer = $('.main-image [data-index="' + ipvJ3i + '"]');
                ipvContainer.find('.iproductvideo-wrapper').remove();

                if (ipvData && ipvData.ipv == 'true') {
                  if (ipvData.source == 'local') {
                    ipvSrc = ipvContainer.find('img').data('largeimg');
                    videoHtml = '<video class="lg-video-object lg-html5" controls autoplay muted preload="auto"><source src="' + ipvSrc + '" type="video/' + ipvData.ext + '"><i>Your browser does not support the HTML5 video tag.</i></video>';
                  }
                  ipvContainer.prepend('<div class="iproductvideo-wrapper ipv-journal3">' + videoHtml + '</div>')
                }
              }
              // End::Journal3
        });

        setTimeout(function() {
          $('.product-info .image-additional a:first-child').trigger('click');
        }, 10);
      
      // When autoplay disabled
      } else {

        // Click on the thumbnail update main image with video image
        $('.product-info .image-additional a[href$="iproductvideo"], .product-info .image-additional a[href$="iproductvideo_local"]').on('click', function(e) {
          e.preventDefault();
          var $a = $('.product-info .image-additional a');

          var $image_gallery = $('.product-info .image-gallery a.swipebox').eq($a.index($(this)));
          var thumb;
          var image;

          /* If LightGallery is available */
          if ($(this).parents('.swiper-container').length > 0) {
            if ($image_gallery.attr('data-original') != undefined) {
              thumb = image = $image_gallery.attr('data-original');
            } else {
              thumb = image = $(this).find('img').attr('src');
            }
            
            Journal.changeProductImage(thumb, image, $a.index($(this)));
          } else {
            if ($image_gallery.find('img').attr('src') != undefined && $.fn.lightGallery != undefined) { 
              thumb = image = $image_gallery.find('img').attr('src');
            } else {
              thumb = image = $(this).find('img').attr('src');
            }

            var $image = $('#image');
            var video_href = $(this).attr('href');

            Journal.changeProductImage(thumb, image);
            $image.parent().attr('href', video_href);
          }
        });

        // Click main image open video popup
        $('.zm-viewer').on('click', function () {
          var $image = $('#image');
          var href = $image.parent().attr('href');

          if (href.indexOf('#iproductvideo_local') >= 0 && $.fn.lightGallery == undefined) { 
            $.magnificPopup.open({
              items: {
                src: href
              },
              type: 'iframe'
            });
          } else if (href.indexOf('iproductvideo') > -1){
            $('.product-info .image-gallery a.swipebox[href="' + $image.parent().attr('href') + '"]').first().click();
          }
        }); 
      }

      /* Adding lg-video plugin required for lightGallery */
      if ($.fn.lightGallery != undefined) {
        /*! lg-video - v1.2.2 - 2018-05-01 */
        !function(a,b){"function"==typeof define&&define.amd?define(["jquery"],function(a){return b(a)}):"object"==typeof module&&module.exports?module.exports=b(require("jquery")):b(a.jQuery)}(this,function(a){!function(){"use strict";function b(a,b,c,d){var e=this;if(e.core.$slide.eq(b).find(".lg-video").append(e.loadVideo(c,"lg-object",!0,b,d)),d)if(e.core.s.videojs)try{videojs(e.core.$slide.eq(b).find(".lg-html5").get(0),e.core.s.videojsOptions,function(){!e.videoLoaded&&e.core.s.autoplayFirstVideo&&this.play()})}catch(a){console.error("Make sure you have included videojs")}else!e.videoLoaded&&e.core.s.autoplayFirstVideo&&e.core.$slide.eq(b).find(".lg-html5").get(0).play()}function c(a,b){var c=this.core.$slide.eq(b).find(".lg-video-cont");c.hasClass("lg-has-iframe")||(c.css("max-width",this.core.s.videoMaxWidth),this.videoLoaded=!0)}function d(b,c,d){var e=this,f=e.core.$slide.eq(c),g=f.find(".lg-youtube").get(0),h=f.find(".lg-vimeo").get(0),i=f.find(".lg-dailymotion").get(0),j=f.find(".lg-vk").get(0),k=f.find(".lg-html5").get(0);if(g)g.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}',"*");else if(h)try{$f(h).api("pause")}catch(a){console.error("Make sure you have included froogaloop2 js")}else if(i)i.contentWindow.postMessage("pause","*");else if(k)if(e.core.s.videojs)try{videojs(k).pause()}catch(a){console.error("Make sure you have included videojs")}else k.pause();j&&a(j).attr("src",a(j).attr("src").replace("&autoplay","&noplay"));var l;l=e.core.s.dynamic?e.core.s.dynamicEl[d].src:e.core.$items.eq(d).attr("href")||e.core.$items.eq(d).attr("data-src");var m=e.core.isVideo(l,d)||{};(m.youtube||m.vimeo||m.dailymotion||m.vk)&&e.core.$outer.addClass("lg-hide-download")}var e={videoMaxWidth:"855px",autoplayFirstVideo:!0,youtubePlayerParams:!1,vimeoPlayerParams:!1,dailymotionPlayerParams:!1,vkPlayerParams:!1,videojs:!1,videojsOptions:{}},f=function(b){return this.core=a(b).data("lightGallery"),this.$el=a(b),this.core.s=a.extend({},e,this.core.s),this.videoLoaded=!1,this.init(),this};f.prototype.init=function(){var e=this;e.core.$el.on("hasVideo.lg.tm",b.bind(this)),e.core.$el.on("onAferAppendSlide.lg.tm",c.bind(this)),e.core.doCss()&&e.core.$items.length>1&&(e.core.s.enableSwipe||e.core.s.enableDrag)?e.core.$el.on("onSlideClick.lg.tm",function(){var a=e.core.$slide.eq(e.core.index);e.loadVideoOnclick(a)}):e.core.$slide.on("click.lg",function(){e.loadVideoOnclick(a(this))}),e.core.$el.on("onBeforeSlide.lg.tm",d.bind(this)),e.core.$el.on("onAfterSlide.lg.tm",function(a,b){e.core.$slide.eq(b).removeClass("lg-video-playing")}),e.core.s.autoplayFirstVideo&&e.core.$el.on("onAferAppendSlide.lg.tm",function(a,b){if(!e.core.lGalleryOn){var c=e.core.$slide.eq(b);setTimeout(function(){e.loadVideoOnclick(c)},100)}})},f.prototype.loadVideo=function(b,c,d,e,f){var g="",h=1,i="",j=this.core.isVideo(b,e)||{};if(d&&(h=this.videoLoaded?0:this.core.s.autoplayFirstVideo?1:0),j.youtube)i="?wmode=opaque&autoplay="+h+"&enablejsapi=1",this.core.s.youtubePlayerParams&&(i=i+"&"+a.param(this.core.s.youtubePlayerParams)),g='<iframe class="lg-video-object lg-youtube '+c+'" width="560" height="315" src="//www.youtube.com/embed/'+j.youtube[1]+i+'" frameborder="0" allowfullscreen></iframe>';else if(j.vimeo)i="?autoplay="+h+"&api=1",this.core.s.vimeoPlayerParams&&(i=i+"&"+a.param(this.core.s.vimeoPlayerParams)),g='<iframe class="lg-video-object lg-vimeo '+c+'" width="560" height="315"  src="//player.vimeo.com/video/'+j.vimeo[1]+i+'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';else if(j.dailymotion)i="?wmode=opaque&autoplay="+h+"&api=postMessage",this.core.s.dailymotionPlayerParams&&(i=i+"&"+a.param(this.core.s.dailymotionPlayerParams)),g='<iframe class="lg-video-object lg-dailymotion '+c+'" width="560" height="315" src="//www.dailymotion.com/embed/video/'+j.dailymotion[1]+i+'" frameborder="0" allowfullscreen></iframe>';else if(j.html5){var k=f.substring(0,1);"."!==k&&"#"!==k||(f=a(f).html()),g=f}else j.vk&&(i="&autoplay="+h,this.core.s.vkPlayerParams&&(i=i+"&"+a.param(this.core.s.vkPlayerParams)),g='<iframe class="lg-video-object lg-vk '+c+'" width="560" height="315" src="//vk.com/video_ext.php?'+j.vk[1]+i+'" frameborder="0" allowfullscreen></iframe>');return g},f.prototype.loadVideoOnclick=function(a){var b=this;if(a.find(".lg-object").hasClass("lg-has-poster")&&a.find(".lg-object").is(":visible"))if(a.hasClass("lg-has-video")){var c=a.find(".lg-youtube").get(0),d=a.find(".lg-vimeo").get(0),e=a.find(".lg-dailymotion").get(0),f=a.find(".lg-html5").get(0);if(c)c.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}',"*");else if(d)try{$f(d).api("play")}catch(a){console.error("Make sure you have included froogaloop2 js")}else if(e)e.contentWindow.postMessage("play","*");else if(f)if(b.core.s.videojs)try{videojs(f).play()}catch(a){console.error("Make sure you have included videojs")}else f.play();a.addClass("lg-video-playing")}else{a.addClass("lg-video-playing lg-has-video");var g,h,i=function(c,d){if(a.find(".lg-video").append(b.loadVideo(c,"",!1,b.core.index,d)),d)if(b.core.s.videojs)try{videojs(b.core.$slide.eq(b.core.index).find(".lg-html5").get(0),b.core.s.videojsOptions,function(){this.play()})}catch(a){console.error("Make sure you have included videojs")}else b.core.$slide.eq(b.core.index).find(".lg-html5").get(0).play()};b.core.s.dynamic?(g=b.core.s.dynamicEl[b.core.index].src,h=b.core.s.dynamicEl[b.core.index].html,i(g,h)):(g=b.core.$items.eq(b.core.index).attr("href")||b.core.$items.eq(b.core.index).attr("data-src"),h=b.core.$items.eq(b.core.index).attr("data-html"),i(g,h));var j=a.find(".lg-object");a.find(".lg-video").append(j),a.find(".lg-video-object").hasClass("lg-html5")||(a.removeClass("lg-complete"),a.find(".lg-video-object").on("load.lg error.lg",function(){a.addClass("lg-complete")}))}},f.prototype.destroy=function(){this.videoLoaded=!1},a.fn.lightGallery.modules.video=f}()});
      } else {
        /* Include the MagnificPopup for the local videos if the lightGallery does not exist */
        if ($('a[href$="iproductvideo_local"]').size()) {
          if (!$.magnificPopup) {
            $('head').append('<script src="catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js"></script>');
            $('head').append('<link href="catalog/view/javascript/jquery/magnific/jquery.magnific-popup.css" type="text/css rel="stylesheet" />');
          }
        }
      }
    }
  });
})();

// Owl Carousel & Magnific popup support
$(window).load(function() {
  if ($.magnificPopup != undefined) {
    if($('.popup-gallery').length >= 0 && $('.popup-gallery').find('.owl-carousel').length >= 0) {
      $('.popup-gallery').magnificPopup({
          delegate: 'a',
          type: 'image',
          tLoading: 'Loading image #%curr%...',
          mainClass: 'mfp-with-zoom',
          gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
          },
          image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
          },
          callbacks: {
            elementParse: function(item) {
             if(item.el.context.href.indexOf('iproductvideo') > -1) {
               item.type = 'iframe';
             } else {
               item.type = 'image';
             }
            }
          },
      });
    }
  }
});
