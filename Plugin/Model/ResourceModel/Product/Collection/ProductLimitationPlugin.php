<?php
/** @noinspection PhpUnused */
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Plugin\Model\ResourceModel\Product\Collection;

use GhoSter\OutOfStockAtLast\Model\Elasticsearch\Flag;
use Magento\Catalog\Model\ResourceModel\Product\Collection\ProductLimitation;

/**
 * Class ProductLimitationPlugin ignoring Using Price indexing also @see MC-42243
 */
class ProductLimitationPlugin
{
    /**
     * @var Flag
     */
    private $flag;

    /**
     * @param Flag $flag
     * @noinspection PhpUnused
     */
    public function __construct(Flag $flag)
    {
        $this->flag = $flag;
    }

    /**
     * Ignore using price indexing @see Issue#10
     *
     * @param ProductLimitation $subject
     * @param bool $result
     * @return bool
     * @noinspection PhpUnused
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterIsUsingPriceIndex(ProductLimitation $subject, bool $result): bool
    {
        if ($this->flag->isApplied()) {
            $result = false;
        }

        return $result;
    }
}
