/*
 * ----------------------------------------------------------
 * FUNCTIONS - Add Menu Height
 * ----------------------------------------------------------
 */
function addMenuHeight() {

    var nav = jQuery('.menu-box');
    var menuHeight =  jQuery('nav').height() - ( jQuery('nav .logo-box').height() + jQuery('nav footer.footer').height() );
    nav.css({'max-height': menuHeight +'px'});

    return false;
}


/*
 * ----------------------------------------------------------
 * FUNCTIONS - Menu
 * ----------------------------------------------------------
 */
;( function( $, window, undefined ) {

	'use strict';

	// global
	var Modernizr = window.Modernizr, $body = $( 'body' );

	$.DLMenu = function( options, element ) {
		this.$el = $( element );
		this._init( options );
	};

	// the options
	$.DLMenu.defaults = {
		animationClasses : { classin : 'dl-animate-in-2', classout : 'dl-animate-out-2' },
		onLevelClick : function( el, name ) { return false; },
		onLinkClick : function( el, ev ) { return false; }
	};

	$.DLMenu.prototype = {
		_init : function( options ) {

			// options
			this.options = $.extend( true, {}, $.DLMenu.defaults, options );
			// cache some elements and initialize some variables
			this._config();

			var animEndEventNames = {
					'WebkitAnimation' : 'webkitAnimationEnd',
					'OAnimation' : 'oAnimationEnd',
					'msAnimation' : 'MSAnimationEnd',
					'animation' : 'animationend'
			},
			transEndEventNames = {
					'WebkitTransition' : 'webkitTransitionEnd',
					'MozTransition' : 'transitionend',
					'OTransition' : 'oTransitionEnd',
					'msTransition' : 'MSTransitionEnd',
					'transition' : 'transitionend'
			};

			this.animEndEventName = animEndEventNames[ Modernizr.prefixed( 'animation' ) ] + '.dlmenu';
			this.transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ] + '.dlmenu',
			this.supportAnimations = Modernizr.cssanimations,
			this.supportTransitions = Modernizr.csstransitions;

			this._initEvents();

		},

		_config : function() {

			this.open = true;
                        this.$menuScroll = $( '.menu-box' );
                        this.$buttonMobile = $( '.menu-button-mobile' );
			this.$menu = this.$el.children( 'ul.dl-menu' );
			this.$menuitems = this.$menu.find( 'li:not(.dl-back)' );
			this.$el.find( 'ul.sub-menu' ).prepend( '<li class="dl-back"><a href="#"></a></li>' );
			this.$back = this.$menu.find( 'li.dl-back' );
                        this.$menuli = this.$menu.find( 'li' );
                        this.$menuli.each(function () {
                            var elem = $(this),
                            name = elem.find( 'a' )['0'];
                            if ( name ) {
                                elem.find( 'li.dl-back' ).find( 'a' ).html('<i class="fa fa-fw fa-long-arrow-left"></i> '+name.innerHTML);
                            } else {
                                elem.find( 'li.dl-back' ).find( 'a' ).html('<i class="fa fa-fw fa-long-arrow-left"></i>');
                            }
                        });

		},

		_initEvents : function() {

			var self = this;

                        this.$buttonMobile.on( 'click.dlmenu', function() {
                                if( $(this).hasClass('close') ) {
			            self.$menuScroll.removeClass('dl-show');
                                    self.$buttonMobile.removeClass('close');
				} else {
			            self.$menuScroll.addClass('dl-show');
                                    self.$buttonMobile.addClass('close');
				}
				return false;
                        });


                        $(document).on( 'click', 'li.submenu > a', function( event ) {

                                event.stopPropagation();

                                var elem = $(this),
                                    elem2 = elem['0'],
				    $item = $(elem2.parentNode),
				    $submenu = $item.children( 'ul.sub-menu' );

				if( $submenu.length > 0 ) {

                                    self.$menuScroll.addClass('scroll-none');

					var $flyin = $submenu.clone().css( 'opacity', 0 ).insertAfter( self.$menu ),
						onAnimationEndFn = function() {
							self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.classout ).addClass( 'dl-subview' );
							$item.addClass( 'dl-subviewopen' ).parents( '.dl-subviewopen:first' ).removeClass( 'dl-subviewopen' ).addClass( 'dl-subview' );
							$flyin.remove();
						};

					setTimeout( function() {
						$flyin.addClass( self.options.animationClasses.classin );
						self.$menu.addClass( self.options.animationClasses.classout );
						if( self.supportAnimations ) {
							self.$menu.on( self.animEndEventName, onAnimationEndFn );
						}
						else {
							onAnimationEndFn.call();
						}

						self.options.onLevelClick( $item, $item.children( 'a:first' ).text() );

					} );

                                        setTimeout( function() {
                                              self.$menuScroll.removeClass('scroll-none');

					},1000 );


					return false;

				}
				else {
                                        self.$el.removeClass('dl-show');
					self.options.onLinkClick( $item, event );
				}

			} );


			this.$back.on( 'click.dlmenu', function( event ) {

                                self.$menuScroll.addClass('scroll-none');

				var $this = $( this ),
					$submenu = $this.parents( 'ul.sub-menu:first' ),
					$item = $submenu.parent(),

					$flyin = $submenu.clone().insertAfter( self.$menu );

				var onAnimationEndFn = function() {
					self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.classin );
					$flyin.remove();
				};

				setTimeout( function() {
					$flyin.addClass( self.options.animationClasses.classout );
					self.$menu.addClass( self.options.animationClasses.classin );
					if( self.supportAnimations ) {
						self.$menu.on( self.animEndEventName, onAnimationEndFn );
					}
					else {
						onAnimationEndFn.call();
					}

					$item.removeClass( 'dl-subviewopen' );

					var $subview = $this.parents( '.dl-subview:first' );
					if( $subview.is( 'li' ) ) {
						$subview.addClass( 'dl-subviewopen' );
					}
					$subview.removeClass( 'dl-subview' );

				} );

                                setTimeout(function () {
                                    self.$menuScroll.removeClass('scroll-none');

                                }, 1000);

				return false;

			} );

		},

		// resets the menu to its original state (first level of options)
		_resetMenu : function() {
			this.$menu.removeClass( 'dl-subview' );
			this.$menuitems.removeClass( 'dl-subview dl-subviewopen' );
		}
	};

	var logError = function( message ) {
		if ( window.console ) {
			window.console.error( message );
		}
	};

	$.fn.dlmenu = function( options ) {
		if ( typeof options === 'string' ) {
			var args = Array.prototype.slice.call( arguments, 1 );
			this.each(function() {
				var instance = $.data( this, 'dlmenu' );
				if ( !instance ) {
					logError( "cannot call methods on dlmenu prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				}
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
					logError( "no such method '" + options + "' for dlmenu instance" );
					return;
				}
				instance[ options ].apply( instance, args );
			});
		}
		else {
			this.each(function() {
				var instance = $.data( this, 'dlmenu' );
				if ( instance ) {
					instance._init();
				}
				else {
					instance = $.data( this, 'dlmenu', new $.DLMenu( options, this ) );
				}
			});
		}
		return this;
	};

} )( jQuery, window );


