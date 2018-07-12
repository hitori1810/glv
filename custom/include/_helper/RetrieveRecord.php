<?php
function retrieve($classroom_id, $auth_key, $param){
    set_time_limit(500);
    $page = 1;
    $count_error = 0;
    $insert_lesson = null;
    $insert_lesson_temp = null;
    $sql_student = "SELECT 
    als.*
    FROM
    alpha_students als
    INNER JOIN
    alpha_classroom alc ON als.classroom_id = alc.id
    AND alc.id = $classroom_id
    AND als.end_date > CONVERT( DATE_ADD(alc.time_retrieve,
    INTERVAL - 7 DAY) , DATE)";
    $rs_student = $GLOBALS['db']->query($sql_student);
    $arr_std = null;
    while($row = $GLOBALS['db']->fetchByAssoc($rs_student)){
        $arr_std[$row['login']]['alpha_student_id']     = $row['alpha_student_id'];
        $arr_std[$row['login']]['email']                = $row['email'];
        $arr_std[$row['login']]['first_name']           = $row['first_name'];
        $arr_std[$row['login']]['last_name']            = $row['last_name'];          
        $arr_std[$row['login']]['alpha_classroom_id']   = $row['alpha_classroom_id'];          
    }
    
    $sql_lesson =  "SELECT 
    al.*
    FROM
    alpha_lessons al
    INNER JOIN
    alpha_students als ON als.alpha_student_id = al.alpha_student_id
    INNER JOIN alpha_classroom alc on alc.id = als.classroom_id
    AND als.classroom_id = $classroom_id
    AND als.end_date > CONVERT( DATE_ADD(alc.time_retrieve,
    INTERVAL - 7 DAY) , DATE)";

    $rs_lesson = $GLOBALS['db']->query($sql_lesson);
    $arr_lesson = null;    
    while($row = $GLOBALS['db']->fetchByAssoc($rs_lesson)){
        $arr_lesson[$row['id']]['skill'] = $row['skill'];    
        $arr_lesson[$row['id']]['session_id'] = $row['session_id'];    
        $arr_lesson[$row['id']]['graded'] = $row['graded'];    
        $arr_lesson[$row['id']]['time'] = $row['time'];    
        $arr_lesson[$row['id']]['passed'] = $row['passed'];    
        $arr_lesson[$row['id']]['unit_type'] = $row['unit_type'];    
        $arr_lesson[$row['id']]['grade'] = $row['grade'];    
        $arr_lesson[$row['id']]['title'] = html_entity_decode_utf8($row['title']);    
        $arr_lesson[$row['id']]['created_at'] = $row['created_at'];    
        $arr_lesson[$row['id']]['unit_id'] = $row['unit_id'];    
        $arr_lesson[$row['id']]['updated_at'] = $row['updated_at'];    
        $arr_lesson[$row['id']]['score'] = $row['score'];    
        $arr_lesson[$row['id']]['level'] = $row['level'];    
        $arr_lesson[$row['id']]['submitted'] = $row['submitted'];    
        $arr_lesson[$row['id']]['passed_in_course'] = $row['passed_in_course'];    
        $arr_lesson[$row['id']]['status'] = $row['status'];    
        $arr_lesson[$row['id']]['title_local'] = html_entity_decode_utf8($row['title_local']);    
        $arr_lesson[$row['id']]['alpha_lesson_id'] = $row['alpha_lesson_id'];     
        $arr_lesson[$row['id']]['alpha_course_id'] = $row['alpha_course_id'];     
        $arr_lesson[$row['id']]['alpha_student_id'] = $row['alpha_student_id'];    
        $arr_lesson[$row['id']]['alpha_classroom_id'] = $row['alpha_classroom_id'];    
    }

    $sql_course = "SELECT 
    ac.*
    FROM
    alpha_courses ac
    INNER JOIN
    alpha_students als ON als.alpha_student_id = ac.alpha_student_id
    INNER JOIN alpha_classroom alc on alc.id = als.classroom_id
    AND als.classroom_id =  $classroom_id
    AND als.end_date > CONVERT( DATE_ADD(alc.time_retrieve,
    INTERVAL - 7 DAY) , DATE)";     
    $rs_course = $GLOBALS['db']->query($sql_course);
    $arr_course = null;
    while($row = $GLOBALS['db']->fetchByAssoc($rs_course)){
        $arr_course[$row['id']]['course_name'] = $row['course_name'];
        $arr_course[$row['id']]['updated_at'] = $row['updated_at'];
        $arr_course[$row['id']]['access_end_date'] = $row['access_end_date'];
        $arr_course[$row['id']]['created_at'] = $row['created_at'];
        $arr_course[$row['id']]['end_date'] = $row['end_date'];
        $arr_course[$row['id']]['course_session_id'] = $row['course_session_id'];
        $arr_course[$row['id']]['payment_status'] = $row['payment_status'];
        $arr_course[$row['id']]['classroom_id'] = $row['classroom_id'];
        $arr_course[$row['id']]['member_id'] = $row['member_id'];
        $arr_course[$row['id']]['linking'] = $row['linking'];
        $arr_course[$row['id']]['course_type'] = $row['course_type'];
        $arr_course[$row['id']]['access_start_date'] = $row['access_start_date'];
        $arr_course[$row['id']]['start_date'] = $row['start_date'];
        $arr_course[$row['id']]['parent_id'] = $row['parent_id'];
        $arr_course[$row['id']]['registration_item_id'] = $row['registration_item_id'];
        $arr_course[$row['id']]['alpha_course_id'] = $row['alpha_course_id'];
        $arr_course[$row['id']]['alpha_student_id'] = $row['alpha_student_id'];
        $arr_course[$row['id']]['alpha_classroom_id'] = $row['alpha_classroom_id'];
    } 
    while($page){
        $url = "https://re.reallyenglish.com/api/v1/organizations/2509/classrooms/".$classroom_id.".json?page=".$page;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);   
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);           
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $param);
        $result = curl_exec($ch);             
        curl_close($ch); 
        if(empty($result)){
            if(++$count_error > 10) 
                return array(
                    'success' => '0',
                    'classroom_id' => $classroom_id,
                    'notify'  => "Please check SSL");    
        }else{   
            $array = json_decode($result, true); 
            foreach($array['students'] as $ks=>$vs){
                if(array_key_exists($vs['login'],$arr_std)){
                    foreach($vs['courses'] as $kc=>$vc){
                        $course_id = create_guid();
                        $updated_at =  date('Y-m-d H:i:s', strtotime($vc['updated_at']));
                        $access_end_date = date('Y-m-d H:i:s', strtotime($vc['access_end_date']));
                        $created_at = date('Y-m-d H:i:s', strtotime($vc['created_at']));
                        $end_date = date('Y-m-d H:i:s', strtotime($vc['end_date']));
                        $access_start_date = date('Y-m-d H:i:s', strtotime($vc['access_start_date']));
                        $start_date = date('Y-m-d H:i:s', strtotime($vc['start_date']));
                        if(array_key_exists($vc['id'], $arr_course)){                              
                            $course_id = $arr_course[$vc['id']]['alpha_course_id'];
                            if(($vc['course_name'] != $arr_course[$vc['id']]['course_name']) ||
                            ($updated_at != $arr_course[$vc['id']]['updated_at'])  ||
                            $access_end_date != $arr_course[$vc['id']]['access_end_date']   ||
                            $created_at != $arr_course[$vc['id']]['created_at']      ||
                            $end_date != $arr_course[$vc['id']]['end_date']     ||
                            $vc['course_session_id'] != $arr_course[$vc['id']]['course_session_id']  ||
                            $vc['payment_status'] != $arr_course[$vc['id']]['payment_status']   ||
                            $vc['classroom_id'] != $arr_course[$vc['id']]['classroom_id']   ||
                            $vc['member_id'] != $arr_course[$vc['id']]['member_id']   ||
                            $vc['linking'] != $arr_course[$vc['id']]['linking']   ||
                            $vc['course_type'] != $arr_course[$vc['id']]['course_type']   ||
                            $access_start_date != $arr_course[$vc['id']]['access_start_date']  ||
                            $start_date != $arr_course[$vc['id']]['start_date']    ||
                            $vc['parent_id'] != $arr_course[$vc['id']]['parent_id']  ||
                            $vc['registration_item_id'] != $arr_course[$vc['id']]['registration_item_id']) {
                                $ext_parent_id = 'parent_id = null,';
                                if(!empty($vc['parent_id']))
                                    $ext_parent_id = 'parent_id = "'.$vc['parent_id'].'",';
                                $update_course = 'UPDATE alpha_courses SET 
                                course_name = "'.$vc['course_name'].'",
                                updated_at = "'.$updated_at.'",
                                access_end_date = "'.$access_end_date.'",
                                created_at = "'.$created_at.'",
                                end_date = "'.$end_date.'",
                                course_session_id = "'.$vc['course_session_id'].'",
                                payment_status = "'.$vc['payment_status'].'",
                                classroom_id = "'.$vc['classroom_id'].'",
                                member_id = "'.$vc['member_id'].'",
                                linking = "'.$vc['linking'].'",
                                course_type = "'.$vc['course_type'].'",
                                access_start_date = "'.$access_start_date.'",
                                start_date = "'.$start_date.'",'.$ext_parent_id.'      
                                registration_item_id = "'.$vc['registration_item_id'].'"
                                WHERE id = '.$vc['id'];
                                $rs = $GLOBALS['db']->query($update_course);   
                            }

                        }else{
                            $parent_id = empty($vc['parent_id'])?"NULL":"'{$vc['parent_id']}'";
                            $insert = 'INSERT INTO `alpha_courses`
                            (`alpha_course_id`,
                            `course_name`,
                            `updated_at`,
                            `access_end_date`,
                            `created_at`,
                            `end_date`,
                            `course_session_id`,
                            `id`,
                            `payment_status`,
                            `classroom_id`,
                            `member_id`,
                            `linking`,
                            `course_type`,
                            `access_start_date`,
                            `start_date`,
                            `parent_id`,
                            `registration_item_id`,
                            `alpha_student_id`,
                            `alpha_classroom_id`,
                            `alpha_delete`)
                            VALUES ("'.$course_id.'",
                            "'.$vc['course_name'].'",
                            "'.$updated_at.'",
                            "'.$access_end_date.'",
                            "'.$created_at.'",
                            "'.$end_date.'",
                            "'.$vc['course_session_id'].'",
                            "'.$vc['id'].'",
                            "'.$vc['payment_status'].'",
                            "'.$vc['classroom_id'].'",
                            "'.$vc['member_id'].'",
                            "'.$vc['linking'].'",
                            "'.$vc['course_type'].'",
                            "'.$access_start_date.'",
                            "'.$start_date.'",
                            '.$parent_id.',
                            "'.$vc['registration_item_id'].'",
                            "'.$arr_std[$vs['login']]['alpha_student_id'].'",
                            "'.$arr_std[$vs['login']]['alpha_classroom_id'].'",0)';                           
                            $rs = $GLOBALS['db']->query($insert);   
                        }

                        foreach($vc['lessons'] as $kl=>$vl){
                            $updated_at =  date('Y-m-d H:i:s', strtotime($vl['updated_at']));
                            $submitted = date('Y-m-d H:i:s', strtotime($vl['submitted']));
                            $created_at = date('Y-m-d H:i:s', strtotime($vl['created_at']));                             
                            if(!array_key_exists($vl['id'], $arr_lesson)){
                                $lesson_id = create_guid();
                                $graded = empty($vl['graded'])?"NULL":"'{$vl['graded']}'";
                                $insert_lesson[] = '(
                                '.$graded.',
                                "'.$vl['score'].'",
                                "'.$vl['skill'].'",
                                "'.$vl['passed'].'",
                                "'.$vl['status'].'",
                                "'.$vl['session_id'].'",
                                "'.$vl['unit_type'].'",
                                "'.$vl['id'].'",
                                "'.$vl['level'].'",
                                "'.$created_at.'",
                                "'.$vl['time'].'",
                                "'.$vl['grade'].'",
                                "'.$vl['unit_id'].'",
                                "'.$updated_at.'",
                                "'.$submitted.'",
                                "'.$vl['title'].'",
                                "'.$vl['title_local'].'",
                                "'.$vl['passed_in_course'].'",
                                "'.$lesson_id.'",
                                "'.$course_id.'",
                                "'.$arr_std[$vs['login']]['alpha_student_id'].'",
                                "'.$arr_std[$vs['login']]['alpha_classroom_id'].'",
                                0)';                                 
                            } else  {
                                if( ($vl['graded'] != $arr_lesson[$vl['id']]['graded']) ||   
                                $vl['score'] != $arr_lesson[$vl['id']]['score']   || 
                                $vl['skill'] != $arr_lesson[$vl['id']]['skill'] || 
                                $vl['passed'] != $arr_lesson[$vl['id']]['passed']  ||   
                                $vl['status'] != $arr_lesson[$vl['id']]['status']  ||  
                                $vl['session_id'] != $arr_lesson[$vl['id']]['session_id']  ||  
                                $vl['unit_type'] != $arr_lesson[$vl['id']]['unit_type'] ||   
                                $vl['level'] != $arr_lesson[$vl['id']]['level']    ||
                                $created_at != $arr_lesson[$vl['id']]['created_at'] ||   
                                $vl['time'] != $arr_lesson[$vl['id']]['time']   || 
                                $vl['grade'] != $arr_lesson[$vl['id']]['grade']  ||  
                                $vl['unit_id'] != $arr_lesson[$vl['id']]['unit_id'] ||   
                                $updated_at != $arr_lesson[$vl['id']]['updated_at']  ||  
                                $submitted != $arr_lesson[$vl['id']]['submitted']    ||
                                $vl['title'] != $arr_lesson[$vl['id']]['title']   || 
                                $vl['title_local'] != $arr_lesson[$vl['id']]['title_local']||    
                                $vl['passed_in_course'] != $arr_lesson[$vl['id']]['passed_in_course'] ||   
                                $course_id != $arr_lesson[$vl['id']]['alpha_course_id'] ||   
                                $arr_std[$vs['login']]['alpha_student_id'] != $arr_lesson[$vl['id']]['alpha_student_id'] ||   
                                $arr_std[$vs['login']]['alpha_classroom_id'] != $arr_lesson[$vl['id']]['alpha_classroom_id']  ) {
                                    $graded = empty($vl['graded'])?"NULL":"'{$vl['graded']}'";
                                    $insert_lesson_temp[] = '('.$graded.',
                                    "'.$vl['score'].'",
                                    "'.$vl['skill'].'",
                                    "'.$vl['passed'].'",
                                    "'.$vl['status'].'",
                                    "'.$vl['session_id'].'",
                                    "'.$vl['unit_type'].'",
                                    "'.$vl['id'].'",
                                    "'.$vl['level'].'",
                                    "'.$created_at.'",
                                    "'.$vl['time'].'",
                                    "'.$vl['grade'].'",
                                    "'.$vl['unit_id'].'",
                                    "'.$updated_at.'",
                                    "'.$submitted.'",
                                    "'.$vl['title'].'",
                                    "'.$vl['title_local'].'",
                                    "'.$vl['passed_in_course'].'",
                                    "'.$arr_lesson[$vl['id']]['alpha_lesson_id'].'",
                                    "'.$course_id.'",
                                    "'.$arr_std[$vs['login']]['alpha_student_id'].'",
                                    "'.$arr_std[$vs['login']]['alpha_classroom_id'].'",   
                                    0)';
                                }    
                            }
                        }    
                    }    
                }
            }

            $page = $array['course_pagination']['next_page'];
            $total_page = $array['course_pagination']['total_pages'];
        }
    }
    if(!empty($insert_lesson)) {
        for($i = 0; $i  < count($insert_lesson); $i+=500){
            $output = array_slice($insert_lesson, $i, min(500, count($insert_lesson)-$i));
            $sql_insert_lesson = "INSERT INTO `alpha_lessons`
            (`graded`,
            `score`,
            `skill`,
            `passed`,
            `status`,
            `session_id`,
            `unit_type`,
            `id`,
            `level`,
            `created_at`,
            `time`,
            `grade`,
            `unit_id`,
            `updated_at`,
            `submitted`,
            `title`,
            `title_local`,
            `passed_in_course`,
            `alpha_lesson_id`,
            `alpha_course_id`,
            `alpha_student_id`,
            `alpha_classroom_id`,
            `alpha_delete`)
            VALUES ".implode(",",$output);   
            $rs = $GLOBALS['db']->query($sql_insert_lesson);
        }

    }
    if(!empty($insert_lesson_temp)){
        for($i = 0; $i  < count($insert_lesson_temp); $i+=500){
            $output = array_slice($insert_lesson_temp, $i, min(500, count($insert_lesson_temp)-$i));
            $sql_insert_lesson_temp = "INSERT INTO `alpha_lessons_temp`
            (`graded`,
            `score`,
            `skill`,
            `passed`,
            `status`,
            `session_id`,
            `unit_type`,
            `id`,
            `level`,
            `created_at`,
            `time`,
            `grade`,
            `unit_id`,
            `updated_at`,
            `submitted`,
            `title`,
            `title_local`,
            `passed_in_course`,
            `alpha_lesson_id`,
            `alpha_course_id`,
            `alpha_student_id`,
            `alpha_classroom_id`,
            `alpha_delete`)
            VALUES ".implode(",",$output); 
            $rs =  $GLOBALS['db']->query($sql_insert_lesson_temp);
        }
    }

    $sql_update_lesson = "UPDATE alpha_lessons al
    INNER JOIN
    alpha_lessons_temp alt ON al.id = alt.id 
    SET 
    al.grade = alt.grade,
    al.score = alt.score,
    al.skill = alt.skill,
    al.passed = alt.passed,
    al.status = alt.status,
    al.session_id = alt.session_id,
    al.unit_type = alt.unit_type,
    al.level = alt.level,
    al.created_at = alt.created_at,
    al.time = alt.time,
    al.graded = alt.graded,
    al.unit_id = alt.unit_id,
    al.updated_at = alt.updated_at,
    al.submitted = alt.submitted,
    al.title = alt.title,
    al.title_local = alt.title_local,
    al.passed_in_course = alt.passed_in_course,
    al.alpha_course_id = alt.alpha_course_id,
    al.alpha_student_id = alt.alpha_student_id,
    al.alpha_classroom_id = alt.alpha_classroom_id";
    $rs = $GLOBALS['db']->query($sql_update_lesson);

    $sql_delete_temp = "DELETE FROM alpha_lessons_temp";                         
    $rs = $GLOBALS['db']->query($sql_delete_temp); 

    $sql_update = "UPDATE alpha_classroom SET total_pages = $total_page, time_retrieve = current_timestamp() WHERE id = $classroom_id";
    $rs = $GLOBALS['db']->query($sql_update);

    return array(
        'success' => '1',
        'classroom_id' => $classroom_id,
        'notify' => ''); 
}  
?>