<?php
/*
Plugin Name: Новый плагин
Plugin URI:
Description:
Version: 0.1
Author: NikolayS93
Author URI: https://vk.com/nikolays_93
Author EMAIL: nikolayS93@ya.ru
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) )
  exit; // disable direct access

define('PLUGINNAME_DIR', rtrim( plugin_dir_path( __FILE__ ), '/') );
define('PLUGINNAME_URL', rtrim(plugins_url(basename(__DIR__)), '/') );

register_activation_hook( __FILE__, array( 'PLUGINNAME', 'activate' ) );
// register_deactivation_hook( __FILE__, array( 'PLUGINNAME', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( 'PLUGINNAME', 'uninstall' ) );

add_action( 'plugins_loaded', array('PLUGINNAME', 'get_instance'), 10 );
class PLUGINNAME {
    const VERSION = '1.0';
    const SETTINGS = __CLASS__;
    const SLUG = 'form';

    private static $settings = array();
    private static $_instance = null;
    private function __construct() {}
    private function __clone() {}

    static function activate() { add_option( self::SETTINGS, array() ); }
    static function uninstall() { delete_option(self::SETTINGS); }

    private static function include_required_classes()
    {
        $classes = array(
            'Example_List_Table' => 'wp-list-table.php',
            'WP_Admin_Page'      => 'wp-admin-page.php',
            'WP_Admin_Forms'     => 'wp-admin-forms.php',
            );

        foreach ($classes as $classname => $dir) {
            if( ! class_exists($classname) ) {
                require_once PLUGINNAME_DIR . '/includes/classes/' . $dir;
            }
        }

        // includes
        require_once PLUGINNAME_DIR . '/includes/register-post_type.php';
        require_once PLUGINNAME_DIR . '/includes/admin-page.php';
    }

    public static function get_instance()
    {
        if( ! self::$_instance ) {
            load_plugin_textdomain( '_plugin', false, PLUGINNAME_DIR . '/languages/' );
            self::$settings = get_option( self::SETTINGS, array() );
            self::include_required_classes();

            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function get( $prop_name )
    {
        return isset( self::$settings[ $prop_name ] ) ? self::$settings[ $prop_name ] : false;
    }
}

add_action( 'admin_init', 'forms_mce_actions_and_filters', 20 );
function forms_mce_actions_and_filters() {
    /** MCE Editor only */
    if ( !user_can_richedit() && !current_user_can('edit_posts') && !current_user_can('edit_pages') )
        return;

    // add_action( 'admin_head', '_icon' );
    add_filter( "mce_external_plugins", 'mce_button_script' );
    add_filter( "mce_buttons", 'mce_register_button' );
}

/** Register Button MCE */
function mce_button_script($plugin_array){
    $plugin_array['forms'] = PLUGINNAME_URL . '/scripts/mce_button.js';

    return $plugin_array;
}

function mce_register_button($buttons){
    $buttons[] = 'forms';

    return $buttons;
}

// function init_mce_plugin()
// {
//     /** MCE Editor */
//     if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
//         return;
//     }

//     add_action('admin_head', array( __CLASS__, 'add_mce_script' ));
//     add_filter("mce_external_plugins", array(__CLASS__, 'mce_plugin'));
//     add_filter("mce_buttons", array(__CLASS__, 'mce_button'));
// }

// function add_mce_script()
// {
//     if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' ) {
//         return;
//     }
//     $req = array( 'shortcode', 'wp-util', 'jquery' );
//     wp_enqueue_script( 'query-sc', plugins_url( 'js/query_shortcode.js', __FILE__ ), $req, false, true );
//     wp_localize_script( 'query-sc',
//         'custom_query_settings',
//         array(
//             'shortcode' => self::SHORTCODE,
//             'types'     => self::get_post_type_list(),
//             'statuses'  => self::get_status_list(),
//             'orderby'   => self::get_order_by_list(),
//         ) );
// }