<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class J_PaymentDetailViewEdit extends ViewEdit
{
    public function display(){
        $card_type = '<select name="card_type" style="" id="card_type"><optgroup label="Card Type">';
        foreach($GLOBALS['app_list_strings']['card_type_payments_list'] as $key=>$value){
            if(!empty($GLOBALS['app_list_strings']['card_rate'][$key]))
                $rate = $GLOBALS['app_list_strings']['card_rate'][$key];
            else $rate = 0;
            $se_ed = '';
            if($this->bean->card_type == $key)
                $se_ed = "selected";
            $card_type .= "<option $se_ed label='$value' rate='$rate' value='$key'>$value</option>";     
        }
        $card_type .= '</optgroup>';
        $card_type .= '<optgroup label="Bank Type">';
        foreach($GLOBALS['app_list_strings']['bank_name_list'] as $key=>$value){
            if(!empty($GLOBALS['app_list_strings']['bank_rate'][$key]))
                $rate = $GLOBALS['app_list_strings']['bank_rate'][$key];
            else $rate = 0;
            $se_ed = '';
            if($this->bean->card_type == $key)
                $se_ed = "selected";
            $card_type .= "<option $se_ed label='$value' rate='$rate' value='$key'>$value</option>";     
        }
        $card_type .= '</optgroup>';
        $card_type .= '</select>';
        $this->ss->assign('card_type',$card_type);

        if(empty($this->bean->serial_no)){
            $this->bean->serial_no = 'AA/15P';   
        }

        parent::display();
    }
}