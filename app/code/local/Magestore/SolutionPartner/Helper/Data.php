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

    public function getNumberEmployeesOption()
    {
        $employees = array('Under 10', '11-20', '21-50', '51-100', '101-200', '201-400', '401-600', '601-1000', 'More than 1000');
        foreach($employees as $value=>$label){
            $option[] = array('value'=>$value,'label'=>$label);
        }
        return $option;
    }

    public function getServiceList()
    {
        $list = array();
        $service = array('Web Design', 'Web Development', 'Hosting/Server Support', 'App Development', 'Software Development', 'Marketing', 'HR Outsourcing');
        foreach($service as $value=>$label){
            $list[$value] = $label;
        }
        return $list;
    }

    public function getServiceOption()
    {
        $option = array();
        $service = $this->getServiceList();
        foreach($service as $value=>$label){
            $option[] = array('value'=>$value,'label'=>$label);
        }
        return $option;
    }

    public function getProjectSizeList()
    {
        $list = array();
        $projectSize = array('Less than $500', '$501-$1000', '$1001-$5000', '$5001-$10000', '$10001-$50000', 'More than 50000');
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

    public function getHourlyRateOption()
    {
        $hourlyRate = array('Less than $20', '$20-$40', '$41-$100', 'More than $100');
        foreach($hourlyRate as $value=>$label){
            $option[] = array('value'=>$value,'label'=>$label);
        }
        return $option;
    }
}