<?php


class Analytic_Dashboard_Block_Adminhtml_Dashboard extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_dashboard';
        $this->_blockGroup = 'analytic_dashboard';
        parent::__construct();

        $this->_headerText = Mage::helper('analytic_dashboard')->__('Manage Dashboards');
        $this->_updateButton('add', 'label', Mage::helper('analytic_dashboard')->__('Add New Dashboard'));

        if (!Mage::helper('analytic_dashboard')->isSectionAllowed('dashboards_create')) {
            $this->_removeButton('add');
        }
    }
}
