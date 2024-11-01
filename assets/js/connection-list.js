jQuery(document).ready(function ($) {
	"use strict";
	
	// ########## Number of row to show starts #########
	$("#list-show-opt").hide();		
	// Show hide popover
	$("#list-show-btn").click(function () {
		$(this).next('#list-show-opt').toggle();
	});
	
	$('.change-posts-per-page').click(function(){
		var per_page = $(this).attr('val');
		
		if($('#posts_per_page').val() != per_page){
			$('#posts_per_page').val(per_page);
			$('#connection-list-form').submit();
		}
	});
	
	$('.num-page').click(function(){
		var num = $(this).attr('val');
		$('#list-show-btn').html(num);
		$('#num_row').val(num);
	});
	// ########## Number of row to show ends #########
	
	// ########## Filer Option starts #########
	$("#list-filter-opt").hide();		
	// Show hide popover
	$("#list-filter-btn").click(function () {
		$(this).next('#list-filter-opt').toggle();
	});
	
	$('.filter-type').click(function(){
		var txt_val = $(this).attr('val');
//		$('#list-filter-btn').html(txt_val);
		$('#filter_type').val(txt_val);
	});
	
	$('.change-posts-by-date').click(function(){
		var post_date = $(this).attr('val');		
		if($('#m').val() != post_date){
			$('#m').val(post_date);
			$('#list-filter-btn').html($(this).attr('data-txt'));
			$('#connection-list-form').submit();
		}
	});
	// ########## Filer Option ends #########
	
	// ######### Connection List Row Action Event Starts #############
	
	$(".unify-row-actions").hide();		
	// Show hide popover
	$(".unify-row-action-btn").click(function () {
		var row_id = $(this).attr('data-val');
		var options_id = '';
		$('#'+row_id).toggle();
		$('.unify-row-actions').each(function( index, element ) {
			options_id = $(this).attr('id');
			if(options_id != row_id){
				$(this).hide();
			}			
		});
	});
	
	// ######### Connection List Row Action Event Ends #############
	
	// ######## Bulk Action starts ##########
		// ########## Bulk Option starts #########
		$("#bulk-act-opt").hide();
		// Show hide popover
		$("#bulk-act-btn").click(function () {
			$(this).next('#bulk-act-opt').toggle();
		});

		// ########## Bulk Option ends #########
		$('#bulk_chk').change(function(){
			if($(this).is(':checked')){
				$('.crm_chk_box').each(function (index, value) { 
					if(!$(this).prop("disabled")){
						$(this).prop("checked", true);
					}					
				});
			}else{
				$('.crm_chk_box').each(function (index, value) { 
					$(this).prop("checked", false);
				});
			}
		});
		
	$('.crm_chk_box').change(function () {
		var chk = true;
		$('.crm_chk_box').each(function (index, value) {
			if (!$(this).prop("checked")){
				chk = false;
			}			
			$('#bulk_chk').prop("checked", chk);				
		});
	});
		
	// ######## Bulk Action Ends ##########
	
	// ######## Bulk Trash Starts #########
	
	$('.bulk-act').click(function(){
		var isAction = $(this).attr('data-action');
		$('#action_type_pop').val(isAction);		
		var clickedId = $(this).attr('id');
		$('#bulk-act-btn').html($(this).attr('data-val'));
		if (clickedId == 'bulk-trash' || clickedId == 'bulk-restore') {
			if ($(".crm_chk_box:checked").length > 0) {
				if(clickedId == 'bulk-trash'){
					openBulkDeleteConn($);
				} else if(clickedId == 'bulk-restore') {
					openBulkRestoreConn($);
				}
			}else {				
				openBulkDeleteConnAlert($);
			}
			$('#bulk-act-btn').html('Bulk Actions');
		}
	});

	// ######## Bulk Trash ends #########
	
	// ######## OnHover Sorting Title Starts #########
	$('#sort-title').hover(function () {
		
		if ($(this).find('.sorting-arrow > i').hasClass('fa-caret-down')) {
			$('#sorting-arrow-icn').removeClass('fa-caret-down');
			$('#sorting-arrow-icn').addClass('fa-caret-up');
		}else if ($(this).find('.sorting-arrow > i').hasClass('fa-caret-up')) {
			$('#sorting-arrow-icn').removeClass('fa-caret-up');
			$('#sorting-arrow-icn').addClass('fa-caret-down');
		}
	});
	// ######## OnHover Sorting Title Ends #########	
	
	// ######## Active Confirmation pop-up Starts #########
	
	$('.modal-custom-act-btn').click(function(){
		var isAction = $('#action_type_pop').val();
		var post_id = [];
		post_id.push($('#post_id_pop').val());
		var isActive = $('#is_active_conn_pop').val();
		if(isAction == 'delete'){
			if (isActive == 'true') {
				$('.customModal-confirm').removeClass('show');				
				$('#is_active_conn_pop').val('false');
				openDeleteActiveConn($);
				return;
			}
			ajax_to_delete($, post_id, $('#is_active_conn_pop').attr('data-to-delete-active'));
		}else if(isAction == 'activate'){
			ajax_to_activate($, post_id);
		}
		$('.customModal-confirm').removeClass('show');
		if(isAction == 'bulk-delete'){
			bulkDelete($, true);
		}else if(isAction == 'bulk-delete-active'){
			bulkDelete($, false);
		}else if(isAction == 'bulk-restore'){
			bulkResore($);
		}else if(isAction == 'restore'){
			restore_connection($, post_id);
		}
		
	});
	
	$('.open_modal_pop').click(function(){
		if($(this).attr('data-trig-ev')){
			return false;
		}
		
		var isAction = $(this).attr('data-action');
		$('#action_type_pop').val(isAction);		
		$('#post_id_pop').val($(this).attr('data-post-id'));
		$('#is_active_conn_pop').val($(this).attr('data-is-active'));		
		
		if(isAction == 'delete'){			
			openDeleteConn($);			
		}else if(isAction == 'activate'){
			openActivateConn($);
		}else if(isAction == 'restore'){
			openRestoreConn($);
		}
	});
	
	$('.close_pop').click(function(){
		$('.conf-form-out').removeClass('show');
		$('#action_type_pop').val('');		
		$('#post_id_pop').val('');
		$('#is_active_conn_pop').val('');	
	});
	
	// ######## Active Confirmation pop-up Ends #########
	
	
	
	// ######### ON CLICK UNDO SECTION STARTS #########//
	
	$('#click_undo_active').click(function(){
		
		var undo_id = $(this).attr('data-undo_id');
		
		$.ajax({
			data: {
				'action': 'activate_conn',
				'post_id': undo_id,
				'undo' : true
			},
			type: 'POST',
			url: ajaxurl,
			success: function (response) {
				var resData = JSON.parse(response);
				window.location = window.location.href;
			}
		});
		
	});
	
	$('#click_undo_delete').click(function(){
		
		var undo_id = $(this).attr('data-undo_id');
		
		$.ajax({
			data: {
				'action': 'bulk_delete_conn',
				'post_id': undo_id.split(","),
				'undo': true
			},
			type: 'POST',
			url: ajaxurl,
			success: function (response) {
				var resData = JSON.parse(response);
				window.location.reload();
			}
		});
		
	});

	// ######### ON CLICK UNDO SECTION ENDS #########//
	
});

