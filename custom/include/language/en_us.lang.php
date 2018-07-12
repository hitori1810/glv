<?php
//Add by Tung Bui - Load File config kind of course & level
include("custom/modules/J_Kindofcourse/course_category.php");   
//include("config_course.php");
//End - add by Tung Bui

// =============== BEGIN APP_STRINGS ===============/
$app_list_strings['extapi_meeting_password'] = array (
    'GoToMeeting' => 'GoToMeeting',
    'WebEx' => 'WebEx',
);
$app_strings['LBL_SELECTED'] = 'Selected';
$app_strings['LBL_QUICK_REPAIR_AND_REBUILD'] = 'Quick Repair';
$app_strings['LBL_STUDIO'] = 'Studio';
$app_strings['LBL_REPAIR_RELATIONSHIP'] = 'Repair relationship';
$app_strings['LBL_DISPLAY_MODULES'] = 'Display Modules and Subpanels';
$app_strings['LBL_GUIDE'] = 'Quick Guide';
$app_strings['LBL_USERS'] = 'User';
$app_strings['LBL_CREATE_NEW_USER'] = 'Create New User';
$app_strings['LBL_OK_CONFIRM_ALERTIFY'] = 'Ok';
$app_strings['LBL_OK_CANCEL_ALERTIFY'] = 'Cancel';
$app_list_strings['type_meeting_list'] = array (
    'Meeting' => 'Meeting',
    'Consultant' => 'Consultant',
    'Event'         => 'Event',
    'Marketing'     => 'Marketing',
    'eLearn hours'  => 'eLearn hours',
    'Standby'       => 'Standby',
    'Placement Test'    => 'Placement Test',
    'Session'           => 'Session',
    'Demo'              => 'Demo',
    'Other'             => 'Other',
);

$app_list_strings['level_lead_list'] = array (
    'Not Interested' => 'Not Interested',
    'Consider (Low)' => 'Consider (Low)',
    'Interested' => 'Interested',
    'Ready To PT/Demo' => 'Ready To PT/Demo',
    'Consider (High)' => 'Consider (High)',
);
$app_list_strings['gender_lead_list'] = array (
    '' => '-none-',
    'Male' => 'Male',
    'Female' => 'Female',
);

$app_list_strings['student_type_list'] = array (
    'Public' => 'Public',
    'Corporate' => 'Corporate',
    'Public/Corp' => 'Public from Corp',
);

$app_list_strings['contact_status_list'] = array (
    'Waiting for class' => 'Waiting for class',
    'In Progress' => 'In Progress',
    'Delayed' => 'Delayed',
    'Finished' => 'Finished',
    'OutStanding' => 'OutStanding',
);

$app_list_strings['number_of_payments_list'] = array (
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4',
);
$app_list_strings['timesheet_minutes_list'] = array (
    '00' => '00',
    '10' => '10',
    '15' => '15',
    '20' => '20',
    '30' => '30',
    '40' => '40',
    '45' => '45',
    '50' => '50',
    '60' => '60',
);
$app_list_strings['case_type_dom'] = array (
    'Complaint' => 'Complaint',
    'Suggestion' => 'Suggestion',
    'Question' => 'Question',
);

$app_list_strings['delivery_status_list'] = array (
    'RECEIVED' => 'RECEIVED',
    'FAILED' => 'FAILED',
);
$app_list_strings['moduleListSingular']['Contacts']='Student';
$app_list_strings['moduleListSingular']['Opportunities']='Enrollment';

$app_list_strings['month_list_view'] = array (
    '0'=>"",
    '01'=>"January",
    '02'=>"February",
    '03'=>"March",
    '04'=>"April",
    '05'=>"May",
    '06'=>"June",
    '07'=>"July",
    '08'=>"August",
    '09'=>"September",
    '10'=>"October",
    '11'=>"November",
    '12'=>"December",
);

$app_list_strings['class_type_list'] = array (
    '' => '-none-',
    'Practice' => 'Practice',
    'Skill' => 'Skill',
    'Connect Club' => 'Connect Club',
    'Connect Event' => 'Connect Event',
    'Waiting Class' => 'Waiting Class',
);

$app_list_strings['membership_level_list'] = array (
    ''          => '-none-',
    'N/A'       => 'N/A',
    'Blue'      => 'Blue',
    'Gold'      => 'Gold',
    'Platinum'  => 'Platinum',
);

$app_list_strings['notifications_severity_list'] = array(
    'Alert' => 'Alert',
    'Info' => 'Info',
    'Other' => 'Other',
    'Success' => 'Success',
    'Warning' => 'Warning',
);

$app_list_strings['target_status_dom'] = array (
    'New'           => 'New',
    'In Process'    => 'In Process',
    'Converted'     => 'Converted',
);

$app_list_strings['teacher_type_list'] = array (
    '' => '- none -',
    'FT' => 'Full-time',
    'PT' => 'Part-time',
    'AM' => 'Academic Manager (AM)',
    'AC' => 'Academic Coordinator (AC)',
    'CST' => 'Center Senior Teacher',
    'ST' => 'Senior Teacher',
    'MT' => 'Menter Teacher',
);

$app_list_strings['teacher_status_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
);

$app_list_strings['room_status_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
);

$app_list_strings['capacity_list'] = array (
    '10' => '10',
    '20' => '20',
    '25' => '25',
    '30' => '30',
    '35' => '35',
    '40' => '40',
    '45' => '45',
    '50' => '50',
    '55' => '55',
    '60' => '60',
    '65' => '65',
    '70' => '70',
);

$app_list_strings['holiday_type_list'] = array (
    'Holidays' => 'Holidays',
    'Sick Leave' => 'Sick Leave',
    'Unpaid Leave' => 'Unpaid Leave',
    'Marriage Leave' => 'Marriage Leave',
    'Maternity Leave' => 'Maternity Leave',
    'Accident Leave' => 'Accident Leave',
    'Public Holiday' => 'Public Holiday',
    'Day Off' => 'Day Off',
);

$app_list_strings['session_status_list'] = array (
    '' => '',
    'Cancelled' => 'Cancelled',
    'Make-up' => 'Make-up',
    'Cover' => 'Cover',
    'Not Started' => 'Not Started',
    'In Progress' => 'In Progress',
    'Finish' => 'Finished',
);
$app_list_strings['moduleListSingular']['Accounts']='Corporate';
$app_strings['LBL_GROUPTAB6_1406016993'] = '360';
//Datatables Language
$app_strings['LBL_JDATATABLE1'] = 'Show ';
$app_strings['LBL_JDATATABLE2'] = ' entries';
$app_strings['LBL_JDATATABLE3'] = 'Search:';
$app_strings['LBL_STT_D'] = 'Order';
$app_strings['LBL_TEN_D'] = 'Name';
$app_strings['LBL_JDATATABLE6'] = 'Showing  ';
$app_strings['LBL_JDATATABLE7'] = ' to ';
$app_strings['LBL_JDATATABLE8'] = ' of total ';
$app_strings['LBL_JDATATABLE9'] = 'First';
$app_strings['LBL_JDATATABLE10'] = 'Next';
$app_strings['LBL_JDATATABLE11'] = 'Previous';
$app_strings['LBL_JDATATABLE12'] = 'Last';
$app_strings['LBL_JDATATABLE13'] = 'No matching records found';
$app_strings['LBL_JDATATABLE14'] = 'No data';
$app_strings['LBL_JDATATABLE15'] = 'Showing 0 entries';
$app_strings['LBL_CAMPAIGNS_SEND_SMS_QUEUED'] = 'Send SMS Campaign';
$app_strings['LBL_GROUPTAB7_1407210988'] = 'New Group';

$app_list_strings['card_type_payments_list']=array (
    '' => '-none-',
    'MasterCard' => 'MasterCard',
    'VisaCard' => 'VisaCard',
    'VietcomBank' => 'VietcomBank',
    'ATM' => 'ATM',
    'JCB' => 'JCB',
    'AmericanExpress' => 'American Express (AMEX)',
    'Other' => 'Other',
);

$app_list_strings['card_rate']=array (
    'VisaCard' => '1.7',
    'MasterCard' => '1.7',
    'VietcomBank' => '0.55',
    'JCB' => '2.2',
    'ATM' => '0',
    'AmericanExpress' => '2.5',
    'Other' => '0',
);

