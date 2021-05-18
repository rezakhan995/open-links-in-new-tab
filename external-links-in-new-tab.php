<?php
/**
 * Plugin Name:       External Links In New Tab
 * Plugin URI:        https://wordpress.org/plugins/external-links-in-new-tab/
 * Description:       Opens all external links in a new window. Search engine optimization (SEO) friendly.
 * Version:           1.0.0
 * Author:            Reza Khan
 * Author URI:        https://www.reza-khan.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       external-links-in-new-tab
 * Domain Path:       /languages
 */

defined('ABSPATH') || wp_die('No access directly.');

class Elinewtab {

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
        load_plugin_textdomain('external-links-in-new-tab', false, self::plugin_dir() . 'languages/');
    }

    /**
     * Initialize Modules
     *
     * @since 1.0.0
     */
    public function initialize_modules(){
        do_action( 'elint/before_load' );

        require_once self::plugin_dir() . 'core/bootstrap.php';

        do_action( 'elint/after_load' );
    }  

    /**
     * Plugin Version
     * 
     * @since 1.0.0
     *
     * @return string
     */
    public static function version(){
        return '1.0.0';
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
 * Load Elinewtab plugin when all plugins are loaded
 *
 * @return Elinewtab
 */
function elinewtab(){
    return Elinewtab::init();
}

// Let's go...
elinewtab();