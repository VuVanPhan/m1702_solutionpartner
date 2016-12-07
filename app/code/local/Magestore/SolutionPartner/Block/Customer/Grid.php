<?php
class Magestore_SolutionPartner_Block_Customer_Grid extends Mage_Customer_Block_Account_Navigation
{
    public function _construct()
    {
        //die('fd');
    }

    /**
     * Add new Column to Grid
     *
     * @param string $columnId
     * @param array $params
     * @return Magestore_Partner_Block_Account_Grid
     */
    public function addColumn($columnId, $params){
        if (isset($params['searchable']) && $params['searchable']) {
            $this->setData('add_searchable_row', true);
            if ($params['type'] == 'date' || $params['type'] == 'datetime') {
                $this->setData('add_calendar_js_to_grid', true);
            }
        }
        $this->_columns[$columnId] = $params;
        return $this;
    }
}