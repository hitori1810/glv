<?php 

require_once('modules/Configurator/Configurator.php');
require_once('include/Sugar_Smarty.php');
include_once 'asteriskConfig.php';


echo "<style>

.table4conf {
	margin:0px;padding:0px;
	width:100%;
	box-shadow: 10px 10px 5px #888888;
	border:1px solid #000000;
	-moz-border-radius-bottomleft:9px;
	-webkit-border-bottom-left-radius:9px;
	border-bottom-left-radius:9px;
	-moz-border-radius-bottomright:9px;
	-webkit-border-bottom-right-radius:9px;
	border-bottom-right-radius:9px;
	-moz-border-radius-topright:9px;
	-webkit-border-top-right-radius:9px;
	border-top-right-radius:9px;
	-moz-border-radius-topleft:9px;
	-webkit-border-top-left-radius:9px;
	border-top-left-radius:9px;
}.CSS_Table_Example table{
	width:100%;
	height:100%;
	margin:0px;padding:0px;
}.CSS_Table_Example tr:last-child td:last-child {
	-moz-border-radius-bottomright:9px;
	-webkit-border-bottom-right-radius:9px;
	border-bottom-right-radius:9px;
}.CSS_Table_Example table tr:first-child td:first-child {
	-moz-border-radius-topleft:9px;
	-webkit-border-top-left-radius:9px;
	border-top-left-radius:9px;
}.CSS_Table_Example table tr:first-child td:last-child {
	-moz-border-radius-topright:9px;
	-webkit-border-top-right-radius:9px;
	border-top-right-radius:9px;
}.CSS_Table_Example tr:last-child td:first-child{
	-moz-border-radius-bottomleft:9px;
	-webkit-border-bottom-left-radius:9px;
	border-bottom-left-radius:9px;
}.CSS_Table_Example tr:hover td{
	background-color:#82c0ff;
	background:-o-linear-gradient(bottom, #82c0ff 5%, #56aaff 100%);	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #82c0ff), color-stop(1, #56aaff) );
	background:-moz-linear-gradient( center top, #82c0ff 5%, #56aaff 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#82c0ff', endColorstr='#56aaff');	background: -o-linear-gradient(top,#82c0ff,56aaff);
}.CSS_Table_Example tr:first-child td{
	background:-o-linear-gradient(bottom, #0069d3 5%, #007fff 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #0069d3), color-stop(1, #007fff) );
	background:-moz-linear-gradient( center top, #0069d3 5%, #007fff 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0069d3', endColorstr='#007fff');	background: -o-linear-gradient(top,#0069d3,007fff);
	background-color:#0069d3;
	border:0px solid #000000;
	text-align:center;
	border-width:0px 0px 1px 1px;
	font-size:18px;
	font-family:Comic Sans MS;
	font-weight:bold;
	color:#ffffff;
}.CSSTableGenerator tr:first-child:hover td{
	background:-o-linear-gradient(bottom, #0069d3 5%, #007fff 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #0069d3), color-stop(1, #007fff) );
	background:-moz-linear-gradient( center top, #0069d3 5%, #007fff 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0069d3', endColorstr='#007fff');	background: -o-linear-gradient(top,#0069d3,007fff);
	background-color:#0069d3;
}.CSS_Table_Example tr:first-child td:first-child{
	border-width:0px 0px 1px 0px;
}.CSS_Table_Example tr:first-child td:last-child{
	border-width:0px 0px 1px 1px;
}.CSS_Table_Example td{
	background:-o-linear-gradient(bottom, #56aaff 5%, #82c0ff 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #56aaff), color-stop(1, #82c0ff) ); 
	background:-moz-linear-gradient( center top, #56aaff 5%, #82c0ff 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#56aaff', endColorstr='#82c0ff');	background: -o-linear-gradient(top,#56aaff,82c0ff);
	background-color:#56aaff;
	border:1px solid #000000;
	border-width:0px 1px 1px 0px;
	text-align:left;
	padding:7px;
	font-size:12px;
	font-family:Comic Sans MS;
	font-weight:bold;
	color:#000000;
}.CSS_Table_Example tr:last-child td{
	border-width:0px 1px 0px 0px;
}.CSS_Table_Example tr td:last-child{
	border-width:0px 0px 1px 0px;
}.CSS_Table_Example tr:last-child td:last-child{
	border-width:0px 0px 0px 0px;
}


</style>";
$macurlstring = macplusurl();
$serial_id=getserialid($macurlstring);
$enc_serial_id =  encrypt_serial($serial_id);

$msg = $_GET['msg'];
$status = $_GET['status'];
$con_status = $_GET['con'];



if (!is_admin($current_user)) {
    sugar_die('Admin Only Section.');
}

$display="";
if($status=="1"){
    $display.="<br><div style='color:red;'>Please activate SugarCRM Asterisk Module</div><br> ";
}else if($status=="success"){
    $display.="<br><div style='color:red;'>Thank You Very Much for Activating SugarCRM Asterisk Module.<br> You have successfully activated SugarCRM Asterisk Module</div><br>";
}else if($status=="failure"){
    $display.="<br><div style='color:red;'>Invalid Serial Key entered.</div><br>";
}else if($status=="reg_done"){
    $display.="<br><div style='color:red;'>You have successfully registered.<br>We will soon contact you to activate The module.</div>";
}else if($status=="reg_update"){
    $display.="<br><div style='color:red;'>You have successfully Updated Record.<br> Please Check Your Email for More Detail</div>";
}

  


if($con_status=="notconnected")
{
    $display.="<br><br><div style='color:red;'>Please connect your Internet for us to contact you.
        <br> or else contact support@.in.
</div>";
}  
echo $display;
$configurator = new Configurator();

$file="custom/modules/Configurator/asterisksyn.txt";

$configurator = new Configurator();

//for contact infomation 

if((!empty($_POST['request_key'])) || (!empty($_POST['update']))){
	$name=$_POST['reg_name'];
	$cmp_name=$_POST['reg_comp_name'];
	$email=$_POST['reg_email'];
	$phone=$_POST['reg_phone'];
	$contactProduct="SugarCRM_Asterisk_Integration";
	$serial_id=$_POST['serial_id'];
	$configurator->loadConfig(); 
	$configurator->config['asterisk_register_name']=$name;
	$configurator->config['asterisk_register_phone']=$phone;
	$configurator->config['asterisk_register_email']=$email;
	$configurator->config['asterisk_register_cname']=$cmp_name;
	$configurator->saveConfig();
	
        $is_connect = is_connected();
        //echo $is_connect;exit;
		if($is_connect=="connected")
		 {
			if(!$name=='' && !$phone=='' && !$email=='' && !$cmp_name=='')
			{
				$formatted_name = str_replace(" ","_",$name);
             $formatted_company = str_replace(" ","_",$cmp_name);
             $formatted_email = str_replace(" ","_",$email);
             $formatted_phone = str_replace(" ","_",$phone);            
 $url = "http://www.techextension.com/techextension-activation-request.php?action=activation&contactName=".$formatted_name."&contactProduct=".$contactProduct."&contactAccount=".$formatted_company."&contactEmail=".$formatted_email."&contactPhone=".$formatted_phone."&serial_id=".$serial_id."";
		 
 $output=file_get_contents($url);  
			 }
         }
         else
         {
			 echo "<script>alert('Please Check your Internet Connectivity');</script>";
		 }
	if(!empty($_POST['update']))
        {
		header('Location:index.php?module=Configurator&action=asteriskUserInfo&status=reg_update&con='.$is_connect);
                
	}
        else if (!empty($_POST['request_key']))
        {         
            header('Location:index.php?module=Configurator&action=asteriskUserInfo&status=reg_done&con='.$is_connect);
	}
    
}

 
 
if(!empty($_POST['save']))
{
$entered_key = $_POST['serial_key'];
//echo "enter key".$entered_key;
$correct_key = GenerateKey($enc_serial_id);
if($entered_key == $correct_key)
{   $handle = fopen($file, 'w');
    fwrite($handle, $entered_key);
    chmod($file, 777);
    header('Location: index.php?module=Configurator&action=asteriskUserInfo&status=success');
}
 else 
     {
        header('Location: index.php?module=Configurator&action=asteriskUserInfo&status=failure');
     }  
}


$key_infile = file_get_contents($file);

if($key_infile!="")
{
    $mode = "readonly";
}

$ss = new Sugar_Smarty();
$ss->assign('MOD', $GLOBALS['mod_strings']);
$ss->assign('APP', $GLOBALS['app_strings']);
$ss->assign('serial_id', $enc_serial_id);
$ss->assign('serial_key', $key_infile);
$ss->assign('serial_mode', $mode);
echo $ss->fetch('custom/modules/Configurator/asteriskUserInfo.tpl');
?>
