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

$app_strings['LBL_QUICK_REPAIR_AND_REBUILD'] = 'Sửa chữa nhanh';
$app_strings['LBL_STUDIO'] = 'Công cụ phát triển';
$app_strings['LBL_REPAIR_RELATIONSHIP'] = 'Sửa chữa quan hệ';
$app_strings['LBL_DISPLAY_MODULES'] = 'Hiển thị Modules and Subpanels';
$app_strings['LBL_GUIDE'] = 'Hướng dẫn';
$app_strings['LBL_USERS'] = 'Quản lý người dùng';
$app_strings['LBL_CREATE_NEW_USER'] = 'Tạo mới người dùng';

$app_list_strings['type_meeting_list'] = array (
    'Meeting' => 'Lịch hẹn',
    'Consultant' => 'Tư vấn',
    'Event'         => 'Sự kiện',
    'Marketing'     => 'Quảng cáo',
    'eLearn hours'  => 'eLearn hours',
    'Standby'       => 'Standby',
    'Placement Test'    => 'Thi đầu vào',
    'Session'           => 'Buổi học',
    'Demo'              => 'Demo',
    'Other'             => 'Khác',
);

$app_list_strings['level_lead_list'] = array (
    'Not Interested' => 'Không quan tâm',
    'Consider (Low)' => 'Xem xét (Thấp)',
    'Interested' => 'Quan tâm',
    'Ready To PT/Demo' => 'Sẵn sàng cho PT/Demo',
    'Consider (High)' => 'Xem xét (Cao)',
);
$app_list_strings['gender_lead_list'] = array (
    '' => '-Chưa chọn-',
    'Male' => 'Nam',
    'Female' => 'Nữ',
);

$app_list_strings['student_type_list'] = array (
    'Public' => 'Học viên lẻ',
    'Corporate' => 'Công ty',           
    'Public/Corp' => 'Doanh nghiệp',
);

$app_list_strings['contact_status_list'] = array (
    'no_class' => 'Không học',
    'waiting_class' => 'Đang chờ lớp',
    'in_process' => 'Đang học',
    'over_grade' => 'Quá tuối',           
    'graduated' => 'Đã tốt nghiệp',           
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
    'Complaint' => 'Phàn nàn',
    'Suggestion' => 'Gợi ý',
    'Question' => 'Câu hỏi',
);

$app_list_strings['delivery_status_list'] = array (
    'RECEIVED' => 'THÀNH CÔNG',
    'FAILED' => 'THẤT BẠI',
);
$app_list_strings['moduleListSingular']['Contacts']='Thiếu nhi';
$app_list_strings['moduleList']['Opportunities']='Tuyển sinh';
$app_list_strings['moduleListSingular']['Opportunities']='Đăng ký';

$app_list_strings['month_list_view'] = array (
    '0'=>"",
    '01'=>"Tháng 1",
    '02'=>"Tháng 2",
    '03'=>"Tháng 3",
    '04'=>"Tháng 4",
    '05'=>"Tháng 5",
    '06'=>"Tháng 6",
    '07'=>"Tháng 7",
    '08'=>"Tháng 8",
    '09'=>"Tháng 9",
    '10'=>"Tháng 10",
    '11'=>"Tháng 11",
    '12'=>"Tháng 12",
);

$app_list_strings['class_type_list'] = array (
    '' => '-Chưa chọn-',
    'Practice' => 'Thực hành',
    'Skill' => 'Kỹ năng',
    'Connect Club' => 'Câu lạc bộ',
    'Connect Event' => 'Sự kiện',
    'Waiting Class' => 'Lớp chờ',
);

$app_list_strings['membership_level_list'] = array (
    ''          => '-Chưa chọn-',
    'N/A'       => 'N/A',
    'Blue'      => 'Xanh',
    'Gold'      => 'Vàng',
    'Platinum'  => 'Bạch kim',
);

$app_list_strings['notifications_severity_list'] = array(
    'Alert' => 'Thông báo',
    'Info' => 'Thông tin',
    'Other' => 'Khác',
    'Success' => 'Thành công',
    'Warning' => 'Cảnh báo',
);

$app_list_strings['target_status_dom'] = array (
    'New'           => 'Mới',
    'In Process'    => 'Đang tiến hành',
    'Converted'     => 'Đã chuyển đổi',
);

$app_list_strings['teacher_type_list'] = array (
    '' => '- Chưa chọn -',
    'FT' => 'Fulltime',
    'PT' => 'Parttime',
    'AM' => 'Giám đốc học thuật',
    'AC' => 'Giáo vụ (AC)',
    'CST' => 'Giáo viên trung cấp',
    'ST' => 'Giáo viên lâu năm',
    'MT' => 'Giáo viên cố vấn',
);

$app_list_strings['teacher_status_list'] = array (
    'Active' => 'Hoạt động',
    'Inactive' => 'Không hoạt động',
);

$app_list_strings['room_status_list'] = array (
    'Active' => 'Hoạt động',
    'Inactive' => 'Không hoạt động',
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
    'Holidays' => 'Ngày nghỉ lễ',
    'Sick Leave' => 'Nghỉ bệnh',
    'Unpaid Leave' => 'Nghỉ phép không lương',
    'Marriage Leave' => 'Nghỉ phép kết hôn',
    'Maternity Leave' => 'Nghỉ thai sản',
    'Accident Leave' => 'Nghỉ tai nạn',
    'Public Holiday' => 'Ngày nghỉ lễ chung',
    'Day Off' => 'Day Off',
);

$app_list_strings['session_status_list'] = array (
    '' => '',
    'Cancelled' => 'Đã hủy',
    'Make-up' => 'Học bù',
    'Cover' => 'Cover',
    'Not Started' => 'Chưa bắt đầu',
    'In Progress' => 'Diễn ra hôm nay',
    'Finish' => 'Kết thúc',
);

