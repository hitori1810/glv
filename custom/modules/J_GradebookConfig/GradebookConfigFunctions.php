<?php

function getKOCOfCenter($center_id, $koc_id = '')
{
    if (empty($center_id)) return '<option json="[{&quot;levels&quot;:&quot;-none-&quot;}]" value = "" >-none-</option>';
    $q1 = "SELECT DISTINCT
    IFNULL(j_kindofcourse.id, '') primaryid,
    IFNULL(j_kindofcourse.name, '') name,
    IFNULL(j_kindofcourse.kind_of_course, j_kindofcourse.kind_of_course_adult) kind_of_course,
    IFNULL(j_kindofcourse.content, '') content,
    IFNULL(l1.id, '') team_id,
    IFNULL(l1.name, '') team_name,
    j_kindofcourse.date_entered j_kindofcourse_date_entered
    FROM
    j_kindofcourse
    INNER JOIN
    teams l1 ON j_kindofcourse.team_id = l1.id
    AND j_kindofcourse.status = 'Active'
    AND l1.deleted = 0
    WHERE
    ((j_kindofcourse.team_set_id IN (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.team_id = '{$center_id}'
    AND team_memberships.deleted = 0)))
    AND j_kindofcourse.deleted = 0
    ORDER BY CASE
    WHEN (j_kindofcourse.kind_of_course = '' OR j_kindofcourse.kind_of_course IS NULL)
    THEN
    0
    WHEN j_kindofcourse.kind_of_course = 'Academic' THEN 1
    WHEN j_kindofcourse.kind_of_course = 'TOEIC' THEN 2
    WHEN j_kindofcourse.kind_of_course = 'IETLS' THEN 3
    WHEN j_kindofcourse.kind_of_course = 'CERF' THEN 4
    ELSE 15
    END ASC";
    $rs1 = $GLOBALS['db']->query($q1);
    //Generate html option
    $htm_koc .= '<option value = "" >-none-</option>';
    while ($row = $GLOBALS['db']->fetchByAssoc($rs1)) {
        if ($koc_id == $row['primaryid']) $str_selected = 'selected';
        else $str_selected = '';
        $htm_koc .= '<option ' . $str_selected . ' json="' . $row['content'] . '" value="' . $row['primaryid'] . '">' . $row['name'] . '</option>';
    }
    return $htm_koc;
}

function saveConfig($_data){
    $thisConfig = new J_GradebookConfig();
    $thisConfig->retrieve_by_string_fields(
        array(
            'team_id'   => $_data['team_id'],
            'koc_id'    => $_data['koc_id'],
            'type'      => $_data['type'],
            'minitest'  => $_data['minitest'],
        )
    );
    if (!$thisConfig->id) {
        $thisConfig->team_id    = $_data['team_id'];
        $thisConfig->koc_id     = $_data['koc_id'];
        $thisConfig->type       = $_data['type'];
        $thisConfig->minitest   = $_data['minitest'];
    }
    $config_name = 'gradebook_config_'.$_data['type'];
    //Customize minitest
    if(!empty($_data['minitest'])){
        $minitest = preg_replace('/[0-9]+/', '', $_data['minitest']);
        $config_name = 'gradebook_config_'.$minitest;
    }

    $configs = $thisConfig->$config_name;

    $thisConfig->name   = $_data['gradebook_name'];
    $thisConfig->weight = $_data['weight'];

    $contents = array();
    foreach ($configs as $key => $parrams) {
        $_name = $parrams['name'] . "_";
        $contents[$key] = array(
            'name'      => (!empty($_data[$_name . 'name'])) ? $_data[$_name . 'name'] : $parrams['name'],
            'alias'     => (!empty($_data[$_name . 'alias'])) ? $_data[$_name . 'alias'] : $parrams['alias'],
            'label'     => (!empty($_data[$_name . 'label'])) ? $_data[$_name . 'label'] : $parrams['label'],
            'group'     => (!empty($_data[$_name . 'group'])) ? $_data[$_name . 'group'] : '',
            'type'      => $parrams['type'],

            'visible'   => ($_data[$_name . 'visible']) ? 1 : 0,
            'max_mark'  => ($_data[$_name . 'visible']) ? (unformat_number($_data[$_name . 'max_mark'])) : 0,
            'weight'    => ($_data[$_name . 'visible']) ? (unformat_number($_data[$_name . 'weight'])): 0,
            'comment_list'=> ($_data[$_name . 'visible']) ? $_data[$_name . 'comment_list'] : '',
            'readonly'  => ($_data[$_name . 'visible']) ? ($_data[$_name . 'readonly'] ? 1 : 0) : 0,
            'formula'   => strtoupper(($_data[$_name . 'readonly']) ? $_data[$_name . 'formula'] : ""),
            'custom_btn_label'   => (!empty($parrams['custom_btn_label'])) ? $parrams['custom_btn_label'] : '',
            'custom_btn_function'   => (!empty($parrams['custom_btn_function'])) ? $parrams['custom_btn_function'] : '',
        );
    }

    $thisConfig->content = json_encode($contents);
    return $thisConfig->save();
}

function getConfigContent($_data){
    $thisConfig = new J_GradebookConfig();
    $thisConfig->retrieve_by_string_fields(
        array(
            'team_id'   => $_data['center_id'],
            'koc_id'    => $_data['koc_id'],
            'type'      => $_data['config_type'],
            'minitest'  => $_data['minitest'],
        )
    );
    return json_encode(array(
        'record' => $thisConfig->id,
        'weight' => $thisConfig->weight,
        'name'   => $thisConfig->name,
        'html'   => $thisConfig->loadConfigContent($_data['koc_id'], $_data['config_type'], $_data['minitest']),
    ));
}

function getTypeOfKOC($_data){
    if(empty($_data['koc_id']))
        $option = array('' => '-none-');
    else
        $option = $GLOBALS['app_list_strings']['gradeconfig_type_options'];

    return json_encode(array(
        'html' => get_select_options_with_id($option, ''),
    ));
}

?>
