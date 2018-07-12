<html>
    <head> 
        <script src="modules/C_ConfigID/tpls/js/script_save.js"></script>    
        <link rel="stylesheet" href="modules/C_ConfigID/tpls/css/style_config.css">
    </head>
    <body>
        <br>
        <div style="margin-left: 10%;margin-top: 5%;">
            <input type="button" class="button primary" value="Add Record" id="add_new">
            <table width="80%" border="0" id="table-list" cellpadding="0" cellspacing="0" class="table-list">
                <tr>
                    <th width="10%">Prefix</th>
                    <th width="10%">Separator</th>
                    <th width="15%">Field Code</th>
                    <th width="15%">Module Name</th>
                    <th width="20%">Date Format</th>
                    <th width="10%">Is Reset</th>
                    <th width="10%">Padding</th>
                    <th width="15%">First Number</th>
                    <th width="10%">Action</th>
                </tr>
                {$table_body}
            </table>
        </div>
        <div class="entry-form">
            <form name="configinfo" id="configinfo"> 
                <table width="100%" class="table-list" border="0" cellpadding="4" cellspacing="0">
                    <tr>
                        <td colspan="2" align="right"><a href="#" id="close">Close</a></td>
                    </tr>
                    <tr>
                        <td>Module Name <span class="required">*</span></td>
                        <td><select name = "module_name" id="module_name">{$select_module}</select></td>
                    </tr>
                    <tr>
                        <td>Field Code<span class="required">*</span></td>
                        <td><div><select name = "code_field" id="code_field" ><option value=''>-none-</option></select></div><br>
                            <input type="text" name="custom_field" id="custom_field" placeholder="Field Name e.g. custom_code" size="25"></td>
                    </tr>
                    <tr>
                        <td>Prefix <span class="required">*</span></td>
                        <td><input type="text" name="name" id="name"></td>
                    </tr>
                    <tr>
                        <td>Separator</td>
                        <td><input type="text" name="code_separator" id="code_separator" size="20"></td>
                    </tr>
                    <tr>
                        <td>Reset by period of format</td>
                        <td><input type="hidden" name="is_reset" value="0"><input style="width: 1.5em; height: 1.5em;" type="checkbox" name="is_reset" id="is_reset" value="1" tabindex="0"></td>
                    </tr>
                    <tr>
                        <td>Date Format</td>
                        <td><select name = "date_format" id = "date_format">{$select_date_format}</select><br><br>
                            <input type="text" name="custom_format" id="custom_format" placeholder="Date Format" size="10">
                        </td>
                    </tr>      
                    <tr>
                        <td>Padding <span class="required">*</span></td>
                        <td>
                            <select name = "zero_padding" id = "zero_padding"><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>
                        </td>
                    </tr>
                    <tr>
                        <td>First Number <span class="required">*</span></td>
                        <td><input type="text" name="first_num" id="first_num" size="20"></td>
                    </tr>
                    <tr>
                        <td align="right"></td>
                        <td><div class="action_buttons">
                            <input title="Save" accesskey="a" class="button primary" action="save" type="button" value="Save" id="save_config">  <input  accesskey="l" class="button" type="button" name="button" value="Cancel" id="cancel">  <div class="clear"></div></div></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>