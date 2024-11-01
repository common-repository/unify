<?php

use \CodeClouds\Unify\Service\Notice;
use \CodeClouds\Unify\Service\Helper;
use \CodeClouds\Unify\Model\ConfigEncryption;

?>
<form name="connection-list-form" id="connection-list-form" method="GET" action="<?php echo esc_url(admin_url('admin.php')); ?>" >
	<!--<input type="hidden" name="post_type" id="post_type" value="unify_connections" />-->
	<input type="hidden" name="action" id="action" value="unify_connections" />

	<input type="hidden" name="page" value="unify-connection" />
	<input type="hidden" name="paged" id="paged" value="<?php echo esc_html($request['paged']) ?>" />	
	<input type="hidden" name="posts_per_page" id="posts_per_page" value="<?php echo esc_html($request['posts_per_page']) ?>" />

	<input type="hidden" name="orderby" id="orderby" value="<?php echo esc_html($request['orderby']); ?>" />
	<input type="hidden" name="order" id="order" value="<?php echo esc_html($request['order']); ?>" />	

	<input type="hidden" name="m" id="m" value="<?php echo esc_html($request['m']) ?>" />
	<input type="hidden" name="post_status" value="<?php echo (esc_html(empty($request['post_status']))) ? '' : esc_html($request['post_status']) ?>" />

	<div class="unify-table-area dash-in">
		<div class="container-fluid unify-mid-heading p-0 mb-4">
			<div class="row">
				<div class="col-12">
					<div class="page-block-top-heading clearfix">
						<h2 class="mid-heading">Integrations</h2></div>
				</div>
			</div>
		</div>
		<?php include_once __DIR__ . '/Notice/lead_notice.php';?>
		<?php include_once __DIR__ . '/Notice/pro-msg.php';?>
		<div class="container-fluid unify-search p-0 mb-2 uni-shadow-box"><div class="row clearfix m-0">
				<div class="col-7 unify-top-search-left pr-0 pl-0">
					<div class="unify-search-col clearfix">
						<div class="unify-search-left">
							<input type="checkbox" name="bulk_chk" value="" id="bulk_chk">
						</div>

						<div class="dropdown-box-group">
							<div class="dropdown dropdown-opt">
								<button type="button" data-toggle="dropdown" id="bulk-act-btn" class="btn btn-light dropdown-toggle bulk-act-txt"> Bulk Actions </button>
								<div class="dropdown-menu uni-shadow-box" id="bulk-act-opt" style="background: rgb(255, 255, 255); display: none;">
									<a class="dropdown-item bulk-act" id="bulk-act" data-val="Bulk Actions" >Bulk Actions</a>
									<!--<a class="dropdown-item ">Edit</a>-->									
