var $ = jQuery.noConflict();
var load  = "<div style='width: 99%; text-align: center'><img src='images/loading1.gif' /></div>";
var sumaveche = 0; var sumanoua = 0; var compania; var companiaveche;
var error = function(titlu, continut){
	$("#errdiagtxt").text(continut);
	$("#myModalLabel").text(titlu);
    $( "#err-dialog" ).modal({
  		keyboard: true
	});
}

function SetCustomTab(){
	if(document.URL.indexOf("/#") != -1 ){
		var hashtabs = document.URL.split("#");
			hashtabs = hashtabs[1];
			$("a[id*='tab']").parent().removeClass('active');
			$(".customtabs").hide();
			$('.' + hashtabs).show();
			$('#' + hashtabs).parent().addClass('active');
			$('html, body').stop().animate({
	        	'scrollTop': $('body').offset().top
	    		}, 500, 'swing');
	}
}

$(document).ready(function() {
if( $('.reminder') ){ InitReminder(); }
setTimeout("SetCustomTab()", 100);

	//  dropdownmenu
	$('.children, .sub-menu').hide();
	$('nav ul li:has(ul)').mouseenter(function() {
		$(this).find('a').addClass('active-parent');
		$(this).find('> .children, > .sub-menu').slideDown('fast');
	}).mouseleave(function() {
		$(this).find('a').removeClass('active-parent');
		$(this).find('> .children, > .sub-menu').hide();
	});

	// mobile menu
	$('nav#mainmenu > ul').mobileMenu({
	    defaultText: 'ALEGE MENIU',
	    subMenuDash: '&ndash;'
	});
	$('.select-menu').wrap('<div class="sexyselect" />');

	// init Magnific popup
	$('.popup, .gallery:not(.photoalbums) .gallery-icon a').magnificPopup({
	  type: 'image',
	  gallery:{enabled:true},
	  titleSrc: 'alt',
	  cursor: 'mfp-zoom-out-cur'
	});

	// hiding default text in form fields
	$('[placeholder]').focus(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		  input.val('');
		  input.removeClass('placeholder');
		}
	}).blur(function() {
		var input = $(this);
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
		  input.addClass('placeholder');
		  input.val(input.attr('placeholder'));
		}
	}).blur();

	// tabs
	$('div.tabs-wrapper').find('.tab:not(:first)').hide();
	$('ul.tabs > li:first-child').addClass('active');

	$('ul.tabs a').click(function(event) {
		event.preventDefault();

		// make tab button active
		$(this).closest('ul').find('li').removeClass('active');
		$(this).closest('li').addClass('active');

		// hide inactive tab and show new tab
		$(this).closest('div').find('.tab').hide();
		var showme = $(this).attr('id');
		$(this).closest('div').find('.tab.' + showme).fadeIn();
	});

	// accordion
	$('.accordion').find('div:not(:first)').hide();
	$('.accordion > .accordion-title:first-child > a').addClass('active');

	$('.accordion > .accordion-title > a').click(function(event) {
		event.preventDefault();

		// hide all panels
	   	$(this).closest('.accordion').find('div').slideUp();

		// remove active class from all
		$(this).closest('.accordion').find('.accordion-title a').removeClass('active');

		// if the panel is closed, open it
	   	if($(this).parent().next().is(':hidden'))
		{
			$(this).parent().next().slideDown();
			$(this).addClass('active');
		}

	   	return false;
	});

	// back to top smooth scroll
	$('#back-to-top').on('click',function (e) {
	    e.preventDefault();

	    $('html, body').stop().animate({
	        'scrollTop': $('body').offset().top
	    }, 900, 'swing');
	});





});

function TrimiteTel(){
	if( $("#snumele").val().length < 2 ){
		$("#myReminderLabel").html("Sună-mă!");
		$("#modaldiagtxt").html("Trebuie să completaţi numele !");
		$( "#reminder-dialog" ).modal({ keyboard: true });
		return false;
	}
	if( $("#stelefon").val().length < 5 ){
		$("#myReminderLabel").html("Sună-mă!");
		$("#modaldiagtxt").html("Te rugăm să completezi telefonul !");
		$( "#reminder-dialog" ).modal({ keyboard: true });
		return false;
	}
	$.ajax({
		type: "POST",
		url: "https://www.rca.md/suna_ma.php",
		data: { lang: 'ro', init: 'true' }
	}).done(function( resp ) {
		$.ajax({
			type: "POST",
			url: "https://www.rca.md/suna_ma.php",
			data: { sessid: resp, lang: 'ro', numele: $('#snumele').val(), telefon: $("#stelefon").val() }
		}).done(function( mesaj ) {
		    if(mesaj == 1){
				$("#myReminderLabel").html("Sună-mă!");
				$("#modaldiagtxt").html("Cererea Dvs a fost trimisă! Vă vom contacta în cel mai scurt timp posibil!");
				$( "#reminder-dialog" ).modal({ keyboard: true });
			}else{
				$("#myReminderLabel").html("Sună-mă!");
				$("#modaldiagtxt").html(mesaj);
				$( "#reminder-dialog" ).modal({ keyboard: true });
			}
		});
	});

}

function InitReminder(){
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	var rcacal = $('#dataexp').datepicker({
		language: 'ro',
		format: 'dd/mm/yyyy',
	  	onRender: function(date) {
	    	return date.valueOf() < now.valueOf() ? 'disabled' : '';
	  	}
  	}).on('changeDate', function(ev) { rcacal.datepicker('hide'); });
	if( $('.reminder') && ( $('#reminder-dialog').length == 0 ) ){
		$( 'body' ).append("<div class='modal fade' id='reminder-dialog' tabindex='-1' role='dialog' aria-labelledby='myReminderLabel' aria-hidden='true'><div class='modal-dialog'><div class='modal-content'><div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button><h4 class='modal-title' id='myReminderLabel'></h4></div><div id='modaldiagtxt' class='modal-body'></div><div class='modal-footer'><button type='button' class='btn btn-default' data-dismiss='modal'>Închide</button></div></div></div></div>");
	}
}

function TrimiteRem(){
	if( $("#tipul").val() == 0 ){
		$("#myReminderLabel").html("Tipul vehiculului");
		$("#modaldiagtxt").html("Trebuie să alegeţi tipul vehiculului !");
		$( "#reminder-dialog" ).modal({ keyboard: true });
		return false;
	}
	if( $("#dataexp").val().length != 10 ){
		$("#myReminderLabel").html("Data expirării");
		$("#modaldiagtxt").html("Te rugăm să alegi data expirării!");
		$( "#reminder-dialog" ).modal({ keyboard: true });
		return false;
	}
	var email = $("#remail").val();
	var txt = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!txt.test(email)) {
		$("#myReminderLabel").html("E-Mail");
		$("#modaldiagtxt").html("Adresa E-Mail este incorectă!");
		$( "#reminder-dialog" ).modal({ keyboard: true });
		return false;
	}
	if( $("#rtelefon").val().length < 5 ){
		$("#myReminderLabel").html("Telefon");
		$("#modaldiagtxt").html("Te rugăm să completezi telefonul !");
		$( "#reminder-dialog" ).modal({ keyboard: true });
		return false;
	}
	$.ajax({
		type: "POST",
		url: "https://www.rca.md/reminder_insert.php",
		data: { lang: 'ro', init: 'true' }
	}).done(function( resp ) {
		$.ajax({
			type: "POST",
			url: "https://www.rca.md/reminder_insert.php",
			data: { sessid: resp, lang: 'ro', tipul: $('#tipul').val(), dataexp: $('#dataexp').val(), remail: $('#remail').val(), rtelefon: $('#rtelefon').val() }
		}).done(function( mesaj ) {
		    if(mesaj == 1){
				$("#myReminderLabel").html("Reminder");
				$("#modaldiagtxt").html("Cererea Dvs a fost înregistrată! Vom lua legătura telefonică cu cel puțin o saptamână înainte ca asigurarea specificată să expire!");
				$( "#reminder-dialog" ).modal({ keyboard: true });
			}else{
				$("#myReminderLabel").html("Reminder");
				$("#modaldiagtxt").html(mesaj);
				$( "#reminder-dialog" ).modal({ keyboard: true });
			}
		});
	});
}

$(window).load(function() {

});

$(window).scroll(function(){
	// on scroll, make the navigation fixed
	if ($(this).scrollTop() > 80) {
		$('#header').addClass('fixed-header');
		$('#back-to-top').css('display','block');
	} else {
		$('#header').removeClass('fixed-header');
		$('#back-to-top').hide();
	}
});

/*
$(document).ready(function(){
    $(".m25").hover(function(){
        $(this).stop().animate({'bottom': '125px'}, 500);
    }, function(){
        $(this).stop().animate({'bottom': '0'}, 500);
    });
});
*/
$(document).ready(function(){
$( ".m25" ).click(function() {
  $( this ).stop.slideUp();
});
	if(document.URL.indexOf("/calculator-rca/") != -1 ){ try{ CalcRCA(); }catch(e){} }
	if(document.URL.indexOf("/calculator-carte-verde/") != -1 ){ try{ CalcCV(); }catch(e){} }
	if(document.URL.indexOf("/calculator-casco/") != -1 ){ try{ initcascopag(); CalcCasco(); }catch(e){} }
	if(document.URL.indexOf("/asigurare-calatorie/") != -1 ){ try{ initac(); }catch(e){} }
});



//rca
function ScrollRoID(id){
    $('html, body').animate({
        scrollTop: $("#" + id).offset().top - 100
    }, 1000);
}
//set subtip auto
function SetSubtip(subtip){
	$(".subtip").hide();
	$("#subtip_" + subtip).show();
}
//arata, ascunde pensionari/invalizi cand apasam pe statutul juridic
function SetPensInv(id){

	if(id == 1){
		$(".tohide").show();
		$("#vrstsofer, #nrpersadm, #vechimeperm").removeAttr("disabled");
		$("#nrpersadm option:last, #vechimeperm option:last").prop('selected', false);
		$("#nrpersadm option:first, #vechimeperm option:first").prop('selected', true);
	}else{
		$(".tohide").hide();
		$("#nrpersadm option:first, #vechimeperm option:first").prop('selected', false);
		$("#nrpersadm option:last, #vechimeperm option:last").prop('selected', true);
		$("#vrstsofer, #nrpersadm, #vechimeperm").attr("disabled", "disabled");
	}

	if(id == 1){
		$("#trsernrlegitimpi").hide();
		$( "div[id*='pf_']" ).show();
		$( "div[id*='pj_']" ).hide();
		if($("#nrpersadm").val() == 1){
			$( "#alltrpav, #trpav1" ).show();
		}
		if( $("input[name|='pensinval']:checked").val() < 1 ){
			$( "#trsernrlegitimpi").show();
		}
	}else{
		$("#trsernrlegitimpi").show();
		$( "div[id*='pf_']" ).hide();
		$( "div[id*='pj_']" ).show();
		$( "#alltrpav" ).hide();
		$( "#trsernrlegitimpi").hide();
	}

}
//set perioada asigurare, elementele
function SetPerAsig(){
	if(($("#modutilizare").val() == 1) && ($("#inscrisin").val() == 1) ){
		$("#trperasigsel").hide();
		$("#trperasiginp").show();
	}else{
		$("#trperasigsel").show();
		$("#trperasiginp").hide();
	}
	if($("#inscrisin").val() > 1){
		$("#tippasspteh").val(3);
		AlegeForm(4);
		$(".tippasaportteh, #pasapoartele").hide();
		//
	}else{
		$(".tippasaportteh, #pasapoartele").show();
		$("#tippasspteh").val(0);
		AlegeForm(0);
		//
	}
}
function SetCVE(val){
	if(val == 2.5){
		$("#trcvpteur12l").show();
	}else{
		$("input[name|='cvpteur12l']:first").prop('checked', false);
		$("input[name|='cvpteur12l']:last").prop('checked', true);
		$("#trcvpteur12l").hide();
	}
}


