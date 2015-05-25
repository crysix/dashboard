<?php
/**
 * {analytic_license_notice}
 *
 * @category   Analytic
 * @package    Analytics
 * @copyright  {analytic_copyright}
 * @license    {analytic_license}
 */

class Analytic_Analytics_Model_Observer
{
    /**
     * Handler for system config save after event
     *
     * @param Varien_Event_Observer $observer
     * @return boolean
     */
    public function actionConfigSaveAfter($observer)
    {
        $savedTimezone = Mage::getStoreConfig('general/locale/timezone');
        $lastTimezone = Mage::getStoreConfig(Analytic_Analytics_Helper_Data::XML_CURRENT_STORE_TIMEZONE);
        if ($savedTimezone !== $lastTimezone) {
            Mage::getResourceModel('analytic/dailyAggregation')->truncateTables();
            Mage::getModel('core/config')->saveConfig(Analytic_Analytics_Helper_Data::XML_CURRENT_STORE_TIMEZONE,
                $savedTimezone);
        }
    }
}