jQuery(document).on("click", function (event) {
	
	if (event.target.id != 'list-show-btn') {
		jQuery("#list-show-opt").hide();
	}
	
	var $trigger = jQuery(".unify-row-action-btn");
	if ($trigger.has(event.target).length < 1) {
		jQuery(".unify-row-actions").hide();
	}
	
	if (event.target.id != 'list-filter-btn') {
		jQuery("#list-filter-opt").hide();
	}
	
	if (event.target.id != 'bulk-act-btn') {
		jQuery("#bulk-act-opt").hide();
	}
	
});

function bulkDelete($, check){
	
		var active_post = false;
		var checkedVal = [];
		$('.crm_chk_box').each(function (index, value) {
			if ($(this).prop("checked")) {
				if($(this).attr('data-is-active') == 'true' && check == true){
					$('#action_type_pop').val('bulk-delete-active');
					$('#is_active_conn_pop').val('false');	
					$('#is_active_conn_pop').attr('data-to-delete-active', 'true');
					openBulkDeleteActiveConn($);
					active_post = true;
				}
				checkedVal.push($(this).val());
			}
		});
		
		if(active_post == true){
			return false;
		}
		
		ajax_to_delete($, checkedVal, $('#is_active_conn_pop').attr('data-to-delete-active'));		
		return true;
}

