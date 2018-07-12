<?php
/* modules/Opportunities/language/vn_vn.lang.php */
if(!defined('sugarEntry'))define('sugarEntry', true);
/*********************************************************************************
* The contents of this file are subject to the SugarCRM Public License Version
* 1.1.3 ("License"); You may not use this file except in compliance with the
* License. You may obtain a copy of the License at http//www.sugarcrm.com/SPL
* Software distributed under the License is distributed on an "AS IS" basis,
* WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
* for the specific language governing rights and limitations under the
* License.
*
* All copies of the Covered Code must include on each user interface screen
*    (i) the "Powered by SugarCRM" logo and
*    (ii) the SugarCRM copyright notice
* in the same form as they appear in the distribution.  See full license for
* requirements.
*
* The Original Code is SugarCRM Open Source
* Contributor Lampada CRM 
* David O'Keefe
* info@lampadacrm.com.br
*	
* The Initial Developer of the Original Code is SugarCRM, Inc.
* Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
* All Rights Reserved.
********************************************************************************/
	$mod_strings=array(
	'LBL_MODULE_NAME' => 'Cơ hội',
	'LBL_MODULE_TITLE' => 'Cơ hội',
	'LBL_SEARCH_FORM_TITLE' => 'Tìm cơ hội',
	'LBL_VIEW_FORM_TITLE' => 'Xem cơ hội',
	'LBL_LIST_FORM_TITLE' => 'Danh sách cơ hội',
	'LBL_OPPORTUNITY_NAME' => 'Tên',
	'LBL_OPPORTUNITY' => 'Cơ hội',
	'LBL_NAME' => 'Tên',
	'LBL_INVITEE' => 'Liên lạc',
	'LBL_CURRENCIES' => 'Tiền tệ',
	'LBL_LIST_OPPORTUNITY_NAME' => 'Tên',
	'LBL_LIST_ACCOUNT_NAME' => 'Khách hàng',
	'LBL_LIST_AMOUNT' => 'Giá trị',
	'LBL_LIST_AMOUNT_USDOLLAR' => 'Giá trị (USD)',
	'LBL_LIST_DATE_CLOSED' => 'Ngày dự kiến chốt',
	'LBL_LIST_SALES_STAGE' => 'Giai đoạn',
	'LBL_ACCOUNT_ID' => 'Mã tài khoản',
	'LBL_CURRENCY_ID' => 'Mã tiền tệ',
	'LBL_CURRENCY_NAME' => 'Tên tiền tệ',
	'LBL_CURRENCY_SYMBOL' => 'Kí hiệu tiền tệ',
	'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
	'db_name' => 'LBL_NAME',
	'db_amount' => 'LBL_LIST_AMOUNT',
	'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
	'UPDATE' => 'Cơ hội - Cập nhật tiền tệ',
	'UPDATE_DOLLARAMOUNTS' => 'Cập nhật giá trị',
	'UPDATE_VERIFY' => 'Kiểm tra giá trị',
	'UPDATE_VERIFY_TXT' => 'Kiểm tra giá trị số lượng là hợp lệ(chỉ bao gồm các kí tự [0-9] và dấu [.] thập phân )',
	'UPDATE_FIX' => 'Cố định số lượng',
	'UPDATE_FIX_TXT' => 'Cố tạo ra một giá trị không hợp lệ bằng cách tạo một giá trị hợp lệ từ số lượng hiện tại. Bất cứ sự chỉnh sửa số lượng nào sẽ được backup trong trường amount_backup trong database. Nếu bạn chạy điều này và thông báo các lỗi, không chạy nó mà không cần khôi phục lại từ phiên bản backup vì nó có thể ghi đè lên bản backup với những dữ liệu không hợp lệ mới. ',
	'UPDATE_DOLLARAMOUNTS_TXT' => 'Cập nhật những số tiền cho các cơ hội, dựa vào các loại ngoại tệ theo giá hiện hành thiết lập. Giá trị này được sử dụng để tính toán Đồ thị và số lượng Danh sách tiền tệ xem',
	'UPDATE_CREATE_CURRENCY' => 'Tạo mới tiền tệ',
	'UPDATE_VERIFY_FAIL' => 'Mẩu tin kiểm tra thất bại',
	'UPDATE_VERIFY_CURAMOUNT' => 'Trị giá hiện tại',
	'UPDATE_VERIFY_FIX' => 'Running Fix would give',
	'UPDATE_INCLUDE_CLOSE' => 'Bao gồm mẩu tin đóng',
	'UPDATE_VERIFY_NEWAMOUNT' => 'Tạo mới Số lượng',
	'UPDATE_VERIFY_NEWCURRENCY' => 'Tiền tệ mới',
	'UPDATE_DONE' => 'Xong',
	'UPDATE_BUG_COUNT' => 'Lỗi tìm thấy và đang sửa',
	'UPDATE_BUGFOUND_COUNT' => 'Lỗi tìm thấy',
	'UPDATE_COUNT' => 'Mẩu tin được cập nhật',
	'UPDATE_RESTORE_COUNT' => 'Số lượng mẩu tin được khôi phục',
	'UPDATE_RESTORE' => 'Số lượng khôi phục',
	'UPDATE_RESTORE_TXT' => 'Khôi phục số tiền từ các bản sao lưu các giá trị tạo ra trong quá trình sửa chữa.',
	'UPDATE_FAIL' => 'Không thể cập nhật',
	'UPDATE_NULL_VALUE' => 'Số lượng là NULL đang thiết lập là 0',
	'UPDATE_MERGE' => 'Đơn vị tiền tệ hợp nhất',
	'UPDATE_MERGE_TXT' => 'Nhiều loại tiền tệ hợp nhất thành một loại tiền tệ. Nếu có nhiều loại tiền tệ cho cùng một đơn vị tiền tệ, bạn kết hợp chúng lại với nhau. Điều này cũng sẽ hợp nhất chúng lại. Các module khách cũng vậy.',
	'LBL_ACCOUNT_NAME' => 'Khách hàng',
	'LBL_AMOUNT' => 'Giá trị',
	'LBL_AMOUNT_USDOLLAR' => 'Giá trị (USD)',
	'LBL_CURRENCY' => 'Tiền tệ',
	'LBL_DATE_CLOSED' => 'Ngày kết thúc dự kiến',
	'LBL_TYPE' => 'Loại',
	'LBL_CAMPAIGN' => 'Chiến dịch',
	'LBL_NEXT_STEP' => 'Bước tiếp theo',
	'LBL_LEAD_SOURCE' => 'Nguồn',
	'LBL_SALES_STAGE' => 'Giai đoạn',
	'LBL_PROBABILITY' => 'Tỷ lệ thành công(%)',
	'LBL_DESCRIPTION' => 'Ghi chú',
	'LBL_DUPLICATE' => 'Cơ hội lặp lại',
	'MSG_DUPLICATE' => 'Cơ hội này ghi nhận về khả năng bạn có thể tạo một bản sao của cơ hội hiện tại. Chọn Lưu để tiếp tục tạo mới cơ hội hoặc chọn Hủy bỏ để trở lại module mà không tạo cơ hội. ',
	'LBL_NEW_FORM_TITLE' => 'Tạo cơ hội',
	'LNK_NEW_OPPORTUNITY' => 'Tạo cơ hội',
	'LNK_OPPORTUNITY_LIST' => 'Danh sách cơ hội',
	'ERR_DELETE_RECORD' => 'Một số hồ sơ phải được xác định để xóa cơ hội.',
	'LBL_TOP_OPPORTUNITIES' => 'Top các cơ hội của tôi',
	'NTC_REMOVE_OPP_CONFIRMATION' => 'Bạn có chắc muốn xóa  các liên hệ từ cơ hội này?',
	'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Bạn có chắc muốn xóa cơ hội này từ dự án?',
	'LBL_DEFAULT_SUBPANEL_TITLE' => 'Cơ hội',
	'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Hoạt động',
	'LBL_HISTORY_SUBPANEL_TITLE' => 'Lịch sử làm việc',
	'LBL_RAW_AMOUNT' => 'Số tiền nguyên',
	'LBL_LEADS_SUBPANEL_TITLE' => 'Khách hàng tiềm năng liên quan',
	'LBL_CONTACTS_SUBPANEL_TITLE' => 'Liên lạc liên quan',
	'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Tài liệu liên quan',
	'LBL_PROJECTS_SUBPANEL_TITLE' => 'Dự án liên quan',
	'LBL_ASSIGNED_TO_NAME' => 'Người phụ trách',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Người phụ trách',
	'LBL_MY_CLOSED_OPPORTUNITIES' => 'Các cơ hội kết thúc riêng',
	'LBL_TOTAL_OPPORTUNITIES' => 'Tổng số cơ hội',
	'LBL_CLOSED_WON_OPPORTUNITIES' => 'Các cơ hội kết thúc thành công',
	'LBL_ASSIGNED_TO_ID' => 'Người được giao',
	'LBL_CREATED_ID' => 'Mã người tạo',
	'LBL_MODIFIED_ID' => 'Mã người sửa',
	'LBL_MODIFIED_NAME' => 'Người thay đổi',
	'LBL_CREATED_USER' => 'Người tạo',
	'LBL_MODIFIED_USER' => 'Người sửa',
	'LBL_CAMPAIGN_OPPORTUNITY' => 'Chiến dịch',
	'LBL_PROJECT_SUBPANEL_TITLE' => 'Dự án',
	'LABEL_PANEL_ASSIGNMENT' => 'Thông báo',
	'LNK_IMPORT_OPPORTUNITIES' => 'Nhập cơ hội từ file',
	'LBL_EXPORT_CAMPAIGN_ID' => 'Mã chiến dịch',
	'LBL_OPPORTUNITY_TYPE' => 'Loại cơ hội',
	'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Người được giao',
	'LBL_EXPORT_ASSIGNED_USER_ID' => 'Mã người được giao',
	'LBL_EXPORT_MODIFIED_USER_ID' => 'Mã người sửa',
	'LBL_EXPORT_CREATED_BY' => 'Mã người tạo',
	'LBL_EXPORT_NAME' => 'Tên cơ hội',
	'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Email người liên quan',

	/**
	* HB 30-06-2014
	*/
	'LBL_LIST_DATE_ENTERED' => 'Ngày tạo',
	'LBL_DATE_MODIFIED' => 'Ngày sửa',
	'LBL_DATE_ENTERED' => 'Ngày tạo',
	'LBL_CREATED' => 'Người tạo',
	'LBL_TEAMS' => 'Nhóm phụ trách',
	'LBL_MODIFIED_USER' => 'Người sửa',
	'LBL_MODIFIED' => 'Người sửa',
	'LNK_OPPORTUNITY_REPORTS' => 'Xem báo cáo Cơ hội',
	'LBL_WORST_CASE' => 'Trường hợp xấu nhất',
	'LBL_BEST_CASE' => 'Trường hợp tốt nhất',
	'LBL_COMMIT_STAGE' => 'Cam kết',

	'LBL_CONTRACTS'=>'Hợp đồng',
	'LBL_CONTRACTS_SUBPANEL_TITLE'=>'Hợp đồng',
	'LBL_PRODUCTS' => 'Sản phẩm',

	'LBL_QUOTES_SUBPANEL_TITLE' => 'Quotes',
	'LBL_TEAM_ID' =>'ID chi nhánh',
	'LBL_TIMEPERIODS' => 'TimePeriods',
	'LBL_TIMEPERIOD_ID' => 'TimePeriod ID',
	'LBL_COMMITTED' => 'Committed',

	'LBL_BEST_CASE_BASE_CURRENCY' => 'Best case base currency',
	'LBL_BEST_CASE_WORKSHEET' => 'Best Case (adjusted)',
	'LBL_LIKELY_CASE' => 'Likely case',
	'LBL_LIKELY_CASE_BASE_CURRENCY' => 'Likely case base currency',
	'LBL_LIKELY_CASE_WORKSHEET' => 'Likely Case (adjusted)',
	'LBL_WORST_CASE' => 'Worst Case',
	'LBL_WORST_CASE_BASE_CURRENCY' => 'Worst case base currency',
	'LBL_FORECAST' => 'Include in Forecast',
	'LBL_WORKSHEET' => 'Bảng tính',
	'LBL_PRODUCT_LINES_SUBPANEL_TITLE' => 'Chi tiết đơn hàng',
);

?>
