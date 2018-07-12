<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/modules/C_KeyboardSetting/css/EditView.css'}"/>
<script type="text/javascript">
    var appliedFields = {$APPLIED_FIELDS};
    var availableFields = {$AVAILABLE_FIELDS};
    var correctionTypeOptions = {$CORRECTION_TYPE_OPTIONS};
    var lblAppliedField = '{$MOD.LBL_APPLIED_FIELD}';
    var lblAppliedFieldName = '{$MOD.LBL_APPLIED_FIELD_NAME}';
    var lblAppliedCorrectionType = '{$MOD.LBL_APPLIED_CORRECTION_TYPE}';
    var lblAvailableFields = '{$MOD.LBL_AVAILABLE_FIELDS}';
</script>

<div id="targetFieldsContainer">
    <div id="appliedFieldCol" class="dragAndDropWidget"></div>
    <div id="availableFieldCol" class="dragAndDropWidget"></div>
    <input type="hidden" id="target_fields" name="target_fields"/>
</div>