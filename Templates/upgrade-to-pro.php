<?php
use \CodeClouds\Unify\Service\Request;
use \CodeClouds\Unify\Service\Notice;
?>
<div class="unify-table-area dash-in dashboard">
  <?php 
      
      if (!session_id()) { session_start(); }
      if(Notice::hasFlashMessage('unify_notification')){
        include_once __DIR__ . '/Notice/notice.php';
      }
    
    ?>
    <?php 
    include_once __DIR__ . '/Notice/pro-msg.php';
    include_once __DIR__ . '/modals.php';
    ?>
    <div class="container-fluid unify-table p-0 tran-bg-in ">
        <div class="row clearfix m-0">

            <div class="col-md-12 pl-0 pr-0">
              <div class="feature-list">
                <div class="featureList-heading text-center">
                  <h3>Upgrade to Unify Pro!</h3>
                  <p>Empower your checkout with these great additional features. <?php echo ($upgrde_request_sent == 1)?"":'<span class="_have_license_key" onclick="goToPro();">Get Unify Pro today →</span>'; ?>
                </div>
                <div class="crd-white-box  feature-list-in">
                    <div class="row-comparison-table clearfix">
                      <div class="comparison-table">
                        <div class="features-strock-table">
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <strong>Features List</strong>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Connect Your Checkout to a CRM (sticky.io, Konnektive or Response CRM)</div>
                          </div>
                           <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Import or Export Product Mapping Data</div>
                          </div>
                           <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Support for Tax Profiles</div>
                          </div>
                           <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Support for Billing Model</div>
                          </div>
                           <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Support for Wix, Clickfunnels & Other Ecommerce Platforms</div>
                          </div>
                           <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Build Custom Ecommerce Checkouts</div>
                          </div>
                           <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Create & Manage Subscription-Based Products</div>
                          </div>
                           <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Create & Manage Product Variants</div>
                          </div>
                           <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Built-In Customer/Membership Portal</div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Lightweight Support Request System</div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">Integrate One-Click Post-Purchase Upsells, Downsells & Cross-Sells</div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col min-height-80">20+ Add-ons Including: SmartyStreets (Address Validation), Promo Code, Google reCAPTCHA, Tip Manager, Order Gifting and more!</div>
                          </div>
                        </div>
                      </div>
                      <div class="comparison-table-small comparison-table-small-mdl text-center">
                        <div class="features-strock-table">
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <strong>Unify Free</strong>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col min-height-80">
                              <i class="fas fa-minus"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="comparison-table-small comparison-table-small-lst text-center blue-tbl">
                        <div class="features-strock-table">
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                              <strong>Unify Pro</strong>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                          <div class="features-strock-table-row">
                            <div class="features-strock-table-col">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                            <div class="features-strock-table-row">
                            <div class="features-strock-table-col min-height-80">
                             <i class="fa fa-check"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <?php 
                if($upgrde_request_sent == 1){?>
                  <div class="upgrade-list">
                  <h5>Upgrade Request Sent <i class="fa fa-check"></i></h5>
                  <div class="upgrade-cnt">
                    <p>Thank you for submitting your request. It may take up to 24 hours for you to receive a response.<br> If you have any questions or concerns, please reach out to <a href="mailto:tech@unify.to"> tech@unify.to</a></p>
                  </div>
                  <div class="_have_license_key" onclick="openModal('proLicenseModal');">I’ve already received my license key!</div>
                </div>
                <?php
                  }else{?>
                      <div class="upgrade-list">
                  <div class="upgrade-cnt">
                    <a class="upgrade-to-pro-form" href="<?php echo esc_url(admin_url('admin.php?page=unify-upgrade-to-pro&section=request-pro')); ?>" class="btn btn-primary btn-block">Upgrade to Unify Pro
                    </a>
                  </div>
                  <div class="_have_license_key" onclick="openModal('proLicenseModal');">I’ve already received my license key!</div>
                </div>
                  <?php }
                ?>
                
                
              </div>
            </div>
        </div>
    </div>
</div> 

<script type="text/javascript">
   function goToPro() {
       window.location = "<?php echo esc_url_raw(admin_url('admin.php?page=unify-upgrade-to-pro&section=request-pro')); ?>";
    }
</script>

