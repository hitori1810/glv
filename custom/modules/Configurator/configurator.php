<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

if(!is_admin($current_user)){
	sugar_die('Admin Only');	
}

require_once('modules/Configurator/Forms.php');
echo get_module_title("techExtension", "SugarCRM Asterisk Configuration"." :", true);
require_once('modules/Configurator/Configurator.php');

$xml_file_name='custom/modules/Asterisk/AsteriskServer/AsteriskManager/AsteriskProperties.xml'; 
$dom = new DOMDocument();
$dom->encoding = 'utf-8';
$dom->xmlVersion = '1.0';
$dom->formatOutput = true; 
$dom->xmlStandalone = true;
$dom = new DOMDocument();
$dom->load($xml_file_name);



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
?>

<script>
function showFields(id)
{
//alert(id);
/*
var IP = document.getElementById("ip2"+id).value;
alert(IP);
*/
//alert(id);
	document.getElementById('ip2'+id).style.display = 'block';
	document.getElementById('ip'+id).style.display = 'none';

	
	document.getElementById('user2'+id).style.display = 'block';
	document.getElementById('user'+id).style.display = 'none';
	
	document.getElementById('pass2'+id).style.display = 'block';
	document.getElementById('pass'+id).style.display = 'none';
	
	document.getElementById('int2'+id).style.display = 'block';
	document.getElementById('int'+id).style.display = 'none';

	document.getElementById('ext2'+id).style.display = 'block';
	document.getElementById('ext'+id).style.display = 'none';
		
	document.getElementById('saveb'+id).style.display = 'block';
	document.getElementById('editb'+id).style.display = 'none';

}

function saveFields(id)
{
	var oldIP = document.getElementById("hdtxtIP"+id).value;
	var IP = document.getElementById("txtIP"+id).value;
	var username = document.getElementById("txtUser"+id).value;
	var password = document.getElementById("txtPass"+id).value;
	var internal_channel = document.getElementById("txtInt"+id).value;
	var external_channel = document.getElementById("txtExt"+id).value;
	
	//alert(id);
  var theAnswer = confirm("Do you really wants to Save Changes?");

  
if(theAnswer)
{
	location.replace("index.php?module=Configurator&action=update&ip="+IP+"&u="+username+"&p="+password+"&ic="+internal_channel+"&ec="+external_channel+"&oldip="+oldIP);
	
}
else
{
	location.reload();
}

}

function deleteField(id)
{
	//alert(id);
	var oldIP = document.getElementById("hdtxtIP"+id).value;
	//alert(oldIP);
	location.replace("index.php?module=Configurator&action=delete&oldip="+oldIP);
}

</script>

<?php



foreach($dom->childNodes as $node)
{
$data  = $node->nodeValue;
}
	  // print_r($data);
	$parts = explode('?', $data);
	//print_r($parts[0]);
	//print_r($parts);
	foreach($parts as $part)
	{
		//print_r($part);
		$new_val[] = array_filter(explode('|',$part)) ;
		//echo "<br/>";
	}
	
	//print_r($new_val[1]);
	
$result = count($new_val);
//echo $result;
$result =$result-1;

//echo $result."<br>";		 

for($i=0;$i<$result;$i++)
{
	$values[] = $new_val[$i];
	
/*
	for($j=0;$j<$result;$j++)
	{
		$val[]=$values[$j];
	}
*/
}
//print_r($values);
for($i=0;$i<$result;$i++)
{
$ip[$i] =  $values[$i][0];
}
for($i=0;$i<$result;$i++)
{
$username[$i] =  $values[$i][1];
}
for($i=0;$i<$result;$i++)
{
$password[$i] =  $values[$i][2];
}
for($i=0;$i<$result;$i++)
{
$internal_channel[$i] =  $values[$i][3];
}
for($i=0;$i<$result;$i++)
{
$external_channel[$i] =  $values[$i][4];
}


//trim($ip);
/*
echo "<script>select { width:500px }</script>";
*/


//add  vars to sugar config. need by Configurator class
global $sugar_config;
foreach ($techextension_config as $key => $value) {
	if (!isset($sugar_config[$key])) {
		$sugar_config[$key] = '';
		$GLOBALS['sugar_config'][$key] = '';
	}
}

$configurator = new Configurator();
$focus = new Administration();


$focus->retrieveSettings();
if(!empty($_POST['restore'])){
	$configurator->restoreConfig();	
}

require_once('include/Sugar_Smarty.php');
$sugar_smarty = new Sugar_Smarty();


$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
$sugar_smarty->assign('APP_LIST', $app_list_strings);

$sugar_smarty->assign('config', $configurator->config);


