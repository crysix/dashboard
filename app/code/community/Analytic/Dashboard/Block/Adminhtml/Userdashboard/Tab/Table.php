<?php


class Analytic_Dashboard_Block_Adminhtml_Userdashboard_Tab_Table extends Analytic_Dashboard_Block_Adminhtml_Userdashboard_Widget_Table
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('analytic_dashboard/tab/table.phtml');

        return $this;
    }
}
