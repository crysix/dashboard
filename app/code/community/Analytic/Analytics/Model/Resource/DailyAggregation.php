<?php
/**
 * {analytic_license_notice}
 *
 * @category   Analytic
 * @package    Analytics
 * @copyright  {analytic_copyright}
 * @license    {analytic_license}
 */

class Analytic_Analytics_Model_Resource_DailyAggregation extends Analytic_Analytics_Model_Resource_Base
{
    /**
     * Initialize resource model
     *
     */
    public function _construct()
    {
        $this->_init('analytic/dailyAggregation', 'id');
    }

    /**
     * check if daily cron job complete
     *
     * @return bool
     */
    public function checkIfCronComplete()
    {
        $logDate = new Zend_Date(Mage::getModel('analytic/dataAggregation')->getResource()->getStartDbTime(), 'yyyy-MM-dd HH:m:s');
        $minCronTime =  new Zend_Date($this->getDbTime(true), 'yyyy-MM-dd HH:m:s');

        $logDate->setTime('00:00:00');
        $minCronTime->setTime('00:00:00');
        if ($logDate->sub($minCronTime)->toValue() == 0) {
            return true;
        }

        return false;
    }

    /**
     * Truncate daily tables
     */
    public function truncateTables()
    {
        $this->_truncateTable($this->getTable('analytic/category_daily'));
        $this->_truncateTable($this->getTable('analytic/category_filter_daily'));
        $this->_truncateTable($this->getTable('analytic/cron_job_daily'));
        $this->_truncateTable($this->getTable('analytic/customer_daily'));
        $this->_truncateTable($this->getTable('analytic/data_daily'));
        $this->_truncateTable($this->getTable('analytic/page_daily'));
        $this->_truncateTable($this->getTable('analytic/product_daily'));
        $this->_truncateTable($this->getTable('analytic/visitor_daily'));
        $this->_truncateTable($this->getTable('analytic/tag_daily'));
        $this->_truncateTable($this->getTable('analytic/search_daily'));
        $this->_truncateTable($this->getTable('analytic/review_daily'));
        $this->_truncateTable($this->getTable('analytic/shop_daily'));
        $this->_truncateTable($this->getTable('analytic/date_partition_daily'));
    }

    /**
     * @param string $periodStart
     * @param string $timezone
     * @return string
     */
    public function getIfDateAgrregated($periodStart, $timezone = '')
    {
        $select = $this->_getReadAdapter()->select()
            ->from(
            array('cron_job' => $this->getTable("analytic/cron_job_daily")),
            'id'
        )
            ->where('cron_job.date_time = "' . $periodStart . '"');
        if ($timezone) {
            $select->where('cron_job.timezone = ?', $timezone);
        }

        return $select->query()->fetchColumn();
    }

    /**
     * return col records for period from hourly table
     *
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     * @return bool
     */
    public function checkIfDataExsists(Zend_Date $periodStart, Zend_Date $periodEnd)
    {
        $periodEndCloned = clone $periodEnd;

        $recordsFromDb = $this->_getReadAdapter()->select()
            ->from(
                array('cron_job' => $this->getTable("analytic/cron_job")),
                'count(id)'
            )
            ->where('cron_job.date_time >= "' . $periodStart->get('y-MM-dd HH:m:s') . '"')
            ->where('cron_job.date_time < "' . $periodEnd->get('y-MM-dd HH:m:s') . '"')
            ->query()
            ->fetchColumn();

        $dateDiff = $periodEndCloned->sub($periodStart)->toValue();

        if ((int)($dateDiff / 60 / 60) == (int)$recordsFromDb) {

            return true;
        }

        return false;
    }

    /**
     * @param bool $isGoDown
     * @return string
     */
    public function getDbTime($isGoDown = false)
    {
        if ($isGoDown) {
            $select = 'MIN';
        } else {
            $select = 'MAX';
        }
        return $this->_getReadAdapter()->select()
            ->from(
            array('cron_job' => $this->getTable("analytic/cron_job_daily")),
            array($select . '(cron_job.date_time)')
        )
            ->where('cron_job.is_real_cron = true')
            ->query()
            ->fetchColumn();
    }

