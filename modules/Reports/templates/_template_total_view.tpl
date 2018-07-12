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
{php}
// start template_total_table code
global $mod_strings;
require_once('modules/Reports/templates/templates_reports.php');
$reporter = $this->get_template_vars('reporter');
$total_header_row = $reporter->get_total_header_row();
$total_row = $reporter->get_summary_total_row();
if ( isset($total_row['group_pos'])) {
	$args['group_pos'] = $total_row['group_pos'];
} // if
if ( isset($total_row['group_column_is_invisible'])) {
	$args['group_column_is_invisible'] = $total_row['group_column_is_invisible'];
} // if
	$reporter->layout_manager->setAttribute('no_sort',1);
	echo get_form_header( $mod_strings['LBL_GRAND_TOTAL'],"", false);
	template_header_row($total_header_row,$args);
{/php}
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="list view">
{if ($show_pagination neq "")}
{$pagination_data}
{/if}
<tr height="20">
{if ($isSummaryCombo)}
<td scope="col" align='center'  valign=middle nowrap>&nbsp;</td>
{/if}
{if ($isSummaryComboHeader)}
<td><span id="img_{$divId}"><a href="javascript:expandCollapseComboSummaryDiv('{$divId}')"><img width="8" height="8" border="0" absmiddle="" alt=$mod_strings.LBL_SHOW src="{$image_path}advanced_search.gif"/></a></span></td>
{/if}
{php}
	$count = 0;
	$this->assign('count', $count);
{/php}
{foreach from=$header_row key=module item=cell}
	{if (($args.group_column_is_invisible != "") && ($args.group_pos eq $count))}
{php}
	$count = $count + 1;
	$this->assign('count', $count);
{/php}
	{ else }
	{if $cell eq ""}
{php}
	$cell = "&nbsp;";
	$this->assign('cell', $cell);
{/php}
	{/if}

	<td scope="col" align='center'  valign=middle nowrap>

	{$cell}
	{/if}
{/foreach}
</tr>
{php}
	if (! empty($total_row)) {
	template_list_row($total_row);
{/php}
	<tr height=20 class="{$row_class}" onmouseover="setPointer(this, '{$rownum}', 'over', '{$bg_color}', '{$hilite_bg}', '{$click_bg}');" onmouseout="setPointer(this, '{$rownum}', 'out', '{$bg_color}', '{$hilite_bg}', '{$click_bg}');" onmousedown="setPointer(this, '{$rownum}', 'click', '{$bg_color}', '{$hilite_bg}', '{$click_bg}');">
	{if ($isSummaryComboHeader)}
	<td><span id="img_{$divId}"><a href="javascript:expandCollapseComboSummaryDiv('{$divId}')"><img width="8" height="8" border="0" absmiddle="" alt=$mod_strings.LBL_SHOW src="{$image_path}advanced_search.gif"/></a></span></td>
	{/if}
	{php}
		$count = 0;
		$this->assign('count', $count);
	{/php}
	{foreach from=$column_row.cells key=module item=cell}
		{if (($column_row.group_column_is_invisible != "") && ($count|in_array:$column_row.group_pos)) }
	{php}
		$count = $count + 1;
		$this->assign('count', $count);
	{/php}
		{ else }
		{if $cell eq ""}
	{php}
		$cell = "&nbsp;";
		$this->assign('cell', $cell);
	{/php}
		{/if}
        {if substr_count($cell, '/') == 2 }
        <td style='mso-number-format:"dd\/mm\/yyyy";text-align: left;' width="{$width}%" valign=TOP class="{$row_class}" bgcolor="{$bg_color}" {if $scope_row} scope='row' {/if}>
        <td style='mso-number-format:"dd\/mm\/yyyy";text-align: left;' width="{$width}%" valign=TOP class="{$row_class}" bgcolor="{$bg_color}" scope="row">
        {elseif (date('Y-m-d', strtotime($cell)) == $cell)}
        <td style='mso-number-format:"yyyy-mm-dd";text-align: left;' width="{$width}%" valign=TOP class="{$row_class}" bgcolor="{$bg_color}" {if $scope_row} scope='row' {/if}>
        <td style='mso-number-format:"yyyy-mm-dd";text-align: left;' width="{$width}%" valign=TOP class="{$row_class}" bgcolor="{$bg_color}" scope="row">
        {elseif ((strlen($cell) > 4) && (preg_match("/^[0-9]+$/", $cell)))}
        <td style="mso-number-format:\@;" width="{$width}%" valign=TOP class="{$row_class}" bgcolor="{$bg_color}" {if $scope_row} scope='row' {/if}>
        <td style="mso-number-format:\@;" width="{$width}%" valign=TOP class="{$row_class}" bgcolor="{$bg_color}" scope="row">
        {else}
        <td width="{$width}%" valign=TOP class="{$row_class}" bgcolor="{$bg_color}" scope="row">
        {/if}

		{$cell}
		{/if}
	{/foreach}
	</tr>

{php}
	} else {
	echo template_no_results();
	}
echo template_end_table($args);
	// end template_total_table code
//template_total_table($reporter);
template_query_table($reporter);
{/php}