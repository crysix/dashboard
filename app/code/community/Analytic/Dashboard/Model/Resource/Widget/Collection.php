<?php


class Analytic_Dashboard_Model_Resource_Widget_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Widget Relation table name
     *
     * @var string
     */
    protected $_widgetRelationTable;

    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('analytic_dashboard/widget');
        $this->_widgetRelationTable = $this->getTable('analytic_dashboard/widget_relation');
    }

    /**
     * Add dashboard filter to collection
     * @param  int                                            $dashboardId
     * @return Analytic_Dashboard_Model_Resource_Widget_Collection
     */
    public function addDashboardToFilter($dashboardId)
    {
        $dashboardId = (int) $dashboardId;
        $this->getSelect()->joinLeft(
            array("dashboard_widgets" => $this->_widgetRelationTable),
            "main_table.id = dashboard_widgets.widget_id AND dashboard_widgets.dashboard_id = $dashboardId",
            array("position_column", "position_order")
        )->order("dashboard_widgets.position_order ASC");

        return $this;
    }
}