$app_list_strings['bank_name_list']=array (
    '' => '-none-',
    'VietcomBank' => 'VietcomBank',
    'Sacombank' => 'Sacombank',
    'ANZ' => 'ANZ',
    'Vietin bank' => 'Vietin bank',
    'BIDV' => 'BIDV',
    'Techcombank' => 'Techcombank',
    'Other' => 'Other',
);

$app_list_strings['bank_rate']=array (
    'VietcomBank' => '0',
    'Sacombank' => '0',
    'ANZ' => '0',
    'Vietin bank' => '0',
    'BIDV' => '0',
    'Techcombank' => '0',
    'Other' => '0',
);

$app_list_strings['timekeeping_type_list']=array (
    'Admin Hours' => 'Admin Hours',
    'Teaching Hours' => 'Teaching Hours',
);

$app_list_strings['moduleList']['C_Timesheet']='Admin Hours';
$app_list_strings['moduleListSingular']['C_Timesheet']='Admin Hours';
$app_strings['LBL_TEAM_MANAGEMENT'] = 'Team Management';
$app_strings['LBL_UNIMPERSONATE'] = 'Resume as {user_name}';

$app_list_strings['kind_of_course_360_list']=array (
    ''          => '-none-',
    'Flexi'     => 'Flexi',
    'Mobile'    => 'Mobile',
    'Access'    => 'Access',
    'Premium'   => 'Premium',
    'IELTS'     => 'IELTS',
    'CORP'     => 'CORP',
);
// =============== END APP_STRINGS ===============/

// =============== BEGIN APP_STRINGS JUNIOR ===============/
// -------------- Begin Lap Nguyen -----------------/
//$app_list_strings['kind_of_course_list'] = array(
//    ''              => '-none-',
//    'Academic'  => 'Academic',
//    'TOEIC'     => 'TOEIC',
//    'IELTS'     => 'IELTS',
//    'CERF'      => 'CERF',                  
//    'Customize'       => 'Customize',
//    'Other'     => 'Other',
//);
//
//$app_list_strings['level_program_list'] = array(
//    ''   => '-none-',
//    'Beginner' => 'Beginner',
//    'Elementary' => 'Elementary',
//    'Pre-Intermediate' => 'Pre-Intermediate',
//    'Intermediate'=> 'Intermediate',
//    'Upper-Intermediate' => 'Upper-Intermediate',
//    'Foundation' => 'Foundation',
//    'Empowerment' => 'Empowerment',
//    'Excellence'   => 'Excellence',
//    'Expert'   => 'Expert',
//    'Power'    => 'Power',
//    'Passport' => 'Passport',
//    'Perfect'  => 'Perfect',
//    'Pro'      => 'Pro',
//    'Other'    => 'Other',
//);
//$app_list_strings['module_program_list'] = array(
//    ''  => '-none-',
//    '1' => '1',
//    '2' => '2',
//    '3' => '3',
//    '4' => '4',
//    '5' => '5',
//    '6' => '6',
//);
//
//$app_list_strings['full_kind_of_course_list'] = array(   //Chu y: Khoang trang 1 la` KOC VD:Academic,TOEIC
//    ''              => '-none-',
//    'Academic Beginer' => 'Academic Beginer',
//    'Academic Elementary 1' => 'Academic Elementary 1',
//    'Academic Elementary 2' => 'Academic Elementary 2',
//    'Academic Pre-Intermediate 1' => 'Academic Pre-Intermediate 1',
//    'Academic Pre-Intermediate 2' => 'Academic Pre-Intermediate 2',
//    'Academic Intermediate 1' => 'Academic Intermediate 1',
//    'Academic Intermediate 2' => 'Academic Intermediate 2',
//    'Academic Upper-Intermediate 1' => 'Academic Upper-Intermediate 1',
//    'Academic Upper-Intermediate 2' => 'Academic Upper-Intermediate 2',
//    'TOEIC Foundation' => 'TOEIC Foundation',
//    'TOEIC Empowerment' => 'TOEIC Empowerment',
//    'TOEIC Excellence' => 'TOEIC Excellence',
//    'TOEIC Expert' => 'TOEIC Expert',
//    'IELTS Power' => 'IELTS Power',
//    'IELTS Passport' => 'IELTS Passport',
//    'IELTS Perfect' => 'IELTS Perfect',
//    'IELTS Pro' => 'IELTS Pro',
//    'CERF A1' => 'CERF A1',
//    'CERF A2' => 'CERF A2',
//    'CERF B1' => 'CERF B1',
//    'CERF B2' => 'CERF B2',
//    'CERF C1' => 'CERF C1',
//    'CERF C2' => 'CERF C2',
//);

$app_list_strings['level_PT_program_list'] = array(
    'none'   => '',
    'Beginner' => 'Beginner',
    'Elementary 1' => 'Elementary 1',
    'Elementary 2' => 'Elementary 2',
    'Pre-Intermediate 1' => 'Pre-Intermediate 1',
    'Pre-Intermediate 2' => 'Pre-Intermediate 2',
    'Intermediate 1'=> 'Intermediate 1',
    'Intermediate 2'=> 'Intermediate 2',
    'Upper-Intermediate 1' => 'Upper-Intermediate 1',
    'Upper-Intermediate 2' => 'Upper-Intermediate 2',
    'Foundation' => 'Foundation',
    'Empowerment' => 'Empowerment',
    'Excellence'   => 'Excellence',
    'Expert'   => 'Expert',
    'Power'    => 'Power',
    'Passport' => 'Passport',
    'Perfect'  => 'Perfect',
    'Pro'      => 'Pro',
    'A1' => 'A1',
    'A2' => 'A2',
    'B1' => 'B1',
    'B2' => 'B2',
    'C1' => 'C1',
    'C2' => 'C2',
    'Other'    => 'Other',
);

$app_list_strings['koc_score_pt_list'] = array(
    ''        => '-none-',
    '80-90'    => 'CERF C2|IELTS Pro',
    '71-80'    => 'CERF C1|IELTS Perfect',
    '61-70'    => 'CERF B2|IELTS Passport|TOEIC Expert|Academic Upper-Intermediate 2|Academic Upper-Intermediate 1',
    '46-60'    => 'CERF B1|IELTS Power|TOEIC Excellence|Academic Intermediate 2|Academic Intermediate 1',
    '31-45'    => 'CERF B1|TOEIC Empowerment|Academic Pre-Intermediate 2|Academic Pre-Intermediate 1',
    '16-30'    => 'CERF A2|TOEIC Foundation|Academic Elementary 2|Academic Elementary 1',
    '0-15'     => 'CERF A1|Academic Beginer',
);

$app_list_strings['period_program_list'] = array(
    ''        => '-none-',
    'Period A'    => 'Period A',
    'Period B'    => 'Period B',
    'Period C'    => 'Period C',
);
$app_list_strings['category_discount_list'] = array(
    ''        => '-none-',
    'Standard Discount'   => 'Standard Discount',  
    'Special Discount'    => 'Special Discount',
);   
$app_list_strings['quantityy_list'] = array(
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
);  
$app_list_strings['week_frame_class_list'] = array(
    'Mon' => 'Mon',
    'Tue' => 'Tue',
    'Wed' => 'Wed',
    'Thu' => 'Thu',
    'Fri' => 'Fri',
    'Sat' => 'Sat',
    'Sun' => 'Sun',
);
$app_list_strings['type_of_class_list'] = array(
    '' => '-none-',
    'Junior' => 'Junior',
    '360'    => '360',
);
$app_list_strings['revenue_type_list'] = array(
    'Enrolled'  => 'Enrolled',
    'Moving In' => 'Moving In',
    'Demo'      => 'Demo',
    'Settle'    => 'Settle',
    'OutStanding'=> 'OutStanding',
    'Stopped'   => 'Stopped',
);
$app_list_strings['data_type_list'] = array(
    '360'             => '360',
    'Junior'        => 'Junior',
);
$app_list_strings['type_student_situation_list'] = array(
    '' => '-none-',
    'Enrolled'   => 'Enrolled',
    'Delayed'    => 'Delayed',
    'OutStanding'=> 'OutStanding',
    'Demo'       => 'Demo',
    'Settle'     => 'Settle',
    'Stopped'    => 'Stopped',
    'Moving Out' => 'Moving Out',
    'Moving In'  => 'Moving In',
    'Waiting Class'  => 'Waiting Class',
);

