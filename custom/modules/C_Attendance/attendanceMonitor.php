<?php
// create by leduytan - 12/09/2014
    function attendanceMonitor(){
        $ss = new Sugar_Smarty();
        $ss->assign("MOD", $GLOBALS['mod_strings']);
        return $ss->fetch('custom/modules/C_Attendance/tpls/attendanceMonitor.tpl');  
       }  
    echo  attendanceMonitor();