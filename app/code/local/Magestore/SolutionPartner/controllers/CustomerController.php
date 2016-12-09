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
 * SolutionPartner Customer Controller
 *
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @author      Magestore Developer
 */
class Magestore_SolutionPartner_CustomerController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
        if(!Mage::getModel('customer/session')->isLoggedIn())
            return $this->_redirect('customer/account/login');
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    public function newsAction()
    {
        if(Mage::helper('solutionpartner/customer')->customerIsNotPartner())
            return $this->_redirect('solutionpartner/customer');
        $this->loadLayout();
        $this->renderLayout();
    }

    public function reportsAction()
    {
        if(Mage::helper('solutionpartner/customer')->customerIsNotPartner())
            return $this->_redirect('solutionpartner/customer');
        $this->loadLayout();
        $this->renderLayout();
    }

    public function settingAction()
    {
        if(Mage::helper('solutionpartner/customer')->customerIsNotPartner())
            return $this->_redirect('solutionpartner/customer');
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editAction()
    {
        if(!$this->getRequest()->isPost())
            return $this->_redirect('solutionpartner/customer/setting');

        $data = $this->getRequest()->getPost();
        if(isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name'] != '') {
            try {
                /* Starting upload */
                $uploader = new Varien_File_Uploader('company_logo');

                // Any extention would work
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                $uploader->setAllowRenameFiles(false);

                $uploader->setFilesDispersion(false);

                // We set media as the upload dir
                $path = Mage::getBaseDir('media') . DS ;
                $uploader->save($path, $_FILES['company_logo']['name'] );

            } catch (Exception $e) {

            }
            $data['company_logo'] = $_FILES['company_logo']['name'];
        } else {
            unset($data['company_logo']);
        }
        $session = Mage::getSingleton('core/session');

        //check email address
        if($error_message = $this->_checkemail($this->getRequest()->getPost('email'))){
            $session->addError($error_message);
            return $this->_redirect('solutionpartner/customer/setting');
        }

        $customer = Mage::getModel('customer/session')->getCustomer();
        $customerEmail = $customer->getEmail();
        if($customerEmail != $data['email']){
            $session->addError($this->__('Customer is not logged in.'));
            return $this->_redirect('solutionpartner/customer/setting');
        }
        $solutionPartner = Mage::getModel('solutionpartner/partner')->load($data['email'], 'email');

        echo "<pre>";
        var_dump($data);
        die();

        try{
            $solutionPartner->addData($data);
            $solutionPartner->save();
            $session->addSuccess($this->__('Solution Partner information was updated successfully.'));
            return $this->_redirect('solutionpartner/customer/setting');
        }catch(exception $e){
            $session->addError($e->getMessage());
            return $this->_redirect('solutionpartner/customer/setting');
        }
    }

    protected function _checkemail($email_address)
    {
        $error_message = null;
        if (!Zend_Validate::is(trim($email_address), 'EmailAddress')) {
            $error_message = Mage::helper('solutionpartner')->__('Invalid email address!');
        }
        return $error_message;
    }
}