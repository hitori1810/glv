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
                <th>{$MOD.LBL_STUDENT_NAME}</th>
                <th>{$MOD.LBL_CLASS_CODE}</th>
                <th>{$MOD.LBL_STUDY_DATE}</th>                             
                <th>{$MOD.LBL_ABSENT_REASON}</th>                             
            </tr>
        </thead>
        <tbody>{$DATA}</tbody>
    </table>
</div>

