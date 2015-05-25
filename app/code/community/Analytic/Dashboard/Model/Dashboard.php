<?php


class Analytic_Dashboard_Model_Dashboard extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('analytic_dashboard/dashboard');
    }

    /**
     * Check if user is allowed to edit dashboard
     *
     * @param  int     $userId
     * @return boolean
     */
    public function canEdit($userId)
    {
        if (Mage::helper('analytic_dashboard')->canManageDashboards()) { //administrator can view/edit all dashboards
            return true;
        }

        return $this->getResource()->canEdit($this->getId(), $userId);
    }

    /**
     * Check if user is allowed to view dashboard
     *
     * @param  int     $userId
     * @return boolean
     */
    public function canView($userId)
    {
        if (Mage::helper('analytic_dashboard')->canManageDashboards()){ //administrator can view/edit all dashboards
            return true;
        }

        return $this->getResource()->canView($this->getId(), $userId);
    }

    /**
     * Processing object before delete data, removing widgets associated with dashboard
     *
     * @return Analytic_Dashboard_Model_Dashboard
     */
    protected function _beforeDelete()
    {
        Mage::getResourceModel("analytic_dashboard/widget")->removeDashboardWidgets($this->getId());
        parent::_beforeDelete();
    }
}
