<?php

namespace CodeClouds\Unify\Service;

/**
 * Server request handler.
 * @package CodeClouds\Unify
 */
class Helper
{

    /**
     * To return the TimeZone in a string format.
     * Example
     * EST or America/New_York
     * IST or Asia/Kolkata
     *
     * @param bool $abbreviation If TimeZone 3/4 char abbreviation is needed then set it to TRUE other it will return full form
     * @return string
     */
    public static function wh_get_timezone_string($abbreviation = true)
    {
        $format = $abbreviation ? 'T' : 'e';
        $timezone = get_option('timezone_string');

        //If site timezone string exists
        if (!empty($timezone)) {
            $dateTime = new \DateTime();
            $dateTime->setTimeZone(new \DateTimeZone($timezone));
            return $dateTime->format($format);
        }

        //Getting UTC offset, if it isn't set then return UTC
        if (0 === ($utc_offset = get_option('gmt_offset', 0))) {
            return 'UTC';
        }

        //Adjusting UTC offset from hours to seconds
        $utc_offset *= 3600;
        $timezone = timezone_name_from_abbr('', $utc_offset, 0);
        // attempt to guess the timezone string from the UTC offset
        if (!empty($timezone)) {
            $dateTime = new \DateTime();
            $dateTime->setTimeZone(new \DateTimeZone($timezone));
            return $dateTime->format($format);
        }

        //Guessing timezone string manually
        $is_dst = date('I');
        foreach (timezone_abbreviations_list() as $abbr) {
            foreach ($abbr as $city) {
                if ($city['dst'] == $is_dst && $city['offset'] == $utc_offset) {
                    return $abbreviation ? strtoupper($abbr) : $city['timezone_id'];
                }

            }
        }

        //Default to UTC
        return 'UTC';
    }

    public static function getDataFromFile($file)
    {
        switch ($file) {
            case 'Messages':
                $data = include_once __DIR__ . '/Messages.php';
                break;
            case 'request-unfiy-pro':
                $data = __DIR__ . '/../Templates/Mail/request-unfiy-pro.php';
                break;
            case 'request-unfiy-pro-user':
                $data = __DIR__ . '/../Templates/Mail/request-unfiy-pro-user.php';
                break;
            default:
                $data = [];
                break;
        }

        return $data;
    }

    public static function getPaginationTemplate($prev_dis, $next_dis, $paged, $total)
    {
        echo include_once __DIR__ . '/../Templates/Pagination/pagination-template.php';
    }

    public static function getCrmType()
    {
        $crm = null;
        $crm_meta = 0;

        $setting_option = \get_option('woocommerce_codeclouds_unify_settings');

        if (!empty($setting_option)) {
            $crm_data = \get_post_meta($setting_option['connection'], 'unify_connection_crm');
            $crm_data_salt = \get_post_meta($setting_option['connection'], 'unify_connection_crm_salt');

            if (!empty($crm_data)) {
                $crm = isset($crm_data_salt[0]) ? \CodeClouds\Unify\Model\ConfigEncryption::metaDecryptSingle($crm_data[0], $crm_data_salt[0]) : $crm_data[0];
            }

            if (!empty($crm)) {
                if ($crm == 'limelight') {
                    $crm_meta = \get_post_meta($setting_option['connection'], 'unify_connection_offer_model');
                    $crm_meta = (!empty($crm_meta)) ? $crm_meta[0] : 0;
                }
            }
        }

        return ['crm' => $crm, 'crm_meta' => $crm_meta];
    }

    public static function getTrialNotice()
    {
        $unify_plugin_activation_date = \get_option('unify_plugin_activation_date');
        $remaining_days = '';
        if (!empty($unify_plugin_activation_date)) {
            $registred_date = date("Y-m-d", $unify_plugin_activation_date);
            $date2 = date("Y-m-d");
            $diff = abs(strtotime($date2) - strtotime($registred_date));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $remaining_days = 7 - $days;
        }
        return $remaining_days;
    }

    public static function getProMsg()
    {
        $unify_plugin_pro = \get_option('codeclouds_unify_pro_license');
        $showmsg = 0;
        if (!empty($unify_plugin_pro)) {
            $showmsg = 1;
        }
        return $showmsg;
    }

    public function getHubEndpoint()
    {
        // $endpoint = UNIFY_HUB_LIVE;
        // if ('sandbox' == strtolower(UNIFY_ENV)) {
        //     $endpoint = UNIFY_HUB_SANDBOX;
        // }
        $endpoint = UNIFY_HUB_URL;
        return $endpoint;
    }

    public static function getEnvironmentVariables()
    {
        $data = include_once __DIR__ . '/Environment_variables.php';
        return $data;
    }

}
