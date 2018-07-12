<?php
include_once('../../../../../config.php');
include('logger.php') ;
//Database initialization
$sqlcon = mysql_connect($sugar_config['dbconfig']['db_host_name'],$sugar_config['dbconfig']['db_user_name'],$sugar_config['dbconfig']['db_password']);
echo $sugar_config['dbconfig']['db_name'];
//echo "hi";
$sqldb = mysql_select_db($sugar_config['dbconfig']['db_name'],$sqlcon);

	echo "Database initialization<br>";
$ContainsTeam=null;$ContainsTeamSet=null;
$DescribeCalls="DESCRIBE calls";
$ResultSetUser= mysql_query($DescribeCalls,$sqlcon);
if(mysql_num_rows($ResultSetUser))
{
	while($row = mysql_fetch_array($ResultSetUser))
	{
		if($row['Field']=="team_id")
		{
			$ContainsTeam=true;
		}
		if($row['Field']=="team_set_id")
		{
			$ContainsTeamSet=true;
		}		
	}
}

//Variable Received through PHP URL
$SourceNumber=null;$DestinationNumber=null;$TeamID=null;$TeamSetId=null;$CreatedCallID=null;$CRMUserID=null;$AccountID=null;$ParentAccount=null;$ParentID=null;$LeadID=null;$ContactID=null;$AsteriskExtension=$_GET['e'];$AsteriskUidd=$_GET['id'];$StartDate=$_GET['st'];$DurationInSec = $_GET['du'];$Caller_No = $_GET['c'];$direction = $_GET['di'];$CallRecordLink = $_GET['rl'];
$st=$_GET['st'];$at=$_GET['at'];$et=$_GET['et'];
//$StartDate=date("Y-m-d H:i:s"); 
$StartDate=$_GET['st'];

$calldispo =$_GET['calldispo'];

output('info','Received command from  Asterisk Connector to make an call entry');
output('debug','Detail received from  Asterisk Connector : Extension: '.$AsteriskExtension.' Date: '.$StartDate.'Call Duration : '.$DurationInSec.'Caller Number : '.$Caller_No.' Direction : '.$direction.' Record Link : '.$CallRecordLink.' Asterisk Unique ID : '.$AsteriskUidd);
echo "<br>Variable Received through PHP URL<br>";


	if($ContainsTeam==null || $ContainsTeamSet==null)
	{
	//Searching User Details through SQL
		$sqluserQuery="select users.id from users inner join users_cstm on users.id=users_cstm.id_c where users_cstm.phoneextension_c='".$AsteriskExtension."' and users.employee_status='Active' and users.deleted=0";
	}
	else
	{
		$sqluserQuery="select users.id,users.team_set_id,users.default_team from users inner join users_cstm on users.id=users_cstm.id_c where users_cstm.phoneextension_c='".$AsteriskExtension."' and users.status='Active' and users.deleted=0";
	}
output('debug','SQL query to search user from CRM using extension : '.$sqluserQuery);
echo 'SQL query to search user from CRM using extension : '.$sqluserQuery;
echo "<br>Searching User Details through SQL<br>";
$ResultSetUser= mysql_query($sqluserQuery,$sqlcon);
if(!mysql_num_rows($ResultSetUser))
{
    //Selecting default user(admin)
	if($ContainsTeam==null || $ContainsTeamSet==null)
	{
		$sqluserQuery="select users.id from users where users.id='1'";
	}
	else
	{
		$sqluserQuery="select users.id,users.team_set_id,users.default_team from users where users.id='1'";
	}
	
    output('warn','No user found for extension ('.$AsteriskExtension. '). Selecting default user');
    $ResultSetUser= mysql_query($sqluserQuery,$sqlcon);
    echo "<br>Selecting default user(admin)<br>";
    
}
/*
if(strlen($Caller_No)<=5)
{
	exit;
}
*/
if(mysql_num_rows($ResultSetUser))
{
	while($row = mysql_fetch_array($ResultSetUser))
	{
		$CRMUserID=$row['id'];
		if($ContainsTeam!=null || $ContainsTeamSet!=null)
		{
			$TeamID=$row['default_team'];
			$TeamSetId=$row['team_set_id'];
		}
                //$CRMPassword=$row['user_hash'];
	}
	output('debug','CRM user found for extension '.$AsteriskExtension.'has an user id : '.$CRMUserID);
	echo "<br>CRM user found for extension : ".$AsteriskExtension." has an user name : ".$CRMUserID."<br>";
}

