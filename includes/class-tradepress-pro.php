<?php
/**
 * Main TradePress Pro Class
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main TradePress_Pro Class
 */
final class TradePress_Pro {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class
     *
     * @var TradePress_Pro
     */
    protected static $_instance = null;

    /**
     * Main TradePress_Pro Instance
     *
     * Ensures only one instance of TradePress_Pro is loaded or can be loaded.
     *
     * @return TradePress_Pro - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Cloning is forbidden
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'tradepress-pro' ), '1.0.0' );
    }

    /**
     * Unserializing instances of this class is forbidden
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Unserializing instances is forbidden.', 'tradepress-pro' ), '1.0.0' );
    }

    /**
     * TradePress_Pro Constructor
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();

        do_action( 'tradepress_pro_loaded' );
    }

    /**
     * Include required files
     */
    private function includes() {
        // Core classes
        require_once TRADEPRESS_PRO_DIR . 'includes/class-pro-loader.php';
        require_once TRADEPRESS_PRO_DIR . 'includes/class-pro-license.php';

        // Admin
        if ( is_admin() ) {
            require_once TRADEPRESS_PRO_DIR . 'admin/class-pro-admin.php';
        }
    }

    /**
     * Hook into actions and filters
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'init' ), 0 );
        add_action( 'init', array( $this, 'load_textdomain' ) );
    }

    /**
     * Initialize TradePress Pro
     */
    public function init() {
        // Before init action
        do_action( 'tradepress_pro_before_init' );

        // Initialize the loader (registers directives, templates, etc.)
        TradePress_Pro_Loader::instance();

        // Initialize admin
        if ( is_admin() ) {
            TradePress_Pro_Admin::instance();
        }

        // Init action
        do_action( 'tradepress_pro_init' );
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'tradepress-pro',
            false,
            dirname( TRADEPRESS_PRO_BASENAME ) . '/languages'
        );
    }

    /**
     * Get the plugin url
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', TRADEPRESS_PRO_FILE ) );
    }

    /**
     * Get the plugin path
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( TRADEPRESS_PRO_FILE ) );
    }
}
