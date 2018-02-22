$(document).ready(function(){

	//////////////////////////////////////
	// GFR market listing randomization //
	//////////////////////////////////////

	var $div_array = [];
	var $div_length = $('.gfr_agent').length;

	for (var i = 0; i <= $div_length; i++) {
		
		$div_array[i] = $('.gfr_agent')[i];
	}

	$('.gfr_agent').remove();
	
	$div_array.sort(function() {
		return 0.5 - Math.random()
	});

	for (var i = 0; i <= $div_length; i++) {
		$( '#gfr_gecko' ).after( $div_array[i] );
	}

	////////////////////////////////////////////////
	// Toggle quote/manage forms on product pages //
	////////////////////////////////////////////////

	$('.task-toggle .toggle').click(function() {
		var form_hide = $(this).data('hide');
		var form_show = $(this).data('show');

		$('#form-contents .'+form_hide).slideUp(500, function(){
			$('#form-contents .'+form_show).slideDown(600);
			$('.task-toggle .container').toggle();
			$('html, body').animate({
				scrollTop: 0
			}, 600);
		});
	});
    
});