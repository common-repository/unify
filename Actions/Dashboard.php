<?php

namespace CodeClouds\Unify\Actions;

use \CodeClouds\Unify\Service\Helper;
use \CodeClouds\Unify\Service\Notice;
use \CodeClouds\Unify\Service\Request;

/**
 * Plugin's Tools.
 * @package CodeClouds\Unify
 */
class Dashboard
{
    /*
    Environment constants
     */
    private static $environment = [];
    const MIN_CURL_VERSION = '7.52.1';
    const MIN_PHP_VERSION = '5.5';
    const MIN_MYSQL_VERSION = '5.7.27';
    const MIN_WOOCOMMERCE_VERSION = '3.0';
    const MIN_WORDPRESS_VERSION = '4.0';

    public static function unify_upgrade_to_pro()
    {
        global $wpdb, $current_user;
        \wp_get_current_user();
        $request_url = Request::get();
        $upgrde_request_sent = \get_option('upgrde_request_sent');
        if (!empty($request_url) && !empty($request_url['section']) && $request_url['section'] === 'request-pro') {
            include_once __DIR__ . '/../Templates/upgrade-to-pro-form.php';
        } else {
            include_once __DIR__ . '/../Templates/upgrade-to-pro.php';

        }

    }

    public static function dashboard_page()
    {
        global $wpdb, $current_user;
        $request_url = Request::get();
        \wp_get_current_user();

        // We add 'wc-' prefix when is missing from order staus
        // $status = 'wc-' . str_replace('wc-', '', $status);

        $todays_order_count = $wpdb->get_var("
			SELECT count(ID)  FROM {$wpdb->prefix}posts WHERE post_status = 'wc-processing' OR post_status = 'wc-completed' AND `post_type` = 'shop_order' AND date(`post_date`) = '" . \date('Y-m-d') . "'
		");

        // Total Connection Count
        $count_posts = wp_count_posts('unify_connections');
        $total_publish_posts = $count_posts->publish + $count_posts->active;

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => '-1',
            'meta_query' => array(
                array(
                    'key' => 'codeclouds_unify_connection',
                    'value' => '',
                    'compare' => '!=',
                ),
            ),
        ];
        $mapped_product = new \WP_Query($args);

        $pro_license = \get_option('codeclouds_unify_pro_license');
        $config_transferred = \get_option('config_transferred_from_button');

        /*
        Environment Checking start
         */
        $report = wc()->api->get_endpoint_data('/wc/v3/system_status');
        self::$environment = $report['environment'];

        /**
         * Load env variable keys from file.
         */
        $environment_variables = Helper::getEnvironmentVariables();

        /**
         *  Current system configuration.
         */
        $mysqlVersion = $wpdb->db_version();

        $current_configuration = [
            (class_exists('WooCommerce')) ? self::$environment['server_info'] : apache_get_version(),
            (class_exists('WooCommerce')) ? self::$environment['php_version'] : phpversion(),
            (class_exists('WooCommerce')) ? self::$environment['php_max_execution_time'] : ini_get('max_execution_time'),
            (class_exists('WooCommerce')) ? self::$environment['wp_version'] : get_bloginfo('version'),
            (class_exists('WooCommerce')) ? self::$environment['version'] : 0,
            (class_exists('WooCommerce')) ? self::$environment['curl_version'] : curl_version()['version'],
            (class_exists('WooCommerce')) ? self::$environment['mysql_version'] : $mysqlVersion,
            (class_exists('WooCommerce')) ? self::$environment['max_upload_size'] : wp_max_upload_size(),
            (class_exists('WooCommerce')) ? self::$environment['wp_memory_limit'] : 'Not Available',
            (class_exists('WooCommerce')) ? self::$environment['wp_cron'] : 'Not Available',
            (class_exists('WooCommerce')) ? self::$environment['log_directory'] : 'Not Available',
        ];

        foreach ($environment_variables as $index => $env_variable) {
            /**
             *  Setting the configuration value to be displayed in frontend
             */
            $environment_variables[$index]['value'] = $current_configuration[$index];

            if ($environment_variables[$index]['id'] == 'curl_version') {
                if (function_exists('curl_version')) {
                    if (version_compare(curl_version()['version'], self::MIN_CURL_VERSION, '<')) {
                        $environment_variables[$index]['error_message'] = 'cUrl version lower than required version! ' . curl_version()['version'];
                    }
                } else {
                    $environment_variables[$index]['error_message'] = 'cURL is not activated on the server!';
                }
            }

            if ($environment_variables[$index]['id'] == 'mysql_version') {
                if (extension_loaded('mysql') || extension_loaded('mysqli')) {
                    global $wpdb;
                    $mysqlVersion = $wpdb->db_version();

                    if (version_compare($mysqlVersion, self::MIN_MYSQL_VERSION, '<')) {
                        $environment_variables[$index]['error_message'] = 'MySQL version is lower than required version! ' . $mysqlVersion;
                    }
                } else {
                    $environment_variables[$index]['error_message'] = 'MySQL is not installed on your hosting server.';
                }
            }

            if ($environment_variables[$index]['id'] == 'php_version') {
                if (version_compare(phpversion(), self::MIN_PHP_VERSION, '<')) {
                    $environment_variables[$index]['error_message'] = 'PHP version is lower than required version! ' . phpversion();
                }
            }

            if ($environment_variables[$index]['id'] == 'wordpress_version') {
                if (version_compare(get_bloginfo('version'), self::MIN_WORDPRESS_VERSION, '<')) {
                    $environment_variables[$index]['error_message'] = 'Wordpress version is lower than required version! ' . get_bloginfo('version');
                }
            }

            if ($environment_variables[$index]['id'] == 'woocommerce_version') {
                if (class_exists('WooCommerce')) {
                    if (version_compare(self::$environment['version'], self::MIN_WOOCOMMERCE_VERSION, '<')) {
                        $environment_variables[$index]['error_message'] = 'Woocommerce version is lower than required version! ' . self::$environment['version'];
                    }
                } else {
                    $environment_variables[$index]['error_message'] = 'Woocommerce is Not installed on your hosting server.';
                }
            }

        }

        /**
         *  Environment checking end
         */

        if (!empty($pro_license)) {
            if (!empty($request_url) && !empty($request_url['section']) && $request_url['section'] === 'request-cancellation') {
                include_once __DIR__ . '/../Templates/cancellation-form.php';
            } else {
                include_once __DIR__ . '/../Templates/pro-dashboard.php';
            }
        } else {
            if (!empty($request_url) && !empty($request_url['section']) && $request_url['section'] === 'free-trial-license-registration') {
                include_once __DIR__ . '/../Templates/free-trial-license-registration.php';
            } else {
                include_once __DIR__ . '/../Templates/dashboard.php';
            }
        }
    }

