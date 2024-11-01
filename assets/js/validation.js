'use strict';

var crms = ['limelight'];

jQuery(document).ready(function($) {

    var currentCrm = $('#unify_connection_crm').val();

    $('#unify_connection_endpoint_area').css('display', 'none');

    $('#unify_connection_offer_model').css('display', 'none');
    
    $('.unify_connection-shipping_id-title').html('Shipping ID (optional)');
    $('#unify_connection_shipping_id').removeAttr('required');

    if (crms.indexOf(currentCrm) >= 0)
    {
        $('#unify_connection_endpoint_area').css('display', 'block');
        $('#unify_connection_endpoint').attr('required', 'required');

        $('#unify_connection_offer_model').css('display', 'block');
    }
    $('#unify_connection_crm').change(function() {

        var crm = $(this).val();

        if (crms.indexOf(crm) >= 0)
        {
            $('#unify_connection_endpoint_area').css('display', 'block');
            $('#unify_connection_endpoint').attr('required', 'required');
            
            $('.unify_connection-shipping_id-title').html('Shipping ID');
            $('#unify_connection_shipping_id').attr('required', 'required');

            $('#unify_connection_offer_model').css('display', 'block');
        }
        else
        {
            $('#unify_connection_endpoint_area').css('display', 'none');
            $('#unify_connection_endpoint').val('');
            $('#unify_connection_endpoint').removeAttr('required');
            
            $('#unify_connection_shipping_id').removeAttr('required');
            $('.unify_connection-shipping_id-title').html('Shipping ID (optional)');

            $('#unify_connection_offer_model').css('display', 'none');
        }
    });
	
});