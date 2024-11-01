<?php
use \CodeClouds\Unify\Service\Helper;
$remaining_days = Helper::getTrialNotice();
$free_trial_license_data = \get_option('woocommerce_codeclouds_unify_free_trial_registation');
$msg = '';
//$remaining_days = -1;

if(!empty($remaining_days) && empty($free_trial_license_data)){
	if($remaining_days>0){
	      $msg = 'will expire';
	}else{
		   $msg = 'trialexpired';
         }
 }
 ?>

<?php if($msg!=''){?>
<div class="container-fluid general-bg unify-search p-0 mb-2 uni-shadow-box unify-lead-notice">
    <div class="row clearfix m-0">
        <div class="col-12 text-general general-bg-text ">
          <p>
        <?php if(!empty($remaining_days) && empty($free_trial_license_data)){
             if($remaining_days>0){
                 include_once __DIR__ . '/lead-notice-msgone.php'; 
             }
            else{
                include_once __DIR__ . '/lead-notice-msgtwo.php'; 
            }
        } ?>
         </p>
            <span class="cross-position"><img alt="" width="10" height="10" src="<?php echo esc_url(plugins_url('/../../assets/images/close-white.svg',__FILE__)); ?>" style=""></span>
        </div>
    </div>
</div>
<?php }?>



<?php if(!empty($remaining_days) && $remaining_days<=0 && empty($free_trial_license_data)){?>
	<script type="text/javascript">
	window.onload = function(){
    var buttons = document.getElementsByClassName("btn"),
        len = buttons != null ? buttons.length : 0,
        i = 0;
    for(i; i < len; i++){
        if(buttons[i].classList.contains("registerFreeLicense")){continue;}
        buttons[i].className += " disablePayPalSettings"; 
    }
};
</script>
<?php }?>

