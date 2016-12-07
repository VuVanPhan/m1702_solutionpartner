<?php
class Magestore_SolutionPartner_Block_Customer_Setting extends Mage_Core_Block_Template
{
    public function getPostActionUrl()
    {
        return $this->getUrl('solutionpartner/customer/edit');
    }

    public function getFormEditData()
    {
        $customer = Mage::getModel('customer/session')->getCustomer();
        $customerEmail = $customer->getEmail();
        $data = Mage::getModel('solutionpartner/partner')->load($customerEmail, 'email');
        return new Varien_Object($data);
    }

    public function getCountryOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getCountryList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormEditData()->getCountry())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }

    public function getNumberEmployeesOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getNumberEmployeesList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormEditData()->getNumberEmployees())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }

    public function getServiceOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getServiceList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormEditData()->getService())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }

    public function getProjectSizeOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getProjectSizeList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormEditData()->getProjectSize())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }

    public function getHourlyRateOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getHourlyRateList();
        foreach($option as $code=>$value){
            $selected = ($code == $this->getFormEditData()->getHourlyRate())? 'selected' : '';
            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
        }
        return $html_option;
    }
}