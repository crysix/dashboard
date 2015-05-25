<?php
/**
 * {analytic_license_notice}
 *
 * @category   Analytic
 * @package    Analytics
 * @copyright  {analytic_copyright}
 * @license    {analytic_license}
 */

/* @var $installer Analytic_Analytics_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
/* @var $table Varien_Db_Ddl_Table */

foreach (array('', '_daily') as $postfix) {
    /**
     * Create table 'analytic/data'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/data' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                           )
        )
        ->addColumn('period', Varien_Db_Ddl_Table::TYPE_DATETIME)
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
            $installer->getIdxName(
                'analytic/data' . $postfix, array('id', 'period'), Varien_Db_Adapter_Interface::INDEX_TYPE_PRIMARY
            ),
            array('id', 'period'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_PRIMARY)
        )
        ->addIndex(
            $installer->getIdxName('analytic/data' . $postfix, array('id')),
            array('id')
        )
        ->addIndex(
            $installer->getIdxName('analytic/data' . $postfix, array('customer_id', 'period')),
            array('customer_id', 'period')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/category'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/category' . $postfix))
        ->addColumn(
        'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                            'identity' => true,
                                                            'unsigned' => true,
                                                            'nullable' => false,
                                                            'primary'  => true,
                                                       )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('category_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('filter_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('category_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
            $installer->getIdxName('analytic/category' . $postfix, array('analytic_id')),
            array('analytic_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $installer->getIdxName('analytic/category' . $postfix, array('category_id')),
            array('category_id')
        )
        ->addIndex(
            $installer->getIdxName('analytic/category' . $postfix, array('analytic_id', 'category_id')),
            array('analytic_id', 'category_id')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/search'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/search' . $postfix))
        ->addColumn(
        'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                            'identity' => true,
                                                            'unsigned' => true,
                                                            'nullable' => false,
                                                            'primary'  => true,
                                                       )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('search_string', Varien_Db_Ddl_Table::TYPE_VARCHAR, 250)
        ->addColumn('search_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
            $installer->getIdxName('analytic/search' . $postfix, array('analytic_id')),
            array('analytic_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $installer->getIdxName('analytic/search' . $postfix, array('search_string')),
            array('search_string')
        )
        ->addIndex(
            $installer->getIdxName('analytic/search' . $postfix, array('analytic_id', 'search_string')),
            array('analytic_id', 'search_string')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/product'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/product' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('products_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('view_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
        $installer->getIdxName('analytic/product' . $postfix, array('products_id')),
            array('products_id')
        )
        ->addIndex(
            $installer->getIdxName('analytic/product' . $postfix, array('analytic_id')),
            array('analytic_id')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/tag'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/tag' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('tag_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
            $installer->getIdxName('analytic/tag' . $postfix, array('analytic_id')),
            array('analytic_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $installer->getIdxName('analytic/tag' . $postfix, array('tag_id')),
            array('tag_id')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/review'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/review' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('review_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
        $installer->getIdxName('analytic/review' . $postfix, array('analytic_id')),
            array('analytic_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $installer->getIdxName('analytic/review' . $postfix, array('review_id')),
            array('review_id')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/refer'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/refer' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('ref_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('ref_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
        $installer->getIdxName('analytic/refer' . $postfix, array('analytic_id')),
            array('analytic_id')
        )
        ->addIndex(
            $installer->getIdxName('analytic/refer' . $postfix, array('ref_id')),
            array('ref_id')
        )
        ->addIndex(
            $installer->getIdxName('analytic/refer' . $postfix, array('analytic_id', 'ref_id')),
            array('analytic_id', 'ref_id')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/page'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/page' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('view_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
            $installer->getIdxName('analytic/page' . $postfix, array('analytic_id')),
            array('analytic_id')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/visitor'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/visitor' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('visit_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
            $installer->getIdxName('analytic/visitor' . $postfix, array('analytic_id')),
            array('analytic_id'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/customer'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/customer' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('is_new_customer', Varien_Db_Ddl_Table::TYPE_BOOLEAN)
        ->addColumn('newsletter_subscription', Varien_Db_Ddl_Table::TYPE_BOOLEAN)
        ->addColumn('is_returned', Varien_Db_Ddl_Table::TYPE_BOOLEAN)
        ->addColumn('is_registered', Varien_Db_Ddl_Table::TYPE_BOOLEAN)
        ->addColumn('logins_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('analytic_id')),
            array('analytic_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('analytic_id', 'is_new_customer')),
            array('analytic_id', 'is_new_customer')
        )
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('analytic_id', 'newsletter_subscription')),
            array('analytic_id', 'newsletter_subscription')
        )
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('analytic_id', 'is_returned')),
            array('analytic_id', 'is_returned')
        )
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('analytic_id', 'is_registered')),
            array('analytic_id', 'is_registered')
        )
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('is_new_customer')),
            array('is_new_customer')
        )
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('newsletter_subscription')),
            array('newsletter_subscription')
        )
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('is_returned')),
            array('is_returned')
        )
        ->addIndex(
            $installer->getIdxName('analytic/customer' . $postfix, array('is_registered')),
            array('is_registered')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/category_filter'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/category_filter' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('filter_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('filter_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addIndex(
            $installer->getIdxName('analytic/page' . $postfix, array('analytic_id')),
            array('analytic_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $installer->getIdxName('analytic/page' . $postfix, array('option_id')),
            array('option_id')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/shop'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/shop' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('analytic_id', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('orders_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('products_in_cart_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('checkout_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('wishlist_products_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('create_cart_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('new_customers_orders_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('returned_customers_orders_count', Varien_Db_Ddl_Table::TYPE_INTEGER)
        ->addColumn('orders_amount', Varien_Db_Ddl_Table::TYPE_DOUBLE)
        ->addColumn('products_in_cart_amount', Varien_Db_Ddl_Table::TYPE_DOUBLE)
        ->addColumn('checkout_amount', Varien_Db_Ddl_Table::TYPE_DOUBLE)
        ->addColumn('wishlist_products_amount', Varien_Db_Ddl_Table::TYPE_DOUBLE)
        ->addColumn('create_cart_amount', Varien_Db_Ddl_Table::TYPE_DOUBLE)
        ->addColumn('new_customers_orders_amount', Varien_Db_Ddl_Table::TYPE_DOUBLE)
        ->addColumn('returned_customers_orders_amount', Varien_Db_Ddl_Table::TYPE_DOUBLE)
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('analytic_id')),
            array('analytic_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('analytic_id', 'orders_count')),
            array('analytic_id', 'orders_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('analytic_id', 'products_in_cart_count')),
            array('analytic_id', 'products_in_cart_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('analytic_id', 'checkout_count')),
            array('analytic_id', 'checkout_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('analytic_id', 'wishlist_products_count')),
            array('analytic_id', 'wishlist_products_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('analytic_id', 'create_cart_count')),
            array('analytic_id', 'create_cart_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('orders_count')),
            array('orders_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('products_in_cart_count')),
            array('products_in_cart_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('checkout_count')),
            array('checkout_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('wishlist_products_count')),
            array('wishlist_products_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('create_cart_count')),
            array('create_cart_count')
        )
        ->addIndex(
            $installer->getIdxName('analytic/shop' . $postfix, array('analytic_id', 'new_customers_orders_count')),
            array('analytic_id', 'new_customers_orders_count')
        )
        ->addIndex(
            $installer->getIdxName(
                'analytic/shop' . $postfix, array('analytic_id', 'returned_customers_orders_count')
            ),
            array('analytic_id', 'returned_customers_orders_count')
        )
        ->setOption('type', 'MyISAM');
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/date_partition'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/date_partition' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('date_time', Varien_Db_Ddl_Table::TYPE_DATETIME);
    $installer->getConnection()->createTable($table);

    /**
     * Create table 'analytic/cron_job'
     */
    $table = $installer->getConnection()
        ->newTable($installer->getTable('analytic/cron_job' . $postfix))
        ->addColumn(
            'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                                'identity' => true,
                                                                'unsigned' => true,
                                                                'nullable' => false,
                                                                'primary'  => true,
                                                           )
        )
        ->addColumn('date_time', Varien_Db_Ddl_Table::TYPE_DATETIME)
        ->addColumn('is_real_cron', Varien_Db_Ddl_Table::TYPE_BOOLEAN);
    if ($postfix == '_daily') {
        $table->addColumn('timeZone', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100);
    }
    $installer->getConnection()->createTable($table);
}

