<?php
// File: includes/updater.php
if ( ! defined('ABSPATH') ) exit;

/**
 * ✅ Guard: verhindert doppelte UI/Updater-Registrierung
 */
if ( defined('BDS_CA_UPDATER_LOADED') ) return;
define('BDS_CA_UPDATER_LOADED', true);

add_action('plugins_loaded', function () {

    // PUC bootstrap (liegt im Plugin-Root unter /plugin-update-checker/)
    $puc = dirname(__DIR__) . '/plugin-update-checker/plugin-update-checker.php';
    if ( ! file_exists($puc) ) return;
    require_once $puc;

    if ( ! class_exists('\YahnisElsts\PluginUpdateChecker\v5\PucFactory') ) return;

    $updater = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
        'https://github.com/freiraum-kd/bag-digitalservice-customadmin',
        BDS_CA_FILE,
        plugin_basename(BDS_CA_FILE)
    );

    if ( method_exists($updater, 'setBranch') ) {
        $updater->setBranch('main');
    }

    // Release Assets bevorzugen (wenn du GitHub Releases nutzt)
    if ( method_exists($updater, 'getVcsApi') ) {
        $api = $updater->getVcsApi();
        if ( $api && method_exists($api, 'enableReleaseAssets') ) {
            $api->enableReleaseAssets();
        }
    }

    // Optional: Token gegen 403 (wenn du es setzt, z.B. in wp-config.php)
    // define('BDS_UPDATER_TOKEN', 'xxxx');
    if ( defined('BDS_UPDATER_TOKEN') && BDS_UPDATER_TOKEN && method_exists($updater, 'setAuthentication') ) {
        $updater->setAuthentication(BDS_UPDATER_TOKEN);
    }

    // UI-Link “Nach Updates suchen”
    if ( class_exists('\Puc\v5p6\Plugin\Ui') ) {
        new \Puc\v5p6\Plugin\Ui($updater);
    } elseif ( class_exists('\Puc\v5\Plugin\Ui') ) {
        new \Puc\v5\Plugin\Ui($updater);
    }

}, 5);