<?php echo (!empty($_GET['post_status']) && $_GET['post_status'] == 'trash') ? '<a class="dropdown-item bulk-act open_modal_pop" id="bulk-restore" data-action="bulk-restore" data-val="Restore" >Restore</a>' : '<a class="dropdown-item bulk-act open_modal_pop" id="bulk-trash" data-action="bulk-delete" data-val="Move to Trash" >Move to Trash</a>'; ?>
								</div> 
							</div>
							<div class="dropdown dropdown-opt">
								<label>Show&nbsp;  | </label>
								<button type="button" data-toggle="dropdown" class="btn btn-light dropdown-toggle" id="list-show-btn" > <?php echo esc_html($request['posts_per_page']) ?> </button>
								<div class="dropdown-menu uni-shadow-box" id="list-show-opt" style="background: rgb(255, 255, 255); display: none;">
									<a class="dropdown-item num-page change-posts-per-page" val="10" >10</a>
									<a class="dropdown-item num-page change-posts-per-page" val="20" >20</a>
									<a class="dropdown-item num-page change-posts-per-page" val="50" >50</a>
								</div> 
							</div>
							
						</div>
					</div>
				</div>
				<div class="col-5 unify-top-search-right pl-0 pr-0">
					<div class="unify-search-right">
						<input type="text" id="search" name="s" value="<?php echo (!empty($_GET['s']) ? esc_html(sanitize_text_field(wp_unslash($_GET['s']))): '') ?>" placeholder="Search...">
						<button type="submit" class="cst-top-search-btn btn btn-primary"><i class="fas fa-search"></i></button>
					</div>
					<div class="add-configuration-inner"><a href="<?php echo esc_url(admin_url('admin.php?page=unify-connection&section=create-connection')); ?>" class="btn btn-primary btn-block">New Configuration</a></div>
				</div>
			</div>

		</div>

		<?php
		if (!session_id())
		{
			session_start();
		}
		if (Notice::hasFlashMessage('unify_notification'))
		{
			include_once __DIR__ . '/Notice/notice.php';
		}

		?>

		<div class="container-fluid tran-bg-in p-0 mb-2">
			<div class="row clearfix">
				<div class="col-12">
					<ul class="brdc-mid">
						<li class=""><a href="<?php echo esc_url(admin_url('admin.php?page=unify-connection')); ?>" class="<?php echo (empty($request['post_status'])) ? 'active-in' : ''; ?>" aria-current="">All <span class="count">(<?php echo esc_html($all_count); ?>)</span></a></li>
						<?php if (!empty($connection_counts->publish))
						{ ?>
							| <li class=""><a href="<?php echo esc_url(admin_url('admin.php?page=unify-connection&post_status=publish')); ?>" class="<?php echo (!empty($request['post_status']) && $request['post_status'] == 'publish') ? 'active-in' : ''; ?>" >Published <span class="count">(<?php echo esc_html($connection_counts->publish); ?>)</span></a></li>
						<?php } ?>
						<?php if (!empty($connection_counts->active))
						{ ?>
							| <li class=""><a href="<?php echo esc_url(admin_url('admin.php?page=unify-connection&post_status=active')); ?>" class="<?php echo (!empty($request['post_status']) && $request['post_status'] == 'active') ? 'active-in' : ''; ?>" >Active <span class="count">(<?php echo esc_html($connection_counts->active); ?>)</span></a></li>
						<?php } ?>
						<?php if (!empty($connection_counts->draft))
						{ ?>
							| <li class=""><a href="<?php echo esc_url(admin_url('admin.php?page=unify-connection&post_status=draft')); ?>" class="<?php echo (!empty($request['post_status']) && $request['post_status'] == 'draft') ? 'active-in' : ''; ?>" >Drafts <span class="count">(<?php echo esc_html($connection_counts->draft); ?>)</span></a></li>
<?php } ?>
<?php if (!empty($connection_counts->pending))
{ ?>
							| <li class=""><a href="<?php echo esc_url(admin_url('admin.php?page=unify-connection&post_status=pending')); ?>" class="<?php echo (!empty($request['post_status']) && $request['post_status'] == 'pending') ? 'active-in' : ''; ?>" >Pending <span class="count">(<?php echo esc_html($connection_counts->pending); ?>)</span></a></li>
<?php } ?>
<?php if (!empty($connection_counts->trash))
{ ?>
							| <li class=""><a href="<?php echo esc_url(admin_url('admin.php?page=unify-connection&post_status=trash')); ?>" class="<?php echo (!empty($request['post_status']) && $request['post_status'] == 'trash') ? 'active-in' : ''; ?>" >Trash <span class="count">(<?php echo esc_html($connection_counts->trash); ?>)</span></a></li>
<?php } ?>
					</ul>

					<span class="uni-show-num">Showing <?php echo count($data['list']); ?> items</span>
				</div>
			</div>
		</div>

		<div class="container-fluid unify-table uni-shadow-box p-0 ">
			<div class="row">
				<div class="col-12">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th class="ut-width-20">&nbsp;</th>
									<th class="sortab"><!-- Name -->
										<a href="javascript:void(0);" onclick="sortConnListing();" id="sort-title" ><span>Name</span>
											<span class="sorting-arrow">
												<i id='sorting-arrow-icn' class="fas <?php echo ($request['order'] == 'asc') ? 'fa-caret-up' : 'fa-caret-down'; ?>" ></i>												
											</span></a>
									</th>
									<th>Connected to</th>
									<th>Campaign ID</th>
									<th>Status</th>
									<th>Date</th>
									<th class="ut-width-20">Action</th>
								</tr>
							</thead>
							<tbody>

