<script type="text/javascript" src="custom/modules/J_Kindofcourse/js/ConfigCategory.js"></script>
<link rel="stylesheet" type="text/css" href="custom/modules/J_Kindofcourse/css/ConfigCategory.css">

<h1 class="title">{$MOD.LBL_CONFIG_CATEGORY}</h1>

<table class="tbl_info" cellspacing="0">
    <thead>
    <tr>      
    </tr>
    <tr>
        <td class="td_1">
            <label>{$MOD.LBL_KOC_OPTIONS}:</label>
        </td>
        <td class="td_2">
            <textarea id="koc_options" cols="10" rows="10">{$KOC_OPTIONS}</textarea>
        </td>  
        <td class="td_1">
            <label>{$MOD.LBL_PT_RESULT_OPTIONS}:</label>
        </td>
        <td class="td_2">                                      
            <textarea id="pt_result_options" colscols="10" rows="">{$PT_RESULT_OPTIONS}</textarea>
        </td>   
    </tr>
    <tr>
        <td class="td_1">
            <label>{$MOD.LBL_LEVEL_OPTIONS}:</label>
        </td>
        <td class="td_2">                                      
            <textarea id="level_options" colscols="10" rows="10">{$LEVEL_OPTIONS}</textarea>
        </td>                 
        <td class="td_1">
            <label>{$MOD.LBL_MODULE_OPTIONS}:</label>
        </td>
        <td class="td_2">                                      
            <textarea id="module_options" colscols="10" rows="10">{$MODULE_OPTIONS}</textarea>
        </td> 
    </tr>     
    </thead>
</table>
</br>
<div id="div_action" style="text-align:center;">
    <input type="button" class="button primary" id="btn_save" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="saveConfig();"/>
</div>

                