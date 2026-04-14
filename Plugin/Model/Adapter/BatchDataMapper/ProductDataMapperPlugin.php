<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Plugin\Model\Adapter\BatchDataMapper;

use GhoSter\OutOfStockAtLast\Model\Elasticsearch\Adapter\DataMapper\Stock as StockDataMapper;
use GhoSter\OutOfStockAtLast\Model\ResourceModel\Inventory;
use Magento\Elasticsearch\Model\Adapter\BatchDataMapper\ProductDataMapper;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ProductDataMapperPlugin for mapping hook
 */
class ProductDataMapperPlugin
{
    /**
     * @var StockDataMapper
     */
    protected $stockDataMapper;

    /**
     * @var Inventory
     */
    protected $inventory;

    /**
     * ProductDataMapperPlugin constructor.
     *
     * @param StockDataMapper $stockDataMapper
     * @param Inventory $inventory
     */
    public function __construct(StockDataMapper $stockDataMapper, Inventory $inventory)
    {
        $this->stockDataMapper = $stockDataMapper;
        $this->inventory = $inventory;
    }

    /**
     * Map more attributes
     *
     * @param ProductDataMapper $subject
     * @param array $documents
     * @param array $documentData
     * @param mixed $storeId
     * @param array $context
     * @return array
     * @throws NoSuchEntityException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterMap(
        ProductDataMapper $subject,
        array $documents,
        array $documentData,
        $storeId,
        array $context
    ): array {
        $this->inventory->saveRelation(array_keys($documents));

        foreach ($documents as $productId => $document) {
            //phpcs:ignore Magento2.Performance.ForeachArrayMerge.ForeachArrayMerge
            $document = array_merge($document, $this->stockDataMapper->map($productId, $storeId));
            $documents[$productId] = $document;
        }

        $this->inventory->clearRelation();

        return $documents;
    }
}
