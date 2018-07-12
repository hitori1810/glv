<script type='text/javascript' src='custom/include/javascripts/Select2/select2.min.js'></script>
<script type='text/javascript' src='custom/include/javascripts/glDatePicker/glDatePicker.min.js'></script>
<link rel='stylesheet' type='text/css' href='custom/include/javascripts/Select2/select2.css'>
<link rel='stylesheet' type='text/css' href='custom/include/javascripts/glDatePicker/default.css'>
<script type='text/javascript' src='custom/modules/C_Timesheet/js/jstimesheet.js'></script>

<table width="100%" border="0" cellspacing="1" cellpadding="0" id="Default_C_TimeSheets_Subpanel" class="yui3-skin-sam edit view panelContainer">
    <tbody>
        <tr>
            <td valign="top" width="100%" colspan="3">
                <table width="99%" border="1" class="main_timesheet">
                    <tbody>
                        <tr>
                            <td>
                                <input type="hidden" id="example1" value="" >
                                <input type="hidden" id="day" value="" >
                                Legends: <br>
<label><span class="timesheet_day_legend">&nbsp;&nbsp;&nbsp;x&nbsp;&nbsp;&nbsp;</span> Added entries</label> <br>
<label><span class="today_c_legend">&nbsp;&nbsp;&nbsp;x&nbsp;&nbsp;&nbsp;</span> Today</label>
                            </td>
                            <td>
                                <h2 id="dis_apply_date" name="dis_apply_date" class="highlight1"></h2>
                                <br>
                                <table width="100%">
                                    <tbody><tr>
                                            <th>Teacher <font color="red">*</font></th>
                                            <th>Task <font color="red">*</font></th>
                                            <th>Hour(s)</th>
                                            <th>Minute(s)</th>
                                            <th>Description <font color="red">*</font></th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                {$TEACHER_OPTION}
                                                <br><label name="validate_teacher_id" id="validate_teacher_id" style="display:none; color:red;">Missing required field: Teacher</label>
                                            </td>
                                            <td>
                                                <select name="tmp_task_name" id="tmp_task_name" style="width: 200px;">
                                                    {$TASK_OPTION}
                                                </select>
                                                <br><label name="validate_task" id="validate_task" style="display:none; color:red;">Missing required field: Task</label>
                                            </td>
                                            <td><input type="text" id="tmp_hours" name="tmp_hours" size="4" maxlength="2" class="currency" value="0" title="Min: 1hr, Max: 9hrs">
                                                <br><label name="validate_hour" id="validate_hour" style="display:none; color:red;">Wrong input</label>
                                            </td>
                                            <td>
                                                <select name="tmp_minutes" id="tmp_minutes" style="width:50px">
                                                    {$MINUTES_OPTION}
                                                </select> (h:mm)
                                            </td>
                                            <td><textarea id="tmp_description" name="tmp_description" maxlength="150" rows="2" cols="30"></textarea>
                                                <br><label name="validate_description" id="validate_description" style="display:none; color:red;">Missing required field: Description</label>
                                            </td>
                                            <td>{if $addPermission}<input type="button" class="btnAddRow" name="btnAddRow" value="Add">{/if}</td>
                                        </tr>
                                    </tbody></table>
                                <br><br>
                                <h2 class="title">Added entries</h2>
                                <div id="added_entries" style="">
                                    <form action="index.php" method="POST" name="Editview" id="Editview">
                                        <input type="hidden" id="date_add" name="date_add" value="{$DATE_ADD}">
                                        <input type="hidden" id="current_user_name" name="current_user_name" value="{$current_user_name}" >
                                        <input type="hidden" id="current_user_id" name="current_user_id" value="{$current_user_id}" >
                                        <input type="hidden" id="current_team_name" name="current_team_name" value="{$current_team_name}" >
                                        <input type="hidden" id="current_team_id" name="current_team_id" value="{$current_team_id}" >
                                        <table class="timesheets" id="timesheets" border="0" cellspacing="0" cellpadding="2" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width='20%' style='text-align: center;'>Teacher</th>
                                                    <th style='text-align: center;'>Task</th>
                                                    <th>Hours</th>
                                                    <th>Minutes</th>
                                                    <th>Description</th>
                                                    <th width='15%'>Created by</th>
                                                    <th width='15%'>Center</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table></form><p align="right"><b>Total: <span id="total_day">0</span> hours</b></p>
                                </div>
                                {if $addPermission}
                                <input type="button" name="btnSave" id="btnSave" value="Save">
                                {/if}
                                <div id="result">No data!</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<div id="runscript"></div>
{$JAVASCRIPT}
