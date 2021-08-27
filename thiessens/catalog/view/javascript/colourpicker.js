$(document).ready(function() {
    $('.hoverimage').click(
        function() {
			var newsrc = $(this).attr('rel');
			var arr = newsrc.split('!');
			
			$('#image').attr('src', arr[0]);
			$('#image').parent().attr('href', arr[1]);
			
			//$('#zoom1').attr('href', arr[1]); //For Cloud Zoom
			//$('#zoom1').CloudZoom(); // For Cloud Zoom
			
			//var ez = $('#image').data('elevateZoom'); // For ElevateZoom
			//ez.swaptheimage(arr[0], arr[1]); // For ElevateZoom
        }
    );
});

$(document).ready(function() {
    $('.categoryimage').on("click", function(){
		var newsrc = $(this).attr('rel');
		var arr = newsrc.split('!');
		
		$('.thumb-' + arr[1]).attr('src', arr[0]);
    });
});

$(document).ready(function() {
	$('.colour-picker').hide();
	
	$('.op li').click(function(){
		var value = $(this).attr('id');
				
		$('.colour-picker').find('option[value=\'' + value + '\']').parent().val(value);

		$('.colour-picker').find('option[value=\'' + value + '\']').parent().trigger('change');
	});
	
	$('ul.color li').click(function() {
		$(this).siblings().css('opacity', 0.5).removeClass('active');
		
		$(this).css('opacity', 1).addClass('active');
	});	
	
	$('ul.size li').click(function(){		
		$(this).siblings().css('opacity',0.5).removeClass('active');
		
		$(this).css('opacity', 1).addClass('active');
	});	
});