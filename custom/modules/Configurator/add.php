<?php 
//require('../../../config.php'); 

$db_name= trim($_GET['d']);
$db_user= trim($_GET['du']);
$db_pass= trim($_GET['dp']);


$IP= $_GET['h'];
$username= $_GET['u'];
$password= $_GET['p'];
$internal_channel= $_GET['i'];
$external_channel= $_GET['e'];
//echo "hi";

$url = $sugar_config['site_url'];

//echo $url;
//echo $external_channel;

$dom = new DOMDocument();
$dom->encoding = 'utf-8';
$dom->xmlVersion = '1.0';
$dom->formatOutput = true; 
$dom->xmlStandalone = true;
$xml_file_name='custom/modules/Asterisk/AsteriskServer/AsteriskManager/AsteriskProperties.xml'; 
//echo $xml_file_name;
if( $xml = file_get_contents($xml_file_name) ) 
{
	//echo "File Found </br>";
	$dom->loadXML( $xml);
	//echo "File Loaded </br>";
	$root = $dom->getElementsByTagName('AsteriskProperties')->item(0);
	
	
	$user_node=$dom->createElement('AsteriskProperty');
	//$attr_Asterisk_ip = new DOMAttr('Asterisk', '192.168.1.54'); 
	//$user_node->setAttributeNode($attr_Asterisk_ip);
	//$root->insertBefore( $user_node, $root->firstChild );
	$child_node_IP=$dom->createElement('Asterisk',$IP."|");
	$user_node->appendChild($child_node_IP);
	
	$child_node_User_name=$dom->createElement('username',$username."|");
	$user_node->appendChild($child_node_User_name);
	
	$child_node_password=$dom->createElement('password',$password."|");
	$user_node->appendChild($child_node_password);
	
	$child_node_ic=$dom->createElement('internalchannel',$internal_channel."|");
	$user_node->appendChild($child_node_ic);
	
	$child_node_ec=$dom->createElement('externalchannel',$external_channel."|");
	$user_node->appendChild($child_node_ec);
	
	$child_node_dname=$dom->createElement('dbname',$db_name."|");
	$user_node->appendChild($child_node_dname);
	
	$child_node_duser=$dom->createElement('dbusername',$db_user."|");
	$user_node->appendChild($child_node_duser);
	
	$child_node_dpass=$dom->createElement('dbpassword',$db_pass."|");
	$user_node->appendChild($child_node_dpass);
	
	$child_node_url=$dom->createElement('url',$url."|");
	$user_node->appendChild($child_node_url);
	
	$child_node_url=$dom->createElement('crmusername',$db_user."|");
	$user_node->appendChild($child_node_url);
	
	$child_node_url=$dom->createElement('crmpassword',$db_user."|");
	$user_node->appendChild($child_node_url);
	
	$child_node_sc=$dom->createElement('sc','?');
	$user_node->appendChild($child_node_sc);
	
	
	$root->appendChild($user_node);
	$dom->save($xml_file_name);
}
else
{
	$root = $dom->createElement('AsteriskProperties'); 
	$user_node=$dom->createElement('AsteriskProperty');
	//$attr_Asterisk_ip = new DOMAttr('Asterisk', '192.168.1.51'); 
	//$user_node->setAttributeNode($attr_Asterisk_ip);
	$child_node_User_name=$dom->createElement('Asterisk',$IP."|");
	$user_node->appendChild($child_node_User_name);
	
	$child_node_User_name=$dom->createElement('username',$username."|");
	$user_node->appendChild($child_node_User_name);
	
	$child_node_password=$dom->createElement('password',$password."|");
	$user_node->appendChild($child_node_password);
	
	$child_node_ic=$dom->createElement('internalchannel',$internal_channel."|");
	$user_node->appendChild($child_node_ic);
	
	$child_node_ec=$dom->createElement('externalchannel',$external_channel."|");
	$user_node->appendChild($child_node_ec);
	
	$child_node_dname=$dom->createElement('dbname',$db_name."|");
	$user_node->appendChild($child_node_dname);
	
	$child_node_duser=$dom->createElement('dbusername',$db_user."|");
	$user_node->appendChild($child_node_duser);
	
	$child_node_dpass=$dom->createElement('dbpassword',$db_pass."|");
	$user_node->appendChild($child_node_dpass);
	
	$child_node_url=$dom->createElement('url',$url."|");
	$user_node->appendChild($child_node_url);
	
	$child_node_url=$dom->createElement('crmusername',$db_user."|");
	$user_node->appendChild($child_node_url);
	
	$child_node_sc=$dom->createElement('crmpassword','none');
	$user_node->appendChild($child_node_sc);
	
	
	
	$child_node_sc=$dom->createElement('sc','?');
	$user_node->appendChild($child_node_sc);
	
	$root->appendChild($user_node);
	$dom->appendChild($root);
	$dom->save($xml_file_name);
}
//echo "done";

echo "<script>location.replace('index.php?module=Configurator&action=configurator');</script>";

?>