/**
 * Create table 'analytic/ref_domain'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('analytic/ref_domain'))
    ->addColumn(
        'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
                                                            'identity' => true,
                                                            'unsigned' => true,
                                                            'nullable' => false,
                                                            'primary'  => true,
                                                       )
    )
    ->addColumn('domain_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 125)
    ->addIndex(
        $installer->getIdxName('analytic/ref_domain', array('domain_name')),
        array('domain_name')
    )
    ->setOption('type', 'MyISAM');
$installer->getConnection()->createTable($table);

/*saving current timezone*/
$currentTimeZone = $installer->getStoreTimezone();
$installer->setConfigData(Analytic_Analytics_Helper_Data::XML_CURRENT_STORE_TIMEZONE, $currentTimeZone);

/* add indexes to log tables */
$tableIndexes = $installer->getConnection()->getIndexList($installer->getTable('log/customer'));
if(!isset($tableIndexes[$installer->getIdxName('log/customer', array('login_at'))])) {
    $installer->getConnection()->addIndex(
        $installer->getTable('log/customer'),
        $installer->getIdxName('log/customer', array('login_at')),
        array('login_at')
    );
}
$tableIndexes = $installer->getConnection()->getIndexList($installer->getTable('log/customer'));
if(!isset($tableIndexes[$installer->getIdxName('log/customer', array('login_at'))])) {
    $installer->getConnection()->addIndex(
        $installer->getTable('customer/entity'),
        $installer->getIdxName('customer/entity', array('created_at')),
        array('created_at')
    );
}
$tableIndexes = $installer->getConnection()->getIndexList($installer->getTable('log/customer'));
if(!isset($tableIndexes[$installer->getIdxName('log/customer', array('login_at'))])) {
    $installer->getConnection()->addIndex(
        $installer->getTable('wishlist/item'),
        $installer->getIdxName('wishlist/item', array('added_at')),
        array('added_at')
    );
}
$tableIndexes = $installer->getConnection()->getIndexList($installer->getTable('log/customer'));
if(!isset($tableIndexes[$installer->getIdxName('log/customer', array('login_at'))])) {
    $installer->getConnection()->addIndex(
        $installer->getTable('log/visitor'),
        $installer->getIdxName('log/visitor', array('store_id')),
        array('store_id')
    );
}
if(!isset($tableIndexes[$installer->getIdxName('log/customer', array('first_visit_at'))])) {
    $installer->getConnection()->addIndex(
        $installer->getTable('log/visitor'),
        $installer->getIdxName('log/visitor', array('first_visit_at')),
        array('first_visit_at')
    );
}

$installer->endSetup();
