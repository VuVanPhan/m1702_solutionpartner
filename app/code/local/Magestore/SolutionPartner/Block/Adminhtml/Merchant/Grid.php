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
 * Solutionpartner Grid Block
 * 
 * @category    Magestore
 * @package     Magestore_SolutionPartner
 * @author      Magestore Developer
 */
class Magestore_SolutionPartner_Block_Adminhtml_Merchant_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('merchantGrid');
        $this->setDefaultSort('merchant_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Magestore_SolutionPartner_Block_Adminhtml_Solutionpartner_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('solutionpartner/merchant')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Magestore_SolutionPartner_Block_Adminhtml_Solutionpartner_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('merchant_id', array(
            'header'    => Mage::helper('solutionpartner')->__('Merchant Id'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'merchant_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('solutionpartner')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('solutionpartner')->__('Email'),
            'align'     =>'left',
            'index'     => 'email',
            'renderer' => 'solutionpartner/adminhtml_solutionpartner_renderer_customer',
        ));

        $this->addColumn('country', array(
            'header' => Mage::helper('solutionpartner')->__('Country'),
            'align' => 'left',
            'index' => 'country',
            'type' => 'options',
            'options' => Mage::helper('solutionpartner')->getCountryList(),
        ));

        $this->addColumn('industry', array(
            'header' => Mage::helper('solutionpartner')->__('Industry'),
            'align' => 'left',
            'index' => 'industry',
            'type' => 'options',
            'options' => Mage::helper('solutionpartner')->getIndustryList(),
        ));

        $this->addColumn('budget', array(
            'header' => Mage::helper('solutionpartner')->__('Budget'),
            'align' => 'center',
            'index' => 'budget',
            'type' => 'options',
            'options' => Mage::helper('solutionpartner')->getProjectSizeList(),
        ));

        $this->addColumn('registered_date', array(
            'header' => Mage::helper('solutionpartner')->__('Registered Date'),
            'align' => 'left',
            'index' => 'registered_date',
            'type' => 'datetime'
        ));

//        $this->addColumn('status', array(
//            'header'    => Mage::helper('solutionpartner')->__('Status'),
//            'align'     => 'left',
//            'width'     => '80px',
//            'index'     => 'status',
//            'type'        => 'options',
//            'options'     => array(
//                1 => 'Enabled',
//                2 => 'Disabled',
//            ),
//        ));

        $this->addColumn('action',
            array(
                'header'    =>    Mage::helper('solutionpartner')->__('Action'),
                'width'        => '100',
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('solutionpartner')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('solutionpartner')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('solutionpartner')->__('XML'));

        return parent::_prepareColumns();
    }
    
    /**
     * prepare mass action for this grid
     *
     * @return Magestore_SolutionPartner_Block_Adminhtml_Solutionpartner_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('solutionpartner_id');
        $this->getMassactionBlock()->setFormFieldName('solutionpartner');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'        => Mage::helper('solutionpartner')->__('Delete'),
            'url'        => $this->getUrl('*/*/massDelete'),
            'confirm'    => Mage::helper('solutionpartner')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('solutionpartner/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('solutionpartner_status', array(
            'label'=> Mage::helper('solutionpartner')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'solutionpartner_status',
                    'type'    => 'select',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('solutionpartner')->__('Status'),
                    'values'=> $statuses
                ))
        ));
        return $this;
    }
    
    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _filterTotalProductsCallback($collection, $column)
    {
        $filter = $column->getFilter()->getValue();
        if (isset($filter['from']) && $filter['from']) {
            $collection->getSelect()->having('COUNT(order.entity_id) >= ?', $filter['from']);
        }
        if (isset($filter['to']) && $filter['to']) {
            $collection->getSelect()->having('COUNT(order.entity_id) <= ?', $filter['to']);
        }
    }
}