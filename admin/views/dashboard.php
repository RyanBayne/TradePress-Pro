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

$license_valid = TRADERSEES_License::is_valid();
$license_status = TRADERSEES_License::get_status();
?>

<div class="wrap trader-sees-dashboard">
    <h1>
        <?php esc_html_e( 'TradePress Pro', 'trader-sees' ); ?>
        <?php TRADERSEES_dev_icon(); ?>
    </h1>

    <?php if ( TRADERSEES_is_dev_mode() ) : ?>
        <?php TRADERSEES_dev_notice(); ?>
    <?php endif; ?>

    <?php if ( ! $license_valid ) : ?>
        <div class="notice notice-warning">
            <p>
                <?php
                printf(
                    /* translators: %s: Link to license page */
                    __( 'Your license is not active. <a href="%s">Activate your license</a> to unlock all Pro features.', 'trader-sees' ),
                    admin_url( 'admin.php?page=trader-sees-license' )
                );
                ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="trader-sees-grid">
        <!-- License Status Card -->
        <div class="trader-sees-card">
            <h2><?php esc_html_e( 'License Status', 'trader-sees' ); ?></h2>
            <p class="license-status <?php echo esc_attr( $license_status ); ?>">
                <span class="dashicons dashicons-<?php echo $license_valid ? 'yes-alt' : 'warning'; ?>"></span>
                <?php echo $license_valid ? esc_html__( 'Active', 'trader-sees' ) : esc_html__( 'Inactive', 'trader-sees' ); ?>
            </p>
            <p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=trader-sees-license' ) ); ?>" class="button">
                    <?php esc_html_e( 'Manage License', 'trader-sees' ); ?>
                </a>
            </p>
        </div>

        <!-- Pro Features Card -->
        <div class="trader-sees-card">
            <h2><?php esc_html_e( 'Pro Features', 'trader-sees' ); ?></h2>
            <ul class="trader-sees-features">
                <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Advanced Directives', 'trader-sees' ); ?></li>
                <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Strategy Templates', 'trader-sees' ); ?></li>
                <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Symbol-Specific Scoring', 'trader-sees' ); ?></li>
                <li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'VIX Regime Analysis', 'trader-sees' ); ?></li>
                <li><span class="dashicons dashicons-chart-line"></span> <?php esc_html_e( 'Automation (Coming Soon)', 'trader-sees' ); ?></li>
                <li><span class="dashicons dashicons-chart-line"></span> <?php esc_html_e( 'Premium Data Providers (Coming Soon)', 'trader-sees' ); ?></li>
            </ul>
        </div>

        <!-- Quick Links Card -->
        <div class="trader-sees-card">
            <h2><?php esc_html_e( 'Quick Links', 'trader-sees' ); ?></h2>
            <ul class="trader-sees-links">
                <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=tradepress-strategies' ) ); ?>"><?php esc_html_e( 'Strategy Builder', 'trader-sees' ); ?></a></li>
                <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=tradepress-directives' ) ); ?>"><?php esc_html_e( 'Directives', 'trader-sees' ); ?></a></li>
                <li><a href="https://github.com/RyanBayne/trader-sees" target="_blank"><?php esc_html_e( 'Documentation', 'trader-sees' ); ?></a></li>
                <li><a href="https://github.com/RyanBayne/trader-sees/issues" target="_blank"><?php esc_html_e( 'Support', 'trader-sees' ); ?></a></li>
            </ul>
        </div>

        <!-- Version Info Card -->
        <div class="trader-sees-card">
            <h2><?php esc_html_e( 'Version Information', 'trader-sees' ); ?></h2>
            <p>
                <strong><?php esc_html_e( 'TradePress Pro:', 'trader-sees' ); ?></strong>
                <?php echo esc_html( TRADERSEES_VERSION ); ?>
            </p>
            <p>
                <strong><?php esc_html_e( 'TradePress Core:', 'trader-sees' ); ?></strong>
                <?php echo defined( 'TRADEPRESS_VERSION' ) ? esc_html( TRADEPRESS_VERSION ) : esc_html__( 'Unknown', 'trader-sees' ); ?>
            </p>
        </div>
    </div>
</div>

<style>
.trader-sees-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.trader-sees-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    padding: 20px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.trader-sees-card h2 {
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

.trader-sees-features,
.trader-sees-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.trader-sees-features li,
.trader-sees-links li {
    padding: 8px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.trader-sees-features .dashicons {
    color: #46b450;
}

.trader-sees-features .dashicons-chart-line {
    color: #999;
}
</style>
