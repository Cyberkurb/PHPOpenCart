/*
 *   Template Name: Pension HTML version *   
 *   Author:  WowThemesNet
 *   Website: www.wowthemes.net
*/

(function($) {
    "use strict";
   
    
       
   $(window).load(function() {
                
       /*==========================================
          PRELOADER & PAGE TRANSITIONS (requires: functions.js)
       =====================================================*/
   
           // active navigation
           $("nav").addClass('activ');
           
           // delete preloader
           $(".preloader").delay("500").fadeOut(500);
            
           // page transitions
           var myTimer = setTimeout(function () {
   
                   PageTransitions.init({
                       pages: $('.page-wrapper'),
                       menu: 'ul.dl-menu',
                       animcursor: 55, // 1 - 60
                       nextAnimcursor: true  // true, false
                   });
                   clearTimeout(myTimer);
   
           }, 1500); 
   
    
   
       
              
   }); // end window load  
   
   
   $(document).ready(function() {
       
       
       /*==========================================
          ADD NEW STYLE "max-height" (requires: functions.js)
       =====================================================*/
       
       addMenuHeight();
       
       
       /*==========================================
          DL-MENU (requires: functions.js)
       =====================================================*/  
       
       $('#dl-menu').dlmenu();
   
   
      
   
   }); // end document ready
   
   
   $(window).resize(function() {
       
           
       /*==========================================
          ADD NEW STYLE "max-height" (requires: functions.js)
       =====================================================*/
       addMenuHeight();
       
       
       
   }); // end window resize
   
       
   })(jQuery);