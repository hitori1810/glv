<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

    class handleSave_Card {
        function handleSave_Card(&$bean, $event, $arguments)
        {  
            if($bean->type == 'Student'){
                $ct = BeanFactory::getBean('Contacts',$bean->c_memberships_contacts_2contacts_idb);
                if(!empty($_POST['image'])){
                    require_once('include/SugarFields/Fields/Image/Image.php');
                    define('UPLOAD_DIR', 'upload/');
                    define('RESIZE_DIR', 'custom/uploads/imagesResize/');
                    unlink(UPLOAD_DIR.$ct->picture);
                    unlink(RESIZE_DIR.$ct->picture);
                    $image_id = create_guid();
                    $img = $_POST['image'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $image_id;
                    $success = file_put_contents($file, $data);
                    if($success){
                        //RESIZE
                        $reize = new Image($file);
                        $reize->resize(220,220);
                        $reize->save(RESIZE_DIR.$image_id);
                        $sql = "UPDATE contacts SET picture='$image_id' WHERE id = '{$bean->c_memberships_contacts_2contacts_idb}'";
                        $GLOBALS['db']->query($sql);    
                    }   
                }
                $bean->name_on_card = $ct->last_name.' '. $ct->first_name; 
            }else{
                $ct1 = BeanFactory::getBean('Leads',$bean->c_memberships_leads_1leads_idb);
                $bean->name_on_card = $ct1->last_name.' '. $ct1->first_name;  
            }
            if($_POST['no_image'] == '1'){
                define('UPLOAD_DIR', 'upload/');
                define('RESIZE_DIR', 'custom/uploads/imagesResize/');
                unlink(UPLOAD_DIR.$ct->picture);
                unlink(RESIZE_DIR.$ct->picture);
                $sql = "UPDATE contacts SET picture='' WHERE id = '{$ct->id}'";
                $GLOBALS['db']->query($sql);   
            }
        }
    }
?>
