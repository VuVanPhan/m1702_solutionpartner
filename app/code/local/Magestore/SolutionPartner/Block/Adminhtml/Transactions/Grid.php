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
class Magestore_SolutionPartner_Block_Adminhtml_Transactions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('transactionsGrid');
        $this->setDefaultSort('increment_id');
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
        $collection = Mage::getModel('solutionpartner/partner')->getCollection();
        $collection->getSelect()->joinLeft(
            array('order' => $collection->getTable('sales/order')),
            'main_table.email = order.customer_email AND order.status = "complete"',
            array(
                'order_id' => 'entity_id',
                'order_status' => 'order.status',
                'number_qtys' => 'count(order.entity_id)'
            ))
            ->group('main_table.solutionpartner_id');
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
        $this->addColumn('increment_id', array(
            'header'    => Mage::helper('solutionpartner')->__('Order Id'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'increment_id',
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
//            'renderer' => 'solutionpartner/adminhtml_solutionpartner_renderer_customer',
        ));

        $this->addColumn('payment_method', array(
            'header' => Mage::helper('solutionpartner')->__('Payment Method'),
            'align' => 'left',
            'index' => 'payment_method',
        ));

        $this->addColumn('country', array(
            'header' => Mage::helper('solutionpartner')->__('Country'),
            'align' => 'left',
            'index' => 'country',
            'type' => 'options',
            'options' => Mage::helper('solutionpartner')->getCountryList(),
        ));

        $this->addColumn('order_total', array(
            'header' => Mage::helper('solutionpartner')->__('Order Total'),
            'align' => 'left',
            'index' => 'order_total',
        ));

        $this->addColumn('created_date', array(
            'header' => Mage::helper('solutionpartner')->__('Created Date'),
            'align' => 'left',
            'index' => 'created_date',
            'type' => 'datetime'
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('solutionpartner')->__('Order Status'),
            'align' => 'left',
            'index' => 'status',
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
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('solutionpartner')->__('Change status'),
            'url'    => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name'    => 'status',
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