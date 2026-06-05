<?php
/**
 * TradePress Pro Loader
 *
 * Registers all Pro components with TradePress core hooks
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * TRADERSEES_Loader Class
 */
class TRADERSEES_Loader {

    /**
     * The single instance of the class
     *
     * @var TRADERSEES_Loader
     */
    protected static $_instance = null;

    /**
     * Main instance
     *
     * @return TRADERSEES_Loader
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
        // Register Pro as active with core
        add_filter( 'TRADERSEES_is_active', array( $this, 'confirm_pro_active' ) );

        // Register directives with core
        add_action( 'tradepress_register_directives', array( $this, 'register_directives' ) );

        // Register strategy templates with core
        add_filter( 'tradepress_strategy_templates', array( $this, 'register_strategy_templates' ) );
    }

    /**
     * Confirm Pro is active and licensed
     *
     * @return bool
     */
    public function confirm_pro_active() {
        return TRADERSEES_License::is_valid();
    }

    /**
     * Register Pro directives with core
     */
    public function register_directives() {
        // Only register if license is valid
        if ( ! TRADERSEES_License::is_valid() ) {
            return;
        }

        // Check if core registration function exists
        if ( ! function_exists( 'tradepress_register_directive' ) ) {
            return;
        }

        // MVP: VIX Regime Scorer
        tradepress_register_directive( 'vix_regime', array(
            'class'       => 'TRADERSEES_DIRective_VIX_Regime',
            'file'        => TRADERSEES_DIR . 'includes/directives/class-vix-regime.php',
            'premium'     => true,
            'name'        => __( 'VIX Regime Scorer', 'trader-sees' ) . TRADERSEES_dev_icon( false ),
            'description' => __( 'Scores based on VIX volatility regime (low, normal, elevated, crisis)', 'trader-sees' ),
            'category'    => 'volatility',
        ) );

        // Additional Pro directives will be registered here as they are built
        // Symbol-specific directives
        // Volatility regime family
        // Macro event proximity family
        // Yield curve and rate sensitivity
        // Oil commodity directives
        // Crypto-specific directives
        // UK-specific directives
    }

    /**
     * Register Pro strategy templates with core
     *
     * @param array $templates Existing templates
     * @return array Modified templates
     */
    public function register_strategy_templates( $templates ) {
        // Only register if license is valid
        if ( ! TRADERSEES_License::is_valid() ) {
            return $templates;
        }

        // MVP: Forex Momentum template
        $templates['forex_momentum'] = array(
            'name'        => __( 'Forex Momentum', 'trader-sees' ) . TRADERSEES_dev_icon( false ),
            'description' => __( 'Momentum-based strategy optimized for forex pairs with trend confirmation', 'trader-sees' ),
            'directives'  => array(
                'macd'   => 35,
                'ema'    => 25,
                'adx'    => 25,
                'volume' => 15,
            ),
            'scope'       => 'forex',
            'premium'     => true,
            'category'    => 'forex',
        );

        // Additional Pro templates will be registered here
        // Crypto Cycle
        // Gold Macro
        // Commodity Seasonal
        // Dividend Growth
        // High-Beta Tech
        // ETF Rotation
        // Symbol-specific templates (TSLA, NVDA, etc.)

        return $templates;
    }
}
