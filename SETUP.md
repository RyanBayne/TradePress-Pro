# TradePress Pro - Setup & Development Guide

## Initial Setup

### 1. Prerequisites
- WordPress 5.8+
- PHP 7.4+
- TradePress core plugin installed and activated
- Git installed

### 2. Installation for Development

```bash
cd wp-content/plugins/
git clone https://github.com/RyanBayne/tradepress-pro.git
```

### 3. Enable Development Mode

Add to `wp-config.php`:

```php
define( 'TRADEPRESS_PRO_DEV_MODE', true );
```

This bypasses license validation during development.

### 4. Activate the Plugin

Navigate to WordPress admin > Plugins and activate "TradePress Pro".

## Architecture Overview

### Plugin Structure

```
tradepress-pro/
├── tradepress-pro.php              # Main plugin file, bootstrap
├── includes/
│   ├── class-tradepress-pro.php    # Main singleton class
│   ├── class-pro-loader.php        # Registers directives/templates with core
│   ├── class-pro-license.php       # License validation (Freemius ready)
│   ├── directives/                 # Pro directive classes
│   │   └── class-vix-regime.php    # MVP: VIX Regime Scorer
│   ├── strategy-templates/         # Pro strategy template files
│   ├── automation/                 # Scheduled scoring, alerts (future)
│   └── providers/                  # Premium data provider integrations (future)
├── admin/
│   ├── class-pro-admin.php         # Admin menus, pages, assets
│   └── views/
│       ├── dashboard.php           # Pro dashboard view
│       └── license.php             # License management view
├── assets/
│   ├── css/
│   │   └── admin.css               # Admin styles
│   └── js/
│       └── admin.js                # Admin scripts
└── languages/
    └── tradepress-pro.pot          # Translation template
```

### How Pro Integrates with Core

TradePress Pro uses a **hook-based architecture** to extend the core plugin without modifying it.

#### 1. Pro Active Check

```php
// Core checks if Pro is active
if ( tradepress_is_pro_active() ) {
    // Show Pro features
}

// Pro confirms it's active
add_filter( 'tradepress_pro_is_active', function() {
    return TradePress_Pro_License::is_valid();
} );
```

#### 2. Directive Registration

```php
// Core provides registration hook
do_action( 'tradepress_register_directives' );

// Pro registers its directives
add_action( 'tradepress_register_directives', function() {
    tradepress_register_directive( 'vix_regime', array(
        'class'    => 'TradePress_Pro_Directive_VIX_Regime',
        'file'     => TRADEPRESS_PRO_DIR . 'directives/class-vix-regime.php',
        'premium'  => true,
    ) );
} );
```

#### 3. Strategy Template Registration

```php
// Core provides filter
$templates = apply_filters( 'tradepress_strategy_templates', $templates );

// Pro adds its templates
add_filter( 'tradepress_strategy_templates', function( $templates ) {
    $templates['forex_momentum'] = array(
        'name'        => 'Forex Momentum',
        'directives'  => array( 'macd' => 35, 'ema' => 25 ),
        'scope'       => 'forex',
        'premium'     => true,
    );
    return $templates;
} );
```

## Core Changes Required

Before Pro can fully function, TradePress core needs these additions:

### 1. Add Registration Hook

In `includes/class-tradepress-directives.php`:

```php
public function load_directives() {
    // Load core directives...
    
    // Allow Pro to register directives
    do_action( 'tradepress_register_directives' );
}
```

### 2. Add Strategy Template Filter

In `includes/class-tradepress-strategies.php`:

```php
public function get_templates() {
    $templates = array(
        // Core templates...
    );
    
    return apply_filters( 'tradepress_strategy_templates', $templates );
}
```

### 3. Add Pro Active Check

In `includes/functions.php`:

```php
function tradepress_is_pro_active() {
    return apply_filters( 'tradepress_pro_is_active', false );
}
```

### 4. Add Pro Placeholders in UI

In directive/strategy UI:

```php
if ( ! tradepress_is_pro_active() ) {
    echo '<div class="tradepress-pro-prompt">';
    echo '<span class="dashicons dashicons-lock"></span> ';
    esc_html_e( 'Available in TradePress Pro', 'tradepress' );
    echo '</div>';
}
```

