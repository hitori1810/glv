<!--<script type="text/javascript" src="custom/modules/Administration/smsPhone/javascript/jquery.js"></script>-->
<script type="text/javascript" src="custom/modules/Administration/smsPhone/javascript/jquery.jmpopups-0.5.1.js"></script>
<script type="text/javascript" src="custom/modules/Administration/smsPhone/javascript/smsPhone.js"></script>


<style type="text/css">
    div#editor_style {
        border:#CCCCCC solid 2px;
        padding:10px;
        background-color:#FFFFFF;
    }
    textarea#sms_msg{
        width:100%;
        font-size:12px;
    }
    div#smstip {
        border:red solid 2px;
        background-color:#FFC;
        padding:10px;
        font-size:11px;
        position:absolute;
        z-index:10;
        float:right;
        display:none;
        width:250px;
    }
    div#sms_response {
        margin-top:10px;
        margin-bottom:5px;
        color:#000;
        font-weight:bold;
    }

</style>

<?php

echo "<div id='editor_boundary'><br><div id='editor_style'>";

# hidden fields
echo "<input type='hidden' id='send_to_multi' value='{$send_to_multi}'>";
echo "<input type='hidden' id='ptype' value='{$ptype}'>";
echo "<input type='hidden' id='pid' value='{$pid}'>";
echo "<input type='hidden' name='pname' id='pname' value='".$pname."'>";
echo "<input type='hidden' name='current_user' id='current_user' value='".$GLOBALS['current_user']->id."'>";

# MULTIPLE RECIPIENT
if ($send_to_multi) {

    if (empty($sms_field) || $sms_field==NULL) {

        echo "<div id='editor_boundary'><br><div id='editor_style'>";
        echo "You have not specified an SMS phone field for this module.<br>";
        echo "Click <a href='index.php?module=Administration&action=smsPhone'>here</a> to configure.";
        echo "</div></div>";

    } else {

        require_once($mod_bean_files);
        $recipients = array();

        echo "Recipients&nbsp;<span class='required'>*</span><br>";

        # need to improve this area in the future to get the delete image
        if (file_exists('themes/Sugar/images/delete_inline.gif'))
            $src = 'themes/Sugar/images/delete_inline.gif';
        elseif(file_exists('themes/Sugar/images/delete_inline.png'))
            $src = 'themes/Sugar/images/delete_inline.png';
        else
            $src = '';

        $img = "<img src='{$src}' alt='[x]' align='absmiddle' border='0' width='12' height='12'>";
        foreach($pids as $id) {
            if(!empty($id)){
                $mod = BeanFactory::getBean($_GET['ptype'],$id);
                if($mod) {
                    $name = $mod->last_name.' '.$mod->first_name;
                    if(empty($name) || $name == ' ')
                       $name = $mod->name;

                    echo "<div class='recipient'>";
                    echo $name;
                    echo "<span class='link' title='Click to remove {$mod->name} (".preg_replace('/[^0-9]/', '', $mod->$sms_field).")'
                    onmouseover=\"$(this).parent().css('background-color','#CCC');\"
                    onmouseout=\"$(this).parent().css('background-color','#FF9');\"
                    onclick=\"$(this).parent().remove(); if($('input#pids').length==0) { $('div#editor_style').html('You removed all recipients. <br>Press ESC key or click the gray area to close this message.').css('padding', '30px').css('font-weight','bold'); };\">{$img}</span>";
                    echo "<input type='hidden' name='pids' id ='pids' value='{$id}'>";
                    echo "</div>";
                }
            }
        }
        echo "<input type='text' name='number' id='number' disabled='disabled' value='MULTI' style='display:none;'>";
        echo "<div style='clear:both;'></div>";

    }

    # SINGLE RECIPIENT
} else {

    //        echo "HOC&nbsp;<span class='required'>*</span><br>";
    echo "Phone Number&nbsp;<span class='required'>*</span><br>";
    echo "<input type='text' name='number' id='number' value='" . trim($phone_number) ."'>";
    echo "&nbsp;<span onmouseover='show_tip();' onmouseout='hide_tip();' style='color:#FF0000;font-weight:bold;cursor:pointer;'>[?]</span>";
    echo "<div id='smstip'></div>";

}
//
# enhancement: uses of tpls 15Jul2010
//if ($sms->uses_template() == true) {
echo "<div style='height:10px;'></div>";
echo "<span title='Optional'>Template</span><br>";
$email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name','type="sms" ','name');
echo "<select id='template_id' onchange='load_message(this.value);'>".get_select_options_with_id($email_templates_arr, "")."</select>";
//}
# # # #

echo "<div style='height:10px;'></div>";
echo "Message <span class='required'>*</span><br>";
echo "<textarea name='sms_msg' id='sms_msg' rows='6' onkeyup=\"check_sms_len();\">{$msg}</textarea><br>";
echo "<span id='sms_len_notifier'>Limit your message up to 480 characters only.</span><br><br>";

echo "<div style='clear:both;'></div>";
echo "<div style='float:left;'>Press ESC key to close.</div>";
echo "<div style='float:right;'>";
echo "<input type='button' class='button' id='recent_sms' value='Recent SMS' onclick='showRecentSMS();'>&nbsp;";
echo "<input type='button' class='button primary' id='send' value='Send' disabled='disabled' onclick='{$onclick}'>&nbsp;";
echo "</div>";
echo "<div style='clear:both;'></div>";

echo "<div id='sms_response'></div></div></div>";

?>
<script type='text/javascript'>check_sms_len(document.getElementById('sms_msg').length)</script>
