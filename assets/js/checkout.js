var w ='';
var left = (screen.width - 600) / 2;
var top = (screen.height - 600) / 4;
var loader = `<style type="text/css">
                .loader-container-div{
                    width: 500px;
                    height: 500px;
                    margin: 0 auto;
                    position: relative;
                }
                .loader-container-div .pay-loader{
                    margin: auto;
                    left: 0;
                    right: 0;
                    top: 0;
                    bottom: 0;
                    position: absolute;
                    display: block;
                    text-align: center;
                }
                .pay-loader {
                    background-color: transparent;
                    border: 5px solid #cbcbca;
                    border-radius: 100%;
                    border-top: 5px solid #2380be;
                    width: 100px;
                    height: 100px;
                    -webkit-animation: payspin .7s linear infinite;
                    animation: payspin .7s linear infinite;
                    opacity: 1;
                }

                /* Safari */
                @-webkit-keyframes payspin {
                    0% { -webkit-transform: rotate(0deg); }
                    100% { -webkit-transform: rotate(360deg); }
                }

                @keyframes payspin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
        </style>

        <div class="loader-container-div">
            <div class="pay-loader"></div>
        </div>`;
        var settings = (document.getElementById("unify_paypal_payment_mode")!=null) ? (document.getElementById("unify_paypal_payment_mode").value == 'yes')?2:1 : 1;

