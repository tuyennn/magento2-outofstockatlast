<?php
/** @noinspection PhpUnused */
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Plugin\Model\ResourceModel\Fulltext\Collection;

use GhoSter\OutOfStockAtLast\Model\Elasticsearch\Flag;
use Magento\Elasticsearch\Model\ResourceModel\Fulltext\Collection\SearchResultApplier;

/**
 * Class SearchResultApplierPlugin marking apply flag
 */
class SearchResultApplierPlugin
{
    /**
     * @var Flag
     */
    private $flag;

    /**
     * @param Flag $flag
     * @noinspection PhpUnused
     */
    public function __construct(Flag $flag)
    {
        $this->flag = $flag;
    }

    /**
     * Mark start and stop for flag
     *
     * @param SearchResultApplier $subject
     * @param callable $proceed
     * @return void
     * @noinspection PhpUnused
     * @noinspection PhpUnusedParameterInspection
     */
    public function aroundApply(SearchResultApplier $subject, callable $proceed): void
    {
        $this->flag->apply();
        $proceed();
        $this->flag->stop();
    }
}