$app_list_strings['moduleList']['Accounts']='Công ty';
$app_list_strings['moduleListSingular']['Accounts']='Công ty';
$app_strings['LBL_GROUPTAB6_1406016993'] = '360';
//Datatables Language
$app_strings['LBL_JDATATABLE1'] = 'Hiển thị';
$app_strings['LBL_JDATATABLE2'] = ' mục';
$app_strings['LBL_JDATATABLE3'] = 'Tìm kiếm:';
$app_strings['LBL_STT_D'] = 'STT';
$app_strings['LBL_TEN_D'] = 'Tên';
$app_strings['LBL_JDATATABLE6'] = 'Đang hiển thị ';
$app_strings['LBL_JDATATABLE7'] = ' đến ';
$app_strings['LBL_JDATATABLE8'] = ' tổng ';
$app_strings['LBL_JDATATABLE9'] = 'Đầu tiên';
$app_strings['LBL_JDATATABLE10'] = 'Kế tiếp';
$app_strings['LBL_JDATATABLE11'] = 'Trước';
$app_strings['LBL_JDATATABLE12'] = 'Cuối cùng';
$app_strings['LBL_JDATATABLE13'] = 'Không tìm thấy kết quả';
$app_strings['LBL_JDATATABLE14'] = 'Không có dữ liệu';
$app_strings['LBL_JDATATABLE15'] = 'Hiển thị 0 mục';
$app_strings['LBL_CAMPAIGNS_SEND_SMS_QUEUED'] = 'Gửi chiến dịch SMS';
$app_strings['LBL_GROUPTAB7_1407210988'] = 'Nhóm mới';

$app_list_strings['card_type_payments_list']=array (
    '' => '-Chưa chọn-',
    'MasterCard' => 'MasterCard',
    'VisaCard' => 'VisaCard',
    'VietcomBank' => 'VietcomBank',
    'ATM' => 'ATM',
    'JCB' => 'JCB',
    'AmericanExpress' => 'American Express (AMEX)',
    'Other' => 'Khác',
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
    '' => '-Chưa chọn-',
    'VietcomBank' => 'VietcomBank',
    'Sacombank' => 'Sacombank',
    'ANZ' => 'ANZ',
    'Vietin bank' => 'Vietin bank',
    'BIDV' => 'BIDV',
    'Techcombank' => 'Techcombank',
    'Other' => 'Khác',
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
    'Admin Hours' => 'Công việc khác',
    'Teaching Hours' => 'Giờ dạy',
);

$app_list_strings['moduleList']['C_Timesheet']='Công việc khác';
$app_list_strings['moduleListSingular']['C_Timesheet']='Công việc khác';
$app_strings['LBL_TEAM_MANAGEMENT'] = 'Quản lý chi nhánh';
$app_strings['LBL_UNIMPERSONATE'] = 'Đăng nhập với tài khoản {user_name}';

$app_list_strings['kind_of_course_360_list']=array (
    ''          => '-Chưa chọn-',
    'Flexi'     => 'Flexi',
    'Mobile'    => 'Mobile',
    'Access'    => 'Access',
    'Premium'   => 'Premium',
    'IELTS'     => 'IELTS',
    'CORP'      => 'CORP',
);
// =============== END APP_STRINGS ===============/

// =============== BEGIN APP_STRINGS JUNIOR ===============/
// -------------- Begin Lap Nguyen -----------------/
//$app_list_strings['kind_of_course_list'] = array(
//    ''              => '-Chưa chọn-',
//    'Academic'  => 'Academic',
//    'TOEIC'     => 'TOEIC',
//    'IELTS'     => 'IELTS',
//    'CERF'      => 'CERF',
//    //    'Outing Trip'   => 'Outing Trip',
//    'Customize'       => 'Customize',
//    'Other'     => 'Khác',
//);
//
//$app_list_strings['level_program_list'] = array(
//    ''   => '-Chưa chọn-',
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
//    'Other'    => 'Khác',
//);
//$app_list_strings['module_program_list'] = array(
//    ''  => '-Chưa chọn-',
//    '1' => '1',
//    '2' => '2',
//    '3' => '3',
//    '4' => '4',
//    '5' => '5',
//    '6' => '6',
//);
//
//$app_list_strings['full_kind_of_course_list'] = array(   //Chu y: Khoang trang 1 la` KOC VD:Academic,TOEIC
//    ''              => '-Chưa chọn-',
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
//
//$app_list_strings['level_PT_program_list'] = array(
//    'none'   => '',
//    'Beginner' => 'Beginner',
//    'Elementary 1' => 'Elementary 1',
//    'Elementary 2' => 'Elementary 2',
//    'Pre-Intermediate 1' => 'Pre-Intermediate 1',
//    'Pre-Intermediate 2' => 'Pre-Intermediate 2',
//    'Intermediate 1'=> 'Intermediate 1',
//    'Intermediate 2'=> 'Intermediate 2',
//    'Upper-Intermediate 1' => 'Upper-Intermediate 1',
//    'Upper-Intermediate 2' => 'Upper-Intermediate 2',
//    'Foundation' => 'Foundation',
//    'Empowerment' => 'Empowerment',
//    'Excellence'   => 'Excellence',
//    'Expert'   => 'Expert',
//    'Power'    => 'Power',
//    'Passport' => 'Passport',
//    'Perfect'  => 'Perfect',
//    'Pro'      => 'Pro',
//    'A1' => 'A1',
//    'A2' => 'A2',
//    'B1' => 'B1',
//    'B2' => 'B2',
//    'C1' => 'C1',
//    'C2' => 'C2',
//    'Other'    => 'Khác',
//);

$app_list_strings['koc_score_pt_list'] = array(
    ''        => '-Chưa chọn-',
    '80-90'    => 'CERF C2|IELTS Pro',
    '71-80'    => 'CERF C1|IELTS Perfect',
    '61-70'    => 'CERF B2|IELTS Passport|TOEIC Expert|Academic Upper-Intermediate 2|Academic Upper-Intermediate 1',
    '46-60'    => 'CERF B1|IELTS Power|TOEIC Excellence|Academic Intermediate 2|Academic Intermediate 1',
    '31-45'    => 'CERF B1|TOEIC Empowerment|Academic Pre-Intermediate 2|Academic Pre-Intermediate 1',
    '16-30'    => 'CERF A2|TOEIC Foundation|Academic Elementary 2|Academic Elementary 1',
    '0-15'     => 'CERF A1|Academic Beginer',
);

