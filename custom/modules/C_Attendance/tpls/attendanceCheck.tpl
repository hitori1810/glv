<link rel='stylesheet' type='text/css' href='custom/modules/C_Attendance/tpls/css/style_nd.css'>   
<link rel='stylesheet' type='text/css' href='custom/modules/C_Attendance/tpls/css/table_nd.css'>

<script type='text/javascript' src='custom/modules/C_Attendance/js/attendanceCheck.js'></script>
<script type="text/javascript" src="custom/include/javascripts/DataTables/js/jquery.dataTables.min.js"></script>
<script type='text/javascript' src='custom/include/javascripts/DataTables/js/Fixedcols.js'></script>
<script type='text/javascript' src='custom/include/javascripts/DataTables/js/jquery.blockui.js'></script>
<script type='text/javascript' src='custom/include/javascripts/DataTables/js/jquery.jeditable.js'></script>
<script type='text/javascript' src='custom/include/javascripts/jquery.qtip-1.0.0-rc3.custom/jquery.qtip-1.0.0-rc3.min.js'></script>
<script type='text/javascript' src='custom/include/javascripts/Select2/select2.min.js'></script>
<link rel='stylesheet' type='text/css' href='custom/include/javascripts/Select2/select2.css'>
<form action="" method="POST" name="AttendanceCheck" id="AttendanceCheck">
    <div class="container">
        <div class="page-header">
            <h1>{$MOD.LBL_ATTENDANCE_HISTORY}</h1>
        </div>
        <table class="table_config">
            <tbody>


                <tr> 
                    <td width="15%" nowrap>
                        <b>{$MOD.LBL_AC_TITLE}:</b> <span class="required">*</span> </b></span>
                    </td>
                    <td NOWRAP width = "85%" nowrap>

                        <input type="radio" name="ac_for" id = "ac_for_0" value="today">
                        <label class="rad_" for="ac_for_0">{$MOD.LBL_TODAY}</label>
                        </input>&nbsp&nbsp
                        
                          
                        <input type="radio" name="ac_for" id = "ac_for_1" value="this_week">
                        <label class="rad_" for="ac_for_1">{$MOD.LBL_THIS_WEEK}</label>
                        </input>&nbsp&nbsp

                        <input type="radio" name="ac_for" id = "ac_for_2" value="this_month">
                        <label class="rad_" for="ac_for_2">{$MOD.LBL_THIS_MONTH}</label>
                        </input> &nbsp&nbsp

                        <input type="radio" name="ac_for" id = "ac_for_3" value="option">
                        <label class="rad_" for="ac_for_3">{$MOD.LBL_OPTION}</label>
                        </input> &nbsp&nbsp
                        <select name="select_month" id = "select_month"  style = 'width:60px; display: none;' onchange = 'ajaxGetClass()'>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>


                        <input name="validate_ac" id="validate_ac" type="hidden"/>
                    </td>                  
                </tr>




                <tr> 
                    <td width="15%" nowrap>
                        <b>{$MOD.LBL_CLASS}:</b> <span class="required">*</span> </b></span>
                    </td>
                    <!--<td NOWRAP width = "85%">
                    <input type="text" name="class_name" id="class_name">                                     
                    <input type="hidden" name="class_id" id="class_id">
                    <input type="hidden" name="content_class" id="content_class">
                    <input title="{$MOD.LBL_SELECT}" type="button" class="button" value="{$MOD.LBL_SELECT}" id="btn_select_class"> 
                    </td>--> 
                    <td>
                        <div id = 'select_class'><select id = 'select_class'></select></div>
                    </td>
                </tr>

                <tr>
                    <td colspan='2' nowrap align = 'center'>
                        <input class="button primary" type="button" name="ac_check" value="{$MOD.LBL_FIND_CLASS}" id="ac_check"> 
                        <input class="button" type="button" name="ac_clear" value="{$MOD.LBL_CLEAR}" id="ac_clear" onclick="diableAndClear('top',false)">
                    </td>
                </tr>                                
            </tbody>
        </table>
        <div id="result_table">             
        </div>
        <div id="result_help"></div>

    </div>

</form>
