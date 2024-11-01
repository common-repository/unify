<?php

namespace CodeClouds\Unify\Actions;

use \CodeClouds\Unify\Model\ConfigEncryption;
use \CodeClouds\Unify\Model\Config\Connection as Connection_Config_Model;
use \CodeClouds\Unify\Model\Connection as Connection_Model;
use \CodeClouds\Unify\Service\Helper;
use \CodeClouds\Unify\Service\Notice;
use \CodeClouds\Unify\Service\Request;

/**
 * Plugin's Tools.
 * @package CodeClouds\Unify
 */
class Connection
{
    /*
     * Load the connection page
     */
    public static function connection_page()
    {
        $sections = [
            'create-connection',
            'connection-listing',
        ];

        if (!empty(Request::get('section')) && Request::get('section') == 'create-connection') {
            self::create_connection();
        } else {
            self::connection_list();
        }
    }

    /**
     * Setup tools page
     */
    public static function create_connection()
    {
        $post_ID = Request::get('post');
        $connection_config_model_object = new Connection_Config_Model();
        $all_connection = $connection_config_model_object->getArray();
        $settings = \get_option('woocommerce_codeclouds_unify_settings');
        $crm_set = (!empty($settings) && !empty($settings['connection'])) ? sanitize_text_field($settings['connection']) : '';

        $conn_data = ['ID' => '', 'post_title' => '', 'post_status' => 'publish', 'unify_connection_crm' => '', 'unify_connection_endpoint' => '', 'unify_connection_api_username' => '', 'unify_connection_api_password' => '', 'unify_connection_campaign_id' => '', 'unify_connection_shipping_id' => '', 'unify_sublytics_connection_id' => '', 'unify_connection_offer_model' => '', 'unify_order_note' => '', 'unify_response_crm_type_enable' => ''];

        if (!empty($post_ID)) {
            $connection_object = new Connection_Model();
            $connection_detail = $connection_object->get_post_with_meta([], $post_ID);

            if (!empty($connection_detail['list'])) {
                foreach ($conn_data as $key => $value) {
                    if ($key == 'unify_connection_api_password' && !empty($connection_detail['list'][0]['unify_connection_api_password'])) {
                        ConfigEncryption::passwordDecrypt($connection_detail, $conn_data, $key);
                    } else if (in_array($key, ['unify_connection_crm', 'unify_connection_api_username'])) {
                        ConfigEncryption::metaDecrypt($connection_detail, $conn_data, $key);

                    } else if ($conn_data['unify_connection_crm'] != 'konnektive' && in_array($key, ['unify_connection_endpoint'])) {
                        ConfigEncryption::metaDecrypt($connection_detail, $conn_data, $key);
                    } else {
                        $conn_data[$key] = empty($connection_detail['list'][0][$key]) ? '' : $connection_detail['list'][0][$key];
                    }
                }
            }
        }

        include_once __DIR__ . '/../Templates/connection.php';
    }

    public static function connection_list()
    {
        global $wpdb;

        $request = [];
        $request['paged'] = (empty($_GET['paged'])) ? 1 : sanitize_text_field(wp_unslash($_GET['paged']));
        $request['posts_per_page'] = (empty($_GET['posts_per_page'])) ? 10 : sanitize_text_field(wp_unslash($_GET['posts_per_page']));
        $request['m'] = (empty($_GET['m'])) ? '' : sanitize_text_field(wp_unslash($_GET['m']));
        $request['orderby'] = (empty($_GET['orderby'])) ? 'post_title' : sanitize_text_field(wp_unslash($_GET['orderby']));
        $request['order'] = (empty($_GET['order'])) ? 'desc' : sanitize_text_field(wp_unslash($request['order']));
        (empty($_GET['post_status'])) ? '' : $_GET['post_status'] = sanitize_text_field(wp_unslash($_GET['post_status']));

//        $connection_object = new Connection_Model();
        //        $data = $connection_object->get_post_with_meta($request);
        $data = Connection_Model::get_post_with_meta($request);

        $connection_counts = wp_count_posts('unify_connections');

        $all_count = $connection_counts->publish + $connection_counts->draft + $connection_counts->pending + $connection_counts->active;

        $dates = $wpdb->get_results('SELECT DISTINCT Month(`post_date`) as mm, CONCAT(YEAR(`post_date`), LPAD(Month(`post_date`), 2, 0)) as yymm, YEAR(`post_date`) as yy FROM `' . $wpdb->posts . '` WHERE `post_type` ="unify_connections"', ARRAY_A);
        $time_zone = Helper::wh_get_timezone_string();

        $settings = \get_option('woocommerce_codeclouds_unify_settings');
        $crm_set = (!empty($settings) && !empty($settings['connection'])) ? $settings['connection'] : '';
        $prev_dis = (($request['paged'] == 1)) ? true : false;
        $next_dis = (!empty($request['paged']) && $request['paged'] == $data['total']) ? true : false;

        include_once __DIR__ . '/../Templates/connection-list.php';
    }