$app_list_strings['birth_month_list'] = array(
    ''  =>"-none-",
    '1' =>"January",
    '2' =>"February",
    '3' =>"March",
    '4' =>"April",
    '5' =>"May",
    '6' =>"June",
    '7' =>"July",
    '8' =>"August",
    '9' =>"September",
    '10'=>"October",
    '11'=>"November",
    '12'=>"December",
);

$app_list_strings['type_of_course_fee_list'] = array(
    ''         => '-none-',
    '1'     => '1 Hour',
    '2'     => '2 Hours',
    '4'     => '4 Hours',
    '12'     => '12 Hours',
    '18'     => '18 Hours',
    '36'     => '36 Hours',
    '40'     => '40 Hours',
    '46'     => '46 Hours',
    '56'     => '56 Hours',
    '60'     => '60 Hours',
    '72'     => '72 Hours',
    '102'     => '102 Hours',
    '106'     => '106 Hours',
    '108'     => '108 Hours',
    '144'     => '144 Hours',
    '180'     => '180 Hours',
);
$app_list_strings['type_coursefee_list'] = array(
    ''    => '-none-',
    'Hours'    => 'Hours',
    'Days'     => 'Days',

);
$app_list_strings['fieldhighlighter_style_options'] = array (
    'blue' => 'Blue',
    'bluelight' => 'Blue Light',
    'red' => 'Red',
    'redlight' => 'Red Light',
    'black' => 'Black',
    'yellow' => 'Yellow',
    'yellowlight' => 'Yellow Light',
    'green' => 'Green',
    'violet' => 'Violet',
    'orange' => 'Orange',
    'crimson' => 'Crimson',
    'blood' => 'Blood',
    'dream' => 'Dream',
    'bold' => 'Bold',
    'bolditalic' => 'Bold Italic',
    'boldunderline' => 'Bold Underline',
    'italic' => 'Italic',
    'underline' => 'Underline',
    'greencolor' => 'Green Color',
    'bluecolor' => 'Blue Color',
    'redcolor' => 'Red Color',
    'violetcolor' => 'Violet Color',
    'yellowcolor' => 'Yellow Color',
    'orangecolor' => 'Orange Color',
    'browncolor' => 'Brown Color',
);

$app_list_strings['detailview_editable_config_type_options'] = array (
    'specific_fields' => 'Disable Fields',
    'whole_module' => 'Disable Module',
);

$app_list_strings['keyboardsetting_correction_type_options'] = array (
    'uppercase_all' => 'UPPERCASE ALL',
    'initial_capital' => 'Initial Capital',
    'first_letter_capital' => 'First letter captial',
    'lowercase_all' => 'lowercase all',
);

$app_list_strings['payment_payment_list'] = array (
    'Success' => 'Success',
    'Closed' => 'Closed',
);

$app_list_strings['duplication_preventive_type_options'] = array (
    'notify_only' => 'Notify Only',
    'notify_and_prevent' => 'Notify And Prevent Saving',
);
$app_list_strings['situation_student_type_list'] = array (
    'Lead' => 'Lead',
    'Student' => 'Student',
);
$app_list_strings['sms_supplier_api'] = array (
    'VHT'       => 'VHT',
    'VIETGUYS'  => 'VIETGUYS',
    'VMG'       => 'VMG',
    'GAPIT'     => 'GAPIT',
);
$app_strings['LBL_DUPLICATION_STATUS_TITLE'] = 'Duplication Status';
$app_strings['LBL_DUPLICATION_DIALOG_TITLE'] = 'Possible Duplicated Records:';

$app_list_strings['level_school_list'] = array(
    ''    => '-none-',
    'Kindergarten'    => 'Kindergaden',
    'Primary school'  => 'Primary school',
    'Secondary school'=> 'Secondary school',
    'High school'     => 'High school',
    'College'         => 'College',
    'University'      => 'University',
    'N/A'             => 'N/A',
);
$app_list_strings['priority_feedback_list'] = array(
    'High' => 'High',
    'Medium' => 'Medium',
    'Low' => 'Low',
);
$app_list_strings['type_feedback_list'] = array(
    'Internal' => 'Internal',
    'LX' => 'LX',
    'Customer' => 'Customer',
);

$app_list_strings['status_feedback_list'] = array(
    'New'       => 'New',
    'Assigned'  => 'Assigned',
    'In Progress' => 'In Process',
    'Done'      => 'Done',
);

$app_list_strings['status_feedback_list_for_vn'] = array(
    'New' => 'Mới ',
    'Assigned' => 'Đã phân công',
    'In Progress' => 'Đang xử lỷ',
    'Done' => 'Đã xong',
);
$app_list_strings['full_relate_feedback_list']=array (
    '' => '-none-',
    'Class Tutoring' => 'Tutoring',
    'Class SMS Issue' => 'SMS Issue',
    'Teacher Issue Nationality' => 'Nationality',
    'Teacher Issue Qualification' => 'Qualification',
    'Teacher Issue Performance In Class' => 'Performance In Class',
    'Teacher Issue Administration' => 'Administration',
    'Teacher Issue Other' => 'Other',
    'Student Issue Performance Level' => 'Performance Level',
    'Student Issue Behavior' => 'Behavior',
    'Student Issue Other' => 'Other',
    'Building Issue Room' => 'Room',
    'Building Issue Facility' => 'Facility',
    'Building Issue Safety' => 'Safety',
    'Building Issue Other' => 'Other',
    'Customer Issue EC Performance' => 'EC Performance',
    'Customer Issue EFL Performance' => 'EFL Performance',
    'Customer Issue Complaint' => 'Complaint',
    'Customer Issue Suggestion' => 'Suggestion',
    'Customer Issue Question' => 'Question',
    'Customer Issue Other' => 'Other',
);
$app_list_strings['full_relate_feedback_list_for_vn']=array (
    '' => '-none-',
    'Teacher Issue Nationality' => 'Nationality',
    'Teacher Issue Qualification' => 'Qualification',
    'Teacher Issue Performance In Class' => 'Performance In Class',
    'Teacher Issue Administration' => 'Administration',
    'Teacher Issue Other' => 'Other',
    'Building Issue Room' => 'Room',
    'Building Issue Facility' => 'Facility',
    'Building Issue Safety' => 'Safety',
    'Building Issue Other' => 'Other',
    'Student Issue Performance Level' => 'Performance Level',
    'Student Issue Behavior' => 'Behavior',
    'Student Issue Other' => 'Other',
    'Customer Issue EC Performance' => 'EC Performance',
    'Customer Issue EFL Performance' => 'EFL Performance',
    'Customer Issue Complaint' => 'Phàn nàn',
    'Customer Issue Suggestion' => 'Góp ý',
    'Customer Issue Question' => 'Thắc mắc',
    'Customer Issue Other' => 'Other',

);
$app_list_strings['portal_feedback_type_list_for_vn'] = array(
    'Customer Issue Suggestion' => 'Góp ý',
    'Customer Issue Question' => 'Thắc mắc',
    'Customer Issue Complaint' => 'Phàn nàn',
);
$app_list_strings['portal_feedback_type_list_for_en'] = array(
    'Customer Issue Suggestion' => 'Suggestion',
    'Customer Issue Question' => 'Question',
    'Customer Issue Complaint' => 'Complaint',
);
$app_list_strings['relate_feedback_list']=array (
    '' => '-none-',
    'Class' => array(
        'Class Tutoring' => 'Tutoring',
        'Class SMS Issue' => 'SMS Issue',
    ),
    'Teacher Issue' => array(
        'Teacher Issue Nationality' => 'Nationality',
        'Teacher Issue Qualification' => 'Qualification',
        'Teacher Issue Performance In Class' => 'Performance In Class',
        'Teacher Issue Administration' => 'Administration',
        'Teacher Issue Other' => 'Other',
    ),
    'Student Issue' => array(
        'Student Issue Performance Level' => 'Performance Level',
        'Student Issue Behavior' => 'Behavior',
        'Student Issue Other' => 'Other',
    ),
    'Building Issue' => array(
        'Building Issue Room' => 'Room',
        'Building Issue Facility' => 'Facility',
        'Building Issue Safety' => 'Safety',
        'Building Issue Other' => 'Other',
    ),
    'Customer Issue' => array(
        'Customer Issue EC Performance' => 'EC Performance',
        'Customer Issue EFL Performance' => 'EFL Performance',
        'Customer Issue Complaint' => 'Complaint',
        'Customer Issue Suggestion' => 'Suggestion',
        'Customer Issue Question' => 'Question',
        'Customer Issue Other' => 'Other',
    ),
);


