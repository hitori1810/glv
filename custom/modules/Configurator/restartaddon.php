<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

if(!is_admin($current_user)){
	sugar_die('Admin Only');	
}

echo "<br/>";

echo "<h4 style='font-family:georgia,serif;color:#0000ff;'>SugarCRM Asterisk Add-on Running Status:</h4>";
//$s=exec('whoami');
//echo "<pre>$s</pre>";

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
{
//windows code start
$cmd="TASKLIST /FI \"IMAGENAME eq java.exe\"";
$result = exec($cmd);
if ($result=="INFO: No tasks are running which match the specified criteria.")
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

else
{
echo "<br/>";
echo "<pre>Status:\t $result</pre>";

echo "<pre>Message: SugarCRM Asterisk Add-on is working.</pre>";
echo "<br/>";

echo "<h5 style='font-family:georgia,serif;color:red;'>Note: If SugarCRM Asterisk Add-on is not working well than restart it and Check Message</h5>";
echo "<script>
function myFunction() {
        
		if (window.XMLHttpRequest)
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
		
		alert('SugarCRM Asterisk Add-on is Restarting....');
		location.reload();
    }
</script>";
echo "<br/>";
echo "<button type='button' onclick='myFunction()' style='font-size:12px;height: 30px; width:200px; color: #ffffff; background-color: #008800; border: 1pt ridge lightgrey;'>Restart Add-on</button>";

echo "<br/>";

}


//windows code ends
}
else
{
//linux code start

echo "<br/>";
echo "<br/>";

echo "<h5>Message:</h5>";

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
echo "<pre>Please Reboot this server</pre>";

}
else
{
echo "<pre>SugarCRM Asterisk Add-on is working...</pre>";

}


echo "<br/>";

echo "<h5 style='font-family:georgia,serif;color:red;'>Note: If SugarCRM Asterisk Add-on is not working well than restart it and Check Message</h5>";

echo "<script>
function myFunction() {
        
		if (window.XMLHttpRequest)
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
		
		alert('SugarCRM Asterisk Add-on is Restarting....');
		location.reload();
    }
</script>";
echo "<br/>";
echo "<button type='button' onclick='myFunction()' style='font-size:12px;height: 30px; width:200px; color: #ffffff; background-color: #008800; border: 1pt ridge lightgrey;'>Restart Add-on</button>";

echo "<br/>";


// linux code ends
}
echo "<br/>";

echo "<h5 style='font-family:georgia,serif;color:#0000ff;'>If SugarCRM Astersik Add-on is not working still please Contact TechExtension Team.</h5>";
echo "<br/>";
echo "<p style='font-family:georgia,serif;color:#a52a2a;'>Email: support@techextension.com</p>";
echo "<p style='font-family:georgia,serif;color:#a52a2a;'>Skype: tech.extension</p>";
echo"<font color=\"#a52a2a\" face=\"georgia, serif\">Website :</font><a href=\"http://www.techextension.com\" target=\"_blank\" style='font-family:georgia,serif;color:#0000ff;'>www.techextension.com</a>";


?>