//Searching phone detals in CRM through SQL
echo "<br>Searching phone detals in CRM through SQL<br>";
$PatternToSearch=".";
$Numbers=str_split(substr($Caller_No,-10));
        foreach($Numbers as $number)
         {
            $PatternToSearch=$PatternToSearch."*".$number."[^0-9]";
         }
$PatternToSearch=substr($PatternToSearch,0,-6);

//Searching for Contact.
$sqluserQuery="SELECT contacts.id,account_id from contacts left join accounts_contacts  on contacts.id=accounts_contacts.contact_id and accounts_contacts.deleted=0  WHERE (contacts.phone_mobile REGEXP '".$PatternToSearch."' OR contacts.phone_home REGEXP '$PatternToSearch' OR contacts.phone_work REGEXP '".$PatternToSearch."' OR contacts.phone_other REGEXP '".$PatternToSearch."' OR contacts.phone_fax REGEXP '".$PatternToSearch."') AND contacts.deleted = 0 order by date_entered desc limit 1";
echo "<br>Query for searching contact in CRM :".$sqluserQuery."<br>";
output('debug','Query for Searching number in Conatcts module: '.$sqluserQuery); 
$ResultSetUser= mysql_query($sqluserQuery,$sqlcon);
if(mysql_num_rows($ResultSetUser))
{
    while($row = mysql_fetch_array($ResultSetUser))
    {
            $ContactID=$row['id'];
            $AccountID=$row['account_id'];
    }
    output('info','Number found in contact module with contact id :'.$ContactID);
    //if
    echo "<br>Number found in contact module with contact id :".$ContactID. "and Account id :" .$AccountID. "<br>";
}
 else 
{
    //Searching for Lead
    $sqluserQuery="SELECT leads.id from leads where (leads.phone_fax REGEXP '".$PatternToSearch."' OR leads.phone_other REGEXP '".$PatternToSearch."' OR leads.phone_work REGEXP '".$PatternToSearch."' OR leads.phone_mobile REGEXP '".$PatternToSearch."' OR leads.phone_home REGEXP '".$PatternToSearch."') and deleted=0 order by date_entered desc limit 1";
    echo "<br>Query for searching leads in CRM :".$sqluserQuery."<br>";
    output('debug','Query for Searching number in Leads module: '.$sqluserQuery); 
    $ResultSetUser= mysql_query($sqluserQuery,$sqlcon);
    if(mysql_num_rows($ResultSetUser))
    {
        while($row = mysql_fetch_array($ResultSetUser))
        {
                $LeadID=$row['id'];
        }
        output('info','Number found in Lead module with Lead id :'.$LeadID);
        echo "<br>Number found in lead module with lead id :".$LeadID. "<br>";
    }
 else 
    {
        //Searching for Account
        $sqluserQuery="select accounts.id from accounts where (accounts.phone_alternate REGEXP '".$PatternToSearch."' OR accounts.phone_office REGEXP '".$PatternToSearch."' OR  accounts.phone_fax REGEXP '".$PatternToSearch."') and deleted=0  order by date_entered desc limit 1";
        echo "<br>Query for searching accounts in CRM :".$sqluserQuery."<br>";
        output('debug','Query for Searching number in Accounts module: '.$sqluserQuery); 
        $ResultSetUser= mysql_query($sqluserQuery,$sqlcon);
        if(mysql_num_rows($ResultSetUser))
        {
            while($row = mysql_fetch_array($ResultSetUser))
            {
                    $AccountID=$row['id'];
            }
            output('info','Number found in Account module with Account id :'.$AccountID);
            echo "<br>Number found in Account module with lead id :".$AccountID. "<br>";
        }
    }
}


//Gathering Call entry information 
echo "<br>Gathering Call entry information<br>";
//Formating Call Duration and status.
echo "<br>Formating Call Duration and status.<br>";
$CallDurationMinuteSec=null;
$CallStatus=null;
$CallSubject=null;



if($DurationInSec==0)
{
    $CallDurationMinuteSec='0.0';
    $CallStatus='Missed';
}
 elseif($DurationInSec<3599) 
{
    $CallDurationMinuteSec=gmdate("i.s", $DurationInSec);
    $CallStatus='Held';
   // $duration_string=$DurationInSec;

}
 else 
{
    $CallDurationMinuteSec=gmdate("H.i.s", $DurationInSec);
    $CallStatus='Held';
}


