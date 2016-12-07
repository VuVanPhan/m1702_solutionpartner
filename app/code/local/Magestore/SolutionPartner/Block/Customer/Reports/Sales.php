<?php
class Magestore_SolutionPartner_Block_Customer_Reports_Sales extends Mage_Core_Block_Template
{
    protected function _construct() {
        parent::_construct();
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();

//        $collection = Mage::getModel('membership/purchasinglog')->getCollection()
//            ->addFieldToFilter('main_table.customer_id', $customerId)
//            ->setOrder('log_id', 'DESC');
//        die('fdgfd');
//        $collection->getSelect()->group('main_table.order_id')->columns(array('order_discount'=>'sum(main_table.partner_discount)'));
//        $collection->getSelect()->joinLeft(array('order'=>$collection->getTable('sales/order')), 'main_table.order_id = order.entity_id',
//            array('subtotal'=>'subtotal', 'created_at'=>'created_at', 'cus_id'=>'customer_id', 'order_status'=>'status', 'increment_id' => 'increment_id',));
//        Mage::dispatchEvent('solutionpartner_prepare_sales_collection', array(
//            'collection' => $collection,
//        ));
//        $collection->setIsGroupCountSql(true);
//        $this->setCollection($collection);
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('solutionpartner_sales_grid');
    }

    public function _prepareLayout() {
        parent::_prepareLayout();
//        die('dfg');
        $pager = $this->getLayout()->createBlock('page/html_pager', 'sales_pager')
            ->setTemplate('solutionpartner/html/pager.phtml');
//            ->setCollection($this->getCollection());
        $this->setChild('sales_pager', $pager);

        $grid = $this->getLayout()->createBlock('solutionpartner/customer_grid', 'solutionpartner_sales_grid');

        // prepare column
        $grid->addColumn('log_id', array(
            'header' => $this->__('No.'),
            'index' => 'log_id',
            'align' => 'left',
            'render' => 'getNoNumber',
            'width' => '20px',
        ));

        $grid->addColumn('order_id', array(
            'header' => $this->__('Order Id'),
            'index' => 'increment_id',
            'render' => 'getOrder',
            'width' => '40px',
            'searchable'    => true,
        ));

        $grid->addColumn('product_id', array(
            'header' => $this->__('Extension(s)'),
            'index' => 'product_id',
            'align' => 'left',
            'render' => 'getFrontendProductHtmls',
            // 'searchable'    => true,
        ));

        $grid->addColumn('subtotal', array(
            'header' => $this->__('Order Total'),
            'align' => 'left',
            'index' => 'subtotal',
            'type' => 'baseprice',
            'width' => '30px',
            // 'searchable'    => true,
        ));

        $grid->addColumn('discount', array(
            'header' => $this->__('Saved'),
            'align' => 'left',
            'type' => 'baseprice',
            'index' => 'order_discount',
            'width' => '30px',
            // 'render' => 'getDiscount',
        ));

        $grid->addColumn('created_at', array(
            'header' => $this->__('Created At'),
            'index' => 'created_at',
            'align' => 'left',
            'format' => 'medium',
            'type' => 'date',
            'searchable'    => true,
            'width' => '100px',
        ));

        $grid->addColumn('status', array(
            'header' => $this->__('Status'),
            'align' => 'left',
            'index' => 'status',
            'filter_index' => 'main_table.status',
            'width' => '55px',
            'type' => 'options',
            'options' => array(
                'pending' => $this->__('Pending'),
                'processing' => $this->__('Processing'),
                'complete' => $this->__('Complete'),
                'closed' => $this->__('Closed'),
                'canceled' => $this->__('Canceled'),
                'holded' => $this->__('On Hold')
            ),
            'searchable'    => true,
        ));

        $this->setChild('solutionpartner_sales_grid', $grid);
        return $this;
    }

    protected function _toHtml()
    {
        $this->getChild('solutionpartner_sales_grid')->setCollection($this->getCollection());
        return parent::_toHtml();
    }

    public function getNoNumber($row)
    {
        return sprintf('#%d', $row->getId());
    }

    public function getOrder($row)
    {
        if($row->getOrderId()){
            $orderIncrement = Mage::getSingleton('sales/order')->load($row->getOrderId())->getIncrementId();
            return sprintf('
                <a href="%s" title="%s">%s</a>',
                $this->getUrl('sales/order/view/', array('_current'=>true, 'order_id' => $row->getOrderId())),
                Mage::helper('partner')->__('View Order Detail'),
                $orderIncrement
            );
        }else{
            return sprintf('%s', $row->getOrderId());
        }
    }

    public function getFrontendProductHtmls($row)
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $sales = Mage::getModel('membership/purchasinglog')->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('order_id', $row->getOrderId())
            ->addFieldToSelect('product_id');
        foreach ($sales as $order) {
            $productId = $order->getProductId();
            $product = Mage::getModel('catalog/product')->load($productId);
            $productName = $product->getName();
            $productUrl = $product->getProductUrl();
            if($product->getId())
                $productHtmls[] = '<a href="' . $productUrl . '" title="' . Mage::helper('partner')->__('View Product Detail') . '">' . $productName . '</a>';
            else
                if($productNames)
                    $productHtmls[] = $productNames[$i];
            $i++;
        }
        return implode('<br />', $productHtmls);
    }
}