    public static function request_unify_pro()
    {
        $request = Request::post();
        $nonce = $request['_wpnonce'];
        $messages = Helper::getDataFromFile('Messages');

        if (wp_verify_nonce($nonce, 'request_unify_pro_chk')) {
            //****** Form Validate Starts *********** //
            $err = self::validate_request_pro_form($request, $messages);
            if (!empty($err)) {
                Notice::setFlashMessage('error', $err);
                wp_redirect(Request::post('_wp_http_referer'));
                exit();
            }
            //****** Form Validate ENDS *********** //

            $request_pro = Dashboard::requestPro($request);
            $response = json_decode($request_pro['body'], true);
            if ($response['success']) {
                $msg = $messages['REQUEST_UNIFY_PRO']['MAIL_SENT'];
                Notice::setFlashMessage('success', $msg);
                wp_redirect(Request::post('_wp_http_referer'));
                exit();
            } else {
                $error_msg = $messages['COMMON']['ERROR'];
                Notice::setFlashMessage('error', $error_msg);
                wp_redirect(Request::post('_wp_http_referer'));
            }
        }

        $error_msg = $messages['COMMON']['ERROR'];
        Notice::setFlashMessage('error', $error_msg);

        wp_redirect(Request::post('_wp_http_referer'));
        exit();
    }

