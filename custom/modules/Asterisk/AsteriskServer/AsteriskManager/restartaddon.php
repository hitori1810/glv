<?php
echo "<br/>";

echo "<h4>SugarCRM Asterisk Add-on Running Status:</h4>";
echo "-----------------------------------------------------------------------------------------------------------------";
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
								xmlhttpsoap.open('GET','restartAsteriskCRM.php',true);
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
echo "-----------------------------------------------------------------------------------------------------------------";
echo "<h5>If SugarCRM Asterisk Add-on is not working well than restart it.</h5>";
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
								xmlhttpsoap.open('GET','restartAsteriskCRM.php',true);
								xmlhttpsoap.send();
		
		alert('SugarCRM Asterisk Add-on is Restarting....');
		location.reload();
    }
</script>";

echo "<button type='button' onclick='myFunction()'>Restart Add-on</button>";



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
								xmlhttpsoap.open('GET','restartAsteriskCRM.php',true);
								xmlhttpsoap.send();
								</script>";
echo "refresh the page";
echo("<meta http-equiv='refresh' content='1'>");


}

else if ( $result >1)
{
echo "<pre>Please Reboot this server</pre>";
echo "<pre>OR</pre>";
echo "<pre> Open CRM server on termainal and run this command </pre>";
echo "<pre>sudo kill -9 $(ps aux | grep AsteriskSupport | grep -v grep | awk '{ print $2 }')</pre>";
echo "<pre>Than check Add-on Status again...</pre>";


}
else
{
echo "<pre>SugarCRM Asterisk Add-on is working...</pre>";

}


echo "<br/>";
echo "-----------------------------------------------------------------------------------------------------------------";
echo "<h5>If SugarCRM Asterisk Add-on is not working well than restart it.</h5>";
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
								xmlhttpsoap.open('GET','restartAsteriskCRM.php',true);
								xmlhttpsoap.send();
		
		alert('SugarCRM Asterisk Add-on is Restarting....');
		location.reload();
    }
</script>";

echo "<button type='button' onclick='myFunction()'>Restart Add-on</button>";



// linux code ends
}
echo "<br/>";
echo "-----------------------------------------------------------------------------------------------------------------";
echo "<h4>If SugarCRM Astersik Add-on is not working still please Contact TechExtension Team.</h4>";

echo "Email: support@techextension.com";
echo "<br/>";
echo "Skype: tech.extension\n";


?>
