<?php
/**
 * TradePress Pro Helper Functions
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if Pro development mode is enabled
 *
 * @return bool
 */
function TRADERSEES_is_dev_mode() {
    return defined( 'TRADERSEES_DEV_MODE' ) && TRADERSEES_DEV_MODE;
}

/**
 * Get Pro development spanner icon
 * Only displays when in development mode
 *
 * @param bool $echo Whether to echo or return
 * @return string|void
 */
function TRADERSEES_dev_icon( $echo = true ) {
    if ( ! TRADERSEES_is_dev_mode() ) {
        return '';
    }

    $icon = '<span class="dashicons dashicons-admin-tools trader-sees-dev-icon" title="' . esc_attr__( 'Pro Feature (Development Mode)', 'trader-sees' ) . '"></span>';

    if ( $echo ) {
        echo $icon;
    } else {
        return $icon;
    }
}

/**
 * Get Pro badge with optional dev icon
 *
 * @param bool $show_dev_icon Whether to show dev icon
 * @param bool $echo Whether to echo or return
 * @return string|void
 */
function TRADERSEES_badge( $show_dev_icon = true, $echo = true ) {
    $badge = '<span class="trader-sees-badge">' . esc_html__( 'PRO', 'trader-sees' ) . '</span>';
    
    if ( $show_dev_icon && TRADERSEES_is_dev_mode() ) {
        $badge .= ' ' . TRADERSEES_dev_icon( false );
    }

    if ( $echo ) {
        echo $badge;
    } else {
        return $badge;
    }
}

/**
 * Wrap content with Pro dev indicator
 * Adds a visual wrapper around Pro features in dev mode
 *
 * @param string $content Content to wrap
 * @param string $label Optional label for the feature
 * @return string
 */
function TRADERSEES_dev_wrap( $content, $label = '' ) {
    if ( ! TRADERSEES_is_dev_mode() ) {
        return $content;
    }

    $output = '<div class="trader-sees-dev-wrapper">';
    
    if ( ! empty( $label ) ) {
        $output .= '<div class="trader-sees-dev-label">';
        $output .= '<span class="dashicons dashicons-admin-tools"></span> ';
        $output .= '<strong>' . esc_html( $label ) . '</strong>';
        $output .= ' <span class="trader-sees-badge">PRO</span>';
        $output .= '</div>';
    }
    
    $output .= $content;
    $output .= '</div>';

    return $output;
}

/**
 * Add Pro dev class to element classes
 *
 * @param array|string $classes Existing classes
 * @return array|string Modified classes
 */
function TRADERSEES_dev_class( $classes = array() ) {
    if ( ! TRADERSEES_is_dev_mode() ) {
        return $classes;
    }

    if ( is_array( $classes ) ) {
        $classes[] = 'trader-sees-dev-item';
    } else {
        $classes .= ' trader-sees-dev-item';
    }

    return $classes;
}

/**
 * Output Pro dev notice
 * Shows a notice that a feature is Pro and in development
 *
 * @param string $feature_name Name of the feature
 */
function TRADERSEES_dev_notice( $feature_name = '' ) {
    if ( ! TRADERSEES_is_dev_mode() ) {
        return;
    }

    ?>
    <div class="notice notice-info trader-sees-dev-notice">
        <p>
            <span class="dashicons dashicons-admin-tools"></span>
            <strong><?php esc_html_e( 'Pro Development Mode:', 'trader-sees' ); ?></strong>
            <?php
            if ( ! empty( $feature_name ) ) {
                printf(
                    /* translators: %s: Feature name */
                    esc_html__( 'You are viewing the Pro feature: %s', 'trader-sees' ),
                    '<code>' . esc_html( $feature_name ) . '</code>'
                );
            } else {
                esc_html_e( 'Pro features are visible for development.', 'trader-sees' );
            }
            ?>
        </p>
    </div>
    <?php
}

/**
 * Check if we should show Pro dev indicators
 * Useful for conditional logic
 *
 * @return bool
 */
function TRADERSEES_show_dev_indicators() {
    return TRADERSEES_is_dev_mode() && is_admin();
}
