<?php

    $entry_point_registry['getCallStatus'] = array('file' => 'custom/modules/Asterisk/include/getCallStatus.php', 'auth' => true);

    $entry_point_registry['ajaxLoadItems'] = array('file' => 'custom/include/utils/AjaxLoadItems.php' , 'auth' => true);
    $entry_point_registry['ajaxChangeDate'] = array('file' => 'custom/modules/Opportunities/ajaxChangeDate.php' , 'auth' => true);
    $entry_point_registry['getRelateField'] = array('file' => 'custom/include/utils/getRelateField.php' , 'auth' => true);

    $entry_point_registry['AutoCreateGradeBook'] = array('file' => 'custom/modules/C_Gradebook/cron_auto_create.php' , 'auth' => true);

    $entry_point_registry['Monitor'] = array('file' => 'custom/modules/C_Attendance/attendanceMonitor.php' , 'auth' => true);
    $entry_point_registry['AjaxMonitor'] = array('file' => 'custom/modules/C_Attendance/ajax_post_code.php' , 'auth' => true);

    $entry_point_registry['Calculate_expire_date'] = array('file' => 'custom/modules/C_Classes/cron_expire_date.php' , 'auth' => true);
    $entry_point_registry['updateClosedClass'] = array('file' => 'custom/modules/C_Classes/cron_closedate.php' , 'auth' => true);

    $entry_point_registry['SendingData'] = array('file' => 'custom/include/utils/SendingData.php' , 'auth' => false);
    $entry_point_registry['quickEditAdmin'] = array('file' => 'custom/include/utils/quickEditAdmin.php' , 'auth' => true);

//    $entry_point_registry['class_junior'] = array('file' => 'custom/modules/J_Class/handleCron.php' , 'auth' => false);             //Đã chuyển nhà vào Scheduler - Ko xài Entrypoint vì lý do bảo mật
//    $entry_point_registry['payment_junior'] = array('file' => 'custom/modules/J_Payment/handleCron.php' , 'auth' => false);
//    $entry_point_registry['student_junior'] = array('file' => 'custom/modules/Contacts/handleCron.php' , 'auth' => false);

//    $entry_point_registry['SMS_birthdate'] = array('file' => 'custom/modules/C_SMS/cron_happy_birthday.php' , 'auth' => false);
//    $entry_point_registry['SMS_remind_payment'] = array('file' => 'custom/modules/C_SMS/cron_remind_payment.php' , 'auth' => false);
//    $entry_point_registry['lead_import_portal'] = array('file' => 'custom/modules/Leads/lead_import_portal.php' , 'auth' => false);

    $entry_point_registry['GetJSLanguage'] = array('file' => 'custom/include/utils/GetJSLanguage.php' , 'auth' => false);
//    $entry_point_registry['updateStatusContacts'] = array('file' => 'custom/modules/Contacts/updateStatusStudents.php' , 'auth' => false);
    $entry_point_registry['getAvatar'] = array('file' => 'getAvatar.php', 'auth' => false);
    $entry_point_registry['changeGlobalLanguage'] = array('file' => 'custom/include/EntryPoints/ChangeGlobalLanguage.php' , 'auth' => false); 
    $entry_point_registry['lisenceinfo'] = array('file' => 'custom/modules/Home/entryPointLisenceInfo.php' , 'auth' => false); 
?>
