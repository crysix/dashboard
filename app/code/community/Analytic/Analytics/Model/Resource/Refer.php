<?php
/**
 * {analytic_license_notice}
 *
 * @category   Analytic
 * @package    Analytics
 * @copyright  {analytic_copyright}
 * @license    {analytic_license}
 */

class Analytic_Analytics_Model_Resource_Refer extends Analytic_Analytics_Model_Resource_Base
{
    /**
     * Initialize resource model
     *
     */
    public function _construct()
    {
        $this->_init('analytic/refer', 'id');
    }

    /**
     * get grouped refers data by period
     *
     * @param  string                                   $dateFrom
     * @param  string                                   $dateTo
     * @param  string                                   $groupType
     * @param  Mage_Eav_Model_Entity_Attribute_Abstract $attribute
     * @param  int                                      $storeId
     * @param  int                                      $limit
     * @param  array                                    $whereValues
     * @return array
     */
    public function getRefersGroupData($dateFrom, $dateTo, $groupType, Mage_Eav_Model_Entity_Attribute_Abstract $attribute, $storeId = null, $limit = null, $whereValues = array())
    {
        $customerAddressAttribute = $this->getCustomerAddressAttribute($groupType);
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from(
            array('analytic_refers' => $this->getPeriodTable('analytic/refer')),
            'addr.value AS group_value, SUM(analytic_refers.ref_count) as count'
        )
            ->join(
            array('analytic' => $this->getPeriodTable('analytic/data')),
            'analytic_refers.analytic_id = analytic.id',
            array()
        )
            ->join(
            array('int_val' =>  $customerAddressAttribute->getBackendTable()),
            'int_val.entity_id = analytic.customer_id AND int_val.attribute_id = :groupTypeId',
            array()
        )
            ->join(
            array('addr' =>  $attribute->getBackendTable()),
            'addr.entity_id = int_val.value AND addr.attribute_id = :groupId',
            array()
        )
            ->where('analytic.period > :dateFrom')
            ->where('analytic.period <= :dateTo');

        $binds = array(
            ":dateFrom" => $dateFrom,
            ":dateTo" => $dateTo,
            ":groupTypeId" => $customerAddressAttribute->getId(),
            ":groupId" => $attribute->getId()
        );

        if ($storeId !== null) {
            $select->where('analytic.store_id = :storeId');
            $binds[':storeId'] = $storeId;
        }
        if ($whereValues) {
            $values = implode(',',$whereValues);
            $select->where("addr.value IN ($values)");
        }
        if ($limit !== null) {
            $select->limit($limit);
        }
        $select->group('group_value');
        $select->order('count DESC');

        return $adapter->fetchAll($select, $binds);
    }

    /**
     * get refers data by period
     *
     * @param  string  $dateFrom
     * @param  string  $dateTo
     * @param  int     $storeId
     * @param  int     $limit
     * @param  boolean $sort
     * @param  array   $whereValues
     * @return array
     */
    public function getRefersData($dateFrom, $dateTo, $storeId = null, $limit = null, $sort = null, $whereValues = array())
    {
        $this->_prepareDataSelect('ref_count', $dateFrom, $dateTo, $storeId, $limit, $sort, $whereValues, self::SUM_AGGREGATOR);

        return $this->_fetchData();
    }

    /**
     * get refers by period
     *
     * @param  string $dateFrom
     * @param  string $dateTo
     * @param  int    $storeId
     * @return int
     */
    public function getRefersCount($dateFrom, $dateTo, $storeId = null)
    {
        $this->_prepareCountSelect('ref_count', $dateFrom, $dateTo, $storeId, self::SUM_AGGREGATOR);
        return $this->_fetchCount();
    }

    /**
     * get avg refers by period
     *
     * @param  string    $dateFrom
     * @param  string    $dateTo
     * @param  int       $storeId
     * @return float|int
     */
    public function getRefersAvgCount($dateFrom, $dateTo, $storeId = null)
    {
        return $this->getAvgCount(
            $this->getRefersCount($dateFrom, $dateTo, $storeId),
            $dateFrom,
            $dateTo
        );
    }
}
