<?php


class Analytic_Dashboard_Model_Metric_NumberOfProductViews extends Analytic_Dashboard_Model_Metric_Abstract
{
    /**
     * @var string
     */
    protected $_code = 'number_of_page_views';
    private $modelName = 'analytic/product';

    /**
     * Get widget data for metric
     *
     * @return int
     */
    public function getData()
    {
        return $this->getModel($this->modelName)->getProductViewsCount(
            Mage::helper('analytic_dashboard')->getDateFrom(true),
            Mage::helper('analytic_dashboard')->getDateTo(true),
            Mage::helper('analytic_dashboard')->getStore()
        );
    }

    /**
     * Get widget data for timeline
     *
     * @param  int     $limit
     * @param  boolean $sort
     * @param  array   $whereValues
     * @return array
     */
    public function getDataForTimeline($limit = null, $sort = false, $whereValues = array())
    {
        return $this->getModel($this->modelName)->getProductViewsData(
            Mage::helper('analytic_dashboard')->getDateFrom(true),
            Mage::helper('analytic_dashboard')->getDateTo(true),
            Mage::helper('analytic_dashboard')->getStore(),
            $limit,
            $sort,
            $whereValues
        );
    }

    /**
     * Get widget data for pie
     * @param  Varien_Object $attributeData
     * @param  int           $limit
     * @param  array         $whereValues
     * @return array
     */
    public function getDataForPie(Varien_Object $attributeData, $limit, $whereValues = array())
    {
        return $this->getModel($this->modelName)->getProductViewsGroupData(
            Mage::helper('analytic_dashboard')->getDateFrom(true),
            Mage::helper('analytic_dashboard')->getDateTo(true),
            $attributeData->getAddressType(),
            $attributeData->getAttribute(),
            Mage::helper('analytic_dashboard')->getStore(),
            $limit,
            $whereValues
        );
    }

    /**
     * Get avg widget data for metric
     *
     * @return int
     */
    public function getAvgData()
    {
        return $this->round($this->getModel($this->modelName)->getProductViewsAvgCount(
            Mage::helper('analytic_dashboard')->getDateFrom(true),
            Mage::helper('analytic_dashboard')->getDateTo(true),
            Mage::helper('analytic_dashboard')->getStore()
        ));
    }
}
