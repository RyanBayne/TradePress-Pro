<?php
/**
 * TradePress Pro License Handler
 *
 * Handles license validation and activation
 * Ready for Freemius integration
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * TradePress_Pro_License Class
 */
class TradePress_Pro_License {

    /**
     * Check if license is valid
     *
     * @return bool
     */
    public static function is_valid() {
        // TODO: Integrate with Freemius
        // For now, return true for development
        // In production, this will check:
        // - License key validity
        // - Expiration date
        // - Site activation status
        
        if ( defined( 'TRADEPRESS_PRO_DEV_MODE' ) && TRADEPRESS_PRO_DEV_MODE ) {
            return true;
        }

        // Freemius integration will go here
        // Example:
        // if ( function_exists( 'tradepress_pro_fs' ) ) {
        //     return tradepress_pro_fs()->is_premium() && tradepress_pro_fs()->can_use_premium_code();
        // }

        // Check for stored license key (temporary until Freemius is integrated)
        $license_key = get_option( 'tradepress_pro_license_key', '' );
        $license_status = get_option( 'tradepress_pro_license_status', '' );

        return ! empty( $license_key ) && 'valid' === $license_status;
    }

    /**
     * Get license status
     *
     * @return string
     */
    public static function get_status() {
        if ( ! self::is_valid() ) {
            return 'invalid';
        }

        // TODO: Return actual license status from Freemius
        return get_option( 'tradepress_pro_license_status', 'unknown' );
    }

    /**
     * Get license expiration date
     *
     * @return string|false
     */
    public static function get_expiration() {
        if ( ! self::is_valid() ) {
            return false;
        }

        // TODO: Get expiration from Freemius
        return get_option( 'tradepress_pro_license_expiration', false );
    }

    /**
     * Activate license
     *
     * @param string $license_key
     * @return bool|WP_Error
     */
    public static function activate( $license_key ) {
        // TODO: Implement Freemius activation
        // For now, simple storage
        update_option( 'tradepress_pro_license_key', sanitize_text_field( $license_key ) );
        update_option( 'tradepress_pro_license_status', 'valid' );

        return true;
    }

    /**
     * Deactivate license
     *
     * @return bool
     */
    public static function deactivate() {
        // TODO: Implement Freemius deactivation
        delete_option( 'tradepress_pro_license_key' );
        delete_option( 'tradepress_pro_license_status' );
        delete_option( 'tradepress_pro_license_expiration' );

        return true;
    }

    /**
     * Check if license is expiring soon (within 30 days)
     *
     * @return bool
     */
    public static function is_expiring_soon() {
        $expiration = self::get_expiration();
        
        if ( ! $expiration ) {
            return false;
        }

        $expiration_timestamp = strtotime( $expiration );
        $thirty_days = 30 * DAY_IN_SECONDS;

        return ( $expiration_timestamp - time() ) < $thirty_days;
    }
}