/*
 * ----------------------------------------------------------
 * FUNCTIONS - Page Transitions
 * ----------------------------------------------------------
 */
PageTransitions = (function() {

                    var isAnimating = false,
                        endCurrPage = true,
                        endNextPage = false,
                        animEndEventNames = {
                            'WebkitAnimation': 'webkitAnimationEnd',
                            'OAnimation': 'oAnimationEnd',
                            'msAnimation': 'MSAnimationEnd',
                            'animation': 'animationend'
                        },
                        animEndEventName = animEndEventNames[ Modernizr.prefixed('animation') ],
                        support = Modernizr.cssanimations;


        function init(options) {

                        $main = options.pages,
                        menu = options.menu,
                        animcursor = options.animcursor,
                        nextAnimcursor = options.nextAnimcursor,
                        pageStart = getPageActiv(),
                        pageActiv = '',

                        $main.append('<section id="page-ajax-prev"></section>');

                        $pages = $main.children('section'),
                        $pages.each(function () {
                            var $page = jQuery(this);
                            if ($page.attr('class')) {
                                pageClass = $page.attr('class');
                            }
                            else {
                                pageClass = "";
                            }
                            $page.data('originalClassList', pageClass);
                        });

                      

        }




        function mobileMenuClose() {

                var timerDelay = 0;

                if( jQuery('.menu-box').hasClass('dl-show') ) {

                    jQuery('.menu-box').removeClass('dl-show');
                    jQuery('.menu-button-mobile').removeClass('close');
                    timerDelay = 700;
                }

                return timerDelay;
        }


        function startLazy() {

            var myTimer = setTimeout(function () {

                jQuery("section.section-current img.lazy").lazy(
                {
                        bind: "event",
                        effect: "fadeIn",
                        effectTime: 2000,
                        appendScroll: jQuery("section.section-current .content"),
                        //fallbackWidth   : 2000,
                        //fallbackHeight  : 2000,
                        delay:-1
                });
                clearTimeout(myTimer);

            }, 200);
        }


        function ajaxLoadPage(dane) {

                $('.page-ajax-preloader').addClass('activ');
                var page_ajaxId = '#page-ajax-prev';
                var myanimcursor = getAnimcursor(dane);


                var $this = dane,
                $remote = $this.data('remote') || $this.attr('href');

                jQuery.ajax({
               url:$remote,
              async: false,
             beforeSend: false,
             cache: false,
             dataType: 'html',
             context: document.body,
//            beforeSend: function (xhr) {
//                xhr.setRequestHeader('X-WPAC-REQUEST', '1');
//                console.log(xhr);
//            },
            success: function (html) {


                        var $section = $(html).children('section'),
                        section_content = $section.children('div.content');

                        //var $script = section_content.find('script');
                        //section_content.find('script').remove();

                        if(! document.querySelector('#page-ajax-prev') ) {
                            $main.append('<section id="page-ajax-prev"></section>');
                        }

                        $(page_ajaxId).html(section_content);

                        $(page_ajaxId+' .ajax-page-link').addClass('page-link');


                var myTimer2 = setTimeout(function () {

                            $('.page-ajax-preloader').removeClass('activ');

                            nextPage(myanimcursor, page_ajaxId);
                            clearTimeout(myTimer2);

                }, 500);



            },
            error: function (jqXhr, textStatus, errorThrown) {


                        if(! document.querySelector('#page-ajax-prev') ) {
                            $main.append('<section id="page-ajax-prev"></section>');
                        }

                        var html = '<h1 class="text-center">Error - page not found!</h1>';
                        var protocol = location.origin.split("://");
                        if(protocol[0] === 'file') {
                            html = '<h1 class="text-center">Error - url adress!</h1><p class="lead text-center">Please use url adress: <kbd>http://</kbd> do not <kbd>file://</kbd> in your browser.</p>';
                        }

                        $(page_ajaxId).html(html);

                        nextPage(myanimcursor, page_ajaxId);

                        $('.page-ajax-preloader').removeClass('activ');

                //console.log(jqXhr.responseText);
            }
        });


        }


        function createSectionPageAjaxPrev() {

                if (pageActiv.attr('id') === 'page-ajax-prev') {
                    $('#page-ajax').remove();
                    $('#page-ajax-prev').attr("id", "page-ajax");
                }
        }

        function contentScrollOn() {

                    jQuery('.content').removeClass('scroll-auto');
                    jQuery("section.section-current .content").addClass('scroll-auto');

        }


        function updateAnimcursor(animid) {
                animcursor = animid;
                ++animcursor;
        }


        function updateNextAnimcursor(animid) {
                nextAnimcursor = false;
                if(animid) {
                    nextAnimcursor = true;
                }
                return nextAnimcursor;
        }


        function getAnimcursor(dane) {
                var animid = dane.attr('data-pageanim');
                if ( dane.attr("data-pageanim") ) {
                    return validateAnimcursor(animid);
                } else {
                    return getNextAnimcursor();
                }
        }


        function getNextAnimcursor() {
                if (nextAnimcursor) {
                    ++animcursor;
                    animcursor = validateAnimcursor(animcursor);
                }
                return animcursor;
        }


        function validateAnimcursor(animcursor) {
                if (animcursor > 67) {
                        animcursor = 1;
                 }
                return animcursor;
        }


        function activeMenuLink(item) {


                if ( !item ) {
                    return false;
                }

                var menuItem = $(item);
                menuItem = menuItem['0'];
                menuItem = $(menuItem.parentNode);

                if(menuItem) {
                    jQuery(menu+' li').removeClass('active');
                    menuItem.addClass('active');
                }

        }


        function getPageActiv(page) {

               if( location.hash !== "") {
                   return location.hash;
               }
               else if(page) {
                   return page;
               }
               else {
                   return '#'+$("section.page-activ").attr('id');
               }
        }

        function validatePage(pageId) {

               if(document.querySelector(pageId)) {

                  return true;
               } else {
                  return false;
               }
        }




	function onEndAnimation( $outpage, $inpage ) {
		endCurrPage = false;
		endNextPage = false;
		resetPage( $outpage, $inpage );
		isAnimating = false;
	}


	function resetPage( $outpage, $inpage ) {

		$outpage.attr( 'class', $outpage.data( 'originalClassList' ) + '' );
		$inpage.attr( 'class', $inpage.data( 'originalClassList' ) + ' section-current' );
                pageActiv = $inpage;
                createSectionPageAjaxPrev();
                contentScrollOn();
                startLazy();

	}


	return { init : init, updateAnimcursor: updateAnimcursor, updateNextAnimcursor: updateNextAnimcursor };

})();
