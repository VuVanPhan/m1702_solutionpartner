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
    /**
     * prepare block's layout
     *
     * @return Magestore_SolutionPartner_Block_Solutionpartner
     */
    public function _prepareLayout()
    {
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

    public function isShow()
    {
        if($this->getRequest()->getParam('state') || $this->getParnerCustomer())
        {
            return false;
        }
        return true;
    }

    public function getParnerCustomer()
    {
        if(Mage::getModel('customer/session')->isLoggedIn()){
            $customer = Mage::getModel('customer/session')->getCustomer();
            $customerEmail = $customer->getEmail();
            $partner = Mage::getModel('solutionpartner/partner')->load($customerEmail, 'email');
            if($partner->getId())
                return $partner->getId();
        }
        return false;
    }

    public function getPostActionUrl()
    {
        return $this->getUrl('solutionpartner/register/post');
    }

    public function getFormData()
    {
        $data = Mage::getSingleton('core/session')->getSolutionpartnerFormData();
        if(!$data){
            if(Mage::getModel('customer/session')->isLoggedIn()){
                $customer = Mage::getModel('customer/session')->getCustomer();
                $data['name'] = $customer->getFirstname().' '.$customer->getLastname();
                $data['email'] = $customer->getEmail();
            }
        }
        return new Varien_Object($data);
    }

    public function getTermCondition()
    {
        $storeId = Mage::app()->getStore()->getId();
        return Mage::getStoreConfig('solutionpartner/general/term', $storeId);
    }

    public function getCountryOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getCountryList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormData()->getCountry())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }

    public function getNumberEmployeesOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getNumberEmployeesList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormData()->getNumberEmployees())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }

    public function getIndustryOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getIndustryList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormData()->getIndustry())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }

    public function getProjectSizeOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getProjectSizeList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormData()->getProjectSize())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }
    
    public function getHourlyRateOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getHourlyRateList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormData()->getHourlyRate())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }
}