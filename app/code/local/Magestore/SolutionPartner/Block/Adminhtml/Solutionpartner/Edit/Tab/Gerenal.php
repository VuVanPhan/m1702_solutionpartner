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
class Magestore_SolutionPartner_Block_Adminhtml_Solutionpartner_Edit_Tab_Gerenal extends Mage_Adminhtml_Block_Widget_Form
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
        
        if (Mage::getSingleton('adminhtml/session')->getSolutionPartnerData()) {
            $data = Mage::getSingleton('adminhtml/session')->getSolutionPartnerData();
            Mage::getSingleton('adminhtml/session')->setSolutionPartnerData(null);
        } elseif (Mage::registry('solutionpartner_data')) {
            $data = Mage::registry('solutionpartner_data')->getData();
        }
        $fieldset = $form->addFieldset('solutionpartner_form', array(
            'legend'=>Mage::helper('solutionpartner')->__('General information')
        ));

        $fieldset->addField('name', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Contact Name'),
            'class'         => 'required-entry',
            'required'      => true,
            'name'          => 'name',
        ));

        $fieldset->addField('company_name', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Company Name'),
            'class'         => 'required-entry',
            'required'      => true,
            'name'          => 'company_name',
        ));

        $fieldset->addField('company_logo', 'image', array(
            'label'         => Mage::helper('solutionpartner')->__('Company Logo'),
            'required'      => false,
            'name'          => 'company_logo',
        ));

        $fieldset->addField('website', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Website'),
            'class'         => 'required-entry',
            'required'      => true,
            'name'          => 'website',
        ));

        $fieldset->addField('email', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Email'),
            'class'         => 'required-entry validate-email',
            'required'      => true,
            'name'          => 'email',
        ));

        $fieldset->addField('phone', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Phone'),
            'class'         => 'required-entry validate-greater-than-zero',
            'required'      => true,
            'name'          => 'phone',
        ));

        $fieldset->addField('country', 'select', array(
            'label'         => Mage::helper('solutionpartner')->__('Country'),
            'required'      => false,
            'name'          => 'country',
            'values'         => Mage::helper('solutionpartner')->getCountryOption()
        ));

        $fieldset->addField('depscription', 'textarea', array(
            'label'         => Mage::helper('solutionpartner')->__('Depscription'),
            'class'         => 'required-entry',
            'required'      => true,
            'name'          => 'depscription',
        ));

        $fieldset->addField('certified_dev', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Certified Dev'),
            'class'         => 'required-entry validate-zero-or-greater validate-number',
            'required'      => true,
            'name'          => 'certified_dev',
        ));

        $fieldset->addField('industry', 'select', array(
            'label'         => Mage::helper('solutionpartner')->__('Industry'),
            'required'      => false,
            'name'          => 'industry',
            'values'        => Mage::helper('solutionpartner')->getIndustryOption()
        ));

        $fieldset->addField('project_year', 'text', array(
            'label'         => Mage::helper('solutionpartner')->__('Project Year'),
            'class'         => 'validate-zero-or-greater validate-number',
            'required'      => false,
            'name'          => 'project_year',
        ));

        $fieldset->addField('project_size', 'select', array(
            'label'         => Mage::helper('solutionpartner')->__('Project Size'),
            'required'      => false,
            'name'          => 'project_size',
            'values'        => Mage::helper('solutionpartner')->getProjectSizeOption()
        ));

        $fieldset->addField('hourly_rate', 'select', array(
            'label'         => Mage::helper('solutionpartner')->__('Hourly Rate'),
            'required'      => false,
            'name'          => 'hourly_rate',
            'values'        => Mage::helper('solutionpartner')->getHourlyRateOption()
        ));

        $fieldset->addField('solutionpartner_status', 'select', array(
            'label'         => Mage::helper('solutionpartner')->__('Status'),
            'name'          => 'solutionpartner_status',
            'values'        => Mage::getSingleton('solutionpartner/status')->getOptionHash(),
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}