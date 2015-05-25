<?php


class Analytic_Dashboard_Block_Adminhtml_Dashboard_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('dashboard_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('analytic_dashboard')->__('Dashboard Details'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('analytic_dashboard')->__('General'),
            'title' => Mage::helper('analytic_dashboard')->__('General'),
            'content' => $this->getLayout()->createBlock('analytic_dashboard/adminhtml_dashboard_edit_tab_form')->toHtml(),
        ));

        $this->addTab('roles_section', array(
            'label' => Mage::helper('analytic_dashboard')->__('Roles'),
            'title' => Mage::helper('analytic_dashboard')->__('Roles'),
            'content' => $this->getLayout()->createBlock('analytic_dashboard/adminhtml_dashboard_edit_tab_roles')->toHtml(),
        ));

        $this->addTab('users_section', array(
            'label' => Mage::helper('analytic_dashboard')->__('Users'),
            'title' => Mage::helper('analytic_dashboard')->__('Users'),
            'content' => $this->getLayout()->createBlock('analytic_dashboard/adminhtml_dashboard_edit_tab_users')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
