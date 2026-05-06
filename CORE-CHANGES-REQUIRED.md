# TradePress Core Changes Required for Pro Integration

This document outlines the exact changes needed in TradePress core to support Pro.

## 1. Add Pro Active Check Function

**File**: `includes/functions.php` (or create `includes/functions-pro.php`)

```php
/**
 * Check if TradePress Pro is active and licensed
 *
 * @return bool
 */
function tradepress_is_pro_active() {
    return apply_filters( 'tradepress_pro_is_active', false );
}
```

## 2. Add Directive Registration Hook

**File**: `includes/class-tradepress-directives.php` (or wherever directives are loaded)

```php
/**
 * Load all directives
 */
public function load_directives() {
    // Load core directives
    $this->load_core_directives();
    
    // Allow Pro and other extensions to register directives
    do_action( 'tradepress_register_directives' );
}

/**
 * Register a directive
 *
 * @param string $id Directive ID
 * @param array $args Directive arguments
 */
function tradepress_register_directive( $id, $args ) {
    global $tradepress_directives;
    
    if ( ! isset( $tradepress_directives ) ) {
        $tradepress_directives = array();
    }
    
    // Load the directive file if specified
    if ( isset( $args['file'] ) && file_exists( $args['file'] ) ) {
        require_once $args['file'];
    }
    
    // Store directive registration
    $tradepress_directives[ $id ] = $args;
}
```

## 3. Add Strategy Template Filter

**File**: `includes/class-tradepress-strategies.php` (or wherever templates are defined)

```php
/**
 * Get strategy templates
 *
 * @return array
 */
public function get_templates() {
    $templates = array(
        // Core templates
        'momentum_confluence' => array(
            'name'        => __( 'Momentum Confluence', 'tradepress' ),
            'description' => __( 'Multiple momentum indicators must align', 'tradepress' ),
            'directives'  => array(
                'rsi'  => 30,
                'macd' => 35,
                'ema'  => 20,
                'adx'  => 15,
            ),
            'premium'     => false,
        ),
        'mean_reversion' => array(
            'name'        => __( 'Mean Reversion', 'tradepress' ),
            'description' => __( 'Identify oversold/overbought conditions', 'tradepress' ),
            'directives'  => array(
                'rsi'              => 40,
                'bollinger_bands'  => 30,
                'stochastic'       => 20,
                'volume'           => 10,
            ),
            'premium'     => false,
        ),
        'trend_strength' => array(
            'name'        => __( 'Trend Strength', 'tradepress' ),
            'description' => __( 'Strong trending markets', 'tradepress' ),
            'directives'  => array(
                'adx'    => 40,
                'ema'    => 30,
                'macd'   => 20,
                'volume' => 10,
            ),
            'premium'     => false,
        ),
    );
    
    // Allow Pro to add templates
    return apply_filters( 'tradepress_strategy_templates', $templates );
}
```

## 4. Add Pro Placeholders in UI

### In Directive List

**File**: `admin/views/directives-list.php` (or similar)

```php
<?php foreach ( $directives as $directive_id => $directive ) : ?>
    <div class="directive-item">
        <h3>
            <?php echo esc_html( $directive['name'] ); ?>
            <?php if ( ! empty( $directive['premium'] ) ) : ?>
                <?php if ( tradepress_is_pro_active() ) : ?>
                    <span class="tradepress-pro-badge"><?php esc_html_e( 'PRO', 'tradepress' ); ?></span>
                <?php else : ?>
                    <span class="tradepress-pro-badge locked"><?php esc_html_e( 'PRO', 'tradepress' ); ?></span>
                <?php endif; ?>
            <?php endif; ?>
        </h3>
        
        <?php if ( ! empty( $directive['premium'] ) && ! tradepress_is_pro_active() ) : ?>
            <div class="tradepress-pro-prompt">
                <span class="dashicons dashicons-lock"></span>
                <?php
                printf(
                    /* translators: %s: Link to Pro */
                    __( 'Available in <a href="%s">TradePress Pro</a>', 'tradepress' ),
                    admin_url( 'admin.php?page=tradepress-pro' )
                );
                ?>
            </div>
        <?php else : ?>
            <p><?php echo esc_html( $directive['description'] ); ?></p>
            <!-- Directive controls -->
        <?php endif; ?>
    </div>
<?php endforeach; ?>
```

### In Strategy Template Selector

**File**: `admin/views/strategy-builder.php` (or similar)

