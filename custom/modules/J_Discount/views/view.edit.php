<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

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


class J_DiscountViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::ViewEdit();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }
    public function display(){
        $arrayNotApplyWith = array();
        if(!isset($this->bean->id) || empty($this->bean->id)){
            $sql  = "SELECT id, name, type FROM j_discount WHERE deleted=0";
            $result = $GLOBALS['db']->query($sql);
            // add gift
            $book = getHtmlAddRow('','',true);
            $book .= getHtmlAddRow('','',false);
            // add partnership
            $pa = getHtmlAddRowPa('','',true);
            $pa .= getHtmlAddRowPa('','',false);
            //add Discount by Hour
            $dih = getHtmlAddRowHour('','',true);
            $dih .= getHtmlAddRowHour('','',false);
            //add Discount by Block
            $bo = getHtmlAddRange('','','',true);
            $bo .= getHtmlAddRange('','','',false);

        }else{
            // add gift
            $book = getHtmlAddRow('','',true);
            if($this->bean->type == 'Gift'){
                $content = json_decode(htmlspecialchars_decode($this->bean->content), true);
                foreach ($content as $key => $value)
                    $book .= getHtmlAddRow($key,$value,false);
            }

            //add partnership
            $pa = getHtmlAddRowPa('','',true);
            if($this->bean->type == 'Partnership'){
                $this->bean->load_relationship('j_discount_j_partnership_1');
                $partnership= $this->bean->j_discount_j_partnership_1->getBeans();
                foreach ($partnership as $part)
                    $pa  .= getHtmlAddRowPa($part->id,$part->name,false);
            }
            //add Discount by Hour
            if($this->bean->type == 'Hour'){
                $content = json_decode(htmlspecialchars_decode($this->bean->content), true);
                $dih = getHtmlAddRowHour('','',true);
                foreach($content['discount_by_hour'] as $key => $value)
                    $dih .= getHtmlAddRowHour($value['hours'],$value['promotion_hours'],false);
                //add Discount by Block
                $bo = getHtmlAddRange('','','',true);
                foreach($content['discount_by_range'] as $key => $value)
                    $bo .= getHtmlAddRange($value['start_hour'],$value['to_hour'],$value['block'],false);

            }

            $arrayNotApplyWith = json_decode(html_entity_decode($this->bean->disable_list),true);
        }
        // Do not apply with options - by Tung Bui
        $sql  = "SELECT
        l1.id, l1.name, l1.type, l2.name team_name
        FROM
        j_discount l1
        INNER JOIN
        teams l2 ON l1.team_id = l2.id AND l2.deleted = 0
        WHERE
        l1.id <> '{$this->bean->id}'
        AND l1.status = 'Active'
        AND l1.deleted = 0
        ORDER BY CASE
        WHEN l1.type = 'Reward' THEN 0
        WHEN l1.type = 'Partnership' THEN 1
        WHEN l1.type = 'Hour' THEN 3
        WHEN l1.type = 'Other' THEN 4
        WHEN l1.type = 'Gift' THEN 4
        ELSE 4
        END ASC , l1.name ASC";
        $result = $GLOBALS['db']->query($sql);
        // Show "do not apply with" options
        $html .= '<table class="table-striped" name="tb_discount_schema[]" id="tb_discount_schema">';
        $html .= '<tbody style="display: block; border: 1px solid lightgray; height: 200px; overflow-y: scroll"> <tr> <td><input type="checkbox" id="checkall" title="Select all"/></td>
        <th> Select all</th> </tr> ';

        //Other discount
        while($row2 = $GLOBALS['db']->fetchByAssoc($result)){
            $checkProp = "";
            if (in_array($row2['id'],$arrayNotApplyWith)) $checkProp = "checked";
            $html .= '<tr ><td><input type="checkbox" name="check_schema[]" value= "'.$row2['id'].'" '.$checkProp.'/></td><td>'.$row2['type'].': '.$row2['name'].' ('.$row2['team_name'].')</td></tr>';
        }
        $html .= '</tbody></table>';
        $this->ss->assign('SCHEMA_TABLE', $html);
        $this->ss->assign('BOOK', $book);
        $this->ss->assign('PA', $pa);
        $this->ss->assign('BO', $bo);
        $this->ss->assign('DIH', $dih);

        parent::display();
    }
}

