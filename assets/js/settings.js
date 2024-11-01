var $ = jQuery;

jQuery(document).ready(function ($) {

	$(".custom-select").each(function () {
		var classes = $(this).attr("class"),
			id = $(this).attr("id"),
			name = $(this).attr("name");
		var template = '<div class="' + classes + '">';
		template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
		template += '<div class="custom-options">';
		$(this).find("option").each(function () {
			template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '" data-crm="' + $(this).attr("data-crm") + '" data-billing-model="' + $(this).attr("data-billing-model") + '" >' + $(this).html() + '</span>';
		});
		template += '</div></div>';

		$(this).wrap('<div class="custom-select-wrapper"></div>');
		$(this).hide();
		$(this).after(template);
	});
	$(".custom-option:first-of-type").hover(function () {
		$(this).parents(".custom-options").addClass("option-hover");
	}, function () {
		$(this).parents(".custom-options").removeClass("option-hover");
	});
	$(".custom-select-trigger").on("click", function () {
//		$('html').one('click', function () {
//			$(".custom-select").removeClass("opened");
//		});
		$(this).parents(".custom-select").toggleClass("opened");
		event.stopPropagation();
	});
	$(".custom-option").on("click", function () {
		var id = $(this).parents(".custom-select-wrapper").find("select").attr("id");
		$(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
		$(this).parents(".custom-options").find(".custom-option").removeClass("selection");
		$(this).addClass("selection");
		$(this).parents(".custom-select").removeClass("opened");
		$(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
		
		$('#' + id).trigger("click");
	});
	
	
	
	//// Form Validation 
	$('#submit_settings').click(function(){
		
		$("#unify_settings_form_post").validate({ 
			ignore: [],
			rules: {
				title: {
					required:  true
				},
				description: {
					required: true
				},
				paypal_payment_title: {
					required: function (element) {
						if($('#paypal_enabled').is(':checked') && $('select[name=connection]').find(":selected").attr('data-crm') == 'limelight'){
							return true;
						}
						if($('#paypal_enabled').is(':checked') && $('select[name=connection]').find(":selected").attr('data-crm') == 'sublytics'){
							return true;
						}						
						return false;
					}
				},
				paypal_payment_description: {
					required: function (element) {
						if($('#paypal_enabled').is(':checked') && $('select[name=connection]').find(":selected").attr('data-crm') == 'limelight'){
							return true;
						}
						return false;
					}
				},
			},
			messages :{
				title: {
					required: 'Display Title is a required field.'
				},
				description: {
					required: 'Description is a required field.'
				},
				connection: {
					required: 'Connection is a required field.'
				},
				shipping_product_id: {
					required: 'CRM Product ID is a required field.'
				},
				shipping_product_offer_id: {
					required: 'CRM Offer ID is a required field.'
				},
				shipping_product_billing_id: {
					required: 'CRM Billing ID is a required field.'
				},
				paypal_payment_title: {
					required: 'Title is a required field.'
				},
				paypal_payment_description: {
					required: 'Description is a required field.'
				}
			},
			errorClass:'text-danger',
			errorPlacement: function(error, element) {
				//Custom position: connection
				if (element.attr("name") == "connection") 
				{
					$("#connection_error").append(error);
				} else
				{
					error.insertAfter(element);
				}
				
			},
		});
		
		if($("#unify_settings_form_post").valid()){
			$('#unify_settings_form_post').submit();
			return true;
		}
		return false;
	});

	$('#submit_paypal_settings').click(function(){
		
		$("#unify_paypal_settings_form_post").validate({ // initialize the plugin
			ignore: [],
			rules: {
				paypal_payment_title: {
					required: function (element) {
						if($('#paypal_enabled').is(':checked') && $('select[name=connection]').find(":selected").attr('data-crm') == 'limelight'){
							return true;
						}
						if($('#paypal_enabled').is(':checked') && $('select[name=connection]').find(":selected").attr('data-crm') == 'sublytics'){
							return true;
						}
						return false;
					}
				},
				paypal_payment_description: {
					required: function (element) {
						if($('#paypal_enabled').is(':checked') && $('select[name=connection]').find(":selected").attr('data-crm') == 'limelight'){
							return true;
						}
						return false;
					}
				},
			},
			messages :{
				paypal_payment_title: {
					required: 'Title is a required field.'
				},
				paypal_payment_description: {
					required: 'Description is a required field.'
				}
			},
			errorClass:'text-danger',
			errorPlacement: function(error, element) {
				if (element.attr("name") == "connection") 
				{
					$("#connection_error").append(error);
				} else
				{
					error.insertAfter(element);
				}
				
			},
		});
		
		if($("#unify_paypal_settings_form_post").valid()){
			$('#unify_paypal_settings_form_post').submit();
			return true;
		}
		return false;
	});
	
	$(".payPalSettings").click(function(){
		$(".payPalSettings-modal").toggle();
	});

	$('.close_pop').click(function(){
		$('.payPalSettings-modal').css('display','none');	
	});
	// Custom Js
	onLoadFirst();
	activatePaymentMethod();

	// On Change connection
	$('select[name=connection]').on('click', function () {
		$('#connection_val').val($(this).find(":selected").val());			
		showAdditionalFeilds();
		onChangeConn();
	});
	
	
	// On change shipping
	$('select[name=shipment_price_settings]').on('click', function () {
		$('#shipment_price_settings_val').val($(this).find(":selected").val());	
		showShippingConfig();
	});

	$('#paypal_button_size').on('click', function () {
		$('#paypal_button_size_selected').val($(this).find(":selected").val());		
	});
	
	$('select[name=paypal_button_color]').on('click', function () {
		$('#paypal_button_color_selected').val($(this).find(":selected").val());		
	});

});

jQuery(document).on("click", function (e) {
	
	if (jQuery(e.target).is(".custom-select") == false) {
		jQuery(".custom-select").removeClass("opened");
	}

});

var enable_multiple_order = $('#shipment_price_settings').val();
	if(enable_multiple_order == 2){
		$('#enable_multiple_shipping').attr("checked", "checked");
	}
	$(document).on("change", "#enable_multiple_shipping", function () {
        if(this.checked) {
            $("#shipment_price_settings").val("2");
            $(this).attr("checked", "checked");
            $("#paypalCheckout").css("display","none");
        }else{
        	$("#shipment_price_settings").val("0");
        	$(this).removeAttr("checked");
        	$("#paypalCheckout").css("display","block");
        }
              
    });

  


    
function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}

function onLoadFirst(){
	$('#shipment_price_settings_div').hide();
	$('#shipping_product_div').hide();
	
	highlightDropDownConnection();
	showAdditionalFeilds();
	
	if($('#shipment_price_settings_val').val() != ''){		
		$('select[name=shipment_price_settings]').val($('#shipment_price_settings_val').val());			
	}else{
		$('#shipment_price_settings_val').val($('select[name=shipment_price_settings]').find(":selected").val());	
	}
	highlightDropDownShipping();
	showShippingConfig();
}

function highlightDropDownConnection(){
	$('select[name=connection]').next().find(".custom-option").each(function () {
		var atrFil = $(this).attr("data-value");
		if (atrFil == $('#connection_val').val()) {
			$(this).trigger('click');
		}
	});
}

function highlightDropDownShipping(){
	$('select[name=shipment_price_settings]').next().find(".custom-option").each(function () {
		var atrFil = $(this).attr("data-value");
		if (atrFil == $('#shipment_price_settings_val').val()) {
			$(this).trigger('click');
		}
	});
}

function showAdditionalFeilds(){
	
	//if ($('select[name=connection]').find(":selected").attr('data-crm') == 'limelight') {
	if ($('#connection').val() == 'limelight') {
		$('#shipment_price_settings_div').show();
	} else {
		$('#shipment_price_settings_div').hide();
	}	
}

function showShippingConfig(){
	if($('#connection').val() == 'limelight' 
		&& $('#shipment_price_settings_val').val() == 1){	
		$('#shipping_product_div').show();
		$("#paypalCheckout").css("display","block");
		if($('select[name=connection]').find(":selected").attr('data-billing-model') == 1){
			$('#shipping_product_id_div').removeClass('col-sm-12').addClass('col-sm-4');
			$('.shipping_product_offer_div').show();			
		}else{
			$('.shipping_product_offer_div').hide();
			$('#shipping_product_id_div').addClass('col-sm-12').removeClass('col-sm-4');
		}
	}
	else if($('#connection').val() == 'sublytics' 
		&& $('#shipment_price_settings_val').val() == 1){	
		$('#shipping_product_div').show();
		$("#paypalCheckout").css("display","block");
		if($('select[name=connection]').find(":selected").attr('data-billing-model') == 1){
			$('#shipping_product_id_div').removeClass('col-sm-12').addClass('col-sm-4');
			$('.shipping_product_offer_div').show();			
		}else{
			$('.shipping_product_offer_div').hide();
			$('#shipping_product_id_div').addClass('col-sm-12').removeClass('col-sm-4');
		}
	}
	
	else{
		$("#paypalCheckout").css("display","none");
		$('#paypal_enabled').prop('checked', false);
		$(".payPalSettings").addClass('disablePayPalSettings');
		$('#shipping_product_div').hide();
	}
}

function onChangeConn(){	
	$('#shipment_price_settings_val').val('');
	$('#shipping_product_div').hide();
	$('.shipping_product_offer_div').hide();
	$('#shipping_product_id_div').addClass('col-sm-12').removeClass('col-sm-4');
	highlightDropDownShipping();
	$('#shipping_product_id').val('');
	$('#shipping_product_offer_id').val('');
	$('#shipping_product_billing_id').val('');
}

function activatePaymentMethod(){
		if ($("#paypal_enabled").is(':checked')){
			$(".payPalSettings").removeClass('disablePayPalSettings');
		}
		$('#paypal_enabled').change(function() {
	        if(this.checked) {
	          $(".payPalSettings").removeClass('disablePayPalSettings');
	        }else{
	          $(".payPalSettings").addClass('disablePayPalSettings');
	        }    
    	});
}

function copyToClipBoard() {
  var copyText = document.getElementById("unify_license_key");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
}

/////////////////////////////////////////////////////////////

