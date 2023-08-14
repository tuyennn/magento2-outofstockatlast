<?php
declare(strict_types=1);

namespace GhoSter\OutOfStockAtLast\Model\Indexer\Stock;

use Magento\ConfigurableProduct\Model\ResourceModel\Indexer\Stock\Configurable as DefaultIndexer;
use Magento\Framework\DB\Select;

class Configurable extends DefaultIndexer
{
    /**
     * @inheritdoc
     */
    protected function _getStockStatusSelect($entityIds = null, $usePrimaryTable = false)
    {
        $select = parent::_getStockStatusSelect($entityIds, $usePrimaryTable);
        $this->_autoCalculate($select);

        return $select;
    }

    /**
     * Calculate depends on simple products
     *
     * @param Select $select
     * @throws \Zend_Db_Select_Exception
     */
    private function _autoCalculate($select)
    {
        $columns = $select->getPart(Select::COLUMNS);
        foreach ($columns as &$column) {
            if (isset($column[2]) && $column[2] == 'qty') {
                $column[1] = new \Zend_Db_Expr('SUM(IF(i.stock_status > 0, i.qty, 0))');
            }
        }
        $select->setPart(Select::COLUMNS, $columns);
    }
}
