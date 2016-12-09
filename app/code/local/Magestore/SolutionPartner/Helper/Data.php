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
 * SolutionPartner Helper
 * 
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @author      Magestore Developer
 */
class Magestore_SolutionPartner_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLE_MODULE        = 'solutionpartner/general/enable';
    const XML_PATH_SOLUTIONPARTNER_PAGE = 'solutionpartner/general/landing_page';
    const XML_PATH_SUCCESS_MESSAGE      = 'solutionpartner/general/success_message';
    const XML_PATH_INDUSTRY             = 'solutionpartner/general/industry';
    const XML_PATH_PROJECT_SIZE         = 'solutionpartner/general/project_size';
    const XML_PATH_HOURLY_RATE          = 'solutionpartner/general/hourly_rate';

    public function getEnableModule()
    {
        return Mage::getStoreConfig(self::XML_PATH_ENABLE_MODULE);
    }

    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED    = 2;

    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('solutionpartner')->__('Approve'),
            self::STATUS_DISABLED   => Mage::helper('solutionpartner')->__('Disapprove')
        );
    }

    /**
     * get model option hash as array
     *
     * @return array
     */
    static public function getOptionHash()
    {
        $options = array();
        foreach (self::getOptionArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }

    public function getSolutionpartnerPage()
    {
        if(!Mage::getModel('customer/session')->isLoggedIn())
            return Mage::getStoreConfig(self::XML_PATH_SOLUTIONPARTNER_PAGE);
        else
            return 'solutionpartner/customer/';
    }

    public function getSuccessMessage()
    {
        return Mage::getStoreConfig(self::XML_PATH_SUCCESS_MESSAGE);
    }

    public function getCountryList()
    {
        $list = array();
        foreach(Mage::helper('directory')->getCountryCollection() as $country){
            $list[$country->getIso2Code()] = $country->getName();
        }
        return $list;
    }

    public function getCountryOption()
    {
        $option = array();
        $list = $this->getCountryList();
        foreach($list as $value=>$label){
            $option[] = array('value'=>$value,'label'=>$label);
        }
        return $option;
    }

    public function getIndustryList()
    {
        $list = array();
        $service = trim(Mage::getStoreConfig(self::XML_PATH_INDUSTRY));
        $service = explode(',',$service);
        foreach($service as $value=>$label){
            $list[$value] = $label;
        }
        return $list;
    }

    public function getIndustryOption()
    {
        $option = array();
        $service = $this->getIndustryList();
        foreach($service as $value=>$label){
            $option[] = array('value'=>$value,'label'=>$label);
        }
        return $option;
    }

    public function getProjectSizeList()
    {
        $list = array();
        $projectSize = trim(Mage::getStoreConfig(self::XML_PATH_PROJECT_SIZE));
        $projectSize = explode(',',$projectSize);
        foreach($projectSize as $value=>$label){
            $list[$value] = $label;
        }
        return $list;
    }
    
    public function getProjectSizeOption()
    {
        $option = array();
        $projectSize = $this->getProjectSizeList();
        foreach($projectSize as $value=>$label){
            $option[] = array('value'=>$value,'label'=>$label);
        }
        return $option;
    }

    public function getHourlyRateList()
    {
        $list = array();
        $hourlyRate = trim(Mage::getStoreConfig(self::XML_PATH_HOURLY_RATE));
        $hourlyRate = explode(',',$hourlyRate);
        foreach($hourlyRate as $value=>$label){
            $list[$value] = $label;
        }
        return $list;
    }

    public function getHourlyRateOption()
    {
        $option = array();
        $hourlyRate = $this->getHourlyRateList();
        foreach($hourlyRate as $value=>$label){
            $option[] = array('value'=>$value,'label'=>$label);
        }
        return $option;
    }

    public function getPackgeUrl()
    {
        $package = Mage::helper('membership')->getFreePackage();
        if($package->getId())
            // $url = Mage::helper('membership')->addToCartUrl($package->getProductId());
            $url = $this->_getUrl('checkout/cart/add', array('product'=>$package->getProductId()));
        else
            $url = $this->_getUrl('customer/account/login');
        return $url;
    }
}