<?php
/**
 * phpcs:ignore Magento2.Legacy.Copyright.FoundCopyrightMissingOrWrongFormat
 * Copyright © GhoSter, Inc. All rights reserved.
 */
declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'GhoSter_OutOfStockAtLast',
    __DIR__
);
