<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Model\Elasticsearch\Adapter\DataMapper;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use GhoSter\OutOfStockAtLast\Model\ResourceModel\Inventory;

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
     */
    public function __construct(
        Inventory $inventory,
        StoreManagerInterface $storeManager
    ) {
        $this->inventory = $inventory;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $entityId
     * @param $storeId
     * @return bool[]|int[]
     * @throws NoSuchEntityException
     */
    public function map($entityId, $storeId): array
    {
        $sku = $this->inventory->getSkuRelation((int)$entityId);

        if (!$sku) {
            return ['out_of_stock_at_last' => true];
        }

        $value = $this->inventory->getStockStatus(
            $sku,
            $this->storeManager->getStore($storeId)->getWebsite()->getCode()
        );

        return ['out_of_stock_at_last' => $value];
    }
}