    public static function save_connection()
    {
        $nonce = Request::post('_wpnonce');
        $pid = Request::post('ID');
        $p_title = Request::post('post_title');
        $p_status = Request::post('post_status');

        $messages = Helper::getDataFromFile('Messages');
		
        if (wp_verify_nonce($nonce, 'codeclouds-unify-connection')) {
            $error = [];
            $connection_post = [];
            $connection_metas = [];

            Connection_Model::prepare_data(Request::post(), $connection_post, $connection_metas, $error);

            if (!empty($error)) {
                $err = '';
                foreach ($error as $key => $value) {
                    $err .= '<span style="display:block;" >' . $messages['VALIDATION']['CREATE_CONNECTION'][strtoupper($value)] . '</span>';
                }

                Notice::setFlashMessage('error', $err);
            } else {
//                if(!empty($connection_post['post_status']) && $connection_post['post_status'] == 'active'){
                //                    $connection_post['post_status'] = 'publish';
                //                }
                //save the new post
                $pid = (empty($pid)) ? wp_insert_post($connection_post) : wp_update_post($connection_post);

                /**
                 * Cycle through the $events_meta array.
                 * Note, in this example we just have one item, but this is helpful if you have multiple.
                 */
                foreach ($connection_metas as $key => $value) {
                    if (
						(gettype(\get_post_meta($pid, $key, true))=='array' && count(\get_post_meta($pid, $key, true)) > 0) ||
						(gettype(\get_post_meta($pid, $key, true))=='string' && !empty(\get_post_meta($pid, $key, true)))
						)
					{
                        /**
                         * If the custom field already has a value, update it.
                         */
                        \update_post_meta($pid, $key, $value);
                    } else {
                        /**
                         * If the custom field doesn't have a value, add it.
                         */
                        \add_post_meta($pid, $key, $value);
                    }

                    if (!$value) {
                        /**
                         * Delete the meta key if there's no value
                         */
                        \delete_post_meta($pid, $key);
                    }
                }
                if (empty(Request::post('ID'))) {
                    $success_msg = strtr($messages['NEW_CONNECTION'], array('{$title}' => $p_title, '{$pid}' => $pid));
                } else {
                    $success_msg = strtr($messages['EDIT_CONNECTION'], array('{$title}' => $p_title, '{$pid}' => $pid));
                }

                $settings = \get_option('woocommerce_codeclouds_unify_settings');
                if (!empty($settings) && !empty($settings['connection']) && $settings['connection'] != $pid && $p_status == 'active') {
                    $res = Connection_Model::set_default_crm($pid);
                    \wp_update_post(['ID' => $settings['connection'], 'post_status' => 'publish']);
                }

                Notice::setFlashMessage('success', $success_msg);
            }
        } else {
            $error_msg = $messages['COMMON']['ERROR'];
            Notice::setFlashMessage('error', $error_msg);
        }
        wp_redirect(Request::post('_wp_http_referer') . '&post=' . $pid);
        exit();
    }

    public static function bulk_delete_conn()
    {
        $res = [];
        $crm_chk_box = (empty(Request::any('crm_chk_box'))) ? [] : Request::any('crm_chk_box');
        $active_post = (empty(Request::any('active_post'))) ? '' : Request::any('active_post');
        $undo = (empty(Request::any('undo'))) ? false : true;

        $messages = Helper::getDataFromFile('Messages');

        if (!empty($undo)) {
            $undo_ids = (empty(Request::any('post_id'))) ? [] : Request::any('post_id');
            if (!empty($undo_ids)) {
                foreach ($undo_ids as $undo_id) {
                    $res[] = Connection_Model::update_post_status($undo_id, 'publish');
                }
            }

            if (count($undo_ids) > 1) {
                Notice::setFlashMessage('success', strtr($messages['CONNECTION']['UNDO_CONNECTION_BULK_DELETED'], array('{$count}' => count($undo_ids))));
            } else {
                $connection_data = Connection_Model::get_post($undo_ids[0]);
                Notice::setFlashMessage('success', strtr($messages['CONNECTION']['UNDO_CONNECTION_DELETED'], array('{$title}' => $connection_data->post_title, '{$pid}' => $connection_data->ID)));
            }
            echo json_encode(['status' => $res]);
            exit();
        } else {

            $delete_ids = [];
            foreach ($crm_chk_box as $key => $post_id) {
                $res[] = Connection_Model::delete_post($post_id);
                $delete_ids[] = $post_id;
            }

            if (count($res) > 1) {
                $message = strtr($messages['CONNECTION']['BULK_CONNECTION_DELETED'], array('{$count}' => count($res)));
            } else {
                $connection_data = Connection_Model::get_post($crm_chk_box[0]);
                $message = strtr($messages['CONNECTION']['CONNECTION_DELETED'], array('{$title}' => $connection_data->post_title, '{$pid}' => $connection_data->ID));
            }

            Notice::setFlashMessage('error', $message);
            if (empty($undo)) {
                Notice::setFlashVariable('undo_id', implode(',', $delete_ids));
                Notice::setFlashVariable('undo_action', 'delete');
            }
            if ($active_post == 'true' && $res) {
                Connection_Model::set_default_crm('');
            }
            echo json_encode(['status' => $res]);
            exit();
        }

        Notice::setFlashMessage('error', $messages['COMMON']['ERROR']);
        echo json_encode(['status' => false]);
        exit();

    }

