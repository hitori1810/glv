<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    global $db;
    $id_saved_search = $_REQUEST['savedsearch'];
    $user_ids = $_REQUEST['user_id'];
    if(is_array($user_ids) && count($user_ids)>0){
        $saved_search=loadBean('SavedSearch');
        $saved_search->retrieveSavedSearch($id_saved_search);

        //kiem tra saved search nay da duoc public thi ko dc public tiep
        $sql = "SELECT parent_id FROM saved_search WHERE id='{$id_saved_search}'";

        $result = $db->query($sql);
        $check = $db->fetchByAssoc($result);
        if(!$check['parent_id']){// ko co parent id thi moi dc publish
            //set public to parent       
            $sql = "UPDATE saved_search SET public = '1' WHERE id='{$id_saved_search}'";
            $db->query($sql);


            //lay tat ca nguoi dung dang active
            /*$query = "SELECT id FROM users WHERE status = 'Active' AND id != '{$saved_search->assigned_user_id}'";
            $result = $db->query($query); */

            // while($user = $db->fetchByAssoc($result)) {
            for($i=0;$i<count($user_ids); $i++){
                $user_id = $user_ids[$i];
                // insert into saved_search table
                $chk = check_saved_search($user_id, $saved_search);
                if($chk== NULL || $chk <=0){
                    $ss = new SavedSearch();
                    $ss->name = $saved_search->name;
                    $ss->assigned_user_id = $user_id;
                    $ss->search_module = $saved_search->search_module;
                    $ss->contents = base64_encode(serialize($saved_search->contents));
                    $ss->parent_id = $saved_search->id;

                    $ss->save(); 

                    //$sql = "INSERT INTO saved_search VALUES ('{$saved_search->name}','{$row['id']}','{$saved_search->search_module},{$saved_search->contents}, {$saved_search->id} )"
                }

            }

            echo 'Done!'; 
        }else{

            echo 'Current saved search has published!';
        }

    }



    /**
    * kiem tra saved search da ton tai voi nguoi dung chua
    * 
    * return true: có rồi, false: chưa có
    * @param mixed $user_id
    * @param mixed $saved_search
    */

    function check_saved_search($user_id, $saved_search){

        global $db;

        $sql = "SELECT id  FROM saved_search WHERE assigned_user_id='$user_id' 
        AND name = '{$saved_search->name}' 
        AND search_module ='{$saved_search->search_module}'
        AND parent_id = '{$saved_search->id}'
        ";

        $result = $db->query($sql);
        return $db->getRowCount($result);

    }


?>
