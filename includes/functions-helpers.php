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
function tradepress_pro_is_dev_mode() {
    return defined( 'TRADEPRESS_PRO_DEV_MODE' ) && TRADEPRESS_PRO_DEV_MODE;
}

/**
 * Get Pro development spanner icon
 * Only displays when in development mode
 *
 * @param bool $echo Whether to echo or return
 * @return string|void
 */
function tradepress_pro_dev_icon( $echo = true ) {
    if ( ! tradepress_pro_is_dev_mode() ) {
        return '';
    }

    $icon = '<span class="dashicons dashicons-admin-tools tradepress-pro-dev-icon" title="' . esc_attr__( 'Pro Feature (Development Mode)', 'tradepress-pro' ) . '"></span>';

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
function tradepress_pro_badge( $show_dev_icon = true, $echo = true ) {
    $badge = '<span class="tradepress-pro-badge">' . esc_html__( 'PRO', 'tradepress-pro' ) . '</span>';
    
    if ( $show_dev_icon && tradepress_pro_is_dev_mode() ) {
        $badge .= ' ' . tradepress_pro_dev_icon( false );
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
function tradepress_pro_dev_wrap( $content, $label = '' ) {
    if ( ! tradepress_pro_is_dev_mode() ) {
        return $content;
    }

    $output = '<div class="tradepress-pro-dev-wrapper">';
    
    if ( ! empty( $label ) ) {
        $output .= '<div class="tradepress-pro-dev-label">';
        $output .= '<span class="dashicons dashicons-admin-tools"></span> ';
        $output .= '<strong>' . esc_html( $label ) . '</strong>';
        $output .= ' <span class="tradepress-pro-badge">PRO</span>';
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
function tradepress_pro_dev_class( $classes = array() ) {
    if ( ! tradepress_pro_is_dev_mode() ) {
        return $classes;
    }

    if ( is_array( $classes ) ) {
        $classes[] = 'tradepress-pro-dev-item';
    } else {
        $classes .= ' tradepress-pro-dev-item';
    }

    return $classes;
}

/**
 * Output Pro dev notice
 * Shows a notice that a feature is Pro and in development
 *
 * @param string $feature_name Name of the feature
 */
function tradepress_pro_dev_notice( $feature_name = '' ) {
    if ( ! tradepress_pro_is_dev_mode() ) {
        return;
    }

    ?>
    <div class="notice notice-info tradepress-pro-dev-notice">
        <p>
            <span class="dashicons dashicons-admin-tools"></span>
            <strong><?php esc_html_e( 'Pro Development Mode:', 'tradepress-pro' ); ?></strong>
            <?php
            if ( ! empty( $feature_name ) ) {
                printf(
                    /* translators: %s: Feature name */
                    esc_html__( 'You are viewing the Pro feature: %s', 'tradepress-pro' ),
                    '<code>' . esc_html( $feature_name ) . '</code>'
                );
            } else {
                esc_html_e( 'Pro features are visible for development.', 'tradepress-pro' );
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
function tradepress_pro_show_dev_indicators() {
    return tradepress_pro_is_dev_mode() && is_admin();
}