    public static function bulk_restore_conn()
    {
        $res = true;
        $crm_chk_box = Request::any('crm_chk_box');

        foreach ($crm_chk_box as $key => $post_id) {
            if (!Connection_Model::update_post_status($post_id, 'publish')) {
                $res = false;
            }
        }

        if ($res) {
            $messages = Helper::getDataFromFile('Messages');
            if (count($crm_chk_box) > 1) {
                $message = strtr($messages['bulk_connection_restore'], array('{$count}' => count($crm_chk_box)));
            } else {
                $connection_data = Connection_Model::get_post($crm_chk_box[0]);
                $message = strtr($messages['connection_restore'], array('{$title}' => $connection_data->post_title, '{$pid}' => $connection_data->ID));
            }

            Notice::setFlashMessage('success', $message);
        }

        echo json_encode(['status' => $res]);
        die;
    }

    public static function activate_conn()
    {
        $res = true;
        $post_id = Request::any('post_id');
        $undo = (empty(Request::any('undo'))) ? false : true;
        if (is_array($post_id)) {
            $post_id = $post_id[0];
        }
        $settings = \get_option('woocommerce_codeclouds_unify_settings');
        if (!empty($settings) && !empty($settings['connection'])) {
            \wp_update_post(['ID' => $settings['connection'], 'post_status' => 'publish']);
        }
        \wp_update_post(['ID' => $post_id, 'post_status' => 'active']);
        $res = Connection_Model::set_default_crm($post_id);
        if (!empty($res)) {
            $messages = Helper::getDataFromFile('Messages');
            $connection_data = Connection_Model::get_post($post_id);

            if (!empty($undo)) {
                $message = strtr($messages['CONNECTION']['UNDO_CONNECTION'], array('{$title}' => $connection_data->post_title, '{$pid}' => $connection_data->ID));
                Notice::setFlashMessage('success', $message);
            } else {
                $message = strtr($messages['CONNECTION']['CONNECTION_ACTIVATED'], array('{$title}' => $connection_data->post_title, '{$pid}' => $connection_data->ID));
                Notice::setFlashMessage('success', $message);
                if (!empty($settings['connection']) && empty($undo)) {
                    Notice::setFlashVariable('undo_id', $settings['connection']);
                    Notice::setFlashVariable('undo_action', 'active');
                }
            }

        }
        echo json_encode(['status' => $res]);
        die;
    }

    public static function custom_post_status_active()
    {
        register_post_status('active', array(
            'label' => _x('Active', 'post'),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
        ));
    }

    public static function validate_crm_connection()
    {
        $username = (empty(Request::any('Username'))) ? '' : Request::any('Username');
        $password = (empty(Request::any('Password'))) ? '' : Request::any('Password');
        $endpoint = (empty(Request::any('Endpoint'))) ? '' : Request::any('Endpoint');
        $connection = (empty(Request::any('Connection'))) ? '' : Request::any('Connection');

        $connection_object = new Connection_Model();

        switch ($connection) {
            case "limelight":
                $response = $connection_object->validateLimelight($username, $password, $endpoint);
                if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                    $response_code = json_decode($response['body'])->response_code;
                    if($response_code == 100) {
                        echo json_encode(array('status' => 1, 'msg' => "Validated"));
                    }
                    else{
                        echo json_encode(array('status' => 0, 'msg' => "Invalid Credential"));
                    }
                }
                else{
                    echo json_encode(array('status' => 0, 'msg' => "Invalid Credential"));
                }

                die();
                break;
            case "response":
                $response = $connection_object->validateResponse($username);
                if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                    $status = json_decode($response['body'])->status;
                    if($status == 0) {
                        echo json_encode(array('status' => 1, 'msg' => "Validated"));
                    }
                    else{
                        echo json_encode(array('status' => 0, 'msg' => "Invalid Credential"));
                    }
                }
                else{
                    echo json_encode(array('status' => 0, 'msg' => "Invalid Credential"));
                }
                die();
                break;

            case "sublytics":
                $response = $connection_object->validateSublytics($username, $password, $endpoint);
                if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                    $status = json_decode($response['body'])->success;
                    if($status == 1) {
                        echo json_encode(array('status' => 1, 'msg' => "Validated"));
                    }
                    else{
                        echo json_encode(array('status' => 0, 'msg' => "Invalid Credential"));
                    }
                }
                else{
                    echo json_encode(array('status' => 0, 'msg' => "Invalid Credential"));
                }
                die();
                break;
        }

    }

}
