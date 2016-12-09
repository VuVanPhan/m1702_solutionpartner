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
 * Solutionpartner Model
 * 
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @author      Magestore Developer
 */
class Magestore_SolutionPartner_Model_Partner extends Mage_Core_Model_Abstract
{
    const XML_PATH_EMAIL_COPY_TO                = 'solutionpartner/email/copy_to';
    const XML_PATH_NEW_REGISTRATION_EMAIL       = 'solutionpartner/email/registration';
    const XML_PATH_ADMIN_NEW_REGISTRATION_EMAIL = 'solutionpartner/email/admin_registration';

    const XML_PATH_INDUSTRY                     = 'solutionpartner/general/industry';
    const XML_PATH_PROJECT_SIZE                 = 'solutionpartner/general/project_size';
    const XML_PATH_HOURLY_RATE                  = 'solutionpartner/general/hourly_rate';

    public function _construct()
    {
        parent::_construct();
        $this->_init('solutionpartner/partner');
    }

    public function _getCustomer()
    {
        if(!$this->getData('customer')){
            $orderIds = array();
            $customers = Mage::getResourceModel('customer/customer_collection');
            $customers->addFieldToFilter('email',$this->getEmail());
            if(count($customers)){
                foreach($customers as $customer){
                    $this->setData('customer',$customer);
                }
            }
        }
        return $this->getData('customer');
    }

    protected function _getEmails($configPath)
    {
        $data = Mage::getStoreConfig($configPath, $this->getStoreId());
        if (!empty($data)) {
            return explode(',', $data);
        }
        return false;
    }

    public function sendRegistrationEmail()
    {
        $storeId = Mage::app()->getStore()->getId();
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($this->getEmail(), $this->getName());
        $mailer->addEmailInfo($emailInfo);
        $templateId = Mage::getStoreConfig(self::XML_PATH_NEW_REGISTRATION_EMAIL,$storeId);

        // Set all required params and send emails
        $mailer->setSender('sales');
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(array(
                'solutionpartner'   => $this,
            )
        );

        $mailer->send();
        $this->setEmailSent(true);

        return $this;
    }

    public function getIndustryList($serviceList)
    {
        $service = trim(Mage::getStoreConfig(self::XML_PATH_INDUSTRY));
        $service = explode(',',$service);
        foreach($service as $value=>$label){
            if($serviceList == $value)
                return  $label;
        }
    }

    public function getProjectSizeList($projectSizeList)
    {
        $projectSize = trim(Mage::getStoreConfig(self::XML_PATH_PROJECT_SIZE));
        $projectSize = explode(',',$projectSize);
        foreach($projectSize as $value=>$label){
            if($projectSizeList == $value)
                return  $label;
        }
    }

    public function getHourlyRateList($hourlyRateList)
    {
        $hourlyRate = trim(Mage::getStoreConfig(self::XML_PATH_HOURLY_RATE));
        $hourlyRate = explode(',',$hourlyRate);
        foreach($hourlyRate as $value=>$label){
            if($hourlyRateList == $value)
                return  $label;
        }
    }

    public function sendAdminRegistrationEmail()
    {
        $storeId = Mage::app()->getStore()->getId();
        $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
        $copyMethod = 'bcc';

        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');

        $emailInfo->addTo('sales@magestore.com', 'Sales Department');
        if ($copyTo && $copyMethod == 'bcc') {
            foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
            }
        }

        $mailer->addEmailInfo($emailInfo);
        $templateId = Mage::getStoreConfig(self::XML_PATH_ADMIN_NEW_REGISTRATION_EMAIL,$storeId);

        // Set all required params and send emails
        $mailer->setSender('sales');
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $countryName=Mage::app()->getLocale()->getCountryTranslation($this->getCountry());
        $numberEmployees = $this->getNumberEmployeesList($this->getNumberEmployees());
        $service = $this->getIndustryList($this->getIndustry());
        $projectSize= $this->getProjectSizeList($this->getProjectSize());
        $hourlyRate= $this->getHourlyRateList($this->getHourlyRate());
        $this->setCountry($countryName);
        $this->setNumberEmployees($numberEmployees);
        $this->setIndustry($service);
        $this->setProjectSize($projectSize);
        $this->setHourlyRate($hourlyRate);
        $mailer->setTemplateParams(array(
                'solutionpartner'   => $this,
            )
        );

        $mailer->send();
        $this->setEmailSent(true);

        return $this;
    }

    public function getCustomerOrderIds()
    {
        if(!$this->getData('customer_order_ids')){
            $orderIds = array();
            $customers = Mage::getResourceModel('customer/customer_collection');
            $customers->addFieldToFilter('email',$this->getEmail());

            if(count($customers)){
                foreach($customers as $customer){
                    $orderIds[] = $customer->getEntityId();
                }
            }
            $this->setData('customer_order_ids',$orderIds);
        }
        return $this->getData('customer_order_ids');
    }
}