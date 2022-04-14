<?php

namespace GhoSter\OutOfStockAtLast\Plugin\Model\Adapter;

/**
 * phpcs:ignore Magento2.Legacy.Copyright.FoundCopyrightMissingOrWrongFormat
 * Class AdditionalFieldMapperPlugin for es attributes mapping
 */
class AdditionalFieldMapperPlugin
{
    /**
     * @var string[]
     */
    protected $allowedFields = [
        'out_of_stock_at_last' => 'integer'
    ];

    /**
     * Missing mapped attribute code
     *
     * @param mixed $subject
     * @param array $result
     * @return array
     */
    public function afterGetAllAttributesTypes($subject, array $result): array
    {
        foreach ($this->allowedFields as $fieldName => $fieldType) {
            $result[$fieldName] = ['type' => $fieldType];
        }

        return $result;
    }

    /**
     * 3rd module Compatibility
     *
     * @param mixed $subject
     * @param array $result
     * @return array
     */
    public function afterBuildEntityFields($subject, array $result): array
    {
        return $this->afterGetAllAttributesTypes($subject, $result);
    }
}
