<?php
use \CodeClouds\Unify\Service\Notice;

$notice = Notice::getFlashMessage('unify_notification');
$undo_id = (Notice::hasFlashMessage('undo_id')) ? Notice::getFlashMessage('undo_id') : '';
$undo_action = (Notice::hasFlashMessage('undo_action')) ? Notice::getFlashMessage('undo_action') : '';
switch ($notice['msg_type'])
{
	case 'error':
?>
<div class="container-fluid  danger-bg unify-search p-0 mb-2 uni-shadow-box">
	<div class="row clearfix m-0">
		<div class="col-12 text-danger danger-bg-text ">
			<p><?php echo esc_html($notice['msg_txt']); ?>
				<?php if(!empty($undo_id) && !empty($undo_action)){ ?>
					<a class="change-pre" id="click_undo_<?php echo esc_html($undo_action); ?>" data-undo_id="<?php echo esc_html($undo_id); ?>" href="javascript:void(0);">Undo</a>
				<?php } ?>
			</p> 
			<span class="cross-position"><img alt="" width="10" height="10" src="<?php echo esc_url(plugins_url('/../../assets/images/close-red.svg',__FILE__)); ?>" style=""></span>
		</div>
	</div>
</div>
<?php
		break;
	case 'success':
?>
<div class="container-fluid  success-bg unify-search p-0 mb-2 uni-shadow-box">
	<div class="row clearfix m-0">
		<div class="col-12 success-bg-text text-success">
			<p><?php echo esc_html($notice['msg_txt']); ?>
				<?php if(!empty($undo_id) && !empty($undo_action)){ ?>
					<a class="change-pre" id="click_undo_<?php echo esc_html($undo_action); ?>" data-undo_id="<?php echo esc_html($undo_id); ?>" href="javascript:void(0);">Undo</a>
				<?php } ?>
			</p>
			<span class="cross-position"><img alt="" width="10" height="10" src="<?php echo esc_url(plugins_url('/../../assets/images/close-green.svg',__FILE__)); ?>" style=""></span>
		</div>
	</div>
</div>
<?php
		break;
}

Notice::destroyFlashMessage('unify_notification');
if(Notice::hasFlashMessage('undo_id')){
	Notice::destroyFlashMessage('undo_id');
}
if(Notice::hasFlashMessage('undo_action')){
	Notice::destroyFlashMessage('undo_action');
}
?>