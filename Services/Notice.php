<?php

namespace CodeClouds\Unify\Service;

/**
 * Description of Notice
 */
class Notice
{
	public static $msg_type = ['error', 'success'];
	
    /**
     * Success message for Admin section.
     * @param String $msg
     */
    public static function success($msg)
    {
        add_action('admin_notices', function() use ($msg) {
          include_once __DIR__ . '/../Templates/Notice/success-msg.php';
        });
    }
    
    /**
     * Error message for Admin section.
     * @param String $msg
     */
    public static function error($msg)
    {
        add_action('admin_notices', function() use ($msg) {
            include_once __DIR__ . '/../Templates/Notice/error-msg.php';
        });
    }
    
    /*
	 * @param string $msg_key check session key.
	 */
    public static function hasFlashMessage($msg_key)
    {
		$return = false;
		if(!empty($_SESSION[$msg_key])){
			$return = true;
		}
		return $return;
    }
    
    /*
	 * @param string $msg_type Ex : success, error etc.
	 * @param string $msg message to set.
	 */
    public static function setFlashMessage($msg_type, $msg)
    {
		if (!session_id())
		{
			session_start();
		}
		$_SESSION['unify_notification'] = [];
		$_SESSION['unify_notification']['msg_type'] = $msg_type;
		$_SESSION['unify_notification']['msg_txt'] = esc_html($msg);
    }
    
    /*
	 * @param string $msg_key get session by key.
	 */
    public static function getFlashMessage($msg_key)
    {
		return $_SESSION[$msg_key];
    }
	
    /*
	 * @param string $msg_key unset session key.
	 */
    public static function destroyFlashMessage($msg_key)
    {
		unset($_SESSION[$msg_key]);
    }
	
    /*
	 * @param string $msg_key session key.
	 * @param string $message session message.
	 */
    public static function setFlashVariable($msg_key, $message)
    {
		if (!session_id())
		{
			session_start();
		}
		
		$_SESSION[$msg_key] = $message;
    }
    
    
}
