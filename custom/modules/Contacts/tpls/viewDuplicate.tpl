<link rel="stylesheet" type="text/css" href="custom/include/javascripts/DataTables/css/jquery.dataTables.css">   

<script type="text/javascript" src="custom/include/javascripts/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="custom/modules/Contacts/js/DuplicateView.js"></script>
<div align="center" width="100%">
    <br><h1>{$MOD.LBL_VIEW_DUPLICATE_TITLE}</h1><br>
</div>
<div width="100%" class="duplicatedDiv">
    <form action="" method="POST" name="ViewDuplicate" id="ViewDuplicate">
        <table class="display nowrap dataTable dtr-inline" style="border: 1px solid #ccc;background-color:#ccc" width="100%"> 
            <thead>
                <tr>
                    <th width="15%" align="center" style="text-align: center;">{$MOD.LBL_NAME}</th>
                    <th width="15%" align="center" style="text-align: center;">{$MOD.LBL_MOBILE_PHONE}</th>
                    <th width="20%" align="center" style="text-align: center;">{$MOD.LBL_EMAIL_ADDRESS}</th>
                    <th width="15%" align="center" style="text-align: center;">{$MOD.LBL_BIRTHDATE}</th>
                    <th width="20%" align="center" style="text-align: center;">{$MOD.LBL_PARENT}</th>
                </tr>
            </thead>
            <tbody>
            {$ROW_DATA}                   
            </tbody>
        </table>
    </form>
</div>  