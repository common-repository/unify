<?php

use \CodeClouds\Unify\Service\Helper;

?>
<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
<form id="unify_product_post" name="unify_product_post" method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">

	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
    <input type="hidden" name="page" value="<?php echo esc_html(\CodeClouds\Unify\Service\Request::any('page')) ?>" />
    <!--<input type="hidden" name="action" value="codeclouds_unify_tool_mapping" />-->
	
	<input type="hidden" name="orderby" id="orderby" value="<?php echo esc_html($request['orderby']); ?>"/>
	<input type="hidden" name="order" id="order" value="<?php echo esc_html($request['order']); ?>" />
	
	<input type="hidden" name="check_submit" id="check_submit" value="update_product" />
	
    <!-- Now we can render the completed list table -->

	<div class="container-fluid tran-bg-in p-0 mb-0">
		<div class="row clearfix">
			<div class="col-6">
				<p class="prd-dp-text"><b>You can map your <em class="no-title-set">CRM Product ID</em> with WooCommerce’s <em class="no-title-set">Product ID</em> here</b></p></div>
			<div class="col-6">	
				<span class="uni-show-num">Showing <?php echo count($data['list']); ?> items</span>
			</div>
		</div>
	</div>

	
	<div class="container-fluid unify-table uni-shadow-box p-0 ">
		<div class="row">
			<div class="col-12">
				<div class="table-responsive product-table <?php echo (!empty($crm_meta) && !in_array($crm_meta, ['limelight', 'response']))? 'single' : ''; ?>">
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="sm-in-tb">Thumbnail</th>
								<th class="sm-in-tb sortab" data-order-by="ID" data-order="<?php echo (!empty($request['orderby']) && $request['orderby'] == 'ID') ? esc_html($request['order']) : 'asc'; ?>" >
									<a href="javascript:void(0);"  id="sort-by-ID" >
										<span>Product ID</span>
										<span class="sorting-arrow">
											<i id='ID-icn' class="fas" <?php echo (!empty($_GET['orderby']) && $request['orderby'] == 'ID') ? 'data-hide="false"' : 'style="display:none;" data-hide="true" '; ?>  ></i>												
										</span>
									</a>
								</th>
								<th class="sm-in-tb sortab"  data-order-by="price" data-order="<?php echo (!empty($request['orderby']) && $request['orderby'] == 'price') ? esc_html($request['order']) : 'asc'; ?>"  >
									<!-- Product Name -->
									<a href="javascript:void(0);"  id="sort-by-price" >
										<span>Product Price</span>
										<span class="sorting-arrow">
											<i id='price-icn' class="fas" <?php echo (!empty($_GET['orderby']) && $request['orderby'] == 'price') ? 'data-hide="false"' : 'style="display:none;" data-hide="true" '; ?>  ></i>												
										</span>
									</a>								
								</th>
								<th class="mid-in-tb sortab" data-order-by="post_title" data-order="<?php echo (!empty($request['orderby']) && $request['orderby'] == 'post_title') ? esc_html($request['order']) : 'asc'; ?>"  >
									<!-- Product Name -->
									<a href="javascript:void(0);"  id="sort-by-post_title" >
										<span>Product Name</span>
										<span class="sorting-arrow">
											<i id='post_title-icn' class="fas" <?php echo (!empty($_GET['orderby']) && $request['orderby'] == 'post_title') ? 'data-hide="false"' : 'style="display:none;" data-hide="true" '; ?>  ></i>												
										</span>
									</a>
								</th>
								<th>CRM Product ID</th>
<?php


 if (!empty($crm_meta) && $crm_meta == 'limelight')
{ if($shipping_price_settings_option == 2){
?>
									<th>Shipping ID <span class="info-tool">
                                            <i class="fas fa-info-circle"></i>
                                                <div class="info-tooltip">
                                                    <p>
                                                        <strong>WooCommerce Shipping ID:</strong> The mapped woocommerce shipping ID from Shipping Mapping.
                                                    </p>
                                                </div>
                                        </span></th>
<?php
}
if (!empty($crm_model_meta) && $crm_model_meta == 1)
{

	?>									
									<th>Offer ID</th>
									<th>Billing Model ID</th>
							<?php }
								}?>
