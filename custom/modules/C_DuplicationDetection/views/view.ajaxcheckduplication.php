<?php

require_once("custom/include/utils/FieldHelper.php");
require_once("custom/modules/C_DuplicationDetection/DuplicationDetectionHelper.php");

class C_DuplicationDetectionViewAjaxCheckDuplication extends SugarView {

    function C_DuplicationDetectionViewAjaxCheckDuplication() {
        parent::SugarView();
    }

    function display() {
        if(isset($_POST['moduleName']) && !empty($_POST['moduleName']) && isset($_POST['fieldData']) && !empty($_POST['fieldData'])) {
            global $beanList, $dictionary, $db, $mod_strings;
            $moduleName = $_POST['moduleName'];
            $fieldData = json_decode(html_entity_decode($_POST['fieldData']), true);
            $appliedFields = array_keys($fieldData);
            $beanName = $beanList[$moduleName];
            $vardefs = $dictionary[$beanName];
            $isPerson = isset($vardefs['fields']['first_name']) && isset($vardefs['fields']['last_name']);

            // Generate query
            $selectFields = array('id', 'assigned_user_id');
            if ($isPerson) {
                // Person module
                $selectFields[] = 'first_name';
                $selectFields[] = 'last_name';
            }else {
                // Normal module
                $selectFields[] = 'name';
            }
            $whereFields = array();
            $targetTable = $vardefs['table'];
            $emailJoinTable = 'email_addr_bean_rel';
            $emailTable = 'email_addresses';
            $emailFieldName = 'email_address';

            // Loop all fields to generate where statements
            foreach($fieldData as $fieldName => $value) {
                if($fieldName != 'id') {    // Id is just for comparing, not for generating sql statement
                    $value = $this->getValueByFieldType($fieldName, $value, $vardefs);

                    if($fieldName != 'email') {
                        // Normal fields
                        $whereFields[] = (!empty($value)) ? $fieldName .' = "'. $value .'"' : $fieldName .' IS NULL';
                    } else {
                        // Email field
                        $whereFields[] = $emailFieldName. ' IN ("'. join('", "', $value) .'")';  // email_address IN ("email1@example.com", "email2@example.com")
                    }
                }
            }
            $ext_where = '';
            if( !empty($_POST['record_id']) )
                $ext_where = " AND $targetTable.id <> '{$_POST['record_id']}'";

            if(!in_array('email', $appliedFields)) {
                // Email is not in the select statement
                $sql = 'SELECT '. join(', ', $selectFields) .', '.$targetTable.'.team_set_id  FROM '. $targetTable .' WHERE '. join(' AND ', $whereFields) .' AND deleted = 0'.$ext_where;
            } else {
                // Email is in the select statement
                $sql = 'SELECT '. $targetTable .'.'. join(', ', $selectFields) .', '.$targetTable.'.team_set_id FROM '. $targetTable .', '. $emailJoinTable .', '. $emailTable .
                ' WHERE '. $emailJoinTable .'.bean_module = "'. $moduleName .'" AND '. $targetTable .'.id = '. $emailJoinTable .'.bean_id AND '. $emailJoinTable .'.email_address_id = '. $emailTable .'.id '.
                'AND '. join(' AND ', $whereFields) .' AND '. $targetTable .'.deleted = 0'.$ext_where.' AND '. $emailJoinTable .'.deleted = 0 AND '. $emailTable .'.deleted = 0';
            }

            $result = $db->query($sql);

            // Generate result rows
            $rows = '';
            $assignedUser = new User();
            while($record = $db->fetchByAssoc($result)) {
                // Don't get this row if the email is applied and the record id is exactly the editing record's id
                if(in_array('email', $appliedFields) && $fieldData['id'] == $record['id']) {
                    $row = '';  // No dupplication in this case
                } else {
                    // Has dupplication
                    $name = ($isPerson) ?  $record['last_name'].' '.$record['first_name']: $record['name'];
                    $assignedUserName = '';
                    if (!empty($record['assigned_user_id'])) {
                        $assignedUser->retrieve($record['assigned_user_id']);
                        $assignedUserName = $assignedUser->name;
                    }                       $teamSetBean = new TeamSet();
                    $teams = $teamSetBean->getTeams($record['team_set_id']);
                    $htmlt = '<td align="center">';
                    $countt = 0;
                    foreach($teams as $id => $team){
                        if($countt == 0)
                            $htmlt .= "{$team->name}";
                        else{
                            $htmlt .= ", {$team->name}";
                        }
                        $countt++;
                    }

                    $row = '<tr>';
                    $row .= '<td align="center"><a target="_blank" href="index.php?module='. $moduleName .'&action=DetailView&record='. $record['id'] .'">'. $name .'</a></td>';
                    if(empty($record['assigned_user_id']))
                        $row .= '<td align="center">-none-</td>';
                    else
                        $row .= '<td align="center"><a target="_blank" href="index.php?module=Users&action=DetailView&record='. $record['assigned_user_id'] .'">'. $assignedUserName .'</a></td>';
                    $row .= "$htmlt</td>";
                    $row .= '</tr>';
                }

                $rows .= $row;
            }

            // Show duplication table only there were duplications
            if($rows != '') {
                $table = '<div id="criteria">'. $mod_strings['LBL_DUPLICATION_CRITERIA'] .': '. $this->getCriteria($moduleName, $appliedFields) .'</div>';
                $table .= '<table>';
                $table .= '<thead>';
                $table .= '<tr><th>'. $mod_strings['LBL_DUPLICATION_NAME_COLUMN'] .'</th><th>'. $mod_strings['LBL_DUPLICATION_ASSIGNED_USER_NAME_COLUMN'] .'</th><th>Center</th><tr>';
                $table .= '</thead>';
                $table .= '<tbody>'. $rows .'</tbody>';
                $table .= '</table>';
                echo $table;
            }
        }

        parent::display();
    }

    // Get applied fields label to display as criteria
    private function getCriteria($moduleName, $fields) {
        $criteria = array();
        foreach($fields as $field) {
            if($field != 'id')  // Don't show the id column
                $criteria[] = FieldHelper::getLabel($moduleName, $field);
        }

        return join(', ', $criteria);
    }

    // Get value for comapring base on the field type
    private function getValueByFieldType($fieldName, $value, $vardefs) {
        $fieldType = $vardefs['fields'][$fieldName]['type'];

        // Trim the value first
        if($fieldName == 'email') {
            $value = array_map('trim', $value);
        } else {
            $value = trim($value);
        }

        // Unformat numeric fields before comparing
        if(in_array($fieldType, array('int', 'float', 'decimal', 'currency'))) {
            $value = unformat_number($value);
        }

        // Format date time fields to db format before comparing
        if(in_array($fieldType, array('date', 'datetimecombo'))) {
            global $timedate;
            list($date, $time) = explode(' ', $value);
            $value = ($fieldType == 'date') ? $timedate->to_db_date($value, false) : $timedate->to_db($value);
        }

        return $value;
    }

}
?>
