{literal} <script type="text/javascript" src="custom/modules/Configurator/dedup_register.js"></script> {/literal}

{php}
global $sugar_config;
$reg_name = $sugar_config['asterisk_register_name'];
$reg_cnmp_name = $sugar_config['asterisk_register_cname'];
$reg_phone = $sugar_config['asterisk_register_phone'];
$reg_email = $sugar_config['asterisk_register_email'];
{/php}
<br><br>   

<form id="DedupKey" name="DedupKey" method="POST" action="" onsubmit="return validate();">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td width="100%">
        <table id="dedup_activation_info" width="100%" border="0" cellspacing="10" cellpadding="10" class="table4conf">
        <tr>
            <td align="left" colspan='' class="dataLabel" width="20%"><b>User Activation Form<b>
            </td>
			<td></td>
         </tr>
         <tr>   
            <td align="left" class="dataLabel" >{$MOD.LBL_CUSTOMER_INFO_NAME}</td>
           <td><input type="textbox" size=30 name="reg_name" value="{php}echo $reg_name;{/php}" placeholder="Enter Your Name"></td>
         </tr>
         <tr>   
            <td align="left" class="dataLabel" >{$MOD.LBL_CUSTOMER_INFO_COMPANY_NAME}</td>
           <td><input type="textbox" size=30 name="reg_comp_name" value="{php}echo $reg_cnmp_name;{/php}" placeholder="Enter Your Company Name"></td>
         </tr>
         <tr>   
            <td align="left" class="dataLabel" >{$MOD.LBL_CUSTOMER_INFO_EMAIL}</td>
           <td><input type="textbox" size=30 name="reg_email" value="{php}echo $reg_email;{/php}" placeholder="Enter Your Email Name"></td>
         </tr>
         
         <tr>   
            <td align="left" class="dataLabel" >{$MOD.LBL_CUSTOMER_INFO_NUMBER}</td>
            <td><input type="textbox" size=30 name="reg_phone" value="{php}echo $reg_phone;{/php}"  placeholder="Enter Your Phone no"></td>
        </tr>
        <tr>
             <td align="left" class="dataLabel" ></td>
            <td><br>
                {php}
                if(!empty($reg_name)){
                {/php}
                <input title="{$MOD.LBL_UPDATE}" name="update" class="button" type="submit" value="{$MOD.LBL_UPDATE}">
                {php}
                }else{
                {/php}
                <input title="{$MOD.LBL_REQUEST_KEY}" name="request_key" class="button" type="submit" value="{$MOD.LBL_REQUEST_KEY}">
                {php}
                }
                {/php}
                &nbsp;&nbsp;&nbsp;
                
                <input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}" 
                onclick="document.location.href='index.php?module=Administration&action=index'" class="button" type="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}"> 
           
           </td>
        </tr>
        
        </table>
    </td>
</td>

</table>
    
    
    
<br><br>    
<table width="100%" cellpadding="10" cellspacing="10" border="0">

<tr>
    <td width="100%">
        <table id="dedup_activation" width="100%" border="0" cellspacing="0" cellpadding="0" class="table4conf">
        <tr>
			<td align="left" class="dataLabel" width="20%"><b>License Key Detail<b>
            </td>
			<td></td>
         </tr>
         <tr>   
            <td align="left" class="dataLabel" >{$MOD.LBL_SERIAL_KEY}</td>
           <td><input type="textbox" size=30 name="serial_id" value="{$serial_id}" readonly style="background-color: gainsboro;"></td>
		   
         </tr>
         <tr>   
            <td align="left" class="dataLabel" >{$MOD.LBL_ACTIVATION_KEY}</td>
            <td><input type="textbox" size=30 name="serial_key" value="{$serial_key}" {$serial_mode} placeholder="Enter Lisence Key"></td>
			<td><font color="red"><b>Note : </b>User have to Fill Above <b>User Activation Form </b>to get Activation key from our Support,If you need any help you can ask us @ support@techextension.com</font></td>
        </tr>
        <tr>
        <td></td>
		<td ><br>
        <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" name="save"  class="button" type="submit" value=" {$APP.LBL_SAVE_BUTTON_LABEL} ">
          &nbsp;&nbsp;&nbsp;&nbsp;<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" onclick="document.location.href='index.php?module=Administration&action=index'" class="button" type="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}"> 
    </td>
</tr>
        </table>
    </td>
</td>

</table>
        

</form>
