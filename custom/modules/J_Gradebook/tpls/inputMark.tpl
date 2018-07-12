<html>
    <head>
        <title></title>
        {sugar_getscript file="custom/include/javascripts/Select2/select2.min.js"}
        {sugar_getscript file="custom/include/javascripts/Numeric.js"}
        {sugar_getscript file="custom/modules/J_Gradebook/js/inputMark.js"}

        <link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/include/javascripts/Select2/select2.css'}"/>
        <link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/modules/J_Gradebook/css/inputMark.css'}"/>
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
</style>
{/literal}
    </head>
    <body>
        <br/>
        <br/>
        <form action="index.php" name="InputMark" id ="InputMark">
            <input name = 'process_type' value="inputMark" type="hidden">
            <input name = 'grade_config' value="{$FIELDS.grade_config}" type="hidden">
            <input name = 'weight' value="{$FIELDS.weight}" type="hidden">
            <div id = "main_form">
                <div class="overide">
                    <div class="title"><p><b>Gradebook Detail</b></p></div>
                    <div class = "content">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td class="lable" width="20%">{$MOD.LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE}:</td>
                                    <td width="1%"></td>
                                    <td class="field" width="79%">
                                        <input type="text" name="j_class_j_gradebook_1_name" class="sqsEnabled yui-ac-input" tabindex="0" id="j_class_j_gradebook_1_name" size="" value="{$FIELDS.j_class_j_gradebook_1_name}" title="" autocomplete="off" db-data="{$FIELDS.j_class_j_gradebook_1_name}">
                                        <input type="hidden" name="j_class_j_gradebook_1j_class_ida" id="j_class_j_gradebook_1j_class_ida" value="{$FIELDS.j_class_j_gradebook_1j_class_ida}" db-data="{$FIELDS.j_class_j_gradebook_1j_class_ida}">
                                        <span class="id-ff multiple">
                                            <button type="button" name="btn_j_class_j_gradebook_1_name" id="btn_j_class_j_gradebook_1_name" tabindex="0" title="Select" class="button" value="Select"
                                                onclick="
                                                open_popup(
                                                'J_Class', 600, 400, '', true, false,
                                                {ldelim}'call_back_function':'set_return','form_name':'InputMark',
                                                'field_to_name_array':{ldelim}'id':'j_class_j_gradebook_1j_class_ida','name':'j_class_j_gradebook_1_name'{rdelim}{rdelim},
                                                'single',
                                                true
                                                );
                                                " db-data="Select"><img src="themes/default/images/id-ff-select.png">
                                            </button>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lable" width="20%">{$MOD.LBL_NAME}: </td>
                                    <td width="1%"></td>
                                    <td class="field" width="79%">
                                        <select class="select_config gradebook_id" id = 'gradebook_id' name = 'gradebook_id' >
                                            {$GRADEBOOK_OPTIONS}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lable" width="20%">{$MOD.LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE}:</td>
                                    <td width="1%"></td>
                                    <td class="field" width="79%">
                                        <input type="text" name="c_teachers_j_gradebook_1_name" class="sqsEnabled yui-ac-input" tabindex="0" id="c_teachers_j_gradebook_1_name" size="" value="{$FIELDS.c_teachers_j_gradebook_1_name}" title="" autocomplete="off" db-data="{$FIELDS.c_teachers_j_gradebook_1_name}">
                                        <input type="hidden" name="c_teachers_j_gradebook_1c_teachers_ida" id="c_teachers_j_gradebook_1c_teachers_ida" value="{$FIELDS.c_teachers_j_gradebook_1c_teachers_ida}" db-data="{$FIELDS.c_teachers_j_gradebook_1c_teachers_ida}">
                                        <span class="id-ff multiple">
                                            <button type="button" name="btn_c_teachers_j_gradebook_1_name" id="btn_c_teachers_j_gradebook_1_name" tabindex="0" title="Select" class="button" value="Select"
                                                onclick="
                                                open_popup(
                                                'C_Teachers', 600, 400, '', true, false,
                                                {ldelim}'call_back_function':'set_return','form_name':'InputMark',
                                                'field_to_name_array':{ldelim}'id':'c_teachers_j_gradebook_1c_teachers_ida','name':'c_teachers_j_gradebook_1_name'{rdelim}{rdelim},
                                                'single',
                                                true
                                                );
                                                " db-data="Select"><img src="themes/default/images/id-ff-select.png">
                                            </button>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center">
                                        <input type="button" class="find button primary" id = 'find' name = 'find' grade-reload = '0' value="{$MOD.LBL_FIND}">
                                        <input type="button" class="button " id = 'clear' name = 'clear' value="{$APP.LBL_CLEAR_BUTTON_LABEL}">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="height: 25px;"></div>
                <div class = 'gradebook_detail'>
                    {$GRADEBOOK_CONTENT}
                </div>
                <div class="config_button">
                    <input type="button" class="button primary" id = 'save' name = 'save' value="{$APP.LBL_SAVE_BUTTON_TITLE}">
                    <input type="button" class="button" grade-reload = '1' id = 'load_config' name = 'load_config' value="{$MOD.LBL_LOADNEWCONFIG}">
                    <!--                    <input type="button" class="button " id = 'refresh' name = 'refresh' value="{$APP.LBL_REFRESH_BUTTON_TITLE}"> -->
                </div>
            </div>
        </form>
        <div id = 'comment_dialog' style="display:none;">
            <div class="dialog_header">
                <table >
                    <tbody>
                        <tr>
                            <td class="right"><b>Student Name:</b></td>
                            <td class="mid"></td>
                            <td><span id = 'dialog_student_name'  class="bold"></span></td>
                        </tr>
                        <tr>
                            <td  class="right"><b>Class:</b></td>
                            <td class="mid"></td>
                            <td><span id = 'dialog_student_class' class="bold"></span></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
            </div>
            <div class="dialog_content">
                <table >
                    <tbody>
                        {foreach from=$COMMENTLIST key=k item=COMMENT}
                        <tr>
                            <td class="right label_comment"><b>{$COMMENT.LABEL}:</b></td>
                            <td class="mid"></td>
                            <td>
                                <select id='{$COMMENT.ID}' class = '{$COMMENT.ID} select_comment'>
                                    {$COMMENT.OPTIONS}
                                </select>
                            </td>
                        </tr>
                        {/foreach}
                        <tr>
                            <td class="right label_comment"><b>{$MOD.LBL_COMMENT_OTHER}:</b></td>
                            <td class="mid"></td>
                            <td>
                                <textarea id='gradebook_other_comment' class = 'gradebook_other_comment select_comment'>
                                </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td  class="right"><b>Review by English:</b></td>
                            <td class="mid"></td>
                            <td>
                                <textarea cols="" rows="" name='dialog_text_comment' id = 'total_comment' class="text_comment"></textarea>
                                <input type="hidden" name = 'dialog_key_comment'>
                            </td>
                        </tr>
                        <tr>
                            <td  class="right"><b>Review by Vietnamese:</b></td>
                            <td class="mid"></td>
                            <td>
                                <textarea cols="" rows="" name='dialog_text_vietnamese' id = 'total_comment' class="text_comment"></textarea>
                                <input type="hidden" name = 'dialog_key_comment'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>