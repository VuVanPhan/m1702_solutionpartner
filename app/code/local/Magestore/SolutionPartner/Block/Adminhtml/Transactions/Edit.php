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
 * Solutionpartner Edit Block
 * 
 * @category     Magestore
 * @package     Magestore_SolutionPartner
 * @author      Magestore Developer
 */
class Magestore_SolutionPartner_Block_Adminhtml_Merchant_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'solutionpartner';
        $this->_controller = 'adminhtml_merchant';
        
        $this->_updateButton('save', 'label', Mage::helper('solutionpartner')->__('Save Merchant'));
        $this->_updateButton('delete', 'label', Mage::helper('solutionpartner')->__('Delete Merchant'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('merchant_content') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'merchant_content');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'merchant_content');
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    
    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('merchant_data')
            && Mage::registry('merchant_data')->getId()
        ) {
            return Mage::helper('solutionpartner')->__("Edit Merchant '%s'",
                                                $this->htmlEscape(Mage::registry('merchant_data')->getTitle())
            );
        }
        return Mage::helper('solutionpartner')->__('Add Merchant');
    }
}