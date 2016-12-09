<?php
class Magestore_SolutionPartner_Block_Directory_Partner_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    /**
     * Retrieve Catalog Config object
     *
     * @return Mage_Catalog_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('catalog/config');
    }

    /**
     * Init Toolbar
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_orderField  = Mage::getStoreConfig(
            Mage_Catalog_Model_Config::XML_PATH_LIST_DEFAULT_SORT_BY
        );

        $this->_availableOrder = array('name'=>$this->__('Name'));

        $this->_availableMode = array('grid' => $this->__('Grid'));

        $this->setTemplate('solutionpartner/directory/partner/list/toolbar.phtml');
    }
}