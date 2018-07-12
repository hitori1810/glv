<?php
//Function to get Mac Address
function GetMacAddr($ifname = 'eth0') {
        switch (PHP_OS) {
            case 'FreeBSD':
              $command_name = "/sbin/ifconfig $ifname ";
              $condition = "/ether [0-9A-F:]*/i";
              break;
            case 'Windows':
              $command_name = "ipconfig /all ";
			  $condition = "/Physical Address [0-9A-F:]*/i";
              break;  
           default:
              $command_name = "/sbin/ifconfig $ifname  | grep HWadd";
              $condition = "/HWaddr (\S+)/i";
              break;
      } 
      $ifip = "";
      exec($command_name , $command_result);
      $ifmac = implode($command_result, "\n");        
          if (preg_match($condition, $ifmac, $match)) { 
                $match = split(" ", $match[0]);
                 $macaddr = $match[1];
                 $strip_mac = str_replace(':','',$macaddr);
                 $final_mac = strtoupper($strip_mac);
                 return $final_mac;
            } else {
                return "(none)";
        }
     } 



//function to get url & do formatting
function curPageURL() 
{
 $pgURL = getcwd();
 $final_url = str_replace("/","",$pgURL);
 return $final_url; 
}
//Concatenate Mac & URL
function macplusurl()
{
	$mac = GetMacAddr();
	$url = curPageURL();
	$diff="SolutinGuru";
	$complete_string=$mac.$url.$diff;
	return $complete_string;
}

$sum=0;
//get serail id from mac address
function getserialid($mac_address){
	
	for($i=0;$i<strlen($mac_address);$i++){
		$ch=substr($mac_address,$i,1);
		if(ctype_digit($ch)){		//for digit
			$sum += $sum + $ch * ($i * 2);
		}else if(ctype_alpha($ch)){	//for latter
			switch ($ch){
				case "A":
					$sum += $sum + 10 * ($i * 2);
					break;
				case "B":
					$sum +=$sum + 11 * ($i * 2);
					break;
				case "C":
					$sum += $sum + 12 * ($i * 2);
					break;
				case "D":
					$sum += $sum + 13 * ($i * 2);
					break;
				case "E":
					$sum += $sum + 14 * ($i * 2);
					break;
				case "F":
					$sum += $sum + 15 * ($i * 2);
					break;
				default :
					$sum += $sum + 1 * ($i * 2);	
			}
		}
	}
  return $sum;
}

 


function encrypt_serial($serialid)
{
	$encrypt_serialkey = md5($serialid);
	$final_enc_sr = substr($encrypt_serialkey,0,16);
        $upper_serial_key = strtoupper($final_enc_sr);
	return $upper_serial_key;
}




//get generate key
function GenerateKey($serial_id){
	$enc_string = md5($serial_id);
	$final_enc_str = substr($enc_string,0,16);
	$str_0 = substr($final_enc_str,0,1);
	$str_1 = substr($final_enc_str,1,1);
	$str_2 = substr($final_enc_str,2,1);
	$str_5 = substr($final_enc_str,5,1);
	$str_7 = substr($final_enc_str,7,1);
	$str_8 = substr($final_enc_str,8,1);
	$serial_key = $str_5.$str_8.$str_0.$str_2.$str_7.$str_1.$str_5.$str_1;
        $upper_serial_key = strtoupper($serial_key);
        return $upper_serial_key;
}


function checklicence()
{
    $licfile="custom/modules/Configurator/asterisksyn.txt";    
    $key_infile = file_get_contents($licfile);
    $macurlstring = macplusurl();
    $serial_id=getserialid($macurlstring);
    $enc_serial_id =  encrypt_serial($serial_id);    
    $serial_key = GenerateKey($enc_serial_id);
    if($serial_key == $key_infile)
    {
        $response = "active";
    }
 else {
       $response = "inactive";
    }
    return $response;
}

function checklicenceouter($licfile)
{
    $key_infile = file_get_contents($licfile);       
    $macurlstring = macplusurl();
    $macurlstring = str_replace("custom", "", $macurlstring);
    $serial_id=getserialid($macurlstring);
    $enc_serial_id =  encrypt_serial($serial_id);    
    $serial_key = GenerateKey($enc_serial_id);;
    if($serial_key == $key_infile)
    {
        $response = "active";
    }
 else {
       $response = "inactive";
    }
    return $response;
}

function is_connected()
{
   $is_conn = gethostbyname('www.google.com');
    if($is_conn != 'www.google.com') {
        $con_status= "connected";
    } 
    else {
        $con_status= "notconnected";
        }
    return $con_status;
}
?>
