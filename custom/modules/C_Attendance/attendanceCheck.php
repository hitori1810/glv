<?php
// create by leduytan - checkRoom controller
    function attendanceCheck(){
        $ss = new Sugar_Smarty();
        $ss->assign("MOD", $GLOBALS['mod_strings']);
        return $ss->fetch('custom/modules/C_Attendance/tpls/attendanceCheck.tpl');  
       }  
    echo  attendanceCheck();