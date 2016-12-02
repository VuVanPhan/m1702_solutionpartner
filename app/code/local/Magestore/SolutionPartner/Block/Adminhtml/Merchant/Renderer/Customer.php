<?php 
class Magestore_SolutionPartner_Block_Adminhtml_Merchant_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/* Render Grid Column*/
	public function render(Varien_Object $row) 
	{
		$html = null;
		$customer = $this->_getSolurionPartner($row->getSolutionpartnerId())->getCustomer();
		if($customer->getId()){
			$html = '<a href="'.$this->getUrl('adminhtml/customer/edit',array('id'=>$customer->getId())).'" target="_blank">'
						 .$row->getEmail().'</a>';
		} else {
			$html = $row->getEmail();
		}

		return $html;
	}
	
	protected function _getSolurionPartner($merchantId)
	{
		if(!Mage::registry('partner_'.$merchantId)){
			$partner = Mage::getModel('solutionpartner/partner')->load($merchantId);
			Mage::register('solutionpartner_'.$merchantId,$partner);
		}
		return Mage::registry('solutionpartner_'.$merchantId);
	}
	
}