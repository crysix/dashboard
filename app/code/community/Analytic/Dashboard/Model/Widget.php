<?php


class Analytic_Dashboard_Model_Widget extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('analytic_dashboard/widget');
    }

    /**
     * Assign widget to dashboard
     * @param  int                        $dashboardId
     * @return Analytic_Dashboard_Model_Widget
     */
    public function assignToDashboard($dashboardId)
    {
        $this->getResource()->assignToDashboard($this->getId(), $dashboardId);

        return $this;
    }

    /**
     * Get Widget config array
     *
     * @return array
     */
    public function getRawWidgetConfig()
    {
        $widgetConfig = new Varien_Object();
        $widgetConfig->setData(Mage::helper('core')->jsonDecode($this->getWidgetConfig()));

        return $widgetConfig;
    }
}
