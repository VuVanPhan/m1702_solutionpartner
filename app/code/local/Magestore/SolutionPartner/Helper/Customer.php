<?php

class Magestore_SolutionPartner_Helper_Customer extends Mage_Core_Helper_Abstract
{

    public function getNavigationLabel()
    {
        return $this->__('My Solution Partner Account');
    }

    public function customerLoggedIn()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    public function customerNotLogin()
    {
        return !$this->customerLoggedIn();
    }

    public function customerIsNotPartner()
    {
        if($this->customerLoggedIn()){
            $customer = Mage::getModel('customer/session')->getCustomer();
            $customerEmail = $customer->getEmail();
            $partner = Mage::getModel('solutionpartner/partner')->load($customerEmail, 'email');
            // if($partner->getId() && $partner->getStatus() == 1)
            if($partner->getId())
                return false;
        }
        return true;
    }
}