<?php
class Magestore_SolutionPartner_Block_Adminhtml_Solutionpartner_Renderer_Tab_Order
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /* Render Grid Column*/
    public function render(Varien_Object $row)
    {
        $html = '<a href="'.$this->getUrl('adminhtml/sales_order/view',array('order_id'=>$row->getEntityId())).'" target="_blank">'
            .$row->getIncrementId().'</a>';

        return $html;
    }
}