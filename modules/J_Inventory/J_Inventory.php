<?PHP
    /*********************************************************************************
    * By installing or using this file, you are confirming on behalf of the entity
    * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
    * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
    * http://www.sugarcrm.com/master-subscription-agreement
    *
    * If Company is not bound by the MSA, then by installing or using this file
    * you are agreeing unconditionally that Company will be bound by the MSA and
    * certifying that you have authority to bind Company accordingly.
    *
    * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
    ********************************************************************************/

    /**
    * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
    */
    require_once('modules/J_Inventory/J_Inventory_sugar.php');
    include_once("custom/include/utils/InventoryHelper.php");
    class J_Inventory extends J_Inventory_sugar {

        /**
        * This is a depreciated method, please start using __construct() as this method will be removed in a future version
        *
        * @see __construct
        * @depreciated
        */
        function J_Inventory(){
            self::__construct();
        }

        public function __construct(){
            parent::__construct();
        }

        function deleteDetail(){
            $sql = "DELETE FROM j_inventorydetail WHERE inventory_id = '{$this->id}'";
            return $this->db->query($sql);
        }

        function getListViewDetail() {
            global $mod_strings;
            $details = InventoryHelper::getInventoryLine($this->id);
            //create table
            $html='<table class="">';
            $html.='<thead>';
            $html.='<tr>';
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_PART'].'</b></th>';
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_QUANTITY'].'</b></th>';            
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_PRICE'].'</b></th>';
            $html.='<th style="text-align: center;"><b>'.$mod_strings['LBL_AMOUNT'].'</b></th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            //foreach array detail have just got from relationship fill value in detailview
            $tq = 0;
            $tm = 0;
            foreach($details as $detail){
                $html.='<tr>';
                $html.='<td style="text-align: center;" >'.$detail['book_name'].'</td>';
                $html.='<td style="text-align: center;">'.$detail['quantity'].'</td>';          
                $html.='<td style="text-align: center;">'.format_number($detail['price']+0,0,0).'</td>';
                $html.='<td style="text-align: center;">'.format_number($detail['amount']+0,0,0).'</td>
                </tr>';
                $tq += $detail['quantity'];
                $tm += $detail['amount'];
            }
            $html.='</tbody>';
            $html.='<tfoot>';
             $html.='<tr>';
                $html.='<td style="text-align: center;"><b>Total: </b></td>';
                $html.='<td style="text-align: center;"><b>'.$tq.'</b></td>';          
                $html.='<td style="text-align: center;"></td>';
                $html.='<td style="text-align: center;"><b>'.format_number($tm+0,0,0).'</b></td>
                </tr>';
            $html.='</tfoot>';
            $html.='</table>';
            return $html;  
        }

        function getFromObject() {
            $sql = "SELECT
            m.from_object_id as id, from_inventory_list as bean,
            ( CASE (m.from_inventory_list)
            WHEN 'Accounts' THEN a.`name`            
            WHEN 'Teams' THEN t.`name`            
            END ) AS name
            FROM
            j_inventory m
            LEFT JOIN accounts a ON a.id = m.from_object_id
            LEFT JOIN teams t ON t.id = m.from_object_id
            WHERE m.id = '{$this->id}'";
            $object = $GLOBALS['db']->fetchOne($sql);            
            return "<a href = 'index.php?module={$object['bean']}&action=DetailView&record={$object['id']}' >{$object['name']}</a>";            
        }
        function getToObject() {
            $sql = "SELECT
            m.to_object_id as id, m.to_inventory_list as bean,
            ( CASE (m.from_inventory_list)
            WHEN 'C_Teachers' THEN TRIM(CONCAT(IFNULL(g.first_name,''),' ',IFNULL(g.last_name,'')))            
            WHEN 'Teams' THEN t.`name`            
            WHEN 'Contacts' THEN TRIM(CONCAT(IFNULL(c.first_name,''),' ',IFNULL(c.last_name,'')))            
            WHEN 'Accounts' THEN t.`name`            
            END ) AS name
            FROM
            j_inventory m
            LEFT JOIN accounts a ON a.id = m.to_object_id
            LEFT JOIN teams t ON t.id = m.to_object_id
            LEFT JOIN contacts c ON c.id = m.to_object_id
            LEFT JOIN c_teachers g ON g.id = m.to_object_id 
            WHERE m.id = '{$this->id}'";
            $object = $GLOBALS['db']->fetchOne($sql);            
            return "<a href = 'index.php?module={$object['bean']}&action=DetailView&record={$object['id']}' >{$object['name']}</a>";            
        }
    }
?>