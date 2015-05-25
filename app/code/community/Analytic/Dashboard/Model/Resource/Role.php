<?php


class Analytic_Dashboard_Model_Resource_Role extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('analytic_dashboard/permissions_role', 'id');
    }

    /**
     * Remove roles for dashboard
     *
     * @param  int  $dashboardId
     * @return null
     */
    public function remove($dashboardId)
    {
        $condition = array(
            'dashboard_id = ?' => (int) $dashboardId,
        );

        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
    }
}
