var $ = jQuery;
jQuery(document).ready(function ($) {
	//// ############## CUSTOM DROPDOWN UI JS STARTS ################### //
	$(".custom-select").each(function () {
		var classes = $(this).attr("class"),
			id = $(this).attr("id"),
			name = $(this).attr("name");
		var template = '<div class="' + classes + '">';
		template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
		template += '<div class="custom-options">';
		$(this).find("option").each(function () {
			template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
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
	//// ############## CUSTOM DROPDOWN UI JS ENDS ################### //

	// Custom Js
	$('select[name=unify_connection_crm_select]').on('click', function () {
		$('#unify_connection_crm').val($(this).find(":selected").val());
		$('#unify_connection_crm').trigger('blur');
		showRequiredStar($(this).find(":selected").val());
		var validation_ids = ["unify_connection_crm_select","unify_connection_api_password","unify_connection_campaign_id","post_title","unify_connection_api_username"];
		validation_ids.forEach(function(id) {
    		$("#"+id+"-error").css("display","none");
		});
		/**
		 * Dynamically calling UI methods
		 */
		if ($(this).find(":selected").val() != '') {
			eval($(this).find(":selected").val() + 'UiConfig()')
		}
	});




	if ($('#unify_connection_crm').val() != '') {
		$('select[name=unify_connection_crm_select]').next(".custom-select").find(".custom-select-trigger").text($('#unify_connection_crm').attr('data-txt'));
		

		$(".custom-option").each(function() {
		var atrFil = $(this).attr("data-value");
		if (atrFil == $('#unify_connection_crm').val()) {
		$(this).trigger('click');
		}
		});
	}
	
	$('select[name=unify_connection_offer_model_select]').on('click', function () {
		$('#unify_connection_offer_model').val($(this).find(":selected").val());
	});

	$('select[name=unify_order_note_enable]').on('click', function () {
		$('#unify_order_note').val($(this).find(":selected").val());
	});

	//// ############## On Load setting dropdwon val STARTS ################### //
	if ($('#unify_connection_crm').val() == 'limelight') {
		$('#offer_model_ent_div').show();
		$('#unify_connection_endpoint_div').show();
	}

	$("#post-stat-action").hide();
	$("#post-stat").click(function () {
		$(this).next('#post-stat-action').toggle();
	});

	$('.selct-stat-val').click(function () {
		var stat = $(this).attr('val');
		$('#post_status').val(stat);
		$('#post-stat').html(stat.charAt(0).toUpperCase() + stat.slice(1));
	});

//	// *********** Hide connection message
//	$('.cross-position').click(function () {
//		$(this).parents('div .container-fluid').fadeOut();
//	});


	$('#validate_connection').click(function(){
		var valid = true;
		var con = $('#unify_connection_crm').val();
		if (con === '') {
        	valid = false;
    	}
    	if(valid){
			var v = valid_fields(con);
        	if (v) {
          		ajax_to_validate_connection(con,num=1);
        	}	
       	}	
	});

	function ajax_to_validate_connection(con,num){
	$.ajax({
		beforeSend: function () {
			$('.overlayDiv').show();
		},
		data: {
			'action': 'validate_crm_connection',
			'Username': $("#unify_connection_api_username").val(),
			'Password': $("#unify_connection_api_password").val(),
			'Endpoint' : $("#unify_connection_endpoint").val(),
			'Connection' : con
		},
		dataType: 'json',
		type: 'POST',
		url: ajaxurl,
		success: function (response) {
			var msg = response.msg;
			var color = "";
			var response_code = response.status;
			if(con == 'response'){
				if(response_code == 1){
					msg = msg+" <i class='fa fa-check-circle' aria-hidden='true'></i>";color = "green";
					if(num==2){
					$('#unify_connections_post').submit();
				}
				}else{
					msg = msg+" <i class='fa fa-exclamation-triangle' aria-hidden='true'></i>";color = "red";
				}

				
			}else{
				if(response_code == 0){
					msg = msg+" <i class='fa fa-exclamation-triangle' aria-hidden='true'></i>";color = "red";
				}else{
					msg = msg+" <i class='fa fa-check-circle' aria-hidden='true'></i>";color = "green";	
					if(num==2){
					$('#unify_connections_post').submit();
				}
				}
				
			}
			$(".validated_msg").html(msg);$(".validated_msg").css("color",color);
			$(".validated_msg").css("display",'inline-block');
			$('#validated_msg').delay(5000).fadeOut('slow');
		},
		error: function (response){
			color = "red";msg = "Invalid Credential <i class='fa fa-exclamation-triangle' aria-hidden='true'></i>";
			$(".validated_msg").html(msg);
			$(".validated_msg").css("color",color);
			$(".validated_msg").css("display",'inline-block');
			$('.validated_msg').delay(5000).fadeOut('slow');
		},
		complete: function (response) {
      		$('.overlayDiv').hide(); 
     	}
	});
}



function valid_fields(con) {
    var valid = true;
    var message = '';
    var connection_endpoint = $("#unify_connection_endpoint").val();
    var connection_api_username = $("#unify_connection_api_username").val();
    var connection_api_password = $("#unify_connection_api_password").val(); 

    if(con == 'limelight'){
    	if (connection_endpoint === '') {
        	valid = false;
        	$("#unify_connection_endpoint-error").remove();
        	message = "API Endpoint";
        	$("#unify_connection_endpoint").after('<label id="unify_connection_endpoint-error" class="text-danger" for="unify_connection_endpoint">'+message+' is a required field.</label>');
     
    	}else{
	    	$("#unify_connection_endpoint-error").remove();
    		valid = true;
	    }

    	if (connection_api_password === '') {
	        valid = false;
	        $("#unify_connection_api_password-error").remove();
	        message = "API Password";
	        $("#unify_connection_api_password").after('<label id="unify_connection_api_password-error" class="text-danger" for="unify_connection_api_password">'+message+' is a required field.</label>');
	    }else{
	    	$("#unify_connection_endpoint-error").remove();
    		valid = true;
	    }
    } 
    
    if (connection_api_username === '') {
        valid = false;
        $("#unify_connection_api_username-error").remove();
        message = (con == 'limelight')?"API Username":'API key';
        $("#unify_connection_api_username").after('<label id="unify_connection_api_username-error" class="text-danger" for="unify_connection_api_username">'+message+' is a required field.</label>');
    }else{
    	$("#unify_connection_api_username-error").remove();
    	valid = true;

    }
      
    if (valid) {
        return true;
    } else {
        return false;
    }
}


	// ######### ON SUBMIT OF FORM JS VALIDATION STARTS #########//
	
	$('#submit_connection').click(function(){
		
		$("#unify_connections_post").validate({ // initialize the plugin
			ignore: [],
			rules: {
				post_title: {
					required: true
				},
				unify_connection_campaign_id: {
					required: true
				},
				unify_connection_shipping_id: {
					required: function (element) {
						if($('#unify_connection_crm_select').val() == 'limelight'){
							return true;
						}
						return false;
					}
				},
				unify_sublytics_connection_id: {
					required: function (element) {
						if($('#unify_connection_crm_select').val() == 'sublytics'){
							return true;
						}
						return false;
					}
				},
				unify_connection_crm: {
					required: true
				},
				unify_connection_endpoint: {
					required: function (element) {
						if($('#unify_connection_crm_select').val() == 'limelight'){
							return true;
						}
						return false;
					}
				},
				unify_connection_api_username: {
					required: true
				},
				unify_connection_api_password: {
					required: function (element) {
						if($('#unify_connection_crm_select').val() == 'response'){
							return false;
						}
						return true;
					}
				},
			},
			messages :{
				post_title: {
					required: 'Configuration Name is a required field.'
				},
				unify_connection_campaign_id: {
                                        required:function(element) {
                                         if($('#unify_connection_crm_select').val() == 'response'){
											return 'Site ID is a required field.';
									}
                                            return 'Campaign ID is a required field.';
                                        } 
				},
				unify_connection_shipping_id: {
                                        required:function(element) {
                                         if($('#unify_connection_crm_select').val() == 'limelight')
                                         {
											return 'Shipping ID is a required field.';
										}     
                                    } 
				},
				unify_connection_crm: {
					required: 'CRM is a required field.'
				},
				unify_connection_endpoint: {
					required: 'API Endpoint is a required field.'
				},
				unify_connection_api_username: {
					required:function(element) {
                                         if($('#unify_connection_crm_select').val() == 'response'){
							return 'API key is a required field.';
						}
                                            return 'API Username is a required field.';
                                        } 
				},
				unify_connection_api_password: {
					required: 'API Password is a required field.'
				}
			},
			errorClass:'text-danger',
		});
		
		if($("#unify_connections_post").valid()){
			var connName = $('#unify_connection_crm_select').val();
			(connName == 'konnektive')?($('#unify_connections_post').submit()):ajax_to_validate_connection(connName,num=2);
		}
		return false;
	});

	// ######### ON SUBMIT OF FORM JS VALIDATION ENDS #########//

	var enable_unify_order_note_value = $('#unify_order_note').val();

	if(enable_unify_order_note_value == 1){
		$('#unify_order_note_enable').attr("checked", "checked");
	}
	$('#unify_order_note_enable').change(function() {
        if(this.checked) {
            $("#unify_order_note").val("1");
            $(this).attr("checked", "checked");
        }else{
        	$("#unify_order_note").val("0");
        	$(this).removeAttr("checked");
        }
              
    });


    
    const urlParams = new URLSearchParams(window.location.search);
	const is_postID_found = urlParams.get('post');
	
	if(!is_postID_found) 
		{ 
			limelightUiConfig();
	$('#unify_connection_crm').val($("#unify_connection_crm_select").find(":selected").val());
		$('#unify_connection_crm').trigger('blur');
		var validation_ids = ["unify_connection_crm_select","unify_connection_api_password","unify_connection_campaign_id","post_title","unify_connection_api_username"];
		validation_ids.forEach(function(id) {
    		$("#"+id+"-error").css("display","none");
		});
			$("#unify_connection_offer_model").val("1");
			$('#unify_response_crm_type_enable').val("1");
		}
	var enable_unify_billing_model_value = $('#unify_connection_offer_model').val();
	 if(enable_unify_billing_model_value == 1){
	 	$('#unify_connection_offer_model_select').removeAttr("checked");
	 	$("#unify_order_note").val("0");
         $('.unify_order_note_enable_div').css("display","none");
	 }else if(enable_unify_billing_model_value == 0){
		$('#unify_connection_offer_model_select').attr("checked", "checked");
        $('.unify_order_note_enable_div').css("display","block");
	}
	

 $('#unify_connection_offer_model_select').change(function() {
        if(this.checked) {
            $("#unify_connection_offer_model").val("0");
            $(this).attr("checked", "checked");
            $('.unify_order_note_enable_div').css("display","block");
        }else{
        	$("#unify_connection_offer_model").val("1");
        	$(this).removeAttr("checked");
        	$("#unify_order_note").val("0");
        	$('.unify_order_note_enable_div').css("display","none");
        }
              
    });


    var enable_unify_response_crm_type = $('#unify_response_crm_type_enable').val();

	if(enable_unify_response_crm_type == 0){
		$('#unify_connection_response_crm_type_enable').attr("checked", "checked");
	}
	$('#unify_connection_response_crm_type_enable').change(function() {
        if(this.checked) {
            $("#unify_response_crm_type_enable").val("0");
            $(this).attr("checked", "checked");
        }else{
        	$("#unify_response_crm_type_enable").val("1");
        	$(this).removeAttr("checked");
        }
              
    });

});

jQuery(document).on("click", function (event) {

	if (event.target.id != 'post-stat') {
		jQuery("#post-stat-action").hide();
	}
	
	if (jQuery(event.target).is(".custom-select") == false) {
		jQuery(".custom-select").removeClass("opened");
	}

});



/**
 * Configure Connection's UI for Limelight
 */
function limelightUiConfig() {
	$('#offer_model_ent_div').show();
	$('#unify_connection_campaign_details').show();
	$('#unify_connection_api_username_details').show();
	$('#unify_connection_endpoint_div').show();
	$('#unify_connection_site_id').hide();
	$('#unify_connection_campaign_id').html('Campaign ID <span class="text-danger">*</span>');
	$('#unify_connection_shipping_id').show();
	$('#unify_campaign_details').show();
	$('#unify_connection_username_label').html('API Username <span class="text-danger">*</span>');
	$('#unify_connection_password_label').show();
	$('#response_crm_ent_div').hide();
	$('#validate_connection').show();
	$('#unify_connection_connection_id_div').hide();
}

/**
 * Configure Connection's UI for Konnektive
 */
function konnektiveUiConfig() {
	$('#unify_connection_campaign_details').show();
	$('#unify_connection_api_credential').hide();
	$('#offer_model_ent_div').hide();
	$('#unify_connection_api_username_details').show();
	$('#unify_connection_endpoint_div').hide();
	$('#unify_connection_shipping_id').show();
	$('#unify_connection_campaign_id').html('Campaign ID <span class="text-danger">*</span>');
    $('#unify_connection_username_label').html('API Username <span class="text-danger">*</span>');
    $('#unify_connection_password_label').show();
    $('#response_crm_ent_div').hide();
    $('#validate_connection').hide();
    $('#unify_connection_connection_id_div').hide();
}

/**
 * Configure Connection's UI for Response
 */
function responseUiConfig() {
	$('#unify_connection_campaign_details').show();
	$('#unify_connection_shipping_id').hide();
	$('#unify_connection_api_username_details').show();
	$('#unify_connection_campaign_id').html('Site ID <span class="text-danger">*</span>');
	$('#unify_connection_username_label').html('API key <span class="text-danger">*</span>');  
	$('#offer_model_ent_div').hide();
    $('#unify_connection_endpoint_div').hide();
	$('#unify_connection_password_label').hide();
	$('#response_crm_ent_div').show();
	$('#validate_connection').show();
	$('#unify_connection_connection_id_div').hide();
}

 /**
 * Configure Connection's UI for sublytics
 */
function sublyticsUiConfig() {
	$('#unify_connection_connection_id_div').show();
	$('#unify_connection_endpoint_div').show();
	$('#offer_model_ent_div').hide();
	$('#validate_connection').show();
    
}

/**
 * truncate unnecessery components from endpoint
 */
function validate_endpoint(v){
	 var url = v.value;
	 checked_url = url.replace(/^(?:https?:\/\/)?(?:www\.)?/i, "").split('/')[0];
	 $("#unify_connection_endpoint").val(checked_url);	
}

/**
 * Remove required Shipping field star as per CRM selection
 */
function showRequiredStar(connName){
	if(connName == "konnektive" || connName == "sublytics"){
		document.getElementById("default_shippingID").style.display = "none";
	}else{
		document.getElementById("default_shippingID").style.display = "inline-block";
	}

	document.getElementsByClassName("validated_msg")[0].style.display = "none";
	document.getElementsByClassName("overlayDiv")[0].style.display = "none";
}





