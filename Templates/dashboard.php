<div class="unify-table-area dash-in">
    <div class="container-fluid unify-mid-heading p-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="page-block-top-heading clearfix">
                    <h2 class="mid-heading dash-heading">Dashboard</h2>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/Notice/lead_notice.php';?>

    <?php include_once __DIR__ . '/Notice/pro-msg.php';?>

    <div class="container-fluid unify-search p-0 bottom-mg-gap dashboard-admin-row">
        <div class="row clearfix m-0">
            <div class="col-12 unify-top-search-left pr-0 pl-0">
                <ul class="dash-top-box">
                    <li class="inner-white-box big-box">
                        <h2 class="lg-bld-heading m-0">Hi there, <?php echo esc_html(ucfirst($current_user->display_name)); ?></h2>
                        <span class="quick-txt">Here’s a quick look at your current connections <br> and products mapped in Unify <span class="arrow-int">&#8594;</span></span> </li>
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

    <div class="container-fluid unify-table p-0 tran-bg-in ">
        <div class="row clearfix tiny-row">
            <div class="col-lg-5 col-md-5 col-sm-12 tiny-col">
                <div class="dashboard-tab">
    <div class="tab-teaser">
    <div class="tab-menu">
      <ul>
        <li><a href="#" class="active" data-rel="tab-1">Unify Status</a></li>
        <li><a href="#" data-rel="tab-2" class="">Server Status</a></li>
      </ul>
</div>

<div class="tab-main-box">
    <div class="tab-box" id="tab-1" style="display:block;">
        <div class="tab-box-list">
            <ul>
            <?php 
            if(!empty($environment_variables)){
                foreach($environment_variables as $env_variable){
                    if($env_variable['category']=='unify'){
                    if($env_variable['id']!='log_directory'){
            ?>     
                <li>
                    <div class="tab-box-list-in">
                        <div class="tab-box-list-cnt">
                        <?php echo esc_html($env_variable['label']); ?>
                        </div>
                        <div class="tab-box-list-info">
                        <?php 
                                        $env_value = (empty($env_variable['hide_value'])) ? 
                                                ($env_variable['type'] != 'size') ? 
                                                esc_html($env_variable['value']) : 
                                                esc_html( size_format($env_variable['value'])) : '';                                             
                                            ?>                                   
                            <span class="list-info-txt" <?php if(strlen($env_value) > 43){ ?>title="<?php echo esc_html($env_value); ?>" <?php } ?>>
                                <?php echo esc_html($env_value); ?>
                            </span>
                            <?php 
                                if(!isset($env_variable['error_message'])){ ?>                                                    
                            <span class="list-info-check">
                                <i class="fas fa-check"></i>
                            </span>
                            <?php } else{ ?>
                            <span class="list-info-times">
                                <i class="fas fa-times"></i>
                            </span>
                          <?php  } ?>
                            <div class="tooltip-box">
                            <span class="list-info-circle"><i class="fas fa-exclamation-circle"></i></span>
                            <div class="tooltip-text">
                                    <span class="tooltiptext">
                                        <?php echo esc_html($env_variable['tooltip_text']); ?>
                                    </span>
                                </div>
                        </div>
                        </div>
                    </div>
                </li>
                <?php }else{ ?>
                <li>
                    <div class="tab-box-list-in">
                        <div class="tab-box-list-cnt">
                            <?php echo esc_html($env_variable['label']); ?>
                        </div>
                        <div class="tab-box-list-info">
                            
                            <span class="list-info-check">
                                <i class="fas fa-check"></i>
                            </span>

                              <div class="tooltip-box">
                            <span class="list-info-circle"><i class="fas fa-exclamation-circle"></i></span>
                            <div class="tooltip-text">
                                    <span class="tooltiptext">
                                        <?php echo esc_html($env_variable['tooltip_text']); ?>                                        
                                    </span>
                                </div>
                        </div>
                        </div>
                    </div>
                    <p class="list-box-content">
                        <?php echo esc_html($env_variable['value']); ?>
                    </p>
                </li>
                <?php } ?>
                <?php }}} ?>
            </ul>
        </div>
    </div>
    <div class="tab-box" id="tab-2">
         <div class="tab-box-list">
            <ul>
            <?php 
            if(!empty($environment_variables)){
                foreach($environment_variables as $env_variable){
                    if($env_variable['category']=='server'){
            ?>     
                <li>
                    <div class="tab-box-list-in">
                        <div class="tab-box-list-cnt">
                        <?php echo esc_html($env_variable['label']); ?>
                        </div>
                        <div class="tab-box-list-info">
                        <?php 
                                        $env_value = (empty($env_variable['hide_value'])) ? 
                                                ($env_variable['type'] != 'size') ? 
                                                esc_html($env_variable['value']) : 
                                                esc_html( size_format($env_variable['value'])) : '';                                             
                                            ?>                                
                            <span class="list-info-txt" <?php if(strlen($env_value) > 43){ ?>title="<?php echo esc_html($env_value); ?>" <?php } ?>>
                                <?php echo esc_html($env_value); ?>
                            </span>
                            <?php 
                                if(!isset($env_variable['error_message'])){ ?>                                                    
                            <span class="list-info-check">
                                <i class="fas fa-check"></i>
                            </span>
                            <?php } else{ ?>
                            <span class="list-info-times">
                                <i class="fas fa-times"></i>
                            </span>
                          <?php  } ?>
                               <div class="tooltip-box">
                            <span class="list-info-circle"><i class="fas fa-exclamation-circle"></i></span>
                            <div class="tooltip-text">
                                    <span class="tooltiptext">
                                        <?php echo esc_html($env_variable['tooltip_text']); ?>                                        
                                    </span>
                                </div>
                        </div>
                        </div>
                    </div>
                </li>
                <?php }}} ?>
            </ul>
        </div>
    </div>