    public static function validate_request_pro_form($request, $messages)
    {
        $validate_field = ['first_name', 'last_name', 'company_name', 'email_address', 'phone_number', 'comment'];
        $err = '';
        foreach ($validate_field as $key => $value) {
            if (empty($request[$value])) {
                $err .= '<span style="display:block;" >' . $messages['VALIDATION']['REQUEST_UNIFY_PRO'][strtoupper($value)] . '</span>';
            } else {
                if ($value == 'email_address' && !filter_var($request[$value], FILTER_VALIDATE_EMAIL)) {
                    $err .= '<span style="display:block;" >' . $messages['VALIDATION']['REQUEST_UNIFY_PRO'][strtoupper($value) . '_INVALID'] . '</span>';
                }

                if ($value == 'phone_number' && !preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $request[$value]) && (strlen($request[$value]) > 10 || strlen($request[$value]) < 10)) {
                    $err .= '<span style="display:block;" >' . $messages['VALIDATION']['REQUEST_UNIFY_PRO'][strtoupper($value) . '_INVALID'] . '</span>';
                }
            }
        }
        return $err;
    }

    /**
     * Plugin Lead generation
     */
    public static function unify_plugin_lead_generate()
    {
        $response = "";
        $messages = Helper::getDataFromFile('Messages');
        $lead_form_data = (empty(Request::any('x-data'))) ? '' : Request::any('x-data');
        parse_str($lead_form_data, $form_data);
        $free_trial_registered = \get_option('woocommerce_codeclouds_unify_free_trial_registation');
        $fields['first_name'] = $form_data['first_name'];
        $fields['last_name'] = $form_data['last_name'];
        $fields['email_address'] = $form_data['email_address'];
        $fields['phone_number'] = $form_data['phone_number'];
        $fields['time_of_registartion'] = time();
        $fields['free_license_key'] = Dashboard::generate_license();
        $fields['license_type'] = 'Free';

        if (empty($free_trial_registered)) {
            $objDashboard = new Dashboard();
            $storeAtHubRes = $objDashboard->leadSubmissionToHub($fields);
            $trial_registration_option = \get_option('woocommerce_codeclouds_unify_free_trial_registation');

            if (!empty($storeAtHubRes)) {
                $result = \add_option('woocommerce_codeclouds_unify_free_trial_registation', $fields);
                $msg = '';

                if ($storeAtHubRes == 406) {
                    $status = 1;
                    $msg = $messages['DASHBOARD']['MAIL_EXISTS'];

                } else if ($storeAtHubRes == 200) {
                    $status = 1;
                    $msg = $messages['DASHBOARD']['FREE_TRIAL_REGISTRATION_SUCCESS'];

                } else {

                    $status = 0;
                    $msg = $messages['COMMON']['ERROR'];
                }

                $response = array(
                    'status' => $status,
                    'msg' => $msg,
                    'redirect' => admin_url('admin.php?page=unify-settings&section=license-management'),
                );
            }

        } else {
            $response = array(
                'status' => 0,
                'msg' => $messages['COMMON']['ERROR'],
            );
        }
        echo json_encode($response);
        exit();

    }