$app_list_strings['status_kindofcourse_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
);
$app_list_strings['type_targetconfig_list'] = array (
    ''                  => '-none-',
    'New Sale'          => 'New Sale',
    'New Student'       => 'New Student',
    'Retention'         => 'Retention',
    'Student Retention' => 'Student Retention',
    'Delivery Revenue'  => 'Delivery Revenue',
    'Collected Revenue' => 'Collected Revenue',
    'Enquiries'         => 'Enquiries',
    'Accrual Rate (Blue)'      => 'Accrual Rate (Blue)',
    'Accrual Rate (Gold)'      => 'Accrual Rate (Gold)',
    'Accrual Rate (Platinum)'      => 'Accrual Rate (Platinum)',
);
$app_list_strings ['default_loyalty_rate'] = array (
    'Conversion Rate'           => '1000',
    'Accrual Rate (N/A)'        => '0',
    'Accrual Rate (Blue)'       => '0.03',
    'Accrual Rate (Gold)'       => '0.03',
    'Accrual Rate (Platinum)'   => '0.03',
);
$app_list_strings ['loyalty_rank_list'] = array (
    'N/A'       => '0',
    'Blue'      => '10000000',
    'Gold'      => '30000000',
    'Platinum'  => '100000000'
);
//Custom Loyalty Card
$app_list_strings ['loyalty_type_list'] = array (
    ''          => '-none-',
    'Accrual'   => 'Accrual',
    'Referral'  => 'Referral',
    'Scholarship'=> 'Scholarship',
    'Redemption'=> 'Redemption',
    'Refund'    => 'Refund',
    'Penalty'   => 'Penalty',
);
$app_list_strings ['loyalty_in_list'] = array (
    'Accrual'   => 'Accrual',
    'Referral'  => 'Referral',
    'Scholarship'=> 'Scholarship',
);
$app_list_strings ['loyalty_out_list'] = array (
    'Redemption'=> 'Redemption',
    'Refund'    => 'Refund',
    'Penalty'   => 'Penalty',
);
$app_list_strings ['input_loyalty_type_list'] = array (
    ''          => '-none-',
    'Referral'     => 'Referral (+)',
    'Scholarship'=> 'Scholarship (+)',
    'Penalty'   => 'Penalty (-)',
);

$app_list_strings['year_targetconfig_list'] = array (
    '2013' => '2013',
    '2014' => '2014',
    '2015' => '2015',
    '2016' => '2016',
    '2017' => '2017',
    '2018' => '2018',
    '2019' => '2019',
    '2020' => '2020',
    '2021' => '2021',
    '2022' => '2022',
    '2023' => '2023',
);
$app_list_strings['frequency_targetconfig_list'] = array (
    ''          => '-none-',
    'Weekly'    => 'Weekly',
    'Monthly'   => 'Monthly',
    'Quarterly' => 'Quarterly',
    'Yearly'    => 'Yearly',
);

$app_list_strings['feedback_time_list'] = array (
    '' => '-None-',
    'Absence' => 'Absence',
    '6th' => '6th (Check using online world)',
    '8th' => '8th (Call new students)',
    '14th' => '14th (Call all students to inform teacher\'s comments)',
    '26th' => '26th (Call customers whose payment period ending)',
    '40th' => '40th (Call all students about test)',
    '62nd' => '62nd (Call customers whose payment period ending)',
    '66th' => '66th (Call for upgrading class)',
    '76th' => '76th (Call all students about test)',
    '102nd' => '102nd (Call for upgrading class)',
    'Other' => 'Other',
);

$app_list_strings['status_inventory_list'] = array(
    //'Draft' => 'Draft',
    //'In Progress' => 'In Process',
    //'Done' => 'Done',
    'Un Confirmed' =>  'Un Confirmed',
    'Confirmed' =>  'Confirmed',
);
$app_list_strings['type_inventory_list'] = array(
    '' => '--None--',
    'Import'        => 'Import',
    'Tranfer Out'   => 'Tranfer Out',
    'Tranfer In'    => 'Tranfer In',
    'Sell'          => 'Sell',
);
$app_list_strings['from_inventory_list'] = array(
    'Teams' => 'Center',
    'TeamsParent' => 'Parent Center',
    'Accounts' => 'Supplier',
);
$app_list_strings['to_inventory_list'] = array(
    'Teams' => 'Center',
    'C_Teachers' => 'Teacher/Library',
    'Accounts' => 'Corp/BEP',
    'Contacts' => 'Student',
);
$app_list_strings['type_accounts_list'] = array(
    'Student' => 'Student',
    'Supplier' => 'Supplier',
    'Teacher/Library' => 'Teacher/Library',
    'Corp/BEP' => 'Corp/BEP',
);
$app_list_strings['type_class_list'] = array(
    'Normal Class' => 'Normal Class',
    'Waiting Class' => 'Waiting Class',
);
$app_list_strings['class_time_type_list'] = array(
    '1 ls/w' => '1 ls/w',
    '2 ls/w' => '2 ls/w',
    '3 ls/w' => '3 ls/w',
);
/*$app_list_strings['lead_source_list'] = array (
    ''              => '-none-',
    'Website'       => 'Website',
    'Landing Page'  => 'Landing Page',
    'Facebook'      => 'Facebook',
    'Youtube'      => 'Youtube',

    'Walk in'       => 'Walk in',
    'Call in'       => 'Call in',
    'Local MKT'     => 'Local MKT',
    'Local Data'    => 'Local Data',
    'Sales'    => 'Sales',
    'Referral'      => 'Referral',
    'Sponsorship'   => 'Sponsorship',
    'Partner'   => 'Partner',
    'AG-OS'   => 'AG-OS',
    'Residence building'=> 'Residence building',
    'Sales Department'=> 'Sales Department',
    'Business Partner'   => 'Business Partner',
    'Schoolink'   => 'Schoolink',
    'Non-SL School'   => 'Non-SL School',
    'PR department'   => 'PR department',
    'Other'   => 'Other',
);   */

$app_list_strings['lead_source_list'] = array (      
    ''                      => '-none-',
    'Walk in'               => 'Walk in', 
    'Call in'               => 'Call in',
    'Referral'              => 'Referral',            
    'Sales Department'      => 'Sales',
    'Sponsorship'           => 'Sponsorship',   
    'Website'               => 'Website',
    'Landing Page'          => 'Landing Page',
    'Facebook'              => 'Facebook',
    'Youtube'               => 'Youtube',
    'Other'                 => 'Other',
);

$app_list_strings['online_source_list'] = array (
    'Website'       => 'Website',
    'Landing Page'  => 'Landing Page',
    'Facebook'      => 'Facebook',
    'Youtube'      => 'Youtube',
);

$app_list_strings['offline_source_list'] = array (
    'Walk in'       => 'Walk in',
    'Call in'       => 'Call in',
    'Local MKT'     => 'Local MKT',
    'Local Data'    => 'Local Data',
    'Sales'    => 'Sales',
    'Referral'      => 'Referral',
    'Sponsorship'   => 'Sponsorship',
    'Partner'   => 'Partner',
    'AG-OS'   => 'AG-OS',
    'Residence building'=> 'Residence building',
    'Sales Department'=> 'Sales Department',
    'Business Partner'   => 'Business Partner',
    'Schoolink'   => 'Schoolink',
    'Non-SL School'   => 'Non-SL School',
    'PR department'   => 'PR department',
    'Other'   => 'Other',
);

$app_list_strings['activity_source_list'] = array (
    ''              => '-none-',
    'Talk To Us'    => 'Talk To Us',
    'Live Chat'     => 'Live Chat',
    'Lead'          => 'Lead',
    'Chat'          => 'Chat',
    'School activity'=> 'School activity',
    'Partnership'    => 'Partnership',
    'Internal'       => 'Internal',
    'External'       => 'External',
);

$app_list_strings['json_activity_source_list'] = array (
    'Website'       => 'Talk To Us|Live Chat',
    'Landing Page'  => 'Talk To Us|Live Chat',
    'Facebook'      => 'Lead|Chat',
    'Local MKT'     => 'School activity|Partnership',
    'Sponsorship'   => 'Internal|External',
);

