<?php


abstract class Analytic_Dashboard_Model_Metric_Abstract
{
    protected $_code = '';

    /**
     * @var string
     */
    protected $_plotType = '';

    /**
     * Round decimal value
     *
     * @param  float $value
     * @return int
     */
    public function round($value)
    {
        return round($value);
    }

    /**
     * Get metric config label
     *
     * @return string
     */
    public function getLabel()
    {
        if ($this->_code) {
            return Mage::getConfig()->getNode('metrics/' . $this->_code . '/label')->asArray();
        }
    }

    /**
     * Get widget data for metric
     *
     * @return float
     */
    public function getData()
    {
        return 0;
    }

    /**
     * Get widget data for timeline plot
     *
     * @param  int     $limit
     * @param  boolean $sort
     * @param  array   $whereValues
     * @return array
     */
    public function getDataForTimeline($limit = null, $sort = false, $whereValues = array())
    {
        return array();
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
        return array();
    }

    /**
     * Set Plot Type
     *
     * @param  string $plotType
     */
    public function setPlotType($plotType){
        $this->_plotType = $plotType;

        return $this;
    }

    /**
     * Get Plot Type
     *
     * @return string
     */
    protected function getPlotType(){
        return $this->_plotType;
    }

    /**
     * get resource model
     *
     * @param  string $modelName
     * @return Object
     */
    protected function getModel($modelName)
    {
        $resourceModel = Mage::getResourceModel($modelName);
        $helper = Mage::helper('analytic_dashboard');
        $resourceModel->setPlotType($this->getPlotType()?$this->getPlotType():$helper->getPlotType())
                      ->setAggregationType($helper->getAggregationType());

        return $resourceModel;
    }
}
