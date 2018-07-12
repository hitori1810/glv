<?php

switch ($_POST['type']) {
    case 'loadConfig':
        $result = loadConfig();
        echo $result;
        break;
    case 'saveConfig':
        $result = saveConfig();
        echo $result;
        break;
}

// ----------------------------------------------------------------------------------------------------------\\

function loadConfig(){
    global $timedate, $current_user;
    if(empty($_POST['tg_center']) || empty($_POST['tg_type']) || empty($_POST['tg_year']) || empty($_POST['tg_frequency']) || empty($_POST['tg_unit_from']) || empty($_POST['tg_unit_to']))
    return json_encode(array("success" => "0"));

    $html = "<table width='100%' class='table table-striped table-bordered dataTable' id='celebs'><thead><tr>";
    $html .= '<th width="1%" style="text-align: center;vertical-align: text-top;">No.</th>';
    $html .= '<th width="10%" style="text-align: center;vertical-align: text-top;">Center Name</th>';
    $html .= '<th width="5%" style="text-align: center;vertical-align: text-top;">Center Code</th>';
    switch ($_POST['tg_frequency']) {
        case "Weekly":
            for($i = $_POST['tg_unit_from']; $i <= $_POST['tg_unit_to']; $i++){
                $parts = getStartEndFromWeek($i,$_POST['tg_year']);
                $html .= '<td width="3%"><b>W '.$i.'</b><br>'.$parts['week_start'].' - '.$parts['week_end'].'</td>';
            }
            break;
        case "Monthly":
            for($i = $_POST['tg_unit_from']; $i <= $_POST['tg_unit_to']; $i++){
                $dt = DateTime::createFromFormat('!m', $i);
                $html .= '<th width="3%" style="text-align: center;vertical-align: text-top;">'.$dt->format('F').'</th>';
            }
            break;
        case "Quarterly":
            for($i = $_POST['tg_unit_from']; $i <= $_POST['tg_unit_to']; $i++){
                $parts = getStartEndFromQuarter($i,$_POST['tg_year']);
                $html .= '<td width="3%" style="text-align: center;vertical-align: text-top;"><b>Q '.$i.'</b><br>'.$parts['quarter_start'].' - '.$parts['quarter_end'].'</td>';
            }
            break;
        case "Yearly":
            $html .= '<th width="3%" style="text-align: center;vertical-align: text-top;">Year '.$_POST['tg_year'].'</th>';
            break;
    }
    $html .= '</tr></thead>';
    $html .= '<tbody>';
    $teams = explode(",", $_POST['tg_center']);
    $q1    = "SELECT DISTINCT
    IFNULL(teams.id, '') primaryid,
    IFNULL(teams.name, '') name,
    IFNULL(teams.short_name, '') short_name,
    IFNULL(teams.team_type, '') team_type,
    IFNULL(teams.region, '') region,
    IFNULL(teams.code_prefix, '') code_prefix
    FROM
    teams
    WHERE
    (((teams.id IN ("."'".implode("','",$teams)."'"."))))
    AND teams.deleted = 0
    ORDER BY team_type DESC, region ASC, code_prefix";
    $rs1 = $GLOBALS['db']->query($q1);
    $data = getDataConfig($teams, $_POST['tg_type'], $_POST['tg_year'], $_POST['tg_frequency'], $_POST['tg_unit_from'], $_POST['tg_unit_to']);
    $count = 1;
    while($row = $GLOBALS['db']->fetchbyAssoc($rs1)){
        $html .= "<tr>
        <td>$count</td>
        <td>{$row['name']}<input type='hidden' class='tg_team_id' value='{$row['primaryid']}'></td>
        <td>{$row['code_prefix']}</td>";
        switch ($_POST['tg_frequency']) {
            case "Weekly":
                for($i = $_POST['tg_unit_from']; $i <= $_POST['tg_unit_to']; $i++){
                    $value = $data[$row['primaryid']][$_POST['tg_type']][$_POST['tg_year']][$_POST['tg_frequency']][$i];
                    if(empty($value)) $value = '';
                    $html .= '<td><input time_unit="'.$i.'" id="" value="'.$value.'" class="'.$_POST['tg_frequency'].' configVal currency" style="text-align:center;" size="10" type="text"></td>';
                }
                break;
            case "Monthly":
                for($i = $_POST['tg_unit_from']; $i <= $_POST['tg_unit_to']; $i++){
                    $value = $data[$row['primaryid']][$_POST['tg_type']][$_POST['tg_year']][$_POST['tg_frequency']][$i];
                    if(empty($value)) $value = '';
                    $html .= '<td><input time_unit="'.$i.'" id="" value="'.$value.'" class="'.$_POST['tg_frequency'].' configVal currency" style="text-align:center;" size="10" type="text"></td>';
                }
                break;
            case "Quarterly":
                for($i = $_POST['tg_unit_from']; $i <= $_POST['tg_unit_to']; $i++){
                    $value = $data[$row['primaryid']][$_POST['tg_type']][$_POST['tg_year']][$_POST['tg_frequency']][$i];
                    if(empty($value)) $value = '';
                    $html .= '<td><input time_unit="'.$i.'" id="" value="'.$value.'" class="'.$_POST['tg_frequency'].' configVal currency" style="text-align:center;" size="10" type="text"></td>';
                }
                break;
            case "Yearly":
                $value = $data[$row['primaryid']][$_POST['tg_type']][$_POST['tg_year']][$_POST['tg_frequency']][$_POST['tg_unit_to']];
                if(empty($value)) $value = '';
                $html .= '<td><input time_unit="'.$_POST['tg_year'].'" id="" value="'.$value.'" class="'.$_POST['tg_frequency'].' configVal currency" style="text-align:center;" size="10" type="text"></td>';
                break;
        }
        $html .= '</tr>';
        $count++;
    }
    $html .= '</tbody></table>
    <input class="button primary" type="button" name="tg_saveconfig" value="Save Config" id="tg_saveconfig" style="padding: 6px 10px 6px 10px; margin:15px;">
    <input class="button" type="button" name="tg_clearconfig" value="Clear" id="tg_clearconfig" style="padding: 6px 10px 6px 10px; margin:15px;">
    ';
    $js   .= "
    <script>
    $(document).ready(function() {
    var table = $('#celebs');
    var oTable = table.dataTable({ 'fnFooterCallback': function( nFoot, aData, iStart, iEnd, aiDisplay ) { }, bStateSave: true, paging: false, aoColumnDefs: [{ bSortable: false, aTargets: [ -1 ]}], oLanguage:{
    sLengthMenu: '".$GLOBALS['app_strings']['LBL_JDATATABLE1']."_MENU_".$GLOBALS['app_strings']['LBL_JDATATABLE2']."',
    sZeroRecords: '".$GLOBALS['app_strings']['LBL_JDATATABLE13']."',
    sInfo: '".$GLOBALS['app_strings']['LBL_JDATATABLE6']."_START_".$GLOBALS['app_strings']['LBL_JDATATABLE7']."_END_".$GLOBALS['app_strings']['LBL_JDATATABLE8']."_TOTAL_".$GLOBALS['app_strings']['LBL_JDATATABLE2']."',
    sInfoEmpty: '".$GLOBALS['app_strings']['LBL_JDATATABLE15']."',
    sEmptyTable: '".$GLOBALS['app_strings']['LBL_JDATATABLE14']."',
    sInfoFiltered: '',
    oPaginate: {
    sFirst: '".$GLOBALS['app_strings']['LBL_JDATATABLE9']."',
    sNext: '".$GLOBALS['app_strings']['LBL_JDATATABLE10']."',
    sPrevious: '".$GLOBALS['app_strings']['LBL_JDATATABLE11']."',
    sLast: '".$GLOBALS['app_strings']['LBL_JDATATABLE12']."',
    },
    iTabIndex: 1,
    sSearch: '".$GLOBALS['app_strings']['LBL_JDATATABLE3']."'
    },ordering: false });
    });
    </script>";
    return json_encode(array(
        "success" => "1",
        "html" => $html.$js,
    ));
}

