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
 * Solutionpartner Adminhtml Controller
 * 
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @author      Magestore Developer
 */
class Magestore_SolutionPartner_Adminhtml_SolutionpartnerController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_SolutionPartner_Adminhtml_SolutionpartnerController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('solutionpartner/solutionpartner')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Items Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
        $this->getLayout()->getBlock('head')->setTitle($this->__('Solution Partner Manager'));
        return $this;
    }
 
    /**
     * index action
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Solution Partner Manager'));
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $solutionpartnerId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('solutionpartner/partner')->load($solutionpartnerId);

        if ($model->getId() || $solutionpartnerId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('solutionpartner_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('solutionpartner/solutionpartner');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Solution Partner Manager'),
                Mage::helper('adminhtml')->__('Solution Partner Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Solution Partner News'),
                Mage::helper('adminhtml')->__('Solution Partner News')
            );

            if ($model->getId())
                $this->_title($model->getCompanyName());
            else
                $this->_title(Mage::helper('adminhtml')->__('Add New Solution Partner'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('solutionpartner/adminhtml_solutionpartner_edit'))
                ->_addLeft($this->getLayout()->createBlock('solutionpartner/adminhtml_solutionpartner_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('solutionpartner')->__('Item does not exist')
            );
            $this->_redirect('*/*/');
        }
    }
 
    public function newAction()
    {
        $this->_forward('edit');
    }
 
    /**
     * save item action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            if (isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name'] != '') {
                try {
                    /* Starting upload */    
                    $uploader = new Varien_File_Uploader('company_logo');
                    
                    // Any extention would work
                       $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                    $uploader->setAllowRenameFiles(false);
                    
                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //    (file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);
                            
                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS ;
                    $result = $uploader->save($path, $_FILES['company_logo']['name'] );
                    $data['company_logo'] = $_FILES['company_logo']['name'];
                } catch (Exception $e) {
                    $data['company_logo'] = $_FILES['company_logo']['name'];
                }
            } elseif(isset($data['company_logo']['delete']) && $data['company_logo']['delete'] == '1') {
                $data['company_logo'] = '';
            } else {
                unset($data['company_logo']);
            }

            $model = Mage::getModel('solutionpartner/partner');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            /*
				Create customer when active partner
			*/
            if($model->getSolutionpartnerStatus() == '1'){
                $customer = Mage::getModel('customer/customer');
                $customerList = $customer->getCollection()->addFieldTofilter('email',$model->getEmmail());
                $websiteId = $model->getWebsiteId() ? $model->getWebsiteId() : 1;
                $storeId = $model->getStoreId() ? $model->getStoreId() : 1;
                if(!count($customerList)){
                    $customer->setData('email', $model->getEmail())
                        ->setData('website_id', $websiteId)
                        ->setData('store_id', $storeId)
                        ->setData('group_id', 1)
                        ->setData('firstname', $model->getName())
                        ->setData('lastname', $model->getName())
                    ;
                    $customer->setPassword($customer->generatePassword());
                    try{
                        $customer->save()->setId(null);
                        $customer->sendNewAccountEmail('registered', '', $model->getStoreId());
                    }catch(Exception $e){
                        
                    }
                }
            }
            
            try {
                if ($model->getRegisteredDate() == NULL || $model->getUpdateTime() == NULL) {
                    $model->setRegisteredDate(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('solutionpartner')->__('Solution Partner was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('solutionpartner')->__('Unable to find Solution Partner to save')
        );
        $this->_redirect('*/*/');
    }
 
    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('solutionpartner/solutionpartner');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Item was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction()
    {
        $solutionpartnerIds = $this->getRequest()->getParam('solutionpartner');
        if (!is_array($solutionpartnerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select solution partner(s)'));
        } else {
            try {
                foreach ($solutionpartnerIds as $solutionpartnerId) {
                    $solutionpartner = Mage::getModel('solutionpartner/partner')->load($solutionpartnerId);
                    $solutionpartner->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                    count($solutionpartnerIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * mass change status for item(s) action
     */
    public function massStatusAction()
    {
        $solutionpartnerIds = $this->getRequest()->getParam('solutionpartner');
        if (!is_array($solutionpartnerIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select solution partner(s)'));
        } else {
            try {
                foreach ($solutionpartnerIds as $solutionpartnerId) {
                    Mage::getSingleton('solutionpartner/partner')
                        ->load($solutionpartnerId)
                        ->setSolutionpartnerStatus($this->getRequest()->getParam('solutionpartner_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($solutionpartnerIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName   = 'solutionpartner.csv';
        $content    = $this->getLayout()
                           ->createBlock('solutionpartner/adminhtml_solutionpartner_grid')
                           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'solutionpartner.xml';
        $content    = $this->getLayout()
                           ->createBlock('solutionpartner/adminhtml_solutionpartner_grid')
                           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('solutionpartner');
    }
}