```php
<select name="strategy_template" id="strategy-template">
    <option value=""><?php esc_html_e( 'Select a template...', 'tradepress' ); ?></option>
    <?php foreach ( $templates as $template_id => $template ) : ?>
        <?php
        $is_premium = ! empty( $template['premium'] );
        $is_locked = $is_premium && ! tradepress_is_pro_active();
        ?>
        <option 
            value="<?php echo esc_attr( $template_id ); ?>"
            <?php disabled( $is_locked ); ?>
        >
            <?php echo esc_html( $template['name'] ); ?>
            <?php if ( $is_premium ) : ?>
                <?php echo $is_locked ? ' 🔒 PRO' : ' ⭐ PRO'; ?>
            <?php endif; ?>
        </option>
    <?php endforeach; ?>
</select>

<?php if ( ! tradepress_is_pro_active() ) : ?>
    <p class="description">
        <?php
        printf(
            /* translators: %s: Link to Pro */
            __( 'Unlock premium templates with <a href="%s">TradePress Pro</a>', 'tradepress' ),
            admin_url( 'admin.php?page=tradepress-pro' )
        );
        ?>
    </p>
<?php endif; ?>
```

## 5. Add Pro Badge CSS

**File**: `assets/css/admin.css`

```css
/* Pro Badge */
.tradepress-pro-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    margin-left: 8px;
    vertical-align: middle;
}

.tradepress-pro-badge.locked {
    background: #999;
}

/* Pro Prompt */
.tradepress-pro-prompt {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    background: #f0f0f1;
    border: 1px solid #dcdcde;
    border-radius: 4px;
    color: #50575e;
    font-size: 13px;
}

.tradepress-pro-prompt .dashicons {
    color: #667eea;
}

.tradepress-pro-prompt a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.tradepress-pro-prompt a:hover {
    text-decoration: underline;
}
```

## 6. Add Symbol Scope Support (for #103)

**File**: `includes/class-tradepress-strategies.php`

Add to strategy metadata:

```php
/**
 * Strategy metadata
 */
$strategy_meta = array(
    'name'         => 'My Strategy',
    'directives'   => array( /* ... */ ),
    'symbol_scope' => 'forex', // 'all', 'forex', 'crypto', 'equity', 'commodity', or specific symbols
);
```

Add validation:

```php
/**
 * Check if strategy applies to symbol
 *
 * @param array $strategy Strategy data
 * @param string $symbol Symbol to check
 * @return bool
 */
function tradepress_strategy_applies_to_symbol( $strategy, $symbol ) {
    if ( empty( $strategy['symbol_scope'] ) || 'all' === $strategy['symbol_scope'] ) {
        return true;
    }
    
    // Get symbol type
    $symbol_type = tradepress_get_symbol_type( $symbol );
    
    // Check if scope matches symbol type
    if ( $strategy['symbol_scope'] === $symbol_type ) {
        return true;
    }
    
    // Check if specific symbol
    if ( is_array( $strategy['symbol_scope'] ) && in_array( $symbol, $strategy['symbol_scope'], true ) ) {
        return true;
    }
    
    return false;
}
```

## 7. Add Per-Strategy Threshold Overrides (for #104)

**File**: `includes/class-tradepress-strategies.php`

```php
/**
 * Get directive threshold for strategy
 *
 * @param string $strategy_id Strategy ID
 * @param string $directive_id Directive ID
 * @return int Threshold value
 */
function tradepress_get_strategy_directive_threshold( $strategy_id, $directive_id ) {
    $strategy = tradepress_get_strategy( $strategy_id );
    
    // Check for strategy-specific override
    if ( isset( $strategy['directive_thresholds'][ $directive_id ] ) ) {
        return intval( $strategy['directive_thresholds'][ $directive_id ] );
    }
    
    // Fall back to global directive threshold
    return tradepress_get_directive_threshold( $directive_id );
}
```

## Testing the Integration

After making these changes:

1. Activate TradePress Pro
2. Enable dev mode: `define( 'TRADEPRESS_PRO_DEV_MODE', true );`
3. Check that:
   - VIX Regime appears in directive list with PRO badge
   - Forex Momentum appears in strategy templates with PRO badge
   - Pro features are accessible when dev mode is on
   - Pro features show lock prompt when dev mode is off and no license

## Priority Order

1. **High Priority** (needed for MVP):
   - `tradepress_is_pro_active()` function
   - `tradepress_register_directives` action
   - `tradepress_strategy_templates` filter

2. **Medium Priority** (needed for full Pro experience):
   - Pro UI placeholders
   - Pro badge CSS
   - Symbol scope support

3. **Low Priority** (can be added later):
   - Per-strategy threshold overrides
   - Advanced SEES diagnostics hooks
