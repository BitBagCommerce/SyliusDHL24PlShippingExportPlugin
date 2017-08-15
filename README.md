![BitBag](https://bitbag.pl/static/bitbag-logo.png)

## Overview

This plugin was made on top of our ShippingExport abstraction layer and it's goal is to allow DHL24PL Shipments to be exported to external web API in Sylius platform based apps. Each time new shipment for configured DHL gateway is placed you will see new shipment in the shipping export tab.

## Installation

```bash
$ composer require bitbag/dhl24-pl-shipping-export-plugin

```
    
Add plugin dependencies to your AppKernel.php file:

```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...
        
        new BitBag\Dhl24PlShippingExportPlugin\Dhl24PlShippingExportPlugin(),
    ]);
}
```

## Testing

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
$ yarn install
$ yarn run gulp
$ php bin/console sylius:install --env test
$ php bin/console server:start --env test
$ open http://localhost:8000
$ bin/behat features/*
$ bin/phpspec run
```
## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/
