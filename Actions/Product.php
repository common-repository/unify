<?php

namespace CodeClouds\Unify\Actions;

use \CodeClouds\Unify\Service\Request;
use \CodeClouds\Unify\Service\Mapping\Fields;
use \CodeClouds\Unify\Service\Notice;
use \CodeClouds\Unify\Service\Helper;
use \CodeClouds\Unify\Model\ConfigEncryption;

/**
 * Product actions.
 * @package CodeClouds\Unify
 */
class Product
{
    private $attrcount = 0;
    /**
     * Add connection's product ID field into "Linked Products" tab.
     */
    public static function product_options_grouping()
    {
        $skip = false;

        $setting_option = \get_option('woocommerce_codeclouds_unify_settings');

        $meta_model_data = \get_post_meta($setting_option['connection'], 'unify_connection_offer_model');
        $meta_model_data = (empty($meta_model_data)) ? false : true;

        $meta_crm = \get_post_meta($setting_option['connection'], 'unify_connection_crm');

        $meta_data_salt = \get_post_meta($setting_option['connection'], 'unify_connection_crm_salt');


        $crm = isset($meta_data_salt[0])?ConfigEncryption::metaDecryptSingle($meta_crm[0],$meta_data_salt [0]):$meta_crm[0];


        $meta_crm = (!empty($meta_crm) && isset($meta_crm[0])) ? $crm : 'limelight';

        $group_method = 'product_grouping_'.$meta_crm;

        $groupingInst = new Product();

       

        foreach(Fields::get() as $field){

            /**
             * For skipping fields for each crm
             */
            $skip = $groupingInst->$group_method($meta_crm, $field, $meta_model_data);

            if($skip){
                continue;
            }


            \woocommerce_wp_text_input([
                'id'          => $field['id'],
                'label'       => sanitize_text_field($field['label']),
                'desc_tip'    => true,
                'description' => sanitize_text_field($field['description']),
            ]);
        }

    }


	/**
	 * Product field grouping for Limelight
	 */

	public function product_grouping_limelight($meta_crm, $field, $meta_model_data){

	    //Skiping below fields for LL non-billing
	    if(!$meta_model_data && (in_array('codeclouds_unify_offer_id', $field) || in_array('codeclouds_unify_billing_model_id', $field))){                               
	        return true;
	    }    

	    // Skiping group id for LL billling and non-billing
	    if (in_array('codeclouds_unify_group_id', $field)) {
	           return true;
	    }
	    return false;         
	}

	/**
	 * Product field grouping for Konnektive
	 */

	public function product_grouping_konnektive($meta_crm, $field, $meta_model_data = false){
	                                                                                        
	            if (in_array('codeclouds_unify_offer_id', $field) || in_array('codeclouds_unify_billing_model_id', $field) || in_array('codeclouds_unify_group_id', $field) ){
	                return true;
	            
	            }
	            return false;  
	        }

	/**
	 * product field grouping for Response
	 */

	public static function product_grouping_response($meta_crm, $field, $meta_model_data = false){

	            if (in_array('codeclouds_unify_offer_id', $field) || in_array('codeclouds_unify_billing_model_id', $field) || in_array('codeclouds_unify_shipping', $field)) {
	                  
	               return true;
	            
	            }
	            return false;
	        }

	/**
	 * product field grouping for Sublytics
	 */

	public static function product_grouping_sublytics($meta_crm, $field, $meta_model_data = false){

        if (in_array('codeclouds_unify_offer_id', $field) || in_array('codeclouds_unify_billing_model_id', $field) || in_array('codeclouds_unify_shipping', $field)) {
              
           return true;
        
        }
        return false;
    }

    /**
     * Save connection's product ID.
     * @param int $post_id
     */
    public static function save_connection_id($post_id)
    {
        foreach(Fields::get() as $field)
        {
            \update_post_meta(
                    $post_id,
                    $field['id'],
                    esc_attr(Request::getPost($field['id']))
            );
        }
    }

    /**
     * Get connection's product ID.
     * @param int $post_id
     * @return String
     */
    public static function get_connetion($post_id)
    {
        $connection = [];
        foreach(Fields::get() as $field)
        {
            $connection[$field['id']] = \get_post_meta($post_id, $field['id'], false)[0];
        }
        return $connection;
    }
	
    /**
     * Add product ID column.
     * @param array $columns
     * @return array
     */
    public static function woo_product_extra_columns($columns)
    {
        $newcolumns = [
            "cb"         => "<input type  = \"checkbox\" />",
            "product_ID" => esc_html__('ID', 'woocommerce'),
        ];
        
        return array_merge($newcolumns, $columns);
    }

