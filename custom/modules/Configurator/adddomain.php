<?php
$url1=$_GET['domain'];
//echo $url1;
$ur2=substr($url1, 0, strpos($url1, "://"));
	if($ur2=="http")
	{  
	}
	else{ $url1="http*://".$url1;}
	
	$ur= substr($url1, -1);
	if($ur=="/")
	{
	$dd=substr_replace($url1 ,"",-1);
	}
	else
	{ 
	 $dd=$url1; $url1=$url1;
	 }
	$uri=$dd;
	$url=$uri;

$dom = new DOMDocument();
$dom->encoding = 'utf-8';
$dom->xmlVersion = '1.0';
$dom->formatOutput = true; 
$dom->xmlStandalone = true;
$xml_file_name='custom/modules/Asterisk/AsteriskServer/conf/jWebSocket.xml'; 





$dom->load($xml_file_name);
$xpath = new DOMXPath($dom);
$items = $xpath->query('/jWebSocket/engines/engine/domains');
foreach($items as $item)
{
  $item->appendChild($dom->createElement('domain', $url1));
}
//echo $url1;
$dom->save($xml_file_name);
echo "<script>location.replace('index.php?module=Configurator&action=configurator');</script>";

?>
