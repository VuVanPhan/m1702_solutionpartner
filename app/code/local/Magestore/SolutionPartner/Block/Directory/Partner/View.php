<?php
class Magestore_SolutionPartner_Block_Directory_Partner_View extends Mage_Catalog_Block_Product_Abstract
{
    public function getPartner()
    {
        if(!Mage::registry('partner_data'))
        {
            return Mage::registry('partner_data');
        }
        return Mage::getModel('solutionpartner/partner')->load($this->getRequest()->getParam('id'));
    }
}