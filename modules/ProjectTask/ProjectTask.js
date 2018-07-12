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
function prep_edit_task_in_grid(the_form)
{the_form.return_module.value='ProjectTask';the_form.return_action.value='DetailView';the_form.return_id.value='{id}';the_form.module.value='Project';the_form.action.value='EditGridView';}
function update_status(percent_complete){if(percent_complete=='0'){document.getElementById('status').value='Not Started';}
else if(percent_complete=='100'){document.getElementById('status').value='Completed';}
else if(isNaN(percent_complete)||(percent_complete<0||percent_complete>100)){document.getElementById('percent_complete').value='';}
else{document.getElementById('status').value='In Progress';}}
function update_percent_complete(status){if(status=='In Progress'){percent_value='50';}
else if(status=='Completed'){percent_value='100';}
else{percent_value='0';}
document.getElementById('percent_complete').value=percent_value;document.getElementById('percent_complete_text').innerHTML=percent_value;}