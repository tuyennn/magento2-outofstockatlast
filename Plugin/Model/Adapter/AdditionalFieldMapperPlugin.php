<?php /** @noinspection PhpUnused */

namespace GhoSter\OutOfStockAtLast\Plugin\Model\Adapter;

/**
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
     * @noinspection PhpUnusedParameterInspection
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
     * @noinspection PhpUnused
     */
    public function afterBuildEntityFields($subject, array $result): array
    {
        return $this->afterGetAllAttributesTypes($subject, $result);
    }
}
