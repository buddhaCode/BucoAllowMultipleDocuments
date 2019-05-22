# BucoAllowMultipleDocuments
Shopware plugin to allow creation of multiple documents per type and order

## Features
Shopware doesn't allow to create more than one document per type for a customer order. For example, one will
create a second delivery slip, the first one will be overwritten. This plugins enables Shopware to create more
than one document for a designated document type. The plugin settings allow to enable this feature on a sub-shop
and document type basis.

## Compatibility
* Shopware >= 5.5.0
* PHP >= 7.0

## Installation

### Git Version
* Checkout plugin in `/custom/plugins/BucoAllowMultipleDocuments`
* Install and active plugin with the Plugin Manager

### Install with composer
* Change to your root installation of Shopware
* Run command `composer require buddha-code/buco-allow-multiple-documents`
* Install and active plugin with `./bin/console sw:plugin:install --activate BucoAllowMultipleDocuments`

## Contributing
Feel free to fork and send pull requests!

## Licence
This project uses the [GPLv3 License](LICENCE).
