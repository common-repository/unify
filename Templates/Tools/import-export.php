<div class="container-fluid unify-table p-0 tran-bg-in ">
   <div class="row clearfix m-0">
      <div class="col-md-6 pl-0 pr-2 ">
         <div class="crd-white-box  border-0 bottom-mg-gap">
            <div class="inner-white-box uni-shadow-box uni-import-part">
               <h3 class="mid-blue-heading">Import CSV </h3>
               <p class="prd-dp-text mt-3">This will map your <em class="no-title-set">CRM Product ID</em> with <em class="no-title-set">WooCommerce’s Product ID</em></p>
               <form id="codeclouds_unify_tool_import" method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                  <div class="inner-api-cont mt-4">
                     <div class="form-group m-0">
                        <div class="input-group">
                           <div class="input-group-append" id="browse_file" >
                              <span class="input-group-text">Browse</span>
                           </div>
                           <input name="unify_import_tool_read" type="text" id="unify_import_tool_read" class="form-control" placeholder="No file selected" readonly>
                           <input name="unify_import_tool" type="file" accept=".csv" id="unify_import_tool" required="required" style="display: none;" >
                        </div>
                        <div class="invalid-feedback"></div>
                     </div>
                  </div>
                  <div class="upl-cnt-btn col-md-6 mgtp-20">
                     <button type="submit" id="import-submit" class="btn btn-primary gen-col-btn-sm">
                     <span class="">Upload</span> 
                     <span class=""></span>
                     </button>
                  </div>
               </form>
               <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
                  <input type="hidden" name="action" value="codeclouds_unify_tool_download">
                  <div class="form-group m-0">
                     <div class="upl-cnt-btn text-center mgtp-20">
                        <button type="submit" class="btn btn-primary gen-col-btn-sm download-btn">
                        <span class="">Download CSV Template</span> 
                        <span class=""></span>
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <div class="col-md-6 pl-2 pr-0">
         <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="codeclouds_unify_tool_download">
            <div class="crd-white-box  border-0 bottom-mg-gap">
               <div class="inner-white-box uni-shadow-box">
                  <h3 class="mid-blue-heading">Export CSV</h3>
                  <p class="prd-dp-text mt-3">A CSV file containing the <em class="no-title-set">Product ID</em> and the <em class="no-title-set">CRM Product ID</em> mapping data.</p>
                  <div class="inner-api-cont mt-4">
                     <div class="form-group m-0">
                        <div class="upl-cnt-btn text-center mgtp-20">
                           <button type="submit" class="btn btn-primary gen-col-btn-sm download-btn">
                           <span class="">Export</span> 
                           <span class=""></span>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
