<?php
class Magestore_SolutionPartner_RegisterController extends Mage_Core_Controller_Front_Action
{
    public function postAction()
    {
        if (!$this->getRequest()->isPost())
            return $this->_redirect(Mage::helper('solutionpartner')->getSolutionpartnerPage());
        /*
            Update
        */
        $data = $this->getRequest()->getPost();
//        echo "<pre>";
//        var_dump(isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name'] != '');
//        var_dump($_FILES);
        if (isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name'] != '') {
            try {
                /* Starting upload */
                $uploader = new Varien_File_Uploader('company_logo');

                // Any extention would work
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(false);

                $uploader->setFilesDispersion(false);

                // We set media as the upload dir
                $path = Mage::getBaseDir('media') . DS;
                $uploader->save($path, $_FILES['company_logo']['name']);
                $data['company_logo'] = $_FILES['company_logo']['name'];
            } catch (Exception $e) {
                $data['company_logo'] = $_FILES['company_logo']['name'];
            }
        } else {
            unset($data['company_logo']);
        }
        
        $session = Mage::getSingleton('core/session');
        $sessionCustomer = Mage::getSingleton('customer/session');

//        var_dump($this->_checkemail($this->getRequest()->getPost('email')));
//        die();
        //check email address
        if ($error_message = $this->_checkemail($this->getRequest()->getPost('email'))) {
            $session->setSolutionpartnerFormData($this->getRequest()->getPost());
            // $session->addError($error_message);
            $sessionCustomer->addError($error_message);
            return $this->_redirect(Mage::helper('solutionpartner')->getSolutionpartnerPage() . '?check=error');
        }

        //check captcha code
//		$captchaCode = $session->getData('register_partner_captcha_code');
//		if (!isset($data['captcha']) || $data['captcha'] != $captchaCode){
//			$session->setPartnerFormData($this->getRequest()->getPost());
//			// $session->addError(Mage::helper('solutionpartner')->__('Please enter a correct verification code!'));
//			$sessionCustomer->addError(Mage::helper('solutionpartner')->__('Please enter a correct verification code!'));
//			return $this->_redirect(Mage::helper('solutionpartner')->getPartnerPage().'?check=error');
//		}

        //new check captcha with recaptcha
//        $captchaCode = $session->getData('register_solutionpartner_captcha_code');
//        $recaptcha = Mage::getModel('magestore_recaptcha/captcha');
//        if (!isset($data['g-recaptcha-response']) || !$recaptcha->verify($data['g-recaptcha-response'])) {
//            $sessionCustomer->addError(Mage::helper('solutionpartner')->__('Please enter captcha!'));
//            return $this->_redirect(Mage::helper('solutionpartner')->getSolutionpartnerPage() . '?check=error');
//        }

        $solutionPartner = Mage::getModel('solutionpartner/partner')->setData($data);

        $ip_address = null;
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $solutionPartner->setIpAddress($ip_address);
        }
        $locked_ips = explode(',', Mage::getStoreConfig('solutionpartner/general/locked_ips'));
        $solutionPartner->setStoreId(Mage::app()->getStore()->getId());
        $solutionPartner->setWebsiteId(Mage::app()->getWebsite()->getId());
        // $sessionCustomer = Mage::getSingleton('customer/session');
        $helper = Mage::helper('solutionpartner');
        try {
            $solutionPartner->setStatus('3');
            if ($solutionPartner->getRegisteredDate() == NULL || $solutionPartner->getUpdateTime() == NULL) {
                $solutionPartner->setRegisteredDate(now())
                    ->setUpdateTime(now());
            } else {
                $solutionPartner->setUpdateTime(now());
            }
            if (!in_array($ip_address, $locked_ips)) {
                $solutionPartner->save();
                $solutionPartner->sendRegistrationEmail()
                    ->sendAdminRegistrationEmail();
                if ($data['solutionpartner_type_register'] == '2') {
                    $customer = Mage::getModel('customer/customer');
                    $customerList = $customer->getCollection()->addFieldTofilter('email', $data['email']);
                    $websiteId = Mage::app()->getWebsite()->getId();
                    $storeId = Mage::app()->getStore()->getId();
                    if (!count($customerList)) {
                        $customer->setData('email', $data['email'])
                            ->setData('website_id', $websiteId)
                            ->setData('store_id', $storeId)
                            ->setData('group_id', 1)
                            ->setData('firstname', $data['name'])
                            ->setData('lastname', $data['name']);
                        $customer->setPassword($customer->generatePassword());
                        $customer->save()->setId(null);
                        $customer->sendNewAccountEmail('registered', '', $storeId);
                        // $sessionCustomer->setBeforeAuthUrl($helper->getPackgeUrl());
                        $sessionCustomer->login($customer->getEmail(), $customer->getPassword());
                        header('Location:' . $helper->getPackgeUrl());
                        exit(0);

                    } else {
                        header('Location:' . $helper->getPackgeUrl());
                        exit(0);
                    }
                }
            }
            $session->addSuccess(Mage::helper('solutionpartner')->getSuccessMessage());
            #$sessionCustomer->addSuccess(Mage::helper('solutionpartner')->getSuccessMessage());
            $session->unsetData('solutionpartner_form_data');
            if ($data['solutionpartner_type_register'] == '1') {
                return $this->_redirect(Mage::helper('solutionpartner')->getSolutionpartnerPage() . '?check=success');
            }
        } catch (exception $e) {
            $session->setPartnerFormData($this->getRequest()->getPost());
            // $session->addError($e->getMessage());
            $sessionCustomer->addError($e->getMessage());
            return $this->_redirect(Mage::helper('solutionpartner')->getSolutionpartnerPage() . '?check=error');
        }
        return $this->_redirect(Mage::helper('solutionpartner')->getSolutionpartnerPage());
    }

    protected function _checkemail($email_address)
    {
        $isvalid_email = true;
        $error_message = null;
        if (!Zend_Validate::is(trim($email_address), 'EmailAddress')) {
            $isvalid_email = false;
            $error_message = Mage::helper('solutionpartner')->__('Invalid email address!');
        }
        if($isvalid_email){
            $email = Mage::getModel('solutionpartner/partner')->getCollection()
                ->addFieldToFilter('email',$email_address)
                ->getFirstItem();
            if($email->getId()) {
                $error_message = Mage::helper('solutionpartner')->__('The email %s belongs to one of our solution partner accounts, please chose an other.',$email_address);
            }
        }
        return $error_message;
    }
}