$app_list_strings['period_program_list'] = array(
    ''        => '-Chưa chọn-',
    'Period A'    => 'Giai đoạn A',
    'Period B'    => 'Giai đoạn B',
    'Period C'    => 'Giai đoạn C',
);   
$app_list_strings['category_discount_list'] = array(
    ''        => '-none-',
    'Standard Discount'   => 'CK thường',  
    'Special Discount'    => 'CK đặc biệt',
);   
$app_list_strings['quantityy_list'] = array(
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4',
    '5' => '5',
);  
$app_list_strings['week_frame_class_list'] = array(
    'Mon' => 'Thứ hai',
    'Tue' => 'Thứ ba',
    'Wed' => 'Thứ tư',
    'Thu' => 'Thứ năm',
    'Fri' => 'Thứ sáu',
    'Sat' => 'Thứ bảy',
    'Sun' => 'Chủ nhật',
);
$app_list_strings['type_of_class_list'] = array(
    '' => '-Chưa chọn-',
    'Junior' => 'Junior',
    '360'    => '360',
);
$app_list_strings['revenue_type_list'] = array(
    'Enrolled'  => 'Đã đăng ký',
    'Moving In' => 'Di chuyển đến',
    'Demo'      => 'Demo',
    'Settle'    => 'Giải quyết',
    'OutStanding'=> 'Còn nợ',
    'Stopped'   => 'Đã dừng',
);
$app_list_strings['data_type_list'] = array(
    '360'             => '360',
    'Junior'        => 'Junior',
);
$app_list_strings['type_student_situation_list'] = array(
    '' => '-Chưa chọn-',
    'Enrolled'   => 'Đăng ký học',
    'Delayed'    => 'Đã dừng',
    'OutStanding'=> 'Học nợ (chưa trả)',
    'Demo'       => 'Học thử',
    'Settle'     => 'Học nợ (đã trả)',
    'Stopped'    => 'Đã dừng',
    'Moving Out' => 'Chuyển center (đi)',
    'Moving In'  => 'Chuyển center (đến)',
    'Waiting Class'  => 'Lớp chờ',
);

$app_list_strings['birth_month_list'] = array(
    ''  =>"-Chưa chọn-",
    '1'=>"Tháng 1",
    '2'=>"Tháng 2",
    '3'=>"Tháng 3",
    '4'=>"Tháng 4",
    '5'=>"Tháng 5",
    '6'=>"Tháng 6",
    '7'=>"Tháng 7",
    '8'=>"Tháng 8",
    '9'=>"Tháng 9",
    '1'=>"Tháng 10",
    '11'=>"Tháng 11",
    '12'=>"Tháng 12",
);

$app_list_strings['type_of_course_fee_list'] = array(
    ''         => '-Chưa chọn-',
    '1'     => '1 giờ',
    '2'     => '2 giờ',
    '4'     => '4 giờ',
    '12'     => '12 giờ',
    '18'     => '18 giờ',
    '36'     => '36 giờ',
    '40'     => '40 giờ',
    '46'     => '46 giờ',
    '56'     => '56 giờ',
    '60'     => '60 giờ',
    '72'     => '72 giờ',
    '102'     => '102 giờ',
    '106'     => '106 giờ',
    '108'     => '108 giờ',
    '144'     => '144 giờ',
    '180'     => '180 giờ',
);
$app_list_strings['type_coursefee_list'] = array(
    ''    => '-Chưa chọn-',
    'Hours'    => 'Giờ',
    'Days'     => 'Ngày',

);
$app_list_strings['fieldhighlighter_style_options'] = array (
    'blue' => 'Màu Blue',
    'bluelight' => 'Màu Blue Light',
    'red' => 'Màu Red',
    'redlight' => 'Màu Red Light',
    'black' => 'Màu Black',
    'yellow' => 'Màu Yellow',
    'yellowlight' => 'Màu Yellow Light',
    'green' => 'Màu Green',
    'violet' => 'Màu Violet',
    'orange' => 'Màu Orange',
    'crimson' => 'Màu Crimson',
    'blood' => 'Màu Blood',
    'dream' => 'Màu Dream',
    'bold' => 'In Đậm',
    'bolditalic' => 'In đậm và nghiêng',
    'boldunderline' => 'In đậm và gạch dưới',
    'italic' => 'Nghiêng',
    'underline' => 'Gạch dưới',
    'greencolor' => 'Màu xanh lá',
    'bluecolor' => 'Màu xanh',
    'redcolor' => 'Màu đỏ',
    'violetcolor' => 'Màu tím',
    'yellowcolor' => 'Màu vàng',
    'orangecolor' => 'Màu cam',
    'browncolor' => 'Màu nâu',
);

$app_list_strings['detailview_editable_config_type_options'] = array (
    'specific_fields' => 'Tắt trường',
    'whole_module' => 'Tắt mô-đun',
);

$app_list_strings['keyboardsetting_correction_type_options'] = array (
    'uppercase_all' => 'VIẾT HOA',
    'initial_capital' => 'Viết Hoa Từng Chữ ',
    'first_letter_capital' => 'Viết hoa chữ đầu',
    'lowercase_all' => 'chữ thường',
);

$app_list_strings['payment_payment_list'] = array (
    'Success' => 'Thành công',
    'Closed' => 'Xong',
);

$app_list_strings['duplication_preventive_type_options'] = array (
    'notify_only' => 'Chỉ thông báo',
    'notify_and_prevent' => 'Thông báo và không cho lưu',
);
$app_list_strings['situation_student_type_list'] = array (
    'Lead' => 'HV Tiềm năng',
    'Student' => 'Thiếu nhi',
);
$app_list_strings['sms_supplier_api'] = array (
    'VHT'       => 'VHT',
    'VIETGUYS'  => 'VIETGUYS',
    'VMG'       => 'VMG',
    'GAPIT'     => 'GAPIT',
);
$app_strings['LBL_DUPLICATION_STATUS_TITLE'] = 'Trạng thái check trùng';
$app_strings['LBL_DUPLICATION_DIALOG_TITLE'] = 'Dữ liệu bị trùng:';

