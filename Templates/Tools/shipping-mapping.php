<?php
   use \CodeClouds\Unify\Service\Helper;
   
   ?>
<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
<form id="unify_product_shipping" name="unify_product_shipping" method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
   <!-- For plugins, we also need to ensure that the form posts back to our current page -->
   <input type="hidden" name="check_submit" id="check_submit" value="update_product" />
   <!-- Now we can render the completed list table -->
   <?php
      $zones = array();
      $zone                                              = new \WC_Shipping_Zone(0);
      $zones[$zone->get_id()]                            = $zone->get_data();
      $zones[$zone->get_id()]['formatted_zone_location'] = $zone->get_formatted_location();
      $zones[$zone->get_id()]['shipping_methods']        = $zone->get_shipping_methods();
      $shipping_zones = array_merge( $zones, WC_Shipping_Zones::get_zones() );
      $zoneShippingArray = array();
      $shippingMethodsArray = array();
      $zone_shipping_methods_count = 0;
      $count_shipping_methods = array();
      foreach ( $shipping_zones as $shipping_zone ) {
          $zone_id = $shipping_zone['id'];
      
          $zone_name = $zone_id == '0' ? __('Rest of the word', 'woocommerce') : $shipping_zone['zone_name'];
          $zone_locations = $shipping_zone['zone_locations']; 
          $zone_location_name = $shipping_zone['formatted_zone_location'];
          $zone_shipping_methods = $shipping_zone['shipping_methods'];
          $zone_shipping_methods_count = count($zone_shipping_methods);
          foreach ( $zone_shipping_methods as $shipping_method_obj ) {
            $count_shipping_methods[] = $shipping_method_obj->get_instance_id();
          	$shippingMethodsArray[$zone_id][] = array("ID"=>$shipping_method_obj->id,'name'=>$shipping_method_obj->get_method_title(),'instance_id'=>$shipping_method_obj->get_instance_id(),'shipping_cost'=>(empty($shipping_method_obj->cost))?"0.0":$shipping_method_obj->cost);
          }
          if($zone_id == '0'){continue;} $zoneArray[$zone_id][] = array('name'=>$zone_name);
          
      }
      
      ?>
   <div class="container-fluid tran-bg-in p-0 mb-0">
      <div class="row clearfix">
         <div class="col-6">
            <p class="prd-dp-text">
               <?php if($crm_meta=='sublytics'){ ?>
               <em><b>Map your CRM Shipping Price with WooCommerce’s Shipping Class ID</b></em>
               <?php }else{ ?>
                  <em><b>Map your CRM Shipping ID with WooCommerce’s Shipping Class ID</b></em>
               <?php } ?>
            </p>
         </div>
         <div class="col-6">
            <span class="uni-show-num">Showing <?php echo count($count_shipping_methods); ?> items</span>
         </div>
      </div>
   </div>
   <div class="container-fluid unify-table uni-shadow-box p-0 ">
      <div class="row">
         <div class="col-12">
            <div class="table-responsive product-table">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th class="mid-in-tb sortab" data-order-by="post_title" data-order="<?php echo (!empty($request['orderby']) && $request['orderby'] == 'post_title') ? esc_html($request['order']) : 'asc'; ?>"  >
                           <a href="javascript:void(0);"  id="sort-by-post_title" >
                           <span>Shipping Name</span>
                           </a>
                        </th>
                        <th>WooCommerce Shipping Class ID</th>
                        <th>CRM Shipping ID</th>                      
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        if (!empty($shippingMethodsArray))
                        {
                        	foreach ($shippingMethodsArray as $key => $value)
                        	{
                        		for($i=0;$i<count($value);$i++){
                        		?>
                     <tr>
                        <td class=""><?php echo esc_html($zoneArray[$key][0]['name']."_".$value[$i]['name']) ?></td>
                        <td>
                           <p class="product-field">
                              <?php echo esc_html($value[$i]['instance_id']); ?>
                              <input type="hidden" name="map[<?php echo esc_html($value[$i]['instance_id']) ?>][woo_shipping_method_price]" value="<?php echo esc_html($value[$i]['shipping_cost']);?>">
                           </p>
                        </td>
                        <td>
                        	<?php $mapped_shipping_id = get_post_meta($value[$i]['instance_id'], "crm_shipping_id");?>
                           <p class="product-field">
                              <input type="text" name="map[<?php echo esc_html($value[$i]['instance_id']) ?>][crm_shipping_id]" class="form-control" aria-required="true" aria-invalid="false" value="<?php echo !empty($mapped_shipping_id[0])?esc_html($mapped_shipping_id[0]):'';?>"/>
                           </p>
                        </td>
                     </tr>
                     <?php
                        }
                        }
                        }
                        else
                        { ?>
                        <tr>
                        	<td>Data not found!</td>
                        </tr>
                        <?php }
                        
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
            <button type="submit" onclick="document.getElementById('check_submit').value='update_product'" class="btn btn-primary gen-col-btn">
            <span class="">Update Shipping Mapping</span> 
            <span class=""></span>
            </button>
         </div>
      </div>
   </div>
   <input type="hidden" name="action" value="unify_product_shipping" />
   <input type="hidden" id="post_type" name="post_type" value="unify_connections">
   <input type="hidden" id="section" name="section" value="product-mapping-new">
   <?php wp_nonce_field('unify-shipping'); ?>
</form>