<?php
   use \CodeClouds\Unify\Service\Request;
   use \CodeClouds\Unify\Service\Notice;
   ?>
<div class="unify-table-area dash-in dashboard">
   <div class="container-fluid unify-mid-heading p-0 mb-4">
      <div class="row">
         <div class="col-12">
            <div class="page-block-top-heading clearfix">
               <h2 class="mid-heading">Dashboard</h2>
            </div>
         </div>
      </div>
   </div>
   <?php 
      if (!session_id()) { session_start(); }
      if(Notice::hasFlashMessage('unify_notification')){
        include_once __DIR__ . '/Notice/notice.php';
      }
      include_once __DIR__ . '/modals.php';
      ?>
    
   <?php if($config_transferred==1){?>
   <div class="container-fluid unify-search p-0 bottom-mg-gap uni-shadow-box">
        <div class="row clearfix">
            <div class="col-sm-12">
                <div class="unify-data-transfer text-center">
                    <div class="data-transfer">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h4 class="unify-wp-head">Your data has been transferred to Unify Checkout!</h4>
                    <p class="unify-wp-cnt m-0 p-0">Head over to Unify Hub to access your checkout instance!</p>
                    <a type="btn" class="btn unify-wp-btn" target="_blank" href="<?php echo esc_html(UNIFY_PLATFORM_LOGIN) ?>">Go to Unify Hub</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid unify-search p-0 bottom-mg-gap uni-shadow-box">
        <div class="row clearfix">
            <div class="col-sm-12">
                <div class="unify-data-transfer text-center">
                
                    <p class="unify-wp-cnt m-0 p-0">Not satisfied with your experience Unify Checkout? If you have doubts, questions or concerns please <br> let us know at <span class="clr-black"> tech@unify.to, </span> otherwise:</p>
                    <div class="cancel-links">
                   <a href="<?php echo esc_url(admin_url('admin.php?page=unify-dashboard&section=request-cancellation')); ?>">Request Cancellation</a>
                   <div class="_have_license_key" onclick="openModal('downgradeModal');">Downgrade to Unify Wordpress Free</div>
               </div>
                </div>
            </div>
        </div>
    </div>
  <?php }else{?>

    <div class="container-fluid general-bg unify-search p-0 mb-2 uni-shadow-box unify-lead-notice">
      <div class="row clearfix m-0">
          <div class="col-12 text-general general-bg-text ">
              <p>
                  Your license has been successfully upgraded to Unify Pro! <span class="" onclick="startTransefer();" style="cursor: pointer;">Start Migration →</span>
              </p> 
               <span class="cross-position"><img alt="" width="10" height="10" src="<?php echo esc_url(plugins_url('/../../assets/images/close-white.svg',__FILE__)); ?>" style=""></span> 
          </div>
      </div>
    </div>
    <div class="container-fluid unify-search p-0 bottom-mg-gap uni-shadow-box">
        <div class="row clearfix m-0">
            <div class="col-12 unify-top-search-left pr-0 pl-0">
                <ul class="dash-top-box">
                    <li class="inner-white-box big-box">
                        <h2 class="lg-bld-heading m-0">Hi there, <?php echo esc_html(ucfirst($current_user->display_name)); ?></h2>
                        <span class="quick-txt">Here’s a quick look at your current integrations and products mapped in Unify Pro <span class="arrow-int">&#8594;</span></span> </li>
                    <li class="inner-white-box text-center">
                         <span class="out-value"><?php echo esc_html($mapped_product->post_count); ?></span>
                        <span class="out-text">Products Mapped</span>
                    </li>
                    <li class="inner-white-box text-center">
                        <span class="out-value"><?php echo esc_html($total_publish_posts); ?></span>
                        <span class="out-text">Total Integrations</span>
                    </li>
                    <li class="inner-white-box text-center">
                        <span class="out-value"><?php echo esc_html($todays_order_count); ?></span>
                        <span class="out-text">Orders Processed Today</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php }?>





   
</div>