$app_list_strings['level_school_list'] = array(
    ''    => '-Chưa chọn-',
    'Kindergarten'    => 'Mẫu giáo',
    'Primary school'  => 'Tiểu học',
    'Secondary school'=> 'THCS',
    'High school'     => 'THPT',
    'College'         => 'Cao đẳng',
    'University'      => 'Đại học',
    'N/A'             => 'N/A',
);
$app_list_strings['priority_feedback_list'] = array(
    'High' => 'Cao',
    'Medium' => 'Trung bình',
    'Low' => 'Thấp',
);
$app_list_strings['type_feedback_list'] = array(
    'Internal' => 'Nội bộ',
    'LX' => 'LX',
    'Customer' => 'Khách hàng',
);

$app_list_strings['status_feedback_list'] = array(
    'New'       => 'Mới',
    'Assigned'  => 'Đã phân công',
    'In Progress' => 'Đang xử lý',
    'Done'      => 'Đã xong',
);

$app_list_strings['status_feedback_list_for_vn'] = array(
    'New' => 'Mới ',
    'Assigned' => 'Đã phân công',
    'In Progress' => 'Đang xử lỷ',
    'Done' => 'Đã xong',
);
$app_list_strings['full_relate_feedback_list']=array (
    '' => '-Chưa chọn-',
    'Class Tutoring' => 'Gia sư',
    'Class SMS Issue' => 'Sự cố SMS',
    'Teacher Issue Nationality' => 'Quốc tịnh',
    'Teacher Issue Qualification' => 'Trình độ chuyên môn',
    'Teacher Issue Performance In Class' => 'Hiệu quả giảng dạy',
    'Teacher Issue Administration' => 'Quản lý',
    'Teacher Issue Other' => 'Khác',
    'Student Issue Performance Level' => 'Mức độ hiệu quả',
    'Student Issue Behavior' => 'Hành vi',
    'Student Issue Other' => 'Khác',
    'Building Issue Room' => 'Phòng học',
    'Building Issue Facility' => 'Cơ sở vật chất',
    'Building Issue Safety' => 'An toàn',
    'Building Issue Other' => 'Khác',
    'Customer Issue EC Performance' => 'Năng lực của EC',
    'Customer Issue EFL Performance' => 'Năng lực của EFL',
    'Customer Issue Complaint' => 'Phàn nàn',
    'Customer Issue Suggestion' => 'Góp ý',
    'Customer Issue Question' => 'Thắc mắc',
    'Customer Issue Other' => 'Khác',
);
$app_list_strings['full_relate_feedback_list_for_vn']=array (
    '' => '-Chưa chọn-',
    'Teacher Issue Nationality' => 'Quốc tịnh',
    'Teacher Issue Qualification' => 'Trình độ chuyên môn',
    'Teacher Issue Performance In Class' => 'Hiệu quả giảng dạy',
    'Teacher Issue Administration' => 'Quản lý',
    'Teacher Issue Other' => 'Khác',
    'Building Issue Room' => 'Phòng học',
    'Building Issue Facility' => 'Cơ sở vật chất',
    'Building Issue Safety' => 'An toàn',
    'Building Issue Other' => 'Khác',
    'Student Issue Performance Level' => 'Mức độ hiệu quả',
    'Student Issue Behavior' => 'Hành vi',
    'Student Issue Other' => 'Khác',                          
    'Customer Issue EC Performance' => 'Năng lực của EC',
    'Customer Issue EFL Performance' => 'Năng lực của EFL',
    'Customer Issue Complaint' => 'Phàn nàn',
    'Customer Issue Suggestion' => 'Góp ý',
    'Customer Issue Question' => 'Thắc mắc',
    'Customer Issue Other' => 'Khác',

);
$app_list_strings['portal_feedback_type_list_for_vn'] = array(
    'Customer Issue Suggestion' => 'Góp ý',
    'Customer Issue Question' => 'Thắc mắc',
    'Customer Issue Complaint' => 'Phàn nàn',
);
$app_list_strings['portal_feedback_type_list_for_en'] = array(
    'Customer Issue Suggestion' => 'Góp ý',
    'Customer Issue Question' => 'Thắc mắc',
    'Customer Issue Complaint' => 'Phàn nàn',
);
$app_list_strings['relate_feedback_list']=array (
    '' => '-Chưa chọn-',
    'Class' => array(
        'Class Tutoring' => 'Trợ giảng',
        'Class SMS Issue' => 'Tin nhắn SMS',
    ),
    'Teacher Issue' => array(
        'Teacher Issue Nationality' => 'Quốc tịch',
        'Teacher Issue Qualification' => 'Chất lượng',
        'Teacher Issue Performance In Class' => 'Lớp học',
        'Teacher Issue Administration' => 'Quản trị',
        'Teacher Issue Other' => 'Khác',
    ),
    'Student Issue' => array(
        'Student Issue Performance Level' => 'Độ hiệu quả',
        'Student Issue Behavior' => 'Thái độ',
        'Student Issue Other' => 'Khác',
    ),
    'Building Issue' => array(
        'Building Issue Room' => 'Phòng học',
        'Building Issue Facility' => 'Cơ sở vật chất',
        'Building Issue Safety' => 'An ninh',
        'Building Issue Other' => 'Khác',
    ),
    'Customer Issue' => array(
        'Customer Issue EC Performance' => 'EC',
        'Customer Issue EFL Performance' => 'EFL',
        'Customer Issue Complaint' => 'Phàn nàn',
        'Customer Issue Suggestion' => 'Góp ý',
        'Customer Issue Question' => 'Thắc mắc',
        'Customer Issue Other' => 'Khác',
    ),
);


$app_list_strings['status_kindofcourse_list'] = array (
    'Active' => 'Hoạt động',
    'Inactive' => 'Không hoạt động',
);
$app_list_strings['type_targetconfig_list'] = array (
    ''                  => '-Chưa chọn-',
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
    ''          => '-Chưa chọn-',
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
    ''          => '-Chưa chọn-',
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
    ''          => '-Chưa chọn-',
    'Weekly'    => 'Hàng tuần',
    'Monthly'   => 'Hàng tháng',
    'Quarterly' => 'Hàng quý',
    'Yearly'    => 'Hàng năm',
);

$app_list_strings['feedback_time_list'] = array (
    '' => '-Chưa chọn-',
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
    'Other' => 'Khác',
);

