    <tr>
        <td class="td_1"><label>{$MOD.LBL_USER}:</label></td>
        <td class="td_2">{$SELECTION_USER}</td>
        <td class="td_1"><label>{$MOD.LBL_HOST}:</label></td>
        <td class="td_2"><input style="width:250px" type="text" name="host" class="host" value="{$HOST}"/></td>
    </tr>
    <tr>
        <td class="td_1"><label>{$MOD.LBL_EMAIL}:</label></td>
        <td class="td_2"><input style="width:250px" type="text" name="email" class="email" value="{$EMAIL}"/></td>
        <td class="td_1"><label>{$MOD.LBL_PASSWORD}:</label></td>
        <td class="td_2"><input style="width:250px" type="password" name="password" class="password" value="{$PASSWORD}"/></td>
    </tr>
    <tr>
        <td class="td_1"><label>{$MOD.LBL_SECURE}:</label></td>
        <td class="td_2">{$SELECTION_SECURE}</td>
        <td class="td_1"><label>{$MOD.LBL_PORT}:</label></td>
        <td class="td_2"><input style="width:250px" type="text" name="port" class="port" value="{$PORT}"/></td>
    </tr>
    <tr>
        <td style="border-bottom: dashed 1px #000" class="td_1">{$MOD.LBL_TYPE_SYNC}:</td>
        <td style="border-bottom: dashed 1px #000" class="td_2">{$SELECTION_TYPE}</td>
        <td style="border-bottom: dashed 1px #000" class="td_1"></td>
        <td style="border-bottom: dashed 1px #000" class="td_2">
            <button type="button" class="btnDel btn-danger" onclick="delRow($(this))">{$MOD.LBL_DELETE}</button>
        </td>
    </tr>