// Generate Add row template Gift
function getHtmlAddRow( $id, $data, $showing){
    if($showing)
        $display = 'style="display:none;"';
    $tpl_addrow  = "<tr class='row_tpl' $display  >";
    $tpl_addrow .= '<td nowrap align="center">
    <div><input name="book_name[]" value="'.$data['book_name'].'" class="book_name" type="text" style="margin-right: 10px;">
    <input name="book_id[]" value="'.$id.'"  class="book_id" type="hidden"><button type="button" class="btn_choose_book" onclick="clickChooseBook($(this))" ><img src="themes/default/images/id-ff-select.png"></button>
    <input name="qty_book[]" value="'.$data['qty_book'].'" class="qty_book" type="text" style="width:50px; font-weight: bold; color: rgb(165, 42, 42); text-align: right;">
    </div>
    </td>';
    $tpl_addrow .= "<td align='center'><button type='button' class='btn btn-danger btnRemove'>Remove</button></td>";
    $tpl_addrow .= '</tr>';
    return $tpl_addrow;
}
// Generate Add row template Partnership
function getHtmlAddRowPa( $pa_id, $pa_name, $showing){
    if($showing)
        $display = 'style="display:none;"';
    $tpl_addrow  = "<tr class='row' $display  >";
    $tpl_addrow .= '<td nowrap align="center">
    <div><input name="pa_name[]" value="'.$pa_name.'" class="pa_name" type="text" style="margin-right: 10px;">
    <input name="pa_id[]" value="'.$pa_id.'"  class="pa_id" type="hidden"><button type="button" class="btn_choose_pa" onclick="clickChoosePa($(this))" ><img src="themes/default/images/id-ff-select.png"></button>
    </div>
    </td>';
    $tpl_addrow .= "<td align='center'><button type='button' class='btn btn-danger btn_Remove'>Remove</button></td>";
    $tpl_addrow .= '</tr>';
    return $tpl_addrow;
}
function getHtmlAddRowHour( $hour, $promtion_hour, $showing){
    if($showing)
        $display = 'style="display:none;"';
    $tpl_addrow  = "<tr class='row' $display >";
    $tpl_addrow .= '<td nowrap align="center"><div><input tabindex="0" autocomplete="off" type="text" name="hours[]" class="hours" value="'.$hour.'" size="4" maxlength="10" style="text-align: center;"></div></td>';
    $tpl_addrow .= '<td nowrap align="center"><div><input tabindex="0" autocomplete="off" type="text" name="promotion_hours[]" class="promotion_hours" value="'.$promtion_hour.'" size="4" maxlength="10" style="text-align: center;color: rgb(165, 42, 42);"></div></td>';
    $tpl_addrow .= "<td align='center'><button type='button' class='btnRemoveHour' style='display: inline-block;'><img src='themes/default/images/id-ff-remove-nobg.png' alt='Remove'></button></td>";
    $tpl_addrow .= '</tr>';
    return $tpl_addrow;
}
function getHtmlAddRange( $start_hour, $to_hour, $block, $showing){
    if($showing)
        $display = 'style="display:none;"';
    $tpl_addrow  = "<tr class='row' $display >";
    $tpl_addrow .= '<td nowrap align="center"><p><input class="start_hour" name="start_hour[]" value="'.$start_hour.'" type="text" style="width: 40px; text-align: center;" autocomplete="off"> - <input class="to_hour" name="to_hour[]" value="'.$to_hour.'" type="text" style="width: 40px; text-align: center;" autocomplete="off"></p></td>';
    $tpl_addrow .= '<td nowrap align="center"><div><input tabindex="0" autocomplete="off" type="text" name="block[]" class="block" value="'.$block.'" size="4" maxlength="10" style="text-align: center;color: rgb(165, 42, 42);"></div></td>';
    $tpl_addrow .= "<td align='center'><button type='button' class='btnRemoveRange' style='display: inline-block;'><img src='themes/default/images/id-ff-remove-nobg.png' alt='Remove'></button></td>";
    $tpl_addrow .= '</tr>';
    return $tpl_addrow;
}