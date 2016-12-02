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
class Magestore_SolutionPartner_Adminhtml_MerchantController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_SolutionPartner_Adminhtml_SolutionpartnerController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('solutionpartner/merchant')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Items Manager'),
                Mage::helper('adminhtml')->__('Item Manager')
            );
        $this->getLayout()->getBlock('head')->setTitle($this->__('Merchant Manager'));
        return $this;
    }
 
    /**
     * index action
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $merchantId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('solutionpartner/merchant')->load($merchantId);

        if ($model->getId() || $merchantId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('merchant_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('solutionpartner/merchant');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Merchant Manager'),
                Mage::helper('adminhtml')->__('Merchant Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Merchant Partner News'),
                Mage::helper('adminhtml')->__('Merchant Partner News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('solutionpartner/adminhtml_merchant_edit'))
                ->_addLeft($this->getLayout()->createBlock('solutionpartner/adminhtml_merchant_edit_tabs'));

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
            if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                try {
                    /* Starting upload */    
                    $uploader = new Varien_File_Uploader('filename');
                    
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
                    $result = $uploader->save($path, $_FILES['filename']['name'] );
                    $data['filename'] = $result['file'];
                } catch (Exception $e) {
                    $data['filename'] = $_FILES['filename']['name'];
                }
            }

            $model = Mage::getModel('solutionpartner/merchant');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getRegisteredDate() == NULL || $model->getUpdateTime() == NULL) {
                    $model->setRegisteredDate(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('solutionpartner')->__('Item was successfully saved')
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
            Mage::helper('solutionpartner')->__('Unable to find item to save')
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
                $model = Mage::getModel('solutionpartner/merchant');
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
        $merchantIdIds = $this->getRequest()->getParam('merchant');
        if (!is_array($merchantIdIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($merchantIdIds as $merchantId) {
                    $solutionpartner = Mage::getModel('solutionpartner/merchant')->load($merchantId);
                    $solutionpartner->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted',
                    count($merchantIdIds))
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
        $merchantIds = $this->getRequest()->getParam('merchant');
        if (!is_array($merchantIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($merchantIds as $merchantId) {
                    Mage::getSingleton('solutionpartner/merchant')
                        ->load($merchantId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($merchantIds))
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
        $fileName   = 'merchant.csv';
        $content    = $this->getLayout()
                           ->createBlock('solutionpartner/adminhtml_merchant_grid')
                           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'merchant.xml';
        $content    = $this->getLayout()
                           ->createBlock('solutionpartner/adminhtml_merchant_grid')
                           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('merchant');
    }
}