$app_list_strings['status_discount_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
);
$app_list_strings['applyfor_discount_list'] = array (
    'Current Student' => 'Current Student',
    'New Student' => 'New Student',
    'Both' => 'Both',
);
$app_list_strings['type_discount_list'] = array (
    'Gift'                 => 'Gift',
    'Partnership'          => 'Partnership',
    'Hour'                 => 'Hour',
    'Reward'               => 'Reward',
    'Other'                => 'Other',
);
$app_list_strings['number_of_payment_junior_list'] = array (
    '1'          => '1',
    '2'          => '2',
    '3'          => '3',
);
$app_list_strings['number_of_payment_contract_list'] = array (
    '1'          => '1',
    '2'          => '2',
    '3'          => '3',
    '4'          => '4',
    '5'          => '5',
);
$app_list_strings['course_discount_list'] = array (
    '1'  => '1',
    '2'  => '2',
    '3'  => '3',
    '4'  => '4',
    '5'  => '5',
    '6'  => '6',
    '7'  => '7',
    '8'  => '8',
    '9'  => '9',
    '10'  => '10',
    '15'  => '15',
    '20'  => '20',
    '25'  => '25',
);
$app_list_strings['status_coursefee_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
);
$app_list_strings['status_class_list'] = array (
    ''              => '-none-',
    'Planning'      => 'Planning',
    'In Progress'   => 'In Progress',
    'Closed'        => 'Closed',
    'Finish'        => 'Finish',
);
$app_list_strings['maxsize_class_list'] = array (
    '5' => '5',
    '10' => '10',
    '12' => '12',
    '15' => '15',
    '20' => '20',
    '25' => '25',
    '30' => '30',
    '35' => '35',
    '40' => '40',
    '45' => '45',
    '50' => '50',
    '55' => '55',
    '60' => '60',
    '65' => '65',
    '70' => '70',
    '75' => '75',
);
$app_list_strings['status_ProductTemplates_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
);
$app_list_strings['supplier_ProductTemplates_list'] = array (
    'Fahasha' => 'Fahasha',
    'NVC' => 'Nguyễn Văn Cừ',
    'PN' => 'Phương Nam',
);
$app_list_strings['unit_ProductTemplates_list'] = array (
    'Set' => 'Set',
    'Unit' => 'Unit',
);
$app_list_strings['type_TeacherContract_list'] = array (
    '' => '- none -',
    'A' => 'Director of Studies', //A
    'FT' => 'Full-time',
    'PT' => 'Part-time',
    'MT' => 'Mentor Teacher',
);
$app_list_strings['status_TeacherContract_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
);
$app_list_strings['dayoff_TeacherContract_list'] = array (
    'Mon' => 'Mon',
    'Tue' => 'Tue',
    'Wed' => 'Wed',
    'Thu' => 'Thu',
    'Fri' => 'Fri',
    'Sat' => 'Sat',
    'Sun' => 'Sun',
);
$app_list_strings['status_marketingplan_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
    'Complete' => 'Complete',
    'In Queue' => 'In Queue',
    'Sending' => 'Sending',
);
$app_list_strings['category_marketingplan_list'] = array (
    'New Student' => 'New Student',
    'Current Student' => 'Current Student',
);
$app_list_strings['type_teacher_list'] = array (
    'Teacher' => 'Teacher',
    'TA' => 'TA',
);
$app_list_strings['lead_status_dom'] = array (
    'New'           => 'New',       
    'In Process'    => 'In Process',
    'PT/Demo'       => 'PT/Demo',
    'Converted'     => 'Converted',
    'Recycled'      => 'Recycled',
    'Dead'          => 'Pending',
);
$app_list_strings['reason_not_interested_leads_list'] = array (
    ''                  => '-none-',
    'course_fee'        => 'Course Fee',
    'loaction'          => 'Location of Center',
    'busy'              => 'Busy',
    'teacher'           => 'Teacher',
    'study_program'     => 'Study Program ',
    'material_facilities'   => 'Material Facilities',
    'Other'             => 'Other',
);
$app_list_strings['status_partnership_list'] = array (
    'Active' => 'Active',
    'Inactive' => 'Inactive',
);
$app_list_strings['type_team_list'] = array (
    '' => '-none-',
    'Adult' => 'Adult',
    'Junior' => 'Junior',
);
$app_list_strings['rela_contacts_list'] = array (
    '' => '-none-',
    'Sister' => 'Sister',
    'Brother' => 'Brother',
    'Friend' => 'Friend',
    'Coursin' => 'Coursin',
    'Father' => 'Father',
    'Mother' => 'Mother',
    'Grandfather' => 'Grandfather',
    'Grandmother' => 'Grandmother',
);
$app_list_strings['status_paymentdetail_list'] = array (
    'Unpaid'        => 'Unpaid',
    'Paid'          => 'Paid',
    'Cancelled'     => 'Cancelled',
);
$app_list_strings['status_payment_list'] = array (
    'Paid'         => 'Paid',
    'Unpaid'       => 'Unpaid',
);

$app_list_strings['payment_type_payment_list'] = array (
    'Enrollment'            => 'Enrollment',
    'Deposit'               => 'Deposit',
    'Cashholder'            => 'Cashholder',   
    'Delay'                 => 'Delay',
    'Schedule Change'       => 'Schedule Change',
    'Transfer In'           => 'Transfer In',
    'Transfer Out'          => 'Transfer Out',
    'Moving In'             => 'Moving In',
    'Moving Out'            => 'Moving Out',
    'Refund'                => 'Refund',
    'Placement Test'        => 'Placement Test',
    'Book/Gift'             => 'Book/Gift',
    'Corporate'             => 'Corporate',
);
$app_list_strings['sale_type_payment_list'] = array (
    ''          => '-Empty-',
    'Not set'   => 'Not set',
    'New Sale'  => 'New Sale',
    'Retention' => 'Retention',
);
$app_list_strings['is_using_list'] = array (
    'Range 1'   => 'Range 1',
    'Range 2'   => 'Range 2',
);
/*$app_list_strings['status_payment_list'] = array (
'Planning' => 'Planning',
'In Progress' => 'In Progress',
'Finished' => 'Finished',
);*/
$app_list_strings['foc_type_payment_list'] = array (
    ''                      => "-none-",
    "Staff"                 => "Staff",     
    "Referral"              => "Referral",
    "Retake"                => "Retake",     
    "Corporate"             => "Corporate", 
    "MKT local"             => "Marketing",  
    "Other"                 => "Other",
);
$app_list_strings['type_j_sponsor_list'] = array (
    'Discount'  => 'Discount',
    'Sponsor'   => 'Voucher',
);
$app_list_strings['payment_method_junior_list'] = array (
    '' => '-none-',
    'Cash' => 'Cash',
    'Card' => 'Card',
    'Bank Transfer' => 'Bank Transfer',
    'Other' => 'Other',
);

$app_list_strings ['emailTemplates_type_list'] = array (
    '' => '' ,
    'campaign' => 'Campaign' ,
    'email' => 'Email',
    'sms' => 'SMS',
    'workflow' => 'Workflow',
);

$app_list_strings ['sms_supplier_options'] = array (
    'mobifone'     => 'Mobifone' ,
    'viettel'     => 'Viettel' ,
    'vinaphone' => 'Vinaphone',
    'vietnamobile' => 'Vietnamobile',
    'sphone'         => 'Sphone',
    'gmobile'         => 'Gmobile',
    'other'         => 'Other',
);

$app_list_strings ['campaign_code_list'] = array (
    ''     => '-none-' ,
);
$app_list_strings ['convert_type_list'] = array (
    'To Hour'     => 'To Hour' ,
    'To Amount'   => 'To Amount' ,
);

$app_list_strings ['phone_number_prefix_options'] = array (
    '8490'  => 'mobifone',
    '8493'  => 'mobifone',
    '84120' => 'mobifone',
    '84121' => 'mobifone',
    '84122' => 'mobifone',
    '84126' => 'mobifone',
    '84128' => 'mobifone',
    '8496'  => 'viettel',
    '8497'  => 'viettel',
    '8498'  => 'viettel',
    '84162' => 'viettel',
    '84163' => 'viettel',
    '84164' => 'viettel',
    '84165' => 'viettel',
    '84166' => 'viettel',
    '84167' => 'viettel',
    '84168' => 'viettel',
    '84169' => 'viettel',
    '8486'  => 'viettel',
    '8491'  => 'vinaphone',
    '8494'  => 'vinaphone',
    '84123' => 'vinaphone',
    '84124' => 'vinaphone',
    '84125' => 'vinaphone',
    '84127' => 'vinaphone',
    '84129' => 'vinaphone',
    '8492'  => 'vietnamobile',
    '84188' => 'vietnamobile',
    '8495'  => 'sphone',
    '84993' => 'gmobile',
    '84994' => 'gmobile',
    '84995' => 'gmobile',
    '84996' => 'gmobile',
    '84199' => 'gmobile',
);

