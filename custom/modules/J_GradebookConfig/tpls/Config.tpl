{literal}
<style type="text/css">
.table-border td, .table-border th{
    border: 1px solid #ddd;
    border-collapse:collapse;
    line-height: 18px;
    padding: 6px;
    vertical-align: middle;
    text-align: center;
}
 .table-border {
     border-collapse:collapse;
 }
.no-bottom-border {
     border-bottom: 0px solid #ddd !important;
}
.no-top-border {
     border-top: 0px solid #ddd !important;
}
textarea.label{
text-align-last: center;
text-align: justify;
font-weight: bold;
width: 90%;
resize: none;
}
</style>
{/literal}

<html>
    <head>
        <title></title>
        {sugar_getscript file="custom/include/javascripts/Numeric.js"}
        {sugar_getscript file="custom/modules/J_GradebookConfig/js/config.js"}

        <link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/modules/J_GradebookConfig/css/config.css'}"/>
    </head>
    <body>
        <br/>
        <br/>
        <form action="index.php" name="GradeConfig" id ="GradeConfig">
        <input name = 'record' value="{$RECORD}" type="hidden">
        <input name = 'process_type' value="saveConfig" type="hidden">
        <div id = "main_form">
            <div class="overide">
                <div class="title"><p><b>Config Gradebook Structure</b></p></div>
                <div class = "content">
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td class="lable" width="20%">{$MOD.LBL_CENTER}: <span class="required">*</span></td>
                                <td width="1%"></td>
                                <td class="field" width="79%">
                                    <select class="select_config team_id" id = 'team_id' name = 'team_id'>
                                        {$CENTER_OPTIONS}
                                    </select></td>
                            </tr>
                            <tr>
                                <td class="lable" width="20%">{$MOD.LBL_KOC_NAME}: <span class="required">*</span> </td>
                                <td width="1%"></td>
                                <td class="field" width="79%">
                                    <select class="select_config koc_id" id = 'koc_id' name = 'koc_id' >
                                        {$KOC_OPTIONS}
                                    </select></td>
                            </tr>
                            <tr>
                                <td class="lable" width="20%">{$MOD.LBL_TYPE}: <span class="required">*</span> </td>
                                <td width="1%"></td>
                                <td class="field" width="79%">
                                    <select class="select_config type" id = 'type' name = 'type' >
                                        {$TYPE_OPTIONS}
                                    </select>
                                    <select class="select_config minitest" id='minitest' name='minitest' {if empty($MINITEST_SELECTED)}style="display: none;"{/if} style="width: 100px;">
                                        {$MINITEST_OPTIONS}
                                    </select>
                                    </td>
                            </tr>
                            <tr>
                                <td class="lable" width="20%">{$MOD.LBL_NAME}: <span class="required">*</span> </td>
                                <td width="1%"></td>
                                <td class="field" width="79%">
                                    <input type="text" name="gradebook_name" id="gradebook_name" placeholder="Auto-Generate if blank" size="30" maxlength="255" value="{$NAME}" title="{$MOD.LBL_NAME}" accesskey="9">
                                </td>
                            </tr>
                            <tr>
                                <td class="lable" width="20%">{$MOD.LBL_WEIGHT}: <span class="required">*</span> </td>
                                <td width="1%"></td>
                                <td class="field" width="79%">
                                    <input id = 'weight' class="weight" name="weight" value="{$WEIGHT}" size="4"> %
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                 <input type="button" class="find button primary" id = 'find' name = 'find' value="{$MOD.LBL_SET_CONFIG}">
                                 <input type="button" class="clear button" id = 'clear' name = 'clear' value="{$MOD.LBL_CLEAR}">
                                 </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="height: 25px;"></div>
            <div class = 'config'>
                {$CONFIG_CONTENT}
            </div>
            <div class="config_button">
                <input type="button" style="display: none;" class="button primary" id = 'save' name = 'save' value="{$APP.LBL_SAVE_BUTTON_TITLE}">
<!--                <input type="button" class="button " id = 'cancel' name = 'cancel' value="{$APP.LBL_CANCEL_BUTTON_TITLE}">    -->
            </div>
        </div>
        </form>
    </body>
</html>