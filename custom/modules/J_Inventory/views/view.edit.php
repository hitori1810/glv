<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    include_once("custom/include/utils/InventoryHelper.php");
    require_once('custom/include/_helper/class_utils.php');
    class J_InventoryViewEdit extends ViewEdit
    {
        public function __construct()
        {                      
            parent::ViewEdit();
        }
        public function display() {    
            global $app_list_strings, $db,$timedate; 
            if (!checkDataLockDate($timedate->to_db_date($this->date_create,false)))  { //not allowed edit when lock date
                SugarApplication::redirect('index.php?module=J_Inventory&action=index');
            }
            
            if($this->bean->type == '') {  
                if($_REQUEST['type']) {
                    $this->bean->type = $_REQUEST['type'];
                } else $this->bean->type = "Import";  
            }
            switch ($this->bean->type) {
                case "Import":
                    $this->bean->from_inventory_list = 'Accounts';
                    unset($app_list_strings['from_inventory_list']['Teams'] );
                    unset($app_list_strings['from_inventory_list']['TeamsParent'] );
                    unset($app_list_strings['to_inventory_list']['C_Teachers'] );                     
                    unset($app_list_strings['to_inventory_list']['Accounts'] );                     
                    unset($app_list_strings['to_inventory_list']['Contacts'] );                     
                    $function = 'getSuppliers';
                    break;
                case "Tranfer":
                    if ($this->bean->from_inventory_list == '') $this->bean->from_inventory_list = 'Teams';
                    unset($app_list_strings['from_inventory_list']['Accounts'] );
                    unset($app_list_strings['to_inventory_list']['Contacts'] );
                    if ($this->bean->from_inventory_list == 'Teams') {
                        $function = 'getCenters' ;
                    } else if ($this->bean->from_inventory_list == 'TeamsParent'){
                        $function = 'getParentCenters' ;
                    }
                    break;
                case "Sale":
                    $this->bean->from_inventory_list = 'Teams';
                    unset($app_list_strings['from_inventory_list']['Accounts'] );
                    unset($app_list_strings['from_inventory_list']['TeamsParent'] );
                    $app_list_strings['to_inventory_list'] = array(
                        'Contacts' => 'Student',
                    );
                    $function = 'getCenters' ;
                    break;
            }

            if(empty($this->bean->id)){
                $this->ss->assign('code_inventory', '<span style="color:red;font-weight:bold"> Auto-generate </span>');
            }else{//In Case edit
                $this->ss->assign('code_inventory', '<span style="color:red;font-weight:bold" id = "code">'.$this->bean->name.'</span>'); 
            }
            //----Custom option choose type from (center or supplier)----//     
            $html_from = InventoryHelper::getSelectOption($app_list_strings['from_inventory_list'], $this->bean->from_inventory_list,"from_inventory_list","from_inventory_list",'','list_strings');
            $object_from_list = InventoryHelper::$function();  
            if(!isset($this->bean->id) || empty($this->bean->id) || $this->bean->from_object_id == ''){
                $this->bean->from_object_id = $GLOBALS['current_user']->team_id;
            }
            $html_from.= InventoryHelper::getSelectOption($object_from_list, $this->bean->from_object_id, 'from_object_id','from_object_id', 'margin-left: 20px; width:200px;');
            $this->ss->assign("html_from",$html_from);
            //----End----//              

            //----Custom option choose to (center or teacher or corp)----//
            $html_to = InventoryHelper::getSelectOption($app_list_strings['to_inventory_list'], $this->bean->to_inventory_list,"to_inventory_list","to_inventory_list",'','list_strings');
            $html_to.= '<select style="margin-left: 20px; width:200px;" class="to_object_id" name="to_object_id" id="to_object_id">';
            $html_to.= '<option value="">--None--</option>'; 
            $html_to.= '</select>';             
            $this->ss->assign("html_to",$html_to);
            $this->ss->assign("to_object_key",$this->bean->to_object_id);
            //----End----//      

            //----Custom Detail Inventory----//
            $book_list = InventoryHelper::getBookList();
            $html_tpl   = getHtmlAddRow(array(),$book_list,true); 
            $html       = getHtmlAddRow(array(),$book_list,false);

            $details = InventoryHelper::getInventoryLine($this->bean->id);       
            //In Case create new
            if(!empty($this->bean->id)){
                $html = '';
                foreach($details as $detail){                      
                    $html .= getHtmlAddRow($detail,$book_list, false);  
                }
            }    
            $this->ss->assign('html_tpl',$html_tpl);
            $this->ss->assign('html',$html);
            parent::display();
        }
    }
    function getHtmlAddRow($detail, $book_list, $showing){    
        if($showing)
            $display = "display:none;";
        $tpl_addrow.="<tr class='tr_template'  style='$display'>";

        $tpl_addrow.='<td>';  
        $tpl_addrow.= '<select class="list_book" name="list_book[]"><option value=""></option>';
        $tpl_addrow.= InventoryHelper::getBookSelectOption($book_list, $detail['book_id']);  
        $tpl_addrow.= '</select>';
        $tpl_addrow.= '</td>'; 

        $tpl_addrow.='<td>
        <input type=text name="quantity[]" class="quantity" style="text-align: right;width: 60px;" value="'.(isset($detail['quantity'])?$detail['quantity']:1).'"/>
        </td>';

        $tpl_addrow.='<td style="text-align:center">
        <label name="part_no" class="part_no">'.$detail['book_code'].'</label>
        </td>';

        $tpl_addrow.='<td style="text-align:center">
        <label name="unit" class="unit">'.$detail['book_unit'].'</label>
        </td>';

        $tpl_addrow.='<td style="">
        <input style="text-align:right" type=text name="price[]" readonly="true" class="price input_readonly" value="'.format_number($detail['price'] + 0,0,0).'"/> 
        </td>';

        $tpl_addrow.='<td style="">
        <input style="text-align:right" type=text name="amount[]" readonly="true" class="amount input_readonly" value="'.format_number($detail['amount'] + 0,0,0).'"/>
        </td>';

        $tpl_addrow.='<td>
        <textarea id="remark" name="remark[]" rows="2" cols="20"  value="'.$detail['remark'].'">'.$detail['remark'].'</textarea>
        </td>';

        $tpl_addrow.='<td style="margin-left: 30px;">
        <button type="button" class="btn btn-danger btnRemove">Remove</button>
        </td>';     
        $tpl_addrow.='</tr>';
        return $tpl_addrow;
    }