$app_list_strings['lock_homepage_options'] = array(
    ''      =>  '',
    'lock'  =>'Do not allow user to edit homepage',
    'merge' =>'Allow user to add tabs, but overwrite base tabs',
    'merge_delete'  =>'Allow user to add/delete tabs, but overwrite base tabs'
);

$app_list_strings['use_type_options'] = array(
    'Amount'    => 'Amount',
    'Hour'      => 'Hour',
);

$app_list_strings['payment_detail_type_options'] = array(
    'Normal'    => 'Normal',
    'Deposit'   => 'Deposit',
);

$app_list_strings['report_list_list'] = array(
    ''          => '-none-',
    'Marketing' => 'Marketing',
    'Sales'     => 'Sales',
    'Academic'  => 'Academic',   
    'Scheduler' => 'Scheduler',  
    'Accounting'=> 'Accounting',
    'Teacher'   => 'Teacher',  
    'BOD'       => 'BOD',
);
$app_list_strings['category_list'] = array(
    ''           => '-none-',
    'Student'    => 'Student',
    'Work'       => 'Work',
);

$app_list_strings['holiday_apply_for_options'] = array(
    ''    => '-none-',
    'forall'    => 'For All Stdent',
);

$app_list_strings['new_sale_range'] = array(
    'HN10.TG'   => '45',
    'HN11.HDT'  => '45',
    'HCM8.PMH'  => '45',
    'BH1.PVT'   => '45',
    'HCM9.GV'   => '45',
    'HCM10.LVV' => '45',
    'BD1.THD'   => '45',
    'HP2.HBT'   => '45',
    'HN12.NHT'  => '45',
    'HN13.NVC'  => '45',
);
$app_list_strings['new_sale_target_deposit'] = array(
    'HN'    => '5000000',
    'HCM'   => '5000000',
    'BN'    => '5000000',
    'HP'    => '5000000',
    'DN'    => '5000000',
    'BH'    => '5000000',
);
$app_list_strings['situation_error_status_list'] = array(
    'Not Started'     => 'Not Started',
    'In Progress'     => 'In Progress',
    'Finished'        => 'Finished',
);


$app_list_strings['teaching_type_options'] = array(
    ''  => '',
    'main_teacher'  => 'Main Teacher',
    'cover'         => 'Cover',
    'take_over'     => 'Take Over',
    'make_up'       => 'Make Up',
);

$app_list_strings['reason_situation_list'] = array(
    ''  => '',
    'Student progress:'         => 'Student progress:',
    'High tuition fee:'         => 'High tuition fee:',
    'Move to competitors:'      => 'Move to competitors:',
    'We do not offer the courseware/academic program they want'     => 'We do not offer the courseware/academic program they want',
    'Teacher change:'           => 'Teacher change:',
    'House moving/Go abroad:'   => 'House moving/Go abroad:',
    'Temporary stop:'           => 'Temporary stop:',
    'Busy schedule:'            => 'Busy schedule:',
    'Summer schedule:'          => 'Summer schedule:',
    'Too young to start a course'     => 'Too young to start a course',
    'Other:'                    => 'Other:'
);

$app_list_strings['case_type_options'] = array(
    'Complaint' => 'Complaint',
    'Suggestion' => 'Suggestion',
    'Question' => 'Question',
);

$app_list_strings['case_type_options_for_vn'] = array(
    'Complaint' => 'Phàn nàn',
    'Suggestion' => 'Góp ý',
    'Question' => 'Thắc mắc',
);

$app_list_strings['case_target_options'] = array(
    'EC'  => 'EC',
    'IT'  => 'IT',
);
$app_list_strings['jfeedback_slc_target_list'] = array(  //  #portal
    'EC'  => 'EC',
    'IT'  => 'IT',
);

$app_list_strings['case_status_dom_for_vn'] = array(
    'New' => 'Mới',
    'Assigned' => 'Đã phân công',
    'Closed' => 'Đã xong',
    'Pending Input' => 'Tạm hoãn',
    'Rejected' => 'Đã bỏ qua',
    'Duplicate' => 'Trùng lặp',
);

// -------------End edit by Bui Kim Tung --------------------//
$app_list_strings['moduleList']['J_Coursefee']='Course Fee';
$app_list_strings['moduleListSingular']['J_Coursefee']='Course Fee';
$app_strings['LBL_GROUPTAB6_1437012463'] = 'All module junior';

$app_list_strings['moduleList']['J_Kindofcourse']='Course';
$app_list_strings['moduleListSingular']['J_Kindofcourse']='Course';
$app_list_strings['moduleList']['ProductTemplates'] ='Book and Gift';
$app_list_strings['moduleList']['Meetings']='Schedule';
$app_list_strings['moduleListSingular']['Meetings']='Schedule';
$app_strings['LBL_TABGROUP_SALES'] = 'Academic';   

$app_strings['LBL_TABGROUP_MARKETING'] = 'Marketing';

$app_strings['LBL_GROUPTAB4_1442479371'] = 'Configurations';

// App string by Tung Bui
$app_strings['LBL_VIEW_MAP'] = 'View Map';
$app_strings['LBL_STATE'] = 'District/Ward';
$app_strings['LBL_REFRESH_BUTTON_TITLE'] = 'Refresh';
// End Tung Bui

/*app list string by Trung Nguyen*/
//cancel reason list
$app_list_strings['session_cancel_reason_options'] = array(
    'Student request' => 'Student request',
    'Weather' => 'Weather',
    'Teacher holiday' => 'Teacher holiday',
    'Teacher sick' => 'Teacher sick',
    'Teacher leaving' => 'Teacher leaving',
    'Public holiday' => 'Public holiday',
    'Other'         => 'Other',
);
$app_list_strings['ptresult_student_options'] = array(   //2016.01.04
    '' => '-None-',
    'Leads' => 'Lead',
    'Contacts' => 'Student',
);

$app_list_strings['month_report_list'] = array(   //2016.01.04
    '01' => 'Jan',
    '02' => 'Feb',
    '03' => 'Mar',
    '04' => 'Apr',
    '05' => 'May',
    '06' => 'Jun',
    '06' => 'Jul',
    '08' => 'Aug',
    '09' => 'Sep',
    '10' => 'Oct',
    '11' => 'Nov',
    '12' => 'Dec',
);
$app_list_strings['date_range_search_dom'] = array(
    '=' => 'Equals',
    'not_equal' => 'Not On',
    'greater_than' => 'After',
    'less_than' => 'Before',
    'last_7_days' => 'Last 7 Days',
    'next_7_days' => 'Next 7 Days',
    'last_30_days' => 'Last 30 Days',
    'next_30_days' => 'Next 30 Days',
    'last_month' => 'Last Month',
    'this_month' => 'This Month',
    'next_month' => 'Next Month',
    'last_year' => 'Last Year',
    'this_year' => 'This Year',
    'next_year' => 'Next Year',
    'between' => 'Is Between',
);
$app_list_strings['meeting_status_dom'] = array(
    'Planned' => 'Planned',
    'Held' => 'Held',
    'Not Held' => 'Not Held',
);
/*end*/


$app_list_strings['timesheet_tasklist_list']=array (
    ''              => '-none-',
    'Event'         => 'Event',
    'Marketing'     => 'Marketing',
    'eLearn hours'  => 'eLearn hours',
    'Standby'       => 'Standby',
    'Demo'          => 'Demo',
    'Placement Test'=> 'Placement Test',
    'Other'         => 'Other',
);

