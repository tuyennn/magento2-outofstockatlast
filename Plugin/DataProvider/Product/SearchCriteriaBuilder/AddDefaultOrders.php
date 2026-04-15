<?php
namespace GhoSter\OutOfStockAtLast\Plugin\DataProvider\Product\SearchCriteriaBuilder;

use GhoSter\OutOfStockAtLast\Model\AdditionalAttribute;

class AddDefaultOrders
{
    /**
     * Add default sorting
     *
     * phpcs:disable Magento2.Annotation.MethodArguments.NoTypeSpecified
     *
     * @param $subject
     * @param array $args
     * @param bool $includeAggregation
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeBuild(
        $subject,
        array $args,
        bool $includeAggregation
    ): array {
        if (!isset($args['sort'])) {
            $args['sort'] = [];
        }

        if (!isset($args['sort'][AdditionalAttribute::ATTRIBUTE_CODE])
        ) {
            $args['sort'][AdditionalAttribute::ATTRIBUTE_CODE] = 'DESC';
        }

        return [$args, $includeAggregation];
    }
}
