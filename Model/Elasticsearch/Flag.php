<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Model\Elasticsearch;

/**
 * Class Flag for determine and ignore Using Price indexing
 */
class Flag
{
    /**
     * @var bool
     */
    private $isApplied = false;

    /**
     * @return void
     */
    public function apply()
    {
        $this->isApplied = true;
    }

    /**
     * @return void
     */
    public function stop()
    {
        $this->isApplied = false;
    }

    /**
     * @return bool
     */
    public function isApplied(): bool
    {
        return $this->isApplied;
    }
}