if ($calldispo=="No")
{
 $CallStatus='Not Held';
}
else if ($calldispo=="FAILED")
{
 $CallStatus='FAILED';
}
else if ($calldispo=="BUSY")
{
 $CallStatus='BUSY';
}
else 
{
 $CallStatus='Held';
}
$array_time=(explode(".",$CallDurationMinuteSec));
//Formating call time and Subject.
echo "<br>Formating call time and Subject.<br>";
echo "<br>Date Recived ".$StartDate."<br>";
$date_in_CRM_format=date('Y-m-d H:i:s',$StartDate);
echo "<br>Formated Date : ".$date_in_CRM_format."<br>";
if( $direction=="Inbound" || $direction=="inbound")
{
    $CallSubject= "Incoming Call from:".$Caller_No." to: ".$AsteriskExtension;
    $SourceNumber=$Caller_No;
    $DestinationNumber=$AsteriskExtension;
    $recordingdirection="in";
}
elseif($direction=="Outbound" || $direction=="outbound")
{
    $CallSubject= "Outgoing Call to:".$Caller_No." from: ".$AsteriskExtension;
    $SourceNumber=$AsteriskExtension;
    $DestinationNumber=$Caller_No;
    $recordingdirection="out";

}
elseif($direction=="internal")
{
	exit;
    $CallSubject= "Internal Call Between:".$Caller_No." and ".$AsteriskExtension;
    $SourceNumber=$AsteriskExtension;
    $DestinationNumber=$Caller_No;
    $recordingdirection="out";

}
else
{
    $CallSubject= "Miss Call to:".$AsteriskExtension." from: ".$Caller_No."; ".$direction='Inbound';
}
echo "<br>Details prepare for call entry. Subject : ".$CallSubject." Call time according to CRM :".$StartDate."Call duration : ".$CallDurationMinuteSec." Record link : ".$CallRecordLink." Call status :".$CallStatus." <br>";
output('debug',"Details prepare for call entry. Subject : ".$CallSubject." Call time according to CRM :".$StartDate."Call duration : ".$CallDurationMinuteSec." Record link : ".$CallRecordLink." Call status :".$CallStatus." <br>");
if($AccountID !=null)
{
    $ParentAccount='Accounts';
    $ParentID=$AccountID;
}
elseif($ContactID != null)
{
    $ParentAccount='Contacts';
    $ParentID=$ContactID;
}
elseif($LeadID != null)
{
    $ParentAccount='Leads';
    $ParentID=$LeadID;
}

$CallQuery = "INSERT INTO calls (id,name,date_entered,date_modified,modified_user_id,created_by,deleted,
    assigned_user_id,
    date_start,
    parent_type,
    parent_id,
    status,
    direction,
    outlook_id ";
if ($TeamID != null)
{
	$CallQuery=$CallQuery.",team_id,team_set_id)";
}
else
{
	$CallQuery=$CallQuery.")";
}
$CallQuery=$CallQuery."VALUES (UUID(),'".$CallSubject."',SUBTIME(now(),'7:00:0'), SUBTIME(now(),'7:00:0'),'".$CRMUserID."',
'".$CRMUserID."','0','".$CRMUserID."',SUBTIME( DATE_FORMAT('".$StartDate."','%Y-%m-%d %H:%i:%s' ),'7:00:0'),";
if($ParentAccount==null)
{
    $CallQuery=$CallQuery." NULL,NULL,";
}
 else 
{
    $CallQuery=$CallQuery."'".$ParentAccount."','".$ParentID."',";
}
 $CallQuery=$CallQuery."'".$CallStatus."', '".$direction."', '".$AsteriskUidd."'";
 if ($TeamID==null)
 {
     $CallQuery=$CallQuery.")";
 }
 else 
 {
     $CallQuery=$CallQuery." ,'".$TeamID."','".$TeamSetId."')";
 }
Echo "Call Entry Query : " . $CallQuery ;

output('debug',"Call Entry Query : " . $CallQuery);
$retval = mysql_query( $CallQuery, $sqlcon );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "<br>Call has been created in the crm. <br>";
output('info',"Call has been created in the crm.");

//Searching newly created 
$searchQuery="select id from calls where calls.name='".$CallSubject."'and date_start=SUBTIME( DATE_FORMAT('".$StartDate."','%Y-%m-%d %H:%i:%s' ),'7:00:0') and calls.direction='".$direction."' and calls.status='".$CallStatus." ' and calls.created_by='".$CRMUserID."' and calls.outlook_id='".$AsteriskUidd."' and calls.assigned_user_id ='".$CRMUserID."' and deleted=0 order by date_entered desc limit 1";
echo '<br> Search Query for CallID : '.$searchQuery.'<br>';
$ResultSetUser= mysql_query($searchQuery,$sqlcon);
    if(mysql_num_rows($ResultSetUser))
    {
        while($row = mysql_fetch_array($ResultSetUser))
        {
                $CreatedCallID=$row['id'];
        }
        output('info','Call has been found in the crm with the call id :'.$CreatedCallID);
        echo "<br>Call has been found in the crm with the call id :".$CreatedCallID. "<br>";
    }
