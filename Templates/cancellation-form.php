<?php
   use \CodeClouds\Unify\Service\Notice;
   ?>
<div class="unify-table-area dash-in uni-license" id="registrationForm">
   <?php
      if (!session_id()) { session_start(); }
      ?>
  <div class="request_cancel_form">
   <h3>Request Cancellation</h3>
   <div class="dash-free-from blue-lt inner-white-box">
      <form name="request_cancellation_form" id="request_cancellation_form" method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" >
         <p>We’re sad to see you go! Please let us know why you are cancelling and how we can further improve our platform and services. </p>

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
            <label>Email <span class="text-danger">*</span></label>
            <span class="grp-all"><input type="email" name="email" id="email_address_cancellation" class="fld-cst" placeholder="Email"></span>
         </div>
         <div class="form-group">
            <label>Phone <span class="text-danger">*</span></label>
            <span class="grp-all"><input type="text" name="mobile" id="phone_number" class="fld-cst" placeholder="Phone"></span>
         </div>
         <div class="form-group">
            <label>Reason For Cancellation<span class="text-danger">*</span></label>
            <span class="grp-all"><textarea id="comment" cols="50" rows="50" name="reason" value="" class="form-control fld-cst" placeholder="Leave a comment"></textarea></span>
         </div>

         <div class="form-group mt-4 text-center">
            <div class="validated_msg" style="display: none;"></div>
            <div class='overlayDiv' style="display: none;z-index: 9999999999;"><div class='ajax-loader'><center> <img class='ajax-loader-image' src='<?php echo esc_url(plugin_dir_url( __DIR__ ))?>assets/images/loading.gif' alt='loading..' width='16px' height='16px'></center></div></div>
            <button type="button" id="submit_cancellation" class="btn btn-primary gen-col-btn-sm" >
                            Submit Request
                            </button>
            <span class="ajax-loader"></span>
         </div>
      </form>
   </div>
 </div>

 <div class="upgrade-request" id="upgrade-request" style="display: none;">
   <div class="upgrade-icon"><i class="far fa-check-circle"></i></div>
      <h3 class="upgrade-heading">Cancellation Request Sent Successfully!</h3>
      <div class="upgrade-request-box">
         <p> If you have doubts, questions or concerns please
let us know at <a href="mailto:tech@unify.to" style="color: #5C79FF;text-decoration: none;">tech@unify.to</a></p>
        <button class="btn upgrade-btn" onclick="goToDashBoard();">Back to Dashboard</button>
   </div>
</div>

</div>


<script type="text/javascript">
   function goToDashBoard() {
       window.location = "<?php echo esc_url(admin_url('admin.php?page=unify-dashboard')); ?>";
    }
</script>






