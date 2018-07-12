<?php

//echo "<script>alert('refreshing');</script>";require('../../config.php'); 
global $sugar_config;
$url = $sugar_config['site_url'];
//echo $url;

	
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
{
	if(file_exists("start.bat")==true)
	{
		$result = exec("start.bat");	
	}
else
{
	
	$fp = fopen("start.bat","wb");
	fwrite($fp,"cd ". getcwd()."\n");
	fwrite($fp,"taskkill /F /IM java.exe \n");//
	fwrite($fp,"java -jar AsteriskSupport.jar \n");
	fclose($fp);
	$result = exec("start.bat");
}
} 
else 
{
	if(file_exists("start.sh")==true)
{
	//echo "<script>alert('file already exist');</script>";
//echo "file exist";
$result = system("bash start.sh");	

}
else
{
	//echo "not file exist";
	$content = "cd ". getcwd() ."\nkill -9 $(ps aux | grep AsteriskSupport | grep -v grep | awk '{ print $2 }')\njava -jar AsteriskSupport.jar";
	$fp = fopen("start.sh","wb");
	fwrite($fp,$content);
	fclose($fp);
	$result = system("bash start.sh");
}

}

	//echo "<script>alert('refreshing');</script>";
$location = $url."/index.php?module=Configurator&action=configurator";
header( 'Location: '.$location ) ;
exit;
?>
