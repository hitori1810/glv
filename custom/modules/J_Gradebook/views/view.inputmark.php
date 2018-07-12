<?php
    class J_GradebookViewInputMark extends SugarView{
        var $bean2;
        function J_GradebookViewInputMark(){
            $this->bean2 = new J_Gradebook();
            $this->record = $_REQUEST['record'] ;
            $this->bean2->retrieve($this->record);//$_REQUEST['record']);
            parent::display();
        }

        function display(){
//             if($this->bean2->isLockGradebook()) {
//                SugarApplication::redirect("index.php?module=J_Gradebook&action=DetailView&record=".($this->bean2->id));
//            }
            include_once("custom/modules/J_Gradebook/GradebookFunctions.php");
            include_once("custom/include/_helper/junior_gradebook_utils.php");
            global $db, $mod_strings, $app_list_strings,$app_strings,$timedate;

            $ss = new Sugar_Smarty();
            $ss->assign('MOD',$mod_strings);
            $ss->assign('APP',$app_strings);
            $ss->assign('RECORD',$this->bean2->id);
            $ss->assign('GRADEBOOK_CONTENT',$this->bean2->loadGradeContent(false));
            $ss->assign('FIELDS',array(
                'j_class_j_gradebook_1j_class_ida' => $this->bean2->j_class_j_gradebook_1j_class_ida,
                'j_class_j_gradebook_1_name' => $this->bean2->j_class_j_gradebook_1_name,
                'c_teachers_j_gradebook_1_name' => $this->bean2->c_teachers_j_gradebook_1_name,
                'c_teachers_j_gradebook_1c_teachers_ida' => $this->bean2->c_teachers_j_gradebook_1c_teachers_ida,
                'grade_config' => $this->bean2->grade_config,
                'weight' => $this->bean2->weight,
            ));
            $ss->assign('GRADEBOOK_OPTIONS',getGradebookSelectOptions($this->bean2->j_class_j_gradebook_1j_class_ida,$this->bean2->id));
       //     if(!empty($this->bean2->grade_config))
            $comment_list = array(
                'comment_Progress_list' => array(
                    'array_name' => 'comment_Progress_list',
                    'label' => 'Progress',
                ),
                'comment_Commitment_list' => array(
                    'array_name' => 'comment_Commitment_list',
                    'label' => 'Commitment',
                ),
                'comment_Overall_list' => array(
                    'array_name' => 'comment_Overall_list',
                    'label' => 'Overall',
                ),
            );
            $comments = array();
            foreach($comment_list as $id => $option) {
                $comments[] = array(
                    'ID' => $id,
                    'LABEL' =>  $option['label'],
                    'OPTIONS' => get_select_options_with_id($app_list_strings[$option['array_name']],''),
                );
            }

            $ss->assign('COMMENTLIST',$comments);

            $ss->display("custom/modules/J_Gradebook/tpls/inputMark.tpl") ;
        }
    }
?>