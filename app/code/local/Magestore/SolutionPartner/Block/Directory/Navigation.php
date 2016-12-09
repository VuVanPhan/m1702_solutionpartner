<?php
class Magestore_SolutionPartner_Block_Directory_Navigation extends Mage_Core_Block_Template
{
    public function getCountryOption()
    {
        $html_option = '';
        $option = Mage::helper('solutionpartner')->getCountryList();
        foreach($option as $code=>$value){
//            $selected = ($code == $this->getFormEditData()->getCountry())? 'selected' : '';
//            $html_option .= '<option value="'.$code.'" '.$selected.'>'.$value.'</option>';
            $html_option .= '<option value="'.$code.'">'.$value.'</option>';
        }
        return $html_option;
    }

}