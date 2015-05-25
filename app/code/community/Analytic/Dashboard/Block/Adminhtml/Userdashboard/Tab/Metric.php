<?php


class Analytic_Dashboard_Block_Adminhtml_Userdashboard_Tab_Metric extends Analytic_Dashboard_Block_Adminhtml_Userdashboard_Widget_Metric
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('analytic_dashboard/tab/metric.phtml');

        return $this;
    }
}
