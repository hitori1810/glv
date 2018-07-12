
<script type='text/javascript' src='include/javascript/overlibmws.js'></script>
<BR>
<form name="ConfigureSettings" enctype='multipart/form-data' method="GET" action="index.php" onSubmit="return (add_checks(document.ConfigureSettings) && check_form('ConfigureSettings'));">
<input type='hidden' name='action' value='configurator'/>
<input type='hidden' name='module' value='Configurator'/>
<input type='hidden' name='action' value='add'/>
<span class='error'>{$error.main}</span>




<style type="text/css">
{literal}
.style5 {font-family: Verdana, Arial, Helvetica, sans-serif; color: red; font-size: 10px; }
{/literal}
</style>
	
	
	
	
<script type="text/javascript">

{literal}
function addip()
{
var asteriskip= document.getElementById('asteriskip').value;
var lable= document.getElementById('lableip').value;
var frm=document.setting;		
if(asteriskip=='')
			   {
			  // alert('in empty ip');
				   document.getElementById("ip").style.display="block";
				   frm.asteriskip.focus();
				   return false;
			   }
			   
			   
if(lable=='')
			   {
				   document.getElementById("lable").style.display="block";
				   frm.lableip.focus();
				   return false;
			   }



document.location.replace('index.php?module=Configurator&action=add_asteriskip&asteriskip='+asteriskip+'&lable='+lable);
}



function add_domain()
{

var domain= document.getElementById('domain').value;
if(domain=='')
			   {
				   document.getElementById("dom").style.display="block";
				   frm.domain.focus();
				   return false;
			   }

document.location.replace('index.php?module=Configurator&action=adddomain&domain='+domain);
}

function addproduct()
	{
		var select_product= document.getElementById('product').value;
		//alert(select_product);
		
		if(select_product=='asterisk')
			{
				//document.getElementById('dbusername').style.display = 'none';
				document.getElementById('dbuserspan').style.display = 'none';

				//document.getElementById('dbpassword').style.display = 'none';
				document.getElementById('dbpassspan').style.display = 'none';

				//document.getElementById('dbname').style.display = 'none';
				document.getElementById('dbnamespan').style.display = 'none';
				document.getElementById('product_div').style.display = 'none';
				
				
			}
		else if(select_product=='asterisk')
			{
				//document.getElementById('dbusername').style.display = 'block';
				document.getElementById('dbuserspan').style.display = 'block';

			//	document.getElementById('dbpassword').style.display = 'block';
				document.getElementById('dbpassspan').style.display = 'block';

				//document.getElementById('dbname').style.display = 'block';
				document.getElementById('dbnamespan').style.display = 'block';
				document.getElementById('product_div').style.display = 'none';
			}
		else if(select_product=='none')
			{
				document.getElementById('product_div').style.display = 'block';
				
				
				
			}
		
			
	}



function update() {



var host= document.getElementById('asteriskip').value;
var uname= document.getElementById('asterisk_username').value;
var pass= document.getElementById('asterisk_password').value;
var ichannel= document.getElementById('internal_channel').value;
var echannel= document.getElementById('external_channel').value;
//var select_product= document.getElementById('product').value;

//var d= document.getElementById('dbname').value;
//var du= document.getElementById('dbusername').value;
//var dp= document.getElementById('dbpassword').value;
//alert(d);

//alert(select_product);
			  
//if(select_product=='none')
	//		   {
		//		  document.getElementById('product_div').style.display = 'block';
			//	  return false;
			  // }
			   
//if(select_product=='asterisk')
	//		   {
		//		 var d= document.getElementById('dbname').value;
			//	 d='none';
				// 
				 //var du= document.getElementById('dbusername').value;
				 //du='none';
				 //var dp= document.getElementById('dbpassword').value;
				 //dp='none';
			   //}


			   
//alert(uname);
if(host=='')
			   {
				  document.getElementById("host").style.display="block";
				  asteriskip.focus();
				  return false;
			   }


if(uname=='')
			   {
				  document.getElementById("username").style.display="block";
				  asterisk_username.focus();
				  return false;
			   }
if(pass=='')
			   {
				  document.getElementById("password").style.display="block";
				  asterisk_password.focus();
				  return false;
			   }

if(ichannel=='')
			   {
				  document.getElementById("internalchannel").style.display="block";
				  internal_channel.focus();
				  return false;
			   }
if(echannel=='')
			   {
				  document.getElementById("externalchannel").style.display="block";
				  external_channel.focus();
				  return false;
			   }
			   
			   
/*			   
if(d=='')
	   {
		  document.getElementById("dbnamediv").style.display="block";
		  dbname.focus();
		  return false;
	   }
	   
if(du=='')
	   {
		  document.getElementById("dbuserdiv").style.display="block";
		  dbusername.focus();
		  return false;
	   }
	   
if(dp=='')
	   {
		  document.getElementById("dbpassdiv").style.display="block";
		  dbpassword.focus();
		  return false;
	   }
*/

	d='none',du='none',dp='none';
			   
			  document.location.replace('index.php?module=Configurator&action=add&h='+host+'&u='+uname+'&p='+pass+'&i='+ichannel+'&e='+echannel+'&d='+d+'&du='+du+'&dp='+dp);
}


