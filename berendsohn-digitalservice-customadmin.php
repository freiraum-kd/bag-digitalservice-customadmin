<?php
/**
 * Plugin Name:       Berendsohn Digital Service - Custom Admin
 * Plugin URI:        https://berendsohn-digitalservice.de
 * Description:       Custom Admin Rolle & Capabilities für Berendsohn-Webseiten.
 * Version:           1.0.2
 * Author:            Berendsohn
 * Author URI:        https://berendsohn-digitalservice.de
 * Text Domain:       berendsohn-digitalservice-customadmin
 * Domain Path:       /languages
 * Update URI:        https://github.com/freiraum-kd/bag-digitalservice-customadmin
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ✅ Eigene Prefixe, damit es NICHT mit dem Core-Plugin kollidiert
 */
define( 'BDS_CA_VERSION', '1.0.2' );
define( 'BDS_CA_FILE', __FILE__ );
define( 'BDS_CA_PATH', plugin_dir_path( __FILE__ ) );
define( 'BDS_CA_URL', plugin_dir_url( __FILE__ ) );

// Optional: Updater (falls du ihn wirklich nutzt)
$__bds_updater = BDS_CA_PATH . 'includes/updater.php';
if ( file_exists( $__bds_updater ) ) {
    require_once $__bds_updater;
}

// Roles laden
require_once BDS_CA_PATH . 'includes/class-roles.php';

add_action( 'plugins_loaded', function() {
    load_plugin_textdomain(
        'berendsohn-digitalservice-customadmin',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );

    if ( class_exists('\BDS\Roles') && method_exists('\BDS\Roles', 'init') ) {
        \BDS\Roles::init();
    }
} );

/**
 * ✅ Rollen beim Aktivieren (empfohlen)
 * class-roles.php sollte dafür eine activate() Methode haben.
 * Wenn nicht vorhanden, ist es trotzdem safe (wir checken method_exists).
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
