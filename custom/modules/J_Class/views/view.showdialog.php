<?php
    //add By Lam Hai 01/06/2016 - get list for function export student list
    if( isset($_POST['template'])) {
        $template = $_POST['template'];
        $classID = $_POST['classID'];
        $script = '<script>
        $("#checkAll").click(function(){
        if ($(this).is(":checked")) {
        $(".check").attr("checked", true);
        } else {
        $(".check").attr("checked", false);
        }
        });

        $(".check").click(function(){
        $("#checkAll").attr("checked", false);
        });
        </script>
        ' ;
    }
    //Show list student when choose thank you template or in course report
    if( (strpos($template, 'Thanks you Template') !== false) || $template == 'In Course Report' || $template == 'In-Course Report Adult' || $template == 'In Course Report (New)') {
        $sqlGetStudents = "SELECT DISTINCT l1.class_code, contacts.id student_id, contact_id, full_student_name
        FROM contacts
        INNER JOIN  j_class_contacts_1_c l1_1 ON contacts.id = l1_1.j_class_contacts_1contacts_idb AND l1_1.deleted = 0
        INNER JOIN  j_class l1 ON l1.id = l1_1.j_class_contacts_1j_class_ida AND l1.deleted = 0
        WHERE l1.id='{$classID}'
        AND  contacts.deleted=0
        ORDER BY full_student_name ASC";
        $rsGetStudents = $GLOBALS['db']->query($sqlGetStudents);
        $studentNo = 1;
        $studentHtml = '';
        $studentTitle ='<tr style="vertical-align: top;">
        <th>'.translate('LBL_EXPORT_CHECK_ALL','J_Class').'<br><input type="checkbox" id="checkAll"></th>
        <th>'.translate('LBL_EXPORT_STUDENT_NO','J_Class').'</th>
        <th>'.translate('LBL_EXPORT_STUDENT_ID','J_Class').'</th>
        <th>'.translate('LBL_EXPORT_STUDENT_NAME','J_Class').'</th>
        </tr>
        <tr>
        <td colspan="4"><hr></td>
        </tr>';

        while($rowStudent = $GLOBALS['db']->fetchByAssoc($rsGetStudents) ) {
            $studentHtml .= '<tr>
            <td class="checkbox"><input type="checkbox" class="check" name = "student_id[]" value="'. $rowStudent['student_id']. '"></td>
            <td>'. $studentNo. '</td>
            <td>'. $rowStudent['contact_id']. '</td>
            <td class="studentName">'. $rowStudent['full_student_name']. '</td>
            </tr>';
            $studentNo ++;
        }
        echo $studentTitle. $studentHtml.$script;
    }
    //Show list student when choose Certificate Junior with some condition
    //student have test 2 & 3 in class 108h or have test 1 & 2 in class 72h or test final in class 36h
    else if (strpos($template, 'Certificate') !== false) {
        $sqlGetStudents = "SELECT DISTINCT
        l1.class_code,
        contacts.id,
        contacts.contact_id,
        contacts.full_student_name,
        gbdetail.final_result,
        gbdetail.certificate_type
        FROM contacts
        INNER JOIN j_class_contacts_1_c l1_1 ON contacts.id = l1_1.j_class_contacts_1contacts_idb AND l1_1.deleted = 0
        INNER JOIN j_class l1 ON l1.id = l1_1.j_class_contacts_1j_class_ida AND l1.deleted = 0
        INNER JOIN j_class_j_gradebook_1_c l2_1 ON l1.id = l2_1.j_class_j_gradebook_1j_class_ida AND l2_1.deleted = 0
        INNER JOIN j_gradebook l2 ON l2.id = l2_1.j_class_j_gradebook_1j_gradebook_idb AND l2.deleted = 0
        INNER JOIN j_gradebookdetail gbdetail ON gbdetail.student_id = contacts.id AND gbdetail.gradebook_id = l2.id AND gbdetail.deleted = 0
        WHERE l1.id='{$classID}'
        AND l1.deleted=0 AND l2.type = 'Overall'
        AND gbdetail.certificate_type != ''";
        $rsGetStudents2 = $GLOBALS['db']->query($sqlGetStudents);
        $studentList = array();

        while($rowStudent2 = $GLOBALS['db']->fetchByAssoc($rsGetStudents2) ) {
            $studentList[$rowStudent2['id']]['class_code'] = $rowStudent2['class_code'];
            $studentList[$rowStudent2['id']]['contact_id'] = $rowStudent2['contact_id'];
            $studentList[$rowStudent2['id']]['full_student_name'] = $rowStudent2['full_student_name'];
            $studentList[$rowStudent2['id']]['final_result'] = $rowStudent2['final_result'];
            $studentList[$rowStudent2['id']]['certificate_type'] = $rowStudent2['certificate_type'];
        }

        $studentTest1 = array();
        $studentHtml = '';
        $studentTitle =
        '<tr style="vertical-align: top;">
        <th>'.translate('LBL_EXPORT_CHECK_ALL','J_Class').'<br><input type="checkbox" id="checkAll"></th>
        <th>'.translate('LBL_EXPORT_STUDENT_NO','J_Class').'</th>
        <th>'.translate('LBL_EXPORT_STUDENT_ID','J_Class').'</th>
        <th>'.translate('LBL_EXPORT_STUDENT_NAME','J_Class').'</th>
        <th>'.translate('LBL_EXPORT_STUDENT_ACHIEVEMENT','J_Class').'</th>
        </tr>
        <tr>
        <td colspan="5"><hr></td>
        </tr>';
        //check valid condition and print list
        $studentNo = 1;
        foreach ($studentList as $key => $value) {
            $studentHtml .= '<tr>
            <td class="checkbox"><input type="checkbox" class="check" name = "student_id[]" value="'. $key .'"></td>
            <td>'. $studentNo .'</td>
            <td>'. $value['contact_id'] .'</td>
            <td class="studentName">'. $value['full_student_name'] .'</td>
            <td>'. $value['certificate_type'] .'</td>
            </tr>';
            $studentNo ++;
        }

        echo $studentTitle. $studentHtml.$script;
    }
    die;