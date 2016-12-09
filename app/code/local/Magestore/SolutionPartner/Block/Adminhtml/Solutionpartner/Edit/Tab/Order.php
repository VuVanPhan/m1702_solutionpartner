<?php

class Magestore_SolutionPartner_Block_Adminhtml_Solutionpartner_Edit_Tab_Order extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('entity_id');
//        $this->setId('recurring_profile_orders')
//            ->setUseAjax(true)
//            ->setSkipGenerateContent(true);
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        // get collection order by customer id
        $collections = Mage::getResourceModel('sales/order_grid_collection');
        $collections->addFieldToFilter('customer_id',$this->getSolutionpartner()->getCustomerOrderIds());
//        $collection->addFieldToFilter('status', array('closed', 'complete'));

        foreach ($collections as $collection)
        {
            // get detail order by increment id
            $order = Mage::getModel('sales/order')->load($collection->getIncrementId(), 'increment_id');
            $orderItems = $order->getItemsCollection();
            $array = array();
            foreach ($orderItems as $item){
                $array[] = $item->getName();
            }

            $collection->setProductName(implode(' - ', $array));
        }

        $this->setCollection($collections);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
            'renderer'  => 'solutionpartner/adminhtml_solutionpartner_renderer_tab_order',
        ));

        $this->addColumn('product_name', array(
            'header' => Mage::helper('sales')->__('Product Name'),
            'index' => 'product_name',
            'type'  => 'text',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));


        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/placedorders', array('_current'=>true,'id'=>$this->getRequest()->getParam('id')));
    }

    public function getSolutionPartner()
    {
        if(!$this->hasData('solutionpartner')){
            $partner = Mage::getModel('solutionpartner/partner')->load($this->getRequest()->getParam('id'));
            $this->setData('solutionpartner',$partner);
        }
        return $this->getData('solutionpartner');
    }
}