{/literal}	
</script>


{*
<table width="100%" border="1" cellspacing="0" cellpadding="0" colspan="2" class="table4conf">
<tr><td><b>Select Your Product</b><div id="product_div" style="display:none" align="center" class="style5">Please Select Product</div></td></tr>
<tr>
	<td>
		Select your product&nbsp;&nbsp;&nbsp;&nbsp;<select STYLE="width: 130px" id='product' name='product' onchange='addproduct()'><option value="none">Select Server Type</option> <option value="asterisk"> Asterisk Server</option> <option value="asterisk">Asterisk Server</option></select>&nbsp;&nbsp;&nbsp;
	</td>
</tr>

</table>

*}

<table  width="100%" border="1" cellspacing="10" cellpadding="10" colspan="2" class="table4conf">
<form name="setting">
<tr><td><b>Configuration to add Asterisk Server<b></td></tr>

</td>
<tr>
<td width="40%">

*Enter SugarCRM URL&nbsp;

&nbsp;&nbsp;&nbsp;<input type='text' id='domain'/>


<input type='button' name='adddomain' value='Save' onclick='add_domain()'></td>
<div id="dom" style="display:none"  class="style5">* Enter CRM URL</div>
</tr>

<tr>

<td class="style5">NOTE : Enter CRM URL Without http:// </td>
</tr>
</td>
</tr>

</table>

<br><br>
<table width="100%" border="1" cellspacing="10" cellpadding="10" colspan="4" class="table4conf">
	
	<tr><td td colspan="4"><b>Asterisk Configuration<b></td></tr>
	
	<tr>
	<td>*Enter Asterisk IP</td>
	<td colspan="4"><input type="text" name='asteriskip' id='asteriskip' size="20" placeholder="192.168.1.43">
	<div id="host" style="display:none" align="center" class="style5">* Please Enter IP</div>
	</td>
		</tr>
	
	<tr>
	<td>*Asterisk Manager Username</td>
	<td colspan="4"><input type="text" name='asterisk_username' id = 'asterisk_username' placeholder="admin" size="20">
		<div id="username" style="display:none" align="center" class="style5">* Enter Asterisk Manager Username</div>
	</td>
	</tr>
	
	<tr>
	<td>*Asterisk Manager Password</td>
	<td colspan="4">
		<input type="password" name='asterisk_password' id = 'asterisk_password' placeholder="******" size="20" >
		<div id="password" style="display:none" align="center" class="style5">* Enter Asterisk Manager Password</div>
	</td>
	</tr>
	<tr>
	<td>*Asterisk Internal Channel</td>
	<td colspan="4">
		<select STYLE="width: 130px" id='internal_channel' name='internal_cahnnal'>
			<option value="">Select</option> 
			<option value="SIP">SIP</option> 
			<option value="DAHDI">DAHDI</option> 
			<option value="ZAP">Zap</option> 
			<option value="LOCAL">Local</option> 
			<option value="IAX">IAX</option>
		</select>
		<div id="internalchannel" style="display:none" align="center" class="style5">* Select Your Internal Channel</div>
	</td>
	</tr>
	
	<tr>
	<td>*Asterisk External Channel</td>
	<td colspan="4">
		<select STYLE="width: 130px" id='external_channel' name='external_cahnnal' > 
			<option value="">Select</option> 
			<option value="SIP">SIP</option> 
			<option value="DAHDI">DAHDI</option>
			<option value="ZAP">Zap</option> 
			<option value="LOCAL">Local</option>
			 <option value="IAX">IAX</option>
		</select>
		<div id="externalchannel" style="display:none" align="center" class="style5">*Select Your External Channel</div>
		</td>
	</tr>
	
	
	
{*
		<tr id='dbnamespan'>
		<td>
		*Database Name
		</td>
		<td colspan="4">
		<input type='text' name='dbname' id='dbname' size="20">
		<div id="dbnamediv" style="display:none" align="center" class="style5">* Enter Database Name</div></span>
		</td>
		</tr>
		<tr id='dbuserspan'>
		<td>*Database Username</td>
		<td><input type='text' name='dbusername' id='dbusername' size="20">
		<div id="dbuserdiv" style="display:none" align="center" class="style5">* Enter Database Username</div>
		</td>
		</tr>
		<tr id='dbpassspan'>
		<td>*Database Password</td>
		<td><input type='text' name='dbpassword' id='dbpassword' size="20">
		<div id="dbpassdiv" style="display:none" align="center" class="style5">* Enter Database Password</div>
		</td>
		</tr>
*}

	<tr>
	<td align="right">
		<input type='button' value='Save' onclick='update()' >
	</td>	
	<td style="padding-left:10px" colspan="4">
		<input type='reset' value='Clear'>
	</td>
	</tr>
	
<tr><td colspan="4"> *<span  class="style5"> Indicate mandatory fields</td></tr>	
	</table>



{$JAVASCRIPT}

