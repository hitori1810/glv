<?php
$my_file = 'flag.txt';
$handle = fopen($my_file, 'r');
$data = fread($handle,filesize($my_file));

//echo $data ;

if($data =='yes')
{
	$var = system("sudo ps aux | grep '".getcwd()."/AsteriskSupport.jar' | grep -v 'grep'");
	echo "no";//break;	
	$myFile = "flag.txt";
	//sleep(15);
	$fh = fopen($myFile, 'w');
	$stringData = "no";
	fwrite($fh, $stringData);
	fclose($fh);
}
else
{
	$var = system("sudo ps aux | grep  '".getcwd()."/AsteriskSupport.jar' | grep -v 'grep'");
	if($var=='')
	{	
		$myFile = "flag.txt";
		$fh = fopen($myFile, 'w');
		$stringData = "yes";
		fwrite($fh, $stringData);
		fclose($fh);
		echo "yes";
	}
	else
	{
		echo "refresh";
	}
}



?>