$app_list_strings['status_inventory_list'] = array(
    //'Draft' => 'Draft',
    //'In Progress' => 'Đang xử lý',
    //'Done' => 'Done',
    'Un Confirmed' =>  'Không xác nhận',
    'Confirmed' =>  'Đã xác nhận',
);
$app_list_strings['type_inventory_list'] = array(
    '' => '--Chưa chọn--',
    'Import'        => 'Nhập',
    'Tranfer Out'   => 'Chuyển ra',
    'Tranfer In'    => 'Chuyển vào',
    'Sell'          => 'Bán',
);
$app_list_strings['from_inventory_list'] = array(
    'Teams' => 'Nhóm',
    'TeamsParent' => 'Nhóm phụ huynh',
    'Accounts' => 'Nhà cung cấp',
);
$app_list_strings['to_inventory_list'] = array(
    'Teams' => 'Nhóm',
    'C_Teachers' => 'Teacher/Library',
    'Accounts' => 'Corp/BEP',
    'Contacts' => 'Thiếu nhi',
);
$app_list_strings['type_accounts_list'] = array(
    'Student' => 'Thiếu nhi',
    'Supplier' => 'Nhà cung cấp',
    'Teacher/Library' => 'Teacher/Library',
    'Corp/BEP' => 'Corp/BEP',
);
$app_list_strings['type_class_list'] = array(
    'Normal Class' => 'Lớp thường',
    'Waiting Class' => 'Lớp chờ',
);
$app_list_strings['class_time_type_list'] = array(
    '1 ls/w' => '1 ls/w',
    '2 ls/w' => '2 ls/w',
    '3 ls/w' => '3 ls/w',
);
/*$app_list_strings['lead_source_list'] = array (
    ''              => '-Chưa chọn-',
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
    'Other'   => 'Khác',
);   */

$app_list_strings['lead_source_list'] = array (
    ''                      => '-Chưa chọn-',
    'Walk in'               => 'Trực tiếp',
    'Call in'               => 'Gọi điện',
    'Referral'              => 'Bạn bè giới thiệu',
    'Sales'                 => 'Sales',             
    'Sponsorship'           => 'Học bổng',  
    'Website'               => 'Website',
    'Landing Page'          => 'Landing Page',
    'Facebook'              => 'Facebook',
    'Youtube'               => 'Youtube',
    'Other'                 => 'Khác',
);

$app_list_strings['online_source_list'] = array (
    'Website'       => 'Website',
    'Landing Page'  => 'Landing Page',
    'Facebook'      => 'Facebook',
    'Youtube'       => 'Youtube',
);

$app_list_strings['offline_source_list'] = array (
    'Walk in'       => 'Trực tiếp',
    'Call in'       => 'Gọi điện',
    'Local MKT'     => 'Marketing',
    'Local Data'    => 'Data có sẵn',
    'Sales'         => 'Sales',
    'Referral'      => 'Bạn bè giới thiệu',
    'Sponsorship'   => 'Học bổng',
    'Partner'       => 'Đối tác',         
    'Other'   => 'Khác',
);

$app_list_strings['activity_source_list'] = array (
    ''              => '-Chưa chọn-',
    'Talk To Us'    => 'Talk To Us',
    'Live Chat'     => 'Live Chat',
    'Lead'          => 'HV Tiềm năng',
    'Chat'          => 'Chat',
    'School activity'=> 'Hoạt động tại trường',
    'Partnership'    => 'Đối tác',
    'Internal'       => 'Data nội bộ',
    'External'       => 'Data mua',
);

$app_list_strings['json_activity_source_list'] = array (
    'Website'       => 'Website',
    'Landing Page'  => 'Langding Page',
    'Facebook'      => 'Lead|Chat',
    'Local MKT'     => 'Marketing',
    'Sponsorship'   => 'Học bổng',
);

$app_list_strings['status_discount_list'] = array (
    'Active' => 'Hoạt động',
    'Inactive' => 'Không hoạt động',
);
$app_list_strings['applyfor_discount_list'] = array (
    'Current Student' => 'Học viên hiện tại',
    'New Student' => 'Học viên mới',
    'Both' => 'Cả hai',
);
$app_list_strings['type_discount_list'] = array (
    'Gift'                 => 'Quà tặng',
    'Partnership'          => 'Đối tác',
    'Hour'                 => 'Giờ',
    'Reward'               => 'Phần thưởng',
    'Other'                => 'Khác',
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
    'Active' => 'Hoạt động',
    'Inactive' => 'Không hoạt động',
);
$app_list_strings['status_class_list'] = array (
    ''              => '-Chưa chọn-',
    'Planning'      => 'Chưa bắt đầu',
    'In Progress'   => 'Đang học',
    'Finish'        => 'Kết thúc',
    'Closed'        => 'Đã đóng',   
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
    'Active' => 'Hoạt động',
    'Inactive' => 'Không hoạt động',
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
    '' => '- Chưa chọn -',
    'A' => 'AC/AM',
    'FT' => 'FT',
    'PT' => 'PT',
    'CST' => 'CST',
    'MT' => 'MT',
    'ST' => 'ST',
);
$app_list_strings['status_TeacherContract_list'] = array (
    'Active' => 'Hoạt động',
    'Inactive' => 'Không hoạt động',
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
    'Active' => 'Hoạt động',
    'Inactive' => 'Không hoạt động',
    'Complete' => 'Complete',
    'In Queue' => 'In Queue',
    'Sending' => 'Sending',
);
$app_list_strings['category_marketingplan_list'] = array (
    'New Student' => 'Học viên mới',
    'Current Student' => 'Học viên hiện tại',
);
$app_list_strings['type_teacher_list'] = array (
    'Teacher' => 'Giáo viên',
    'TA' => 'TA',
);
$app_list_strings['lead_status_dom'] = array (
    'New'           => 'Mới',           
    'In Process'    => 'Đang chăm sóc',
    'PT/Demo'       => 'Thi đầu vào',
    'Converted'     => 'Đã chuyển đổi',
    'Recycled'      => 'Chăm sóc lại',
    'Dead'          => 'Tạm ngừng',
);

