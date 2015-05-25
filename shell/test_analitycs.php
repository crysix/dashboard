<?php

require_once 'abstract.php';

class Mage_Shell_Test extends Mage_Shell_Abstract
{
    /**
     * @var Varien_Db_Adapter_Pdo_Mysql
     */
    private $dbConnection;

    public function __construct()
    {
        parent::__construct();
        $this->dbConnection = Mage::getSingleton('core/resource')->getConnection();
    }

    public function run()
    {
        $this->getAnalyticsDataTableInfo();
        $this->getRegistrationsPerDay();
        $this->getRegistrationsPerWeek();
        $this->getRegistrations();
        $this->getLogins();
        $this->getLoginsPerWeek();
        $this->getLoginsPerMonth();
        $this->getMostViewedProducts();
        $this->getMostViewedProductsPerDay();
        $this->getMostViewedProductsPerWeek();
        //$this->getMostViewedProductsPerMonth();
        $this->getLoginsForZipForDay();
        $this->getLoginsForZipForMonth();
        $this->getLoginsForZipForTwoMonth();
        $this->getOrdersPerDayForCA();
        $this->getOrdersPerWeekForCA();
        $this->getOrdersPerMonthForCA();
        $this->getOrdersPerTwoMonthsForCA();
        $this->getOrdersPerFiveMonthsForCA();
        $this->getNumbersAddToCartByDay();
        $this->getNumbersAddToCartByWeek();
        $this->getNumbersAddToCartMonthly();
        $this->getReturnedCustomersPerDay();
        $this->getReturnedCustomersPerWeek();
        $this->getReturnedCustomersPerMonth();
        $this->getNewsletterSubscriptionsPerDay();
        $this->getNewsletterSubscriptionsPerWeek();
        $this->getNewsletterSubscriptionsPerMonth();
        $this->getRefersForADay();
        $this->getRefersForAWeek();
        $this->getRefersForAMonth();
        $this->getPagesForADay();
        $this->getPagesForAWeek();
        $this->getPagesForAMonth();
        $this->getCategoriesForADay();
        $this->getCategoriesForAWeek();
        $this->getCategoriesForAMonth();
        $this->getSearchesForADay();
        $this->getSearchesForAWeek();
        $this->getSearchesForAMonth();
        $this->getCheckoutsByDay();
        $this->getCheckoutsByWeek();
        $this->getCheckoutsMonthly();
        $this->getOrdersByDay();
        $this->getOrdersByWeek();
        $this->getOrdersMonthly();
        $this->getFirstTimeBuyerByDay();
        $this->getFirstTimeBuyerByWeek();
        $this->getFirstTimeBuyerByMonth();
        $this->getCartsByDay();
        $this->getCartsByWeek();
        $this->getCartsMonthly();
        $this->getWishlistsByDay();
        $this->getWishlistsWeek();
        $this->getWishlistsMonthly();
        $this->getOrdersByNewCustomersByDay();
        $this->getOrdersByNewCustomersByWeek();
        $this->getOrdersByNewCustomersByMonth();
        $this->getOrdersByReturnedCustomersByDay();
        $this->getOrdersByReturnedCustomersByWeek();
        $this->getOrdersByReturnedCustomersByMonth();

        //$this->createNewPartition();
    }

    /**
     * run test and show info about it
     *
     * @param string $testInfo
     * @param string $sqlQuery
     * @param string $execMethod
     */
    private function runTest($testInfo, $sqlQuery, $execMethod = 'fetchAll')
    {
        echo 'Test. ' . $testInfo . PHP_EOL;
        $startTime = microtime(true);
        if ($execMethod == 'fetchOne') {
            $resultData = $this->dbConnection->fetchOne($sqlQuery);
        } elseif ($execMethod == 'exec') {
            $resultData = $this->dbConnection->exec($sqlQuery);
        } else {
            $resultData = count($this->dbConnection->fetchAll($sqlQuery));
        }
        echo 'result: ' . $resultData . PHP_EOL;
        $execTime = microtime(true) - $startTime;
        echo 'Generation time: ' . $execTime . ' seconds'. PHP_EOL;
        echo '-----------------' . PHP_EOL;
        $this->dbConnection->closeConnection();
    }

