<?php
namespace GhoSter\OutOfStockAtLast\Plugin\DataProvider\Product\SearchCriteriaBuilder;

use GhoSter\OutOfStockAtLast\Model\AdditionalAttribute;
use Magento\CatalogGraphQl\DataProvider\Product\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;

class AddDefaultOrders
{
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * Add default sorting.
     *
     * @param SearchCriteriaBuilder $subject
     * @param SearchCriteriaInterface $searchCriteria
     * @param array $args
     * @param bool $includeAggregation
     *
     * @return SearchCriteriaInterface
     * @see \Magento\CatalogGraphQl\DataProvider\Product\SearchCriteriaBuilder::build
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterBuild(
        SearchCriteriaBuilder $subject,
        SearchCriteriaInterface $searchCriteria,
        array $args,
        bool $includeAggregation
    ): SearchCriteriaInterface {
        $sortOrders = $searchCriteria->getSortOrders();

        if (empty($sortOrders)) {
            return $searchCriteria;
        }

        $sortOrder = $this->sortOrderBuilder
            ->setField(AdditionalAttribute::ATTRIBUTE_CODE)
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        array_unshift($sortOrders, $sortOrder);

        $searchCriteria->setSortOrders($sortOrders);

        return $searchCriteria;
    }
}
