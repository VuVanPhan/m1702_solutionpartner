<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Solutionpartner Block
 * 
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @author      Magestore Developer
 */
class Magestore_SolutionPartner_Block_Solutionpartner extends Mage_Core_Block_Template
{
    public function __construct()
    {
        die('dff');
    }
    /**
     * prepare block's layout
     *
     * @return Magestore_SolutionPartner_Block_Solutionpartner
     */
    public function _prepareLayout()
    {
        die('fg');
        return parent::_prepareLayout();
    }

//    public function addLinkMyAccount(){
//        $customer = Mage::getSingleton('customer/session')->getCustomer();
//        if(!$customer || !$customer->getId()){
//            return;
//        }
//        $solutionPartner = Mage::getModel('solutionpartner/partner')->loadByCustomerEmail($customer->getEmail());
//        if($solutionPartner->getId()) {
//            $customerlinkBlock = $this->getParentBlock();
//            if($customerlinkBlock)
//                $customerlinkBlock->addLink('solutionpartner','solutionpartner/account/reports','Solution Partner',true,array(),10);
//        }
//    }
}