jQuery(function ($) {
    $(document).on('updated_checkout', function () {
        $('#billing_state_field > label > .optional').remove();
        $('#billing_state_field').addClass('validate-required');
        if($('#billing_state_field > label').html().search('required') < 1)
        {
            $('#billing_state_field > label').append('<abbr class="required" title="required">*</abbr>');
        }
        $('#shipping_state_field > label > .optional').remove();
        $('#shipping_state_field').addClass('validate-required');
        if($('#shipping_state_field > label').html() != undefined && $('#shipping_state_field > label').html().search('required') < 1)
        {
            $('#shipping_state_field > label').append('<abbr class="required" title="required">*</abbr>');
        }
    });

    jQuery( 'body' )
    .on( 'updated_checkout', function() {
          usingGateway();
            jQuery('input[name="payment_method"]').change(function(){
            $('.overlayDiv').show();
              usingGateway();
        });
    });

    $(".place_order_paypal").on('click', function() {
        var payment_method = jQuery('form.checkout').find('input[name^="payment_method"]:checked').val();
        if(payment_method === 'codeclouds_unify_paypal_payment'){   
            var v = valid_billing();
            if(v){
                init(this);
            }
        }
     } );
    

    function popUpClose(){
        w.onunload = function() {
            $(".modal-backdrop.show").css({"opacity": "0"});
        }
    } 

    function init(form){
        //popUpClose();
        if(settings == 2){
            //getPaypalRedirected();
            payPalAjax();
        }else{
           var popups_are_disabled = popsUp(form);
            if (popups_are_disabled == true) {
                alert("Your browser pop Up settings is disabled.");
            } 
        }
             
    }

    function payPalAjax() {
        var origin = window.location.pathname;    
        var jqXHR = $.ajax({
                url: origin+'?wc-ajax=checkout',
                type: "POST",
                data: jQuery(".woocommerce-checkout").serialize(),
                beforeSend: function () {
                    if(settings == 2){
                        $('.overlayDiv').show();
                    }
                },
                success: function(response) {
                    (settings == 2)?getPaypalRedirected(response):gatewayFunction(response);
                },
                error: function() {
                    $('.overlayDiv').hide();
                    console.log("error");
                }
            });
        return false;
    }

    function getPaypalRedirected(response){
        var obj = typeof response === 'string' ? JSON.parse(response) : response;
        var res = obj.result;
        var reserror = obj.messages;
            if(res!='failure'){
                if(typeof res == 'string' && res.includes('<html>')) {
                    $(".woocommerce-checkout").empty();
                    $(".woocommerce-checkout").html(res);
                    window.self.OnLoadEvent();
                } else {
                    window.location.href = res;

                }
            }else{
                $('.overlayDiv').hide();
                   $(reserror).insertBefore("#customer_details");
                $('.woocommerce-error').delay(5000).fadeOut('slow');
                   $('html, body').animate({scrollTop:10}, 'slow');
            }
    }

    function popsUp(form){
        w = window.open("","unifyWindow","width=600,height=600" + "top=" + top + ", left=" + left);
        if (!w || w.closed || typeof w == 'undefined' || typeof w.closed == 'undefined') {

                return true;
            }

        backDrop();
        w.document.write(loader);
        payPalAjax();
        //gatewayFunction( form );
        windowUnload(w);
        return false;
    }

    function backDrop(){
        $('<div class="modal-backdrop fade show" ></div>').appendTo('body');
        $(".modal-backdrop").css({"position": "fixed","top": "0","left": "0","z-index": "1040","width": "100vw","height": "100vh","background-color": "#000"});
        $(".modal-backdrop.show").css({"opacity": ".5"});
    }

    function windowUnload(w){
        w.onunload = function() {
                    $(".modal-backdrop").remove();
                }
    }

    function gatewayFunction( response ) {
        var obj = typeof response === 'string' ? JSON.parse(response) : response;
        var res = obj.result;
        var reserror = obj.messages;

        $('.overlayDiv').hide();
        if(res!='failure'){
            if(typeof res == 'string' && res.includes('<html>')) {
                w.document.open();
                w.document.write(res);
                w.OnLoadEvent();
                setInterval(function() {
                    try {
                        if (w.closed == true) {$(".modal-backdrop").remove(); return;}
                        w.focus();
                        var url = w.location.href;
                        var urlParams = new URLSearchParams(w.location.search);
                        if(urlParams.has('orderStatus') && urlParams.get('orderStatus') == 1){
                            w.close();
                            window.location.replace(removeParam('orderStatus',url));
                        }else if(urlParams.has('orderStatus') && urlParams.get('orderStatus') == 0){
                            w.close();
                            window.location.replace(removeParam('orderStatus',url));
                        }
                        else if(urlParams.has('PayerID')){
                                w.close();
                                Window.location.replace(url);
                            }                        
                    } catch (e) {
                        if (w.closed == true) {
                            $(".modal-backdrop").remove();
                        }
                    }
                }.bind(this), 100);
            } else {
                w.document.open();
                w.location.replace(res);
                //w.OnLoadEvent();
                setInterval(function() {
                    try {
                        if (w.closed == true) { $(".modal-backdrop").remove(); return;}
                            var url = w.location.href;
                            var urlParams = new URLSearchParams(w.location.search);
                            if(urlParams.has('orderStatus') && urlParams.get('orderStatus') == 1){
                                w.close();
                                window.location.replace(removeParam('orderStatus',url));
                            }else if(urlParams.has('orderStatus') && urlParams.get('orderStatus') == 0){
                                w.close();
                                $('.overlayDiv').show();
                                window.location.replace(removeParam('orderStatus',url));
                            }
                           else if(urlParams.has('PayerID')){
                                w.close();
                                $('.overlayDiv').show();
                                   window.location.replace(url);
                            }                                
                    } catch (e) {
                        if (w.closed == true) {
                            $(".modal-backdrop").remove();
                        }
                    }
                }.bind(this), 100);
                //windowUnload(w);
                }
        }else{
            w.close();
            $('.overlayDiv').hide();
            $(reserror).insertBefore("#customer_details");
            $('.woocommerce-error').delay(5000).fadeOut('slow');
            $('html, body').animate({scrollTop:10}, 'slow');

        }
                
    }

    function usingGateway(){
        var payment_method = jQuery('form.checkout').find('input[name^="payment_method"]:checked').val();
        if(payment_method == 'codeclouds_unify_paypal_payment'){
            $('#place_order').css("display","none");
            $('#place_order_paypal').css("display","flex");
            $('.overlayDiv').hide();
        }else{
            $('#place_order_paypal').css("display","none");
            $('#place_order').css("display","block");
            $('.overlayDiv').hide();
        }
    }

    function valid_billing() {
            var valid = true;
            var message = '';
            var html = '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout" id="payal_error"><ul class="woocommerce-error" role="alert">';
            var billing_first_name = $("#billing_first_name").val();
            var billing_last_name = $("#billing_last_name").val();
            var billing_address = $("#billing_address_1").val();
            var billing_city = $("#billing_city").val();
            var billing_zip = $("#billing_postcode").val();
            var billing_email = $("#billing_phone").val();
            var billing_phone = $("#billing_email").val();
            if (billing_first_name === '') {
                valid = false;
                var wrapper = $("#billing_first_name").closest('.form-row');
                wrapper.addClass('woocommerce-invalid'); 
                html +='<li><strong>Billing First name</strong> is a required field.</li>';
            }
            if (billing_last_name === '') {
                valid = false;
                var wrapper = $("#billing_last_name").closest('.form-row');
                wrapper.addClass('woocommerce-invalid'); 
                html +='<li><strong>Billing Last name</strong> is a required field.</li>';
            }
            if (billing_address === '') {
                valid = false;
                var wrapper = $("#billing_address_1").closest('.form-row');
                wrapper.addClass('woocommerce-invalid'); 
                html +='<li><strong>Billing Street address</strong> is a required field.</li>';
            }
            if (billing_city === '') {
                valid = false;
                var wrapper = $("#billing_city").closest('.form-row');
                wrapper.addClass('woocommerce-invalid'); 
                html +='<li><strong>Billing Town / City</strong> is a required field.</li>';
            }
            if (billing_zip === '') {
                valid = false;
                var wrapper = $("#billing_postcode").closest('.form-row');
                wrapper.addClass('woocommerce-invalid'); 
                html +='<li><strong>Billing ZIP Code</strong> is a required field.</li>';
            }
            if (billing_email === '') {
                valid = false;
                var wrapper = $("#billing_email").closest('.form-row');
                wrapper.addClass('woocommerce-invalid'); 
                html +='<li><strong>Billing Email</strong> is a required field.</li>';
            }
            if (billing_phone === '') {
                valid = false;
                var wrapper = $("#billing_phone").closest('.form-row');
                wrapper.addClass('woocommerce-invalid'); 
                html +='<li><strong>Billing Phone</strong> is a required field.</li>';
            }
            html +='</ul></div>';
            

            if (valid) {
                html='';
                $('.woocommerce-checkout').find(".woocommerce-NoticeGroup").remove();
                return true;


            } else {
                $('.woocommerce-checkout').find(".woocommerce-NoticeGroup").remove();
                $(html).insertBefore("#customer_details");
                $('html, body').animate({
                    'scrollTop' : $("#payal_error").position().top
                });
                return false;
            }

    }

    /*URL params removal from response URL except the WC_order key*/

    function removeParam(key, sourceURL) {
        var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
            if (queryString !== "") {
                params_arr = queryString.split("&");
                for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                    param = params_arr[i].split("=")[0];
                    if (param === key) {
                        params_arr.splice(i, 1);
                    }
                }
                if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
            }
        return rtn;
    }

});