<?php

namespace CodeClouds\Unify\Service\Mapping;

use \CodeClouds\Unify\Service\Request;

/**
 * Description of Product_List.
 */
class Product_List_Table extends \WP_List_Table
{
    /**
     * Retrieve products (ID, title, thumbnail) from the database.
     * @global wpdb $wpdb
     * @return array
     */
    protected function get_all_products()
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

    /**
     * Product_List_Table constructor.
     */
    public function __construct()
    {
        /**
         * Set parent defaults.
         */
        parent::__construct([
            'singular' => 'product', // Singular name of the listed records.
            'plural'   => 'products', // Plural name of the listed records.
            'ajax'     => false, // Does this table support ajax?
        ]);
    }

    /**
     * Set table columns.
     * @return array
     */
    public function get_columns()
    {
        $columns = [
            'thumbnail'  => _x('Thumbnail', 'Column label', 'wp-list-table-unify'),
            'ID'         => _x('ID', 'Column label', 'wp-list-table-unify'),
            'post_title' => _x('Title', 'Column label', 'wp-list-table-unify'),
            'connection' => _x('Connection\'s Product ID', 'Column label', 'wp-list-table-unify'),
			'offer_id' => _x('Offer ID <small style="color: #00a0d2;">(Only for LimeLight)</small>', 'Column label', 'wp-list-table-unify'),
            'billing_model_id' => _x('Billing Model ID <small style="color: #00a0d2;">(Only for LimeLight)</small>', 'Column label', 'wp-list-table-unify'),
        ];
		
		$wc_codeclouds_unify_settings = get_option('woocommerce_codeclouds_unify_settings');		
		if (!empty($wc_codeclouds_unify_settings) && $wc_codeclouds_unify_settings['shipment_price_settings'] == 2)
		{
			$columns['connection_shipping'] = _x('Shipping ID <small style="color: #00a0d2;">(Only for LimeLight)</small>', 'Column label', 'wp-list-table-unify');
		}
        return $columns;
    }

    /**
     * Sort table as per column.
     * @return array
     */
    protected function get_sortable_columns()
    {
        $sortable_columns = [
            'ID'         => ['ID', false],
            'post_title' => ['post_title', false],
        ];
        return $sortable_columns;
    }

    /**
     * Get default column value.
     * @param object $item A singular item (one full row's worth of data).
     * @param string $column_name The name/slug of the column to be processed.
     * @return string Text or HTML to be placed inside the column.
     */
    protected function column_default($item, $column_name)
    {
        switch ($column_name)
        {
            case 'connection':
                return '<input type="text" name="map[' . $item['ID'] . '][connection]" value="' . \get_post_meta($item['ID'], 'codeclouds_unify_connection', true) . '" />';
            case 'connection_shipping':
                return '<input type="text" name="map[' . $item['ID'] . '][shipping]" value="' . \get_post_meta($item['ID'], 'codeclouds_unify_shipping', true) . '" />';
            case 'thumbnail':
                if (empty($item[$column_name]))
                {
                    return '<img src="' . plugins_url('/../../assets/images/placeholder.png',__FILE__) . '" width="30" />';
                }
                return '<img src="' . $item[$column_name] . '" width="30" />';
			case 'offer_id':
                return '<input type="text" name="map[' . $item['ID'] . '][offer_id]" value="' . \get_post_meta($item['ID'], 'codeclouds_unify_offer_id', true) . '" />';
            case 'billing_model_id':
                return '<input type="text" name="map[' . $item['ID'] . '][billing_model_id]" value="' . \get_post_meta($item['ID'], 'codeclouds_unify_billing_model_id', true) . '" />';
            default :
                return $item[$column_name];
        }
    }

    /**
     * Prepares the list of items for displaying.
     * @global wpdb $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     */
    function prepare_items()
    {
        /*
         * First, lets decide how many records per page to show
         */
        $per_page = 20;

        /*
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns  = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();

        /*
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * three other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = [$columns, $hidden, $sortable];

        $data = $this->get_all_products();

        /*
         * This checks for sorting input and sorts the data in our array of dummy
         * data accordingly (using a custom usort_reorder() function). It's for 
         * example purposes only.
         *
         * In a real-world situation involving a database, you would probably want
         * to handle sorting by passing the 'orderby' and 'order' values directly
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary. In other words: remove this when
         * you implement your own query.
         */
        usort($data, [$this, 'usort_reorder']);

        /*
         * REQUIRED for pagination. Let's figure out what page the user is currently
         * looking at. We'll need this later, so you should always include it in
         * your own package classes.
         */
        $current_page = $this->get_pagenum();

        /*
         * REQUIRED for pagination. Let's check how many items are in our data array.
         * In real-world use, this would be the total number of items in your database,
         * without filtering. We'll need this later, so you should always include it
         * in your own package classes.
         */
        $total_items = count($data);

        /*
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to do that.
         */
        $data = array_slice($data, ( ( $current_page - 1 ) * $per_page), $per_page);

        /*
         * REQUIRED. Now we can add our *sorted* data to the items property, where
         * it can be used by the rest of the class.
         */
        $this->items = $data;

        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args([
            'total_items' => $total_items, // WE have to calculate the total number of items.
            'per_page'    => $per_page, // WE have to determine how many items to show on a page.
            'total_pages' => ceil($total_items / $per_page), // WE have to calculate the total number of pages.
        ]);
    }

    /**
     * Callback to allow sorting of example data.
     * @param string $a First value
     * @param string $b Second value
     * @return int
     */
    protected function usort_reorder($a, $b)
    {
        // If no sort, default to post_title.
        $orderby = !empty(Request::any('orderby')) ? wp_unslash(Request::any('orderby')) : 'post_title'; // WPCS: Input var ok.
        // If no order, default to asc.
        $order   = !empty(Request::any('order')) ? wp_unslash(Request::any('order')) : 'asc'; // WPCS: Input var ok.
        // Determine sort order.
        $result  = strcmp($a[$orderby], $b[$orderby]);
        return ( 'asc' === $order ) ? $result : - $result;
    }
}
