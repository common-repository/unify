<?php
   use \CodeClouds\Unify\Service\Notice;
   ?>
<div class="unify-table-area dash-in">
   <div class="container-fluid unify-mid-heading p-0 mb-4">
      <div class="row">
         <div class="col-12">
            <div class="page-block-top-heading clearfix">
               <h2 class="mid-heading">Settings&nbsp;&nbsp;</h2>
            </div>
         </div>
      </div>
   </div>
   <?php include_once __DIR__ . '/Notice/lead_notice.php';?>
   <?php include_once __DIR__ . '/Notice/pro-msg.php';?>
   <?php include_once __DIR__ . '/modals.php';?>
   <div class="container-fluid unify-search p-0 mgbt-25 uni-shadow-box">
      <div class="row clearfix m-0">
         <div class="col-12 unify-top-search-left pr-0 pl-0">
            <div class="unify-white-menu clearfix">
               <ul class="option-row-simple-menu">
                  <li class="btn btn-link <?php echo (empty($_GET['section']))?'active' : ''; ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=unify-settings'))?>">General</a></li>
                  <li class="btn btn-link <?php echo ((isset($_GET['section'])&& $_GET['section']==='license-management'))?'active' : ''; ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=unify-settings&section=license-management'))?>">License Management</a></li>
               </ul>
            </div>
         </div>
      </div>
   </div>
   <?php
      if (!session_id()) { session_start(); }
      
      ?>
   <div class="container-fluid unify-table p-0 tran-bg-in ">
      <div class="row clearfix m-0">
         <div class="col-md-6 pl-0 pr-2 ">
            <div class="crd-white-box  border-0 bottom-mg-gap">
                    <div class="inner-white-box uni-shadow-box">
                        <h3 class="mid-blue-heading" >Unify License Key</h3>
                        <?php
                        if(empty($free_trial_license_data)){?>
                         <div class="inner-api-cont mt-4" >
                            <div class="form-group m-0">
                               <p>It seems like you have not registered your free Unify license yet!</p>
                               <p>
                                <?php if($remaining_days>0){?>
                                  Your free trial will expire in <?php echo esc_html($remaining_days);?> days.
                                <?php }else{?>
                                  Your free trial has expired.
                                  <?php }?>
                              </p>            
                            </div>
                         </div>
                         <div class="upl-cnt-btn text-center mgtp-20">
                            <button type="button" class="btn btn-primary gen-col-btn-sm registerFreeLicense" id="submit_settings" onclick="goToRegister();">
                            Register 
                            <span class=""></span>
                            </button>
                         </div>
                  <?php }else{ ?>
                         <div class="inner-api-cont mt-4" >
                            <div class="form-group m-0">
                               <p>If you have any technical issues, require support or have questions about Unify, please contact our support team <a href="mailto:tech@unify.to" style="color: #212D3D;text-decoration: underline;">tech@unify.to</a> using your registered email.</p>
                               <input type="text" id="unify_license_key" value="<?php echo esc_html($free_trial_license_data['free_license_key'])?>" class="form-control" readonly>
                               <span onclick="copyToClipBoard()"><i class="far fa-copy"></i></span>
                            </div>
                         </div>
                         <div class="inner-api-cont mt-4" >
                            <div class="form-group m-0">
                               <label for="title" class="cursorNonPointer">License Type: <strong>Unify <?php echo esc_html($free_trial_license_data['license_type'])?></strong></label>            
                            </div>
                         </div>
                         <div class="inner-api-cont mt-4" >
                            <div class="form-group m-0">
                               <label for="title" class="cursorNonPointer">Registered Email: <strong><?php echo esc_html($free_trial_license_data['email_address'])?></strong></label>            
                            </div>
                         </div>
                         <div class="inner-api-cont mt-4" >
                            <div class="form-group m-0">
                               <label for="title" class="cursorNonPointer _have_license_key" onclick="openModal('proLicenseModal');">I have my Unify Pro license key!</label>            
                            </div>
                         </div>
                      </div>
                  <?php }?>
                  
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript">
   function goToRegister() {
       window.location = "<?php echo esc_url_raw(admin_url('admin.php?page=unify-dashboard&section=free-trial-license-registration')); ?>";
    }
</script>