function ajax_to_delete($, checkedVal, activePost){
	$.ajax({
		data: {
			'action': 'bulk_delete_conn',
			'crm_chk_box': checkedVal,
			'active_post': activePost
		},
		type: 'POST',
		url: ajaxurl,
		success: function (response) {
			var resData = JSON.parse(response);
			window.location.reload();
		}
	});
}

function ajax_to_activate($, post_id){
	$.ajax({
		data: {
			'action': 'activate_conn',
			'post_id': post_id
		},
		type: 'POST',
		url: ajaxurl,
		success: function (response) {
			var resData = JSON.parse(response);
			window.location = window.location.href;
//			window.location.reload();
		}
	});
}

function bulkResore($) {
	var checkedVal = [];
	$('.crm_chk_box').each(function (index, value) {
		if ($(this).prop("checked")) {
			checkedVal.push($(this).val());
		}
	});
	restore_connection($, checkedVal);
	return true;
}

function restore_connection($, checkedVal) {

	$.ajax({
		data: {
			'action': 'bulk_restore_conn',
			'crm_chk_box': checkedVal
		},
		type: 'POST',
		url: ajaxurl,
		success: function (response) {
			var resData = JSON.parse(response);
			window.location.reload();
		}
	});
}

function sortConnListing(){
	var orderVal = (jQuery('#order').val() == 'desc') ? 'asc' : 'desc';
	
	jQuery('#order').val(orderVal);		
	jQuery('#connection-list-form').submit();
}

function openDeleteConn($){	
	$('#is_active_conn_pop').attr('data-to-delete-active', $('#is_active_conn_pop').val());
	$('.modal-custm-title').html('Delete this connection?');
	$('.modal-custm-body').html('Are you sure you want to delete this connection? You can still undo this action afterwards.');
	$('.customModal-confirm').addClass('show');
}

function openDeleteActiveConn($){
	$('.modal-custm-body').html('Are you sure you want to delete this connection?');
	$('.modal-custm-title').html('Active connection alert');
	$('.customModal-confirm').addClass('show');
}

function openActivateConn($) {
	var isActive = $('#is_active_conn_pop').val();
	if (isActive == 'true') {
		return false;
	} else {
		$('.modal-custm-body').html('Are you sure you want to set this as the active connection? Only one connection can be active at a time.');
		$('.modal-custm-title').html('Set as active connection?');
		$('.customModal-confirm').addClass('show');
	}
}

function openBulkDeleteConnAlert($){
	$('.modal-custm-body-alert').html('Please select atleast one connection to proceed.');
	$('.customModal-alert').addClass('show');
}

function openBulkDeleteConn($){
	$('.modal-custm-body').html('Are you sure you want to delete the selected connection? You can still undo this action afterwards.');
	$('.modal-custm-title').html('Delete selected connection?');
	$('.customModal-confirm').addClass('show');
}

function openBulkDeleteActiveConn($){
	$('.modal-custm-body').html('Are you sure you want to delete this connection?');
	$('.modal-custm-title').html('Active Connection alert.');
	$('.customModal-confirm').addClass('show');
}

function openBulkRestoreConn($){
	$('.modal-custm-body').html('Are you sure you want to restore the selected connection?');
	$('.modal-custm-title').html('Restore selected connection?');
	$('.customModal-confirm').addClass('show');
}

function openRestoreConn($){	
	$('.modal-custm-title').html('Restore this connection?');
	$('.modal-custm-body').html('Are you sure you want to restore this connection? You can still undo this action afterwards.');
	$('.customModal-confirm').addClass('show');
}