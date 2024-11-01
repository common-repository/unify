<?php
   use \CodeClouds\Unify\Service\Notice;
   ?>
<div class="unify-table-area dash-in uni-license" id="registrationForm">
   <?php
      if (!session_id()) { session_start(); }
      ?>
  <div class="request_pro_plugin_form">
   <h3>Upgrade to Unify Pro</h3>
   <div class="dash-free-from blue-lt inner-white-box">
      <form name="request_unify_pro_form" id="request_unify_pro_form" method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" >
         <p>Interested in Unify Pro features? Request an upgrade to through this form! </p>

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
         <div class="form-group">
            <label>Business/Company <span class="text-danger">*</span></label>
            <span class="grp-all"><input type="text" name="company_name" id="company_name" class="fld-cst" placeholder="Business/Company Name"></span>
         </div>
         <div class="form-group">
            <div class="row clearfix">
               <div class="col-sm-6">
                  <label>Email <span class="text-danger">*</span></label>
                  <span class="grp-all"><input type="email" name="email_address" id="email_address_pro" class="fld-cst" placeholder="Email"></span>
               </div>
               <div class="col-sm-6">
                  <label>Phone <span class="text-danger">*</span></label>
                  <span class="grp-all"><input type="text" name="phone_number" id="phone_number" class="fld-cst" placeholder="Phone"></span>
               </div>
            </div>
         </div>
         <div class="form-group">
            <label>Message<span class="text-danger">*</span></label>
            <span class="grp-all"><textarea id="comment" cols="50" rows="50" name="comment" value="" class="form-control fld-cst" placeholder="Leave a comment"></textarea></span>
         </div>
         
         <div class="form-group text-center privacy_policy_chkbox_div">
            <input type="checkbox" name="privacy_policy_chkbox" class="form-check-input" id="privacy_policy_chkbox" title="Privacy Policy is a required field!">
            <label class="form-check-label" for="privacy_policy_chkbox">I have read and agreed to the <a href="https://hub.unify.to/privacy-policy" target="_blank"> Privacy Policy </a> and <a href="https://hub.unify.to/terms-of-service" target="_blank">Terms & Conditions</a>.</label>
         </div>
         <div class="form-group mt-4 text-center">
            <div class="validated_msg" style="display: none;"></div>
            <div class='overlayDiv' style="display: none;z-index: 9999999999;"><div class='ajax-loader'><center> <img class='ajax-loader-image' src='<?php echo esc_url(plugin_dir_url( __DIR__ ))?>assets/images/loading.gif' alt='loading..' width='16px' height='16px'></center></div></div>
            <button type="button" id="submit_unify_pro" class="btn btn-primary gen-col-btn-sm upgrade-btn-new" >
                            Submit Upgrade Request
                            </button>
            <span class="ajax-loader"></span>
         </div>

      </form>
   </div>
 </div>

 <div class="upgrade-request" id="upgrade-request" style="display: none;">
   <div class="upgrade-icon"><i class="far fa-check-circle"></i></div>
      <h3 class="upgrade-heading">Upgrade Request Sent Successfully!</h3>
      <div class="upgrade-request-box">
        <p>Thank you for submitting your request to upgrade to Unify Pro, we appreciate it! </p> <p> You will receive a response from our sales team members shortly. If you have any further questions or concerns, please reach out to <a href="mailto:tech@unify.to" style="color: #5C79FF;text-decoration: none;">tech@unify.to</a></p>
        <button class="btn upgrade-btn" onclick="goToDashBoard();">Back to Dashboard</button>
   </div>
</div>
</div>







<script type="text/javascript">
   function goToDashBoard() {
       window.location = "<?php echo esc_url(admin_url('admin.php?page=unify-dashboard')); ?>";
    }
</script>