    /**
     * Show product ID(s) in the column.
     * @global Object $post
     * @param array $column
     */
    public static function woo_product_extra_columns_content($column)
    {
        global $post;

        $product_id = $post->ID;
        switch ($column)
        {
            case "product_ID":
                echo esc_html($product_id);
                break;
        }
    }
    
    /**
     * Import CSV and add with connection ID as per product ID.
     * Ajax action.
     */
    public static function import_connections()
    { 

		$messages = Helper::getDataFromFile('Messages');
		
		$crm_type = Helper::getCrmType();
		$counter = 0;

        if(isset($_FILES['unify_import_tool']['tmp_name']) && !empty($_FILES['unify_import_tool']['tmp_name']) && strtolower(pathinfo(sanitize_text_field(wp_unslash($_FILES['unify_import_tool']['name'])), PATHINFO_EXTENSION)) == 'csv')
        {
            $file = fopen(sanitize_text_field(wp_unslash($_FILES['unify_import_tool']['tmp_name'])), 'r');
            fgetcsv($file);
            while (($line = fgetcsv($file)) !== FALSE)
            {   
				$counter = 0;
                if(!empty($line[2]))
                {
                    foreach(Fields::get() as $key=>$field)
                    {
                        if(!array_key_exists('crm', $field)){
                            update_post_meta($line[0], $field['id'], esc_attr($line[2 + $counter]));
							$counter++;
						}elseif(isset($field['crm']) && $field['crm'] == $crm_type['crm']){
							update_post_meta($line[0], $field['id'], esc_attr($line[2 + $counter]));
							$counter++;
						}
                    }
                }
				continue;
            }
            fclose($file);
			
			$msg = $messages['FILES']['VALID'];
			Notice::setFlashMessage('success', $msg);
			exit();
        }
		
		$msg = $messages['FILES']['INVALID'];
		Notice::setFlashMessage('error', $msg);
    }
    
    /**
     * Download CSV of products (ID & title)
     */
	public static function download_csv()
    {
        $crm = '';
        $setting_option = \get_option('woocommerce_codeclouds_unify_settings');

        if (!empty($setting_option) && !empty($setting_option['connection']))
        {
            $meta_data = \get_post_meta($setting_option['connection'], 'unify_connection_crm');

            $meta_data_salt = \get_post_meta($setting_option['connection'], 'unify_connection_crm_salt');

            

            if (!empty($meta_data))
            {
                $crm = isset($meta_data_salt[0])?ConfigEncryption::metaDecryptSingle($meta_data[0],$meta_data_salt [0]):$meta_data[0];
            }
        }

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
        );
        $loop = new \WP_Query($args);
		if (!empty($loop->posts))
		{
			foreach ($loop->posts as $key => $value)
			{
				$all_products[$key] = (array) $value;
				$metas = \CodeClouds\Unify\Model\Connection::get_post_meta($value->ID);
		
				foreach ($metas as $k => $val)
				{
					if (in_array($k, ['codeclouds_unify_connection', 'codeclouds_unify_shipping', 'codeclouds_unify_offer_id', 'codeclouds_unify_billing_model_id', 'codeclouds_unify_group_id']))
					{
						$all_products[$key][$k] = !empty($val[1])?$val[1]:$val[0];
					}
				}
			}
		}
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="unify.csv"');

        $fp = fopen('php://output', 'wb');
        
        if (!empty($crm) && $crm == 'limelight')
        {
            fputcsv($fp, ['Product ID', 'Title', 'Connection Product ID', 'Shipping ID (Only for LimeLight)', 'Offer ID (Only for LimeLight)', 'Billing Model ID (Only for LimeLight)']);
        }
        else if (!empty($crm) && $crm == 'response')
        {
            fputcsv($fp, ['Product ID', 'Title', 'Connection Product ID', 'Group ID (Only for Response)']);
        }
        else if (!empty($crm) && $crm == 'sublytics')
        {
            fputcsv($fp, ['Product ID', 'Title', 'Connection Product ID']);
        }
        else
        {
            fputcsv($fp, ['Product ID', 'Title', 'Connection Product ID']);
        }  

        foreach($all_products as $product){
            if (!empty($crm) && $crm == 'limelight')
            {
                fputcsv(
                    $fp, [
                    $product['ID'],
                    $product['post_title'],
                    $product['codeclouds_unify_connection'],
                    $product['codeclouds_unify_shipping'],
                    $product['codeclouds_unify_offer_id'],
                    $product['codeclouds_unify_billing_model_id']
                    ]
                );                
            }
            else if (!empty($crm) && $crm == 'response')
            {
                fputcsv(
                    $fp, [
                    $product['ID'],
                    $product['post_title'],
                    $product['codeclouds_unify_connection'],
                    $product['codeclouds_unify_group_id'],
                    ]
                );                
            }
            else{
                fputcsv(
                    $fp, [
                    $product['ID'],
                    $product['post_title'],
                    $product['codeclouds_unify_connection'],
                    ]
                );                
            }
        }
        wp_reset_query();

