{*
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

*}
</form>

{*Added by Hieu Nguyen on 2015-05-28 to enable including scripts via javascript block in detailviewdef.php *}
{{if $externalJSFile}}
    {sugar_include include=$externalJSFile}
{{/if}}

{{if isset($scriptBlocks)}}
    {{$scriptBlocks}}
{{/if}}
{*End Hieu Nguyen*}

<script>SUGAR.util.doWhen("document.getElementById('form') != null",
        function(){ldelim}SUGAR.util.buildAccessKeyLabels();{rdelim});
</script>