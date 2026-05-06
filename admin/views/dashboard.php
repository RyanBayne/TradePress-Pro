<?php
/**
 * TradePress Pro Dashboard View
 *
 * @package TradePressProâ€
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$license_valid = TradePress_Pro_License::is_valid();
$license_status = TradePress_Pro_License::get_status();
?>

<div class="wrap tradepress-pro-dashboard">
    <h1>
        <?php esc_html_e( 'TradePress Pro', 'tradepress-pro' ); ?>
        <?php tradepress_pro_dev_icon(); ?>
    </h1>

    <?php if ( tradepress_pro_is_dev_mode() ) : ?>
        <?php tradepress_pro_dev_notice(); ?>
    <?php endif; ?>

    <?php if ( ! $license_valid ) : ?>
        <div class="notice notice-warning">
            <p>
                <?php
                printf(
                    /* translators: %s: Link to license page */
                    __( 'Your license is not active. <a href="%s">Activate your license</a> to unlock all Pro features.', 'tradepress-pro' ),
                    admin_url( 'admin.php?page=tradepress-pro-license' )
                );
                ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="tradepress-pro-grid">
        <!-- License Status Card -->
        <div class="tradepress-pro-card">
            <h2><?php esc_html_e( 'License Status', 'tradepress-pro' ); ?></h2>
            <p class="license-status <?php echo esc_attr( $license_status ); ?>">
                <span class="dashicons dashicons-<?php echo $license_valid ? 'yes-alt' : 'warning'; ?>"></span>
                <?php echo $license_valid ? esc_html__( 'Active', 'tradepress-pro' ) : esc_html__( 'Inactive', 'tradepress-pro' ); ?>
            </p>
            <p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=tradepress-pro-license' ) ); ?>" class="button">
                    <?php esc_html_e( 'Manage License', 'tradepress-pro' ); ?>
                </a>
            </p>
        </div>

        <!-- Pro Features Card -->
        <div class="tradepress-pro-card">
            <h2><?php esc_html_e( 'Pro Features', 'tradepress-pro' ); ?></h2>
            <ul class="tradepress-pro-features">
                <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Advanced Directives', 'tradepress-pro' ); ?></li>
                <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Strategy Templates', 'tradepress-pro' ); ?></li>
                <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Symbol-Specific Scoring', 'tradepress-pro' ); ?></li>
                <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'VIX Regime Analysis', 'tradepress-pro' ); ?></li>
                <li><span class="dashicons dashicons-chart-line"></span> <?php esc_html_e( 'Automation (Coming Soon)', 'tradepress-pro' ); ?></li>
                <li><span class="dashicons dashicons-chart-line"></span> <?php esc_html_e( 'Premium Data Providers (Coming Soon)', 'tradepress-pro' ); ?></li>
            </ul>
        </div>

        <!-- Quick Links Card -->
        <div class="tradepress-pro-card">
            <h2><?php esc_html_e( 'Quick Links', 'tradepress-pro' ); ?></h2>
            <ul class="tradepress-pro-links">
                <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=tradepress-strategies' ) ); ?>"><?php esc_html_e( 'Strategy Builder', 'tradepress-pro' ); ?></a></li>
                <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=tradepress-directives' ) ); ?>"><?php esc_html_e( 'Directives', 'tradepress-pro' ); ?></a></li>
                <li><a href="https://github.com/RyanBayne/tradepress-pro" target="_blank"><?php esc_html_e( 'Documentation', 'tradepress-pro' ); ?></a></li>
                <li><a href="https://github.com/RyanBayne/tradepress-pro/issues" target="_blank"><?php esc_html_e( 'Support', 'tradepress-pro' ); ?></a></li>
            </ul>
        </div>

        <!-- Version Info Card -->
        <div class="tradepress-pro-card">
            <h2><?php esc_html_e( 'Version Information', 'tradepress-pro' ); ?></h2>
            <p>
                <strong><?php esc_html_e( 'TradePress Pro:', 'tradepress-pro' ); ?></strong>
                <?php echo esc_html( TRADEPRESS_PRO_VERSION ); ?>
            </p>
            <p>
                <strong><?php esc_html_e( 'TradePress Core:', 'tradepress-pro' ); ?></strong>
                <?php echo defined( 'TRADEPRESS_VERSION' ) ? esc_html( TRADEPRESS_VERSION ) : esc_html__( 'Unknown', 'tradepress-pro' ); ?>
            </p>
        </div>
    </div>
</div>

<style>
.tradepress-pro-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
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
    font-size: 18px;
}

.license-status {
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.license-status.valid {
    color: #46b450;
}

.license-status.invalid {
    color: #dc3232;
}

.tradepress-pro-features,
.tradepress-pro-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tradepress-pro-features li,
.tradepress-pro-links li {
    padding: 8px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tradepress-pro-features .dashicons {
    color: #46b450;
}

.tradepress-pro-features .dashicons-chart-line {
    color: #999;
}
</style>
