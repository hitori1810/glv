<script type="text/javascript" src="custom/modules/Administration/js/general_config.js"></script>
<link rel="stylesheet" type="text/css" href="custom/modules/Administration/css/general_config.css">

<table class="tbl_info" cellspacing="0">
    <thead>
    <tr>
        <th colspan="4">{$MOD.LBL_SET_DEFAULT_IMPORT_MAPPING_RECORD}</th>
        </th>
    </tr>
    <tr>
        <td class="td_1">
            <label>{$MOD.LBL_DEFAUL_MAPPING_LEAD}:</label>
        </td>
        <td class="td_2">
            {html_options name="default_mapping_lead" id="default_mapping_lead" options=$MAPPING_LEAD_OPTIONS selected=$DEFAUL_MAPPING_LEAD style="width: auto;" }
        </td>
        <td class="td_1">
        </td>
        <td class="td_2">
        </td>
    </tr>
    </thead>
</table>
</br>
<table class="tbl_info" cellspacing="0">
    <thead>
    <tr>
        <th colspan="4">{$MOD.LBL_PROMOTION}</th>
        </th>
    </tr>
    <tr>
        <td class="td_1">
            <label>{$MOD.LBL_REFER_VOUCHER_TYPE}:</label>
        </td>
        <td class="td_2">
            {html_options name="refer_voucher_type" id="refer_voucher_type" options=$REFER_VOUCHER_TYPE selected=$SELECT_REFER_VOUCHER_TYPE style="width: 150px;" }
        </td>
        <td class="td_2">
            <label>({$MOD.LBL_REFER_VOUCHER_DESCRIPTION})</label>
        </td>  
        <td class="td_1"></td>
    </tr>
    <tr>
        <td class="td_1">
            <label>{$MOD.LBL_REFER_VOUCHER_VALUE}:</label>
        </td>
        <td class="td_2">
            <input class="number" type="text" name="refer_voucher_value" id="refer_voucher_value" value="{$REFER_VOUCHER_VALUE}" style="width: 150px;"></input>
        </td>      
        <td class="td_2">
            <input id="btn_apply_voucher" type="button" class="button" value="{$MOD.BTN_APPLY_FOR_CURRENT_REFER_VOUCHER}" onclick="ajaxApplyVoucherConfig();"/>
        </td>
        <td class="td_1"></td>
    </tr>
    </thead>
</table>
</br>
<div id="div_action" style="text-align:center;">
    <input type="button" class="button primary" id="btn_save" value="{$MOD.LBL_SAVE}" onclick="saveConfig();"/>
</div>

                