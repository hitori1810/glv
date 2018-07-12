<input type="hidden" name="to_assigned_to" value="0">

<label>
    <input type="checkbox" name="to_assigned_to" id="to_assigned_to" value="1" {if $fields.to_assigned_to.value==1}checked {/if}/>{$MOD.LBL_TO_ASSIGNED_TO}
</label>
<br />

<input type="hidden" name="to_other_email" value="0">

<label>
    <input type="checkbox" name="to_other_email" id="to_other_email" {if $fields.to_other_email.value==1}checked {/if} value="1" onclick="showInputEmail();"/>{$MOD.LBL_TO_OTHER_EMAIL}
</label>  
              
<input name="other_email" id="other_email" type="text" hidden value="{$fields.other_email.value}"/>
</br>
<div id="notify" style="margin-top: 10px;"></div>