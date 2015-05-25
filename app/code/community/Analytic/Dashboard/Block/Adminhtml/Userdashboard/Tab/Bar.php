<?php


class Analytic_Dashboard_Block_Adminhtml_Userdashboard_Tab_Bar extends Analytic_Dashboard_Block_Adminhtml_Userdashboard_Widget_Bar
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('analytic_dashboard/tab/bar.phtml');

        return $this;
    }
}
