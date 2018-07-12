<?php
echo  showSendSMSScreen();

function showSendSMSScreen(){
    global $current_user,$timedate;
    $ss = new Sugar_Smarty();
    $studentList = "";
    $email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name','type="sms" AND base_module="Contacts"','name');

    $ss->assign("MOD", $GLOBALS['mod_strings']);
    $ss->assign("CURRENT_USER_ID", $current_user->id);
    $ss->assign("TEMPLATE_OPTIONS", getSMSTenplates());
    $ss->assign("BRAND_NAME_OPTIONS", getBrandName());
    return $ss->fetch('custom/modules/Contacts/tpls/send_sms_screen.tpl');
}

function getSMSTenplates(){
    $sql = "SELECT id,name, body
    FROM email_templates
    WHERE deleted = 0
    AND type = 'sms'
    AND base_module = 'Contacts'";
    $result = $GLOBALS['db']->query($sql);
    $html = "<option selected value=''>-none-</option>";
    while($row = $GLOBALS['db']->fetchByAssoc($result)){
        $html .= '<option value="'.$row["id"].'" content="'.$row["body"].'">'.$row["name"].'</option>';
    }

    return $html;
}

function getBrandName(){
    global $current_user;
    $user_id = $current_user->id;
    if($current_user->isAdmin()){
        $user_id = '1';
    }
    $sql = "
    SELECT DISTINCT
    IFNULL(users.id, '') primaryid,
    IFNULL(users.user_name, '') users_user_name,
    IFNULL(l1.id, '') defaut_team_id,
    IFNULL(l1.name, '') defaut_team_name,
    IFNULL(l2.id, '') id,
    IFNULL(l2.name, '') name,
    IFNULL(l2.sms_config, '') sms_config
    FROM
    users
    INNER JOIN
    teams l1 ON users.default_team = l1.id
    AND l1.deleted = 0
    INNER JOIN
    team_memberships l2_1 ON users.id = l2_1.user_id
    AND l2_1.deleted = 0
    INNER JOIN
    teams l2 ON l2.id = l2_1.team_id AND l2.deleted = 0
    WHERE
    (((users.id = '$user_id') AND l2.sms_config <> ''))
    AND users.deleted = 0";
    $result = $GLOBALS['db']->query($sql);
    $html = "<option value=''>-none-</option>";
    while($row = $GLOBALS['db']->fetchByAssoc($result)){
        if($current_user->team_id == $row["id"])
            $selected = "selected";
        else $selected = "";
        $html .= '<option '.$selected.' value="'.$row["id"].'" config="'.$row["sms_config"].'">'.$row["name"].'</option>';
    }

    return $html;
}
?>