$app_list_strings['hr_center_code']=array (
    'BN1.NSL' => 'BNE.BN1',
    'DN1.DD' => 'DNE.DN1',
    'BH1.PVT' => 'BHE.BH1',
    'HCM1.DBP' => 'SGE.S01',
    'HCM2.PNT' => 'SGE.S02',
    'HCM7.NT360' => 'SGE.S03',
    'HCM4.TBT' => 'SGE.S04',
    'HCM5.LDH' => 'SGE.S05',
    'HCM6.BC' => 'SGE.S06',
    'HCM7.PNT360' => 'SGE.S07',
    'HN1.PH' => 'HNE.H01',
    'HN2.NNV' => 'HNE.H02',
    'HN3.HQV' => 'HNE.H03',
    'HN4.LG' => 'HNE.H04',
    'HN5.NVL' => 'HNE.H05',
    'HN6.XT360' => 'HNE.H06',
    'HN7.VQ' => 'HNE.H07',
    'HN8.PH360' => 'HNE.H08',
    'HN9.XD' => 'HNE.H09',
    'HN10.TG' => 'HNE.H10',
    'HN11.HDT'=> 'HNE.H11',
    'HP1.LHP' => 'HPE.HP1',
    'HP2.HBT' => 'HPE.HP2',
    'HCM9.GV' => 'SGE.S09',
    'HCM8.PMH' => 'SGE.S08',
    'HN2.LVV' => 'HNE.H02',
);
/**********TRUNG***************/
$app_list_strings['gradeconfig_type_options']=array (
    ''      => '-none-',
    'Progress'              => 'Progress',
    'Commitment'    => 'Commitment',
    'Overall'       => 'Overall',
);
$app_list_strings['gradeconfig_minitest_options']=array (
    ''      => '-none-',
    'minitest1'   => 'Mini Test 1',
    'minitest2'   => 'Mini Test 2',
    'minitest3'   => 'Mini Test 3',
    'minitest4'   => 'Mini Test 4',
    'minitest5'   => 'Mini Test 5',
    'minitest6'   => 'Mini Test 6',
    'project1'   => 'Project 1',
    'project2'   => 'Project 2',
    'project3'   => 'Project 3',
    'project4'   => 'Project 4',
    'project5'   => 'Project 5',
    'project6'   => 'Project 6',
);

$app_list_strings['option_comment_list']=array (
    '' => '-Default-',
    //            'comment_Progress_list' => 'Progress_list',
    //            'comment_Commitment_list' => 'Commitment_list',
    //            'comment_Overall_list' => 'Overall_list',
);
$app_list_strings['leaving_type_student_list']=array (
    'P'    => 'P',
    'A'    => 'A',
);

$app_list_strings['comment_Progress_list']=array (
    '' => '--- Select ---',
    'progress1' => 'Congratulations on an excellent course!  You have far exceeded our expectations for progress.  Your results show that you have worked tirelessly to improve your chances of success in the exam.  We are confident that you will do exceptionally well in the IELTS and will attain the score that you have set out to achieve.  You are a natural linguist with a gift for [SKILL] and are approaching fluency in the English language.',
    'progress2' => 'Excellent work, well done!  You have exceeded our expectations for progress.  Your results show that you have worked hard to improve your chances of success in the exam.  We are confident that you will do well in the IELTS and attain the score that you have set out to achieve.  You are a talented linguist with particular skill in [SKILL].  You will become fluent if you continue learning at this rate.',
    'progress3' => 'Very good job!  You have made excellent progress on this course, especially in [SKILL].  Your results show that you have worked hard to improve your chances of success in the exam.  We are confident that you will do well in the IELTS and attain the score that you have set out to achieve.',
    'progress4' => 'Well done.  You have worked hard and made progress on this course.  Your results have improved since you started and you are ready to take the IELTS exam. Be sure to review what you\'ve learnt and continue practicing English, especially [SKILL], up until the date of the exam.  If you do this we are sure you achieve your target result.',
    'progress5' => 'You have made some progress on this course in some areas, but you need to continue practicing and reviewing what was learnt on the course in order to have the best chance of success in the exam.  You made very good progress in [SKILL] but need to take opportunities to further develop your [SKILL].',
    'progress6' => 'You have not made as much progress on this course as we would expect.  Although you have seen some progress in your [SKILL], you must take further opportunities to develop your [SKILL] and [SKILL] in order to have the best chance of success in the exam.  At this stage, we would recommend further study before you book the IELTS exam.',
    'progress7' => 'Unfortunately, you have not made progress on this course.  We do not recommend booking the IELTS exam yet as you are unlikely to attain your target results.  You may need to reexamine your study skills in order to find a way of making progress in your language level.',
);

$app_list_strings['comment_Commitment_list']=array (
    '' => '--- Select ---',
    'commitment1' => 'You have been an exemplary student on this course.  Your attendance and participation were perfect and your homework was submitted punctually and to a consistently high standard.  In class, you were a great support to your teacher and your peers.  We hope that you continue to enjoy studying English after this course because you are extremely good at it!',
    'commitment2' => 'You have been an excellent student on this course.  Your attendance and participation were almost perfect and you completed your homework on time and to a high standard.  In class, you were a support to your teacher and your peers.  We wish you every success and firmly believe that you have the ability to achieve whatever targets you set yourself in English.',
    'commitment3' => 'You have been very committed to your learning on this course.  You studied hard both in and outside of class and organised yourself well.  In class, you were enthusiastic and helpful.  Your study skills should enable you to achieve any target that you set yourself in English.',
    'commitment4' => 'You have shown good commitment to your learning on this course.  For the most part you were hardworking and well organised.  In classwork, you contributed to your teams and took advantage of many learning opportunities.  You could have pushed yourself further by [ADD RECOMMENDATION OR ADVICE HERE].  Keep up the good work and your English will continue to improve.',
    'commitment5' => 'Your commitment to your learning on this course was a little below the requirements.  Your organisation and dedication fell short at times and this impacted on your potential for progress.  In class, you were good at [ADD STRENGTH] but you could improve [ADD WEAKNESS].  We wish you well on your language learning journey and recommend that you keep working on your study skills to reach your full potential.',
    'commitment6' => 'You showed a lack of commitment to your learning on this course, and this affected your results.  We recommend that you work on your study skills if you wish to make greater progress in English.',
    'commitment7' => 'We are sorry to say that your behaviour on this course was disruptive not only to your own learning, but to others as well.  We hope that as you mature, you discover that learning can be less effort and much more enjoyable when you give it your commitment.',
);

$app_list_strings['comment_Overall_list']=array (
    '' => '--- Select ---',
    'overall1' => 'Placeholder Text 1',
    'overall2' => 'Placeholder Text 2',
    'overall3' => 'Placeholder Text 3',
    'overall4' => 'Placeholder Text 4',
    'overall5' => 'Placeholder Text 5',
    'overall6' => 'Placeholder Text 6',
    'overall7' => 'Placeholder Text 7',
);

$app_list_strings['gradebook_status_options']=array (
    'Approved' => 'Approved',
    'Not Approval' => 'Not Approval',
);
/*************************/
$app_list_strings['c_sms_module_selected_list']=array (
    '-BLANK-' => '-BLANK-',
    'Contacts' => 'Students',
    'C_Teachers' => 'Teachers',
    'J_StudentSituations' => 'Student Situations',
    'J_PTResult' => 'PT Result',
    'Leads' => 'Leads',
);
                                                
$app_list_strings['region_list'] = array (
    '' => '-none-',
    'South' => 'South',
    'North' => 'North',
);

$app_list_strings['call_type_dom'] = array (
    '' => '-none-',
    '3rd Call' => '3rd Call',
    '7th Call' => '7th Call',
);

$app_list_strings['map_kind_of_code_dom'] = array (
    '' => '',
    'Beginner'      => 'Beginner',
    'Elementary'    => 'Elementary',
    'Pre Inter'     => 'Pre-intermediate',
    'Inter'         => 'Intermediate',
    'Upper Inter'   => 'Upper-intermediate',
    'Advance'       => 'Advance',
    'Master'        => 'Master',
    'Other'         => 'Other',
);
$app_list_strings['voucher_use_time'] = array (
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
    'N' => 'N',
);
$app_list_strings['voucher_status_dom'] = array (
    'Inactive'  => 'Inactive',
    'Activated' => 'Activated',
    'Expired'   => 'Expired'
);
$app_list_strings['voucher_type_options'] = array (
    'amount'  => 'Amount',
    'percent' => 'Percent',       
);
$app_list_strings ['myelt_link_list'] = array (
);
//endl
$app_strings['LBL_GROUPTAB2_1480343614'] = 'Corporate';

$app_strings['LBL_GROUPTAB3_1486717995'] = 'Marketing';

