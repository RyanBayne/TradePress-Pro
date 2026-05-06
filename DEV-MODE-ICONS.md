# TradePress Pro - Development Mode Icons

## Overview

TradePress Pro includes a visual indicator system using spanner (🔧) icons to identify Pro features during development. These icons **only appear when `TRADEPRESS_PRO_DEV_MODE` is enabled**, helping you track exactly what's being added to the Pro extension.

## Enabling Development Mode

Add to your `wp-config.php`:

```php
define( 'TRADEPRESS_PRO_DEV_MODE', true );
```

## Visual Indicators

### 1. Spanner Icon (🔧)
- **Color**: Orange/Gold (#f0a000)
- **Animation**: Subtle pulse effect
- **Appears**: Next to Pro feature names, titles, and labels
- **Purpose**: Quick visual identification of Pro features

### 2. Dashed Border Wrapper
- **Style**: Orange dashed border with light background
- **Use**: Wraps entire Pro feature sections
- **Purpose**: Clear visual separation of Pro content

### 3. Dev Notice
- **Type**: WordPress info notice
- **Color**: Orange accent
- **Location**: Top of Pro admin pages
- **Purpose**: Confirms dev mode is active

## Helper Functions

### Basic Icon Display

```php
// Echo the spanner icon (only in dev mode)
tradepress_pro_dev_icon();

// Return the icon HTML
$icon = tradepress_pro_dev_icon( false );
```

### Pro Badge with Dev Icon

```php
// Echo Pro badge with dev icon
tradepress_pro_badge();

// Return Pro badge with dev icon
$badge = tradepress_pro_badge( true, false );

// Pro badge without dev icon
tradepress_pro_badge( false );
```

### Wrap Content with Dev Indicator

```php
// Wrap content with visual dev wrapper
$wrapped = tradepress_pro_dev_wrap( $content, 'Feature Name' );

// Example:
echo tradepress_pro_dev_wrap( 
    '<p>This is a Pro feature</p>', 
    'VIX Regime Scorer' 
);
```

### Add Dev Class to Elements

```php
// Add dev class to array of classes
$classes = array( 'my-class', 'another-class' );
$classes = tradepress_pro_dev_class( $classes );

// Add dev class to string of classes
$class_string = 'my-class another-class';
$class_string = tradepress_pro_dev_class( $class_string );
```

### Display Dev Notice

```php
// Show generic dev notice
tradepress_pro_dev_notice();

// Show dev notice with feature name
tradepress_pro_dev_notice( 'VIX Regime Scorer' );
```

### Check Dev Mode Status

```php
// Check if dev mode is enabled
if ( tradepress_pro_is_dev_mode() ) {
    // Dev mode specific code
}

// Check if we should show dev indicators (dev mode + admin)
if ( tradepress_pro_show_dev_indicators() ) {
    // Show dev UI elements
}
```

## Usage Examples

### In Directive Registration

```php
tradepress_register_directive( 'my_directive', array(
    'name' => __( 'My Directive', 'tradepress-pro' ) . tradepress_pro_dev_icon( false ),
    // ... other args
) );
```

### In Strategy Template Registration

```php
$templates['my_strategy'] = array(
    'name' => __( 'My Strategy', 'tradepress-pro' ) . tradepress_pro_dev_icon( false ),
    // ... other args
);
```

### In Admin Pages

```php
<h1>
    <?php esc_html_e( 'Pro Feature', 'tradepress-pro' ); ?>
    <?php tradepress_pro_dev_icon(); ?>
</h1>

<?php if ( tradepress_pro_is_dev_mode() ) : ?>
    <?php tradepress_pro_dev_notice( 'Feature Name' ); ?>
<?php endif; ?>
```

### Wrapping Feature Sections

```php
<?php
$feature_html = '<div class="feature-content">...</div>';
echo tradepress_pro_dev_wrap( $feature_html, 'Advanced Analytics' );
?>
```

### In Lists/Tables

```php
<ul>
    <li class="<?php echo esc_attr( tradepress_pro_dev_class( 'feature-item' ) ); ?>">
        VIX Regime Scorer
        <?php tradepress_pro_badge(); ?>
    </li>
</ul>
```

## CSS Classes

### Available Classes

- `.tradepress-pro-dev-icon` - The spanner icon
- `.tradepress-pro-dev-wrapper` - Dashed border wrapper
- `.tradepress-pro-dev-label` - Label inside wrapper
- `.tradepress-pro-dev-item` - Individual item with left indicator
- `.tradepress-pro-dev-notice` - Admin notice styling

### Custom Styling

You can override the default styles in your theme or plugin:

```css
/* Change spanner color */
.tradepress-pro-dev-icon {
    color: #ff6600 !important;
}

/* Change wrapper border */
.tradepress-pro-dev-wrapper {
    border-color: #ff6600 !important;
    background: rgba(255, 102, 0, 0.05) !important;
}
```

## Best Practices

### 1. Always Use for New Pro Features

When adding any new Pro feature, include the dev icon:

```php
// ✅ Good
'name' => __( 'New Feature', 'tradepress-pro' ) . tradepress_pro_dev_icon( false ),

// ❌ Bad (missing dev icon)
'name' => __( 'New Feature', 'tradepress-pro' ),
```

### 2. Use Wrappers for Complex Features

For multi-element Pro features, use the wrapper:

```php
echo tradepress_pro_dev_wrap( $complex_feature_html, 'Feature Name' );
```

### 3. Add Dev Notices to Admin Pages

At the top of Pro admin pages:

```php
<?php if ( tradepress_pro_is_dev_mode() ) : ?>
    <?php tradepress_pro_dev_notice( 'Page Name' ); ?>
<?php endif; ?>
```

### 4. Check Dev Mode Before Showing Indicators

The helper functions handle this automatically, but for custom code:

```php
if ( tradepress_pro_is_dev_mode() ) {
    // Show dev-specific UI
}
```

## Production Behavior

When `TRADEPRESS_PRO_DEV_MODE` is **not** defined or set to `false`:

- ✅ All dev icons are hidden
- ✅ Dev wrappers don't render
- ✅ Dev notices don't display
- ✅ Dev classes aren't added
- ✅ Only the Pro badge shows (without spanner)

This ensures a clean production experience while maintaining full visibility during development.

## Troubleshooting

### Icons Not Showing

1. Check `wp-config.php` has: `define( 'TRADEPRESS_PRO_DEV_MODE', true );`
2. Clear WordPress cache
3. Hard refresh browser (Ctrl+F5)
4. Check browser console for CSS errors

### Icons Showing in Production

1. Remove or set to false: `define( 'TRADEPRESS_PRO_DEV_MODE', false );`
2. Never commit `TRADEPRESS_PRO_DEV_MODE` as true to production

### Styling Issues

1. Ensure admin CSS is enqueued
2. Check for CSS conflicts with other plugins
3. Verify dashicons are loaded

## Future Enhancements

Potential additions to the dev icon system:

- Color coding by feature type (directive, template, automation)
- Click-to-copy feature IDs
- Dev mode dashboard showing all Pro features
- Export list of Pro features for documentation
- Toggle dev indicators on/off without changing constant
