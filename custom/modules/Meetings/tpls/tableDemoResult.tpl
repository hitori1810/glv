{sugar_getscript file="custom/modules/Administration/smsPhone/javascript/jquery.jmpopups-0.5.1.js"}
{sugar_getscript file="custom/modules/Administration/smsPhone/javascript/smsPhone.js"}
<link rel="stylesheet" type="text/css" href="custom/modules/Administration/smsPhone/style/smsPhone.css" />
{literal}
<style>
    .demo_template:hover td{
    background:#ecf7ff;
    }

</style>
{/literal}
<form method="POST" name="form_result_demo" id="form_result_demo" enctype="multipart/form-data">
    <table id="diagnosis_list_demo" width="100%" border="0" class="list view">
        <thead>
            <tr class="row" style="margin-left: 5px;margin-top: 10px;">
                <td colspan="10">
                    <select style="float:left;" name="parent_type" id="parent_type" style="margin-left: 20px;">
                        <option value="Leads">Lead</option>
                        <option value="Contacts">Student</option>
                    </select>
                    <button  style="float:left; margin-left: 20px;" type="button" id="btnSelectDemo" class="button" onclick="clickChooseStudentDemo()" >{$MOD.LBL_SELECT}</button>
                    <button  style="float:left; margin-left: 20px;display:none" type="button" id="btnAddRowDemo">Add</button>
                    <button  style="float:left; margin-left: 20px;" type="button" id="btnFreeText" class="button" >{$MOD.LBL_FREE_TEXT}</button>
                    {$ADD_ANOTHER_BUTTON}

                    <a target="_blank" style="float:left; margin-left: 20px;" href="index.php?action=ReportCriteriaResults&module=Reports&page=report&id=a303b49b-b287-78f9-6168-5689e6d5313d" class="button">Export Demo List</a>



                    <div style="float:left;" class="selectedTopSupanel"></div> </td>
            </tr>
            <tr>
                <th style="text-align: left;padding-left: 5px !important;">
                    <input  type="checkbox" class="checkall_custom_checkbox" module_name="J_PTResult" onclick="handleCheckBox($(this));"/>
                </th>
                <th style="text-align: center;width: 1%;"><span sugar="slot12" style="white-space:normal;">No.</span></th>
                <th style="text-align: center;width: 15%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_NAME}</span></th>
                <th style="text-align: center;width: 7%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_GENDER}</span></th>
                <th style="text-align: center;width: 7%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_BIRTHDAY}</span></th>
                <th style="text-align: center;width: 15%;"><span sugar="slot12" style="white-space:normal;">Parent</span></th>
                <th style="text-align: center;width: 11%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_PHONE}</span></th>
                <th style="text-align: center;width: 11%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_EMAIL}</span></th>
                <th style="text-align: center;width: 7%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_SESSION_STATUS}</span></th>
                <th style="text-align: center;width: 9%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_SOURCE}</span></th>
                <th style="text-align: center;width: 15%;"><span sugar="slot12" style="white-space:normal;">First EC</span></th>
                <th style="text-align: center;width: 8%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_ATTENDED}</span></th>
                <th style="text-align: center;width: 15%;"><span sugar="slot12" style="white-space:normal;">{$MOD.LBL_EC_NOTE}</span></th>

                <th style="text-align: center;"><span sugar="slot12" style="white-space:normal;">&nbsp;</span></th>
            </tr>
        </thead>
        <tbody id="tbodyDemo">
            {$html_tpl}
            {$html}
        </tbody>
    </table>
</form>
{sugar_getscript file="custom/modules/Meetings/js/demoResults.js"}