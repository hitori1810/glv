<?php
    global $current_user;
    require_once('modules/Users/User.php');
    require_once('custom/modules/Users/HomepageManagerLogicHook.php');
    $dd=new defaultHomepage();
    $focus=new User();
    if($_REQUEST['stage']=='1') {
        if(!empty($_REQUEST['delete'])) {
            $id=$_REQUEST['dashboard_name'];
            $sql="UPDATE user_preferences SET deleted=1 WHERE id='$id' AND category='Home';";
            $result=$focus->db->query($sql,true);
        } elseif (!empty($_REQUEST['save'])) {
            $user_id=$current_user->id;
            $sql="SELECT * FROM user_preferences WHERE assigned_user_id='$user_id' AND category='Home' AND deleted=0;";
            $result=$focus->db->query($sql,true);
            $hash=$focus->db->fetchByAssoc($result);
            $name='DD_'.$_REQUEST['new_dashboard_name'];
            $new_id=create_guid();
            $todays_date=date('Y-m-d h:i:s');
            $sql="INSERT INTO user_preferences (id,category,deleted,date_entered,date_modified,assigned_user_id,contents)
            VALUES('{$new_id}',
            'Home',
            0,
            '{$todays_date}',
            '{$todays_date}',
            '{$name}',
            '{$hash['contents']}');";
            $result=$focus->db->query($sql,true);
        }
        elseif(!empty($_REQUEST['update'])){
            $user_id=$current_user->id;
            $sql="SELECT * FROM user_preferences WHERE assigned_user_id='$user_id' AND category='Home' AND deleted=0;";
            $result=$focus->db->query($sql,true);
            $hash=$focus->db->fetchByAssoc($result);
            $id=$_REQUEST['dashboard_name'];
            $update = "UPDATE user_preferences SET contents = '{$hash['contents']}' WHERE id = '{$id}'";
            $result=$focus->db->query($update,true);
        }
    }
    echo "\n<p>\n";
    echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_HOMEPAGE_MANAGER_TITLE'], true);
    echo "\n</p>\n";
?>
<form name="default-dashboard" action="index.php" method="GET" enctype="multipart/form-data">
    <INPUT type="hidden" name="module" value="Users">
    <INPUT type="hidden" name="action" value="EditHomepageSettings">
    <INPUT type="hidden" name="stage" value="1">
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr>
            <th align="left" scope="row" colspan="4"><h4><slot><?php echo $mod_strings['LBL_SAVE_CONFIGRUATION'];?></slot></h4></th>
        </tr>
        <tr><td scope="row">
                <i><?php echo $mod_strings['LBL_SAVE_TEXT']; ?></i>
            </td>
        </tr>
        <tr>
            <td><?php echo $mod_strings['LBL_NAME'] ?>: <INPUT type="text" name="new_dashboard_name">&nbsp;<INPUT type="submit" name="save" value="<?php echo $GLOBALS['app_strings']['LBL_SAVE_BUTTON_LABEL'] ?>"></td>
        </tr>
    </table>
    <br>
    <table width="100%" border="0" cellpadding="5">
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr>
            <th align="left" scope="row" colspan="4"><h4><slot><?php echo $mod_strings['LBL_DASHBOARD_CONFIGURATION'] ?></slot></h4></th>
        </tr>
        <tr><td>
            <i><?php echo $mod_strings['LBL_DASHBOARD_CONFIGURATION_HELP'] ?></i>
        </td>
        <tr>
            <td>Name:
                <select name="dashboard_name">
                    <?php
                        echo $dd->getCustomDashboardOptions("",true);
                    ?>
                </select>&nbsp;<INPUT type="submit" name="update" value="<?php echo $GLOBALS['app_strings']['LBL_UPDATE'] ?>"> &nbsp; <INPUT type="submit" name="delete" value="<?php echo $GLOBALS['app_strings']['LBL_DELETE_BUTTON_LABEL'] ?>"></td>
        </tr>
    </table>
</form>