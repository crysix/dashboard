<?php
/**
 * {analytic_license_notice}
 *
 * @category   Analytic
 * @package    Analytics
 * @copyright  {analytic_copyright}
 * @license    {analytic_license}
 */

class Analytic_Analytics_Model_CronJob extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource model
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('analytic/cronJob');
    }
}
