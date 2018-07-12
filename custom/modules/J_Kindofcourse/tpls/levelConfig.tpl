{sugar_getscript file="custom/include/javascripts/Multifield/jquery.multifield.min.js"}
<table id="tblLevelConfig" width="80%" border="1" class="list view">
    <thead>
        <tr>
            <th width="20%" style="text-align: center;">{$MOD.LBL_LEVEL}</th>
            <th width="20%" style="text-align: center;">{$MOD.LBL_MODULE}</th>
            <th width="20%" style="text-align: center;">{$MOD.LBL_HOURS}<span class="required">*</span></th>
            <th width="20%" style="text-align: center;">{$MOD.LBL_IS_SET_HOUR} </th>
            <th width="20%" style="text-align: center;">{$MOD.LBL_IS_UPGRADE} </th>
            <td width="10%" style="text-align: center;"><button class="button" type="button" id="btnAddrow"><b>+</b></td></td>
        </tr>
    </thead>
    <tbody id="tbodylLevelConfig">
        {$TBODY}
    </tbody>
</table>