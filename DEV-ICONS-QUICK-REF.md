# Dev Icon Quick Reference

## Enable Dev Mode
```php
// In wp-config.php
define( 'TRADEPRESS_PRO_DEV_MODE', true );
```

## Common Usage

### Add Icon to Feature Name
```php
'name' => __( 'Feature Name', 'tradepress-pro' ) . tradepress_pro_dev_icon( false ),
```

### Show Icon in Title
```php
<h1>
    <?php esc_html_e( 'Pro Feature', 'tradepress-pro' ); ?>
    <?php tradepress_pro_dev_icon(); ?>
</h1>
```

### Show Dev Notice
```php
<?php if ( tradepress_pro_is_dev_mode() ) : ?>
    <?php tradepress_pro_dev_notice( 'Feature Name' ); ?>
<?php endif; ?>
```

### Wrap Feature Section
```php
echo tradepress_pro_dev_wrap( $content, 'Feature Label' );
```

### Pro Badge with Icon
```php
<?php tradepress_pro_badge(); ?>
```

## What Shows in Dev Mode

✅ Orange spanner icon (🔧) next to Pro features
✅ Dashed orange border around Pro sections  
✅ Dev notice at top of Pro pages
✅ Pulsing animation on icons

## What Shows in Production

✅ Pro badge only (no spanner)
❌ No dev icons
❌ No dev wrappers
❌ No dev notices

## Functions

| Function | Purpose |
|----------|---------|
| `tradepress_pro_is_dev_mode()` | Check if dev mode enabled |
| `tradepress_pro_dev_icon()` | Show spanner icon |
| `tradepress_pro_badge()` | Show Pro badge + icon |
| `tradepress_pro_dev_wrap()` | Wrap content with border |
| `tradepress_pro_dev_class()` | Add dev class |
| `tradepress_pro_dev_notice()` | Show dev notice |

See **DEV-MODE-ICONS.md** for full documentation.