$app_strings['LBL_GROUPTAB4_1487735757'] = 'Report';
$app_strings['LBL_AJAX_PLEASE_WAIT'] = 'Please wait...';
$app_strings['LBL_AJAX_SAVE_SUCCESS'] = 'Successfully';
$app_strings['LBL_AJAX_ERROR'] = 'Something error, please try again!';
$app_strings['LBL_CREATE_DEMO_USER'] = 'Create Demo User';
$app_strings['LBL_SEARCH_FORM_SHOW_LESS'] = 'Show Less';
$app_strings['LBL_SEARCH_FORM_SHOW_MORE'] = 'Show More';
$app_strings['LBL_LISENCE'] = 'Lisence';
$app_strings['LBL_CHECK_LISENCE_NOW'] = 'Check Lisence Info Now';
$app_strings['LBL_LISENCE_ONLINECRM_INFO'] = '<h2>Please contact OnlineCRM for more information!</h2><br>
Hotline: 0935 543 543<br/>
Email: info@onlinecrm.vn<br/>
Website: www.onlinecrm.vn<br/>';
$app_strings['LBL_LISENCE_WARNING_LIMIT_STOP'] = '<h2>You have reached the lisence limit so all access is now prohibited. </h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];
$app_strings['LBL_LISENCE_WARNING_LIMIT_USERS'] = '<h2>You can only create up to limit_number users for cloud_version version! </h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];
$app_strings['LBL_LISENCE_WARNING_LIMIT_LEADS'] = '<h2>You can only create up to limit_number leads for cloud_version version! </h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];
$app_strings['LBL_LISENCE_WARNING_LIMIT_STUDENTS'] = '<h2>You can only create up to limit_number students for cloud_version version! </h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];
$app_strings['LBL_LISENCE_EXPIRIED'] = '<h2>License is expired so all access is now prohibited. </h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];



$app_list_strings['graduated_rate_list'] = array (
    '' => '-none-',
    'Excellent' => 'Excellent',
    'Very good' => 'Very good',
    'Good'      => 'Good',
    'Average good'=> 'Average good',
    'Ordinary'    => 'Ordinary',
);
$app_list_strings['month_options'] = array (
    ''=>"",
    '01'=>"01",
    '02'=>"02",
    '03'=>"03",
    '04'=>"04",
    '05'=>"05",
    '06'=>"06",
    '07'=>"07",
    '08'=>"08",
    '09'=>"09",
    '10'=>"10",
    '11'=>"11",
    '12'=>"12",
);
$app_list_strings['day_options'] = array (
    ''=>"",
    '01'=>"01",
    '02'=>"02",
    '03'=>"03",
    '04'=>"04",
    '05'=>"05",
    '06'=>"06",
    '07'=>"07",
    '08'=>"08",
    '09'=>"09",
    '10'=>"10",
    '11'=>"11",
    '12'=>"12",
    '13'=>"13",
    '14'=>"14",
    '15'=>"15",
    '16'=>"16",
    '17'=>"17",
    '18'=>"18",
    '19'=>"19",
    '20'=>"20",
    '21'=>"21",
    '22'=>"22",
    '23'=>"23",
    '24'=>"24",
    '25'=>"25",
    '26'=>"26",
    '27'=>"27",
    '28'=>"28",
    '29'=>"29",
    '30'=>"30",
    '31'=>"31",
);
$app_list_strings['status_paid_payment_list'] = array(
    'Fully Paid' => 'Fully Paid',
    'Partly Paid' => 'Partly Paid',
    'Unpaid' => 'Unpaid',
);
$app_list_strings['moduleList']['J_PaymentDetail']='Receipt';
$app_list_strings['moduleListSingular']['J_PaymentDetail']='Receipt';
$app_list_strings['moduleList']['J_Voucher']='Voucher Code';
$app_list_strings['moduleListSingular']['J_Voucher']='Voucher Code';
$app_list_strings['moduleList']['J_Payment']='Enrollment & Payment';
$app_list_strings['moduleListSingular']['J_Payment']='Enrollment & Payment';  
$app_strings['LBL_GROUPTAB0_1527137856'] = 'Sale';

$app_strings['LBL_GROUPTAB1_1527137856'] = 'Enrollment';

$app_strings['LBL_GROUPTAB5_1527137856'] = 'Calendar';
$app_strings['LBL_COMPOSE_SMS'] = 'Compose SMS';

$app_strings['LBL_NONE_SELECTED'] = 'None selected';
$app_strings['LBL_ALL_SELECTED'] = 'All selected';
$app_strings['LBL_SELECT_ALL'] = 'Select All';
$app_strings['LBL_SELECT_OF_ALL'] = 'All seletecd';
$app_strings['LBL_MULTISELECT_NO_RESULT'] = 'No matches found';
// $app_strings['LBL_SELECTED'] => 'selected';

$app_list_strings['moduleList']['J_StudentSituations']='Study History';
$app_list_strings['moduleListSingular']['J_StudentSituations']='Study History';
$app_list_strings['moduleList']['Contacts']='Student';
$app_list_strings['moduleList']['Opportunities']='Enrollment';
$app_list_strings['moduleList']['Cases']='Feedback';
$app_list_strings['moduleList']['Notes']='Note';
$app_list_strings['moduleList']['Calls']='Call';
$app_list_strings['moduleList']['Emails']='Email';
$app_list_strings['moduleList']['Tasks']='Task';
$app_list_strings['moduleList']['Leads']='Lead';
$app_list_strings['moduleList']['Contracts']='Contract';
$app_list_strings['moduleList']['Quotes']='Quote';
$app_list_strings['moduleList']['Products']='Product';
$app_list_strings['moduleList']['Reports']='Report';
$app_list_strings['moduleList']['Project']='Project';
$app_list_strings['moduleList']['Campaigns']='Campaign';
$app_list_strings['moduleList']['Documents']='Document';
$app_list_strings['moduleList']['Notifications']='Notification';
$app_list_strings['moduleList']['Prospects']='Target';
$app_list_strings['moduleList']['ProspectLists']='Marketing List';
$app_list_strings['moduleList']['C_Contacts']='Contact';
$app_list_strings['moduleList']['Holidays']='Holiday';
$app_list_strings['moduleListSingular']['Cases']='Feedback';
$app_list_strings['moduleListSingular']['Notifications']='Notification';
$app_list_strings['moduleListSingular']['ProspectLists']='Marketing List';
$app_list_strings['moduleListSingular']['C_Contacts']='Contact';
$app_list_strings['moduleListSingular']['bc_survey']='Survey';
$app_list_strings['moduleListSingular']['bc_survey_automizer']='Survey Automation';
$app_list_strings['moduleListSingular']['bc_survey_language']='Survey Language';
$app_list_strings['moduleListSingular']['C_DuplicationDetection']='Duplication Detection';
$app_list_strings['moduleListSingular']['C_FieldHighlighter']='Field Highlighter';
$app_list_strings['moduleListSingular']['C_HelpTextConfig']='Help Text Config';
$app_list_strings['moduleListSingular']['Holidays']='Holiday';
$app_list_strings['moduleListSingular']['J_Inventory']='Inventory';
$app_list_strings['moduleList']['C_Rooms']='Room';
$app_list_strings['moduleList']['C_Teachers']='Teacher';
$app_list_strings['moduleList']['C_KeyboardSetting']='Keyboard Setting';
$app_list_strings['moduleList']['C_Memberships']='Membership';
$app_list_strings['moduleListSingular']['J_Invoice']='Invoice';
$app_list_strings['moduleListSingular']['J_GradebookConfig']='Gradebook Config';
$app_list_strings['moduleListSingular']['J_Gradebook']='Gradebook';
$app_list_strings['moduleListSingular']['J_GradebookDetail']='Gradebook Detail';
$app_list_strings['moduleListSingular']['J_Partnership']='Partnership';
$app_list_strings['moduleListSingular']['J_PTResult']='PT Result';
$app_list_strings['moduleListSingular']['C_Rooms']='Room';
$app_list_strings['moduleListSingular']['J_School']='School';
$app_list_strings['moduleListSingular']['J_Sponsor']='Sponsor';
$app_list_strings['moduleListSingular']['C_Teachers']='Teacher';
$app_list_strings['moduleListSingular']['J_Teachercontract']='Teacher Contract';
$app_list_strings['moduleListSingular']['C_KeyboardSetting']='Keyboard Setting';
$app_list_strings['moduleListSingular']['J_Loyalty']='Loyalty';
$app_list_strings['moduleListSingular']['C_Memberships']='Membership';
$app_list_strings['moduleList']['Accounts']='Corporate';