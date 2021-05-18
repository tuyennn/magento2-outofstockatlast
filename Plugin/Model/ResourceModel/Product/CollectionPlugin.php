<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Plugin\Model\ResourceModel\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\DB\Select;

class CollectionPlugin
{
    /**
     * @param Collection $subject
     * @param $attribute
     * @param string $dir
     * @return array
     */
    public function beforeSetOrder(
        Collection $subject,
        $attribute,
        string $dir = Select::SQL_DESC
    ): array {
        $subject->setFlag('is_processed', true);
        $this->applyOutOfStockAtLastOrders($subject);
        $subject->setFlag('is_processed', false);
        return [$attribute, $dir];
    }

    /**
     * @param Collection $collection
     */
    private function applyOutOfStockAtLastOrders(Collection $collection)
    {
        if (!$collection->getFlag('is_sorted_by_oos')) {
            $collection->setFlag('is_sorted_by_oos', true);
            $collection->setOrder('out_of_stock_at_last', Select::SQL_DESC);
        }
    }

    /**
     * @param $subject
     * @param $attribute
     * @param string $dir
     * @return array
     */
    public function beforeAddOrder(
        $subject,
        $attribute,
        string $dir = Select::SQL_DESC
    ): array {
        if (!$subject->getFlag('is_processed')) {
            $result = $this->beforeSetOrder($subject, $attribute, $dir);
        }

        return $result ?? [$attribute, $dir];
    }
}