        fclose($fp);
    }


	public static function product_mapping()
    {
        foreach (Request::post('map') as $id => $item)
		{
			foreach ($item as $key => $value)
			{
				if (!empty($value))
				{
					update_post_meta($id, 'codeclouds_unify_' . $key, $value);
				}
				else
				{
					$meta = get_post_meta($id, 'codeclouds_unify_' . $key, true);

					if (!empty($meta))
					{
						delete_post_meta($id, 'codeclouds_unify_' . $key, $value);
					}
				}
			}
		}

		wp_redirect(Request::post('_wp_http_referer'));
		die();
    }
    
    
    public static function add_custom_field_to_variations($loop, $variation_data, $variation) {

        $setting_option = \get_option('woocommerce_codeclouds_unify_settings');
        $meta_crm = \get_post_meta($setting_option['connection'], 'unify_connection_crm');
        $meta_data_salt = \get_post_meta($setting_option['connection'], 'unify_connection_crm_salt');

        $crm = isset($meta_data_salt[0])?ConfigEncryption::metaDecryptSingle($meta_crm[0],$meta_data_salt [0]):$meta_crm[0];

        $meta_crm_connection_name = (!empty($meta_crm) && isset($meta_crm[0])) ? $crm : '';


        if($meta_crm_connection_name == 'konnektive'){
            // Text Field
            woocommerce_wp_text_input(
                    array(
                        'wrapper_class' => 'form-row form-row-full',
                        'id' => 'unify_crm_variation_prod_id[' . $variation->ID . ']',
                        'label' => __('CRM Variation Product ID', 'woocommerce'),
                        'placeholder' => 'Please enter CRM Variation Product ID',
                        'value' => get_post_meta($variation->ID, 'unify_crm_variation_prod_id', true)
                    )
            );
        }
        if($meta_crm_connection_name == 'sublytics'){

            $attrs = count(preg_grep('/^attribute_*/', array_keys( $variation_data )));
            for($i=1;$i<=$attrs;$i++){
            // Text Field
            woocommerce_wp_text_input(
                array(
                    'wrapper_class' => 'form-row form-row-full',
                    'id' => 'unify_crm_item_option_id[' . $variation->ID . ']['.$i.']',
                    'label' => __('CRM Item Option ID '.$i.'', 'woocommerce'),
                    'placeholder' => 'Please enter CRM Item Option ID',
                    'value' => get_post_meta($variation->ID, 'unify_crm_item_option_id_'.$i, true),
                )
        );
        // Text Field
        woocommerce_wp_text_input(
            array(
                'wrapper_class' => 'form-row form-row-full',
                'id' => 'unify_crm_item_option_value_id[' . $variation->ID . ']['.$i.']',
                'label' => __('CRM Item Option Value ID '.$i.'', 'woocommerce'),
                'placeholder' => 'Please enter CRM Item Option Value ID',
                'value' => get_post_meta($variation->ID, 'unify_crm_item_option_value_id_'.$i, true)
            )
            );            

            }

        woocommerce_wp_hidden_input(
            array(
                'id'=>'attribute_count['.$variation->ID.']',
                'value'=>$attrs,
            )
        );
        
        }
    }
    
    public static function save_custom_field_variations($variation_id, $i) {       
        $unify_crm_variation_prod_id = sanitize_text_field(wp_unslash($_POST['unify_crm_variation_prod_id'][$variation_id]));
 
        $unify_crm_attribute_count = sanitize_text_field(wp_unslash($_POST['attribute_count'][$variation_id]));

        if (isset($unify_crm_attribute_count))
            update_post_meta($variation_id, 'unify_crm_attribute_count', esc_attr($unify_crm_attribute_count));


        for($i=1;$i<=$unify_crm_attribute_count;$i++){
            $unify_crm_item_option_value_id = sanitize_text_field(wp_unslash($_POST['unify_crm_item_option_value_id'][$variation_id][$i]));
            $unify_crm_item_option_id = sanitize_text_field(wp_unslash($_POST['unify_crm_item_option_id'][$variation_id][$i]));
    
            if (isset($unify_crm_item_option_value_id))
                update_post_meta($variation_id, 'unify_crm_item_option_value_id_'.$i, esc_attr($unify_crm_item_option_value_id));
    
            if (isset($unify_crm_item_option_id))
                update_post_meta($variation_id, 'unify_crm_item_option_id_'.$i, esc_attr($unify_crm_item_option_id));    
        }            

        if (isset($unify_crm_variation_prod_id))
            update_post_meta($variation_id, 'unify_crm_variation_prod_id', esc_attr($unify_crm_variation_prod_id));

    }

}
