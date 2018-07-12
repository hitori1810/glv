<?php
require_once("custom/include/_helper/junior_revenue_utils.php");
$sql_contact = "select id from contacts where deleted = 0";
$rs_contact = $GLOBALS['db']->query($sql_contact);
while($row = $GLOBALS['db']->fetchByAssoc($rs_contact)){
    update_remain_last_date($row['id']);
}
?>