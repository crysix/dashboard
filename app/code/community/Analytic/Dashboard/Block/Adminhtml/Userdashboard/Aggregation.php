<?php


class Analytic_Dashboard_Block_Adminhtml_Userdashboard_Aggregation extends Mage_Adminhtml_Block_Template
{
    protected function _prepareLayout()
    {
        $this->setTemplate('analytic_dashboard/aggregation/process.phtml');

        return parent::_prepareLayout();
    }

    /**
     * Get aggregation process url
     *
     * @return string
     */
    public function getAggregationProcessUrl()
    {
        return $this->getUrl('*/*/dailyAggregationProcess');
    }

    /**
     * Get aggregation status url
     *
     * @return string
     */
    public function getAggregationStatusUrl()
    {
        return $this->getUrl('*/*/dailyAggregationStatus');
    }

    /**
     * Get days count for daily aggregation
     *
     * @return string
     */
    public function getDaysCount(){
        $currentDate = new Zend_Date();
        $currentDate->subDay(1);
        $currentDate->setTime("00:00:00");
        $startDate = Mage::helper('analytic_dashboard')->getStartDbTime();

        return ceil($currentDate->sub($startDate)->toValue() / 60 / 60 / 24);
    }
}
