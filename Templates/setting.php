<?php
use \CodeClouds\Unify\Service\Notice;
use \CodeClouds\Unify\Model\ConfigEncryption;

?>
<div class="unify-table-area dash-in">
    <div class="container-fluid unify-mid-heading p-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="page-block-top-heading clearfix">
                    <h2 class="mid-heading">Settings&nbsp;&nbsp;</h2></div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/Notice/lead_notice.php';?>
    <?php include_once __DIR__ . '/Notice/pro-msg.php';?>

    <div class="container-fluid unify-search p-0 mgbt-25 uni-shadow-box">
        <div class="row clearfix m-0">
            <div class="col-12 unify-top-search-left pr-0 pl-0">
                <div class="unify-white-menu clearfix">
                    <ul class="option-row-simple-menu"> 
                        <li class="btn btn-link active"><a href="<?php echo esc_url(admin_url('admin.php?page=unify-settings'))?>">General</a></li>
                        <li class="btn btn-link <?php echo ((isset($_GET['section'])&& $_GET['section']==='license-management'))?'active' : ''; ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=unify-settings&section=license-management'))?>">License Management</a></li>
                        <!--<li class="btn btn-link"><a href="">Pro Settings</a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    if (!session_id()) { session_start(); }
    
    if (Notice::hasFlashMessage('unify_notification'))
    {
        include_once __DIR__ . '/Notice/notice.php';
    }

    $crm_connection_settings = !empty($setting_data['connection'])?get_post_meta($setting_data['connection']):'';
    $crm_conection_type = !empty($crm_connection_settings)?isset($crm_connection_settings['unify_connection_crm_salt'][0])?ConfigEncryption::metaDecryptSingle($crm_connection_settings['unify_connection_crm'][0],$crm_connection_settings['unify_connection_crm_salt'][0]):$crm_connection_settings['unify_connection_crm'][0]:'';
    
    ?>

    <div class="container-fluid unify-table p-0 tran-bg-in ">
        <div class="row clearfix m-0">
            <div class="col-md-6 pl-0 pr-2 ">
            
                <div class="crd-white-box  border-0 bottom-mg-gap">
                    <form name="unify_settings_form_post" id="unify_settings_form_post" method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" >
                        <div class="inner-white-box uni-shadow-box">
                            <h3 class="mid-blue-heading" >Checkout Configuration</h3>

                            <div class="inner-api-cont mt-4">
                                <div class="form-group m-0">
                                    <label for="title">Display Title</label>
                                    <small class="form-text text-muted">This controls the title which the user sees during checkout.</small>
                                    <input type="text" id="title" name="title" value="<?php echo (!empty($setting_data['title'])) ? esc_html($setting_data['title']) : ''; ?>" class="form-control">
                                </div>
                            </div>

                            <div class="inner-api-cont mt-4">
                                <div class="form-group m-0">
                                    <label for="description">Description</label>
                                    <small class="form-text text-muted">This controls the description which the user sees during checkout.</small>
                                    <input type="text" id="description" name="description" value="<?php echo (!empty($setting_data['description'])) ? esc_html($setting_data['description']) : ''; ?>" class="form-control">
                                </div>
                            </div>
                            <?php  $settings_meta_data = get_post_meta($setting_data['connection']);?>
                            <div class="inner-api-cont mt-4">
                                <div class="form-group m-0" id="connection_error" >
                                    <input type="hidden" id="connection" value="<?php echo esc_html($crm_conection_type); ?>">
                                    
                                </div>
                            </div>

                            <div class="inner-api-cont mt-4" id="shipment_price_settings_div" >
                                <div class="form-group m-0">
                                    <label for="shipment_price_settings">Shipment Price 
                                        <span class="info-tool">
                                            <i class="fas fa-info-circle"></i>
                                                <div class="info-tooltip">
                                                    <p>
                                                        <strong>Single Order:</strong> All products will be grouped under one order and the shipping will be charged based on WooCommerce’s Shipping calculation.
                                                    </p>
                                                    <p>
                                                        <strong>Multiple Orders:</strong> All products will be separated under unique Shipping ID(s), as a result, more than 1 order will be in generated in the CRM.
                                                    </p>
                                                </div>
                                        </span>
                                    </label>
                                    <select name="shipment_price_settings" id="shipment_price_settings" class="custom-select sources" placeholder="<?php echo (esc_html(!empty($setting_data['shipment_price_settings']) && array_key_exists($setting_data['shipment_price_settings'], ($shipment_list)))) ? esc_html($shipment_list[$setting_data['shipment_price_settings']]) : esc_html($shipment_list[1]); ?>"  >
                                        <?php
                                        foreach ($shipment_list as $k => $conn_sett)
                                        {

                                            ?>
                                            <option value="<?php echo esc_html($k); ?>"  ><?php echo esc_html($conn_sett); ?></option>
<?php } ?>

                                    </select>
                                </div>
                            </div>
                         
                            
                            <div class="upl-cnt-btn text-center mgtp-20">
                                <button type="button" class="btn btn-primary gen-col-btn-sm" id="submit_settings" >
                                    <span class="">Save</span> 
                                    <span class=""></span>
                                </button>
                            </div>
                            
                        </div>      
                        
                        <input type="hidden" name="connection_val" id="connection_val" value="<?php echo (!empty($setting_data['connection'])) ? esc_html($setting_data['connection']) : ''; ?>" />
                        <input type="hidden" name="shipment_price_settings_val" id="shipment_price_settings_val" value="<?php echo (!empty($setting_data['shipment_price_settings'])) ? esc_html($setting_data['shipment_price_settings']) : '';?>" />
                        <!-- <input type="hidden" name="shipment_price_settings_val" id="shipment_price_settings_val" value="1" /> -->
                        <input type="hidden" name="testmode_val" id="testmode_val" value="<?php echo (!empty($setting_data['testmode'])) ? esc_html($setting_data['testmode']) : ''; ?>"/>
                        <input type="hidden" name="action" value="unify_settings_form_post" />
                        <input type="hidden" id="post_type" name="post_type" value="unify_connections">
                        <input type="hidden" name="page" value="<?php echo esc_html(\CodeClouds\Unify\Service\Request::any('page')) ?>" />
                        <?php wp_nonce_field('unify-settings-data'); ?>
                    
                </div>

            </div>
            <div class="col-md-6 pl-2 pr-0">
                <div class="crd-white-box  border-0 bottom-mg-gap" >
                    <div class="inner-white-box uni-shadow-box">
                        <h3 class="mid-blue-heading">Additional Settings</h3>
                         <div class="inner-api-cont mt-4">
                                <div class="form-row">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="google_recaptcha" class="mb-0">Test Mode</label>
                                        <small class="form-text text-muted">It will disable card number's validation.</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                    <input id="testmode" name="testmode" value="yes" type="checkbox" class="switch" <?php echo (!empty($setting_data['testmode']) && $setting_data['testmode'] == 'yes') ? 'checked="checked"' : ''; ?> >
                                                    <label for="testmode"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>                    
                        </div>

                        <div class="inner-api-cont mt-4">
                                <div class="form-row">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="google_recaptcha" class="mb-0">Enable Debugging</label>
                                        <small class="form-text text-muted">Enable debugging to log API request and response.</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                   <input id="enable_debugging" name="enable_debugging" value="yes" type="checkbox" class="switch" <?php echo (!empty($setting_data['enable_debugging']) && $setting_data['enable_debugging'] == 'yes') ? 'checked="checked"' : ''; ?> >
                                                    <label for="enable_debugging"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>     
                        </div>
                        <?php if($crm_conection_type == 'limelight'){?>
                            <div class="inner-api-cont mt-4" id="paypalCheckout">
                                    <div class="form-row">
                                        <div class="form-group col-sm-8 p-0 m-0">
                                            <label for="google_recaptcha" class="mb-0">PayPal Checkout</label>
                                            <small class="form-text text-muted">Enable PayPal Checkout through sticky.io</small>
                                        </div> 
                                        <div class="col-sm-4 p-0">    
                                            <div class="ad-on-btn-in">
                                                <div class="slide-opt-box">
                                                    <span class="switch">
                                                        <i class="fas fa-cog payPalSettings disablePayPalSettings"></i>
                                                        <input 
                                                            id="paypal_enabled" 
                                                            name="paypal_enabled" 
                                                            value="yes" type="checkbox" 
                                                            class="switch" <?php echo (empty($additional_setting_option) || (!empty($additional_setting_option['enabled']) && $additional_setting_option['enabled'] == 'yes')) ? 'checked="checked"' : ''; ?>  > 
                                                        <label for="paypal_enabled"></label>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                            </div>
                        <?php }?>
                        <?php if($crm_conection_type == 'sublytics'){?>
                            <div class="inner-api-cont mt-4" id="paypalCheckout">
                                    <div class="form-row">
                                        <div class="form-group col-sm-8 p-0 m-0">
                                            <label for="google_recaptcha" class="mb-0">PayPal Checkout</label>
                                            <small class="form-text text-muted">Enable PayPal Checkout through Sublytics</small>
                                        </div> 
                                        <div class="col-sm-4 p-0">    
                                            <div class="ad-on-btn-in">
                                                <div class="slide-opt-box">
                                                    <span class="switch">
                                                        <i class="fas fa-cog payPalSettings disablePayPalSettings"></i>
                                                        <input id="paypal_enabled" name="paypal_enabled" value="yes" type="checkbox" class="switch" <?php echo (empty($additional_setting_option) || (!empty($additional_setting_option['enabled']) && $additional_setting_option['enabled'] == 'yes')) ? 'checked="checked"' : ''; ?>  > 
                                                        <label for="paypal_enabled"></label>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                            </div>
                        <?php }?>                        
                    </div>
                </div>
            </div>
            </form>

            <!-- <div class="col-md-6 pl-2 pr-0"> -->
                <div class="crd-white-box  border-0 bottom-mg-gap" style="display: none;" >
                    <div class="inner-white-box uni-shadow-box">
                        <h3 class="mid-blue-heading">Activate or Deactivate Modules</h3>
                         <div class="inner-api-cont mt-4">
                                <div class="form-row">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="google_recaptcha" class="mb-0">Google Recaptcha</label>
                                        <small class="form-text text-muted">Enable Google Recaptcha security on your checkout</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                    <input id="google_recaptcha" type="checkbox" name="allow-customer-register" class="switch"> 
                                                    <label for="google_recaptcha"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="google_autocomplete" class="mb-0">Google Autocomplete</label>
                                        <small class="form-text text-muted">Enable Google Autocomplete to fill out forms faster</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                    <input id="google_autocomplete" type="checkbox" name="allow-customer-register" class="switch"> 
                                                    <label for="google_autocomplete"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="xverify" class="mb-0">Xverify</label>
                                        <small class="form-text text-muted">Eliminate your hard-bounces and reduce spam complaints</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                    <input id="xverify" type="checkbox" name="allow-customer-register" class="switch"> 
                                                    <label for="xverify"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="smarty_streets" class="mb-0">Smarty Streets</label>
                                        <small class="form-text text-muted">Address Verification for USPS and international addresses</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                    <input id="smarty_streets" type="checkbox" name="allow-customer-register" class="switch"> 
                                                    <label for="smarty_streets"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="Kount" class="mb-0">Kount</label>
                                        <small class="form-text text-muted">All-in-one fraud and risk management solution</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                    <input id="Kount" type="checkbox" name="allow-customer-register" class="switch"> 
                                                    <label for="Kount"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="paypal_checkout" class="mb-0">PayPal Checkout</label>
                                        <small class="form-text text-muted">Enable PayPal checkout to accept payments</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                    <input id="paypal_checkout" type="checkbox" name="allow-customer-register" class="switch"> 
                                                    <label for="paypal_checkout"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row m-0">
                                    <div class="form-group col-sm-8 p-0 m-0">
                                        <label for="promocode" class="mb-0">PROMO Code</label>
                                        <small class="form-text text-muted">Enable the PROMO/Coupon engine on your checkout</small>
                                    </div> 
                                    <div class="col-sm-4 p-0">
                                        <div class="ad-on-btn-in">
                                            <div class="slide-opt-box">
                                                <span class="switch">
                                                    <input id="promocode" type="checkbox" name="allow-customer-register" class="switch"> 
                                                    <label for="promocode"></label></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

            <!-- </div> -->
        </div>
    </div>

