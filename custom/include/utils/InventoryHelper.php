<?php
    include_once("custom/include/_helper/junior_schedule.php");
    define('juniorCenterId','4e3de4c1-2c5e-6c00-3494-55667b495afe');     //config id of junior center
    class InventoryHelper {
        //var $juniorCenterId = '4e3de4c1-2c5e-6c00-3494-55667b495afe';  
        function getCenters($custom_where = '', $keyid = false) {
            $sql_team  = "SELECT DISTINCT id,  name 
            FROM teams 
            WHERE deleted = 0 AND private = 0 $custom_where
            AND id NOT IN(SELECT parent_id FROM teams WHERE deleted = 0 AND (parent_id != '' OR !ISNULL(parent_id)))"; 
            if (!$GLOBALS['current_user']->isAdmin()) {
                $teamlist = $GLOBALS['current_user']->get_my_teams();
                $team_ids = array_keys($teamlist) ;
                $sql_team .= " AND id IN ('".implode("','",$team_ids)."')";
            }
            if($keyid) {
                $data = array();
                $result = $GLOBALS['db']->query($sql_team);
                while($row = $GLOBALS['db']->fetchByAssoc($result)) {
                    $data[$row['id']] = $row['name'];
                }
                return $data;
            }
            return $GLOBALS['db']->fetchArray($sql_team);            
        }

        function getParentCenters($custom_where = '',$keyid = false) {
            $sql_team  = "SELECT DISTINCT id,  name 
            FROM teams 
            WHERE deleted = 0 AND private = 0 $custom_where
            AND parent_id = '".(juniorCenterId)."' "; 
            if (!$GLOBALS['current_user']->isAdmin()) {
                $teamlist = $GLOBALS['current_user']->get_my_teams();
                $team_ids = array_keys($teamlist) ;
                $sql_team .= " AND id IN ('".implode("','",$team_ids)."')";
            }
            if($keyid) {
                $data = array();
                $result = $GLOBALS['db']->query($sql_team);
                while($row = $GLOBALS['db']->fetchByAssoc($result)) {
                    $data[$row['id']] = $row['name'];
                }
                return $data;
            }
            return $GLOBALS['db']->fetchArray($sql_team);            
        } 

        function getCentersOfUser($user_id) {
            $data = array();
            $sql_team  = "SELECT DISTINCT id,  name 
            FROM teams 
            WHERE deleted = 0 AND private = 0 $custom_where
            AND id NOT IN(SELECT parent_id FROM teams WHERE deleted = 0 AND (parent_id != '' OR !ISNULL(parent_id)))"; 
            if (!$GLOBALS['current_user']->isAdmin()) {
                $teamlist = $GLOBALS['current_user']->get_my_teams();
                $team_ids = array_keys($teamlist) ;
                $sql_team .= " AND id IN ('".implode("','",$team_ids)."')";
            }
            $result = $GLOBALS['db']->query($sql_team);
            while($row = $GLOBALS['db']->fetchByAssoc($result)) {
                $data[$row['id']] = $row['name'];
            }
            return $data;           
        } 

        function getSuppliers($custom_where) {
            $sql_supplier  = "SELECT id,name
            FROM accounts 
            WHERE deleted=0 and type_of_account='Supplier' $custom_where";
            if (!$GLOBALS['current_user']->isAdmin()) {                
                $sql_supplier .= " AND (accounts.team_set_id IN 
                (SELECT tst.team_set_id
                FROM team_sets_teams tst
                INNER JOIN team_memberships team_memberships ON tst.team_id = team_memberships.team_id
                AND team_memberships.user_id = '{$current_user->id}' AND team_memberships.deleted = 0))";
            }

            return $GLOBALS['db']->fetchArray($sql_supplier);
        }

        function getTeachers($center_id = array()) {
            return getTeacherOfCenter($center_id);
        }
        function getStudentsOfCenters($center_id = array()) {
            if (!in_array($center_id)) $center_id = array($center_id) ;
            $data = array();
            global $locale;
            $sql = "SELECT t.id, t.first_name, t.last_name
            FROM contacts t
            INNER JOIN team_sets_teams tst ON tst.team_set_id = t.team_set_id AND tst.deleted = 0
            INNER JOIN teams ts ON ts.id = tst.team_id AND ts.deleted = 0 AND ts.id IN ('".implode("','",$center_id)."')
            WHERE t.deleted = 0";
            $result = $GLOBALS['db']->query($sql);
            while($row = $GLOBALS['db']->fetchByAssoc($result)) {
                $row['name'] = $locale->getLocaleFormattedName($row['first_name'], $row['last_name'],'');
                $data[$row['id']] = $row['name'];
            }
            return $data;
        }

        function getCorpsOfCenters($center_id = array()) {
            if (!in_array($center_id)) $center_id = array($center_id) ;
            $data = array();
            global $locale;
            $sql = "SELECT t.id, t.name
            FROM accounts t
            INNER JOIN team_sets_teams tst ON tst.team_set_id = t.team_set_id AND tst.deleted = 0
            INNER JOIN teams ts ON ts.id = tst.team_id AND ts.deleted = 0 AND ts.id IN ('".implode("','",$center_id)."')
            WHERE t.deleted = 0 AND t.type_of_account = 'Corp/BEP' ";
            $result = $GLOBALS['db']->query($sql);
            while($row = $GLOBALS['db']->fetchByAssoc($result)) {
                $data[$row['id']] = $row['name'];
            }
            return $data;
        }
        function getSelectOption($option_list, $key = '', $id = '', $name = '', $style = '', $type = array('key' => 'id', 'val' => 'name')){
            $options = array();
            if (is_array($type)) {
                foreach($option_list as $k => $option) {
                    $options[$option[$type['key']]] = $option[$type['val']];
                } 
            }  else {
                $options = $option_list;
            }
            $html_from.= "<select style='$style' id='$id' name='$name'>";
            $html_from.= get_select_options_with_id($options,$key);
            $html_from.= "</select>";
            return $html_from;            
        }

        function getInventoryLine($id) {
            if (!$id) return array();
            $sql = "SELECT l.*, p.name book_name, p.code book_code,p.unit book_unit
            FROM j_inventorydetail l
            INNER JOIN j_inventory m ON l.deleted = 0 AND m.id = l.inventory_id AND m.id = '$id'
            INNER JOIN product_templates p ON p.id = l.book_id  AND p.deleted = 0"; 

            return $GLOBALS['db']->fetchArray($sql);
        }

        function getBookList($where = ''){
            $sql = "SELECT p.id, p.name as book_name, p.`code` as partno, p.unit, t.`name` as type, t.id as type_id, p.discount_price
            FROM product_templates p
            LEFT JOIN product_types t ON t.deleted = 0 AND p.type_id = t.id 
            WHERE p.deleted = 0
            ORDER BY type_id";
            $result = $GLOBALS['db']->query($sql);
            $booklist = array();
            while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
                $out = ($row['type']?$row['type']:"-None-");
                $booklist[$out][$row['id']] = $row;
            }
            return $booklist;
        }

        function getBookSelectOption($options_list, $select = '') {
            $tpl_addrow = "";
            foreach($options_list as $key => $options) {
                $tpl_addrow.= '<optgroup label="'.$key.'">';       
                foreach($options as $k => $option) {  
                    $selected = "";
                    if($k == $select)  $selected =  "selected" ; 
                    $tpl_addrow.= '<option '.$selected.' value="'.$option['id'].'" price="'.$option["discount_price"].'" 
                    part_no="'.$option["partno"].'" unit="'.$option["unit"].'">'.$option['book_name'].'</option>';  
                }
                $tpl_addrow.='</optgroup>';
            }
            return $tpl_addrow;
        }

    }
?>
