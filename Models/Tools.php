<?php

namespace CodeClouds\Unify\Model;

/**
 * Connection post type model.
 * @package CodeClouds\Unify
 */
class Tools_model
{

	/**
	 * Get a connection by ID.
	 * @param int|WP_Post|null $ID
	 * @return array
	 */
	public static function get_post($ID)
	{
		return \get_post($ID);
	}

	/**
	 * Get post meta by connection ID.
	 * @param int $ID
	 * @return array
	 */
	public static function get_post_meta($ID)
	{
		return \get_post_meta($ID);
	}

	public static function get_post_with_meta()
	{
		global $wpdb;

		$uploadDir = wp_upload_dir()['baseurl'];

		$sql = "
            SELECT 
                post.ID,
                post.post_title,
                CONCAT( '" . $uploadDir . "','/', thumb.meta_value) as thumbnail,
                post.post_type
            FROM (
                SELECT  p.ID,   
                    p.post_title, 
                    p.post_date,
                    p.post_type,
                    MAX(CASE WHEN pm.meta_key = '_thumbnail_id' then pm.meta_value ELSE NULL END) as thumbnail_id,
                    term.name as category_name,
                    term.slug as category_slug,
                    term.term_id as category_id
                FROM " . $wpdb->prefix . "posts as p 
                LEFT JOIN " . $wpdb->prefix . "postmeta as pm ON ( pm.post_id = p.ID)
                LEFT JOIN " . $wpdb->prefix . "term_relationships as tr ON tr.object_id = p.ID
                LEFT JOIN " . $wpdb->prefix . "terms as term ON tr.term_taxonomy_id = term.term_id
                WHERE 1 AND p.post_status = 'publish' AND p.post_type='product'
                GROUP BY p.ID ORDER BY p.post_date DESC
            ) as post
            LEFT JOIN " . $wpdb->prefix . "postmeta AS thumb 
            ON thumb.meta_key = '_wp_attached_file' 
            AND thumb.post_id = post.thumbnail_id";

		return $wpdb->get_results($sql, ARRAY_A);
	}

	public static function delete_post($post_ID)
	{
		\wp_trash_post($post_ID);
	}

	public static function get_products_with_meta($request = [], $post_ID = '')
	{
		$all_products = [];
		$args = ['post_type' => 'product'];

		if (!empty($request))
		{
			$args['posts_per_page'] = $request['posts_per_page'];
			$args['paged'] = $request['paged'];
			if($request['orderby']){
				$args['orderby'] = $request['orderby'];
			}			
			if($request['order']){
				$args['order'] = $request['order'];
			}			
			if(!empty($request['orderby']) && $request['orderby']=='price'){
				$args['meta_key'] = '_price';
				$args['orderby'] = 'meta_value_num';
			}
		}
		$products = new \WP_Query($args);

		if (!empty($products->posts))
		{
			foreach ($products->posts as $key => $value)
			{
				$all_products[$key] = (array) $value;
				$metas = Connection::get_post_meta($value->ID);
		
				foreach ($metas as $k => $val)
				{
					if (in_array($k, ['codeclouds_unify_connection', 'codeclouds_unify_shipping', 'codeclouds_unify_offer_id', 'codeclouds_unify_billing_model_id', 'codeclouds_unify_group_id']))
					{
						$all_products[$key][$k] = !empty($val[1])?$val[1]:$val[0];
					}
				}
			}
		}

		return ['list' => $all_products, 'total' => $products->max_num_pages];
	}

	

}
