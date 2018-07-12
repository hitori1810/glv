<div id="SpotResults">
    <table width="100%" id="celebs_notification" cellpadding="0" cellspacing="0">
    <tfoot>
    <tr><td colspan="3" align="center"><a style='font-weight:bold;' href='index.php?module=Notifications&action=ListView' title='View Notifications' rel='tooltip'><i class="icon icon-plus"></i> View Notifications</a></td></tr>
    </tfoot>
    <tbody>
        {foreach from=$data item=n}
             <tr id="n_{$n->id}" {if $n->status == 'Read'} class='' {else} class="Unread" {/if}>
             <td width="15%"><span class="nofi-label nofi-{$n->parent_type}">{$n->parent_type}</span></td>
             <td width="75%">{$n->content}<br><span class="timestampContent">{$n->timeLapse}</span></td>
             <td width="10%"><span id="nofi_toggle" style="font-size: 10px;" onclick="markNotification('{$n->id}');" class="nofi-label nofi-Default" title="{if $n->status == 'Read'}Mark as Unread{else}Mark as Read{/if}">{$n->status}</span></td>
             </tr>
        {foreachelse}

        {/foreach}
    </tbody>
        </table>
        <input type="hidden" name="js_notification" id="js_notification" value="{$js_notification}">
</div>
