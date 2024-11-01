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
   <div class="container-fluid unify-search p-0 mgbt-25 uni-shadow-box">
      <div class="row clearfix m-0">
         <div class="col-12 unify-top-search-left pr-0 pl-0">
            <div class="unify-white-menu clearfix">
               <ul class="option-row-simple-menu">
                <?php if(empty($pro_license['license_key'])){ ?>
                  <li class="btn btn-link <?php echo (empty($_GET['section']))?'active' : ''; ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=unify-settings'))?>">General</a></li>
                  <li class="btn btn-link <?php echo ((isset($_GET['section'])&& $_GET['section']==='license-management'))?'active' : ''; ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=unify-settings&section=license-management'))?>">License Management</a></li>
                <?php }?>
                  <li class="btn btn-link <?php echo ((isset($_GET['section'])&& $_GET['section']==='update-pro'))?'active' : ''; ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=unify-settings&section=update-pro'))?>">Update to Pro</a></li>
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
                        <h3 class="mid-blue-heading" >Pro Settings</h3>
                         <div class="inner-api-cont mt-4" >
                            <div class="form-group m-0">
                              <label for="title">Unify Pro License Key</label>
                               <textarea id="unify_pro_license_key" value="" class="form-control" <?php echo (!empty($pro_license) ? 'readonly' : '') ?>><?php echo (!empty($pro_license['license_key'])) ? esc_html($pro_license['license_key']) : ''; ?></textarea>
                            </div>
                         </div>
                          <div class="inner-api-cont mt-4">
                                <div class="form-group m-0">
                                    <label for="title">Domain</label>
                                    <input type="text" id="unify_domain_name" value="<?php echo (!empty($pro_license['domain_name'])) ? esc_html($pro_license['domain_name']) : ''; ?>" class="form-control">
                                </div>
                          </div>
                         <div class="upl-cnt-btn text-center mgtp-20">
                            <div class="validated_msg" style="display: none;"></div>
                            <div class='overlayDiv' style="display: none;z-index: 9999999999;"><div class='ajax-loader'><center> <img class='ajax-loader-image' src='<?php echo esc_url(plugin_dir_url( __DIR__ ))?>assets/images/loading.gif' alt='loading..' width='16px' height='16px'></center></div></div>
                            <button type="button" id="redeemLicensebtn" class="btn btn-primary gen-col-btn-sm" onclick="redeemLicense();">
                            Redeem
                            </button>
                         </div>
                      </div>
            </div>
         </div>
      </div>
   </div>
</div>
