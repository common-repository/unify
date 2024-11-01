
<!--Pro license activation modal -->
<div class="modal fade unify-wp-modal" id="proLicenseModal" tabindex="-1" role="dialog"  >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close unify-wp-close" data-dismiss="modal" aria-label="Close" onclick="closeModal('proLicenseModal');">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal-body-in">
            <div class="lock"><i class="fas fa-key"></i></div>
            <h4 class="unify-wp-head">Enter Your Unify Pro License</h4>
            <p class="unify-wp-cnt">Copy your key your hosted platform and paste it here:</p>
            <div class="modal-body-row">
              <textarea id="unify_pro_license_key" class="unify-wp-input form-control fld-cst" value="" placeholder="Unify Pro License Key" style="resize: none;"></textarea>
            </div>
            <div class="modal-body-row">
              <input type="text" id="unify_domain_name" onkeyup="validate_endpoint(this)" value="" class="unify-wp-input" placeholder="Domain">
            </div>
            <div class="validated_msg" style="display: none;"></div>
            <div class='overlayDiv' style="display: none;z-index: 9999999999;"><div class='ajax-loader'><center> <img class='ajax-loader-image' src='<?php echo esc_url(plugin_dir_url( __DIR__ ))?>assets/images/loading.gif' alt='loading..' width='16px' height='16px'></center></div></div>
            <button type="btn" id="redeemLicensebtn" class="btn unify-wp-btn" onclick="redeemLicense();">Activate License</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End pro license activation modal -->




<!--Pro license success modal -->
<div class="modal fade unify-wp-modal" id="proLicenseSuccessModal" tabindex="-1" role="dialog"  >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close unify-wp-close" data-dismiss="modal" aria-label="Close" onclick="closeModal('proLicenseSuccessModal');goToDashBoard();">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal-body-in">
           <div class="succ-act"><i class="far fa-check-circle"></i></div>
            <h4 class="unify-wp-head">Woohoo! We’ve successfully activated your license!</h4>
            <div class="unify-wp-cnt">
              <p>To start the migration process, click the button below to start transferring your data from Wordpress/WooCommerce to Unify Checkout! </p>

             <p>Not quite ready yet? No worries, your Wordpress instance is still fully functional.</p>
            </div>
        
            <button type="btn" class="btn unify-wp-btn" onclick="startTransefer();">Start Transfer &#8594;</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End pro license success modal -->



<!--Transfering modal -->
<div class="modal fade unify-wp-modal" id="transeferringModal" tabindex="-1" role="dialog"  >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close unify-wp-close" data-dismiss="modal" aria-label="Close" onclick="closeModal('transeferringModal');goToDashBoard()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal-body-in">
            <div id="animation_container" style="background-color:rgba(255, 255, 255, 1.00); width:375px; height:130px; margin:0px auto">
              <canvas id="canvas" width="375" height="130" style="position: absolute; display: block; background-color:rgba(255, 255, 255, 1.00);"></canvas>
              <div id="dom_overlay_container" style="pointer-events:none; overflow:hidden; width:375px; height:130px; position: absolute; left: 0px; top: 0px; display: block;">
            </div>
        </div>
        <div class="modal-progress">
          <p class="progress-text">25%</p>
        <div class="progress">
          <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
        <p class="product-info">Transferring your product information…</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Transfering modal -->



<!--Transfer complete modal -->
<div class="modal fade unify-wp-modal" id="transeferCompleteModal" tabindex="-1" role="dialog"  >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close unify-wp-close" data-dismiss="modal" aria-label="Close" onclick="closeModal('transeferCompleteModal');goToDashBoard();">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal-body-in">
           <div class="succ-act"><i class="far fa-check-circle"></i></div>
            <h4 class="unify-wp-head">Transfer Complete!</h4>
            <p class="unify-wp-cnt m-0 p-0">Your data was successfully transferred to Unify Checkout hosted platform!</p>
        
            <a type="btn" class="btn unify-wp-btn" target="_blank" href="<?php echo esc_html(UNIFY_PLATFORM_LOGIN) ?>">Go to Unify Hub</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Transfer complete modal -->


<!--Downgrade modal -->
<div class="modal fade unify-wp-modal" id="downgradeModal" tabindex="-1" role="dialog"  >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close unify-wp-close" data-dismiss="modal" aria-label="Close" onclick="closeModal('downgradeModal');goToDashBoard();">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal-body-in">
           <div class="down-grade"><i class="fas fa-cart-arrow-down"></i></div>
            <h4 class="unify-wp-head">Downgrade to Unify Wordpress Free</h4>
            <p class="unify-wp-cnt m-0 p-0">We’re sad to see you go, are you sure you want to downgrade to Unify Free plugin? You will no longer have access to Unify Checkout and it’s pro features! <br><br> Click the button below to start migrating your data back to Wordpress/WooCommerce.</p>
        
            <button type="btn" class="btn unify-wp-btn" onclick="downgrade();">Start Transfer →</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Downgrade modal -->


<!--Rollback modal -->
<div class="modal fade unify-wp-modal" id="rollBackModal" tabindex="-1" role="dialog"  >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close unify-wp-close" data-dismiss="modal" aria-label="Close" onclick="closeModal('rollBackModal');goToDashBoard();">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal-body-in">
           <div class="succ-act"><i class="far fa-check-circle"></i></div>
            <h4 class="unify-wp-head">Rollback Complete!</h4>
            <div class="unify-wp-cnt pb-1">
              <p class="p-0">We’re sad to see you go! Let us know why you’ve made this decision at <a style="color: #212D3D; text-decoration: underline;" href="mailto:tech@unify.to"> tech@unify.to</a> so we can further improve our platform and services.</p>
            </div>
            <a type="btn" class="btn unify-wp-btn" href="<?php echo esc_url(admin_url('admin.php?page=unify-dashboard')) ?>">Go to Dashboard</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Downgrade modal -->

<div class="modal fade unify-wp-modal" id="TransferFailedModal" tabindex="-1" role="dialog"  >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close unify-wp-close" data-dismiss="modal" aria-label="Close" onclick="closeModal('TransferFailedModal');goToDashBoard();">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="modal-body-in">
           <div class="succ-act"><i class="fas fa-times"></i></div>
           <div class="transfer_fail"></div>
        
            <a type="btn" class="btn unify-wp-btn" href="<?php echo esc_url(admin_url('admin.php?page=unify-dashboard')) ?>">Go to Dashboard</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
   function goToDashBoardDelay() {
    setTimeout(function(){
            window.location = "<?php echo esc_url(admin_url('admin.php?page=unify-dashboard')); ?>";
          }, 3000);
       
    }

    function goToDashBoard() {
       window.location = "<?php echo esc_url(admin_url('admin.php?page=unify-dashboard')); ?>";
    }
</script>