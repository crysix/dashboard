<?php


class Analytic_Dashboard_Model_Metric_NumberOfProductsInCart extends Analytic_Dashboard_Model_Metric_Abstract
{
    protected $_code = 'number_of_products_in_cart';
    private $modelName = 'analytic/shop';

    /**
     * Get widget data for metric
     *
     * @return int
     */
    public function getData()
    {
        return $this->getModel($this->modelName)->getProductsInCartCount(
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
        return $this->getModel($this->modelName)->getProductsInCartData(
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
        return $this->getModel($this->modelName)->getProductsInCartGroupData(
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
        return $this->round($this->getModel($this->modelName)->getProductsInCartAvgCount(
            Mage::helper('analytic_dashboard')->getDateFrom(true),
            Mage::helper('analytic_dashboard')->getDateTo(true),
            Mage::helper('analytic_dashboard')->getStore()
        ));
    }
}
