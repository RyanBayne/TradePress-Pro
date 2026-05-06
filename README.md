# TradePress Pro

Premium extension for TradePress - Advanced directives, strategy templates, automation, and premium data providers.

## Features

### Advanced Directives
- **VIX Regime Scorer** - Volatility regime analysis based on VIX levels
- Symbol-specific directives (TSLA, NVDA, CAD/USD, Gold, SPY/QQQ)
- Volatility regime family (HV, ATR, Bollinger Width)
- Macro event proximity family (Fed, CPI, NFP)
- Yield curve and rate sensitivity directives
- Oil commodity directives
- Crypto-specific directives (Fear/Greed, Halving Cycle)
- UK-specific directives (FTSE Sector, GBP Sensitivity)

### Strategy System
- Per-strategy directive threshold overrides
- Symbol scope (strategy scoped to forex/crypto/equity/specific tickers)
- Asset-class strategy templates (Forex, Crypto, Commodity, Dividend, High-Beta, ETF)
- Symbol-specific strategy templates
- Strategy import/export
- Strategy audit history

### Automation (Coming Soon)
- Scheduled scoring (CRON-driven)
- Watchlist automation
- Score change alerts

### Premium Data Providers (Coming Soon)
- Finnhub integration
- FMP (Financial Modeling Prep) integration
- Enhanced provider capabilities

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- TradePress (free core plugin)

## Installation

1. Install and activate the free TradePress plugin from WordPress.org
2. Upload the `tradepress-pro` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to TradePress > License to activate your license key

## Development

### Directory Structure

```
tradepress-pro/
├── tradepress-pro.php              # Main plugin file
├── includes/
│   ├── class-tradepress-pro.php    # Main class
│   ├── class-pro-loader.php        # Registers Pro components
│   ├── class-pro-license.php       # License validation
│   ├── directives/                 # Pro directives
│   ├── strategy-templates/         # Pro strategy templates
│   ├── automation/                 # Automation features
│   └── providers/                  # Premium data providers
├── admin/
│   ├── class-pro-admin.php         # Admin functionality
│   └── views/                      # Admin view templates
├── assets/
│   ├── css/                        # Stylesheets
│   └── js/                         # JavaScript files
└── languages/                      # Translation files
```

### Development Mode

To enable development mode (bypasses license validation):

```php
define( 'TRADEPRESS_PRO_DEV_MODE', true );
```

Add this to your `wp-config.php` file.

## Pricing

- **Personal**: $20/year (1 site) - Launch price
- **Developer**: $40/year (5 sites)
- **Lifetime Early Access**: $79 one-time (1 site) - Limited time

Prices will increase as the product matures and features are added.

## Support

- [GitHub Issues](https://github.com/RyanBayne/tradepress-pro/issues)
- [Documentation](https://github.com/RyanBayne/tradepress-pro/wiki)
- Email support for Pro customers

## Changelog

### 1.0.0 - 2025-01-XX
- Initial release
- VIX Regime Scorer directive
- Forex Momentum strategy template
- License management system
- Pro dashboard and settings

## License

GPL-3.0 License. See LICENSE file for details.

## Author

**Ryan Bayne**
- Website: [ryanbayne.uk](https://www.ryanbayne.uk)
- GitHub: [@RyanBayne](https://github.com/RyanBayne)
