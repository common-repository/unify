<?php

namespace CodeClouds\Unify\Actions;

/**
 * Menu handler.
 * @package CodeClouds\Unify
 */
class Menu
{
    /**
     * Add settings to the menu.
     */
    public static function add_settings_to_menu()
    {
		add_menu_page(
			'Dashboard', 
			'Dashboard',
			'manage_options',
			'unify-dashboard', 
			['CodeClouds\Unify\Actions\Dashboard', 'dashboard_page'],
			plugins_url('/../assets/images/unify-white-icon.svg',__FILE__),
			2 
		);
		

        add_submenu_page(
            'unify-dashboard',
            __('Integrations', 'unify-connection'),
            __('Integrations', 'unify-connection'),
            'manage_options',
            'unify-connection',
            ['CodeClouds\Unify\Actions\Connection', 'connection_page']
        );
		
        add_submenu_page(
            'unify-dashboard',
            __('Tools', 'unify-tools-new'),
            __('Tools', 'unify-tools-new'),
            'manage_options',
            'unify-tools',
            ['CodeClouds\Unify\Actions\Tools', 'tools_page']
        );		
        
//        add_submenu_page(
//            'unify-dashboard',
//            __('About', 'unify-about'),
//            __('About', 'unify-about'),
//            'manage_options',
//            'unify-about',
//            ['CodeClouds\Unify\Actions\About', 'about_page']
//        );

        add_submenu_page(
            'unify-dashboard',
            __('Settings', 'unify-settings'),
            __('Settings', 'unify-settings'),
            'manage_options',
            'unify-settings',
            ['CodeClouds\Unify\Actions\Settings', 'setting']
        );

        add_submenu_page(
            'unify-dashboard',
            __('Upgrade to Pro', 'unify-upgrade-to-pro'),
            __('Upgrade to Pro', 'unify-upgrade-to-pro'),
            'manage_options',
            'unify-upgrade-to-pro',
            ['CodeClouds\Unify\Actions\Dashboard', 'unify_upgrade_to_pro']
        );
    }
	
	public static function alter_menu_label()
	{
		global $menu;
		foreach ($menu as $key => $m)
		{
			if (!empty($m[2]) && $m[2] == 'unify-dashboard')
			{
				$menu[$key][0] = 'Unify'; //changing the Menu Label
				break;
			}
		}
	}


    public static function unify_admin_menu_new_item(){
        ?>

            <script type="text/javascript">
                jQuery(document).ready( function($) {   
                    $('#unify-hub-submenu').parent().attr('target','_blank');  
                });
            </script>
        <?php
    }

    public static function unify_pro_admin_menu(){
        $pro_license = \get_option('codeclouds_unify_pro_license');

        if(!empty($pro_license)) {
        $page_array = ['unify-connection','unify-tools','unify-settings','unify-upgrade-to-pro'];
        $section_array = ['license-management'];

            if(isset($_GET['page']) && in_array($_GET['page'], $page_array)){
                    header("Location: ".admin_url('admin.php?page=unify-dashboard'));
                    die();
            }
        }
    }

}