</div> 

<div class="payPalSettings-modal modal">
    <div class="conf-form-in modal-dialog">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mid-heading modal-custm-title">PayPal Checkout Settings</h5>
                    <img alt="" width="16px" height="16px" src="<?php echo esc_url(plugins_url('/../assets/images/close-new.svg',__FILE__)); ?>" style="cursor: pointer;" class="close_pop">
                </div> 
                <form name="unify_paypal_settings_form_post" id="unify_paypal_settings_form_post" method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" >
                    
                <div class="modal-body modal-custm-body"> 
                        <?php wp_nonce_field('unify-additional-settings-data'); ?>
                            <div class="inner-api-cont mt-4" id="additional_payment_method1_title" >
                                <div class="form-group m-0">
                                    <label for="title">Display Title</label>
                                    <small class="form-text text-muted">This controls the title which the user sees at the checkout.</small>
                                    <input type="text" id="paypal_payment_title" name="paypal_payment_title" value="<?php echo (!empty($additional_setting_option['title'])) ? esc_html($additional_setting_option['title']) : 'PayPal'; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="inner-api-cont mt-4" id="additional_payment_method1_desc">
                                <div class="form-group m-0">
                                    <label for="description">Description</label>
                                    <small class="form-text text-muted">This controls the description which the user sees at the checkout.</small>
                                    <input type="text" id="paypal_payment_description" name="paypal_payment_description" value="<?php echo (!empty($additional_setting_option['description'])) ? esc_html($additional_setting_option['description']) : 'Unify Paypal payment'; ?>" class="form-control">
                                </div>
                            </div>

                            <div class="inner-api-cont mt-4" id="additional_payment_method1_mode" >
                                <div class="form-group m-0">
                                    <label for="title">Enable Full Page</label>
                                    <small class="form-text text-muted">It will open the PayPal form in full page.</small>
                                    <div class="">
                                        <span class="switch">
                                            <input id="paypal_payment_mode" type="checkbox" value="yes" name="paypal_payment_mode" class="switch" <?php echo (!empty($additional_setting_option['paypal_payment_mode']) && $additional_setting_option['paypal_payment_mode'] == 'yes') ? 'checked="checked"' : ''; ?>> 
                                            <label for="paypal_payment_mode"></label></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="inner-api-cont mt-4" id="additional_payment_method1_desc">
                                <div class="form-group m-0">
                                    <label for="description">Button Style</label>
                                    <small class="form-text text-muted">This controls the PayPal button style which the user sees at the checkout.</small>

                                    <select name="paypal_button_size" id="paypal_button_size" class="custom-select sources" placeholder="<?php echo (esc_html(!empty($additional_setting_option['paypal_button_size_selected']) && array_key_exists($additional_setting_option['paypal_button_size_selected'], ($paypal_button_size_list)))) ? esc_html($paypal_button_size_list[$additional_setting_option['paypal_button_size_selected']]) : esc_html($paypal_button_size_list[1]); ?>">
                                            <?php
                                        foreach ($paypal_button_size_list as $k => $size)
                                        {

                                            ?>
                                            <option value="<?php echo esc_html($k); ?>"  ><?php echo esc_html($size); ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="hidden" name="paypal_button_size_selected" value="<?php echo empty($additional_setting_option['paypal_button_size_selected'])?esc_html($paypal_button_size_list[1]):esc_html($additional_setting_option['paypal_button_size_selected']); ?>" id="paypal_button_size_selected"> 

                                    <select name="paypal_button_color" id="paypal_button_color" class="custom-select sources" placeholder="<?php echo (esc_html(!empty($additional_setting_option['paypal_button_color_selected']) && array_key_exists($additional_setting_option['paypal_button_color_selected'], ($paypal_button_size_color_list)))) ? esc_html($paypal_button_size_color_list[$additional_setting_option['paypal_button_color_selected']]) : esc_html($paypal_button_size_color_list[1]); ?>">
                                            <?php
                                        foreach ($paypal_button_size_color_list as $k => $color)
                                        {

                                            ?>
                                            <option value="<?php echo esc_html($k); ?>"  ><?php echo esc_html($color); ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="hidden" name="paypal_button_color_selected" value="<?php echo empty($additional_setting_option['paypal_button_color_selected'])?esc_html($paypal_button_size_color_list[1]):esc_html($additional_setting_option['paypal_button_color_selected']); ?>" id="paypal_button_color_selected"> 
                                </div>
                            </div>                    
                </div>
                <input type="hidden" name="action" value="unify_paypal_settings_form_post" />
                <div class="modal-footer">
                    <div class="">
                        <button type="button" data-dismiss="modal" class="btn btn-link gen-col-btn-sm gray mr-2 close_pop">Cancel</button>
                        <button type="button" class="btn btn-primary gen-col-btn-sm modal-custom-act-btn" id="submit_paypal_settings">Save</button>
                    </div> 
                </div>
                </form>
            </div>
        </div>
    </div>

</div>