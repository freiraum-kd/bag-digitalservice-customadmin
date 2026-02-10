<?php
namespace BDS;

if ( ! defined( 'ABSPATH' ) ) exit;

class Roles {

    const ROLE = 'customadmin';

    public static function init() {
        register_activation_hook( BDS_FILE, [ __CLASS__, 'activate' ] );
        add_action( 'init', [ __CLASS__, 'ensure_role_caps' ], 11 );
        add_filter( 'login_redirect', [ __CLASS__, 'customadmin_login_redirect' ], 10, 3 );
        add_action( 'admin_menu', [ __CLASS__, 'cleanup_menu_for_customadmin' ], 99 );
    }

    public static function activate() {
        self::add_or_clone_role();
        self::ensure_role_caps();
    }

    private static function add_or_clone_role() {
        $admin = get_role( 'administrator' );
        if ( ! $admin ) return;

        $role = get_role( self::ROLE );
        if ( ! $role ) {
            add_role( self::ROLE, 'Custom Admin', $admin->capabilities );
        }
    }

    public static function ensure_role_caps() {
        $role = get_role( self::ROLE );
        if ( ! $role ) return;

        $remove = [
            'list_users','edit_users','add_users','create_users','delete_users','remove_users','promote_users',
            'delete_posts','delete_pages','switch_themes','update_core',
        ];

        foreach ( $remove as $cap ) {
            if ( $role->has_cap( $cap ) ) $role->remove_cap( $cap );
        }

        $role->add_cap( 'read', true );
        $role->add_cap( 'edit_dashboard', true );
        $role->add_cap( 'edit_theme_options', true );
    }

    public static function customadmin_login_redirect( $redirect_to, $request, $user ) {
        if ( is_wp_error( $user ) || ! $user ) return $redirect_to;
        if ( in_array( self::ROLE, (array) $user->roles, true ) ) {
            return admin_url( 'edit.php?post_type=page' );
        }
        return $redirect_to;
    }

    public static function cleanup_menu_for_customadmin() {
        if ( ! self::current_user_is_customadmin() ) return;
        remove_menu_page( 'edit.php' ); // Beiträge
        remove_menu_page( 'plugins.php' );
        remove_menu_page( 'themes.php' );
        remove_menu_page( 'users.php' );
        remove_menu_page( 'tools.php' );
        remove_menu_page( 'options-general.php' );
    }

    private static function current_user_is_customadmin() : bool {
        if ( ! is_user_logged_in() ) return false;
        $u = wp_get_current_user();
        return in_array( self::ROLE, (array) $u->roles, true );
    }
}