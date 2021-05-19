<?php

namespace GhoSter\OutOfStockAtLast\Plugin\Model\Product\Indexer\Fulltext\Datasource;

use GhoSter\OutOfStockAtLast\Model\Elasticsearch\Adapter\DataMapper\Stock as StockDataMapper;
use GhoSter\OutOfStockAtLast\Model\ResourceModel\Inventory;
use Magento\Framework\Exception\NoSuchEntityException;
use Smile\ElasticsuiteCatalog\Model\Product\Indexer\Fulltext\Datasource\AttributeData;

class AttributeDataPlugin
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
     * AttributeDataPlugin constructor.
     * @param StockDataMapper $stockDataMapper
     * @param Inventory $inventory
     */
    public function __construct(StockDataMapper $stockDataMapper, Inventory $inventory)
    {
        $this->stockDataMapper = $stockDataMapper;
        $this->inventory = $inventory;
    }

    /**
     * @param AttributeData $subject
     * @param array $result
     * @param $storeId
     * @param array $indexData
     * @return array
     * @throws NoSuchEntityException
     */
    public function afterAddData(
        AttributeData $subject,
        array $result,
        $storeId,
        array $indexData
    ): array {
        $this->inventory->saveRelation(array_keys($indexData));
        foreach ($result as $productId => $item) {
            //@codingStandardsIgnoreLine
            $item = array_merge($item, $this->stockDataMapper->map($productId, $storeId));
            $result[$productId] = $item;
        }
        $this->inventory->clearRelation();

        return $result;
    }
}