</div>
  </div>




                </div> 
            </div>
            <div class="col-sm-12 col-lg-7 col-md-7 tiny-col">
                <div class="admin-box">
                    <div class="row clearfix tiny-row">
                            <div class="col-md-6 tiny-col">
                
                <div class="crd-white-box  border-0 bottom-mg-gap uni-shadow-box" onclick="manageConn(this);" >
                    <div class="inner-white-box text-center hov-box ">
                        <img alt="" width="" height="" src="<?php echo esc_url(plugins_url('/../assets/images/icon_connection.svg',__FILE__)); ?>" style="" class="sv-icon">
                            <span class="hov-box-txt">Manage Integrations</span>
                    </div>
                </div>
                
                
                <div class="crd-white-box  border-0 bottom-mg-gap uni-shadow-box" onclick="manageSettings(this);" >
                    <div class="inner-white-box text-center hov-box">
                        <img alt="" width="" height="" src="<?php echo esc_url(plugins_url('/../assets/images/icon_plugin.svg',__FILE__)); ?>" style="" class="sv-icon">
                            <span class="hov-box-txt">Plugin Settings</span>
                    </div>
                </div>
                    
            </div>

            <div class="col-md-6 tiny-col">
                
                <div class="crd-white-box  border-0 bottom-mg-gap uni-shadow-box" onclick="manageProdMap(this);" >
                    <div class="inner-white-box text-center hov-box ">
                        <img alt="" width="" height="" src="<?php echo esc_url(plugins_url('/../assets/images/icon_prodmap.svg',__FILE__)); ?>" style="" class="sv-icon">
                        <span class="hov-box-txt">Manage Product Mapping</span>
                    </div>
                </div>
                    
                
                <div class="crd-white-box  border-0 bottom-mg-gap uni-shadow-box" onclick="manageCustomerPortal(this);">
                    <div class="inner-white-box text-center hov-box ">
                        <div class="uni-custom-badge" >PRO</div>
                        <img alt="" width="" height="" src="<?php echo esc_url(plugins_url('/../assets/images/icon_portal.svg',__FILE__)); ?>" style="" class="sv-icon">
                        <span class="hov-box-txt">Go to Customer Portal</span>
                    </div>
                </div>
                
            </div>
                    </div>
                </div>
            </div>
        
        </div>
    </div>

</div> 

<script type = "text/javascript">
	
	function manageConn(elem) {
        removeClass();
        elem.classList.add('active');
	   window.location = "<?php echo esc_url(admin_url('admin.php?page=unify-connection')); ?>";
	}
	
	function manageSettings(elem) {
        removeClass();
        elem.classList.add('active');
	   window.location = "<?php echo esc_url(admin_url('admin.php?page=unify-settings')); ?>";
	}
	
	function manageProdMap(elem) {
        removeClass();
        elem.classList.add('active');
	   window.location = "<?php echo esc_url_raw(admin_url('admin.php?page=unify-tools&section=product-mapping')); ?>";
	}

    function manageCustomerPortal(elem) {
        removeClass();
        elem.classList.add('active');
       window.location = "<?php echo esc_url(admin_url('admin.php?page=unify-upgrade-to-pro')); ?>";
    }

    function removeClass(){
        var a = document.getElementsByClassName('crd-white-box');
        for (i = 0; i < a.length; i++) {
            a[i].classList.remove('active')
        }
    }

    $('.tab-menu li a').on('click', function(){
            var target = $(this).attr('data-rel');
            $('.tab-menu li a').removeClass('active');
            $(this).addClass('active');
            $("#"+target).fadeIn('slow').siblings(".tab-box").hide();
            return false;
  });
</script>