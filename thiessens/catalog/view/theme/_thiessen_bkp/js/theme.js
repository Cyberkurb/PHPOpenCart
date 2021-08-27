var categories = [];

$(document).ready(function() {

	var $div = $("<ul class='list-unstyled'></ul>").appendTo('.footer-categories'), code;
    $('#menu .shop-item > .shop-dropdown > li').each(function() {
        name = $(this).children('a').html();
        href = $(this).children('a').attr('href');
        $div.append( "<li><a href='" + href + "'>" + name + "</a></li>");
    });

    new Mmenu( "#mobile-menu", {
		"navbar": {
           "titleLink": "anchor"
        },
        "onClick": {
        	"close" : 1
        },
		"extensions": [
          "pagedim-black"
       ],
       "navbars": [
        {
            "position": "top",
            "content": [
                "searchfield"
             ]
          }
       ],
       "searchfield": {
          "showSubPanels": false,
          "search": false,
          "placeholder": "SEARCH..."
       }
    }, {
       "searchfield": {
          "form": [],
          "submit": true
       }
    });

		/* Search */
	$('.mm-searchfield__input input').next('.mm-btn').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('.mm-searchfield__input input').val();

		if (value != 0) {
			url += '&search=' + encodeURIComponent(value);
			location = url;
		}
	});

	$('.mm-searchfield__input input').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('.mm-searchfield__input input').next('.mm-btn').trigger('click');
		}
	});

	if ($('#product-category').length > 0 || $('#product-product').length > 0) {
		$('.breadcrumb li:first-child').after('<li><a href="javascript:void(0);">Shop</a></li>');
	}

  $(document).on('click', '.filter-section .filter-heading a', function(){
      $(this).toggleClass('open');
      $('.filter-section .filter-dropdown').slideToggle();
  });

  $('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-nav'
  });
  $('.slider-nav').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    focusOnSelect: true,
    nextArrow: '<span class="arrows next"><i class="far fa-chevron-right"></i></span>',
    prevArrow: '<span class="arrows prev"><i class="far fa-chevron-left"></i></span>',
    responsive: [
        {
            breakpoint: 1025,
            settings: {
              slidesToShow: 5,
              adaptiveHeight: true
           }
        },
        {
            breakpoint: 769,
            settings: {
              slidesToShow: 4,
              adaptiveHeight: true
           }
        },
        {
            breakpoint: 480,
            settings: {
              slidesToShow: 3,
              adaptiveHeight: true
           }
        },
      ]
  });
  if ($("#instafeed" ).length) {
      $('#instafeed').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
          enabled: true,
          navigateByImgClick: true,
          preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
          tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
          titleSrc: function(item) {
            return '<small>' + item.el.attr('title') + '</small>';
          }
        }
      });
  }

  /* sticky menu */
  if ($(window).scrollTop() > 0)
    $("body").addClass("sticky-menu");
  else
    $("body").removeClass("sticky-menu");


    $(".add-rating input:radio").attr("checked", false);

    $('.add-rating input').click(function () {
        $(".add-rating span").removeClass('checked');
        $(this).parent().addClass('checked');
    });
});


$(window).on("scroll", function(){

  if ($(window).scrollTop() > 0)
    $("body").addClass("sticky-menu");
  else
    $("body").removeClass("sticky-menu");
});