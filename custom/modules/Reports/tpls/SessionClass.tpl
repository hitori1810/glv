<!--<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/modules/Reports/css/SessionClass.css'}"> -->
<link rel="stylesheet" type="text/css" href="custom/include/javascripts/DataTables/css/jquery.dataTables.css">   
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file= 'custom/modules/Reports/css/SessionClass.css'}">
<script type="text/javascript" src="custom/include/javascripts/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="custom/modules/Reports/js/ReportDataTable.js"></script>
<div class="report">
    <table class="display nowrap dataTable dtr-inline" style="border: 1px solid #ccc;background-color:#ccc" width="100%">
        <thead class="thead-default">
            <tr>
                <th>{$MOD.LBL_NO}</th>
                <th>{$MOD.LBL_CLASS_NAME}</th>
                <th>{$MOD.LBL_LESSON_NO}</th>
                <th>{$MOD.LBL_TILL_HOUR}</th>
                <th>{$MOD.LBL_SESSION_STATUS}</th>
                <th>{$MOD.LBL_WEEK_DATE}</th>
                <th>{$MOD.LBL_DATE_START}</th>
                <th>{$MOD.LBL_DATE_END}</th>
                <th>{$MOD.LBL_DURATION_CALL}</th>
                <th>{$MOD.LBL_NUMBER_OF_STUDENT}</th>
                <th>{$MOD.LBL_ATTENDED}</th>
                <th>{$MOD.LBL_TEACHER}</th>
                <th>{$MOD.LBL_TEACHING_TYPE}</th>
                <th>{$MOD.LBL_CHANGE_TEACHER_REASON}</th>               
            </tr>
        </thead>
        <tbody>{$DATA}</tbody>
    </table>
</div>

