<?php


class Analytic_Dashboard_Block_Adminhtml_Userdashboard_Tab_Timeline extends Analytic_Dashboard_Block_Adminhtml_Userdashboard_Widget_Timeline
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('analytic_dashboard/tab/timeline.phtml');

        return $this;
    }
}
