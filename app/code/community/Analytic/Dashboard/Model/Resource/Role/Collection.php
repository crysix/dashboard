<?php


class Analytic_Dashboard_Model_Resource_Role_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('analytic_dashboard/role');

    }
}
