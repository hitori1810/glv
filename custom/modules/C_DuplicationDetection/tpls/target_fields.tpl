<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/modules/C_DuplicationDetection/css/EditView.css'}"/>
<script type="text/javascript">
    var appliedFields = {$APPLIED_FIELDS};
    var availableFields = {$AVAILABLE_FIELDS};
    var lblAppliedFields = '{$MOD.LBL_APPLIED_FIELDS}';
    var lblAvailableFields = '{$MOD.LBL_AVAILABLE_FIELDS}';
</script>

<div id="targetFieldsContainer">
    <div id="applied_fields_col" class="dragAndDropWidget"></div>
    <div id="available_fields_col" class="dragAndDropWidget"></div>
    <input type="hidden" id="target_fields" name="target_fields"/>
</div>