    /**
     * Test 1. Number of records in analytic_data
     */
    private function getAnalyticsDataTableInfo()
    {
        $this->runTest(
            'Test 1. Number of records in analytic_data',
            'SELECT COUNT(*) FROM analytic_data',
            'fetchOne'
        );
    }

    /**
     * Test 2. Get registrations per day
     */
    private function getRegistrationsPerDay()
    {
        $this->runTest(
            'Test 2. Get registrations per day',
            'SELECT  analytic.period, COUNT(*)
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                AND customer.is_registered = true
                AND analytic.period >= "2012-01-21"
                AND analytic.period < "2012-01-22"
                GROUP BY period
                ORDER BY period'
        );
    }

    /**
     * Test 3. Get registrations per week
     */
    private function getRegistrationsPerWeek()
    {
        $this->runTest(
            'Test 3. Get registrations per week',
            'SELECT  analytic.period, COUNT(*)
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                AND customer.is_registered = true
                AND analytic.period >= "2012-01-21"
                AND analytic.period < "2012-01-28"
                GROUP BY period
                ORDER BY period'
        );
    }

    /**
     * Test 4. Get registrations per month
     */
    private function getRegistrations()
    {
        $this->runTest(
            'Test 4. Get registrations per month',
            'SELECT  analytic.period, COUNT(*)
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                AND customer.is_registered = true
                AND analytic.period >= "2012-01-21"
                AND analytic.period < "2012-02-22"
                GROUP BY period
                ORDER BY period'
        );
    }

    /**
     * Test 5. Get logins for a day
     */
    private function getLogins()
    {
        $this->runTest(
            'Test 5. Get logins for a day',
            'SELECT  analytic.period, SUM(customer.logins_count) as logins
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND customer.is_registered = true
                    AND analytic.period >= "2012-01-21"
                    AND analytic.period < "2012-01-22"
                GROUP BY period
                ORDER BY period'
        );
    }

    /**
     * Test 6. Get logins for a week
     */
    private function getLoginsPerWeek()
    {
        $this->runTest(
            'Test 6. Get logins for a week',
            'SELECT  analytic.period, SUM(customer.logins_count) as logins
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND customer.is_registered = true
                    AND analytic.period >= "2012-01-21"
                    AND analytic.period < "2012-01-28"
                GROUP BY period
                ORDER BY period'
        );
    }

    /**
     * Test 7. Get logins for a month
     */
    private function getLoginsPerMonth()
    {
        $this->runTest(
            'Test 7. Get logins for a month',
            'SELECT  analytic.period, SUM(customer.logins_count) as logins
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND customer.is_registered = true
                    AND analytic.period >= "2012-01-21"
                    AND analytic.period < "2012-02-21"
                GROUP BY period
                ORDER BY period'
        );
    }

    /**
     * Test 8. Get most viewed products per hour
     */
    private function getMostViewedProducts()
    {
        $this->runTest(
            'Test 8. Get most viewed products per hour',
            'SELECT  analytic.period, product.products_id, SUM(product.count) AS view_count
                FROM analytic_product product,
                  analytic_data analytic
                WHERE product.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-05-01 01:00"
                GROUP BY products_id,analytic.period
                ORDER BY analytic.period ASC,view_count DESC'
        );
    }

    /**
     * Test 9. Get most viewed products per day
     */
    private function getMostViewedProductsPerDay()
    {
        $this->runTest(
            'Test 9. Get most viewed products per day',
            'SELECT analytic.period, product.products_id, SUM(product.count) AS view_count
                FROM analytic_product product,
                  analytic_data analytic
                WHERE product.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-05-02 00:00"
                GROUP BY products_id,analytic.period
                ORDER BY analytic.period ASC,view_count DESC
                LIMIT 100'
        );
    }

    /**
     * Test 10. Get most viewed products per week
     */
    private function getMostViewedProductsPerWeek()
    {
        $this->runTest(
            'Test 10. Get most viewed products per week',
            'SELECT analytic.period, product.products_id, SUM(product.count) AS view_count
                FROM analytic_product product,
                  analytic_data analytic
                WHERE product.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-05-08 00:00"
                GROUP BY products_id,analytic.period
                ORDER BY analytic.period ASC,view_count DESC
                LIMIT 100'
        );
    }

