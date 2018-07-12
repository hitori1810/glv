<tr class='row_tpl' {$DISPLAY}>
    <td align="center">
        <select name="levels[]" class="levels">
            {$LEVEL_OPTIONS}
        </select>
    </td>
    <td align="center">
        <select name="modules[]" multiple="multiple" class="modules">
            {$MODULE_OPTIONS}
        </select>
    </td>
    <td nowrap align="center">
        <!--<table id="tblBook">
            <tr>
                <td>
                    <button type="button" id="addBook">
                        <img src="themes/default/images/id-ff-add.png?">
                    </button>
                </td>
            </tr>
            {$BOOK_LIST}
            <tfoot style="display:none;" >
                <tr>
                    <td>
                        <input name="book_name[]" value="" class="book_name" type="text" style="margin-right: 10px;">
                        <input name="book_id[]" value="" class="book_id" type="hidden">
                        <button type="button" class="btn_choose_book" onclick="clickChooseBook($(this))">
                            <img src="themes/default/images/id-ff-select.png">
                        </button>
                        <button type="button" name="btn_clr_book" class="btn_clr_book" >
                            <img src="themes/default/images/id-ff-remove-nobg.png?">
                        </button>
                    </td>
                </tr>
            <tfoot>
        </table> -->
        <input name="hours[]" value="{$HOURS}" class="hours" type="text" style="width: 70px;text-align: center;">
    </td>
    <td align="center">
        {if $IS_SET_HOUR == '1'}
        <input type="checkbox" name="is_set_hour" class="is_set_hour" checked="" value="{$IS_SET_HOUR}">
        {else}
        <input type="checkbox" name="is_set_hour" class="is_set_hour" value="{$IS_SET_HOUR}">
        {/if}
    </td>
    <td align="center">
        {if $IS_UPGRADE == '1'}
        <input type="checkbox" name="is_upgrade" class="is_upgrade" checked="" value="{$IS_UPGRADE}">
        {else}
        <input type="checkbox" name="is_upgrade" class="is_upgrade" value="{$IS_UPGRADE}">
        {/if}
    </td>
    <td align='center'>
        <input name='jsons[]' value='{$JSON}' class='jsons' type='hidden'/>
        <input type="button" class='button btnRemove' value='{$MOD.LBL_REMOVEROW}'/>
    </td>
</tr>