function saveConfig(){
    global $timedate, $current_user;
    $teams = explode(",", $_POST['tg_center']);
    $data   = getDataConfig($teams, $_POST['tg_type'], $_POST['tg_year'], $_POST['tg_frequency'], $_POST['tg_unit_from'], $_POST['tg_unit_to'], 'roaming');
    $team_objs = json_decode(html_entity_decode($_POST['configVal']),true);
    if(!empty($data)){
        $ss_rmv = "'".implode("','", array_column($data,'primaryid'))."'";
        $GLOBALS['db']->query("DELETE FROM j_targetconfig WHERE id IN ($ss_rmv)");
    }

    if(empty($team_objs))
        return json_encode(array("success" => "0"));

    foreach($team_objs as $team_id => $team_obj){
        foreach($team_obj as $time_unit => $value){
            if(!empty($value)){
                $tg                 = new J_Targetconfig();
                $tg->type           = $_POST['tg_type'];
                $tg->year           = $_POST['tg_year'];
                $tg->frequency      = $_POST['tg_frequency'];
                $tg->time_unit      = $time_unit;
                $tg->value          = $value;
                $tg->team_id        = $team_id;
                $tg->team_set_id    = $tg->team_id;
                $team_code = $GLOBALS['db']->getOne("SELECT code_prefix FROM teams WHERE id = '{$tg->team_id}' AND deleted = 0");
                $tg->name           = $team_code.': '.$_POST['tg_type'].' - '.$_POST['tg_year'];
                $tg->save();
            }
        }
    }
    return json_encode(array("success" => "1"));
}

