<?php

function fillHTMLBody($center){

    $task1 = '7b1c3d50-5789-2cb2-69b5-58b2f4cac3f2';
    $task2 = 'aed6a03e-9376-13b0-722e-58b3c9e20634';
    $task3 = '6cb83318-eb37-0e50-c9fa-58b3cd5a1252';
    if($center['team_type'] == 'Adult')
        $task4 = '28f0978a-5f9c-11a0-1c34-58b3d342eb23';
    else
        $task4 = '365e2abf-cbe0-a868-1cd0-583941924a58';

    // Add Expired Payment
    $ext_task5 = '';
    if($center['team_type'] == 'Junior'){
        $ext_task5 = '<p data-mce-style="text-align: left;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-size: small; font-family: verdana, geneva;" style="font-size: small; font-family: verdana, geneva;">5. Danh sách Payment sẽ hết hạn sử dụng trong tháng tới:</span></p>
        <p data-mce-style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-family: verdana, geneva; font-size: small;" style="font-family: verdana, geneva; font-size: small;"><strong data-mce-style="color: #073763; font-family: verdana, sans-serif; font-size: small;" style="color: rgb(7, 55, 99); font-family: verdana, sans-serif;"><a href="http://crm.atlantic.edu.vn/index.php?action=ReportCriteriaResults&amp;module=Reports&amp;page=report&amp;id=697b06b9-45f6-e751-a35d-59030eab6e80" data-mce-style="color: #1155cc;" style="color: rgb(17, 85, 204);">Click here</a></strong></span></p>';
    }

    $body = '<div>
    <p data-mce-style="text-align: left;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-size: small; font-family: verdana, geneva;" style="font-size: small; font-family: verdana, geneva;">Dear EC, EFL '.$center['short_name'].'.</span></p>
    <p data-mce-style="text-align: left;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-size: small; font-family: verdana, geneva;" style="font-size: small; font-family: verdana, geneva;">Dưới đây là danh sách công việc mà SIS yêu cầu bạn phải giải quyết trong tháng này, click vào các link bên dưới để truy cập danh sách công việc:</span></p>
    <p data-mce-style="text-align: left;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-size: small; font-family: verdana, geneva;" style="font-size: small; font-family: verdana, geneva;">1. Danh sách payment Unpaid:</span></p>
    <p data-mce-style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-family: verdana, geneva; font-size: small;" style="font-family: verdana, geneva; font-size: small;"><strong data-mce-style="color: #073763; font-family: verdana, sans-serif; font-size: small;" style="color: rgb(7, 55, 99); font-family: verdana, sans-serif;"><a href="http://crm.atlantic.edu.vn/index.php?action=ReportCriteriaResults&amp;module=Reports&amp;page=report&amp;id='.$task1.'" data-mce-style="color: #1155cc;" style="color: rgb(17, 85, 204);">Click here</a></strong></span></p>
    <p data-mce-style="text-align: left;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-size: small; font-family: verdana, geneva;" style="font-size: small; font-family: verdana, geneva;">2. Danh sách lớp chưa Submit In Progress:</span></p>
    <p data-mce-style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-family: verdana, geneva; font-size: small;" style="font-family: verdana, geneva; font-size: small;"><strong data-mce-style="color: #073763; font-family: verdana, sans-serif; font-size: small;" style="color: rgb(7, 55, 99); font-family: verdana, sans-serif;"><a href="http://crm.atlantic.edu.vn/index.php?action=ReportCriteriaResults&amp;module=Reports&amp;page=report&amp;id='.$task2.'" data-mce-style="color: #1155cc;" style="color: rgb(17, 85, 204);">Click here</a></strong></span></p>
    <p data-mce-style="text-align: left;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-size: small; font-family: verdana, geneva;" style="font-size: small; font-family: verdana, geneva;">3. Danh sách các lớp chưa xếp giáo việc:</span></p>
    <p data-mce-style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-family: verdana, geneva; font-size: small;" style="font-family: verdana, geneva; font-size: small;"><strong data-mce-style="color: #073763; font-family: verdana, sans-serif; font-size: small;" style="color: rgb(7, 55, 99); font-family: verdana, sans-serif;"><a href="http://crm.atlantic.edu.vn/index.php?action=ReportCriteriaResults&amp;module=Reports&amp;page=report&amp;id='.$task3.'" data-mce-style="color: #1155cc;" style="color: rgb(17, 85, 204);">Click here</a></strong></span></p>
    <p data-mce-style="text-align: left;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-size: small; font-family: verdana, geneva;" style="font-size: small; font-family: verdana, geneva;">4. Danh sách học viên sẽ hết học phí trong tháng tới:</span></p>
    <p data-mce-style="color: #000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-family: verdana, geneva; font-size: small;" style="font-family: verdana, geneva; font-size: small;"><strong data-mce-style="color: #073763; font-family: verdana, sans-serif; font-size: small;" style="color: rgb(7, 55, 99); font-family: verdana, sans-serif;"><a href="http://crm.atlantic.edu.vn/index.php?action=ReportCriteriaResults&amp;module=Reports&amp;page=report&amp;id='.$task4.'" data-mce-style="color: #1155cc;" style="color: rgb(17, 85, 204);">Click here</a></strong></span></p>
    '.$ext_task5.'
    <p data-mce-style="text-align: left;" style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px;"><span data-mce-style="font-size: small; font-family: verdana, geneva;" style="font-size: small; font-family: verdana, geneva;">Đây là email trả lời tự động, bạn không reply lại nội dung email này, mọi thắc mắc vui lòng gọi IT để được hỗ trợ xử lý. Xin cảm ơn,</span></p>
    <br>
    </div>';
    return $body;
}