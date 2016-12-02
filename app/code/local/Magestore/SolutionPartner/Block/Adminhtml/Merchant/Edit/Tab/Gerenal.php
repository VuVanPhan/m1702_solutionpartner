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
 * Solutionpartner Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @author      Magestore Developer
 */
class Magestore_SolutionPartner_Block_Adminhtml_Merchant_Edit_Tab_Gerenal extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_SolutionPartner_Block_Adminhtml_Solutionpartner_Edit_Tab_Gerenal
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getMerchantData()) {
            $data = Mage::getSingleton('adminhtml/session')->getMerchantData();
            Mage::getSingleton('adminhtml/session')->setMerchantData(null);
        } elseif (Mage::registry('merchant_data')) {
            $data = Mage::registry('merchant_data')->getData();
        }
        $fieldset = $form->addFieldset('merchant_form', array(
            'legend'=>Mage::helper('solutionpartner')->__('Merchant information')
        ));

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $wysiwygConfig->addData(array(
            'add_variables'				=> false,
            'plugins'					=> array(),
            'widget_window_url'			=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
            'directives_url'			=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'directives_url_quoted'		=> preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
            'files_browser_window_url'	=> Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
        ));

        $fieldset->addField('name', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Name'),
            'class'         => 'required-entry',
            'required'      => true,
            'name'          => 'name',
        ));

        $fieldset->addField('email', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Email'),
            'class'         => 'required-entry validate-email',
            'required'      => true,
            'name'          => 'email',
        ));

        $fieldset->addField('phone', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Phone'),
            'class'         => 'validate-greater-than-zero',
            'required'      => false,
            'name'          => 'phone',
        ));

        $fieldset->addField('company', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Company'),
            'required'      => false,
            'name'          => 'company',
        ));

        $fieldset->addField('address', 'textarea', array(
            'label'         => Mage::helper('solutionpartner')->__('Address'),
            'required'      => false,
            'name'          => 'address',
        ));

        $fieldset->addField('country', 'select', array(
            'label'         => Mage::helper('solutionpartner')->__('Country'),
            'required'      => false,
            'name'          => 'country',
            'options'       => Mage::helper('solutionpartner')->getCountryList(),
        ));

        $fieldset->addField('service', 'select', array(
            'label'         => Mage::helper('solutionpartner')->__('Service'),
            'required'      => false,
            'name'          => 'service',
            'values'        => Mage::helper('solutionpartner')->getServiceOption()
        ));

        $fieldset->addField('budget', 'select', array(
            'label'         => Mage::helper('solutionpartner')->__('Budget'),
            'required'      => false,
            'name'          => 'budget',
            'values'        => Mage::helper('solutionpartner')->getProjectSizeOption()
        ));

        $fieldset->addField('description', 'editor', array(
            'label'         => Mage::helper('solutionpartner')->__('Description:'),
            'class'         => 'required-entry',
            'required'      => true,
            'name'          => 'description',
        ));

//        $image_calendar = Mage::getBaseUrl('skin') . 'adminhtml/default/default/images/grid-cal.gif';
//
//        $fieldset->addField('registered_date', 'date', array(
//            'label'        => Mage::helper('solutionpartner')->__('Registered Date:'),
//            'class'        => 'required-entry',
//            'format' => 'yyyy-MM-dd',
//            'required' => true,
//            'image' => $image_calendar,
//            'name' => 'registered_date',
//            'time' => true,
//        ));
//
//        $fieldset->addField('update_time', 'date', array(
//            'label'        => Mage::helper('solutionpartner')->__('Update Time:'),
//            'class'        => 'required-entry',
//            'format' => 'yyyy-MM-dd',
//            'required' => false,
//            'image' => $image_calendar,
//            'name' => 'update_time',
//            'time' => true,
//        ));

//        $fieldset->addField('status', 'select', array(
//            'label'        => Mage::helper('solutionpartner')->__('Status:'),
//            'name'        => 'status',
//            'values'    => Mage::getSingleton('solutionpartner/status')->getOptionHash(),
//        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}