    /**
     * Test 11. Get most viewed products per month
     */
    private function getMostViewedProductsPerMonth()
    {
        $this->runTest(
            'Test 11. Get most viewed products per month',
            'SELECT analytic.period, product.products_id, SUM(product.count) AS view_count
                FROM analytic_product product,
                  analytic_data analytic
                WHERE product.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-06-01 00:00"
                GROUP BY products_id,analytic.period
                ORDER BY analytic.period ASC,view_count DESC'
        );
    }


    /**
     * Test 12. Get logins for customers from zip test_1 for period 2012-01-21 00:00 to 2012-01-21 23:59
     */
    private function getLoginsForZipForDay()
    {
        $this->runTest(
            'Test 12. Get logins for customers from zip test_1 for period 2012-01-21 00:00 to 2012-01-21 23:59',
            'SELECT  analytic.period, SUM(customer_analytic.logins_count) as logins
                FROM analytic_data analytic,
                      customer_entity customer,
                      analytic_customer customer_analytic,
                      customer_address_entity address_entity,
                      customer_address_entity_text address_entity_text
                WHERE customer_analytic.analytic_id = analytic.id
                AND address_entity_text.value = "test_1"
                AND address_entity_text.attribute_id = 25
                AND address_entity_text.entity_id = address_entity.entity_id
                AND address_entity.parent_id = customer.entity_id
                AND analytic.period >= "2012-01-21 00:00"
                AND analytic.period <= "2012-01-21 23:59"
                AND analytic.customer_id = customer.entity_id
                GROUP BY analytic.period, analytic.customer_id
                ORDER BY analytic.period'
        );
    }

    /**
     * Test 13. Get logins for customers from zip test_1 for period 2012-01-21 00:00 to 2012-02-21 23:59
     */
    private function getLoginsForZipForMonth()
    {
        $this->runTest(
            'Test 13. Get logins for customers from zip test_1 for period 2012-01-21 00:00 to 2012-02-21 23:59',
            'SELECT  analytic.period, SUM(customer_analytic.logins_count) as logins
                FROM analytic_data analytic,
                     customer_entity customer,
                     analytic_customer customer_analytic,
                     customer_address_entity address_entity,
                     customer_address_entity_text address_entity_text
                WHERE customer_analytic.analytic_id = analytic.id
                AND address_entity_text.value = "test_1"
                AND address_entity_text.attribute_id = 25
                AND address_entity_text.entity_id = address_entity.entity_id
                AND address_entity.parent_id = customer.entity_id
                AND analytic.period >= "2012-01-21 00:00"
                AND analytic.period <= "2012-02-21 23:59"
                AND analytic.customer_id = customer.entity_id
                GROUP BY analytic.period, analytic.customer_id
                ORDER BY analytic.period'
        );
    }

    /**
     * Test 14. Get logins for customers from zip test_1 for period 2012-01-21 00:00 to 2012-03-21 23:59
     */
    private function getLoginsForZipForTwoMonth()
    {
        $this->runTest(
            'Test 14. Get logins for customers from zip test_1 for period 2012-01-21 00:00 to 2012-03-21 23:59',
            'SELECT  analytic.period, SUM(customer_analytic.logins_count) as logins
                FROM analytic_data analytic,
                      customer_entity customer,
                      analytic_customer customer_analytic,
                      customer_address_entity address_entity,
                      customer_address_entity_text address_entity_text
                WHERE customer_analytic.analytic_id = analytic.id
                AND address_entity_text.value = "test_1"
                AND address_entity_text.attribute_id = 25
                AND address_entity_text.entity_id = address_entity.entity_id
                AND address_entity.parent_id = customer.entity_id
                AND analytic.period >= "2012-01-21 00:00"
                AND analytic.period <= "2012-03-21 23:59"
                AND analytic.customer_id = customer.entity_id
                GROUP BY analytic.period, analytic.customer_id
                ORDER BY analytic.period'
        );
    }

    /**
     * Test 15. Get orders numbers for the day for the state CA
     */
    private function getOrdersPerDayForCA()
    {
        $this->runTest(
            'Test 15. Get orders numbers for the day for the state CA',
            'SELECT  analytic.period, SUM(analytic_shop.orders_count) as orders
                FROM analytic_data analytic,
                     analytic_shop analytic_shop,
                     customer_entity customer,
                     customer_address_entity address_entity,
                     customer_address_entity_varchar address_entity_text
                WHERE address_entity_text.value = "CA"
                AND address_entity_text.attribute_id = 28
                AND address_entity_text.entity_id = address_entity.entity_id
                AND address_entity.parent_id = customer.entity_id
                AND analytic.customer_id = customer.entity_id
                AND analytic.period >= "2012-01-21 00:00"
                AND analytic.period <= "2012-01-21 23:59"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY analytic.period
                ORDER BY analytic.period'
        );
    }