    /**
     * get start date for daily cron job
     *
     * @param bool $is_real_cron
     * @return string
     */
    public function getStartDbTime($is_real_cron = true)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(
                array('cron_job' => $this->getTable("analytic/cron_job")),
                array('MIN(cron_job.date_time)')
            );
            if ($is_real_cron) {
                $select->where('cron_job.is_real_cron = true');
            }

        return $select->query()->fetchColumn();
    }

    /**
     * get visitors daily data
     *
     * @param $periodStart
     * @param $periodEnd
     * @return array
     */
    public function getVisitorsData($periodStart, $periodEnd)
    {
        return $this->_getReadAdapter()->select()
            ->from(
                array('visitor' => $this->getTable("analytic/visitor")),
                array(
                     'SUM(visitor.visit_count) as visit_count',
                )
            )
            ->join(
                array('data' => $this->getTable("analytic/data")),
                'data.id = visitor.analytic_id',
                array('data.customer_id', 'data.store_id')
            )
            ->where('data.period >= "' . $periodStart->get('y-MM-dd HH:m:s') . '"')
            ->where('data.period < "' . $periodEnd->get('y-MM-dd HH:m:s') . '"')
            ->group(array('data.customer_id', 'data.store_id'))
            ->query()
            ->fetchAll();
    }

    /**
     * save visitors daily data
     *
     * @param array $visitor
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     */
    public function saveVisitorsData($visitor, $periodStart, $periodEnd)
    {
        $this->_getWriteAdapter()->insert(
            $this->getTable('analytic/visitor_daily'),
            array(
                 'analytic_id' => $this->getAnalyticId(
                     $visitor['customer_id'],
                     $visitor['store_id'],
                     $periodStart,
                     $periodEnd
                 ),
                 'visit_count' => $visitor['customer_id'] === null ? $visitor['visit_count'] : 1
            )
        );
    }

    /**
     * get customer daily data
     *
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     * @return array
     */
    public function getCustomerData($periodStart, $periodEnd)
    {
        return $this->_getReadAdapter()->select()
            ->from(
                array('customer' => $this->getTable("analytic/customer")),
                array(
                    'SUM(customer.is_new_customer) as is_new_customer',
                    'SUM(customer.newsletter_subscription) as newsletter_subscription',
                    'SUM(customer.is_returned) as is_returned',
                    'SUM(customer.logins_count) as logins_count'
                )
            )
            ->join(
                array('data' => $this->getTable("analytic/data")),
                'data.id = customer.analytic_id',
                array('data.customer_id', 'data.store_id')
            )
            ->where('data.period >= "' . $periodStart->get('y-MM-dd HH:m:s') . '"')
            ->where('data.period < "' . $periodEnd->get('y-MM-dd HH:m:s') . '"')
            ->group(array('data.customer_id', 'data.store_id'))
            ->query()
            ->fetchAll();
    }

    /**
     * save customer daily data
     *
     * @param array $customer
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     */
    public function saveCustomerData($customer, $periodStart, $periodEnd)
    {
        $this->_getWriteAdapter()->insert(
            $this->getTable('analytic/customer_daily'),
            array(
                'analytic_id' => $this->getAnalyticId(
                    $customer['customer_id'],
                    $customer['store_id'],
                    $periodStart,
                    $periodEnd
                ),
                'is_new_customer' => ((int) $customer['is_new_customer'] > 0) ? true : false,
                'newsletter_subscription' => ((int) $customer['newsletter_subscription'] > 0) ? true : false,
                'is_returned' => ((int) $customer['is_returned'] > 0) ? true : false,
                'logins_count' => $customer['logins_count']
            )
        );
    }

    /**
     * get shop table data for daily migration
     *
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     * @return array
     */
    public function getShopData($periodStart, $periodEnd)
    {
        return $this->_getReadAdapter()->select()
            ->from(
                array('shop' => $this->getTable("analytic/shop")),
                array(
                    'SUM(shop.orders_count) as orders_count',
                    'SUM(shop.products_in_cart_count) as products_in_cart_count',
                    'SUM(shop.checkout_count) as checkout_count',
                    'SUM(shop.wishlist_products_count) as wishlist_products_count',
                    'SUM(shop.create_cart_count) as create_cart_count',
                    'SUM(shop.new_customers_orders_count) as new_customers_orders_count',
                    'SUM(shop.returned_customers_orders_count) as returned_customers_orders_count',
                    'SUM(shop.orders_amount) as orders_amount',
                    'SUM(shop.products_in_cart_amount) as products_in_cart_amount',
                    'SUM(shop.wishlist_products_amount) as wishlist_products_amount',
                    'SUM(shop.create_cart_amount) as create_cart_amount',
                    'SUM(shop.new_customers_orders_amount) as new_customers_orders_amount',
                    'SUM(shop.returned_customers_orders_amount) as returned_customers_orders_amount',
                )
            )
            ->join(
                array('data' => $this->getTable("analytic/data")),
                'data.id = shop.analytic_id',
                array('data.customer_id', 'data.store_id')
            )
            ->where('data.period >= "' . $periodStart->get('y-MM-dd HH:m:s') . '"')
            ->where('data.period < "' . $periodEnd->get('y-MM-dd HH:m:s') . '"')
            ->group(array('data.customer_id', 'data.store_id'))
            ->query()
            ->fetchAll();
    }

    /**
     * save shop daily data
     *
     * @param $shop
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     */
    public function saveShopData($shop, $periodStart, $periodEnd)
    {
        $this->_getWriteAdapter()->insert(
            $this->getTable('analytic/shop_daily'),
            array(
                'analytic_id' => $this->getAnalyticId(
                    $shop['customer_id'],
                    $shop['store_id'],
                    $periodStart,
                    $periodEnd
                ),
                'orders_count' => $shop['orders_count'],
                'products_in_cart_count' => $shop['products_in_cart_count'],
                'checkout_count' => $shop['checkout_count'],
                'wishlist_products_count' => $shop['wishlist_products_count'],
                'create_cart_count' => $shop['create_cart_count'],
                'new_customers_orders_count' => $shop['new_customers_orders_count'],
                'returned_customers_orders_count' => $shop['returned_customers_orders_count'],
                'orders_amount' => $shop['orders_amount'],
                'products_in_cart_amount' => $shop['products_in_cart_amount'],
                'wishlist_products_amount' => $shop['wishlist_products_amount'],
                'create_cart_amount' => $shop['create_cart_amount'],
                'new_customers_orders_amount' => $shop['new_customers_orders_amount'],
                'returned_customers_orders_amount' => $shop['returned_customers_orders_amount'],
            )
        );
    }

    /**
     * get page data for daily aggregation
     *
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     * @return array
     */
    public function getPageData($periodStart, $periodEnd)
    {
        return $this->_getReadAdapter()->select()
            ->from(
            array('shop' => $this->getTable("analytic/page")),
                array(
                    'SUM(shop.view_count) as view_count',
                )
            )
            ->join(
            array('data' => $this->getTable("analytic/data")),
                'data.id = shop.analytic_id',
                array('data.customer_id', 'data.store_id')
            )
            ->where('data.period >= "' . $periodStart->get('y-MM-dd HH:m:s') . '"')
            ->where('data.period < "' . $periodEnd->get('y-MM-dd HH:m:s') . '"')
            ->group(array('data.customer_id', 'data.store_id'))
            ->query()
            ->fetchAll();
    }

    /**
     * save page daily data
     *
     * @param $shop
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     */
    public function savePageData($shop, $periodStart, $periodEnd)
    {
        $this->_getWriteAdapter()->insert(
            $this->getTable('analytic/page_daily'),
            array(
                'analytic_id' => $this->getAnalyticId(
                    $shop['customer_id'],
                    $shop['store_id'],
                    $periodStart,
                    $periodEnd
                ),
                'view_count' => $shop['view_count'],
            )
        );
    }

    /**
     * get product data for daily aggregation
     *
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     * @return array
     */
    public function getProductData($periodStart, $periodEnd)
    {
        return $this->_getReadAdapter()->select()
            ->from(
                array('product' => $this->getTable("analytic/product")),
                array(
                    'product.products_id',
                    'SUM(product.view_count) as view_count',
                )
            )
            ->join(
            array('data' => $this->getTable("analytic/data")),
                'data.id = product.analytic_id',
                array('data.customer_id', 'data.store_id')
            )
            ->where('data.period >= "' . $periodStart->get('y-MM-dd HH:m:s') . '"')
            ->where('data.period < "' . $periodEnd->get('y-MM-dd HH:m:s') . '"')
            ->group(array('data.customer_id', 'data.store_id', 'product.products_id'))
            ->query()
            ->fetchAll();
    }

    /**
     * save product daily data
     *
     * @param $product
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     */
    public function saveProductData($product, $periodStart, $periodEnd)
    {
        $this->_getWriteAdapter()->insert(
            $this->getTable('analytic/product_daily'),
            array(
                'analytic_id' => $this->getAnalyticId(
                    $product['customer_id'],
                    $product['store_id'],
                    $periodStart,
                    $periodEnd
                ),
                'products_id' => $product['products_id'],
                'view_count' => $product['view_count'],
            )
        );
    }

    /**
     * get refers data for daily aggregation
     *
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     * @return array
     */
    public function getReferData($periodStart, $periodEnd)
    {
        return $this->_getReadAdapter()->select()
            ->from(
            array('refer' => $this->getTable("analytic/refer")),
            array(
                    'refer.ref_id',
                    'SUM(refer.ref_count) as ref_count',
                )
            )
            ->join(
            array('data' => $this->getTable("analytic/data")),
                'data.id = refer.analytic_id',
                array('data.customer_id', 'data.store_id')
            )
            ->where('data.period >= "' . $periodStart->get('y-MM-dd HH:m:s') . '"')
            ->where('data.period < "' . $periodEnd->get('y-MM-dd HH:m:s') . '"')
            ->group(array('data.customer_id', 'data.store_id', 'refer.ref_id'))
            ->query()
            ->fetchAll();
    }

    /**
     * save refers daily data
     *
     * @param $refer
     * @param Zend_Date $periodStart
     * @param Zend_Date $periodEnd
     */
    public function saveReferData($refer, $periodStart, $periodEnd)
    {
        $this->_getWriteAdapter()->insert(
            $this->getTable('analytic/refer_daily'),
            array(
                'analytic_id' => $this->getAnalyticId(
                    $refer['customer_id'],
                    $refer['store_id'],
                    $periodStart,
                    $periodEnd
                ),
                'ref_id' => $refer['ref_id'],
                'ref_count' => $refer['ref_count'],
            )
        );
    }

    /**
     * Check for analytic data record and
     * create new one if where is no record for this date, customer and store
     *
     * @param  int $customerId
     * @param  int $storeId
     * @param  Zend_Date $periodStart
     * @param  Zend_Date $periodEnd
     * @return int
     */
    public function getAnalyticId($customerId, $storeId, $periodStart, $periodEnd)
    {
        if (!is_numeric($storeId)) {
            $storeId = Mage::app()
                ->getWebsite()
                ->getDefaultGroup()
                ->getDefaultStoreId();
        }

        $tableName = $this->getTable('analytic/data_daily');
        $recordSearch = $this->_getReadAdapter()->select()
            ->from(
            array('entity' => $tableName),
            array('entity.id')
        )
            ->where('period = "' . $periodStart->get('y-MM-dd HH:m:s') . '"')
            ->where('store_id = ' . $storeId);
        if ($customerId != null) {
            $recordSearch->where('customer_id = ' . $customerId);
        } else {
            $recordSearch->where('customer_id IS NULL');
        }

        $recordId = $recordSearch->query()->fetchColumn();

        if (!(int) $recordId) {
            $this->checkDailyDataPartition($periodStart);
            $this->_getWriteAdapter()->insert(
                $tableName,
                array(
                    'period' => $periodStart->get('y-MM-dd'),
                    'store_id' => $storeId,
                    'customer_id' => $customerId
                )
            );
            $recordId = $this->_getWriteAdapter()->lastInsertId();
        }

        return (int) $recordId;
    }

    /**
     * @param string $dateTime
     * @param boolean $isRealCron
     * @param string $timeZone
     */
    public function insertNewMaxDbTime($dateTime, $isRealCron, $timeZone)
    {
        $this->_getWriteAdapter()->insert($this->getTable("analytic/cron_job_daily"), array(
            'is_real_cron' => $isRealCron,
            'date_time' => $dateTime,
            'timezone' => $timeZone
        ));
    }
}
