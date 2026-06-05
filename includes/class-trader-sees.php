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
 * Main TraderSEES Class
 */
final class TraderSEES {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class
     *
     * @var TraderSEES
     */
    protected static $_instance = null;

    /**
     * Main TraderSEES Instance
     *
     * Ensures only one instance of TraderSEES is loaded or can be loaded.
     *
     * @return TraderSEES - Main instance
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
        _doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'trader-sees' ), '1.0.0' );
    }

    /**
     * Unserializing instances of this class is forbidden
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Unserializing instances is forbidden.', 'trader-sees' ), '1.0.0' );
    }

    /**
     * TraderSEES Constructor
     */
    public function __construct() {
        $this->includes();
        $this->init_hooks();

        do_action( 'TRADERSEES_loaded' );
    }

    /**
     * Include required files
     */
    private function includes() {
        // Helper functions
        require_once TRADERSEES_DIR . 'includes/functions-helpers.php';
        
        // Core classes
        require_once TRADERSEES_DIR . 'includes/class-pro-loader.php';
        require_once TRADERSEES_DIR . 'includes/class-pro-license.php';

        // Admin
        if ( is_admin() ) {
            require_once TRADERSEES_DIR . 'admin/class-pro-admin.php';
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
        do_action( 'TRADERSEES_before_init' );

        // Initialize the loader (registers directives, templates, etc.)
        TRADERSEES_Loader::instance();

        // Initialize admin
        if ( is_admin() ) {
            TRADERSEES_Admin::instance();
        }

        // Init action
        do_action( 'TRADERSEES_init' );
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'trader-sees',
            false,
            dirname( TRADERSEES_BASENAME ) . '/languages'
        );
    }

    /**
     * Get the plugin url
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', TRADERSEES_FILE ) );
    }

    /**
     * Get the plugin path
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( TRADERSEES_FILE ) );
    }
}
