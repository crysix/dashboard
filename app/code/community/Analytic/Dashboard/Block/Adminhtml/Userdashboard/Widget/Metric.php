<?php


class Analytic_Dashboard_Block_Adminhtml_Userdashboard_Widget_Metric extends Analytic_Dashboard_Block_Adminhtml_Userdashboard_Widget_Abstract
{
    protected function _prepareLayout()
    {
        $this->setTemplate('analytic_dashboard/widget/simple.phtml');

        return parent::_prepareLayout();
    }

    /**
     * Get widget data
     *
     * @return string
     */
    public function getWidgetData()
    {
        /** @var $helper Analytic_Dashboard_Helper_Data */
        $helper = Mage::helper('analytic_dashboard');
        $metricClass = $helper->getMetricsClass($this->getMetric());
        if ($metricClass) {
            $metric = new $metricClass;
            if ($helper->isMoneyMetric($this->getMetric())){
                return $helper->toMoney($metric->getData());
            }

            return $helper->toNumber($metric->getData());
        }

        return 0;
    }

    /**
     * Get avg widget data
     *
     * @return string
     */
    public function getAvgWidgetData()
    {
        /** @var $helper Analytic_Dashboard_Helper_Data */
        $helper = Mage::helper('analytic_dashboard');
        $metricClass = $helper->getMetricsClass($this->getMetric());
        if ($metricClass) {
            $metric = new $metricClass;
            if ($helper->isMoneyMetric($this->getMetric())){
                return $helper->toMoney($metric->getAvgData());
            }

            return $helper->toNumber($metric->getAvgData());
        }

        return 0;
    }

    /**
     * Check if we count avg data per day
     *
     * @return bool
     */
    public function getAvgIsPerDay()
    {
        /** @var $helper Analytic_Dashboard_Helper_Data */
        $helper = Mage::helper('analytic_dashboard');
        $dateObjectStart = new Zend_Date($helper->getDateFrom(true));
        $dateObjectEnd = new Zend_Date($helper->getDateTo(true));
        $dateObjectEnd->addHour(1);
        if ((int) $dateObjectEnd->sub($dateObjectStart)->toValue() / 60 / 60 / 24 > 1) {
            return true;
        }

        return false;
    }
}
