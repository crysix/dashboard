<?php
/**
 * {analytic_license_notice}
 *
 * @category   Analytic
 * @package    Analytics
 * @copyright  {analytic_copyright}
 * @license    {analytic_license}
 */

class Analytic_Analytics_Model_Resource_CronJob extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    public function _construct()
    {
        $this->_init('analytic/cron_job', 'id');
    }
}
