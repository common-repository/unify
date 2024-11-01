<?php
   use \CodeClouds\Unify\Service\Notice;
   ?>
<div class="unify-table-area dash-in uni-license" id="registrationForm">
   <?php
      if (!session_id()) { session_start(); }
      $free_trial_registered = \get_option('woocommerce_codeclouds_unify_free_trial_registation');
      $redirect_url = admin_url('admin.php?page=unify-settings&section=license-management');
      if (!empty($free_trial_registered)) { header("Location: ".$redirect_url); exit;}
      ?>
   <div class="container-fluid unify-search p-0 mb-2 uni-shadow-box" id="freeLicenseResponse" style="display: none">
   </div>
   <h3>Register Your Free Unify License</h3>
   <div class="dash-free-from blue-lt inner-white-box">
      <form name="unify_free_trial_form" id="unify_free_trial_form" method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" >
         <input type="hidden" name="action" id="action" value="unify_plugin_lead_generate" /> 
         <p>You’ll be provided with free basic support upon registering your free Unify license. </p>
         <small>(Basic support is not available for free trial users).</small>
         <div class="form-group">
            <div class="row clearfix">
               <div class="col-sm-6">
                  <label>First Name <span class="text-danger">*</span></label>
                  <span class="grp-all"><input type="text" name="first_name" id="first_name" class="fld-cst" placeholder="First Name"></span>
               </div>
               <div class="col-sm-6">
                  <label>Last Name <span class="text-danger">*</span></label>
                  <span class="grp-all"><input type="text" name="last_name" id="last_name" class="fld-cst" placeholder="Last Name"></span>
               </div>
            </div>
         </div>
         <!-- <div class="form-group">
            <label>Company Name <span class="text-danger">*</span></label>
            <span class="grp-all"><input type="email" name="company_name" id="company_name" class="fld-cst" placeholder="Company Name"></span>
         </div> -->
         <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <span class="grp-all"><input type="email" name="email_address" id="email_address" class="fld-cst" placeholder="Email"></span>
         </div>
         <div class="form-group">
            <label>Phone <span class="text-danger">*</span></label>
            <span class="grp-all"><input type="text" name="phone_number" id="phone_number" class="fld-cst" placeholder="Phone"></span>
         </div>
         <div class="form-group text-center">
            <input type="checkbox" class="form-check-input" id="privacy_policy_chkbox">
            <label class="form-check-label" for="privacy_policy_chkbox">I have read and agreed to the <a href="https://hub.unify.to/privacy-policy" target="_blank"> Privacy Policy </a> and <a href="https://hub.unify.to/terms-of-service" target="_blank">Terms & Conditions</a>.</label>
         </div>
         <div class="form-group mt-4 text-center">
            <input type="button" onclick="javascript:void(0);" value="Register License" id="register_free_trial" class=""><span class="ajax-loader"></span>
         </div>
         <?php wp_nonce_field('unify_free_trial_form_nonce'); ?>
      </form>
   </div>
</div>
<div class="modal fade" id="peogressModalEle" role="dialog" data-keyboard="false" data-backdrop="static">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title modal-title-peogressModalEle">
               <div class="div_1">Registering Your Free Unify License</div>
            </h4>
         </div>
         <div class="modal-body">
            <div id="myProgress">
               <div id="myBar"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="conf-form-out modal customModal customModal-confirm" id="progressModal" style="display: none;">
   <div class="progressSection">
      <div class="conf-form-in modal-dialog">
         <div role="document" class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title mid-heading modal-custm-title">Registering Your Free Unify License</h5>
               </div>
               <div class="modal-body modal-custm-body">
                  <div class="progressbarIn">
                     <h4>Please wait while we prepare your license...</h4>
                     <div class="progress-bar-div">
                        <div class="progress">
                           <div class="progress-bar w-25" id="progressbar-w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h2 class="progress-text">25%</h2>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>


<div class="conf-form-out modal customModal customModal-confirm" id="sucessModal" style="display: none;">
   <div class="progressSection">
      <div class="conf-form-in modal-dialog">
         <div role="document" class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title mid-heading modal-custm-title">License Key Registration Successful!</h5>
               </div>
               <div class="modal-body modal-custm-body">
                  <div class="key-registration">
                     <h3>Thank you for registering your free Unify license!</h3>
                     <h5>Your license key has been generated under <strong> Settings > License Management</strong></h5>
                     <div class="registration-button">
                        <button class="btn btn-back" onclick="goToDashBoard();">Back to Dashboard</button>
                        <button class="btn btn-view" onclick="ViewLicense();">View License Key</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>

<script type="text/javascript">
   function goToDashBoard() {
       window.location = "<?php echo esc_url(admin_url('admin.php?page=unify-dashboard')); ?>";
    }

    function ViewLicense(){
        window.location = "<?php echo esc_url_raw(admin_url('admin.php?page=unify-settings&section=license-management')); ?>";
    }
</script>