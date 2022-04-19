<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Model\ResourceModel;

use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Module\Manager;

/**
 * Class Inventory for stock processing and calculation
 */
class Inventory extends AbstractDb
{
    /**
     * @var Manager
     */
    private $moduleManager;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @var array
     */
    private $stockStatus;

    /**
     * @var array
     */
    private $stockIds;

    /**
     * @var array
     */
    private $skuRelations;

    /**
     * Inventory constructor.
     *
     * @param Manager $moduleManager
     * @param StockRegistryInterface $stockRegistry
     * @param Context $context
     * @param ?string $connectionName
     * @noinspection PhpDeprecationInspection
     * @noinspection PhpUnused
     */
    public function __construct(
        Manager $moduleManager,
        StockRegistryInterface $stockRegistry,
        Context $context,
        string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->moduleManager = $moduleManager;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * Initialize
     * @noinspection PhpUnused
     */
    protected function _construct()
    {
        $this->stockIds = [];
        $this->skuRelations = [];
    }

    /**
     * Get stock status
     *
     * @param string $productSku
     * @param ?string $websiteCode
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStockStatus(string $productSku, ?string $websiteCode): int
    {
        if ($this->moduleManager->isEnabled('Magento_Inventory')) {
            $stockStatus = $this->getMsiStock($productSku, $websiteCode);
        } else {
            $stockStatus = $this->stockRegistry
                ->getStockItemBySku($productSku, $websiteCode)
                ->getIsInStock();
        }

        return (int)$stockStatus;
    }

    /**
     * Get Msi stock
     *
     * @param string $productSku
     * @param string $websiteCode
     * @return int
     */
    protected function getMsiStock(string $productSku, string $websiteCode): int
    {
        if (!isset($this->stockStatus[$websiteCode][$productSku])) {
            $select = $this->getConnection()->select()
                ->from($this->getTable('inventory_stock_' . $this->getStockId($websiteCode)), ['is_salable'])
                ->where('sku = ?', $productSku)
                ->group('sku');
            $this->stockStatus[$websiteCode][$productSku] = (int) $this->getConnection()->fetchOne($select);
        }

        return $this->stockStatus[$websiteCode][$productSku];
    }

    /**
     * Get stock id
     *
     * @param string $websiteCode
     * @return int|mixed
     */
    public function getStockId(string $websiteCode)
    {
        if (!isset($this->stockIds[$websiteCode])) {
            $select = $this->getConnection()->select()
                ->from($this->getTable('inventory_stock_sales_channel'), ['stock_id'])
                ->where('type = \'website\' AND code = ?', $websiteCode);

            $this->stockIds[$websiteCode] = (int)$this->getConnection()->fetchOne($select);
        }

        return $this->stockIds[$websiteCode];
    }

    /**
     * Relation saving
     *
     * @param array $entityIds
     * @return Inventory
     */
    public function saveRelation(array $entityIds): Inventory
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable('catalog_product_entity'),
            ['entity_id', 'sku']
        )->where('entity_id IN (?)', $entityIds);

        $this->skuRelations = $this->getConnection()->fetchPairs($select);

        return $this;
    }

    /**
     * Clean the relation
     */
    public function clearRelation()
    {
        $this->skuRelations = null;
    }

    /**
     * Get sku relation
     *
     * @param int $entityId
     * @return string
     */
    public function getSkuRelation(int $entityId): string
    {
        return $this->skuRelations[$entityId] ?? '';
    }
}
