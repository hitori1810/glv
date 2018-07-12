<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

$mod_strings = array (
'LBL_NAME' => 'Tên công việc',
'LBL_EXECUTE_TIME'			=> 'Thời gian thực thi',
'LBL_SCHEDULER_ID' 	=> 'Lập lịch biểu',
'LBL_STATUS' 	=> 'Trạng thái công việc',
'LBL_RESOLUTION' 	=> 'Kết quả',
'LBL_MESSAGE' 	=> 'Messages',
'LBL_DATA' 	=> 'Dũ liệu công việc',
'LBL_REQUEUE' 	=> 'Thử lại khi không thành công',
'LBL_RETRY_COUNT' 	=> 'Số lần thử tối đa',
'LBL_FAIL_COUNT' 	=> 'Số lần thất bại',
'LBL_INTERVAL' 	=> 'Khoảng thời gian tối thiểu giữa các lần thử',
'LBL_CLIENT' 	=> 'Owning client',
'LBL_PERCENT'	=> 'Hoàn thành toàn bộ',
'LBL_JOB_GROUP' => 'Nhóm công việc',
// Errors
'ERR_CALL' 	=> "Không thể gọi hàm: %s",
'ERR_CURL' => "Không có CURL - không thể chạy công việc URL",
'ERR_FAILED' => "Lỗi không mong muốn, vui lòng kiểm tra nhật ký PHP và sugarcrm.log",
'ERR_PHP' => "%s [%d]: %s in %s on line %d",
'ERR_NOUSER' => "Không có User ID nào được chỉ định cho công việc",
'ERR_NOSUCHUSER' => "User ID %s not found",
'ERR_JOBTYPE' 	=> "Loại công việc không xác định: %s",
'ERR_TIMEOUT' => "Forced failure on timeout",
'ERR_JOB_FAILED_VERBOSE' => 'Job %1$s (%2$s) failed in CRON run',
);