function CalcRCA(){
var tipauto            = $("#tipauto").val();
var subtipauto         = $("#subtipauto_" + tipauto).val();
var inmatricult_in     = $("#inscrisin").val();
var resedinta          = $("#resedinta").val();
var statutjuridic      = $("input[name|='statutjur']:checked").val();
var cvpteur12l         = $("input[name|='cvpteur12l']:checked").val();
var nrpersadmise       = $("#nrpersadm").val();

var varstasofer        = $("#vrstsofer").val();
var vechimepermis      = $("#vechimeperm").val();

var modutilizare       = $("#modutilizare").val();
var pensionar_inv      = $("input[name|='pensinval']:checked").val();
var test_contract      = $("input[name|='testcontract']:checked").val();
var test_accidente		=$("#testaccidente").val();

if (test_contract == 1.00){
	$("#test_accidente").hide();
	if(varstasofer == 1 && vechimepermis >= 3) varstasofer = 1.1;
	if(varstasofer == 2 && vechimepermis >= 3) varstasofer = 0.9;
}
if (test_contract == 0.65){
	$("#test_accidente").show();	
	test_contract = 1;
}
if( $("#trperasigsel").css("display") === "none" ){
	var perioadaasig = $("#perasiginp").val();
}else{
	var perioadaasig = $("#perasigsel").val();
}

if(test_accidente == 1.00){
if (varstasofer == 1 && vechimepermis == 1) varstasofer = 1.2 * 1.0;
if (varstasofer == 1 && vechimepermis == 2) varstasofer = 1.2 * 0.95;
if (varstasofer == 1 && vechimepermis == 3) varstasofer = 1.1 * 0.90;
if (varstasofer == 1 && vechimepermis == 4) varstasofer = 1.1 * 0.85;
if (varstasofer == 1 && vechimepermis == 5) varstasofer = 1.1 * 0.80;
if (varstasofer == 1 && vechimepermis == 6) varstasofer = 1.1 * 0.75;
if (varstasofer == 1 && vechimepermis == 7) varstasofer = 1.1 * 0.7;
if (varstasofer == 1 && vechimepermis == 8) varstasofer = 1.1 * 0.65;

if (varstasofer == 2 && vechimepermis == 1) varstasofer = 1.0 * 1.0;
if (varstasofer == 2 && vechimepermis == 2) varstasofer = 1.0 * 0.95;
if (varstasofer == 2 && vechimepermis == 3) varstasofer = 0.9 * 0.90;
if (varstasofer == 2 && vechimepermis == 4) varstasofer = 0.9 * 0.85;
if (varstasofer == 2 && vechimepermis == 5) varstasofer = 0.9 * 0.80;
if (varstasofer == 2 && vechimepermis == 6) varstasofer = 0.9 * 0.75;
if (varstasofer == 2 && vechimepermis == 7) varstasofer = 0.9 * 0.7;
if (varstasofer == 2 && vechimepermis == 8) varstasofer = 0.9 * 0.65;
}
if(test_accidente == 1.45){
if (varstasofer == 1 && vechimepermis == 1) varstasofer = 1.2 * 1.0;
if (varstasofer == 1 && vechimepermis == 2) varstasofer = 1.2 * 0.90;
if (varstasofer == 1 && vechimepermis == 3) varstasofer = 1.1 * 0.80;
if (varstasofer == 1 && vechimepermis == 4) varstasofer = 1.1 * 0.69;
if (varstasofer == 1 && vechimepermis == 5) varstasofer = 1.1 * 0.66;
if (varstasofer == 1 && vechimepermis == 6) varstasofer = 1.1 * 0.62;
if (varstasofer == 1 && vechimepermis == 7) varstasofer = 1.1 * 0.59;
if (varstasofer == 1 && vechimepermis == 8) varstasofer = 1.1 * 0.55;

if (varstasofer == 2 && vechimepermis == 1) varstasofer = 1.0 * 1.0;
if (varstasofer == 2 && vechimepermis == 2) varstasofer = 1.0 * 0.90;
if (varstasofer == 2 && vechimepermis == 3) varstasofer = 0.9 * 0.80;
if (varstasofer == 2 && vechimepermis == 4) varstasofer = 0.9 * 0.69;
if (varstasofer == 2 && vechimepermis == 5) varstasofer = 0.9 * 0.66;
if (varstasofer == 2 && vechimepermis == 6) varstasofer = 0.9 * 0.62;
if (varstasofer == 2 && vechimepermis == 7) varstasofer = 0.9 * 0.59;
if (varstasofer == 2 && vechimepermis == 8) varstasofer = 0.9 * 0.55;
}
if(test_accidente == 1.90){
if (varstasofer == 1 && vechimepermis == 1) varstasofer = 1.2 * 1.0;
if (varstasofer == 1 && vechimepermis == 2) varstasofer = 1.2 * 0.84;
if (varstasofer == 1 && vechimepermis == 3) varstasofer = 1.1 * 0.76;
if (varstasofer == 1 && vechimepermis == 4) varstasofer = 1.1 * 0.68;
if (varstasofer == 1 && vechimepermis == 5) varstasofer = 1.1 * 0.60;
if (varstasofer == 1 && vechimepermis == 6) varstasofer = 1.1 * 0.53;
if (varstasofer == 1 && vechimepermis == 7) varstasofer = 1.1 * 0.50;
if (varstasofer == 1 && vechimepermis == 8) varstasofer = 1.1 * 0.47;

if (varstasofer == 2 && vechimepermis == 1) varstasofer = 1.0 * 1.0;
if (varstasofer == 2 && vechimepermis == 2) varstasofer = 1.0 * 0.84;
if (varstasofer == 2 && vechimepermis == 3) varstasofer = 0.9 * 0.76;
if (varstasofer == 2 && vechimepermis == 4) varstasofer = 0.9 * 0.68;
if (varstasofer == 2 && vechimepermis == 5) varstasofer = 0.9 * 0.60;
if (varstasofer == 2 && vechimepermis == 6) varstasofer = 0.9 * 0.53;
if (varstasofer == 2 && vechimepermis == 7) varstasofer = 0.9 * 0.50;
if (varstasofer == 2 && vechimepermis == 8) varstasofer = 0.9 * 0.47;
}
if(test_accidente == 2.50 && $("#testaccidente option:selected").text() == 3){
if (varstasofer == 1 && vechimepermis == 1) varstasofer = 1.2 * 1.0;
if (varstasofer == 1 && vechimepermis == 2) varstasofer = 1.2 * 0.88;
if (varstasofer == 1 && vechimepermis == 3) varstasofer = 1.1 * 0.76;
if (varstasofer == 1 && vechimepermis == 4) varstasofer = 1.1 * 0.64;
if (varstasofer == 1 && vechimepermis == 5) varstasofer = 1.1 * 0.58;
if (varstasofer == 1 && vechimepermis == 6) varstasofer = 1.1 * 0.52;
if (varstasofer == 1 && vechimepermis == 7) varstasofer = 1.1 * 0.46;
if (varstasofer == 1 && vechimepermis == 8) varstasofer = 1.1 * 0.40;

if (varstasofer == 2 && vechimepermis == 1) varstasofer = 1.0 * 1.0;
if (varstasofer == 2 && vechimepermis == 2) varstasofer = 1.0 * 0.88;
if (varstasofer == 2 && vechimepermis == 3) varstasofer = 0.9 * 0.76;
if (varstasofer == 2 && vechimepermis == 4) varstasofer = 0.9 * 0.64;
if (varstasofer == 2 && vechimepermis == 5) varstasofer = 0.9 * 0.58;
if (varstasofer == 2 && vechimepermis == 6) varstasofer = 0.9 * 0.52;
if (varstasofer == 2 && vechimepermis == 7) varstasofer = 0.9 * 0.46;
if (varstasofer == 2 && vechimepermis == 8) varstasofer = 0.9 * 0.40;
}
if(test_accidente == 2.50 && $("#testaccidente option:selected").text() !== 3){
if (varstasofer == 1 && vechimepermis == 1) varstasofer = 1.2 * 1.0;
if (varstasofer == 1 && vechimepermis == 2) varstasofer = 1.2 * 1.0;
if (varstasofer == 1 && vechimepermis == 3) varstasofer = 1.1 * 1.0;
if (varstasofer == 1 && vechimepermis == 4) varstasofer = 1.1 * 1.0;
if (varstasofer == 1 && vechimepermis == 5) varstasofer = 1.1 * 1.0;
if (varstasofer == 1 && vechimepermis == 6) varstasofer = 1.1 * 1.0;
if (varstasofer == 1 && vechimepermis == 7) varstasofer = 1.1 * 1.0;
if (varstasofer == 1 && vechimepermis == 8) varstasofer = 1.1 * 1.0;

if (varstasofer == 2 && vechimepermis == 1) varstasofer = 1.0 * 1.0;
if (varstasofer == 2 && vechimepermis == 2) varstasofer = 1.0 * 1.0;
if (varstasofer == 2 && vechimepermis == 3) varstasofer = 0.9 * 1.0;
if (varstasofer == 2 && vechimepermis == 4) varstasofer = 0.9 * 1.0;
if (varstasofer == 2 && vechimepermis == 5) varstasofer = 0.9 * 1.0;
if (varstasofer == 2 && vechimepermis == 6) varstasofer = 0.9 * 1.0;
if (varstasofer == 2 && vechimepermis == 7) varstasofer = 0.9 * 1.0;
if (varstasofer == 2 && vechimepermis == 8) varstasofer = 0.9 * 1.0;
}

if (statutjuridic == 1.5) {
$("label[for='testcontract']").text("Autovehiculul dat a fost asigurat şi anul precedent de această persoană juridică?");
pensionar_inv = 1;
test_contract = 1;
if(test_accidente == 1.00){
varstasofer = 0.65;
test_accidente =1;
}
if(test_accidente == 1.45){
varstasofer = 0.80;
test_accidente =1;
}
if(test_accidente == 1.90){
varstasofer = 0.90;
test_accidente =1;
}
if(test_accidente == 2.50 && $("#testaccidente option:selected").text() == 3){
varstasofer = 1;
test_accidente =1;
}
if(test_accidente == 2.50 && $("#testaccidente option:selected").text() !== 3){
varstasofer = 2.5;
test_accidente =1;
}
if($('#test_accidente').css('display') == 'none'){
varstasofer = 1;
test_accidente =1;
}
}
if (inmatricult_in == 3) {
resedinta = 1;
varstasofer = 1;
}

if (statutjuridic == 0.9 && nrpersadmise == 1.2 && test_contract == 1) {
if (vechimepermis == 1) varstasofer = 1.0;
if (vechimepermis == 2) varstasofer = 0.95;
if (vechimepermis == 3) varstasofer = 0.90;
if (vechimepermis == 4) varstasofer = 0.85;
if (vechimepermis == 5) varstasofer = 0.80;
if (vechimepermis == 6) varstasofer = 0.75;
if (vechimepermis == 7) varstasofer = 0.7;
if (vechimepermis == 8) varstasofer = 0.65;
}
if (statutjuridic == 0.9 && nrpersadmise == 1.2 && test_contract !== 1) {
varstasofer = 1;
test_contract = 1;
}


	var sv_total = Math.round(600 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sv_neoasig").text(sv_total);
	var sn_total = Math.round(715 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sn_neoasig").text(sn_total);
	var sv_total = Math.round(600 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sv_neoasig2").text(sv_total);
	var sn_total = Math.round(715 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sn_neoasig2").text(sn_total);
	var sv_total = Math.round(600 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sv_asito").text(sv_total);
	var sn_total = Math.round(715 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sn_asito").text(sn_total);
	var sv_total = Math.round(600 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sv_neoasig3").text(sv_total);
	var sn_total = Math.round(715 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sn_neoasig3").text(sn_total);
	var sv_total = Math.round(600 * 0.90 * 0.95 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sv_moldcargo").text(sv_total);
	var sn_total = Math.round(715 * 0.90 * 0.95 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sn_moldcargo").text(sn_total);
	var sv_total = Math.round(550 * 0.90 * 0.95 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sv_transelit").text(sv_total);
	var sn_total = Math.round(715 * 0.90 * 0.95 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sn_transelit").text(sn_total);
	var sv_total = Math.round(600 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sv_viktoria").text(sv_total);
	var sn_total = Math.round(715 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sn_viktoria").text(sn_total);
	var sv_total = Math.round(600 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sv_grawe").text(sv_total);
	var sn_total = Math.round(715 * 1.00 * 1.00 * subtipauto * inmatricult_in * resedinta * statutjuridic * nrpersadmise * varstasofer * cvpteur12l * perioadaasig * pensionar_inv * test_contract *test_accidente);
	$("#sn_grawe").text(sn_total);

if( $("input[name|='companiaasig']:checked").val() != undefined ){
	compania = $("input[name|='companiaasig']:checked").val();
	sumanoua = $("#sn_" + compania).text();
	if(sumanoua != sumaveche){
		Cadouri_RCA(compania);
		sumaveche = sumanoua;
	}
	if(compania !== companiaveche){
		Cadouri_RCA(compania);
		companiaveche = compania;
	}
}


setTimeout("CalcRCA()", 100);
}

//afisam cadouri
function Cadouri_RCA(v){
	$("#boxcadouri").html(load);
	$.ajax({
		type: "POST",
		url: "https://www.rca.md/cadouri.php",
		data: { lang: 'ro',porecla: v, suma: $("#sn_" + v).text(), tip: 'rca' }
	}).done(function( resp ) {
		   $("#boxcadouri").html(resp);
	});
}
function SetCadou(id){
	if(document.URL.indexOf("/rca/") != -1 ){ $(".divcadouri").removeClass("cadouadtiv"); $("#cadou_" + id).addClass("cadouadtiv"); ScrollRoID('tippasp'); }
	if(document.URL.indexOf("/carte-verde/") != -1 ){ $(".divcadouri").removeClass("cadouadtiv-cv"); $("#cadou_" + id).addClass("cadouadtiv-cv"); ScrollRoID('tippasp'); }
	if(document.URL.indexOf("/casco/") != -1 ){ $(".divcadouri").removeClass("cadouadtiv-casco"); var cadoultxt = $("#cadou_" + id).find("span").text(); $("#cadou_" + id).addClass("cadouadtiv-casco"); $("#cadoulid, #cadoultxt").remove(); $("#cascofrm").append("<input id='cadoulid' name='cadoulid' type='hidden' value='" + id + "' />\n<input id='cadoultxt' name='cadoultxt' type='hidden' value='" + cadoultxt + "' />\n"); ScrollRoID('casnume'); }
}

function AlegeForm(val){
	$(".divtippaspteh").removeClass("tippasptehadtivrca");
	$("#tippasaport_" + val).addClass("tippasptehadtivrca");
	var inmatricult_in     = $("#inscrisin").val();
	var statutjuridic      = $("input[name|='statutjur']:checked").val();
	var nrpersadmise       = $("#nrpersadm").val();
	var pensionar_inv      = $("input[name|='pensinval']:checked").val();
	$("#formcomanda").html(load);
	if(val == 0){
		$("#formcomanda").html("");
	}else{
		$.post( "https://www.rca.md/rca_forms/" + val + ".php", { lang: 'ro', tippaspteh: val, inmatricult_in: inmatricult_in, statutjuridic: statutjuridic, nrpersadmise: nrpersadmise, pensionar_inv: pensionar_inv })
		.done(function( data ) {
			$("#formcomanda").html(data);
		    ScrollRoID('formcomanda');
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		    var rcacal = $('#incepvalabil').datepicker({
		    	language: 'ro',
		    	format: 'dd/mm/yyyy',
  				onRender: function(date) {
    				return date.valueOf() < now.valueOf() ? 'disabled' : '';
  				}
  			}).on('changeDate', function(ev) { rcacal.datepicker('hide'); });
		});
	}
}

function SetSuplDrivers(val){
	if(val == 1){
		$("#trpav1").show();
	}else{
		$( "tr[id*='trpav']" ).hide();
	}
}

function SetPensInvF(val){
	if(val == 1){
		$("#trsernrlegitimpi").hide();
		$("#alltrpav, #divnrpersadm").show();
	}else{
		$("#trsernrlegitimpi").show();
		$("#alltrpav, #divnrpersadm").hide();
	}
}

function TrimiteRCA(id){
	var tot = "lang=ro&";
	tot = tot + "tip_pasp_tehnic=" + id + "&";
	if( $("#termeni").prop('checked') == false ){
		error("EROARE TERMENI ŞI CONDIŢII", "Trebuie să acceptaţi termenii şi condiţiile de utilizare ale acestui web-site !");
		return false;
	}
	var tip_auto           = $("#tipauto").val(); tot = tot + "tip_auto=" + tip_auto + "&";
	var subtip_auto        = $("#subtipauto_" + tip_auto).val(); tot = tot + "subtip_auto=" + subtip_auto + "&";
	var inmatriculat_in    = $("#inscrisin").val(); tot = tot + "inmatriculat_in=" + inmatriculat_in + "&";
	var resedinta          = $("#resedinta").val(); tot = tot + "resedinta=" + resedinta + "&";
	var statutjuridic      = $("input[name|='statutjur']:checked").val(); tot = tot + "statutjuridic=" + statutjuridic + "&";
	var cvpteur12l         = $("input[name|='cvpteur12l']:checked").val(); tot = tot + "cvpteur12l=" + cvpteur12l + "&";
	var nrpersadmise       = $("#nrpersadm").val(); tot = tot + "nrpersadmise=" + nrpersadmise + "&";
	var varstasofer        = $("#vrstsofer").val(); tot = tot + "varstasofer=" + varstasofer + "&";
	var vechimepermis      = $("#vechimeperm").val(); tot = tot + "vechimepermis=" + vechimepermis + "&";
	var modutilizare       = $("#modutilizare").val(); tot = tot + "modutilizare=" + modutilizare + "&";
	var pensionar_inv      = $("input[name|='pensinval']:checked").val(); tot = tot + "pensionar_inv=" + pensionar_inv + "&";
	if( $("#trperasigsel").css("display") === "none" ){
		var perioadaasig   = $("#perasiginp").val(); tot = tot + "perioadaasig=" + perioadaasig + "&";
		var perioadaasigtx = $("#perasiginptx").text(); tot = tot + "perioadaasigtx=" + perioadaasigtx + "&";
	}else{
		var perioadaasig   = $("#perasigsel").val(); tot = tot + "perioadaasig=" + perioadaasig + "&";
		var perioadaasigtx = $("#perasigsel option:selected").text(); tot = tot + "perioadaasigtx=" + perioadaasigtx + "&";
	}
	var companiaasig = $("input[name|='companiaasig']:checked").val(); tot = tot + "companiaasig=" + companiaasig + "&";
	var suma = $("input[name|='companiaasig']:checked").val(); suma = $("#sn_" + suma).text(); tot = tot + "suma=" + suma + "&";
	if(suma.length == 0){
		ScrollRoID('companiileasig');
		error("COMPANIA ASIGURĂRI", "Te rugăm să alegi compania de asigurări cu suma aferentă rezultată !");
		return false;
	}

	var numcadouri = $(".divcadouri").length;
	var cadou = $( ".cadouadtiv" ).find('span').text(); tot = tot + "cadou=" + cadou + "&";
	if((numcadouri > 0) && (cadou.length == 0) ){
		ScrollRoID('boxcadouri');
		error("CADOU INCORECT", "Te rugăm să alegi un cadou !");
		return false;
	}

	if(id == 1){
		if( $("#pf_np").css("display") !== "none") {
			var numeprenume = $("#numeprenume").val(); tot = tot + "numeprenume=" + numeprenume + "&";
			if(numeprenume.length < 2){
				ScrollRoID('pf_np');
				error("NUME INCORECT", "Trebuie să completaţi numele !");
				return false;
			}
		}
		if( $("#pf_cp").css("display") !== "none") {
			var codpersonal = $("#codpersonal").val(); tot = tot + "codpersonal=" + codpersonal + "&";
			if(codpersonal.length < 13){
				ScrollRoID('pf_cp');
				error("COD PERSONAL INCORECT", "Trebuie să completaţi corect cod personal!");
				return false;
			}
		}
		if( $("#pj_di").css("display") !== "none") {
			var dencompanie = $("#dencompanie").val().replace("&", "{_}"); tot = tot + "dencompanie=" + dencompanie + "&";
			if(dencompanie.length < 3){
				ScrollRoID('pj_di');
				error("DENUMIRE COMPANIE", "Vă rugăm să completaţi corect denumirea companiei !");
				return false;
			}
		}
		if( $("#pj_cf").css("display") !== "none") {
			var codfiscal   = $("#codfiscal").val(); tot = tot + "codfiscal=" + codfiscal + "&";
			if(codfiscal.length < 3){
				ScrollRoID('pj_cf');
				error("COD FISCAL", "Vă rugăm să completaţi corect codul fiscal !");
				return false;
			}
		}
		var dreptposesiune  = $("#dreptposesiune").val(); tot = tot + "dreptposesiune=" + dreptposesiune + "&";
		var nrinmatricdoc   = $("#nrinmatric").val(); tot = tot + "nrinmatricdoc=" + nrinmatricdoc + "&";
		var nrinregauto     = $("#nrregauto").val(); tot = tot + "nrinregauto=" + nrinregauto + "&";
		if( $("#trsernrlegitimpi").css("display") !== "none") {
			var ser_nr_legit_pens_inv   = $("#sernrlegitimpi").val(); tot = tot + "ser_nr_legit_pens_inv=" + ser_nr_legit_pens_inv + "&";
		}
		if( $("#trpav1").css("display") !== "none") {
			var pav_np1   = $("#pav_np1").val(); tot = tot + "pav_np1=" + pav_np1 + "&";
			var pav_cp1   = $("#pav_cp1").val(); tot = tot + "pav_cp1=" + pav_cp1 + "&";
		}
		if( $("#trpav2").css("display") !== "none") {
			var pav_np2   = $("#pav_np2").val(); tot = tot + "pav_np2=" + pav_np2 + "&";
			var pav_cp2   = $("#pav_cp2").val(); tot = tot + "pav_cp2=" + pav_cp2 + "&";
		}
		if( $("#trpav3").css("display") !== "none") {
			var pav_np3   = $("#pav_np3").val(); tot = tot + "pav_np3=" + pav_np3 + "&";
			var pav_cp3   = $("#pav_cp3").val(); tot = tot + "pav_cp3=" + pav_cp3 + "&";
		}
		var incepvalabil   = $("#incepvalabil").val(); tot = tot + "incepvalabil=" + incepvalabil + "&";
		if(incepvalabil.length != 10){
			error("DATA ÎNCEPERE VALABILITATE", "Vă rugăm să alegeţi data de la care asigurarea intră în vigoare !");
			return false;
		}
		var telefon = $("#telefon").val(); tot = tot + "telefon=" + telefon + "&";
		var email = $("#email").val(); tot = tot + "email=" + email + "&";
		if( $("#pj_rb1").css("display") !== "none") {
			var nrcontbanca   = $("#nrcontbanca").val(); tot = tot + "nrcontbanca=" + nrcontbanca + "&";
			var denumirebanca   = $("#denumirebanca").val(); tot = tot + "denumirebanca=" + denumirebanca + "&";
			var codulbaniiswift   = $("#codulbaniiswift").val(); tot = tot + "codulbaniiswift=" + codulbaniiswift + "&";
			var vattva   = $("#vattva").val(); tot = tot + "vattva=" + vattva + "&";
		}
		var modplata = $("#modplata").val(); tot = tot + "modplata=" + modplata;

	}

	if(id == 2){
		var nrinmatricdoc   = $("#nrinmatric").val(); tot = tot + "nrinmatricdoc=" + nrinmatricdoc + "&";
		var nrinregauto     = $("#nrregauto").val(); tot = tot + "nrinregauto=" + nrinregauto + "&";

		var anprodauto    = $("#anprodauto").val(); tot = tot + "anprodauto=" + anprodauto + "&";
		var marcamodelul  = $("#marcamodelul").val(); tot = tot + "marcamodelul=" + marcamodelul + "&";
		var tipautov      = $("#tipautov").val(); tot = tot + "tipautov=" + tipautov + "&";
		var capacitatecil = $("#capacitatecil").val(); tot = tot + "capacitatecil=" + capacitatecil + "&";
		var masaproprie   = $("#masaproprie").val(); tot = tot + "masaproprie=" + masaproprie + "&";
		var masamaxima    = $("#masamaxima").val(); tot = tot + "masamaxima=" + masamaxima + "&";
		var nrlocuri      = $("#nrlocuri").val(); tot = tot + "nrlocuri=" + nrlocuri + "&";
		var nrcaroserie   = $("#nrcaroserie").val(); tot = tot + "nrcaroserie=" + nrcaroserie + "&";
		var nrmotorului   = $("#nrmotorului").val(); tot = tot + "nrmotorului=" + nrmotorului + "&";

		if( $("#pf_np").css("display") !== "none") {
			var numeprenume = $("#numeprenume").val(); tot = tot + "numeprenume=" + numeprenume + "&";
			if(numeprenume.length < 2){
				error("NUME INCORECT", "Trebuie să completaţi numele !");
				return false;
			}
		}
		if( $("#pf_cp").css("display") !== "none") {
			var codpersonal = $("#codpersonal").val(); tot = tot + "codpersonal=" + codpersonal + "&";
			if(codpersonal.length < 13){
				error("COD PERSONAL INCORECT", "Trebuie să completaţi corect cod personal!");
				return false;
			}
		}
		if( nrinmatricdoc.length < 1 ){
			error("Numărul de inmatriculare al documentului este incorect!", "Vă rugăm să completaţi Numărul de inmatriculare al documentului!");
			return false;
		}
		if( nrinregauto.length < 1 ){
			error("Numărul de inregistrare al autovehiculului este incorect!", "Vă rugăm să completaţi Numărul de inregistrare al autovehiculului!");
			return false;
		}
		if( anprodauto.length < 1 ){
			error("Anul de fabricație al autovehiculului este incorect!", "Vă rugăm să completaţi Anul de fabricație al autovehiculului!");
			return false;
		}
		if( marcamodelul.length < 1 ){
			error("Marca/Modelul autovehiculului este incorectă!", "Vă rugăm să completaţi Marca/Modelul autovehiculului!");
			return false;
		}
		if( tipautov.length < 1 ){
			error("Tipul autovehiculului este incorect!", "Vă rugăm să completaţi Tipul autovehiculului!");
			return false;
		}
		if( capacitatecil.length < 1 ){
			error("Capacitatea cilindrică a autovehiculului este incorectă!", "Vă rugăm să completaţi Capacitatea cilindrică a autovehiculului!");
			return false;
		}
		if( masaproprie.length < 1 ){
			error("Masa proprie a autovehiculului este incorectă!", "Vă rugăm să completaţi Masa proprie a autovehiculului!");
			return false;
		}
		if( masamaxima.length < 1 ){
			error("Masa maximă a autovehiculului este incorectă!", "Vă rugăm să completaţi Masa maximă a autovehiculului!");
			return false;
		}
		if( nrlocuri.length < 1 ){
			error("Numărul de locuri a autovehiculului este incorect!", "Vă rugăm să completaţi Numărul de locuri a autovehiculului!");
			return false;
		}
		if( nrcaroserie.length < 1 ){
			error("Numărul caroseriei autovehiculului este incorect!", "Vă rugăm să completaţi Numărul caroseriei autovehiculului!");
			return false;
		}
		if( nrmotorului.length < 1 ){
			error("Numărul motorului autovehiculului este incorect!", "Vă rugăm să completaţi Numărul motorului autovehiculului!");
			return false;
		}
		var telefon=$("#telefon").val();
		var email=$("#email").val();
		if( telefon.length < 1 ){
			error("Numărul de telefon este incorect!", "Te rugăm să completezi telefonul !");
			return false;
		}
		if( email.length < 1 ){
			error("Adresa E-Mail este incorectă!", "Vă rugăm să completaţi Emailul!");
			return false;
		}
		if( $("#pj_di").css("display") !== "none") {
			var dencompanie = $("#dencompanie").val().replace("&", "{_}"); tot = tot + "dencompanie=" + dencompanie + "&";
			if(dencompanie.length < 3){
				error("DENUMIRE COMPANIE", "Vă rugăm să completaţi corect denumirea companiei !");
				return false;
			}
		}
		if( $("#pj_cf").css("display") !== "none") {
			var codfiscal   = $("#codfiscal").val(); tot = tot + "codfiscal=" + codfiscal + "&";
			if(codfiscal.length < 3){
				error("COD FISCAL", "Vă rugăm să completaţi corect codul fiscal !");
				return false;
			}
		}
		var dreptposesiune  = $("#dreptposesiune").val(); tot = tot + "dreptposesiune=" + dreptposesiune + "&";
		var nrinmatricdoc   = $("#nrinmatric").val(); tot = tot + "nrinmatricdoc=" + nrinmatricdoc + "&";
		var nrinregauto     = $("#nrregauto").val(); tot = tot + "nrinregauto=" + nrinregauto + "&";
		if( $("#trsernrlegitimpi").css("display") !== "none") {
			var ser_nr_legit_pens_inv   = $("#sernrlegitimpi").val(); tot = tot + "ser_nr_legit_pens_inv=" + ser_nr_legit_pens_inv + "&";
		}
		if( $("#trpav1").css("display") !== "none") {
			var pav_np1   = $("#pav_np1").val(); tot = tot + "pav_np1=" + pav_np1 + "&";
			var pav_cp1   = $("#pav_cp1").val(); tot = tot + "pav_cp1=" + pav_cp1 + "&";
		}
		if( $("#trpav2").css("display") !== "none") {
			var pav_np2   = $("#pav_np2").val(); tot = tot + "pav_np2=" + pav_np2 + "&";
			var pav_cp2   = $("#pav_cp2").val(); tot = tot + "pav_cp2=" + pav_cp2 + "&";
		}
		if( $("#trpav3").css("display") !== "none") {
			var pav_np3   = $("#pav_np3").val(); tot = tot + "pav_np3=" + pav_np3 + "&";
			var pav_cp3   = $("#pav_cp3").val(); tot = tot + "pav_cp3=" + pav_cp3 + "&";
		}
		var incepvalabil   = $("#incepvalabil").val(); tot = tot + "incepvalabil=" + incepvalabil + "&";
		if(incepvalabil.length != 10){
			error("DATA ÎNCEPERE VALABILITATE", "Vă rugăm să alegeţi data de la care asigurarea intră în vigoare !");
			return false;
		}
		var telefon = $("#telefon").val(); tot = tot + "telefon=" + telefon + "&";
		var email = $("#email").val(); tot = tot + "email=" + email + "&";
		if( $("#pj_rb1").css("display") !== "none") {
			var nrcontbanca   = $("#nrcontbanca").val(); tot = tot + "nrcontbanca=" + nrcontbanca + "&";
			var denumirebanca   = $("#denumirebanca").val(); tot = tot + "denumirebanca=" + denumirebanca + "&";
			var codulbaniiswift   = $("#codulbaniiswift").val(); tot = tot + "codulbaniiswift=" + codulbaniiswift + "&";
			var vattva   = $("#vattva").val(); tot = tot + "vattva=" + vattva + "&";
		}
		var modplata = $("#modplata").val(); tot = tot + "modplata=" + modplata;

	}

	if(id == 3){
		if( $("#pf_np").css("display") !== "none") {
			var numeprenume = $("#numeprenume").val(); tot = tot + "numeprenume=" + numeprenume + "&";
			if(numeprenume.length < 2){
				error("NUME INCORECT", "Trebuie să completaţi numele !");
				return false;
			}
		}
		if( $("#pf_cp").css("display") !== "none") {
			var codpersonal = $("#codpersonal").val(); tot = tot + "codpersonal=" + codpersonal + "&";
			if(codpersonal.length < 13){
				error("COD PERSONAL INCORECT", "Trebuie să completaţi corect cod personal!");
				return false;
			}
			var telefon=$("#telefon").val();
			var email=$("#email").val();
			if( telefon.length < 1 ){
				error("Numărul de telefon este incorect!", "Te rugăm să completezi telefonul !");
				return false;
			}
			if( email.length < 1 ){
				error("Adresa E-Mail este incorectă!", "Vă rugăm să completaţi Emailul!");
				return false;
			}
		}
		if( $("#pj_di").css("display") !== "none") {
			var dencompanie = $("#dencompanie").val().replace("&", "{_}"); tot = tot + "dencompanie=" + dencompanie + "&";
			if(dencompanie.length < 3){
				error("DENUMIRE COMPANIE", "Vă rugăm să completaţi corect denumirea companiei !");
				return false;
			}
		}
		if( $("#pj_cf").css("display") !== "none") {
			var codfiscal   = $("#codfiscal").val(); tot = tot + "codfiscal=" + codfiscal + "&";
			if(codfiscal.length < 3){
				error("COD FISCAL", "Vă rugăm să completaţi corect codul fiscal !");
				return false;
			}
		}
		var dreptposesiune  = $("#dreptposesiune").val(); tot = tot + "dreptposesiune=" + dreptposesiune + "&";
		var nrinmatricdoc   = $("#nrinmatric").val(); tot = tot + "nrinmatricdoc=" + nrinmatricdoc + "&";
		var nrinregauto     = $("#nrregauto").val(); tot = tot + "nrinregauto=" + nrinregauto + "&";

			var anprodauto    = $("#anprodauto").val(); tot = tot + "anprodauto=" + anprodauto + "&";
			var marcamodelul  = $("#marcamodelul").val(); tot = tot + "marcamodelul=" + marcamodelul + "&";
			var tipautov      = $("#tipautov").val(); tot = tot + "tipautov=" + tipautov + "&";
			var capacitatecil = $("#capacitatecil").val(); tot = tot + "capacitatecil=" + capacitatecil + "&";
			var masaproprie   = $("#masaproprie").val(); tot = tot + "masaproprie=" + masaproprie + "&";
			var masamaxima    = $("#masamaxima").val(); tot = tot + "masamaxima=" + masamaxima + "&";
			var nrlocuri      = $("#nrlocuri").val(); tot = tot + "nrlocuri=" + nrlocuri + "&";
			var nrcaroserie   = $("#nrcaroserie").val(); tot = tot + "nrcaroserie=" + nrcaroserie + "&";
			var nrmotorului   = $("#nrmotorului").val(); tot = tot + "nrmotorului=" + nrmotorului + "&";
	
		if( nrinmatricdoc.length < 1 ){
			error("Numărul de inmatriculare al documentului este incorect!", "Vă rugăm să completaţi Numărul de inmatriculare al documentului!");
			return false;
		}
		if( nrinregauto.length < 1 ){
			error("Numărul de inregistrare al autovehiculului este incorect!", "Vă rugăm să completaţi Numărul de inregistrare al autovehiculului!");
			return false;
		}
		if( anprodauto.length < 1 ){
			error("Anul de fabricație al autovehiculului este incorect!", "Vă rugăm să completaţi Anul de fabricație al autovehiculului!");
			return false;
		}
		if( marcamodelul.length < 1 ){
			error("Marca/Modelul autovehiculului este incorectă!", "Vă rugăm să completaţi Marca/Modelul autovehiculului!");
			return false;
		}
		if( tipautov.length < 1 ){
			error("Tipul autovehiculului este incorect!", "Vă rugăm să completaţi Tipul autovehiculului!");
			return false;
		}
		if( capacitatecil.length < 1 ){
			error("Capacitatea cilindrică a autovehiculului este incorectă!", "Vă rugăm să completaţi Capacitatea cilindrică a autovehiculului!");
			return false;
		}
		if( masaproprie.length < 1 ){
			error("Masa proprie a autovehiculului este incorectă!", "Vă rugăm să completaţi Masa proprie a autovehiculului!");
			return false;
		}
		if( masamaxima.length < 1 ){
			error("Masa maximă a autovehiculului este incorectă!", "Vă rugăm să completaţi Masa maximă a autovehiculului!");
			return false;
		}
		if( nrlocuri.length < 1 ){
			error("Numărul de locuri a autovehiculului este incorect!", "Vă rugăm să completaţi Numărul de locuri a autovehiculului!");
			return false;
		}
		if( nrcaroserie.length < 1 ){
			error("Numărul caroseriei autovehiculului este incorect!", "Vă rugăm să completaţi Numărul caroseriei autovehiculului!");
			return false;
		}
		if( nrmotorului.length < 1 ){
			error("Numărul motorului autovehiculului este incorect!", "Vă rugăm să completaţi Numărul motorului autovehiculului!");
			return false;
		}
		
		if( $("#trsernrlegitimpi").css("display") !== "none") {
			var ser_nr_legit_pens_inv   = $("#sernrlegitimpi").val(); tot = tot + "ser_nr_legit_pens_inv=" + ser_nr_legit_pens_inv + "&";
		}
		if( $("#trpav1").css("display") !== "none") {
			var pav_np1   = $("#pav_np1").val(); tot = tot + "pav_np1=" + pav_np1 + "&";
			var pav_cp1   = $("#pav_cp1").val(); tot = tot + "pav_cp1=" + pav_cp1 + "&";
		}
		if( $("#trpav2").css("display") !== "none") {
			var pav_np2   = $("#pav_np2").val(); tot = tot + "pav_np2=" + pav_np2 + "&";
			var pav_cp2   = $("#pav_cp2").val(); tot = tot + "pav_cp2=" + pav_cp2 + "&";
		}
		if( $("#trpav3").css("display") !== "none") {
			var pav_np3   = $("#pav_np3").val(); tot = tot + "pav_np3=" + pav_np3 + "&";
			var pav_cp3   = $("#pav_cp3").val(); tot = tot + "pav_cp3=" + pav_cp3 + "&";
		}
		var incepvalabil   = $("#incepvalabil").val(); tot = tot + "incepvalabil=" + incepvalabil + "&";
		if(incepvalabil.length != 10){
			error("DATA ÎNCEPERE VALABILITATE", "Vă rugăm să alegeţi data de la care asigurarea intră în vigoare !");
			return false;
		}
		var telefon = $("#telefon").val(); tot = tot + "telefon=" + telefon + "&";
		var email = $("#email").val(); tot = tot + "email=" + email + "&";
		if( $("#pj_rb1").css("display") !== "none") {
			var nrcontbanca   = $("#nrcontbanca").val(); tot = tot + "nrcontbanca=" + nrcontbanca + "&";
			var denumirebanca   = $("#denumirebanca").val(); tot = tot + "denumirebanca=" + denumirebanca + "&";
			var codulbaniiswift   = $("#codulbaniiswift").val(); tot = tot + "codulbaniiswift=" + codulbaniiswift + "&";
			var vattva   = $("#vattva").val(); tot = tot + "vattva=" + vattva + "&";
		}
		var modplata = $("#modplata").val(); tot = tot + "modplata=" + modplata;

	}

    $( "#final-dialog" ).modal({
  		keyboard: true
	});
	$("#finaldiagtxt").html(load);
	$.ajax({
		type: "POST",
		cache: false,
		url: "https://www.rca.md/finalizare_rca.php",
		data: tot
	}).done(function( resp ) {
		if(resp != 1 ){
		   $("#finaldiagtxt").html("<span style='color: red'>" + resp + "</span>");
		}else{
			$("#finaldiagtxt").html("Felicitări! Comanda Dvs a fost trimisă cu succes. Revenim cu un sunet în cel mai scurt timp. Vă mulţumim!");

			if(modplata == 3){
				$.post( "https://www.rca.md/payGen.php", { pay: 'card', asig: 'rca', lang: 'ro' })
				.done(function( data2 ) {
					if(data2 == 0){
						$("#finaldiagtxt").html("<span style='color: red'>Nu pot procesa CC!</span>");
					}else{
						$('#VictoriaBank').remove();
						$( data2 ).appendTo( "body" );
						setTimeout("$('#VictoriaBank').submit();", 1000);
					}
				});	
			}

			
		}
	});

}
//SFARSIT RCA
//INCEPUT CARTE VERDE
function CVSelTipVeh(val){
	var zid = $("#zona option:selected").text().split(".");
	$("input[name*='tipveh_']").removeAttr('checked');
	$(".ulnolist").hide();
	$("#ultipveh_" + zid[0]).show();
                                //alert(zid[0]);
	$(".tridconp").hide();
	if(zid[0] == 2){
		$("#tridconp_1").show();
		$("#tridconp_2").show();
	}else{
		$(".tridconp").show();
	}
}
function CalcCV(){
	var zona = $("#zona").val();
	var zid = $("#zona option:selected").text().split("."); zid = zid[0];
    var cvtipveh = $("input[name|='tipveh_" + zid + "']:checked").val();
	var cvperioada = $("#perioada").val();
	if( (zona == 552) && (cvperioada == 0.15) ){
		cvperioada = 0.1;
	}

	var cvremorca = $("input[name|='cvremorca']:checked").val();

//alert(zona);
    var cvrata = parseFloat('19.5313');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sv_neoasig").text(result);

    var cvrata = parseFloat('22.1947');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sn_neoasig").text(result);


    var cvrata = parseFloat('19.5313');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sv_neoasig2").text(result);

    var cvrata = parseFloat('22.1947');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sn_neoasig2").text(result);


    var cvrata = parseFloat('19.5313');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sv_asito").text(result);

    var cvrata = parseFloat('22.1947');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sn_asito").text(result);


    var cvrata = parseFloat('19.5313');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sv_moldcargo").text(result);

    var cvrata = parseFloat('22.1947');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sn_moldcargo").text(result);


    var cvrata = parseFloat('19.5313');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sv_viktoria").text(result);

    var cvrata = parseFloat('22.1947');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sn_viktoria").text(result);


    var cvrata = parseFloat('19.5313');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sv_grawe").text(result);

    var cvrata = parseFloat('22.1947');
    var result = Math.round(cvtipveh * zona * cvperioada * 100)/100;
    result = (cvremorca != 0) ? result + result*cvremorca : result;
    result = Math.round(cvrata * result); if(result >= 0){}else{ result = 0; }
    $("#sn_grawe").text(result);




if( $("input[name|='companiaasig']:checked").val() != undefined ){
	compania = $("input[name|='companiaasig']:checked").val();
	sumanoua = $("#sn_" + compania).text();
	if(sumanoua != sumaveche){
		Cadouri_CV(compania);
		sumaveche = sumanoua;
	}
	if(compania !== companiaveche){
		Cadouri_CV(compania);
		companiaveche = compania;
	}
}


	setTimeout("CalcCV()", 200);

}

function Cadouri_CV(v){
	$("#boxcadouri").html(load);
	$.ajax({
		type: "POST",
		url: "https://www.rca.md/cadouri.php",
		data: { lang: 'ro', porecla: v, suma: $("#sn_" + v).text(), tip: 'cv' }
	}).done(function( resp ) {
		if(resp.length < 10){
			$("#boxcadouri").html("Pentru această sumă nu sunt cadouri disponibile !");
		}else{
			$("#boxcadouri").html(resp);
		}
	});
}


function CVSetTipPaspTeh(tip){
	$(".divtippaspteh").removeClass("tippasptehadtiv");
	$("#tippasaport_" + tip).addClass("tippasptehadtiv");
	AlegeCVForm(tip);
}

function AlegeCVForm(val){
	$("#CVFormCmd").html(load);
	var zona = $("#zona").val();
	var zid = $("#zona option:selected").text().split("."); zid = zid[0];
    var cvtipveh = $("input[name|='tipveh_" + zid + "']:checked").val();
	var cvperioada = $("#perioada").val();
	var cvremorca = $("input[name|='cvremorca']:checked").val();
	$.post( "https://www.rca.md/cv_forms/" + val + ".php", { lang: 'ro', tip: val, zona: zona, cvtipveh: cvtipveh, cvperioada: cvperioada, cvremorca: cvremorca })
		.done(function( data ) {
			$("#CVFormCmd").html(data);
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		    var cvcal = $('#cvdataincep').datepicker({
		    	language: 'ro',
		    	format: 'dd/mm/yyyy',
  				onRender: function(date) {
    				return date.valueOf() < now.valueOf() ? 'disabled' : '';
  				},

  			}).on('changeDate', function(ev) { cvcal.datepicker('hide'); });
			ScrollRoID('CVFormCmd');
	});



}

function FinishCV(){
	$("#cvform").empty();
	if( $("#termeni").prop('checked') == false ){
		error("EROARE TERMENI ŞI CONDIŢII", "Trebuie să acceptaţi termenii şi condiţiile de utilizare ale acestui web-site !");
		return false;
	}
										$("#cvform").html("<input type='hidden' name='lang' value='ro' />");
	var zona = $("#zona").val();
										$("#cvform").append("<input type='hidden' name='zona' value='" + zona + "' />");
			var zid = $("#zona option:selected").text().split("."); zid = zid[0];
    var cvtipveh = $("input[name|='tipveh_" + zid + "']:checked").val();
	if( !cvtipveh ){
		error("TIP VEHICUL", "Trebuie să alegeţi tipul vehiculului !");
		return false;
	}
										$("#cvform").append("<input type='hidden' name='valtipvehicul' value='" + cvtipveh + "' />");
    var cvtipvehtxt = $("input[name|='tipveh_" + zid + "']:checked").parent('li').text();
    									$("#cvform").append("<input type='hidden' name='txttipvehicul' value='" + cvtipvehtxt + "' />");
    var valperioada = $("#perioada").val();
    									$("#cvform").append("<input type='hidden' name='valperioada' value='" + valperioada + "' />");
    var txtperioada = $("#perioada option:selected").text();
    									$("#cvform").append("<input type='hidden' name='txtperioada' value='" + txtperioada + "' />");
    var remorca = $("input[name|='cvremorca']:checked").val();
    									$("#cvform").append("<input type='hidden' name='remorca' value='" + remorca + "' />");
	var companiaasig = $("input[name|='companiaasig']:checked").val();
										$("#cvform").append("<input type='hidden' name='companiaasig' value='" + companiaasig + "' />");
	var suma = $("input[name|='companiaasig']:checked").val(); suma = $("#sn_" + suma).text();
	if(suma.length == 0){
		error("COMPANIA ASIGURĂRI", "Te rugăm să alegi compania de asigurări cu suma aferentă rezultată !");
		return false;
	}
										$("#cvform").append("<input type='hidden' name='suma' value='" + suma + "' />");
	var numcadouri = $(".divcadouri").length;
	var cadou = $( ".cadouadtiv-cv" ).find('span').text();
	if((numcadouri > 0) && (cadou.length == 0) ){
		error("CADOU INCORECT", "Te rugăm să alegi un cadou !");
		return false;
	}
										$("#cvform").append("<input type='hidden' name='cadou' value='" + cadou + "' />");
	var tippasapteh = $(".tippasptehadtiv").find('span').text();
										$("#cvform").append("<input type='hidden' name='tippasapteh' value='" + tippasapteh + "' />");
	var statutjuridic = $("input[name|='cvpstatjur']:checked").val();
										$("#cvform").append("<input type='hidden' name='statutjuridic' value='" + statutjuridic + "' />");
	var nume = $("#cvnume").val();
	var codnumpers = $("#cvcodpers").val();
	var telefon = $("#cvtelefon").val();
	var email = $("#cvemail").val();
	var dataincep = $("#cvdataincep").val();
	if( $("#numesicodpers").css("display") !== "none" ){
		if( nume.length < 2 ){
			error("NUME INCORECT", "Trebuie să completaţi numele !");
			return false;
		}
		if( codnumpers.length < 13 ){
			error("COD PERSONAL INCORECT", "Trebuie să completaţi corect cod personal!");
			return false;
		}
		if( dataincep.length < 1 ){
			error("Data de inceput este incorectă!", "Vă rugăm să completaţi Data de inceput!");
			return false;
		}
		if( telefon.length < 1 ){
			error("Numărul de telefon este incorect!", "Te rugăm să completezi telefonul !");
			return false;
		}
		if( email.length < 1 ){
			error("Adresa E-Mail este incorectă!", "Vă rugăm să completaţi Emailul!");
			return false;
		}
	}
										$("#cvform").append("<input type='hidden' name='nume' value='" + nume + "' />");
										$("#cvform").append("<input type='hidden' name='codnumpers' value='" + codnumpers + "' />");
	var denfirma = $("#cvdenfirma").val();
	var codfirma = $("#cvcodfiscal").val();
	if( $("#denfirmsicod").css("display") !== "none" ){
		if( denfirma.length < 2 ){
			error("DENUMIRE COMPANIE", "Vă rugăm să completaţi corect denumirea companiei !");
			return false;
		}
		if( codfirma.length < 3 ){
			error("COD FISCAL", "Vă rugăm să completaţi corect codul fiscal !");
			return false;
		}
	}
										$("#cvform").append("<input type='hidden' name='denfirma' value='" + denfirma + "' />");
										$("#cvform").append("<input type='hidden' name='codfirma' value='" + codfirma + "' />");
	var dreptposesie = $("#cvdreptfol").val();
										$("#cvform").append("<input type='hidden' name='dreptposesie' value='" + dreptposesie + "' />");
	var nrinmatricdoc = $("#cvnrinmatricdoc").val();
										$("#cvform").append("<input type='hidden' name='nrinmatricdoc' value='" + nrinmatricdoc + "' />");
	var nrinregauto = $("#cvnrinregauto").val();
										$("#cvform").append("<input type='hidden' name='nrinregauto' value='" + nrinregauto + "' />");
	var anprodautov = $("#cvanprodautov").val();
										$("#cvform").append("<input type='hidden' name='anprodautov' value='" + anprodautov + "' />");
	var marcamodel = $("#cvmarcamodel").val();
										$("#cvform").append("<input type='hidden' name='marcamodel' value='" + marcamodel + "' />");
	var tipautov = $("#cvtipautov").val();
										$("#cvform").append("<input type='hidden' name='tipautov' value='" + tipautov + "' />");
	var capacitcil = $("#cvcapacitcil").val();
										$("#cvform").append("<input type='hidden' name='capacitcil' value='" + capacitcil + "' />");
	var masapropie = $("#cvmasapropie").val();
										$("#cvform").append("<input type='hidden' name='masapropie' value='" + masapropie + "' />");
	var masamaxautoriz = $("#cvmasamaxautoriz").val();
										$("#cvform").append("<input type='hidden' name='masamaxautoriz' value='" + masamaxautoriz + "' />");
	var nrlocuri = $("#cvnrlocuri").val();
										$("#cvform").append("<input type='hidden' name='nrlocuri' value='" + nrlocuri + "' />");
	var caroserie = $("#cvcaroserie").val();
										$("#cvform").append("<input type='hidden' name='caroserie' value='" + caroserie + "' />");
	var motor = $("#cvmotor").val();
										$("#cvform").append("<input type='hidden' name='motor' value='" + motor + "' />");
	var dataincep = $("#cvdataincep").val();
	if( dataincep.length != 10 ){
		error("DATA ÎNCEPUT", "Selectaţi data începerii valabilităţii !");
		return false;
	}
										$("#cvform").append("<input type='hidden' name='dataincep' value='" + dataincep + "' />");
	var telefon = $("#cvtelefon").val();
										$("#cvform").append("<input type='hidden' name='telefon' value='" + telefon + "' />");
	var email = $("#cvemail").val();
										$("#cvform").append("<input type='hidden' name='email' value='" + email + "' />");
	var nrcontbanca = $("#cvnrcontbanca").val();
										$("#cvform").append("<input type='hidden' name='nrcontbanca' value='" + nrcontbanca + "' />");
	var denbanca = $("#cvdenbanca").val();
										$("#cvform").append("<input type='hidden' name='denbanca' value='" + denbanca + "' />");
	var codbanca = $("#cvcodbanca").val();
										$("#cvform").append("<input type='hidden' name='codbanca' value='" + codbanca + "' />");
	var tva = $("#cvtva").val();
										$("#cvform").append("<input type='hidden' name='tva' value='" + tva + "' />");
	var modplata = $("#cvmodplata").val();
										$("#cvform").append("<input type='hidden' name='modplata' value='" + modplata + "' />");


	var cvcform = $("#cvform").serialize();
    $( "#final-dialog" ).modal({
  		keyboard: true
	});
	$("#finaldiagtxt").html(load);
	$.ajax({
		type: "POST",
		cache: false,
		url: "https://www.rca.md/finalizare_cv.php",
		data: cvcform
	}).done(function( resp ) {
		if(resp != 1){
		   $("#finaldiagtxt").html("<span style='color: red'>" + resp + "</span>");
		}else{
			$("#finaldiagtxt").html("Felicitări! Comanda Dvs a fost trimisă cu succes. Revenim cu un sunet în cel mai scurt timp. Vă mulţumim!");
			
			if(modplata == 3){
				$.post( "https://www.rca.md/payGen.php", { pay: 'card', asig: 'cv', lang: 'ro' } )
				.done(function( data2 ) {
					if(data2 == 0){
						$("#finaldiagtxt").html("<span style='color: red'>Nu pot procesa CC!</span>");
					}else{
						$('#VictoriaBank').remove();
						$( data2 ).appendTo( "body" );
						setTimeout("$('#VictoriaBank').submit();", 1000);
					}
				});	
			}

		}
	});

}






//casco
function initcascopag(){

			if( $("span#caserr").text().length > 10 ){
				error("FINALIZARE COMANDA CASCO", $("span#caserr").text() );
			}
			if( $("span#cassucc").text().length > 10 ){
					$("#findiagtxt").text($("span#cassucc").text());
		    		$( "#final-dialog" ).modal({
		  				keyboard: true
					});
			}
}
function CalcCasco(){
	var companies 	= {1:'MOLDASIG', 2:'ASITO', 3:'GARANTIE', 4:'MOLDCARGO'};

	var basePrice 	= {
		'USD': ($("#capretUSD").val()>0) ? Math.round($("#capretUSD").val()) : 0,
		'EUR': ($("#capretEUR").val()>0) ? Math.round($("#capretEUR").val()) : 0,
		'MDL': ($("#capretLEI").val()>0) ? Math.round($("#capretLEI").val()) : 0
	};
var result 		= {'MOLDASIG': 0, 'ASITO': 0, 'GARANTIE': 0, 'MOLDCARGO': 0};
var base = 0;
	var ts = $("#catipveh").val();
	var car = $("#camarca").val();
	var car_model = $("#camodelul").val();
	var year = $("#caanprod").val();
var currYear	= 2014;
	var territory = $("#cavalter").val();
	var pack = $("#caacoperirea").val();
	var staj = $("#castagiu").val();

	var DISCOUNT = {
		'MOLDASIG': 0.00,
		'ASITO': 0.00,
		'GARANTIE': 0.00,
		'MOLDCARGO': 0.00
	};

var prices	=
{ 2014:
{1: /* Moldasig */
{
1: {1: 5.4, 2: 4.5, 3: 4.2},
2: 4.5,
3: 0,
4: 4.5,
5: 0,
6: 0
},
2: /* Asito */
{
1: 4.84,
2: 4.84,
3: 2.3,
4: 2.3,
5: 2.3,
6: 5.5
},
3: /* Garantie */
{
1: 4.4,
2: 4.5,
3: 2.3,
4: 1.3,
5: 2.3,
6: 0
},
4: /* Moldcargo */
{
1: 4.5,
2: 2.5,
3: 2.5,
4: 2.6,
5: 2.2,
6: 0
}
},
/************ End 2014 ************/
2013:
{1: /* Moldasig */
{
1: {1: 5.4, 2: 4.5, 3: 4.2},
2: 4.5,
3: 0,
4: 4.5,
5: 0,
6: 0
},
2: /* Asito */
{
1: 4.84,
2: 4.84,
3: 2.3,
4: 2.3,
5: 2.3,
6: 5.5
},
3: /* Garantie */
{
1: 4.4,
2: 4.5,
3: 2.3,
4: 1.3,
5: 2.3,
6: 0
},
4: /* Moldcargo */
{
1: 4.5,
2: 2.5,
3: 2.5,
4: 2.6,
5: 2.2,
6: 0
}
},
/************ End 2013 ************/
2012:
{1: /* Moldasig */
{
1: {1: 5.4, 2: 4.5, 3: 4.2},
2: 4.5,
3: 0,
4: 4.5,
5: 0,
6: 0
},
2: /* Asito */
{
1: 4.84,
2: 4.84,
3: 2.3,
4: 2.3,
5: 2.3,
6: 5.5
},
3: /* Garantie */
{
1: 4.4,
2: 4.5,
3: 2.3,
4: 1.3,
5: 2.3,
6: 0
},
4: /* Moldcargo */
{
1: 4.5,
2: 2.5,
3: 2.5,
4: 2.6,
5: 2.2,
6: 0
}
},
/************ End 2012 ************/
2011:
{1: /* Moldasig */
{
1: {1: 5.7, 2: 4.8, 3: 4.6},
2: 5,
3: 0,
4: 5,
5: 0,
6: 0
},
2: /* Asito */
{
1: 4.84,
2: 4.84,
3: 2.3,
4: 2.3,
5: 2.3,
6: 5.5
},
3: /* Garantie */
{
1: 4.4,
2: 4.5,
3: 2.3,
4: 1.3,
5: 2.3,
6: 0
},
4: /* Moldcargo */
{
1: 4.5,
2: 2.5,
3: 2.5,
4: 2.6,
5: 2.2,
6: 0
}
},
/************ End 2011 ************/
2010:
{1: /* Moldasig */
{
1: {1: 5.7, 2: 4.8, 3: 4.6},
2: 5,
3: 0,
4: 5,
5: 0,
6: 0
},
2: /* Asito */
{
1: 4.84,
2: 4.84,
3: 2.3,
4: 2.3,
5: 2.3,
6: 5.5
},
3: /* Garantie */
{
1: 4.6,
2: 4.7,
3: 2.5,
4: 1.5,
5: 2.5,
6: 0
},
4: /* Moldcargo */
{
1: 4.5,
2: 2.5,
3: 2.5,
4: 2.6,
5: 2.2,
6: 0
}
},
/************ End 2010 ************/
2009:
{1: /* Moldasig */
{
1: {1: 5.7, 2: 4.8, 3: 4.6},
2: 5,
3: 0,
4: 5,
5: 0,
6: 0
},
2: /* Asito */
{
1: 5.06,
2: 5.06,
3: 2.42,
4: 2.42,
5: 2.42,
6: 5.83
},
3: /* Garantie */
{
1: 4.6,
2: 4.7,
3: 2.5,
4: 1.5,
5: 2.5,
6: 0
},
4: /* Moldcargo */
{
1: 4.95,
2: 2.75,
3: 2.75,
4: 2.86,
5: 2.42,
6: 0
}
},
/************ End 2009 ************/
2008:
{1: /* Moldasig */
{
1: {1: 6, 2: 5.4, 3: 5.2},
2: 0,
3: 0,
4: 0,
5: 0,
6: 0
},
2: /* Asito */
{
1: 5.06,
2: 5.06,
3: 2.42,
4: 2.42,
5: 2.42,
6: 5.83
},
3: /* Garantie */
{
1: 4.8,
2: 4.9,
3: 2.6,
4: 1.6,
5: 2.6,
6: 0
},
4: /* Moldcargo */
{
1: 5.2,
2: 2.9,
3: 2.9,
4: 3,
5: 2.53,
6: 0
}
},
/************ End 2008 ************/
2007:
{1: /* Moldasig */
{
1: {1: 6, 2: 5.4, 3: 5.2},
2: 0,
3: 0,
4: 0,
5: 0,
6: 0
},
2: /* Asito */
{
1: 5.06,
2: 5.06,
3: 2.42,
4: 2.42,
5: 2.42,
6: 5.83
},
3: /* Garantie */
{
1: 4.8,
2: 4.9,
3: 2.6,
4: 1.6,
5: 2.6,
6: 0
},
4: /* Moldcargo */
{
1: 5.4,
2: 3,
3: 3,
4: 3.15,
5: 2.64,
6: 0
}
},
/************ End 2007 ************/
2006:
{1: /* Moldasig */
{
1: {1: 6.5, 2: 6.4, 3: 6.1},
2: 0,
3: 0,
4: 0,
5: 0,
6: 0
},
2: /* Asito */
{
1: 5.06,
2: 5.06,
3: 2.42,
4: 2.42,
5: 2.42,
6: 5.83
},
3: /* Garantie */
{
1: 5.0,
2: 5.1,
3: 2.7,
4: 1.7,
5: 2.7,
6: 0
},
4: /* Moldcargo */
{
1: 5.6,
2: 3.2,
3: 3.2,
4: 3.25,
5: 2.75,
6: 0
}
},
/************ End 2006 ************/
2005:
{1: /* Moldasig */
{
1: {1: 6.5, 2: 6.4, 3: 6.1},
2: 0,
3: 0,
4: 0,
5: 0,
6: 0
},
2: /* Asito */
{
1: 5.06,
2: 5.06,
3: 2.42,
4: 2.42,
5: 2.42,
6: 5.83
},
3: /* Garantie */
{
1: 5.0,
2: 5.1,
3: 2.7,
4: 1.7,
5: 2.7,
6: 0
},
4: /* Moldcargo */
{
1: 5.6,
2: 3.2,
3: 3.2,
4: 3.25,
5: 2.75,
6: 0
}
},
/************ End 2005 ************/
2004:
{1: /* Moldasig */
{
1: 0,
2: 0,
3: 0,
4: 0,
5: 0,
6: 0
},
2: /* Asito */
{
1: 5.39,
2: 5.39,
3: 2.53,
4: 2.53,
5: 2.53,
6: 6.16
},
3: /* Garantie */
{
1: 5,
2: 2.7,
3: 2.7,
4: 2.7,
5: 2.7,
6: 0
},
4: /* Moldcargo */
{
1: 5.6,
2: 3.2,
3: 3.2,
4: 3.25,
5: 2.75,
6: 0
}
},
/************ End 2004 ************/
2003:
{1: /* Moldasig */
{
1: 0,
2: 0,
3: 0,
4: 0,
5: 0,
6: 0
},
2: /* Asito */
{
1: 5.39,
2: 5.39,
3: 2.53,
4: 2.53,
5: 2.53,
6: 6.16
},
3: /* Garantie */
{
1: 5,
2: 2.7,
3: 2.7,
4: 2.7,
5: 2.7,
6: 0
},
4: /* Moldcargo */
{
1: 0,
2: 0,
3: 0,
4: 0,
5: 0,
6: 0
}
}
}
/* Ущерб */
var pricesDamage=	{
2014:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 4		/* Moldcargo */
},
2013:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 4		/* Moldcargo */
},
2012:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 4		/* Moldcargo */
},
2011:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 4		/* Moldcargo */
},
2010:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 4		/* Moldcargo */
},
2009:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 4.4		/* Moldcargo */
},
2008:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 4.6		/* Moldcargo */
},
2007:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 4.8		/* Moldcargo */
},
2006:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 5		/* Moldcargo */
},
2005:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 5		/* Moldcargo */
},
2004:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 5		/* Moldcargo */
},
2003:
{
1: 0,		/* Moldasig */
2: 0.85,	/* Asito */
3: 1,		/* Garantie */
4: 0		/* Moldcargo */
},
}


result['MOLDASIG'] = 0;
if (pack == 1) {
var tmpMold = prices[year][1];
tmpMold = tmpMold[ts];
if( Object.prototype.toString.call( tmpMold ) === '[object Object]' ) {
if (basePrice['EUR']  < 10000) { tmpMold = tmpMold[1]; }
if (basePrice['EUR'] >= 10000 && basePrice['EUR'] < 30000) { tmpMold = tmpMold[2]; }
if (basePrice['EUR'] >= 30000) { tmpMold = tmpMold[3]; }
}
result['MOLDASIG'] = tmpMold / 100;if (territory == 1) {
result['MOLDASIG'] = result['MOLDASIG'] * 0.8; // decrease 0.2
}
if (staj == 0) {
result['MOLDASIG'] = result['MOLDASIG'] * 1.2;  // increase 0.2
}}
else if (pack == 2) {
result['MOLDASIG']  = pricesDamage[year][1] / 100;
}
result['MOLDASIG'] 	= result['MOLDASIG'] - (result['MOLDASIG']*DISCOUNT['MOLDASIG']);
$("#causd_moldasig").text(Math.round(result['MOLDASIG'] * basePrice['USD']));
$("#caeur_moldasig").text(Math.round(result['MOLDASIG'] * basePrice['EUR']));
$("#calei_moldasig").text(Math.round(result['MOLDASIG'] * basePrice['MDL']));




result['ASITO'] = 0;
/*if (pack == 1) { */
var tmpAsito = prices[year][2];
tmpAsito = tmpAsito[ts] / 100;
result['ASITO'] = tmpAsito;/*}*/if (pack == 2) {
result['ASITO'] = result['ASITO'] * pricesDamage[year][2];
}
result['ASITO'] 	= result['ASITO'] - (result['ASITO']*DISCOUNT['ASITO']);
$("#causd_asito").text(Math.round(result['ASITO'] * basePrice['USD']));
$("#caeur_asito").text(Math.round(result['ASITO'] * basePrice['EUR']));
$("#calei_asito").text(Math.round(result['ASITO'] * basePrice['MDL']));




result['GARANTIE'] = 0;
/*if (pack == 1) {*/
var tmpGarant = prices[year][3];
tmpGarant = tmpGarant[ts] / 100;
result['GARANTIE'] = tmpGarant;if (ts == 1 && territory == 1) {
result['GARANTIE'] = result['GARANTIE'] * 0.9; // decrease 0.1
}
/*}*/
if (pack == 2) {
result['GARANTIE'] = result['GARANTIE'] * pricesDamage[year][3];
}
result['GARANTIE'] 	= result['GARANTIE'] - (result['GARANTIE']*DISCOUNT['GARANTIE']);
$("#causd_garantie").text(Math.round(result['GARANTIE'] * basePrice['USD']));
$("#caeur_garantie").text(Math.round(result['GARANTIE'] * basePrice['EUR']));
$("#calei_garantie").text(Math.round(result['GARANTIE'] * basePrice['MDL']));






result['MOLDCARGO'] = 0;
if (pack == 1) {
var tmpMcargo = prices[year][4];if (ts != 1) {
$("#moldcargo-info").fadeIn(100);
}
else {
$("#moldcargo-info").fadeOut(100);if (basePrice['EUR'] > 25000 && (currYear-year) <= 5) {
tmpMcargo[ts] = 4.3;
}}result['MOLDCARGO'] = tmpMcargo[ts] / 100;}
else if (pack == 2) {
result['MOLDCARGO'] = pricesDamage[year][4] / 100;
}
result['MOLDCARGO'] = result['MOLDCARGO'] - (result['MOLDCARGO']*DISCOUNT['MOLDCARGO']);
$("#causd_moldcargo").text(Math.round(result['MOLDCARGO'] * basePrice['USD']));
$("#caeur_moldcargo").text(Math.round(result['MOLDCARGO'] * basePrice['EUR']));
$("#calei_moldcargo").text(Math.round(result['MOLDCARGO'] * basePrice['MDL']));

if( $("input[name|='companiaasig']:checked").val() != undefined ){
	compania = $("input[name|='companiaasig']:checked").val();
	sumanoua = $("#calei_" + compania).text();
	if(sumanoua != sumaveche){
		Cadouri_CASCO(compania);
		sumaveche = sumanoua;
	}
	if(compania !== companiaveche){
		Cadouri_CASCO(compania);
		companiaveche = compania;
	}
}

setTimeout("CalcCasco()", 200); }

function Cadouri_CASCO(v){
	$("#boxcadouri").html(load);
	$.ajax({
		type: "POST",
		url: "https://www.rca.md/cadouri.php",
		data: { lang: 'ro', porecla: v, suma: $("#calei_" + v).text(), tip: 'cv' }
	}).done(function( resp ) {
		if(resp.length < 10){
			$("#boxcadouri").html("Pentru această sumă nu sunt cadouri disponibile !");
			$("#cadoulid, #cadoultxt").remove();
		}else{
			$("#boxcadouri").html(resp);
		}
	});
}



function CalcUSD(){
	var frmUSD = $("#capretUSD").val();
	if(USD < EUR){
		var PAR = USD/EUR;
	}else{
		var PAR = EUR/USD;
	}
	$("#capretEUR").val( Math.round(frmUSD * PAR) );
	$("#capretLEI").val( Math.round(frmUSD * USD) );

}
function CalcEUR(){
	var frmEUR = $("#capretEUR").val();
	if( EUR < USD){
		var PAR = USD/EUR;
	}else{
		var PAR = EUR/USD;
	}
	$("#capretUSD").val( Math.round(frmEUR * PAR) );
	$("#capretLEI").val( Math.round(frmEUR * EUR) );

}
function CalcLEI(){
	var frmLEI = $("#capretLEI").val();
	$("#capretUSD").val( Math.round(frmLEI / USD) );
	$("#capretEUR").val( Math.round(frmLEI / EUR) );

}

function SendCasco(){
    $("#sumaUSD, #sumaEUR, #sumaLEI").remove();
	if( $("#termeni").prop('checked') == false ){
		error("EROARE TERMENI ŞI CONDIŢII", " Trebuie să acceptaţi termenii şi condiţiile de utilizare ale acestui web-site !");
		return false;
	}
	var companiaasig = $("input[name|='companiaasig']:checked").val();
	var suma = $("input[name|='companiaasig']:checked").val(); suma = $("#calei_" + suma).text();
	if(suma == 0){
		error("COMPANIA ASIGURĂRI", "Te rugăm să alegi compania de asigurări cu suma aferentă rezultată !");
		return false;
	}
/*	var numcadouri = $(".divcadouri").length;
	var cadou = $( ".cadouadtiv-casco" ).find('span').text();
	if((numcadouri > 0) && (cadou.length == 0) ){
		error("CADOU INCORECT", "Te rugăm să alegi un cadou !");
		return false;
	}*/
	var nume = $("#casnume").val();
	if( nume.length < 2 ){
		error("NUME INCORECT", "Te rugăm să completezi numele !");
		return false;
	}
	var telefon = $("#castelefon").val();
	if( telefon.length < 3 ){
		error("TELEFON", "Te rugăm să completezi telefonul !");
		return false;
	}
	var email = $("#casemail").val();
	var txt = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!txt.test(email)) {
		error("EMAIL INCORECT", "Adresa E-Mail este incorectă!");
		return false;
	}
	var sumaUSD = $("input[name|='companiaasig']:checked").val(); sumaUSD = $("#causd_" + sumaUSD).text();
	var sumaEUR = $("input[name|='companiaasig']:checked").val(); sumaEUR = $("#caeur_" + sumaEUR).text();
	var sumaLEI = $("input[name|='companiaasig']:checked").val(); sumaLEI = $("#calei_" + sumaLEI).text();
	$("#cascofrm").append("<input id='sumaUSD' name='sumaUSD' type='hidden' value='" + sumaUSD + "' />\n<input id='sumaEUR' name='sumaEUR' type='hidden' value='" + sumaEUR + "' />\n<input id='sumaLEI' name='sumaLEI' type='hidden' value='" + sumaLEI + "' />\n");
	$("#cascofrm").submit();
}

function initac(){
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		    var rcacal = $('#incepvalabil').datepicker({
		    	language: 'ro',
		    	format: 'dd/mm/yyyy',
  				onRender: function(date) {
    				return date.valueOf() < now.valueOf() ? 'disabled' : '';
  				}
  			}).on('changeDate', function(ev) { rcacal.datepicker('hide'); });


			if( $("span#caserr").text().length > 10 ){
				error("FINALIZARE COMANDA ASIGURARE MEDICALA", $("span#caserr").text() );
			}
			if( $("span#cassucc").text().length > 10 ){
					$("#findiagtxt").text($("span#cassucc").text());
		    		$( "#final-dialog" ).modal({
		  				keyboard: true
					});
			}


}

function SendAC(){
	if( $("#termeni").prop('checked') == false ){
		error("EROARE TERMENI ŞI CONDIŢII", " Trebuie să acceptaţi termenii şi condiţiile de utilizare ale acestui web-site !");
		ScrollRoID('termeni');
		return false;
	}
	var nume = $("#nume").val();
	if( nume.length < 2 ){
		error("NUME INCORECT", "Te rugăm să completezi numele !");
		ScrollRoID('nume');
		return false;
	}
	var prenume = $("#prenume").val();
	if( prenume.length < 2 ){
		error("PRENUME INCORECT", "Te rugăm să completezi prenumele !");
		ScrollRoID('prenume');
		return false;
	}
	var dtan = $("#dnastere").val();
	if( dtan.length < 5 ){
		error("DATA NAŞTERII", "Te rugăm să completezi data naşterii !");
		ScrollRoID('dnastere');
		return false;
	}
	var cnp = $("#cnp").val();
	if( cnp.length < 13 ){
		error("COD PERSONAL", "Trebuie să completaţi corect cod personal!");
		ScrollRoID('cnp');
		return false;
	}
	var pasaport = $("#snpass").val();
	if( pasaport.length < 5 ){
		error("PAŞAPORT", "Te rugăm să completezi seria şi numărul paşaportului !");
		ScrollRoID('snpass');
		return false;
	}
	var telefon = $("#telefon").val();
	if( telefon.length < 5 ){
		error("TELEFON", "Te rugăm să completezi telefonul !");
		ScrollRoID('telefon');
		return false;
	}
	var email = $("#email").val();
	var txt = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!txt.test(email)) {
		error("EMAIL INCORECT", "Adresa E-Mail este incorectă!");
		ScrollRoID('email');
		return false;
	}
	var incepvalabil = $("#incepvalabil").val();
	if( incepvalabil.length < 10 ){
		error("DATA ÎNCEPERE VALABILITATE", "Vă rugăm să alegeţi data de la care asigurarea intră în vigoare !");
		ScrollRoID('incepvalabil');
		return false;
	}
	var nrzile = $("#nrzile").val();
	if( isNaN(nrzile) || (nrzile.length == 0) ){
		error("ZILE", "Te rugăm să completezi numărul de zile !");
		ScrollRoID('nrzile');
		return false;
	}
	var destcal = $("#destcal").val();
	if( destcal == 0 ){
		error("DESTINAţIA CăLăTORIEI", "Alege destinaţia călătoriei !");
		ScrollRoID('destcal');
		return false;
	}
	$("#acfrm").submit();
}
