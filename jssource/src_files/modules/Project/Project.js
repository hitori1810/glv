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




function prep_edit(the_form)
{
	the_form.return_module.value='Project';
	the_form.return_action.value='DetailView';
	the_form.return_id.value='{id}';
	the_form.action.value='EditView';
	the_form.sugar_body_only.value='0';
}

function prep_edit_project_tasks(the_form)
{
	the_form.return_module.value='Project';
	the_form.return_action.value='DetailView';
	the_form.return_id.value='{id}';
	the_form.action.value='EditGridView';
	the_form.sugar_body_only.value='0';
}

function prep_duplicate(the_form)
{
	the_form.return_module.value='Project';
	the_form.return_action.value='index';
	the_form.isDuplicate.value=true;
	the_form.action.value='EditView';
	the_form.sugar_body_only.value='0';
}

function prep_delete(the_form)
{
	the_form.return_module.value='Project';
	the_form.return_action.value='ListView';
	the_form.action.value='Delete';
	the_form.sugar_body_only.value='0';
}

function prep_save_as_template(the_form)
{
	the_form.return_module.value='Project';
	the_form.return_action.value='DetailView';
	the_form.return_id.value='{id}';
	the_form.action.value='Convert';
	the_form.sugar_body_only.value='0';
}
function prep_save_as_project(the_form)
{
	the_form.return_module.value='Project';
	the_form.return_action.value='ProjectTemplatesDetailView';
	the_form.return_id.value='{id}';
	the_form.action.value='Convert';
}

function prep_export_to_project(the_form)
{
	the_form.return_module.value='Project';
	the_form.return_action.value='DetailView';
	the_form.return_id.value='{id}';
	the_form.action.value='Export';
	the_form.sugar_body_only.value='1';
}
