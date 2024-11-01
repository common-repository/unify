jQuery(document).ready(function ($) {	
	const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const phone_regex = /^\+{0,2}([\-\. ])?(\(?\d{0,3}\))?([\-\. ])?\(?\d{0,3}\)?([\-\. ])?\d{3}([\-\. ])?\d{4}/;

	// *********** Hide connection message
	$('.cross-position').click(function () {
		$(this).parents('div .container-fluid').fadeOut();
	});

        jQuery('img.sv-icon').each(function () {
            var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function (data) {
                var $svg = jQuery(data).find('svg');
                if (typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                if (typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass + ' replaced-svg');
                }
                $svg = $svg.removeAttr('xmlns:a');
                $img.replaceWith($svg);
            }, 'xml');
        });

    $(document).on("keyup", "#email_address", function () {
        if ($(this).val() === '' || !(regex).test($(this).val())) {
            $("#email_address-error").remove(); 
            $(this).after('<label id="email_address-error" class="text-danger" for="email_address">Please enter a valid email address.</label>');
        }else{
           $("#email_address-error").remove();   
        } 
    });

    $('input#phone_number').on('keypress', function (key) {
        if (key.keyCode === 8 || key.keyCode === 9 || key.keyCode === 13 || key.keyCode === 16) {
            return true;
        }
        if (key.charCode < 48 || key.charCode > 57) {
            return false;
        }
    });

     $('#unify_free_trial_form input[type=text]').on('keyup', function (key) {
        let current_id = $(this).attr("id");
        let current_placeholder = $(this).attr("placeholder");
        if ($(this).val() != '') {
            $("#"+current_id+"-error").remove(); 
        }else{
           $("#"+current_id+"-error").remove();
           $(this).after('<label id="'+current_id+'-error" class="text-danger" for="'+current_id+'">'+current_placeholder+' is a required field.</label>');  
        }
    });
    
    $('#register_free_trial').click(function(){
        var valid = true;
        if(valid){
            var v = valid_fields();
            if (v) {
                ajax_to_add_plugin_lead();
            }   
        }   
    });

    function ajax_to_add_plugin_lead(){
    $.ajax({
        beforeSend: function () {
            $('#registrationForm').fadeOut("slow");$('#progressModal').fadeIn("slow");
        },
        data: {
            'action': 'unify_plugin_lead_generate',
            'x-data': $('#unify_free_trial_form').serialize()
        },
        dataType: 'json',
        type: 'POST',
        url: ajaxurl,
        success: function (response) {
            console.log(response);
            const status = response.status;
            var cls = '';
            if(status == 1){
                setTimeout(function(){
                     $('.progress-bar').removeClass('w-25');
                     $('.progress-bar').addClass('w-50');
                     $('.progress-text').html('50%'); 
                 }, 2000);
                    setTimeout(function(){
                     $('.progress-bar').removeClass('w-50');
                     $('.progress-bar').addClass('w-75');
                     $('.progress-text').html('75%'); 
                 }, 4000);  
                    setTimeout(function(){
                     $('.progress-bar').removeClass('w-75');
                     $('.progress-bar').addClass('w-100');
                     $('.progress-text').html('100%');  
                 }, 5000);  
                setTimeout(function(){
                 $('#progressModal').fadeOut("slow");
                 $('#sucessModal').fadeIn("slow"); 
             }, 6000);  
            }else{
                $('#progressModal').fadeOut("slow");$('#registrationForm').fadeIn("slow");
                cls = "danger";
                $("#freeLicenseResponse").addClass(cls+"-bg");
                $("#freeLicenseResponse").html("<div class='row clearfix m-0'><div class='col-12 text-"+cls+" "+cls+"-bg-text'>"+response.msg+"</div></div>");
                $("#freeLicenseResponse").css("display",'block');
                $('#freeLicenseResponse').delay(5000).fadeOut('slow');
            }
        },
        error: function (response){
            $('#progressModal').fadeOut("slow");$('#registrationForm').fadeIn("slow");
                cls = "danger";
                $("#freeLicenseResponse").addClass(cls+"-bg");
                $("#freeLicenseResponse").html("<div class='row clearfix m-0'><div class='col-12 text-"+cls+" "+cls+"-bg-text'>Some Error Occured!</div></div>");
                $("#freeLicenseResponse").css("display",'block');
                $('#freeLicenseResponse').delay(5000).fadeOut('slow');
        },
        complete: function (response) { 
        }
    });
}

    function valid_fields() {
        var valid = true;
        var message = '';
        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var email_address = $("#email_address").val();
        var phone_number = $("#phone_number").val();
        //var company_name = $("#company_name").val();  
         
        agreement_checkbox_validation();

        if (first_name === '') {
            valid = false;
            $("#first_name-error").remove(); 
            $("#first_name").after('<label id="first_name-error" class="text-danger" for="email_address">First Name is a required field.</label>');
        }
        if (last_name === '') {
            valid = false;
            $("#last_name-error").remove(); 
            $("#last_name").after('<label id="last_name-error" class="text-danger" for="last_name">Last Name is a required field.</label>');
        }
        if (email_address === '') {
            valid = false;
            $("#email_address-error").remove(); 
            $("#email_address").after('<label id="email_address-error" class="text-danger" for="email_address">Email is a required field.</label>');
        }else{
            if (!(regex).test(email_address)) {
                valid = false;
                $("#email_address-error").remove(); 
                $("#email_address").after('<label id="email_address-error" class="text-danger" for="email_address">Please enter a valid email address.</label>');
            }else{
                $("#email_address-error").remove();  
            }
        }
        if (phone_number === '') {
            valid = false;
            $("#phone_number-error").remove(); 
            $("#phone_number").after('<label id="phone_number-error" class="text-danger" for="phone_number">Phone is a required field.</label>');
        }else{
            if (!(phone_regex).test(phone_number)) {
                valid = false;
                $("#phone_number-error").remove(); 
                $("#phone_number").after('<label id="phone_number-error" class="text-danger" for="phone_number">Please Enter a valid Phone Number.</label>');
            }else if (phone_number.length < 10) {
                valid = false;
                $("#phone_number-error").remove(); 
                $("#phone_number").after('<label id="phone_number-error" class="text-danger" for="phone_number">Please Enter a valid Phone Number.</label>');
            }else{
                $("#phone_number-error").remove();  
            }
        }
          
        if (valid && agreement_checkbox_validation()==true) {
            $("#privacy_policy_chkbox-error").remove(); 
            return true;
        } else {
            return false;
        }
    }

    /*terms and condition checkbox validation*/
    function agreement_checkbox_validation() {
        var valid = true;
        if ($("#privacy_policy_chkbox").prop("checked") == true) {
                $("#privacy_policy_chkbox-error").remove(); 
            } else {
                valid = false;
                $("#privacy_policy_chkbox-error").remove(); 
                $("#privacy_policy_chkbox").after('<label id="privacy_policy_chkbox-error" class="text-danger" for="privacy_policy_chkbox">Privacy Policy is a required field.</label>');
            }
        return valid;
    }

    $('#privacy_policy_chkbox').change(function() {
        if(this.checked) {
            $("#privacy_policy_chkbox-error").remove(); 
        }
    });


});