<?php
if ((!empty($crm_meta) && $crm_meta == 'response') && (empty($crm_model_meta) && $crm_model_meta != 1))
{

	?>
							 		<th>Group ID</th>
							<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							if (!empty($data['list']))
							{
								foreach ($data['list'] as $k => $prod_list)
								{

									?>
									<tr>
										<td class=""><span class="prd-thumb"><img alt="" width="35" height="35" src="<?php echo (empty(\wp_get_attachment_image_src(\get_post_thumbnail_id($prod_list['ID']), 'single-post-thumbnail')[0]) ? esc_url(plugins_url('/../../assets/images/placeholder.png',__FILE__)) : \wp_get_attachment_image_src(\get_post_thumbnail_id($prod_list['ID']), 'single-post-thumbnail')[0]); ?>" style="" ></span></td>
										<td class=""><?php echo esc_html($prod_list['ID']) ?></td>
										<td class="">
											<?php 
												echo esc_html(\get_woocommerce_currency_symbol()) .' '. esc_html($prod_list['price']);
											?>
										</td>
										<td class=""><?php echo esc_html($prod_list['post_title']) ?></td>
										<td><p class="product-field"><input type="text" name="map[<?php echo esc_html($prod_list['ID']) ?>][codeclouds_unify_connection]" onkeyup="javascript:this.value = this.value.replace(/[^0-9]/g, '');" value="<?php echo empty($prod_list['codeclouds_unify_connection']) ? '' : esc_html($prod_list['codeclouds_unify_connection']); ?>" class="form-control" aria-required="true" aria-invalid="false" /></p></td>
										<?php if (!empty($crm_meta) && $crm_meta == 'limelight')
										{
											if($shipping_price_settings_option == 2){
											?>
											<td><p class="product-field"><input type="text" name="map[<?php echo esc_html($prod_list['ID']) ?>][codeclouds_unify_shipping]" onkeyup="javascript:this.value = this.value.replace(/[^0-9]/g, '');" value="<?php echo esc_html(empty($prod_list['codeclouds_unify_shipping'])) ? '' : esc_html($prod_list['codeclouds_unify_shipping']); ?>" class="form-control" aria-required="true" aria-invalid="false" /></p></td>

										<?php }
										if (!empty($crm_model_meta) && $crm_model_meta == 1)
										{

											?>	
											<td><p class="product-field"><input type="text" name="map[<?php echo esc_html($prod_list['ID']) ?>][codeclouds_unify_offer_id]" onkeyup="javascript:this.value = this.value.replace(/[^0-9]/g, '');" value="<?php echo empty($prod_list['codeclouds_unify_offer_id']) ? '' : esc_html($prod_list['codeclouds_unify_offer_id']); ?>" class="form-control" aria-required="true" aria-invalid="false" /></p></td>
											<td><p class="product-field"><input type="text" name="map[<?php echo esc_html($prod_list['ID']) ?>][codeclouds_unify_billing_model_id]" onkeyup="javascript:this.value = this.value.replace(/[^0-9]/g, '');" value="<?php echo empty($prod_list['codeclouds_unify_billing_model_id']) ? '' : esc_html($prod_list['codeclouds_unify_billing_model_id']); ?>" class="form-control" aria-required="true" aria-invalid="false" /></p></td>
									<?php } 
									}
									?>
											
									<?php
									if ((!empty($crm_meta) && $crm_meta == 'response') && (empty($crm_model_meta) && $crm_model_meta != 1))
									{

										?>
										<td><p class="product-field"><input type="text" name="map[<?php echo esc_html($prod_list['ID']) ?>][codeclouds_unify_group_id]" value="<?php echo empty($prod_list['codeclouds_unify_group_id']) ? '' : esc_html($prod_list['codeclouds_unify_group_id']); ?>" class="form-control" aria-required="true" aria-invalid="false" /></p></td>

										<?php
									}
									?>
									</tr>
									<?php
								}
							}
							else
							{?>
								<tr>
									<td colspan="<?php echo (!empty($crm_meta) && $crm_meta == 'limelight') ? '4' : '7'; ?>">Data not found!</td>
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

	<div class="container-fluid tran-bg-in p-0">
		<div class="row">
			<div class="col-12 text-right mgtp-20">
				<button type="submit" id="product_mapping" onclick="document.getElementById('check_submit').value='update_product'" class="btn btn-primary gen-col-btn">
					<span class="">Update Products</span> 
					<span class=""></span>
				</button>
			</div>
		</div>
	</div>

<?php  if($data['total'] > 1) { echo esc_html(Helper::getPaginationTemplate($prev_dis, $next_dis, $request['paged'], $data['total'])); }  ?>

	<input type="hidden" name="action" value="unify_product_post" />
	<input type="hidden" id="post_type" name="post_type" value="unify_connections">
	<input type="hidden" id="section" name="section" value="product-mapping-new">
<?php wp_nonce_field('unify-product'); ?>
</form>