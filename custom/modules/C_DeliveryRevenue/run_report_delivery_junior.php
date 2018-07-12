<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}
require_once("custom/modules/C_DeliveryRevenue/DeliveryRevenue_Junior.php");
$filter = str_replace(' ','',$this->where);
$parts = explode("AND", $filter);
for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "date_input>=") !== FALSE) $start_date = get_string_between($parts[$i]);
    if(strpos($parts[$i], "date_input<=") !== FALSE) $end_date     = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l1.id=") !== FALSE) $team_id = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l1.idIN(") !== FALSE) $team_one_of_id = get_string_between($parts[$i],"IN(", ")");
    if(strpos($parts[$i], "l2.id=") !== FALSE) $student_id = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l3.id=") !== FALSE) $class_id = get_string_between($parts[$i]);
    if(strpos($parts[$i], "c_deliveryrevenue.revenue_typeIN(") !== FALSE) $type = get_string_between($parts[$i],"IN(", ")");
    if(strpos($parts[$i], "date_input='") !== FALSE){
        $start_date = get_string_between($parts[$i]);
        $end_date   = $start_date;
    }
}   

calDeliveryJunior($start_date , $end_date , $team_id, $team_one_of_id, $student_id, $class_id, $type);
