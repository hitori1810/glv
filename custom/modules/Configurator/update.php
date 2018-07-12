<?php


$oldip=trim($_GET['oldip']);

$oldip .="|"; 
//echo $oldip;
$ip=trim($_GET['ip']);
$ip .="|"; 
$username=trim($_GET['u']);
$username .="|"; 
$password=trim($_GET['p']);
$password .="|"; 
$internal_channel=trim($_GET['ic']);
$internal_channel .="|"; 
$external_channel=trim($_GET['ec']);
$external_channel .="|"; 


//$_SESSION['ip'] = $ip ;

//echo "".$ip.$username.$password.$internal_channel.$external_channel;exit;
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
	  
	 $result->item(0)->getElementsByTagName('Asterisk')->item(0)->nodeValue = $ip;
       $result->item(0)->getElementsByTagName('username')->item(0)->nodeValue = $username;
        $result->item(0)->getElementsByTagName('password')->item(0)->nodeValue = $password;
         $result->item(0)->getElementsByTagName('internalchannel')->item(0)->nodeValue = $internal_channel;
          $result->item(0)->getElementsByTagName('externalchannel')->item(0)->nodeValue = $external_channel;
    //  echo "hi";
       $dom->save($xml_file_name);

/*

session_unset();
session_destroy();
*/
echo "<script>$.post('custom/modules/Asterisk/AsteriskServer/AsteriskManager/restartAsteriskCRM.php')</script>";
	//echo "<script>alert('calling restart.php');</script>";
//echo "<script>location.replace='AsteriskCRMConServer/AsteriskCRMConServer/restartAsteriskCRM.php'</script>";
//echo "<script>location.replace('index.php?module=Configurator&action=_configurator')</script>";echo "<script>location.replace('index.php?module=Configurator&action=_configurator')</script>";
echo "<script>location.replace('index.php?module=Configurator&action=configurator')</script>";
//echo "Succeed";

?>
