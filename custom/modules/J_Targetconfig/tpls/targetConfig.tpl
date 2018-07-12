{sugar_getscript file="custom/modules/J_Targetconfig/js/targetConfig.js"}
{sugar_getscript file="custom/include/javascripts/Bootstrap/bootstrap.min.js""}
{sugar_getscript file="custom/include/javascripts/DataTables/js/dataTables.bootstrap.js""}
{sugar_getscript file="custom/include/javascripts/DataTables/js/Fixedcols.js""}

<link rel='stylesheet' href='{sugar_getjspath file="custom/modules/C_Classes/tpls/css/style_nd.css"}'/>
<link rel='stylesheet' href='{sugar_getjspath file="custom/modules/C_Classes/tpls/css/table_nd.css"}'/>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/include/javascripts/DataTables/css/jquery.dataTables.min.css'}">
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/include/javascripts/Bootstrap/bootstrap.min.css'}">

<form action="" method="POST" name="TargetConfigForm" id="TargetConfigForm">
    <div class="container">
        <div class="page-header">
            <h1>Target Config</h1>
        </div>
        <table class="table_config">
            <tbody>
                <tr>
                    <td width="15%" nowrap>
                        <b>Center: <span class="required">*</span> </b>
                    </td>
                    <td nowrap colspan='3'>
                   <select id="tg_center" name="tg_center" style="width: 200px;">{$option_center}</select>
                    </td>
                </tr>
                <tr>
                    <td width="15%" nowrap>
                        <b>{$MOD.LBL_TYPE}: <span class="required">*</span> </b>
                    </td>
                    <td nowrap colspan='3'>
                   <select id="tg_type" name="tg_type" style="width: 200px;">{$option_type}</select>
                    </td>
                </tr>
                <tr>
                    <td width="15%" nowrap>
                        <b>{$MOD.LBL_YEAR}: <span class="required">*</span> </b></span>
                    </td>
                    <td width="35%" colspan='3' nowrap>
                    <select id="tg_year" name="tg_year" style="width: 100px;">{$option_year}</select>
                    </td>
                </tr>
                <tr>
                    <td width="15%" nowrap>
                        <b>{$MOD.LBL_FREQUENCY}: <span class="required">*</span> </b>
                    </td>
                    <td width='35%' nowrap>
                    <select id="tg_frequency" name="tg_frequency" style="width: 100px;">{$option_frequency}</select>
                    </td>
                    <td width = "15%" nowrap>
                        <b>{$MOD.LBL_TIME_UNIT}: <span class="required">*</span> </b>
                    </td>
                    <td width='35%' nowrap>
                    <select id="tg_unit_from" name="tg_unit_from">{$option_unit_from}</select> - To - <select id="tg_unit_to" name="tg_unit_to">{$option_unit_to}</select>
                    </td>
                </tr>
                <tr>
                    <td nowrap colspan='2' align='right'>
                    <input class="button primary" type="button" name="tg_loadconfig" value="Load Config" id="tg_loadconfig" style="padding: 6px 10px 6px 10px;"></td>
                    <td nowrap colspan='2' align='left'>
                    <input class="button" type="button" name="tg_refresh" value="Refresh" id="tg_refresh" style="padding: 6px 10px 6px 10px;">
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="result_table" style="text-align: center;"></div>
    </div>
</form>
</div>