<?php
if (!empty($data['list']))
{
	foreach ($data['list'] as $k => $conn)
	{

		$active_conn = (!empty($crm_set) && $crm_set == $conn['ID'] && !empty($conn['post_status']) && $conn['post_status'] == 'active');
		$stat_trash = (!empty($_GET['post_status']) && $_GET['post_status'] == 'trash');
		$stat_draft = (!empty($conn['post_status']) && $conn['post_status'] == 'draft');
		
		$crm_name = isset($conn['unify_connection_crm_salt']) ? ConfigEncryption::metaDecryptSingle($conn['unify_connection_crm'],$conn['unify_connection_crm_salt']):$conn['unify_connection_crm'];
		?>

										<tr>
											<td><input type="checkbox" name="crm_chk_box[]" value="<?php echo esc_html($conn['ID']); ?>" class="crm_chk_box" data-is-active='<?php echo ($active_conn) ? "true" : "false"; ?>' <?php echo ($active_conn) ? 'disabled' : ''; ?> ></td>
											<td class="<?php echo ($active_conn) ? 'active-blue' : '' ?>" ><?php echo empty($conn['post_title']) ? '(No title set)' : esc_html($conn['post_title']); ?> #<?php echo esc_html($conn['ID']); ?></td>
											<td class="<?php echo ($active_conn) ? 'active-blue' : '' ?>" ><?php echo empty($conn['unify_connection_crm']) ? '(No connection set)' : (($crm_name=='limelight')?'sticky.io (Formerly LimeLight CRM)':esc_html(ucfirst($crm_name))); ?></td>
											<td class="<?php echo ($active_conn) ? 'active-blue' : '' ?>" ><?php echo empty($conn['unify_connection_campaign_id']) ? '(No campaign set)' : esc_html($conn['unify_connection_campaign_id']); ?></td>
											<td><p class="<?php echo ($active_conn) ? 'text-success' : '' ?>" ><?php echo (($active_conn) ? 'Active' : (($conn['post_status'] == 'publish') ? 'Published' : esc_html(ucfirst($conn['post_status'])))); ?></p></td>
											<td class="<?php echo ($active_conn) ? 'active-blue' : '' ?>" ><?php echo esc_html(date("m/d/Y, H:i ", strtotime($conn['post_date']))) . esc_html($time_zone); ?></td>
											<td class="ut-width-20">
												<div class="dropdown unify-row-action-btn" data-val="unify-row-actions-<?php echo esc_html($k); ?>" >
													<button type="button" data-toggle="dropdown" class="btn btn-link" ><i class="fas fa-ellipsis-v"></i> <span class="caret"></span></button>
													<ul class="dropdown-menu dropdown-menu-right unify-row-actions text-left conn-list-ul" id="unify-row-actions-<?php echo esc_html($k); ?>" style="display: none;">
														<li class="pl-3 text-center <?php echo esc_html($stat_trash) ? 'disabled' : ''; ?>"><a class="remove-ul-anchor" href="<?php echo esc_html($stat_trash) ? 'javascript:void(0);' : esc_url(admin_url('admin.php?page=unify-connection&section=create-connection&post=' . esc_html($conn['ID']))); ?>" >Edit</a></li>
														<?php if(!$active_conn){?>
														<li class="pl-3 text-center open_modal_pop <?php echo ($stat_trash || $stat_draft) ? 'disabled' : ''; ?>" data-trig-ev="<?php echo ($stat_trash || $stat_draft) ? true : false; ?>" data-action='activate' data-post-id="<?php echo esc_html($conn['ID']); ?>" data-is-active='false' >Set as Active</li>
														<li class="pl-3 text-center">
														<?php if ($stat_trash){ ?>
																<a class="open_modal_pop remove-ul-anchor" href="javascript:void(0);" data-action="restore" data-post-id="<?php echo esc_html($conn['ID']); ?>"  >Restore</a>
														<?php }else{ ?>
																<a class="open_modal_pop remove-ul-anchor text-danger" href="javascript:void(0);" data-action='delete' data-post-id="<?php echo esc_html($conn['ID']); ?>" data-is-active='<?php echo ($active_conn) ? "true" : "false"; ?>' data-trig-ev="<?php echo ($active_conn) ? true : false; ?>" >Delete</a>
														<?php } }?>
															<!--<a class="remove-ul-anchor text-danger" href="<?php //echo get_site_url() . '/wp-admin/edit.php?post_type=unify_connections&page=unify-connection-list&action=delete&post=' . $conn['ID']; ?>" data-is-active='<?php //echo ($active_conn) ? "true" : "false"; ?>' >Delete</a>-->
														</li>
													</ul>
												</div>
											</td>
										</tr>

	<?php
	}
}
else
{

	?>
									<tr>
										<td colspan="7" >Data not found!</td>										
									</tr>
							<?php
						}

						?>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		
		<?php if($data['total'] > 1) { echo esc_html(Helper::getPaginationTemplate($prev_dis, $next_dis, $request['paged'], $data['total'])); }  ?>
	</div> 

</form>

<div class="conf-form-out modal customModal customModal-confirm">
	<div class="conf-form-in modal-dialog">
		<div role="document" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title mid-heading modal-custm-title">Delete this connection?</h5>
				</div> 
				<div class="modal-body modal-custm-body"> 
					Are you sure you want to delete this connection? You can still undo this action afterwards.
				</div>
				<div class="modal-footer">
					<div class="">
						<button type="button" data-dismiss="modal" class="btn btn-link gen-col-btn-sm gray mr-2 close_pop">No</button>
						<button type="button" class="btn btn-primary gen-col-btn-sm modal-custom-act-btn">Yes</button>
					</div> 
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="post_id_pop" />
	<input type="hidden" id="is_active_conn_pop" data-to-delete-active='' />
	<input type="hidden" id="action_type_pop" />
</div>


<div class="conf-form-out modal customModal customModal-alert">
	<div class="conf-form-in modal-dialog">
		<div role="document" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--					<h5 class="modal-title mid-heading modal-custm-title">Delete this connection?</h5>-->
				</div> 
				<div class="modal-body modal-custm-body modal-custm-body-alert"> 
					Please select a connection to process.
				</div>
				<div class="modal-footer">
					<div class="">
						<button type="button" class="btn btn-primary gen-col-btn-sm close_pop">OK</button>
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>