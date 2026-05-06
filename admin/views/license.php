<?php
/**
 * TradePress Pro License View
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Handle license activation/deactivation
if ( isset( $_POST['tradepress_pro_license_action'] ) && check_admin_referer( 'tradepress_pro_license' ) ) {
    $action = sanitize_text_field( $_POST['tradepress_pro_license_action'] );

    if ( 'activate' === $action && isset( $_POST['license_key'] ) ) {
        $license_key = sanitize_text_field( $_POST['license_key'] );
        $result = TradePress_Pro_License::activate( $license_key );

        if ( is_wp_error( $result ) ) {
            echo '<div class="notice notice-error"><p>' . esc_html( $result->get_error_message() ) . '</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>' . esc_html__( 'License activated successfully!', 'tradepress-pro' ) . '</p></div>';
        }
    } elseif ( 'deactivate' === $action ) {
        TradePress_Pro_License::deactivate();
        echo '<div class="notice notice-success"><p>' . esc_html__( 'License deactivated.', 'tradepress-pro' ) . '</p></div>';
    }
}

$license_valid = TradePress_Pro_License::is_valid();
$license_status = TradePress_Pro_License::get_status();
$license_key = get_option( 'tradepress_pro_license_key', '' );
$expiration = TradePress_Pro_License::get_expiration();
?>

<div class="wrap tradepress-pro-license">
    <h1><?php esc_html_e( 'TradePress Pro License', 'tradepress-pro' ); ?></h1>

    <div class="tradepress-pro-license-container">
        <?php if ( $license_valid ) : ?>
            <!-- Active License -->
            <div class="tradepress-pro-card license-active">
                <h2>
                    <span class="dashicons dashicons-yes-alt"></span>
                    <?php esc_html_e( 'License Active', 'tradepress-pro' ); ?>
                </h2>

                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e( 'License Key:', 'tradepress-pro' ); ?></th>
                        <td>
                            <code><?php echo esc_html( substr( $license_key, 0, 8 ) . str_repeat( '*', strlen( $license_key ) - 12 ) . substr( $license_key, -4 ) ); ?></code>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Status:', 'tradepress-pro' ); ?></th>
                        <td>
                            <span class="license-status-badge active"><?php esc_html_e( 'Active', 'tradepress-pro' ); ?></span>
                        </td>
                    </tr>
                    <?php if ( $expiration ) : ?>
                    <tr>
                        <th><?php esc_html_e( 'Expires:', 'tradepress-pro' ); ?></th>
                        <td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expiration ) ) ); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>

                <form method="post">
                    <?php wp_nonce_field( 'tradepress_pro_license' ); ?>
                    <input type="hidden" name="tradepress_pro_license_action" value="deactivate">
                    <p>
                        <button type="submit" class="button button-secondary">
                            <?php esc_html_e( 'Deactivate License', 'tradepress-pro' ); ?>
                        </button>
                    </p>
                </form>
            </div>

        <?php else : ?>
            <!-- Inactive License -->
            <div class="tradepress-pro-card license-inactive">
                <h2>
                    <span class="dashicons dashicons-warning"></span>
                    <?php esc_html_e( 'Activate Your License', 'tradepress-pro' ); ?>
                </h2>

                <p><?php esc_html_e( 'Enter your license key to unlock all TradePress Pro features.', 'tradepress-pro' ); ?></p>

                <form method="post">
                    <?php wp_nonce_field( 'tradepress_pro_license' ); ?>
                    <input type="hidden" name="tradepress_pro_license_action" value="activate">

                    <table class="form-table">
                        <tr>
                            <th>
                                <label for="license_key"><?php esc_html_e( 'License Key:', 'tradepress-pro' ); ?></label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="license_key"
                                    name="license_key"
                                    class="regular-text"
                                    placeholder="<?php esc_attr_e( 'Enter your license key', 'tradepress-pro' ); ?>"
                                    required
                                >
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-primary">
                            <?php esc_html_e( 'Activate License', 'tradepress-pro' ); ?>
                        </button>
                    </p>
                </form>

                <div class="tradepress-pro-license-help">
                    <h3><?php esc_html_e( 'Need a License?', 'tradepress-pro' ); ?></h3>
                    <p>
                        <?php
                        printf(
                            /* translators: %s: Purchase URL */
                            __( '<a href="%s" target="_blank">Purchase a license</a> to unlock all Pro features.', 'tradepress-pro' ),
                            'https://github.com/RyanBayne/tradepress-pro'
                        );
                        ?>
                    </p>
                    <p>
                        <?php
                        printf(
                            /* translators: %s: Support URL */
                            __( 'Lost your license key? <a href="%s" target="_blank">Contact support</a>.', 'tradepress-pro' ),
                            'https://github.com/RyanBayne/tradepress-pro/issues'
                        );
                        ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Development Mode Notice -->
        <?php if ( defined( 'TRADEPRESS_PRO_DEV_MODE' ) && TRADEPRESS_PRO_DEV_MODE ) : ?>
            <div class="notice notice-info">
                <p>
                    <strong><?php esc_html_e( 'Development Mode Active', 'tradepress-pro' ); ?></strong><br>
                    <?php esc_html_e( 'License validation is bypassed. All Pro features are available.', 'tradepress-pro' ); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.tradepress-pro-license-container {
    max-width: 800px;
    margin-top: 20px;
}

.tradepress-pro-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    padding: 20px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.tradepress-pro-card h2 {
    margin-top: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.license-active h2 {
    color: #46b450;
}

.license-inactive h2 {
    color: #dc3232;
}

.license-status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 3px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
}

.license-status-badge.active {
    background: #46b450;
    color: #fff;
}

.tradepress-pro-license-help {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
}

.tradepress-pro-license-help h3 {
    margin-top: 0;
}
</style>