    /**
     * Test 16. Get orders numbers for the week for the state CA
     */
    private function getOrdersPerWeekForCA()
    {
        $this->runTest(
            'Test 16. Get orders numbers for the week for the state CA',
            'SELECT  analytic.period, SUM(analytic_shop.orders_count) as orders
                FROM analytic_data analytic,
                     analytic_shop analytic_shop,
                     customer_entity customer,
                     customer_address_entity address_entity,
                     customer_address_entity_varchar address_entity_text
                WHERE address_entity_text.value = "CA"
                AND address_entity_text.attribute_id = 28
                AND address_entity_text.entity_id = address_entity.entity_id
                AND address_entity.parent_id = customer.entity_id
                AND analytic.customer_id = customer.entity_id
                AND analytic.period >= "2012-01-21 00:00"
                AND analytic.period <= "2012-01-27 23:59"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY analytic.period
                ORDER BY analytic.period'
        );
    }

    /**
     * Test 17. Get orders numbers for the month for the state CA
     */
    private function getOrdersPerMonthForCA()
    {
        $this->runTest(
            'Test 17. Get orders numbers for the month for the state CA',
            'SELECT  analytic.period, SUM(analytic_shop.orders_count) as orders
                FROM analytic_data analytic,
                     analytic_shop analytic_shop,
                     customer_entity customer,
                     customer_address_entity address_entity,
                     customer_address_entity_varchar address_entity_text
                WHERE address_entity_text.value = "CA"
                AND address_entity_text.attribute_id = 28
                AND address_entity_text.entity_id = address_entity.entity_id
                AND address_entity.parent_id = customer.entity_id
                AND analytic.customer_id = customer.entity_id
                AND analytic.period >= "2012-01-21 00:00"
                AND analytic.period <= "2012-02-21 23:59"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY analytic.period
                ORDER BY analytic.period'
        );
    }

    /**
     * Test 18. Get orders numbers for the two months for the state CA
     */
    private function getOrdersPerTwoMonthsForCA()
    {
        $this->runTest(
            'Test 18. Get orders numbers for the two months for the state CA',
            'SELECT  analytic.period, SUM(analytic_shop.orders_count) as orders
                FROM analytic_data analytic,
                     analytic_shop analytic_shop,
                     customer_entity customer,
                     customer_address_entity address_entity,
                     customer_address_entity_varchar address_entity_text
                WHERE address_entity_text.value = "CA"
                AND address_entity_text.attribute_id = 28
                AND address_entity_text.entity_id = address_entity.entity_id
                AND address_entity.parent_id = customer.entity_id
                AND analytic.customer_id = customer.entity_id
                AND analytic.period >= "2012-01-21 00:00"
                AND analytic.period <= "2012-03-21 23:59"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY analytic.period
                ORDER BY analytic.period'
        );
    }

    /**
     * Test 19. Get orders numbers for the five month for the state CA
     */
    private function getOrdersPerFiveMonthsForCA()
    {
        $this->runTest(
            'Test 19. Get orders numbers for the five month for the state CA',
            'SELECT  analytic.period, SUM(analytic_shop.orders_count) as orders
                FROM analytic_data analytic,
                     analytic_shop analytic_shop,
                     customer_entity customer,
                     customer_address_entity address_entity,
                     customer_address_entity_varchar address_entity_text
                WHERE address_entity_text.value = "CA"
                AND address_entity_text.attribute_id = 28
                AND address_entity_text.entity_id = address_entity.entity_id
                AND address_entity.parent_id = customer.entity_id
                AND analytic.customer_id = customer.entity_id
                AND analytic.period >= "2012-01-21 00:00"
                AND analytic.period <= "2012-05-21 23:59"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY analytic.period
                ORDER BY analytic.period'
        );
    }


