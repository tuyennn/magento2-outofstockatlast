<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Plugin\Model\ResourceModel\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Zend_Db_Select;

class CollectionPlugin
{
    /**
     * @var array
     */
    private $skipFlags = [];

    /**
     * @param Collection $subject
     * @param $attribute
     * @param string $dir
     * @return array
     */
    public function beforeSetOrder(
        Collection $subject,
        $attribute,
        string $dir = Zend_Db_Select::SQL_DESC
    ): array {
        $subject->setFlag('is_processing', true);
        $this->applyOutOfStockAtLastOrders($subject);

        $flagName = $this->_getFlag($attribute);

        if ($subject->getFlag($flagName)) {
            $this->skipFlags[] = $flagName;
        }

        $subject->setFlag('is_processing', false);
        return [$attribute, $dir];
    }

    /**
     * Get flag by attribute
     * @param string $attribute
     * @return string
     */
    private function _getFlag(string $attribute): string
    {
        return 'sorted_by_' . $attribute;
    }

    /**
     * Try to determine applied sorting attribute flags
     * @param $subject
     * @param callable $proceed
     * @param $attribute
     * @param string $dir
     * @return mixed
     */
    public function aroundSetOrder($subject, callable $proceed, $attribute, $dir = Zend_Db_Select::SQL_DESC)
    {
        $flagName = $this->_getFlag($attribute);
        if (!in_array($flagName, $this->skipFlags)) {
            $proceed($attribute, $dir);
        }

        return $subject;
    }

    /**
     * Apply sort orders
     * @param Collection $collection
     */
    private function applyOutOfStockAtLastOrders(Collection $collection)
    {
        if (!$collection->getFlag('is_sorted_by_oos')) {
            $collection->setFlag('is_sorted_by_oos', true);
            $collection->setOrder('out_of_stock_at_last', Zend_Db_Select::SQL_DESC);
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
        string $dir = Zend_Db_Select::SQL_DESC
    ): array {
        if (!$subject->getFlag('is_processing')) {
            $result = $this->beforeSetOrder($subject, $attribute, $dir);
        }

        return $result ?? [$attribute, $dir];
    }
}