$app_list_strings['status_partnership_list'] = array (
    'Active'    => 'Hoạt động',
    'Inactive'  => 'Không hoạt động',
);
$app_list_strings['type_team_list'] = array (
    '' => '-Chưa chọn-',
    'Adult' => 'Adult',
    'Junior' => 'Junior',
);
$app_list_strings['rela_contacts_list'] = array (
    '' => '-Chưa chọn-',
    'Sister' => 'Chị/Em gái',
    'Brother' => 'Anh/Em trai',
    'Friend' => 'Bạn bè', 
    'Father' => 'Ba',
    'Mother' => 'Mẹ',
    'Grandfather' => 'Ông',
    'Grandmother' => 'Bà',
    'Coursin' => 'Cô',
);
$app_list_strings['status_paymentdetail_list'] = array (
    'Unpaid'        => 'Chưa TT',
    'Paid'          => 'Đã TT',
    'Cancelled'     => 'Đã hủy',
);
$app_list_strings['status_payment_list'] = array (
    'Paid'         => 'Đã thanh toán',
    'Unpaid'       => 'Chưa thanh toán',
);

$app_list_strings['payment_type_payment_list'] = array (
    'Enrollment'            => 'Đăng ký học',
    'Deposit'               => 'Đặt cọc',
    'Cashholder'            => 'Mua khóa học',
    'Delay'                 => 'Hủy lớp',
    'Schedule Change'       => 'Đổi lịch',
    'Transfer In'           => 'Chuyển tiền (nhận)',
    'Transfer Out'          => 'Chuyển tiền (ra)',
    'Moving In'             => 'Chuyển chi nhánh (nhận)',
    'Moving Out'            => 'Chuyển chi nhánh (ra)',
    'Refund'                => 'Hoàn tiền',
    'Placement Test'        => 'Thi đầu vào',
    'Book/Gift'             => 'Sách/ quà tặng',
    'Corporate'             => 'Hợp đồng',
);
$app_list_strings['sale_type_payment_list'] = array (
    ''          => '-Empty-',
    'Not set'   => 'Chưa xác định',
    'New Sale'  => 'HV mới',
    'Retention' => 'HV cũ',
);
$app_list_strings['is_using_list'] = array (
    'Range 1'   => 'Range 1',
    'Range 2'   => 'Range 2',
);
/*$app_list_strings['status_payment_list'] = array (
'Planning' => 'Lập kế hoạch',
'In Progress' => 'Đang xử lý',
'Finished' => 'Đã kết thúc',
);*/
$app_list_strings['foc_type_payment_list'] = array (
    ''                      => "-Chưa chọn-",
    "Staff"                 => "Nhân viên", 
    "Referral"              => "Giới thiệu",       
    "Marketing"             => "Markeitng", 
    "Retake"                => "Học lại",   
    "Other"                 => "Khác",
);
$app_list_strings['type_j_sponsor_list'] = array (
    'Discount'  => 'Chiết khấu',
    'Sponsor'   => 'Voucher',
);
$app_list_strings['payment_method_junior_list'] = array (
    '' => '-Chưa chọn-',
    'Cash' => 'Tiền mặt',
    'Card' => 'Thẻ',
    'Bank Transfer' => 'Chuyển khoản',
    'Other' => 'Khác',
);

$app_list_strings ['emailTemplates_type_list'] = array (
    '' => '' ,
    'campaign' => 'Chiến dịch',
    'email' => 'Email',
    'sms' => 'SMS',
    'workflow' => 'Workflow',
);

$app_list_strings ['sms_supplier_options'] = array (
    'mobifone'      => 'Mobifone' ,
    'viettel'       => 'Viettel' ,
    'vinaphone'     => 'Vinaphone',
    'vietnamobile'  => 'Vietnamobile',
    'sphone'        => 'Sphone',
    'gmobile'       => 'Gmobile',
    'other'         => 'Khác',
);

$app_list_strings ['campaign_code_list'] = array (
    ''     => '-Chưa chọn-' ,
);
$app_list_strings ['convert_type_list'] = array (
    'To Hour'     => 'Số tiền' ,
    'To Amount'   => 'Số giờ' ,
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
    'lock'  =>'Không cho phép người dùng chỉnh sửa trang chủ',
    'merge' =>'Allow user to add tabs, but overwrite base tabs',
    'merge_delete'  =>'Allow user to add/delete tabs, but overwrite base tabs'
);

$app_list_strings['use_type_options'] = array(
    'Amount'    => 'Số tiền',
    'Hour'      => 'Số giờ',
);

$app_list_strings['payment_detail_type_options'] = array(
    'Normal'    => 'Bình thường',
    'Deposit'   => 'Tiền gửi',
);

$app_list_strings['report_list_list'] = array(
    ''         => '-Chưa chọn-',
    'Accounting'=> 'Kế toán',
    'Academic' => 'Giáo vụ',
    'Marketing'=> 'Marketing',
    'Operation'=> 'Quản trị',
    'BOD'      => 'BOD',
);
$app_list_strings['category_list'] = array(
    ''           => '-Chưa chọn-',
    'Student'    => 'Học sinh',
    'Work'       => 'Đã đi làm',
);

$app_list_strings['holiday_apply_for_options'] = array(
    ''    => '-Chưa chọn-',
    'forall'    => 'Cho tất cả học viên',
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
    'Not Started'     => 'Chưa bắt đầu',
    'In Progress'     => 'Đang xử lý',
    'Finished'        => 'Đã kết thúc',
);


$app_list_strings['teaching_type_options'] = array(
    ''  => '',
    'main_teacher'  => 'Giáo viên chính',
    'cover'         => 'Giáo viên hổ trợ',
    'take_over'     => 'Giáo viên dạy thay',
    'make_up'       => 'Giáo viên dạy bù',
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
    'Other:'                    => 'Other:',
);

