<?php


class Analytic_Dashboard_Block_Adminhtml_Userdashboard_Tab_Pie extends Analytic_Dashboard_Block_Adminhtml_Userdashboard_Widget_Pie
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('analytic_dashboard/tab/pie.phtml');

        return $this;
    }
}
