<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class hookInventory{   
        function handleBeforeSave(&$bean, $event, $arguments){
            if ($_POST["module"] == "J_Inventory" && $_POST['action'] == 'Save'){                
                $bean->to_teacher_id = '';
                $bean->to_student_id = '';
                $bean->to_corp_id = '';
                $bean->to_team_id = '';
                $bean->from_supplier_id = '';
                $bean->from_team_id = '';                 

                if($bean->from_inventory_list == 'Accounts') {
                    $bean->from_supplier_id = $bean->from_object_id; 
                }else if($bean->from_inventory_list == 'Teams') {
                    $bean->from_team_id = $bean->from_object_id;                  
                } 

                if($bean->to_inventory_list == 'Accounts') {
                    $bean->to_corp_id = $bean->to_object_id; 
                }else if($bean->to_inventory_list == 'Teams') {
                    $bean->to_team_id = $bean->to_object_id;                  
                }else if($bean->to_inventory_list == 'Contacts') {
                    $bean->to_student_id = $bean->to_object_id; 
                }else if($bean->to_inventory_list == 'C_Teachers') {
                    $bean->to_teacher_id = $bean->to_object_id;                 
                }     
            }
        }            
        function handleAfterSave(&$bean, $event, $arguments){
            if ($_POST["module"] == "J_Inventory" && $_POST['action'] == 'Save'){
                $bean->deleteDetail();

                $price = $_POST['price'];
                $quantity = $_POST['quantity'];
                $remark = $_POST['remark'];
                $book_id = $_POST['list_book'];
                $amount = $_POST['amount'];
                for($i = 1; $i< count($price); $i++){
                    $detail=new J_Inventorydetail();
                    $detail->inventory_id = $bean->id;
                    $detail->book_id= $book_id[$i];
                    $detail->quantity= $quantity[$i];
                    $detail->price= $price[$i];
                    $detail->amount= $amount[$i];
                    $detail->remark= $remark[$i];

                    $detail->team_id = $bean->team_id;
                    $detail->team_set_id = $bean->team_set_id;
                    $detail->assigned_user_id = $bean->assigned_user_id;

                    $detail->save();
                }    
            }
            elseif ($_POST["module"] == "J_Payment"){
                if ($bean->deleted == 1) {
                    $bean->deleteDetail();      
                }
            }
        }

        function handleBeforeDelete(&$bean, $event, $arguments){
            // delete payment book/gift
            $payment = BeanFactory::getBean("J_Payment", $bean->j_payment_j_inventory_1j_payment_ida); 
            if ($payment->deleted == 0){
                $payment->deleted=1;
                $payment->save();
            }
            //delete detail inventory relationship
            $bean->deleteDetail();      
        }

        ///to mau id va status Quyen.Cao
        function listViewColorInven(&$bean, $event, $arguments){
            if($_REQUEST['action']=='Popup'){

            }else{
                $bean->name = '<span class="textbg_blue">'.$bean->name.'</span>';   
            }


            switch ($bean->status) {
                case "Draft":
                    $colorClass = "textbg_green";
                    break;
                case "Un Confirmed":
                    $colorClass = "textbg_orange";
                    break;
                case "Confirmed":
                $colorClass = "textbg_crimson";
                break;
            } 
            $bean->status = '<span class="'.$colorClass.'">'.$bean->status.'</span>';  

            switch ($bean->type) {
                case "Tranfer":
                    $bean->type = '<span class="textbg_violet">'.$bean->type.'</span>';
                    break;
                case "Import":
                    $bean->type = '<span class="textbg_bluelight">'.$bean->type.'</span>';
                    break;
                case "Sale":
                    $bean->type = '<span class="textbg_redlight">'.$bean->type.'</span>';
                    break;
            }

        }

        function showDetail(&$bean, $event, $arguments){
            $bean->html_detail = $bean->getListViewDetail();

            $bean->from_inventory_list = "<b>".$GLOBALS['app_list_strings']['from_inventory_list'][$bean->from_inventory_list].": </b>"
            .$bean->getFromObject();
            $bean->to_inventory_list = "<b>".$GLOBALS['app_list_strings']['to_inventory_list'][$bean->to_inventory_list].": </b>"
            .$bean->getToObject();
        }

        function autoCode(&$bean, $event, $arg) {
            if($bean->name == '') {
                $typecode = "";
                $centercode = "";
                switch ($bean->type) {
                    case "Tranfer":
                        if($bean->to_inventory_list == 'Teams') $typecode = 'MT';
                        else $typecode = 'MI';
                        $centercode_id = $bean->from_object_id;
                        break;
                    case "Import":
                        $typecode = 'MR' ;
                        $centercode_id = $bean->to_object_id; 
                        break;
                    case "Sale":
                        $typecode = 'MI' ;
                        $centercode_id = $bean->from_object_id;
                        break;
                }
                $centercode =  $GLOBALS['db']->getOne("SELECT short_name FROM teams WHERE id = '{$centercode_id}'");
                $date_time = date('my',strtotime($bean->date_create));
                $auto_code = $GLOBALS['db']->getOne("SELECT MAX(SUBSTR(name FROM (LENGTH(name)-3) FOR 4))
                    FROM j_inventory 
                    WHERE MONTH(date_create) = '".date('m',strtotime($bean->date_create))."'
                    AND YEAR(date_create) = '".date('Y',strtotime($bean->date_create))."'") + 1;
                if($auto_code <= 9) $auto_code = "000".$auto_code;
                else if($auto_code <= 99) $auto_code = "00".$auto_code;
                else if($auto_code <= 999) $auto_code = "0".$auto_code;

                    $bean->name =  $centercode."-".$typecode.$date_time.$auto_code;
            }
        }
    }
?>