{sugar_getscript file="custom/include/javascripts/Selecttablelist/jquery.selectable-list.js"}

<div id="div_bookgift" style="display: none;">
    <table id="tblLevelConfig" >
        <thead>
            <tr><td ><button class="button" type="button" id="btnAddrow">Add</button></td></tr>
        </thead>
        <tbody id="tbodylLevelConfig">
            {$BOOK}
        </tbody>
    </table>
</div>

<div id="div_partnership" style="display: none;">
    <table id="tblpa" >
        <thead>
            <tr><td ><button class="button" type="button" id="btnAdd">Add</button></td></tr>
        </thead>
        <tbody id="tbodypa">
            {$PA}
        </tbody>
    </table>
</div>

<div id="div_hour" style="display: none;">
{sugar_getscript file="custom/include/javascripts/Multifield/jquery.multifield.min.js"}
<table id="tblHourConfig" width="80%" border="1" class="list view">
    <thead>
        <tr><th colspan="3" align="center">Level discount by hour</th></tr>
        <tr>
            <th width="20%" style="text-align: center;">Tuition Hours</th>
            <th width="20%" style="text-align: center;">Promotion Hours</th>
            <td width="10%" style="text-align: center;"><button class="button" type="button" id="btnAddrowHour"><img src="themes/default/images/id-ff-add.png" alt="Add new"></button></td></td>
        </tr>
    </thead>
    <tbody id="tbodyHourConfig">
    {$DIH}
    </tbody>
</table>
<table id="tblBlockConfig" width="80%" border="1" class="list view">
    <thead>
        <tr><th colspan="3" align="center">Level discount by range</th></tr>
        <tr>
            <th width="20%" style="text-align: center;">Hour Range</th>
            <th width="20%" style="text-align: center;">Block</th>
            <td width="10%" style="text-align: center;"><button class="button" type="button" id="btnAddrowRange"><img src="themes/default/images/id-ff-add.png" alt="Add new"></button></td>
        </tr>
    </thead>
    <tbody id="tbodyBlockConfig">
    {$BO}
    </tbody>
</table>
</div>



