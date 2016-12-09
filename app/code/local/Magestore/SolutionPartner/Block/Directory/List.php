<?php
class Magestore_SolutionPartner_Block_Directory_List extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'solutionpartner/directory_partner_list_toolbar';

    /**
     * Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_solutionPartnerCollection;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }

    public function getLoadedSolutionPartnerCollection()
    {
        return $this->_getSolutionPartnerCollection();
    }

    public function _getSolutionPartnerCollection()
    {
        if (is_null($this->_solutionPartnerCollection)) {
            $solutionPartner = Mage::getSingleton('solutionpartner/partner')->getResourceCollection();
            $this->_solutionPartnerCollection = $solutionPartner;
        }
        return $this->_solutionPartnerCollection;
    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result)
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->_getSolutionPartnerCollection();

        // use sortable parameters
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }

        if ($sort = $this->getSortBy()) {
            $toolbar->setDefaultOrder($sort);
        }

        if ($dir = $this->getDefaultDirection()) {
            $toolbar->setDefaultDirection($dir);
        }

        if ($modes = $this->getModes()) {
            $toolbar->setModes($modes);
        }

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        Mage::dispatchEvent('solutionpartner_block_directory_partner_list_collection', array(
            'collection' => $this->_getSolutionPartnerCollection()
        ));

        $this->_getSolutionPartnerCollection()->load();

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode()
    {
//        return $this->getChild('toolbar')->getCurrentMode();
        return 'grid';
    }
}