$host_c = '<select tabindex="5" STYLE="width: 130px" name="host_c" id= "host_c">';
$host_c .= get_select_options_with_id($app_list_strings['host_list'], $focus->host_c);
$host_c .= '</select>';
$sugar_smarty->assign('HOST_OPTIONS', $host_c);
$sugar_smarty->assign('error', $configurator->errors);

$sugar_smarty->display('custom/modules/Configurator/configurator.tpl');


if ($result!='')
{

echo "<br><br><div id='tab'><table class='table4conf'  cellspacing='15' align='center'>";
		 echo "<tr>
		<th>Asterisk IP</th>
		<th>Username</th><th>Password</th><th>Internal Channel</th><th>External Channel</th><th>Options</th></tr>";

		 echo "<tr>";
/*
$a="*";
$var=strlen($password[$i]);
//echo $var; 
 for($i=1;$i<$var;$i++)
 {
	
	 $a=$a."*";
}*/

for($i=0;$i<$result;$i++)
{
	
	
	?>
	<td align='center'>
		<div style="display:block" id='ip<?php echo $i; ?>' >
			<?php echo $ip[$i]; ?>
		</div>
		<div style="display:none" align ="center" id='ip2<?php echo $i; ?>'>
			<input type='hidden' id='hdtxtIP<?php echo $i; ?>' value='<?php echo $ip[$i];?>' />		
			<input type='text' id='txtIP<?php echo $i; ?>' value='<?php echo trim($ip[$i]);?>' />		
		</div>
	</td>
	
	
	
	<td align='center'>
		<div style="display:block" id='user<?php echo $i; ?>' >
			<?php echo $username[$i]; ?>
		</div>
		<div style="display:none" align ="center" id='user2<?php echo $i; ?>'>
		
	<input type='text' size="5" id='txtUser<?php echo $i; ?>' value='<?php echo trim($username[$i]); ?>' />		
	</div>
	</td>
		
		
	<td align='center'>
	<div style="display:block" id='pass<?php echo $i; ?>' >
	********
	</div>
	<div style="display:none" align ="center" id='pass2<?php echo $i; ?>'>
	<input type='password' size="5" id='txtPass<?php echo $i; ?>' value='<?php echo trim($password[$i]); ?>' />		
	</div>
	</td>
	
		
	<td align='center'>
	<div style="display:block" id='int<?php echo $i; ?>' >
	<?php echo $internal_channel[$i]; ?>
	</div>
	<div style="display:none" align ="center" id='int2<?php echo $i; ?>'>
	<input type='text' size="5" id='txtInt<?php echo $i; ?>' value='<?php echo trim($internal_channel[$i]); ?>' />		
	</div>
	</td>
	
	
	
	<td align='center'>
	<div style="display:block" id='ext<?php echo $i; ?>' >
	<?php echo $external_channel[$i]; ?>
	</div>
	<div style="display:none" align ="center" id='ext2<?php echo $i; ?>'>
	<input type='text' size="5" id='txtExt<?php echo $i; ?>' value='<?php echo trim($external_channel[$i]); ?>' />		
	</div>
	</td>
	 
	 <td align='center' width='15%'>
	 <div style="display:block" id='editb<?php echo $i; ?>' >
	 <input type = 'button' value='Edit' name ='edit1' class='button' onclick='showFields(<?php echo $i; ?>);' />
	 <input type='hidden' id='hiddenip<?php echo $i; ?>' value='<?php echo $ip[$i];?>' />
	 <input type = 'button' value='Delete' name ='deleteip' class='button' onclick='deleteField(<?php echo $i; ?>);' />
	 
	 </div>
	 <div style='display:none' align='center' id='saveb<?php echo $i; ?>' >
	 <input type = 'button' value='Save' name ='save1' class='button' onclick='saveFields(<?php echo $i; ?>);' />
	 </div>
	 
	 </td>
	<?php
//	echo $i;
		//for($i=0; $i<=$count-1; $i++)
/*
		 echo "<td>".$IP[$i]."</td>
		 <td>".$username[$i]."</td>
		 <td>".$password[$i]."</td>
		 <td>".$internal_channel[$i]."</td>
		 <td>".$external_channel[$i]."<td>
		 <input type = 'button' value='Edit' name ='edit1' class='button1' onclick='edit($i);'></td>";
		 echo "<script>function edit(i){var i = i;alert(i);location.replace('index.php?module=Configurator&action=configurator&ac=edit&n='+i)}</script></td>";
		 
*/

		 echo "</tr>";
		
}		
		
		
		 echo "</table></div>";

 echo "<br><br><br><br><br><br>";
}


require_once("include/javascript/javascript.php");
$javascript = new javascript();
$javascript->setFormName("ConfigureSettings");

echo $javascript->getScript();
?>
