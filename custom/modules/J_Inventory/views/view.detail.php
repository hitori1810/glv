<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    require_once('include/MVC/View/views/view.detail.php');
       include_once("custom/include/utils/InventoryHelper.php");
    class J_InventoryViewDetail extends ViewDetail {

        function J_InventoryViewDetail(){
            parent::ViewDetail();
        }
        function display() {
            global $mod_strings, $app_list_strings;
            //get from center or supplier
            $objectFrom = BeanFactory::getBean($this->bean->from_inventory_list,$this->bean->from_object_id);
            $html_from = "<div><span id = 'from_inventory_list'> <b>".$app_list_strings['from_inventory_list'][$this->bean->from_inventory_list]."</b> : </span>
            <a href='index.php?module=".$this->bean->from_inventory_list."&action=DetailView&record=".$this->bean->from_object_id."' id = 'from_object_id'>".$objectFrom->name."</a>
            </div>";            
                $this->ss->assign('html_from',$html_from);  
           
           $objectTo = BeanFactory::getBean($this->bean->to_inventory_list,$this->bean->to_object_id);
            $html_to = "<div><span id = 'from_inventory_list'> <b>".$app_list_strings['from_inventory_list'][$this->bean->to_inventory_list]."</b> : </span>
            <a href='index.php?module=".$this->bean->to_inventory_list."&action=DetailView&record=".$this->bean->to_object_id."' id = 'from_object_id'>".$objectTo->name."</a>
            </div>";            
                $this->ss->assign('html_to',$html_to);  
           
            $payment_button="";
            //if type is export, get to list
            if($this->bean->to_inventory_list == "Student"){
                $payment = BeanFactory::getBean("J_Payment",$this->bean->j_payment_j_inventory_1j_payment_ida);
                // Show button "Go to payment"
                $onClick = "window.open(\"index.php?module=J_Payment&action=DetailView&record=".$payment->id."\",\"_blank\");";
                $payment_button = "<input type='button' title='Go To Payment' name='payment_button' id='payment_button' value='Go To Payment' onclick='".$onClick."'></input>";
            }
            
            $this->ss->assign('PAYMENT_BUTTON',$payment_button); 
            
            
            //get detail             
             //$book_list = InventoryHelper::getBookList();           
            $details = InventoryHelper::getInventoryLine($this->bean->id);
            //create table
            $html='<table class="td_alt">';
            $html.='<thead>';
            $html.='<tr>';
             $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_PART'].'</b></th>';
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_QUANTITY'].'</b></th>';            
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_PART_NO'].'</b></th>';
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_UNIT'].'</b></th>';
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_PRICE'].'</b></th>';
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_AMOUNT'].'</b></th>';
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_REMARK'].'</b></th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            //foreach array detail have just got from relationship fill value in detailview
            foreach($details as $detail){
                $html.='<tr>';
                $html.='<td style="text-align: center;">'.$detail['book_name'].'</td>';
                $html.='<td style="text-align: center;">'.$detail['quantity'].'</td>';          
                $html.='<td style="text-align: center;">'.$detail['book_code'].'</td>';
                $html.='<td style="text-align: center;">'.$detail['book_unit'].'</td>';
                $html.='<td style="text-align: center;">'.format_number($detail['price']+0,0,0).'</td>';
                $html.='<td style="text-align: center;">'.format_number($detail['amount']+0,0,0).'</td>';
                $html.='<td style="text-align: center;">'.$detail['remark'].'</td> </tr>';
            }
            $html.='</tbody>';
            $html.='</table>';                           
            $this->ss->assign('html',$html);
            $this->ss->assign('MOD',$mod_strings);

            //custom export detail to excel
            if($this->bean->type=="Export"){
                $EXPORT_DETAIL='<input class="button" type="submit" value="'.$GLOBALS['mod_strings']['LBL_EXPORT_DETAIL'].'" id="export"></input>';
                $this->ss->assign('EXPORT_DETAIL',$EXPORT_DETAIL);
            }
            $this->ss->assign('code_inven', '<span class="textbg_blue">'.$this->bean->name.'</span>'); 
            parent::display();
        }
    }
?>