function getStartEndFromWeek($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('j/n');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('j/n');
    return $ret;
}
function getStartEndFromQuarter($quaty, $year) {
    $ret['quarter_start'] =  date('j/n', strtotime(date($year) . '-' . (($quaty * 3) - 2) . '-1'));
    $ret['quarter_end'] = date('t/n', strtotime(date($year) . '-' . (($quaty * 3)) . '-1'));
    return $ret;
}

function getDataConfig( $teams, $type, $year, $frequency, $unit_from, $unit_to, $return_type = 'checking'){
    $q2 = "SELECT DISTINCT
    IFNULL(j_targetconfig.id, '') primaryid,
    IFNULL(l1.id, '') team_id,
    IFNULL(j_targetconfig.name, '') name,
    IFNULL(j_targetconfig.frequency, '') frequency,
    IFNULL(j_targetconfig.time_unit, '') time_unit,
    IFNULL(j_targetconfig.type, '') type,
    IFNULL(j_targetconfig.value, '') value,
    IFNULL(j_targetconfig.year, '') year
    FROM
    j_targetconfig
    INNER JOIN
    teams l1 ON j_targetconfig.team_id = l1.id
    AND l1.deleted = 0
    WHERE
    (((j_targetconfig.year = '$year')
    AND (j_targetconfig.type = '$type')
    AND (j_targetconfig.frequency = '$frequency')
    AND ((j_targetconfig.time_unit >= $unit_from) AND (j_targetconfig.time_unit <= $unit_to))
    AND (l1.id IN ("."'".implode("','",$teams)."'"."))))
    AND j_targetconfig.deleted = 0
    ORDER BY team_id";

    if($return_type == 'checking'){
        $rs2 = $GLOBALS['db']->query($q2);
        $data = array();
        while($row = $GLOBALS['db']->fetchbyAssoc($rs2)){
            $data[$row['team_id']][$type][$year][$frequency][$row['time_unit']] = format_number($row['value'],0,0);
        }
    }else
        $data = $GLOBALS['db']->fetchArray($q2);

    return $data;
}

?>