## Adding New Directives

### 1. Create Directive Class

Create `includes/directives/class-my-directive.php`:

```php
class TradePress_Pro_Directive_My_Directive {
    public $id = 'my_directive';
    public $name = 'My Directive';
    public $premium = true;
    
    public function calculate( $symbol, $data, $config = array() ) {
        // Your scoring logic
        return array(
            'score'       => 75,
            'confidence'  => 80,
            'explanation' => 'Why this score',
        );
    }
}
```

### 2. Register in Loader

In `includes/class-pro-loader.php`:

```php
public function register_directives() {
    tradepress_register_directive( 'my_directive', array(
        'class'       => 'TradePress_Pro_Directive_My_Directive',
        'file'        => TRADEPRESS_PRO_DIR . 'includes/directives/class-my-directive.php',
        'premium'     => true,
        'name'        => __( 'My Directive', 'tradepress-pro' ),
        'description' => __( 'What it does', 'tradepress-pro' ),
        'category'    => 'technical',
    ) );
}
```

## Adding New Strategy Templates

In `includes/class-pro-loader.php`:

```php
public function register_strategy_templates( $templates ) {
    $templates['my_strategy'] = array(
        'name'        => __( 'My Strategy', 'tradepress-pro' ),
        'description' => __( 'Strategy description', 'tradepress-pro' ),
        'directives'  => array(
            'rsi'  => 30,
            'macd' => 40,
            'ema'  => 30,
        ),
        'scope'       => 'equity', // or 'forex', 'crypto', 'commodity'
        'premium'     => true,
        'category'    => 'momentum',
    );
    
    return $templates;
}
```

## License Integration (Freemius)

### Future Implementation

When ready to integrate Freemius:

1. Download Freemius SDK
2. Add to `includes/libraries/freemius/`
3. Initialize in `tradepress-pro.php`:

```php
if ( ! function_exists( 'tradepress_pro_fs' ) ) {
    function tradepress_pro_fs() {
        global $tradepress_pro_fs;
        
        if ( ! isset( $tradepress_pro_fs ) ) {
            require_once TRADEPRESS_PRO_DIR . 'includes/libraries/freemius/start.php';
            
            $tradepress_pro_fs = fs_dynamic_init( array(
                'id'             => 'YOUR_PRODUCT_ID',
                'slug'           => 'tradepress-pro',
                'premium_slug'   => 'tradepress-pro',
                'type'           => 'plugin',
                'public_key'     => 'YOUR_PUBLIC_KEY',
                'is_premium'     => true,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                    'slug'    => 'tradepress-pro',
                    'parent'  => array(
                        'slug' => 'tradepress',
                    ),
                ),
            ) );
        }
        
        return $tradepress_pro_fs;
    }
    
    tradepress_pro_fs();
}
```

4. Update `class-pro-license.php` to use Freemius methods

## Testing

### Manual Testing Checklist

- [ ] Plugin activates without errors
- [ ] Admin menu appears under TradePress
- [ ] Dashboard displays correctly
- [ ] License page displays correctly
- [ ] VIX Regime directive registers with core
- [ ] Forex Momentum template appears in strategy builder
- [ ] Pro features are gated when license is invalid
- [ ] Pro features unlock when license is valid

### Development Mode Testing

With `TRADEPRESS_PRO_DEV_MODE` enabled:
- All Pro features should be accessible
- No license validation should occur
- Admin notice should indicate dev mode is active

## Deployment Checklist

Before pushing to production:

- [ ] Remove or disable `TRADEPRESS_PRO_DEV_MODE`
- [ ] Integrate Freemius SDK
- [ ] Test license activation/deactivation
- [ ] Test auto-updates via Freemius
- [ ] Generate translation files
- [ ] Update version numbers
- [ ] Update CHANGELOG.md
- [ ] Tag release in Git

## Support & Documentation

- GitHub: https://github.com/RyanBayne/tradepress-pro
- Issues: https://github.com/RyanBayne/tradepress-pro/issues
- Wiki: https://github.com/RyanBayne/tradepress-pro/wiki
