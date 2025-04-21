# PHP client for Soft-Switch PBX API

## References

- [Soft-Switch PBX](http://www.it-communicationsltd.co.uk/Soft-Switch-PBX)
- [Soft-Switch API Documentation](docs/api.md)

## Installation

You can install the package via Composer:

```bash
composer require stephanecoinon/softswitch
```

### Version Compatibility

- **^1.0**: Compatible with Laravel 7.
```bash
composer require stephanecoinon/softswitch:"^1.0"
```

## Configuration

Add the API credentials to your `.env`:

```ini
SOFT_SWITCH_API_URL=https://pbx-cl1-01.soft-switch-pbx.uk/pbx/proxyapi.php
SOFT_SWITCH_API_USERNAME=
SOFT_SWITCH_API_KEY=
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="StephaneCoinon\SoftSwitch\Laravel\SoftSwitchServiceProvider"
```

## Testing

### With code coverage

Enable XDebug then run:

```bash
phpunit --coverage-filter=src/ --coverage-html=coverage-report/
```
