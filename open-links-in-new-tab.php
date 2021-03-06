<?php
/**
 * Plugin Name:       Open Links In New Tab
 * Plugin URI:        https://wordpress.org/plugins/open-links-in-new-tab/
 * Description:       Wordpress plugin that opens links in a new tab. Search engine optimization (SEO) friendly.
 * Version:           1.0.4
 * Author:            Reza Khan
 * Author URI:        https://www.reza-khan.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       open-links-in-new-tab
 * Domain Path:       /languages
 */

defined('ABSPATH') || wp_die('No access directly.');

class Olinewtab {

    public static $instance = null;

    public static function init(){
        if( self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct() {
        add_action('init', [$this, 'i18n']);
        add_action('plugins_loaded', [$this, 'initialize_modules']);
    }

    public function i18n(){
        load_plugin_textdomain('open-links-in-new-tab', false, self::plugin_dir() . 'languages/');
    }

    /**
     * Initialize Modules
     *
     * @since 1.0.0
     */
    public function initialize_modules(){
        do_action( 'olint/before_load' );
        
        require_once self::core_dir() . 'bootstrap.php';

        do_action( 'olint/after_load' );
    }  
    

    static function olint_activate() {
        update_option( "olint_open_external_link_in_new_tab", 'yes' );
    }
    
    static function olint_deactivate() {
        delete_option( 'olint_open_external_link_in_new_tab' );
    }

    /**
     * Plugin Version
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public static function version(){
        return '1.0.4';
    }

    /**
     * Core Url
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public static function core_url(){
        return trailingslashit( self::plugin_url() . 'core' );
    }

    /**
     * Core Directory Path
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public static function core_dir(){
        return trailingslashit( self::plugin_dir() . 'core' );
    }

    /**
     * Plugin Url
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public static function plugin_url(){
        return trailingslashit( plugin_dir_url( self::plugin_file() ) );
    }

    /**
     * Plugin Directory Path
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public static function plugin_dir(){
        return trailingslashit( plugin_dir_path( self::plugin_file() ) );
    }

    /**
     * Plugins Basename
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public static function plugins_basename(){
        return plugin_basename( self::plugin_file() );
    }
    
    /**
     * Plugin File
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public static function plugin_file(){
        return __FILE__;
    }

}


/**
 * Load Olinewtab plugin when all plugins are loaded
 *
 * @return Olinewtab
 */
function olinewtab(){
    return Olinewtab::init();
}

// Let's go...
olinewtab();

/* Do something when the plugin is activated? */
register_activation_hook( __FILE__, ['Olinewtab', 'olint_activate'] );

/* Do something when the plugin is deactivated? */
register_deactivation_hook( __FILE__, ['Olinewtab', 'olint_deactivate'] );