$app_list_strings['case_type_options'] = array(
    'Complaint' => 'Phàn nàn',
    'Suggestion' => 'Góp ý',
    'Question' => 'Thắc mắc',
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


$app_list_strings['moduleList']['Cases']='Phản hồi';
$app_list_strings['moduleListSingular']['Cases']='Phản hồi';
$app_list_strings['moduleList']['J_Coursefee']='Đơn giá';
$app_list_strings['moduleListSingular']['J_Coursefee']='Đơn giá';
$app_strings['LBL_GROUPTAB6_1437012463'] = 'Tất cả module cơ sở';

$app_list_strings['moduleList']['J_Kindofcourse']='Khóa học';
$app_list_strings['moduleListSingular']['J_Kindofcourse']='Khóa học';
$app_list_strings['moduleList']['ProductTemplates'] ='Quà tặng';
$app_list_strings['moduleListSingular']['Meetings']='Bộ lập lịch';
$app_strings['LBL_TABGROUP_SALES'] = 'Giáo vụ';

$app_strings['LBL_TABGROUP_MARKETING'] = 'Marketing';

$app_strings['LBL_GROUPTAB4_1442479371'] = 'Cấu hình';

// App string by Tung Bui
$app_strings['LBL_VIEW_MAP'] = 'Xem bản đồ';
$app_strings['LBL_STATE'] = 'Quận/Phường';
$app_strings['LBL_REFRESH_BUTTON_TITLE'] = 'Làm mới';
// End Tung Bui

/*app list string by Trung Nguyen*/
//cancel reason list
$app_list_strings['session_cancel_reason_options'] = array(
    'Student request' => 'Yêu cầu của học viên',
    'Weather' => 'Thời tiết',
    'Teacher holiday' => 'Giáo viên nghỉ lễ',
    'Teacher sick' => 'Giáo viên nghỉ bệnh',
    'Teacher leaving' => 'Giáo viên nghỉ dạy',
    'Public holiday' => 'Ngày lễ',
    'Other'         => 'Khác',
);
$app_list_strings['ptresult_student_options'] = array(   //2016.01.04
    '' => '-Chưa chọn-',
    'Leads' => 'HV Tiềm năng',
    'Contacts' => 'Thiếu nhi',
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
    '=' => 'Bằng',
    'not_equal' => 'Không bằng',
    'greater_than' => 'Sau',
    'less_than' => 'Trước',
    'last_7_days' => '7 ngày qua',
    'next_7_days' => '7 ngày tới',
    'last_30_days' => '30 ngày qua',
    'next_30_days' => '30 ngày tới',
    'last_month' => 'Tháng trước',
    'this_month' => 'Tháng này',
    'next_month' => 'Tháng tiếp theo',
    'last_year' => 'Năm trước',
    'this_year' => 'Năm này',
    'next_year' => 'Năm tiếp theo',
    'between' => 'Giữa',
);
$app_list_strings['meeting_status_dom'] = array(
    'Planned' => 'Lập kế hoạch',
    'Held' => 'Đã thực hiện',
    'Not Held' => 'Không thực hiện',
);
/*end*/


$app_list_strings['timesheet_tasklist_list']=array (
    ''              => '-Chưa chọn-',
    'Event'         => 'Event',
    'Marketing'     => 'Marketing',
    'eLearn hours'  => 'eLearn hours',
    'Standby'       => 'Standby',
    'Demo'          => 'Demo',
    'Placement Test'=> 'Kỳ thi xếp lớp',
    'Other'         => 'Khác',
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
    ''      => '-Chưa chọn-',
    'Progress'              => 'Progress',
    'Commitment'    => 'Commitment',
    'Overall'       => 'Overall',
);
$app_list_strings['gradeconfig_minitest_options']=array (
    ''      => '-Chưa chọn-',
    'minitest1'   => 'Bài kiểm tra 1',
    'minitest2'   => 'Bài kiểm tra 2',
    'minitest3'   => 'Bài kiểm tra 3',
    'minitest4'   => 'Bài kiểm tra 4',
    'minitest5'   => 'Bài kiểm tra 5',
    'minitest6'   => 'Bài kiểm tra 6',
    'project1'   => 'Đề tài 1',
    'project2'   => 'Đề tài 2',
    'project3'   => 'Đề tài 3',
    'project4'   => 'Đề tài 4',
    'project5'   => 'Đề tài 5',
    'project6'   => 'Đề tài 6',
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
    'Approved' => 'Phê duyệt',
    'Not Approval' => 'Không phê duyệt',
);
/*************************/
$app_list_strings['c_sms_module_selected_list']=array (
    '-BLANK-' => '-BLANK-',
    'Contacts' => 'Thiếu nhi',
    'C_Teachers' => 'Giáo viên',
    'J_StudentSituations' => 'Quá trình học tập',
    'J_PTResult' => 'Kết quả PT',
    'Leads' => 'HV Tiềm năng',
);

$app_list_strings['region_list'] = array (
    '' => '-Chưa chọn-',
    'South' => 'Miền Nam',
    'North' => 'Miền Bắc',
);

$app_list_strings['call_type_dom'] = array (
    '' => '-Chưa chọn-',
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
    'Other'         => 'Khác',
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
    'Inactive'  => 'Không hoạt động',
    'Activated' => 'Đã kích hoạt',
    'Expired'   => 'Đã hết hạn',
);
$app_list_strings['voucher_type_options'] = array (
    'amount'  => 'Số tiền',
    'percent' => 'Giảm %',       
);
$app_list_strings ['myelt_link_list'] = array (
);
//endl
$app_strings['LBL_GROUPTAB2_1480343614'] = 'Corporate';

$app_strings['LBL_GROUPTAB3_1486717995'] = 'Marketing';
$app_strings['LBL_SELECTED'] = 'Đã chọn';
$app_strings['LBL_GROUPTAB4_1487735757'] = 'Báo cáo';
$app_strings['LBL_AJAX_PLEASE_WAIT'] = 'Vui lòng đợi...';
$app_strings['LBL_AJAX_SAVE_SUCCESS'] = 'Hoàn thành';
$app_strings['LBL_AJAX_ERROR'] = 'Đã có lỗi, xin vui lòng thử lại';
$app_strings['LBL_SEARCH_FORM_SHOW_LESS'] = 'Bộ lọc cơ bản';
$app_strings['LBL_SEARCH_FORM_SHOW_MORE'] = 'Bộ lọc đầy đủ';
$app_strings['LBL_LISENCE'] = 'Thông tin bản quyền';
$app_strings['LBL_CHECK_LISENCE_NOW'] = 'Kiểm tra thông tin gói CRM đang sử dụng';
$app_strings['LBL_LISENCE_ONLINECRM_INFO'] = '<h2>Xin vui lòng liên hệ OnlineCRM để được hỗ trợ!</h2><br>
Hotline: 0935 543 543<br/>
Email: info@onlinecrm.vn<br/>
Website: www.onlinecrm.vn<br/>';
$app_strings['LBL_LISENCE_WARNING_LIMIT_STOP'] = '<h2>Dữ liệu lưu trữ đã vượt quá giới hạn. '.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];
$app_strings['LBL_LISENCE_WARNING_LIMIT_USERS'] = '<h2>Phiên bản cloud_version chỉ có thể tạo tối đa limit_number Người dùng!</h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];
$app_strings['LBL_LISENCE_WARNING_LIMIT_LEADS'] = '<h2>Phiên bản cloud_version chỉ có thể tạo tối đa limit_number Học viên tiềm năng!</h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];
$app_strings['LBL_LISENCE_WARNING_LIMIT_STUDENTS'] = '<h2>Phiên bản cloud_version chỉ có thể tạo tối đa limit_number Học viên chính thức!</h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];
$app_strings['LBL_LISENCE_EXPIRIED'] = '<h2>Thời hạn sử dụng phiên bản CRM này đã hết, xin vui lòng liên hệ OnlineCRM để gia hạn!</h2>'.$app_strings['LBL_LISENCE_ONLINECRM_INFO'];


