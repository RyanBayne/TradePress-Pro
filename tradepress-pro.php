<?php
/**
 * Plugin Name: TradePress Pro
 * Plugin URI: https://github.com/RyanBayne/tradepress-pro
 * Description: Premium extension for TradePress - Advanced directives, strategy templates, automation, and premium data providers.
 * Version: 1.0.0
 * Author: Ryan Bayne
 * Author URI: https://www.ryanbayne.uk
 * Requires at least: 5.8
 * Tested up to: 6.7
 * Requires PHP: 7.4
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: tradepress-pro
 * Domain Path: /languages
 * 
 * @package TradePressProâ€
 * @category Core
 * @author Ryan Bayne
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
if ( ! defined( 'TRADEPRESS_PRO_VERSION' ) ) {
    define( 'TRADEPRESS_PRO_VERSION', '1.0.0' );
}
if ( ! defined( 'TRADEPRESS_PRO_FILE' ) ) {
    define( 'TRADEPRESS_PRO_FILE', __FILE__ );
}
if ( ! defined( 'TRADEPRESS_PRO_BASENAME' ) ) {
    define( 'TRADEPRESS_PRO_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'TRADEPRESS_PRO_DIR' ) ) {
    define( 'TRADEPRESS_PRO_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'TRADEPRESS_PRO_URL' ) ) {
    define( 'TRADEPRESS_PRO_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Check if TradePress core is active
 */
function tradepress_pro_check_core() {
    if ( ! function_exists( 'tradepress' ) && ! class_exists( 'TradePress' ) ) {
        add_action( 'admin_notices', 'tradepress_pro_core_missing_notice' );
        return false;
    }
    return true;
}

/**
 * Display admin notice if TradePress core is missing
 */
function tradepress_pro_core_missing_notice() {
    ?>
    <div class="notice notice-error">
        <p>
            <?php
            echo wp_kses_post(
                sprintf(
                    __( '<strong>TradePress Pro</strong> requires the free TradePress plugin to be installed and activated. <a href="%s">Install TradePress</a>', 'tradepress-pro' ),
                    admin_url( 'plugin-install.php?s=tradepress&tab=search&type=term' )
                )
            );
            ?>
        </p>
    </div>
    <?php
}

/**
 * Initialize the plugin
 */
function tradepress_pro_init() {
    // Check if core is active
    if ( ! tradepress_pro_check_core() ) {
        return;
    }

    // Load the main plugin class
    require_once TRADEPRESS_PRO_DIR . 'includes/class-tradepress-pro.php';

    // Initialize
    TradePress_Pro::instance();
}
add_action( 'plugins_loaded', 'tradepress_pro_init', 20 );

/**
 * Activation hook
 */
function tradepress_pro_activate() {
    // Check if core is active
    if ( ! tradepress_pro_check_core() ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die(
            wp_kses_post( __( 'TradePress Pro requires the free TradePress plugin to be installed and activated.', 'tradepress-pro' ) ),
            'Plugin Activation Error',
            array( 'back_link' => true )
        );
    }

    // Set activation flag
    set_transient( 'tradepress_pro_activated', true, 30 );
}
register_activation_hook( __FILE__, 'tradepress_pro_activate' );

/**
 * Deactivation hook
 */
function tradepress_pro_deactivate() {
    // Cleanup if needed
    delete_transient( 'tradepress_pro_activated' );
}
register_deactivation_hook( __FILE__, 'tradepress_pro_deactivate' );
