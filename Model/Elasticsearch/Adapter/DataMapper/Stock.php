<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Model\Elasticsearch\Adapter\DataMapper;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use GhoSter\OutOfStockAtLast\Model\ResourceModel\Inventory;

/**
 * Class Stock for mapping
 */
class Stock
{
    /**
     * @var Inventory
     */
    private $inventory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Stock constructor.
     * @param Inventory $inventory
     * @param StoreManagerInterface $storeManager
     * @noinspection PhpUnused
     */
    public function __construct(
        Inventory $inventory,
        StoreManagerInterface $storeManager
    ) {
        $this->inventory = $inventory;
        $this->storeManager = $storeManager;
    }

    /**
     * Map the attribute
     *
     * @param mixed $entityId
     * @param mixed $storeId
     * @return int[]
     * @throws NoSuchEntityException
     */
    public function map($entityId, $storeId): array
    {
        $sku = $this->inventory->getSkuRelation((int)$entityId);

        if (!$sku) {
            return ['out_of_stock_at_last' => 1];
        }

        $value = $this->inventory->getStockStatus(
            $sku,
            $this->storeManager->getStore($storeId)->getWebsite()->getCode()
        );

        return ['out_of_stock_at_last' => $value];
    }
}
