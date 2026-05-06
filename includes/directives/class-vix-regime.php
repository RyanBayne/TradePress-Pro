<?php
/**
 * VIX Regime Scorer Directive
 *
 * Scores based on VIX volatility regime
 * - Low VIX (< 15): Complacency, potential for sudden moves
 * - Normal VIX (15-20): Healthy market
 * - Elevated VIX (20-30): Caution warranted
 * - Crisis VIX (> 30): High risk environment
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * TradePress_Pro_Directive_VIX_Regime Class
 */
class TradePress_Pro_Directive_VIX_Regime {

    /**
     * Directive ID
     *
     * @var string
     */
    public $id = 'vix_regime';

    /**
     * Directive name
     *
     * @var string
     */
    public $name = 'VIX Regime Scorer';

    /**
     * Directive description
     *
     * @var string
     */
    public $description = 'Scores based on VIX volatility regime';

    /**
     * Directive category
     *
     * @var string
     */
    public $category = 'volatility';

    /**
     * Is premium directive
     *
     * @var bool
     */
    public $premium = true;

    /**
     * Calculate the directive score
     *
     * @param string $symbol Symbol to score
     * @param array  $data   Market data
     * @param array  $config Directive configuration
     * @return array Score result
     */
    public function calculate( $symbol, $data, $config = array() ) {
        // Get VIX value
        $vix_value = $this->get_vix_value( $data );

        if ( false === $vix_value ) {
            return array(
                'score'       => 0,
                'confidence'  => 0,
                'explanation' => __( 'VIX data not available', 'tradepress-pro' ),
                'regime'      => 'unknown',
            );
        }

        // Determine regime and score
        $regime = $this->determine_regime( $vix_value );
        $score = $this->calculate_score( $vix_value, $regime );

        return array(
            'score'       => $score,
            'confidence'  => 85, // High confidence when VIX data is available
            'explanation' => $this->get_explanation( $vix_value, $regime ),
            'regime'      => $regime,
            'vix_value'   => $vix_value,
        );
    }

    /**
     * Get current VIX value
     *
     * @param array $data Market data
     * @return float|false
     */
    private function get_vix_value( $data ) {
        // Check if VIX is provided in data
        if ( isset( $data['vix'] ) ) {
            return floatval( $data['vix'] );
        }

        // Try to get from TradePress data providers
        if ( function_exists( 'tradepress_get_market_data' ) ) {
            $vix_data = tradepress_get_market_data( '^VIX' );
            if ( isset( $vix_data['price'] ) ) {
                return floatval( $vix_data['price'] );
            }
        }

        return false;
    }

    /**
     * Determine VIX regime
     *
     * @param float $vix_value VIX value
     * @return string Regime name
     */
    private function determine_regime( $vix_value ) {
        if ( $vix_value < 15 ) {
            return 'low';
        } elseif ( $vix_value < 20 ) {
            return 'normal';
        } elseif ( $vix_value < 30 ) {
            return 'elevated';
        } else {
            return 'crisis';
        }
    }

    /**
     * Calculate score based on VIX regime
     *
     * @param float  $vix_value VIX value
     * @param string $regime    Regime name
     * @return int Score (0-100)
     */
    private function calculate_score( $vix_value, $regime ) {
        switch ( $regime ) {
            case 'low':
                // Low VIX: Moderate score (complacency risk)
                return 50;

            case 'normal':
                // Normal VIX: High score (healthy market)
                return 75;

            case 'elevated':
                // Elevated VIX: Lower score (caution)
                return 40;

            case 'crisis':
                // Crisis VIX: Low score (high risk)
                return 20;

            default:
                return 0;
        }
    }

    /**
     * Get human-readable explanation
     *
     * @param float  $vix_value VIX value
     * @param string $regime    Regime name
     * @return string
     */
    private function get_explanation( $vix_value, $regime ) {
        $explanations = array(
            'low'      => sprintf(
                /* translators: %s: VIX value */
                __( 'VIX at %s indicates low volatility. Market complacency may precede sudden moves.', 'tradepress-pro' ),
                number_format( $vix_value, 2 )
            ),
            'normal'   => sprintf(
                /* translators: %s: VIX value */
                __( 'VIX at %s indicates normal volatility. Healthy market conditions.', 'tradepress-pro' ),
                number_format( $vix_value, 2 )
            ),
            'elevated' => sprintf(
                /* translators: %s: VIX value */
                __( 'VIX at %s indicates elevated volatility. Exercise caution.', 'tradepress-pro' ),
                number_format( $vix_value, 2 )
            ),
            'crisis'   => sprintf(
                /* translators: %s: VIX value */
                __( 'VIX at %s indicates crisis-level volatility. High risk environment.', 'tradepress-pro' ),
                number_format( $vix_value, 2 )
            ),
        );

        return isset( $explanations[ $regime ] ) ? $explanations[ $regime ] : '';
    }

    /**
     * Get directive metadata
     *
     * @return array
     */
    public function get_metadata() {
        return array(
            'id'                  => $this->id,
            'name'                => $this->name,
            'description'         => $this->description,
            'category'            => $this->category,
            'premium'             => $this->premium,
            'recommended_symbols' => array( 'SPY', 'QQQ', 'IWM', 'DIA' ), // US equity indices
            'data_requirements'   => array( 'vix' ),
        );
    }
}