//Enerting Custom Call Entry.
//echo "<script>alert('Call ID'"+$CreatedCallID+");</script>";


//call Recording Formate
//call Recording Formate
$CallRecordLink="http://IP/recordings/monitor/";
//$recordingdate=date("Y/m/d");
//$recod_date=date("Ymd");

$format=".wav";

$recordingdate=substr($st,0,10);
$recordingdate=str_replace("-","/",$recordingdate);

$recod_date=substr($st,0,10);
$recod_date=str_replace("-","",$recod_date);

$st=substr($st,-8);
$st=str_replace("-","",$st);

$recordingtime=$st;
$recordingdirection="exten";


//$CallRecordLink=$CallRecordLink.$recordingdate."/".$recordingdirection."-".$DestinationNumber."-".$SourceNumber."-".$recod_date."-".$recordingtime."-".$AsteriskUidd.$format;

$CallRecordLink=$CallRecordLink.$recordingdate."/".$AsteriskUidd.$format;
$CallRecordLink="";

if($CreatedCallID != null)
{
   //array_time
    output('info','Entering custom call data in for call : '.$CreatedCallID);
    $CustQuery="Insert into calls_cstm(id_c,call_duration_minute_c,call_source_c,call_destination_c,call_entrysource_c,record_c) values ('".$CreatedCallID."','".$CallDurationMinuteSec."','".$SourceNumber."','".$DestinationNumber."','Asterisk','".$CallRecordLink."')";
    echo "<br>Entering custom call data in for call : ".$CreatedCallID."Insert Query : ".$CustQuery."<br>";
    $retval = mysql_query( $CustQuery, $sqlcon );
    if(! $retval )
    {
      die('Could not enter data: ' . mysql_error());
    }
    
    echo "<br>Custom data for call has been made. <br>";
    output('info',"Custom data for call has been made.");
}
//~ output('info',"CALL-ID : ".$CreatedCallID);
//~ header("CALL-ID : ".$CreatedCallID );
//~ var_dump(headers_list());


$CalenderQuery="Insert into calls_users(id,call_id,user_id,required,accept_status,date_modified,deleted) values(UUID(),'".$CreatedCallID."','$CRMUserID','1','accept',SUBTIME(now(),'7:00:0'),'0')";
    echo "<br>Entering data for calender,Query Is :  ".$CalenderQuery."<br>";
    $retval = mysql_query( $CalenderQuery, $sqlcon );
    if(! $retval )
    {
      die('Could not enter data: ' . mysql_error());
    }
    echo "<br>Data inserted for Calender. <br>";
    output('info',"Data inserted for Calender.");







//Creating Relation with contacts
if($ContactID != null)
{
   
    output('info','Creating Relation with contacts using contact id : '.$ContactID);
    $RelationQuery="Insert into calls_contacts(id,call_id,contact_id,date_modified) values(UUID(),'".$CreatedCallID."','$ContactID',SUBTIME(now(),'7:00:0'))";
    echo "<br>Creating Relation with contacts using contact id : ".$ContactID."Relation Ship Query : ".$RelationQuery."<br>";
    $retval = mysql_query( $RelationQuery, $sqlcon );
    if(! $retval )
    {
      die('Could not enter data: ' . mysql_error());
    }
    echo "<br>Call Relation has been created with Contact. <br>";
    output('info',"Call Relation has been created with Contact.");

}
if($LeadID != null)
{
   
    output('info','Creating Relation with Leads using lead id : '.$LeadID);
    $RelationQuery="Insert into calls_leads(id,call_id,lead_id,date_modified) values(UUID(),'".$CreatedCallID."','$LeadID',SUBTIME(now(),'5:30:0'))";
    echo "<br>Creating Relation with Leads using lead id : ".$LeadID."Relation Ship Query : ".$RelationQuery."<br>";
    $retval = mysql_query( $RelationQuery, $sqlcon );
    if(! $retval )
    {
      die('Could not enter data: ' . mysql_error());
    }
    echo "<br>Call Relation has been created with Contact. <br>";
    output('info',"Call Relation has been created with Contact.");
}
echo "<br><br>";

?>
