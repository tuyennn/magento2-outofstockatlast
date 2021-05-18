# Magento 2.4.x module Sort Out Of Stock Product At last the product list

    composer require ghoster/module-outofstockatlast

[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/thinghost)
[![Build Status](https://travis-ci.org/tuyennn/magento2-outofstockatlast.svg?branch=master)](https://travis-ci.org/tuyennn/magento2-outofstockatlast)
![Version 1.0.0](https://img.shields.io/badge/Version-1.0.0-green.svg)

---
- [Extension on GitHub](https://github.com/tuyennn/magento2-outofstockatlast)
- [Direct download link](https://github.com/tuyennn/magento2-outofstockatlast/tarball/master)

## Main Functionalities
- Sort Out Of Stock Product At last the product list
- Firstly `Display Out of Stock Products` Stores > Configuration > Catalog > Inventory > Stock must be set `Yes`
- Of course, we are talking about Elastic Search. We don't support *old search engine*

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

- Unzip the zip file in `app/code/GhoSter`
- Enable the module by running `php bin/magento module:enable GhoSter_OutOfStockAtLast`
- Apply database updates by running `php bin/magento setup:upgrade`\*
- Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

- Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public GitHub repository as vcs
- Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
- Install the module composer by running `composer require ghoster/module-outofstockatlast`
- enable the module by running `php bin/magento module:enable GhoSter_OutOfStockAtLast`
- apply database updates by running `php bin/magento setup:upgrade`\*
- Flush the cache by running `php bin/magento cache:flush`


## Configuration

- Reindexing after you enable the module

