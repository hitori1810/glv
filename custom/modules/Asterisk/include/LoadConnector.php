<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class LoadConnector 
{
	function echoJavaScript() 
	{

if($_REQUEST['module'] == 'ModuleBuilder')
{
exit;
}

$sqluserQuery="SELECT id from users LEFT JOIN users_cstm ON users.id = users_cstm.id_c WHERE users.deleted = 0 AND users_cstm.phoneextension_c NOT LIKE '' AND users_cstm.phoneextension_c IS NOT NULL";

 $resultSet = $GLOBALS['current_user']->db->query($sqluserQuery, false); 
		while($dbrow = $GLOBALS['current_user']->db->fetchByAssoc($resultSet)) 
		{ 
			
                $phoneextension_c[]=$dbrow['id']; 
                                 
		}
$no_Of_users = count($phoneextension_c);
	
	



//echo '<script type="text/javascript">alert('.$no_Of_users.')</script>';

		 if ((!isset($_REQUEST['sugar_body_only']) || $_REQUEST['sugar_body_only'] != true) && $_REQUEST['action'] != 'modulelistmenu' &&
             $_REQUEST['action'] != "favorites" && $_REQUEST['action'] != 'Popup' && empty($_REQUEST['to_pdf']) &&
            (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv']) && $_REQUEST['action'] != 'Login' &&
            $_REQUEST['module'] != 'Timesheets') 
            {
			$conditionalJqueryIncludeScript=<<<QUERY8
<script type="text/javascript">
if(typeof JQuery == "undefined") {
var head = document.getElementByTagName("head")[0];
script = document.createElement('script');
script.id = 'JQuery';
script.type = 'text/javascript';
script.src = 'custom/include/javascript/jquery/jquery.pack.js';
head.appendChild(script);
}
</script>
QUERY8;
				
				$poll_rate = "10000";
				$current_user_id = $GLOBALS['current_user']->id;
            if(!empty( $GLOBALS['current_user']->showclicktocall_c) &&
               (($GLOBALS['current_user']->call_notification_c == '1')))
            {

				$PhoneExtension=$GLOBALS['current_user']->phoneextension_c ;
				$AsteriskIP=$GLOBALS['current_user']->asteriskname_c ;
				$CallNotify=$GLOBALS['current_user']->call_notification_c ;
				$CreateAccount=$GLOBALS['current_user']->create_account_c ;
				$CreateContact=$GLOBALS['current_user']->create_contact_c ;
				$CreateLead=$GLOBALS['current_user']->create_lead_c ;
				$CallTransfer=$GLOBALS['current_user']->call_transfer_c ;
				$CallHangup=$GLOBALS['current_user']->call_hangup_c ;
				$ShowPopup=$GLOBALS['current_user']->showclicktocall_c ;
				$DialoutPrefix=$GLOBALS['current_user']->dialout_prefix_c ;
				$DialPlan=$GLOBALS['current_user']->dial_plan_c ;
				$RelateContact=$GLOBALS['current_user']->relate_contact_c; ;
				$RelateAccount=$GLOBALS['current_user']->relate_account_c; ;
				$uservalue=$GLOBALS['current_user']->lastcall_c ;
				$createCase=$GLOBALS['current_user']->create_case_c ;
				$scheduleCall=$GLOBALS['current_user']->schedulecall_c ;
				$taskCall=$GLOBALS['current_user']->taskcall_c ;
				$lastcall='1' ;
				$RelateCase='1' ;
              $CallHold="";


				//Loading all variables and js,css files

				echo '<script type="text/javascript">window.poll_rate = ' . $poll_rate . ';</script>';
				echo '<script type="text/javascript">window.current_user_id = ' . "'$current_user_id'" . ';</script>';
				echo '<script type="text/javascript">window.no_Of_users = ' . "'$no_Of_users'" . ';</script>';
				echo '<script type="text/javascript">window.AsteriskIP = ' . "'$AsteriskIP'" . ';</script>';//
				echo '<script type="text/javascript">window.RelateAccount = ' . $RelateAccount . ';</script>';
				echo '<script type="text/javascript">window.RelateContact = ' . $RelateContact . ';</script>';
				echo '<script type="text/javascript">window.PhoneExtension =' . "'$PhoneExtension'" . ';</script>';
				echo '<script type="text/javascript">window.CreateAccount = ' . "'$CreateAccount'" . ';</script>';
				echo '<script type="text/javascript">window.CreateLead = ' . "'$CreateLead'" . ';</script>';
				echo '<script type="text/javascript">window.CreateContact = ' . $CreateContact . ';</script>';
				echo '<script type="text/javascript">window.CallTransfer = ' . $CallTransfer . ';</script>';
				echo '<script type="text/javascript">window.CallHold = ' . "'$CallHold'" . ';</script>';
				echo '<script type="text/javascript">window.CallHangup = ' . "'$CallHangup'" . ';</script>';
				echo '<script type="text/javascript">window.ShowPopup = ' . "'$ShowPopup'" . ';</script>';
				echo '<script type="text/javascript">window.createCase = ' . "'$createCase'" . ';</script>';
				echo '<script type="text/javascript">window.DialoutPrefix = ' . "'$DialoutPrefix'" . ';</script>';
				echo '<script type="text/javascript">window.DialPlan = ' . "'$DialPlan'" . ';</script>';
				echo '<script type="text/javascript">window.LastCall = ' . "'$lastcall'" . ';</script>';
				echo '<script type="text/javascript">window.uservalue = ' . "'$uservalue'" . ';</script>';
				echo '<script type="text/javascript">window.RelateCase = ' . "'$RelateCase'" . ';</script>';
				echo '<script type="text/javascript">window.scheduleCall = ' . "'$scheduleCall'" . ';</script>';
				echo '<script type="text/javascript">window.taskCall = ' . "'$taskCall'" . ';</script>';


				//Load Files JS
				if( preg_match("/^6\.[1-4]/",$GLOBALS['sugar_version'])) 
				{
				//~ echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>';
				//~ echo '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js" type="text/javascript"></script>';
				echo '<link rel="stylesheet" src="//normalize-css.googlecode.com/svn/trunk/normalize.css"/>';
				echo '<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/ui-lightness/jquery-ui.css">';
				//echo '<link rel="stylesheet" href="http://addyosmani.github.io/jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.10.3.custom.css">';
				echo '<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>';
				echo '<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>';
				//echo '<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>';		
				}
                     
					
					echo '<script type="text/javascript" src="include/javascript/jquery/jquery-ui-min.js"></script>';
				
				

				//echo '<script type="text/javascript" src="custom/modules/Asterisk/include/js/LowerSugarVersion/jquery.js"></script>';
				echo '<script type="text/javascript" src="custom/modules/Asterisk/include/js/tinybox.js"></script>';
				echo '<script type="text/javascript" src="custom/modules/Asterisk/include/js/jWebSocket.js"></script>';	
				echo '<script type="text/javascript" src="custom/modules/Asterisk/include/js/handlebars.runtime.js"></script>';
//				echo '<script type="text/javascript" src="custom/modules/Asterisk/include/template/call-template.tmpl"></script>';
						echo '<script type="text/javascript" src="custom/modules/Asterisk/include/template/calltemplate.js"></script>';
				echo '<script type="text/javascript" src="custom/modules/Asterisk/include/js/jquery.fancybox.js"></script>';
				echo '<script type="text/javascript" src="custom/modules/Asterisk/include/js/callPopups.js"></script>';
				echo '<script type="text/javascript" src="custom/modules/Asterisk/include/js/dialout.js"></script>';

				echo '<link rel="stylesheet" type="text/css" media="all" href="custom/modules/Asterisk/include/css/asterisk.css" />';
				echo '<link rel="stylesheet" type="text/css" media="all" href="custom/modules/Asterisk/include/css/style.css" />';
			//}}
		
		
		
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
				{
				
				//windows code start
$cmd="TASKLIST /FI \"IMAGENAME eq java.exe\"";
$result = exec($cmd);
if ($result=="INFO: No tasks are running which match the specified criteria.")
{
//echo "<pre>your Add-on will get restrted</pre>";
//$result = system("bash start.sh");
//echo "refresh";

echo "<script>								if (window.XMLHttpRequest)
								{// code for IE7+, Firefox, Chrome, Opera, Safari
								xmlhttpsoap=new XMLHttpRequest();
								}
								else
								{// code for IE6, IE5
								xmlhttpsoap=new ActiveXObject('Microsoft.XMLHTTP');
								}
								xmlhttpsoap.onreadystatechange=function()
								{
								if (xmlhttpsoap.readyState==4 && xmlhttpsoap.status==200)
								{
								}
								}
								xmlhttpsoap.open('GET','custom/modules/Asterisk/AsteriskServer/AsteriskManager/restartAsteriskCRM.php',true);
								xmlhttpsoap.send();
								</script>";
//echo "refresh the page";
//echo("<meta http-equiv='refresh' content='1'>");

}

else
{

//echo "<pre>Status:\t $result</pre>";

//echo "<pre>Message: SugarCRM Asterisk Add-on is working good.</pre>";
}


//windows code ends
				
				}
				else{
						
								
                                                            
                         //linux code start

//echo "<br/>";
//echo "<br/>";

//echo "<h5>Message:</h5>";

$output =shell_exec("ps aux | grep AsteriskSupport | grep -v grep | awk '{ print $2 }'");

$array = explode("\n", $output);

//echo "$output";

//echo '<pre>'.print_r($array,true).'</pre>';
$result = count($array);
$result=$result-1;
//echo "<pre>$result</pre>";

if ( $result <1)
{
echo "<pre>your Add-on will get restrted</pre>";
//$result = system("bash start.sh");
//echo "refresh";

echo "<script>								if (window.XMLHttpRequest)
								{// code for IE7+, Firefox, Chrome, Opera, Safari
								xmlhttpsoap=new XMLHttpRequest();
								}
								else
								{// code for IE6, IE5
								xmlhttpsoap=new ActiveXObject('Microsoft.XMLHTTP');
								}
								xmlhttpsoap.onreadystatechange=function()
								{
								if (xmlhttpsoap.readyState==4 && xmlhttpsoap.status==200)
								{
								}
								}
								xmlhttpsoap.open('GET','custom/modules/Asterisk/AsteriskServer/AsteriskManager/restartAsteriskCRM.php',true);
								xmlhttpsoap.send();
								</script>";
echo "refresh the page";
echo("<meta http-equiv='refresh' content='1'>");


}

else if ( $result >1)
{
//echo "<pre>Please Reboot this server</pre>";

}
else
{
//echo "<pre>SugarCRM Asterisk Add-on is working...</pre>";

}




// linux code ends

							
					
					}
    }      
}
}}


?>
