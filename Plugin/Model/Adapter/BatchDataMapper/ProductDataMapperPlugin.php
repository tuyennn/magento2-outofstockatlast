<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Plugin\Model\Adapter\BatchDataMapper;

use Magento\Elasticsearch\Model\Adapter\BatchDataMapper\ProductDataMapper;
use GhoSter\OutOfStockAtLast\Model\Elasticsearch\Adapter\DataMapper\Stock as StockDataMapper;
use GhoSter\OutOfStockAtLast\Model\ResourceModel\Inventory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * phpcs:ignore Magento2.Legacy.Copyright.FoundCopyrightMissingOrWrongFormat
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
     * @param mixed $documents
     * @param mixed $documentData
     * @param mixed $storeId
     * @param mixed $context
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function afterMap(
        ProductDataMapper $subject,
        $documents,
        $documentData,
        $storeId,
        $context
    ) {
        $this->inventory->saveRelation(array_keys($documents));

        foreach ($documents as $productId => $document) {
            //@codingStandardsIgnoreLine
            $document = array_merge($document, $this->stockDataMapper->map($productId, $storeId));
            $documents[$productId] = $document;
        }

        $this->inventory->clearRelation();

        return $documents;
    }
}
