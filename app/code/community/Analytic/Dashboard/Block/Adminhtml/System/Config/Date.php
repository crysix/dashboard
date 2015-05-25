<?php


class Analytic_Dashboard_Block_Adminhtml_System_Config_Date extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
        $element->setFormat(Varien_Date::DATE_INTERNAL_FORMAT);

        return parent::render($element);
    }
}