$app_list_strings['graduated_rate_list'] = array (
    '' => '-Chưa chọn-',
    'Excellent' => 'Xuất sắc',
    'Very good' => 'Rất tốt',
    'Good'      => 'Tốt',
    'Average good'=> 'Trung bình tốt',
    'Ordinary'    => 'Bình thường',
);
$app_list_strings['reason_not_interested_leads_list'] = array (  
    ''                  => '-Chưa chọn-',
    'course_fee'        => 'Học phí',
    'loaction'          => 'Nhà xa',
    'busy'              => 'Bận',
    'teacher'           => 'Giáo viên',
    'study_program'     => 'Chương trình học',
    'material_facilities'   => 'Cơ sở vật chất',
    'Other'             => 'Khác',
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
    'Fully Paid' => 'Thanh toán đủ',
    'Partly Paid' => 'Thanh toán một phần',
    'Unpaid' => 'Chưa thanh toán',
); 
$app_list_strings['c_grade_status_options'] = array(
    'active' => 'Đang sử dụng',
    'inactive' => 'Đã khóa',       
);       
$app_list_strings['c_classgroup_status_options'] = array(
    'active' => 'Đang sử dụng',
    'inactive' => 'Đã khóa',       
);       
$app_list_strings['c_class_status_options'] = array(
    'active' => 'Đang học',
    'inactive' => 'Đã kết thúc',       
);      
$app_list_strings['contact_guardian_rela_options'] = array(
    'father' => 'Bố',
    'mother' => 'Mẹ',       
);       
$app_list_strings['moduleList']['Contacts']='Thiếu nhi';
$app_list_strings['record_type_display']['Contacts']='Thiếu nhi';
$app_list_strings['parent_type_display']['Contacts']='Thiếu nhi';
$app_list_strings['record_type_display_notes']['Contacts']='Thiếu nhi';
$app_list_strings['moduleList']['Meetings']='Lịch hẹn';
$app_list_strings['moduleList']['J_Class']='Lớp';
$app_list_strings['moduleList']['J_Feedback']='Phản hồi';
$app_strings['LBL_GROUPTAB0_1527137856'] = 'GLV';

$app_strings['LBL_GROUPTAB1_1527137856'] = 'Đăng ký';

$app_strings['LBL_GROUPTAB5_1527137856'] = 'Lịch làm việc';
$app_strings['LBL_COMPOSE_SMS'] = 'Gửi SMS';
$app_strings['LBL_CREATE_DEMO_USER'] = 'Tạo user demo';

$app_strings['LBL_NONE_SELECTED'] = 'Chưa chọn';
$app_strings['LBL_ALL_SELECTED'] = 'Chọn tất cả';  
$app_strings['LBL_SELECT_ALL'] = 'Chọn tất cả';
$app_strings['LBL_SELECT_OF_ALL'] = 'Chọn tất cả';
$app_strings['LBL_MULTISELECT_NO_RESULT'] = 'Không tìm thấy';
//$app_strings['LBL_SELECTED'] => 'đã chọn';
$app_strings['LBL_OK_CONFIRM_ALERTIFY'] = 'Đồng ý';
$app_strings['LBL_OK_CANCEL_ALERTIFY'] = 'Hủy';

$app_list_strings['moduleList']['C_Attendance']='Điểm danh';             
$app_list_strings['moduleList']['C_DuplicationDetection']='Chặn trùng';
$app_list_strings['moduleList']['C_FieldHighlighter']='Hiển thị dữ liệu';
$app_list_strings['moduleList']['C_HelpTextConfig']='Hỗ trợ nhập liệu';
$app_list_strings['moduleList']['Holidays']='Ngày nghỉ lễ';                           
$app_list_strings['moduleListSingular']['C_Attendance']='Điểm danh';     
$app_list_strings['moduleList']['C_Saint'] = 'Tên thánh';
$app_list_strings['moduleList']['C_Grade'] = 'Khối';
$app_list_strings['moduleList']['C_Class'] = 'Lớp';
$app_list_strings['moduleList']['C_ClassGroup'] = 'Cấp';
$app_list_strings['moduleList']['C_Gradebook'] = 'Bảng điểm';      
$app_list_strings['moduleList']['C_SMS'] = 'SMS';      
$app_list_strings['moduleListSingular']['C_Saint'] = 'Tên thánh';
$app_list_strings['moduleListSingular']['C_Grade'] = 'Khối';
$app_list_strings['moduleListSingular']['C_Class'] = 'Lớp';
$app_list_strings['moduleListSingular']['C_ClassGroup'] = 'Cấp';
$app_list_strings['moduleListSingular']['C_Gradebook'] = 'Bảng điểm';  
$app_list_strings['moduleList']['C_Contacts']='Phụ huynh';
$app_list_strings['moduleListSingular']['C_Contacts']='Phụ huynh';
$app_list_strings['moduleListSingular']['C_SMS'] = 'SMS';      