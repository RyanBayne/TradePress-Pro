<?php
/**
 * TradePress Pro Admin
 *
 * Handles admin pages, settings, and UI
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * TradePress_Pro_Admin Class
 */
class TradePress_Pro_Admin {

    /**
     * The single instance of the class
     *
     * @var TradePress_Pro_Admin
     */
    protected static $_instance = null;

    /**
     * Main instance
     *
     * @return TradePress_Pro_Admin
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 60 );

        // Enqueue admin assets
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

        // Show activation notice
        add_action( 'admin_notices', array( $this, 'activation_notice' ) );

        // Add Pro badge to core menu items
        add_action( 'admin_menu', array( $this, 'add_pro_badges' ), 999 );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Add submenu under TradePress
        add_submenu_page(
            'tradepress', // Parent slug (TradePress core menu)
            __( 'TradePress Pro', 'tradepress-pro' ),
            __( 'Pro Features', 'tradepress-pro' ),
            'manage_options',
            'tradepress-pro',
            array( $this, 'render_dashboard' )
        );

        // Add license settings page
        add_submenu_page(
            'tradepress',
            __( 'Pro License', 'tradepress-pro' ),
            __( 'License', 'tradepress-pro' ),
            'manage_options',
            'tradepress-pro-license',
            array( $this, 'render_license_page' )
        );
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook Current admin page hook
     */
    public function enqueue_admin_assets( $hook ) {
        // Only load on TradePress Pro pages
        if ( strpos( $hook, 'tradepress-pro' ) === false ) {
            return;
        }

        wp_enqueue_style(
            'tradepress-pro-admin',
            TRADEPRESS_PRO_URL . 'assets/css/admin.css',
            array(),
            TRADEPRESS_PRO_VERSION
        );

        wp_enqueue_script(
            'tradepress-pro-admin',
            TRADEPRESS_PRO_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            TRADEPRESS_PRO_VERSION,
            true
        );
    }

    /**
     * Show activation notice
     */
    public function activation_notice() {
        if ( ! get_transient( 'tradepress_pro_activated' ) ) {
            return;
        }

        delete_transient( 'tradepress_pro_activated' );

        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                printf(
                    /* translators: %s: Link to license page */
                    __( '<strong>TradePress Pro</strong> has been activated! <a href="%s">Enter your license key</a> to unlock all features.', 'tradepress-pro' ),
                    admin_url( 'admin.php?page=tradepress-pro-license' )
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * Add Pro badges to core menu items
     */
    public function add_pro_badges() {
        // This will add visual indicators for Pro features in the core UI
        // Implementation depends on TradePress core menu structure
    }

    /**
     * Render Pro dashboard
     */
    public function render_dashboard() {
        include TRADEPRESS_PRO_DIR . 'admin/views/dashboard.php';
    }

    /**
     * Render license page
     */
    public function render_license_page() {
        include TRADEPRESS_PRO_DIR . 'admin/views/license.php';
    }
}
