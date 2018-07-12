<?php
$get_class = "SELECT 
c.id,
c.name,
c.team_id,
c.koc_id,
c.level,
c.assigned_user_id
FROM
j_class c
INNER JOIN
teams t ON t.id = c.team_id
AND t.team_type = 'Adult'
AND c.deleted = 0
LEFT JOIN
(SELECT 
cg.j_class_j_gradebook_1j_class_ida class_id,
cg.j_class_j_gradebook_1j_gradebook_idb gradebook_id
FROM
j_class_j_gradebook_1_c cg
INNER JOIN j_gradebook g ON cg.j_class_j_gradebook_1j_gradebook_idb = g.id
AND g.type = 'LMS'
AND cg.deleted = 0
AND g.deleted = 0) cg2 ON cg2.class_id = c.id
LEFT JOIN
j_gradebook g2 ON g2.id = cg2.gradebook_id
AND g2.type = 'LMS'
WHERE
c.class_type_adult = 'Practice'
AND IFNULL(g2.id, '') = ''
AND c.level IN ('Beginner' , 'Elementary',
'Pre Inter',
'Inter',
'Upper Inter')";
$class_rs = $GLOBALS['db']->query($get_class);
while($row = $GLOBALS['db']->fetchByAssoc($class_rs)){
    $grade          = new J_Gradebook();
    //    $grade->id                                  = create_guid();
    $grade->type                                = "LMS";
    $grade->j_class_j_gradebook_1_name          = $row['name'];
    $grade->j_class_j_gradebook_1j_class_ida    = $row['id'];
    $grade->status                              = 'Approved';
    $grade->date_input                          = $GLOBALS['timedate']->nowDbDate();
    $grade->team_id                             = $row['team_id'];
    $grade->team_set_id                         = $row['team_id'];


    $gd_config = new J_GradebookConfig();
    $gd_config->retrieve_by_string_fields(
        array(
            'team_id' => $row['team_id'],
            'koc_id' => $row['koc_id'],
            'level' => $row['level'],
            'type' => $grade->type,
        )
    );
    $grade->grade_config    =  html_entity_decode($gd_config->content);
    $grade->weight          =  $gd_config->weight;

    $grade->assigned_user_id = $row['assigned_user_id'];
    $grade->save();

    $sql_contact_class = "SELECT 
    j_class_contacts_1contacts_idb contact_id
    FROM
    j_class_contacts_1_c
    WHERE
    j_class_contacts_1j_class_ida = '{$row['id']}'
    AND deleted = 0";
    $contact_class_rs = $GLOBALS['db']->query($sql_contact_class);
    while($row_contact = $GLOBALS['db']->fetchByAssoc($contact_class_rs)){
        $gbd                = new J_GradebookDetail();
        $gbd->team_id       = $row['team_id'];
        $gbd->team_set_id   = $row['team_id'];

        $gbd->student_id    = $row_contact['contact_id'];
        $gbd->gradebook_id  = $grade->id;
        $gbd->j_class_id    = $row['id'];
        $gbd->date_input    = $timedate->nowDbDate();
        $gbd->total_mark    = 0;
        $gbd->final_result  = 0;
        $gbd->content       = "{\"comment_key\":null,\"comment_label\":\"\"}";
        $gbd->save();    
    }   
}   
?>
