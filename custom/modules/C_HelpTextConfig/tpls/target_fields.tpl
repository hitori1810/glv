<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/modules/C_HelpTextConfig/css/EditView.css'}"/>
<script type="text/javascript">
    var appliedFields = {$APPLIED_FIELDS};
    var availableFields = {$AVAILABLE_FIELDS};
    var lblAppliedField = '{$MOD.LBL_APPLIED_FIELD}';
    var lblAppliedFieldName = '{$MOD.LBL_APPLIED_FIELD_NAME}';
    var lblHelpText = '{$MOD.LBL_HELP_TEXT}';
    var lblAvailableFields = '{$MOD.LBL_AVAILABLE_FIELDS}';
</script>

<div id="targetFieldsContainer">
    <div id="appliedFieldCol" class="dragAndDropWidget"></div>
    <div id="availableFieldCol" class="dragAndDropWidget"></div>
    <input type="hidden" id="target_fields" name="target_fields"/>
</div>

<div id="frmEditHelpText">
    <textarea id="helpTextEditor"></textarea>
</div>