![BitBag](https://bitbag.pl/static/bitbag-logo.png)

## Overview

This plugin was made on top of our ShippingExport abstraction layer and it's goal is to allow DHL24PL Shipments to be exported to external web API in Sylius platform based apps. Each time new shipment for configured DHL gateway is placed you will see new shipment in the shipping export tab.

## Support

We work on amazing eCommerce projects on top of Sylius and Pimcore. Need some help or additional resources for a project?
Write us an email on mikolaj.krol@bitbag.pl or visit [our website](https://bitbag.shop/)! :rocket:

## Demo

We created a demo app with some useful use-cases of the plugin! Visit [demo.bitbag.shop](https://demo.bitbag.shop) to take a look at it. 
The admin can be accessed under [demo.bitbag.shop/admin](https://demo.bitbag.shop/admin) link and `sylius: sylius` credentials.

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
