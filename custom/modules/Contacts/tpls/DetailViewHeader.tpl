
{assign var=preForm value="<table width='100%' border='1' cellspacing='0' cellpadding='0'><tr><td><table width='100%'><tr><td>"}
{assign var=displayPreform value=false}
{if isset($lead_convert_id) && !empty($lead_convert_id)}
{assign var=displayPreform value=true}
{assign var=preForm value=$preForm|cat:$MOD.LBL_CONVERTED_LEAD}
{assign var=preForm value=$preForm|cat:"&nbsp;<a href='index.php?module=Leads&action=DetailView&record="}
{assign var=preForm value=$preForm|cat:$lead_convert_id}
{assign var=preForm value=$preForm|cat:"'>"}
{assign var=preForm value=$preForm|cat:$lead_convert_name}
{assign var=preForm value=$preForm|cat:"</a>"}
{/if}

{assign var=preForm value=$preForm|cat:"</td><td>"}

{assign var=preForm value=$preForm|cat:"</td></tr></table></td></tr></table>"}
{if $displayPreform}
{$preForm}
<br>
{/if}

{{include file='include/DetailView/header.tpl'}}