    /**
     * create new partition
     */
    private function createNewPartition()
    {
        $this->runTest(
            'create new partition',
            'ALTER TABLE analytic_data
                ADD PARTITION (
                    PARTITION p_2013_05_01 VALUES LESS THAN(TO_DAYS("2013-05-01 00:00"))
                )',
            'exec'
        );
    }

    /**
     * Test 20. Get numbers of add to cart by day
     */
    private function getNumbersAddToCartByDay()
    {
        $this->runTest(
            'Test 20. Get numbers of add to cart by day',
            'SELECT  period, SUM(analytic_shop.products_in_cart_count) as products_in_cart_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period <= "2012-10-22 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 21. Get numbers of add to cart by week
     */
    private function getNumbersAddToCartByWeek()
    {
        $this->runTest(
            'Test 21. Get numbers of add to cart by week',
            'SELECT  period, SUM(analytic_shop.products_in_cart_count) as products_in_cart_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period <= "2012-10-28 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 22. Get numbers of add to cart by month
     */
    private function getNumbersAddToCartMonthly()
    {
        $this->runTest(
            'Test 22. Get numbers of add to cart by month',
            'SELECT  period, SUM(analytic_shop.products_in_cart_count) as products_in_cart_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period <= "2012-11-21 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 23. Get returned persons list for day
     */
    private function getReturnedCustomersPerDay()
    {
        $this->runTest(
            'Test 23. Get returned persons list for day',
            'SELECT  analytic.period, COUNT(*) as returned_count
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND period >= "2012-10-21 00:00"
                    AND period <= "2012-10-22 00:00"
                    AND customer.is_returned = true
                GROUP BY period
                LIMIT 100'
        );
    }

    /**
     * Test 24. Get returned persons list for week
     */
    private function getReturnedCustomersPerWeek()
    {
        $this->runTest(
            'Test 22. Get returned persons list for week',
            'SELECT  analytic.period, COUNT(*) as returned_count
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND period >= "2012-10-21 00:00"
                    AND period <= "2012-10-28 23:59"
                    AND customer.is_returned = true
                GROUP BY period
                LIMIT 100'
        );
    }

    /**
     * Test 24. Get returned persons list for month
     */
    private function getReturnedCustomersPerMonth()
    {
        $this->runTest(
            'Test 24. Get returned persons list for month',
            'SELECT  analytic.period, COUNT(*) as returned_count
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND period >= "2012-10-01 00:00"
                    AND period <= "2012-11-01 00:00"
                    AND customer.is_returned = true
                GROUP BY period
                LIMIT 100'
        );
    }

    /**
     * Test 25. Get count of newsletter subscriptions per day
     */
    private function getNewsletterSubscriptionsPerDay()
    {
        $this->runTest(
            'Test 25. Get count of newsletter subscriptions per day',
            'SELECT  analytic.period, COUNT(*) as newsletter_subscriptions
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND analytic.period >= "2012-01-01 00:00"
                    AND analytic.period < "2012-01-02 00:00"
                    AND customer.newsletter_subscription = true
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 26. Get count of newsletter subscriptions per week
     */
    private function getNewsletterSubscriptionsPerWeek()
    {
        $this->runTest(
            'Test 26. Get count of newsletter subscriptions per week',
            'SELECT  analytic.period, COUNT(*) as newsletter_subscriptions
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND analytic.period >= "2012-01-01 00:00"
                    AND analytic.period < "2012-01-08 00:00"
                    AND customer.newsletter_subscription = true
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 27. Get count of newsletter subscriptions per month
     */
    private function getNewsletterSubscriptionsPerMonth()
    {
        $this->runTest(
            'Test 27. Get count of newsletter subscriptions per month',
            'SELECT  analytic.period, COUNT(*) as newsletter_subscriptions
                FROM analytic_data analytic,
                  analytic_customer customer
                WHERE customer.analytic_id = analytic.id
                    AND analytic.period >= "2012-01-01 00:00"
                    AND analytic.period < "2012-02-01 00:00"
                    AND customer.newsletter_subscription = true
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 28. Get refers for a day
     */
    private function getRefersForADay()
    {
        $this->runTest(
            'Test 28. Get refers for a day',
            'SELECT  analytic.period, ref_domain.domain_name, SUM(analytic_refers.ref_count) as refer_count
                FROM analytic_data analytic,
                  analytic_ref_domain ref_domain,
                  analytic_refer analytic_refers
                WHERE analytic_refers.analytic_id = analytic.id
                    AND ref_domain.id = analytic_refers.ref_id
                    AND analytic.period >= "2012-10-21 00:00"
                    AND analytic.period <= "2012-10-22 00:00"
                GROUP BY ref_domain.id
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 29. Get refers for a week
     */
    private function getRefersForAWeek()
    {
        $this->runTest(
            'Test 29. Get refers for a week',
            'SELECT  analytic.period, ref_domain.domain_name, SUM(analytic_refers.ref_count) as refer_count
                FROM analytic_data analytic,
                  analytic_ref_domain ref_domain,
                  analytic_refer analytic_refers
                WHERE analytic_refers.analytic_id = analytic.id
                    AND ref_domain.id = analytic_refers.ref_id
                    AND analytic.period >= "2012-10-21 00:00"
                    AND analytic.period <= "2012-10-28 00:00"
                GROUP BY ref_domain.id
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 30. Get refers for a month
     */
    private function getRefersForAMonth()
    {
        $this->runTest(
            'Test 30. Get refers for a month',
            'SELECT  analytic.period, ref_domain.domain_name, SUM(analytic_refers.ref_count) as refer_count
                FROM analytic_data analytic,
                  analytic_ref_domain ref_domain,
                  analytic_refer analytic_refers
                WHERE analytic_refers.analytic_id = analytic.id
                    AND ref_domain.id = analytic_refers.ref_id
                    AND analytic.period >= "2012-10-01 00:00"
                    AND analytic.period <= "2012-11-01 00:00"
                GROUP BY ref_domain.id
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 31. Get page viewes for a day
     */
    private function getPagesForADay()
    {
        $this->runTest(
            'Test 31. Get page viewes for a day',
            'SELECT  analytic.period, SUM(analytic_pages.view_count) as view_count
                FROM analytic_data analytic,
                  analytic_page analytic_pages
                WHERE analytic.id = analytic_pages.analytic_id
                    AND analytic.period >= "2012-10-21 00:00"
                    AND analytic.period < "2012-10-22 00:00"
                GROUP BY analytic.period
                LIMIT 100'
        );
    }

    /**
     * Test 32. Get page viewes for a week
     */
    private function getPagesForAWeek()
    {
        $this->runTest(
            'Test 32. Get page viewes for a week',
            'SELECT  analytic.period, SUM(analytic_pages.view_count) as view_count
                FROM analytic_data analytic,
                  analytic_page analytic_pages
                WHERE analytic.id = analytic_pages.analytic_id
                    AND analytic.period >= "2012-10-21 00:00"
                    AND analytic.period < "2012-10-28 00:00"
                GROUP BY analytic.period
                LIMIT 100'
        );
    }

    /**
     * Test 33. Get page viewes for a month
     */
    private function getPagesForAMonth()
    {
        $this->runTest(
            'Test 33. Get page viewes for a month',
            'SELECT  analytic.period, SUM(analytic_pages.view_count) as view_count
                FROM analytic_data analytic,
                  analytic_page analytic_pages
                WHERE analytic.id = analytic_pages.analytic_id
                    AND analytic.period >= "2012-10-21 00:00"
                    AND analytic.period < "2012-11-21 00:00"
                GROUP BY analytic.period
                LIMIT 100'
        );
    }

    /**
     * Test 34. Get categories for a day
     */
    private function getCategoriesForADay()
    {
        $this->runTest(
            'Test 34. Get categories for a day',
            'SELECT  analytic.period, category.category_id, SUM(category.count) AS view_count
                FROM analytic_category category,
                  analytic_data analytic
                WHERE category.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-05-02 00:00"
                GROUP BY category.category_id, analytic.period
                ORDER BY analytic.period ASC,view_count DESC
                LIMIT 100'
        );
    }

    /**
     * Test 35. Get categories for a week
     */
    private function getCategoriesForAWeek()
    {
        $this->runTest(
            'Test 35. Get categories for a week',
            'SELECT  analytic.period, category.category_id, SUM(category.count) AS view_count
                FROM analytic_category category,
                  analytic_data analytic
                WHERE category.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-05-08 00:00"
                GROUP BY category.category_id, analytic.period
                ORDER BY analytic.period ASC,view_count DESC
                LIMIT 100'
        );
    }

    /**
     * Test 36. Get categories for a month
     */
    private function getCategoriesForAMonth()
    {
        $this->runTest(
            'Test 36. Get categories for a month',
            'SELECT  analytic.period, category.category_id, SUM(category.count) AS view_count
                FROM analytic_category category,
                  analytic_data analytic
                WHERE category.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-06-01 00:00"
                GROUP BY category.category_id, analytic.period
                ORDER BY analytic.period ASC,view_count DESC
                LIMIT 100'
        );
    }

    /**
     * Test 37. Get searches for a day
     */
    private function getSearchesForADay()
    {
        $this->runTest(
            'Test 37. Get searches for a day',
            'SELECT analytic.period, search.search_string, SUM(search.count) AS search_count
                FROM analytic_search search,
                  analytic_data analytic
                WHERE search.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-05-02 00:00"
                GROUP BY search.search_string, analytic.period
                ORDER BY analytic.period ASC, search_count DESC
                LIMIT 100'
        );
    }

    /**
     * Test 38. Get searches for a week
     */
    private function getSearchesForAWeek()
    {
        $this->runTest(
            'Test 38. Get searches for a week',
            'SELECT search.search_string, SUM(search.count) AS search_count
                FROM analytic_search search,
                  analytic_data analytic
                WHERE search.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-05-07 00:00"
                GROUP BY analytic.period
                ORDER BY search_count DESC
                LIMIT 100'
        );
    }

    /**
     * Test 39. Get searches for a month
     */
    private function getSearchesForAMonth()
    {
        $this->runTest(
            'Test 39. Get searches for a month',
            'SELECT search.search_string, SUM(search.count) AS search_count
                FROM analytic_search search,
                  analytic_data analytic
                WHERE search.analytic_id = analytic.id
                    AND analytic.period >= "2012-05-01 00:00"
                    AND analytic.period < "2012-06-01 00:00"
                GROUP BY search.search_string
                ORDER BY search_count DESC
                LIMIT 100'
        );
    }

    /**
     * Test 40. Get checkouts by day
     */
    private function getCheckoutsByDay()
    {
        $this->runTest(
            'Test 20. Get numbers of add to cart by day',
            'SELECT  period, SUM(analytic_shop.checkout_count) as checkouts
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-10-22 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 41. Get checkouts by week
     */
    private function getCheckoutsByWeek()
    {
        $this->runTest(
            'Test 41. Get checkouts by week',
            'SELECT  period, SUM(analytic_shop.checkout_count) as checkouts
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-10-28 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 42. Get checkouts by month
     */
    private function getCheckoutsMonthly()
    {
        $this->runTest(
            'Test 42. Get checkouts by month',
            'SELECT  period, SUM(analytic_shop.checkout_count) as checkouts
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-11-22 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 43. Get orders by day
     */
    private function getOrdersByDay()
    {
        $this->runTest(
            'Test 43. Get orders by day',
            'SELECT  period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-10-22 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 44. Get orders by week
     */
    private function getOrdersByWeek()
    {
        $this->runTest(
            'Test 44. Get orders by week',
            'SELECT  period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-10-28 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 45. Get orders by month
     */
    private function getOrdersMonthly()
    {
        $this->runTest(
            'Test 45. Get orders by month',
            'SELECT  period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-11-21 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 46. Get first tyme buyers by day
     */
    private function getFirstTimeBuyerByDay()
    {
        $this->runTest(
            'Test 46. Get first tyme buyers by day',
            'SELECT  analytic_data.period, analytic_data.customer_id
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-21 00:00"
                    AND analytic_data.period <= "2012-10-22 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_registered = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }

    /**
     * Test 47. Get first tyme buyers by week
     */
    private function getFirstTimeBuyerByWeek()
    {
        $this->runTest(
            'Test 47. Get first tyme buyers by week',
            'SELECT  analytic_data.period, analytic_data.customer_id
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-21 00:00"
                    AND analytic_data.period <= "2012-10-28 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_registered = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }

    /**
     * Test 48. Get first tyme buyers by month
     */
    private function getFirstTimeBuyerByMonth()
    {
        $this->runTest(
            'Test 48. Get first tyme buyers by month',
            'SELECT  analytic_data.period, analytic_data.customer_id
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-01 00:00"
                    AND analytic_data.period <= "2012-11-01 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_registered = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }

    /**
     * Test 49. Get carts by day
     */
    private function getCartsByDay()
    {
        $this->runTest(
            'Test 49. Get carts by day',
            'SELECT  period, SUM(analytic_shop.create_cart_count) as create_carts_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-10-22 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 50. Get carts by week
     */
    private function getCartsByWeek()
    {
        $this->runTest(
            'Test 50. Get carts by week',
            'SELECT  period, SUM(analytic_shop.create_cart_count) as create_carts_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-10-28 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 51. Get carts by month
     */
    private function getCartsMonthly()
    {
        $this->runTest(
            'Test 51. Get carts by month',
            'SELECT  period, SUM(analytic_shop.create_cart_count) as create_carts_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-11-21 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 52. Get wishlist products count by day
     */
    private function getWishlistsByDay()
    {
        $this->runTest(
            'Test 52. Get wishlist products count by day',
            'SELECT  period, SUM(analytic_shop.wishlist_products_count) as wishlist_products_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-10-22 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 53. Get wishlist products count by week
     */
    private function getWishlistsWeek()
    {
        $this->runTest(
            'Test 53. Get wishlist products count by week',
            'SELECT  period, SUM(analytic_shop.wishlist_products_count) as wishlist_products_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-10-28 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 54. Get wishlist products count by month
     */
    private function getWishlistsMonthly()
    {
        $this->runTest(
            'Test 54. Get wishlist products count by month',
            'SELECT  period, SUM(analytic_shop.wishlist_products_count) as wishlist_products_count
                FROM analytic_data analytic,
                  analytic_shop analytic_shop
                WHERE period >= "2012-10-21 00:00"
                AND period < "2012-11-21 00:00"
                AND analytic_shop.analytic_id = analytic.id
                GROUP BY period
                ORDER BY period
                LIMIT 100'
        );
    }

    /**
     * Test 55. get orders by new customers by day
     */
    private function getOrdersByNewCustomersByDay()
    {
        $this->runTest(
            'Test 55. Get orders by new customers by day',
            'SELECT  analytic_data.period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-21 00:00"
                    AND analytic_data.period <= "2012-10-22 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_registered = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }

    /**
     * Test 56. Get orders by new customers by week
     */
    private function getOrdersByNewCustomersByWeek()
    {
        $this->runTest(
            'Test 56. Get orders by new customers by week',
            'SELECT  analytic_data.period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-21 00:00"
                    AND analytic_data.period <= "2012-10-28 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_registered = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }

    /**
     * Test 57. Get orders by new customers by month
     */
    private function getOrdersByNewCustomersByMonth()
    {
        $this->runTest(
            'Test 57. Get orders by new customers by month',
            'SELECT  analytic_data.period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-01 00:00"
                    AND analytic_data.period <= "2012-11-01 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_registered = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }

    /**
     * Test 55. get orders by returned customers by day
     */
    private function getOrdersByReturnedCustomersByDay()
    {
        $this->runTest(
            'Test 55. Get orders by returned customers by day',
            'SELECT  analytic_data.period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-21 00:00"
                    AND analytic_data.period <= "2012-10-22 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_returned = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }

    /**
     * Test 56. Get orders by returned customers by week
     */
    private function getOrdersByReturnedCustomersByWeek()
    {
        $this->runTest(
            'Test 56. Get orders by returned customers by week',
            'SELECT  analytic_data.period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-21 00:00"
                    AND analytic_data.period <= "2012-10-28 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_returned = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }

    /**
     * Test 57. Get orders by returned customers by month
     */
    private function getOrdersByReturnedCustomersByMonth()
    {
        $this->runTest(
            'Test 57. Get orders by returned customers by month',
            'SELECT  analytic_data.period, SUM(analytic_shop.orders_count) as orders_count
                FROM analytic_data analytic_data,
                    analytic_customer analytic_customer,
                    analytic_shop analytic_shop
                WHERE analytic_data.period >= "2012-10-01 00:00"
                    AND analytic_data.period <= "2012-11-01 00:00"
                    AND analytic_shop.analytic_id = analytic_data.id
                    AND analytic_shop.orders_count > 0
                    AND analytic_data.id = analytic_customer.analytic_id
                    AND analytic_customer.is_returned = true
                ORDER BY analytic_data. period
                LIMIT 100'
        );
    }
}

$shell = new Mage_Shell_Test();
$shell->run();
