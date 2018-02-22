//Animate smile on setion--worry
function animate_half_circle() {
	(function() {
		var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
									window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
		window.requestAnimationFrame = requestAnimationFrame;
	})();
	
	var canvas = document.getElementById('half-circle');
	var context = canvas.getContext('2d');
	var x = canvas.width / 2;
	var y = 0;
	var radius = 167;
	var endPercent = 51;
	var curPerc = 0;
	var counterClockwise = false;
	var circ = Math.PI * 2;
	var quart = 0;

	context.lineWidth = 2;
	context.strokeStyle = '#FFFFFF';

	function animate(current) {
		context.clearRect(0, 0, canvas.width, canvas.height);
		context.beginPath();
		context.arc(x, y, radius, -(quart), ((circ) * current) - quart, false);
		context.stroke();
		curPerc++;
		if (curPerc < endPercent) {
			requestAnimationFrame(function () {
				animate(curPerc / 100)
			});
		}
	}

	animate();
}

//Special functions for the service sidebar
$('#sidebar select').change(function(){
	$('#sidebar .extra-info').hide();
});

$('#sidebar .dummy-login').on('focus', function(){
	$('#sidebar .extra-info').hide();
	$('#sidebar #homepage_manage_ecams_form').addClass('show').fadeIn();
	$('#sidebar #LOGIN_policyNo').focus();
});

$(document).ready(function()
{
	//Close app banner and set a 60 day cookie
	$('#banner_close').click(function(e){
		e.preventDefault(); //something with design kit (4.0) button click sets scroll position to top of page
		$.cookie('banner_closed', 'true', { expires: 60, path: '/' });
		$('#banner').removeClass('open');
	});

	//Show the Auto/ECAMS login form
	$('#primary-panel.service #homepage_manage_ecams_form').addClass('show').show();

	//Function to determine if an element is being displayed on screen or not
	$.fn.isOnScreen = function(extra_padding){
		var win = $(window);
		
		var viewport = {
			top : win.scrollTop(),
			left : win.scrollLeft()
		};
		viewport.right = viewport.left + win.width();
		viewport.bottom = viewport.top + win.height();
		
		var bounds = this.offset();
		bounds.right = bounds.left + this.outerWidth();
		bounds.bottom = bounds.top + this.outerHeight();

		bounds.top = bounds.top + 100;
		bounds.bottom = bounds.bottom - extra_padding;
		
		return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
	};

	//Create a book mark for each section
	$('section').each(function(index) {
		if (index == 0 ) {
			$('#bookmarks').append('<p class="bookmark current" data-section="'+this.id+'"></p>');	
		}
		else {
			$('#bookmarks').append('<p class="bookmark" data-section="'+this.id+'"></p>');
		}
	});

	$('.bookmark').click(function(){
		var section = $(this).data('section');
		$('html, body').animate({scrollTop: $("#"+section).offset().top - 60 }, 500);
	});

	var half_circ_shown = false;

	$(window).bind("load scroll", function(){

		//Animates the half circle on save_worry
		if($('#half-circle').isOnScreen()) {
			if(half_circ_shown == false) {
				animate_half_circle();
				half_circ_shown = true;
			}
		}

		//Handles the bookmark sections to the right
		$('section').each(function(index) {
			if (index == 0 ) {
				if($('#'+this.id).isOnScreen()) {
					$('.bookmark').removeClass('current');
					$('.bookmark[data-section='+this.id+']').addClass('current');
				}
			} else {
				if($('#'+this.id+' .split-section').isOnScreen()) {
					$('.bookmark').removeClass('current');
					$('.bookmark[data-section='+this.id+']').addClass('current');
				}
			}
		});
	});
});

/* If you're reading the below code and it is still commented out, please delete. 

//Recall panel's "ZIP Code" text hide/show
function zipText() {
	if (document.getElementById('zip').value == "ZIP") {
		document.getElementById('zip').value = "";
	} else if (document.getElementById('zip').value == "") {
		document.getElementById('zip').value = "ZIP";
	}
}*/