<?php
/**
 * Plugin Name: Trader.SEES
 * Plugin URI: https://github.com/RyanBayne/trader-sees
 * Description: Premium SEES (Scoring Engine Execution System) extension for Trader.CORE - Advanced scoring directives, strategy manager, and proprietary trading algorithms.
 * Version: 1.0.0
 * Author: Ryan Bayne
 * Author URI: https://www.ryanbayne.uk
 * Requires at least: 5.8
 * Tested up to: 6.7
 * Requires PHP: 7.4
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: trader-sees
 * Domain Path: /languages
 * 
 * @package TraderSEES
 * @category Core
 * @author Ryan Bayne
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
if ( ! defined( 'TRADERSEES_VERSION' ) ) {
    define( 'TRADERSEES_VERSION', '1.0.0' );
}
if ( ! defined( 'TRADERSEES_FILE' ) ) {
    define( 'TRADERSEES_FILE', __FILE__ );
}
if ( ! defined( 'TRADERSEES_BASENAME' ) ) {
    define( 'TRADERSEES_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'TRADERSEES_DIR' ) ) {
    define( 'TRADERSEES_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'TRADERSEES_URL' ) ) {
    define( 'TRADERSEES_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Check if TradePress core is active
 */
function TRADERSEES_check_core() {
    if ( ! function_exists( 'tradercore' ) && ! class_exists( 'TraderCore' ) ) {
        add_action( 'admin_notices', 'TRADERSEES_core_missing_notice' );
        return false;
    }
    return true;
}

/**
 * Display admin notice if TradePress core is missing
 */
function TRADERSEES_core_missing_notice() {
    ?>
    <div class="notice notice-error">
        <p>
            <?php
            echo wp_kses_post(
                sprintf(
                    __( '<strong>Trader.SEES</strong> requires the free Trader.CORE plugin to be installed and activated. <a href="%s">Install Trader.CORE</a>', 'trader-sees' ),
                    admin_url( 'plugin-install.php?s=tradercore&tab=search&type=term' )
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
function TRADERSEES_init() {
    // Check if core is active
    if ( ! TRADERSEES_check_core() ) {
        return;
    }

    // Load the main plugin class
    require_once TRADERSEES_DIR . 'includes/class-trader-sees.php';

    // Initialize
    TraderSEES::instance();
}
add_action( 'plugins_loaded', 'TRADERSEES_init', 20 );

/**
 * Activation hook
 */
function TRADERSEES_activate() {
    // Check if core is active
    if ( ! TRADERSEES_check_core() ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die(
            wp_kses_post( __( 'Trader.SEES requires the free Trader.CORE plugin to be installed and activated.', 'trader-sees' ) ),
            'Plugin Activation Error',
            array( 'back_link' => true )
        );
    }

    // Set activation flag
    set_transient( 'TRADERSEES_activated', true, 30 );
}
register_activation_hook( __FILE__, 'TRADERSEES_activate' );

/**
 * Deactivation hook
 */
function TRADERSEES_deactivate() {
    // Cleanup if needed
    delete_transient( 'TRADERSEES_activated' );
}
register_deactivation_hook( __FILE__, 'TRADERSEES_deactivate' );
