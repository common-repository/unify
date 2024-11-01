<fieldset class="wc-credit-card-form wc-payment-form" id="wc-unify-cc-form">
    <p class="form-row form-row-wide woocommerce-validated">
        <label for="wc-unify-card-number">
            Name on card
            <span class="required">
                *
            </span>
        </label>
        <input autocapitalize="no" autocorrect="no" class="input-text" id="wc-unify-name-on-card" name="name_on_card" placeholder="Name on card" type="text"/>
    </p>
    <p id="wc-unify-card-number-wrapper" class="form-row form-row-wide woocommerce-validated">
        <label for="wc-unify-card-number">
            Card number
            <span class="required">
                *
            </span>
        </label>
        <input autocapitalize="no" autocomplete="cc-number" maxlength="16" autocorrect="no" class="input-text wc-credit-card-form-card-number" id="wc-unify-card-number" inputmode="numeric" name="cc_number" placeholder="•••• •••• •••• ••••" spellcheck="no" type="tel"/>
    </p>
    <p id="wc-unify-card-expiry-wrapper" class="form-row form-row-first woocommerce-validated">
        <label for="wc-unify-card-expiry">
            Expiry (MM/YY)
            <span class="required">
                *
            </span>
        </label>
        <input autocapitalize="no" autocomplete="cc-exp" autocorrect="no" class="input-text wc-credit-card-form-card-expiry" id="wc-unify-card-expiry" inputmode="numeric" name="cc_expiry" placeholder="MM / YY" spellcheck="no" type="tel" onkeyup="
		var date = this.value;
		if (date.match(/^\d{2}$/) !== null) {
		   this.value = date + '/';
		}" maxlength="5"/>
    </p>
    <p class="form-row form-row-last woocommerce-validated">
        <label for="wc-unify-card-cvc">
            Card code
            <span class="required">
                *
            </span>
        </label>
        <input autocapitalize="no" autocomplete="off" autocorrect="no" class="input-text wc-credit-card-form-card-cvc" id="wc-unify-card-cvc" inputmode="numeric" maxlength="4" name="cc_cvc" placeholder="CVC" spellcheck="no" style="width:100px" type="tel"/>
    </p>
    <div class="clear"></div>
</fieldset>

<script type="text/javascript">
jQuery(document).ready(function ($) {
    /** If NMI set as payment gateway, then masking/spaces adds into unify card number, expiry fields
     * to avoid masking/spaces removing the field and append new input field by innerhtml using wrapper
     */
    document.getElementById("wc-unify-card-number").remove(); // for unify card number
     const wrapper = document.getElementById("wc-unify-card-number-wrapper");
     wrapper.innerHTML += '<input autocapitalize="no" autocomplete="cc-number" maxlength="16" autocorrect="no" class="input-text wc-credit-card-form-card-number" id="wc-unify-card-number" inputmode="numeric" name="cc_number" placeholder="•••• •••• •••• ••••" spellcheck="no" type="tel"/>';

     document.getElementById("wc-unify-card-expiry").remove(); // for unify card expiry
     const wrapperExpiry = document.getElementById("wc-unify-card-expiry-wrapper");
     wrapperExpiry.innerHTML += '<input autocapitalize="no" autocomplete="cc-exp" autocorrect="no" class="input-text wc-credit-card-form-card-expiry" id="wc-unify-card-expiry" inputmode="numeric"  name="cc_expiry" placeholder="MM / YY" spellcheck="no" type="tel" pattern="[0-9.]+"   maxlength="5"/>';
     
     jQuery("#wc-unify-card-expiry").keyup(function (e) {

        this.value = this.value.replace(/[^0-9/]/g,"");
        var date = this.value;
		if (date.match(/^\d{2}$/) !== null) {console.log('iff');
		   this.value = date + '/';
		}
    });
    /**End wrapper process */

    jQuery("#wc-unify-card-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    jQuery("#wc-unify-card-cvc").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
	$('#billing_first_name').blur(function(){
		get_name_on_card();
	});
	
	$('#billing_last_name').blur(function(){
		get_name_on_card();
	});
	get_name_on_card();
});

function get_name_on_card(){
	jQuery('#wc-unify-name-on-card').val( jQuery('#billing_first_name').val()+ ' ' + jQuery('#billing_last_name').val());
}
</script>