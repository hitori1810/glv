<?php
//echo "node gonna deleting......";
$oldip = trim($_GET['oldip'])."|";
//echo $oldip."<br>";


$xml_file_name='custom/modules/Asterisk/AsteriskServer/AsteriskManager/AsteriskProperties.xml'; 
//echo "".$ip.$username.$password.$internal_channel.$external_channel;
$dom = new DOMDocument();
       $dom->load($xml_file_name);
       $library = $dom->documentElement;
       $xpath = new DOMXPath($dom);
       
       
       $result = $xpath->query('/AsteriskProperties/AsteriskProperty[Asterisk="'.$oldip.'"]');
       //print_r($result);
						//'/AsteriskProperties/AsteriskProperty[Asterisk="192.168.1.51"]'
	  // echo $result->item(0)->getElementsByTagName('Asterisk')->item(0)->nodeValue;
	  
	 $result->item(0)->parentNode->removeChild($result->item(0));
$dom->save($xml_file_name);
echo "<script>location.replace('index.php?module=Configurator&action=configurator');</script>";


?>
