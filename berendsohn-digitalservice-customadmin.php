<?php
/**
 * Plugin Name:       Berendsohn Digital Service – Custom Admin
 * Plugin URI:        https://berendsohn-digitalservice.de
 * Description:       Custom Admin Rolle für Berendsohn-Webseiten.
 * Version:           3.0.0
 * Author:            Berendsohn
 * Author URI:        https://berendsohn-digitalservice.de
 * Text Domain:       berendsohn-digitalservice-customadmin
 * Domain Path:       /languages
 * Update URI:        https://github.com/freiraum-kd/bag-digitalservice-customadmin
 */

if ( ! defined('ABSPATH') ) exit;

/**
 * ✅ Eigene Konstanten (keine Kollision mit Core-Plugin)
 */
define( 'BDS_CA_VERSION', '3.0.0' );
define( 'BDS_CA_FILE', __FILE__ );
define( 'BDS_CA_PATH', plugin_dir_path( __FILE__ ) );
define( 'BDS_CA_URL', plugin_dir_url( __FILE__ ) );

/**
 * Updater (optional, aber für Auto-Updates nötig)
 */
$__bds_ca_updater = BDS_CA_PATH . 'includes/updater.php';
if ( file_exists($__bds_ca_updater) ) {
    require_once $__bds_ca_updater;
}

/**
 * Roles
 */
require_once BDS_CA_PATH . 'includes/class-roles.php';

add_action( 'plugins_loaded', function () {
    load_plugin_textdomain(
        'berendsohn-digitalservice-customadmin',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );

    if ( class_exists('\BDS\Roles') && method_exists('\BDS\Roles', 'init') ) {
        \BDS\Roles::init();
    }
});

/**
 * Rollen/Capabilities beim Aktivieren setzen (empfohlen)
 */
register_activation_hook( BDS_CA_FILE, function () {
    if ( class_exists('\BDS\Roles') && method_exists('\BDS\Roles', 'activate') ) {
        \BDS\Roles::activate();
    }
    flush_rewrite_rules();
});

register_deactivation_hook( BDS_CA_FILE, function () {
    flush_rewrite_rules();
});
