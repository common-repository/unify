<?php
use CodeClouds\Unify\Service\Request;
use \CodeClouds\Unify\Service\Helper;
$showProMsg = Helper::getProMsg();
$page_array = ['unify-connection','unify-tools','unify-settings'];
$section_array = ['license-management'];

if($showProMsg ==1){ 
    if(in_array(Request::get('page'), $page_array)){
        if( (Request::get('section')!=='request-cancellation')){
            header("Location: ".admin_url('admin.php?page=unify-dashboard'));
            die();
        }
    }
?>
<div class="container-fluid general-bg unify-search p-0 mb-2 uni-shadow-box unify-lead-notice">
    <div class="row clearfix m-0">
        <div class="col-12 text-general general-bg-text ">
            <p>
                Your license has been successfully upgraded to Unify Pro! <span onclick="startTransefer();">Start Migration →</span>
            </p> 
             <span class="cross-position"><img alt="" width="10" height="10" src="<?php echo esc_url(plugins_url('/../../assets/images/close-white.svg',__FILE__)); ?>" style=""></span>
        </div>
    </div>
</div>
<?php }?>