/**
 * Generate a License Key.
 */
    public static function generate_license($suffix = null)
    {
        if (isset($suffix)) {
            $num_segments = 3;
            $segment_chars = 6;
        } else {
            $num_segments = 4;
            $segment_chars = 5;
        }
        $tokens = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $license_string = '';
        for ($i = 0; $i < $num_segments; $i++) {
            $segment = '';
            for ($j = 0; $j < $segment_chars; $j++) {
                $segment .= $tokens[rand(0, strlen($tokens) - 1)];
            }
            $license_string .= $segment;
            if ($i < ($num_segments - 1)) {
                $license_string .= '-';
            }
        }
        if (isset($suffix)) {
            if (is_numeric($suffix)) {
                $license_string .= '-' . strtoupper(base_convert($suffix, 10, 36));
            } else {
                $long = sprintf("%u\n", ip2long($suffix), true);
                if ($suffix === long2ip($long)) {
                    $license_string .= '-' . strtoupper(base_convert($long, 10, 36));
                } else {
                    $license_string .= '-' . strtoupper(str_ireplace(' ', '-', $suffix));
                }
            }
        }
        return $license_string;
    }

    public function requestPro($fields)
    {
        $user_ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
        $args1 = array(
			'body'        => [],
			'httpversion' => '1.0',
			'headers'     => [
				'Content-Type' => 'application/json'
			],
			'cookies'     => [],
        );	
        $res_body =  wp_remote_retrieve_body(wp_remote_get("https://ipinfo.io/{$user_ip}/json?token=a590d2779b191f",$args1));
        $location_details = json_decode($res_body);
        $location = (!empty($location_details) && !empty($location_details->city)) ? $location_details->city . ', ' : '';
        $location .= (!empty($location_details) && !empty($location_details->country)) ? $location_details->country : '';

        $helper = new Helper();
        $endpoint = $helper->getHubEndpoint();
        $api_method = 'signup/wp-unify/upgrade';
        $curl_url = $endpoint . $api_method;
        $auth_token = md5($fields["email_address"]);
        
        $header = [
            'Content-Type' => 'application/json',
            'X-Auth-token' => $auth_token,
        ];
        $args = array(
            'timeout' => '5000',
            'httpversion' => '1.1',
            'cookies' => [],
            'data_format' => 'body',
        );

        $response = \Requests::post($curl_url, $header, json_encode(array('name' => $fields['first_name'] . " " . $fields['last_name'], 'company' => $fields['company_name'], 'email' => $fields['email_address'], 'mobile' => $fields['phone_number'], 'comment' => $fields['comment'], 'website_url' => UNIFY_WP_HOME_URL, 'ip_address' => $user_ip, 'location' => $location)));

        if(is_wp_error($response)){
            return $var->body= json_encode([
                'success'=>'false',
            ],true);
        }else{
            return $response;
        }
    }

/**
 * Plugin Lead submission to Hub
 */
    public function leadSubmissionToHub($fields)
    {
        $objHelper = new Helper();
        $endpoint = $objHelper->getHubEndpoint();
        $api_method = 'signup/wp-unify';
        $curl_url = $endpoint . $api_method;
        $auth_token = md5($fields["email_address"]);
        $httpcode = null;
        $header = [
            'Content-Type' => 'application/json',
            'X-Auth-token' => $auth_token,
        ];
        $args = array(
            'timeout' => '5000',
            'httpversion' => '1.1',
            'cookies' => [],
            'data_format' => 'body',
        );

        $response = \Requests::post($curl_url, $header, json_encode(array('first_name' => $fields['first_name'], 'last_name' => $fields['last_name'], 'email' => $fields['email_address'], 'mobile' => $fields['phone_number'])));
        if(!empty($response) && !empty($response->status_code)){
            $httpcode = $response->status_code ;
        }
        return $httpcode;    
    }

    public static function unify_pro_request()
    {
        $request = Request::post('x');
        parse_str($request, $output);
        $messages = Helper::getDataFromFile('Messages');
        $request_pro1 = new Dashboard();
        $request_pro = $request_pro1->requestPro($output);
        $response = json_decode($request_pro->body, true);
        if ($response['success']) {
            $upgrde_request_sent = \get_option('upgrde_request_sent');
            if (empty($upgrde_request_sent)) {
                $result = \add_option('upgrde_request_sent', 1);
            }
            $msg = $messages['REQUEST_UNIFY_PRO']['MAIL_SENT'];
            echo json_encode(['status' => 1, 'msg' => $msg]);
        } else {
            $error_msg = $messages['COMMON']['ERROR'];
            echo json_encode(['status' => 0, 'msg' => $error_msg]);
        }
        exit();
    }
}
