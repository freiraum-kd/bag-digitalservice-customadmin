<?php
/**
 * Plugin Name:       Berendsohn Digital Service - Custom Admin
 * Plugin URI:        https://berendsohn-digitalservice.de
 * Description:       Übergeordnete Funktionen/Anpassungen für Berendsohn-Webseiten (Custom Admin Rolle).
 * Version:           1.0.1
 * Author:            Berendsohn
 * Author URI:        https://berendsohn-digitalservice.de
 * Text Domain:       berendsohn-digitalservice
 * Domain Path:       /languages
 * Update URI:        https://github.com/freiraum-kd/bag-digitalservice-customadmin
 */
//Test Alex vom 
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'BDS_VERSION', '1.0.1' );
define( 'BDS_FILE', __FILE__ );
define( 'BDS_PATH', plugin_dir_path( __FILE__ ) );
define( 'BDS_URL', plugin_dir_url( __FILE__ ) );

// Load updater safely (never fatal if file missing)
$__bds_updater = BDS_PATH . 'includes/updater.php';
if ( file_exists($__bds_updater) ) {
    require_once $__bds_updater;
}

require_once BDS_PATH . 'includes/class-roles.php';


add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'berendsohn-digitalservice', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    \BDS\Roles::init();
} );

register_activation_hook( BDS_FILE, function () {
    \BDS\Login_Mask::add_rewrite();
    flush_rewrite_rules();
});
register_deactivation_hook( BDS_FILE, function () {
    flush_rewrite_rules();
});
