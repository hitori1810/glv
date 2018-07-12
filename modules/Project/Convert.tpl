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

<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td>
			<form name="EditView" id="EditView" method="post" action="index.php">
			<input type="hidden" name="module" value="Project" />
			<input type="hidden" name="record" value="{$ID}" />
			<input type="hidden" name="team_id" value="{$TEAM}" />
			<input type="hidden" name="to_pdf" id="to_pdf" value="1">
			<input type="hidden" name="action" id="action" value="Save" />
			<input type="hidden" name="save_type" value="{$SAVE_TYPE}" />
			{foreach from=$PROJECT_FORM item="PROJECT" key="PROJECT_KEY"}
				<input type="hidden" name="{$PROJECT_KEY}" value="{$PROJECT}" />
			{/foreach}
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
				<tr>
					<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">			
					<tr>				
						<td scope="row"><span sugar='slot1'>{$SAVE_TO_LBL}<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></span sugar='slot'> <input type="text" name="{$SAVE_TO}_name" value="{$NAME}"  /></td>
						<td align="right">
							<input type="submit" name="button" value="  {$SAVE_BUTTON}  "
							       class="button" tabindex="6"
								   onclick="this.form.module.value='Project'; this.form.action.value='Save'; this.form.record.value='{$ID}';return check_form('EditView');"
								   title="{$SAVE_BUTTON}" />
						</td>
					</table>
			</form>
		</td>
	</tr>
</table>