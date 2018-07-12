<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $current_language;
//require_once("custom/modules/Asterisk/language/".$current_language.".lang.php");
require_once("custom/modules/Asterisk/language/en_us.lang.php");
  //~ ini_set('display_errors',1);
  //~ ini_set('display_startup_errors',1);

global $sugar_config;

if($_GET['action']=='DialPlan')
{
	$sqluserQuery="SELECT dial_plan_c from users_cstm where phoneextension_c=".$_GET['extension'];
		
		 $resultSet = $GLOBALS['current_user']->db->query($sqluserQuery, false); 
		while($dbrow = $GLOBALS['current_user']->db->fetchByAssoc($resultSet)) 
		{ 
			$Dial_plan=$dbrow['dial_plan_c'];    
		}
echo $Dial_plan;  
		
		exit(0);
}

if($_GET['action']=='closebox')
{
	unset($_SESSION['state']);
	unset($_SESSION['uniqueid']);
	unset($_SESSION['Direction']);
	unset($_SESSION['PhoneNumber']);
}
if($_GET['action']=='savememo')
{
		$sqluserQuery="SELECT id from calls where outlook_id = '".$_GET['call_record_id']."' and deleted = 0 order by date_modified desc limit 1";
		$callLoader = new Call(); 
		$CallResult = $callLoader->db->query($sqluserQuery);

		while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
		{ 
			$BeanModuleID=$dbrow['id'];  
		}
		//echo "Call ID is : ".$BeanModuleID;
		if(!$BeanModuleID)
		{
			echo "Please Wait till Call Ends...";
		}
		else
		{
			$callLoader->retrieve($BeanModuleID);
			$callLoader->description = $_GET['description'];
			$callLoader->save();
			echo "Call Memo Saved Succssfully in call Description...";
		}
	
}

if($_GET['action']=='CallUpdate')
{
$CurrentUserID = $GLOBALS['current_user']->id;      
    $response = build_getCalls_item_list($mod_strings,$GLOBALS['current_user']->id);
    $response_array = array();
    if (count($response) == 0) {
        print json_encode(array("."));
    } else {
        foreach ($response as $call) {

            $response_array[] = $call;
        }
        print json_encode($response_array);
		
    }
}
else
{
	if($_GET['action']=='relateBean')
	{
		if($_GET['beanModule']=='contacts')
		{
			//******************Remove Previous Relationship With Call log*****************//
			//~ $focus = new Call();
			//~ $focus->retrieve($_GET['callRecordId']);
			//~ $focus->load_relationship('contacts');
			//~ $focus->contacts->delete($focus->id, $focus->contact_id);
			//~ $focus->save();
			
			//******************Set Relationship With Contacts*****************************//
			
			$sqluserQuery="SELECT id from calls where outlook_id ='".$_GET['callRecordId']."'";
			//~ echo $sqluserQuery;
				$callLoader = new Call(); 
				$CallResult = $callLoader->db->query($sqluserQuery);
				
				while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
				{ 
					$callId=$dbrow['id'];  
				}
			
			$call_module = new Call();  
			$call_module->retrieve($callId);
			$call_module->load_relationship('contacts');
			$call_module->contacts->add($_GET['beanId']);
			$call_module->save();
			
		}
		if($_GET['beanModule']=='cases')
		{
		$CallQuery = "INSERT INTO cases_calls_c (id,date_modified ,deleted,cases_calldcf4lscases_ida,cases_call38f7lscalls_idb) VALUES (UUID(),NOW(),0,'".$_GET['beanId']."','".$_GET['callRecordId']."')";
		
				
			 $resultSet = $GLOBALS['current_user']->db->query($CallQuery, false); 		
			//INSERT INTO cases_calls_c(id,date_modified,deleted,cases_calldcf4lscases_ida,cases_call38f7lscalls_idb) VALUES 
//INSERT INTO cases_calls_c (`id`,`date_modified`,`deleted`,`cases_calldcf4lscases_ida`,`cases_call38f7lscalls_idb`)
//VALUES (UUID(),NOW(),0,'d96fc2a2-f712-4ae7-0720-52d7b75f86c7','7ff26b35-fc0f-b134-94e9-52da27c52ce6');
		}
		
		
		else
		{
			if($_GET['beanModule']=='leads')
			{
			//******************Remove Previous Relationship With Call log*****************//
			//~ $focus = new Call();
			//~ $focus->retrieve($_GET['callRecordId']);
			//~ $focus->load_relationship('leads');
			//~ $focus->contacts->delete($focus->id, $focus->lead_id);
			//~ $focus->save();
			
			
			$sqluserQuery="SELECT id from calls where outlook_id ='".$_GET['callRecordId']."'";
			//~ echo $sqluserQuery;
				$callLoader = new Call(); 
				$CallResult = $callLoader->db->query($sqluserQuery);
				
				while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
				{ 
					$callId=$dbrow['id'];  
				}
			
			//******************Set Relationship With Leads********************************//
			$call_module = new Call();  
			$call_module->retrieve($callId);
			$call_module->load_relationship('leads');
			$call_module->leads->add($_GET['beanId']);
			$call_module->save();
			}
			else
			{
				
				
				//******************Remove Previous Relationship With Call log*****************//
				//~ $call_module_delete = new Call();  
				//~ $call_module_delete->retrieve($_GET['callRecordId']);
				//~ $call_module_delete->load_relationship('accounts');
				//~ $call_module_delete->accounts->delete($call_module_delete->id, $call_module_delete->parent_id);
				//~ $call_module_delete->save();
				
				
				$sqluserQuery="SELECT id from calls where outlook_id ='".$_GET['callRecordId']."'";
			//~ echo $sqluserQuery;
				$callLoader = new Call(); 
				$CallResult = $callLoader->db->query($sqluserQuery);
				
				while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
				{ 
					$callId=$dbrow['id'];  
				}
				
				
				
				//******************Set Relationship With Accounts****************************//
				$call_module = new Call();  
				$call_module->retrieve($callId);
				$call_module->parent_type="Accounts";
				$call_module->parent_id=$_GET['beanId'];
				$call_module->save();
			}
			
		}
		
	}
	
}
    
    function build_getCalls_item_list($mod_strings,$AssignedUserID)
    {
		session_start();
		$response = array();
		$hangupStatus=false;
		$CreatedCall=null;
                 

		switch($_GET['CurrentRequest'])
		{
			case "DialEvent":
				$AsteriskCallID = $_GET['AsteriskCallID'];
				$status = "DialEvent";
				$_SESSION['state']="dial";
				$hangupStatus = false;
				$Direction = $_GET['Direction'];
				$SourceNumber = $_GET['PhoneNumber'];
				$Extension = $_GET['Extension'];
				$callDuration = "0.0";
				//Session Part
				$_SESSION['uniqueid']=$_GET['AsteriskCallID'];
				$_SESSION['Direction']=$_GET['Direction'];
				
				$_SESSION['PhoneNumber']=$_GET['PhoneNumber'];
                                
			break;
			
			case "CONNECTED":
				$AsteriskCallID = $_GET['AsteriskCallID'];
				$status = "CONNECTED";
				$_SESSION['state']="connected";
				$hangupStatus = false;
				$Direction = $_GET['Direction'];
				$SourceNumber = $_GET['PhoneNumber'];
				$Extension = $_GET['Extension'];
				$callDuration = "0.0";
				//Session Part
				$_SESSION['uniqueid']=$_GET['AsteriskCallID'];
				$_SESSION['Direction']=$_GET['Direction'];
				$_SESSION['PhoneNumber']=$_GET['PhoneNumber'];
                                



			break;
			
			case "HANGUP":
				$AsteriskCallID = $_GET['AsteriskCallID'];
				$status = "HANGUP";
				$_SESSION['state']="hangup";
				$hangupStatus = true;
				$Direction = $_GET['Direction'];
				$SourceNumber = $_GET['PhoneNumber'];
				$Extension = $_GET['Extension'];
				$DurationinBilliSeconds = $_GET['Duration'];
				if($DurationinBilliSeconds==0)
				{
					$callDuration='0.0';
					
				}
				elseif($DurationinBilliSeconds<3599) 
				{
					$callDuration=gmdate("i.s", $DurationinBilliSeconds);
				}
				else 
				{
					$callDuration=gmdate("H.i.s", $DurationinBilliSeconds);
				}
			break;
			case "Reload":
				if($_SESSION['state']=='dial')
				{
					$AsteriskCallID = $_SESSION['uniqueid'];
					$hangupStatus = false;
					$Direction = $_SESSION['Direction'];
					$SourceNumber = $_SESSION['PhoneNumber'];
					$status ="DialEvent";
					$callDuration = "0.0";

				}
				if($_SESSION['state']=='connected')
				{
					$AsteriskCallID = $_SESSION['uniqueid'];
					$hangupStatus = false;
					$Direction = $_SESSION['Direction'];
					$SourceNumber = $_SESSION['PhoneNumber'];
					$status ="CONNECTED";
					$callDuration = "0.0";

                                        // echo $_SESSION['uniqueid'];
                                       // echo  $_SESSION['Direction'];
                                       // echo  $_SESSION['PhoneNumber'];



				}

				if($_SESSION['state']=='hangup')
				{
					unset($_SESSION['state']);
					unset($_SESSION['uniqueid']);
					unset($_SESSION['Direction']);
					unset($_SESSION['PhoneNumber']);
					
				}
			break;
		  
		}
		$State=CurrentCallStatus($status,$mod_strings);
		$call_direction=CallDirection($Direction,$mod_strings);
		$beans=MakeBeanArray($SourceNumber);
		
		$callDescription = $beans[0]['description'];
		//print_r($beans);
		//exit;
		//echo $callDescription;exit;
		 $call = array(
                'id' => $AsteriskCallID,
                'asterisk_id' => $AsteriskCallID,
                'state' =>$State,
                'is_hangup' => $hangupStatus,
                'call_record_id' => $AsteriskCallID,
                'phone_number' => $SourceNumber,
                'timestamp_call' => "21",
                'title' => get_title($beans,$SourceNumber,$State,$mod_strings),
                'beans' => $beans,
                'call_type' => $call_direction['call_type'],
                'direction' => $call_direction['direction'],
                'duration' => $callDuration,
				'description' => $callDescription,
                'mod_strings' => $mod_strings['TECHEXTENSION']
            );
            $response[] = $call;
            return $response;
	
		}
	
	function CurrentCallStatus($CurrentRequest,$mod_strings)
	{
		$state=null;
			if($CurrentRequest=="DialEvent")
			   { 
				   $state = $mod_strings['TECHEXTENSION']['DIAL'];
			   }
			   if($CurrentRequest=="CONNECTED")
			   { 
				   $state = $mod_strings['TECHEXTENSION']['CONNECTED'];
			   }
			   if($CurrentRequest=="HANGUP")
			   { 
				   $state = $mod_strings['TECHEXTENSION']['HANGUP'];
			   }
			   if($CurrentRequest=="UnregisterExtension")
			   { 
				   //~ $state = "Please Register Your Extension".$extension." at IP ".$ip;
			   }
			   
			   
			   return $state;
	}
	function CallDirection($Direction, $mod_strings) 
	{
		//echo $Direction;
    $result = array();

		if ($Direction == 'inbound') {
			$result['call_type'] = $mod_strings['TECHEXTENSION']['ASTERISKLBL_COMING_IN'];
			$result['direction'] = "Inbound";
		}

		if ($Direction == 'outbound') 
		{
			$result['call_type'] = $mod_strings['TECHEXTENSION']['ASTERISKLBL_GOING_OUT'];
			$result['direction'] = "Outbound";
		}
				if ($Direction == 'internal') 
		{
			$result['call_type'] = $mod_strings['TECHEXTENSION']['ASTERISKLBL_IN'];
			$result['direction'] = "Internal";
		}
	//print_r($result);
    return $result;
	}
	
	function MakeBeanArray($PhoneNumber) 
	{	$BeanModuleID='';
		$account_id='';
		$Beanlast_name='';
		$Beanfirst_name='';
		$ParentName='';
		$BeanModuleDescription = '';
		$parentBeanModule ='';
		$callDescriptionLead="";
		$BeanModuleIDLead="";
		$BeanModuleNameAccount="";
if(strlen($PhoneNumber) >6)
{
		$tempnumber=substr($PhoneNumber,-7);
		$PhoneNumber1=substr($tempnumber,0,3)."-".substr($tempnumber,3,7);
		$PhoneNumber=$tempnumber;
		$callDescription='';
		$sqluserQuery="SELECT id from contacts where (phone_home like '%".$PhoneNumber."' or phone_mobile like '%".$PhoneNumber."' or phone_work like '%".$PhoneNumber."' or phone_other like '%".$PhoneNumber."' or phone_fax like '%".$PhoneNumber."' or phone_home like '%".$PhoneNumber1."' or phone_mobile like '%".$PhoneNumber1."' or phone_work like '%".$PhoneNumber1."' or phone_other like '%".$PhoneNumber1."' or phone_fax like '%".$PhoneNumber1."') and deleted = 0 order by date_modified desc limit 1";
		$contactLoader = new Contact(); 
		$CallResult = $contactLoader->db->query($sqluserQuery);
		while($dbrow = $contactLoader->db->fetchByAssoc($CallResult)) 
			{ 
				$BeanModuleID=$dbrow['id'];  
			}
	
		if($BeanModuleID)
		{
		$sqlCallDescription="select description from calls where parent_id = '".$BeanModuleID."'and deleted=0  order by date_entered desc limit 1";
						$callLoader = new Call(); 
						$CallResult = $callLoader->db->query($sqlCallDescription);
						
						while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
						{ 
							$callDescription=$dbrow['description']; 
						}
				$BeanModule   ='Contacts';
				$contactLoader = new Contact(); 
				$contactLoader->retrieve($BeanModuleID);
				$first_name = $contactLoader->first_name;
				$last_name = $contactLoader->last_name;
				$BeanModuleName=$first_name.' '.$last_name;
				$sqluserQuery="SELECT account_id from accounts_contacts where contact_id = '".$BeanModuleID."' and deleted = 0 order by date_modified desc limit 1";
				$AccountLoader = new Account(); 
				$CallResult = $AccountLoader->db->query($sqluserQuery);
				
				while($dbrow = $AccountLoader->db->fetchByAssoc($CallResult)) 
				{ 
					$account_id=$dbrow['account_id'];  
				}
			if($account_id)
				{
					$parentBeanModule   ='Accounts';
					$sqluserQuery="select accounts.name from accounts where accounts.id= '".$account_id."'and deleted=0  order by date_entered desc limit 1";
					$result = $AccountLoader->db->query($sqluserQuery); 
					while($dbrow = $AccountLoader->db->fetchByAssoc($result)) 
					{ 
						$ParentName=$dbrow['name'];
					}

		$sqlCallDescription="select description from calls where parent_id = '".$account_id."'and deleted=0  order by date_entered desc limit 1";
						$callLoader = new Call(); 
						$CallResult = $callLoader->db->query($sqlCallDescription);
						
						while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
						{ 
							$callDescription=$dbrow['description']; 
						}
				}
		}
		if(true)
		{
				// *****************************  new Search In Leads Again *****************************************///////////
				
				$sqluserQueryLead="SELECT id,first_name,last_name from leads where (phone_home like '%".$PhoneNumber."' or phone_mobile like '%".$PhoneNumber."' or phone_work like '%".$PhoneNumber."' or phone_other like '%".$PhoneNumber."' or phone_fax like '%".$PhoneNumber."' or phone_home like '%".$PhoneNumber1."' or phone_mobile like '%".$PhoneNumber1."' or phone_work like '%".$PhoneNumber1."' or phone_other like '%".$PhoneNumber1."' or phone_fax like '%".$PhoneNumber1."') and deleted = 0 order by date_modified desc limit 1";
				$BeanModuleLead   ='Leads';
				$leadLoader = new Lead(); 
				$CallResult = $leadLoader->db->query($sqluserQueryLead);
				while($dbrow = $leadLoader->db->fetchByAssoc($CallResult)) 
				{ 
					$BeanModuleIDLead=$dbrow['id'];  
					$Beanfirst_name=$dbrow['first_name'];  
					$Beanlast_name=$dbrow['last_name'];  
				}
				
				if($BeanModuleIDLead)
				{
					$sqluserQueryLeadDesc="select * from leads where id = '".$BeanModuleIDLead."'and deleted=0  order by date_entered desc limit 1";
					$CallResult = $leadLoader->db->query($sqluserQueryLeadDesc);
					while($dbrow = $leadLoader->db->fetchByAssoc($CallResult)) 
					{ 
						$ParentNameLead=$dbrow['account_name'];  
						$Beanfirst_nameLead=$dbrow['first_name'];  
						$Beanlast_nameLead=$dbrow['last_name'];  
					}
					$BeanModuleNameLead=$Beanfirst_nameLead.' '.$Beanlast_nameLead;
					$sqlCallDescriptionLead="select description from calls where parent_id = '".$BeanModuleIDLead."'and deleted=0  order by date_entered desc limit 1";
						$callLoader = new Call(); 
						$CallResult = $callLoader->db->query($sqlCallDescriptionLead);

						while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
						{ 
							$callDescriptionLead=$dbrow['description']; 
						}
					
				}
				
			// *****************************  End Of new Search In Leads Again *****************************************///////////	
		}
		if(true)
		{
			// *****************************  new Search In Accounts Again *****************************************///////////
						
						$BeanModuleAccount   ='Accounts';
						$sqluserQueryAccount="SELECT id,name from accounts where (phone_office like '%".$PhoneNumber."' or phone_alternate like '%".$PhoneNumber."'  or phone_fax like '%".$PhoneNumber."' or phone_office like '%".$PhoneNumber1."' or phone_alternate like '%".$PhoneNumber1."'  or phone_fax like '%".$PhoneNumber1."') and deleted = 0 order by date_modified desc limit 1";
						$acountLoader = new Account(); 
						$CallResult = $acountLoader->db->query($sqluserQueryAccount);

						while($dbrow = $acountLoader->db->fetchByAssoc($CallResult)) 
						{ 
							$BeanModuleIDAccount=$dbrow['id']; 
							$ParentNameAccount=$dbrow['name'];  
						}
						$BeanModuleNameAccount=$ParentNameAccount;
						$sqlCallDescriptionAccount="select description from calls where parent_id = '".$BeanModuleIDAccount."'and deleted=0  order by date_entered desc limit 1";
						$callLoader = new Call(); 
						$CallResult = $callLoader->db->query($sqlCallDescriptionAccount);
						while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
						{ 
							$callDescriptionAccount=$dbrow['description']; 
						}
						
		// ***************************** End Of new Search In Accounts Again *****************************************///////////
		}
}
else
{
//paste that code here


		$callDescription='';
	$sqluserQuery="SELECT id from contacts where (phone_home = '".$PhoneNumber."' or phone_mobile  ='".$PhoneNumber."' or phone_work  ='".$PhoneNumber."' or phone_other ='".$PhoneNumber."' or phone_fax ='".$PhoneNumber."') and deleted = 0 order by date_modified desc limit 1";
			//~ echo $sqluserQuery;exit;
				$contactLoader = new Contact(); 
				$CallResult = $contactLoader->db->query($sqluserQuery);
				
				while($dbrow = $contactLoader->db->fetchByAssoc($CallResult)) 
				{ 
					$BeanModuleID=$dbrow['id'];  
				}
		if($BeanModuleID)
		{
		$sqlCallDescription="select description from calls where parent_id = '".$BeanModuleID."'and deleted=0  order by date_entered desc limit 1";
		$callLoader = new Call(); 
		$CallResult = $callLoader->db->query($sqlCallDescription);

		while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
		{ 
			$callDescription=$dbrow['description']; 
		}
				$BeanModule   ='Contacts';
				$contactLoader = new Contact(); 
				$contactLoader->retrieve($BeanModuleID);
				$first_name = $contactLoader->first_name;
				$last_name = $contactLoader->last_name;
				$BeanModuleName=$first_name.' '.$last_name;
				$sqluserQuery="SELECT account_id from accounts_contacts where contact_id = '".$BeanModuleID."' and deleted = 0 order by date_modified desc limit 1";
				$AccountLoader = new Account(); 
				$CallResult = $AccountLoader->db->query($sqluserQuery);
				
				while($dbrow = $AccountLoader->db->fetchByAssoc($CallResult)) 
				{ 
					$account_id=$dbrow['account_id'];  
				}
				
				if($account_id)
				{
						$parentBeanModule   ='Accounts';
						$sqluserQuery="select accounts.name from accounts where accounts.id= '".$account_id."'and deleted=0  order by date_entered desc limit 1";
						$result = $AccountLoader->db->query($sqluserQuery); 
						while($dbrow = $AccountLoader->db->fetchByAssoc($result)) 
						{ 
							$ParentName=$dbrow['name'];
						}
				if(!$callDescription)
					{
							$sqlCallDescription="select description from calls where parent_id = '".$account_id."'and deleted=0  order by date_entered desc limit 1";
							$callLoader = new Call(); 
							$CallResult = $callLoader->db->query($sqlCallDescription);
							
							while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
							{ 
								$callDescription=$dbrow['description']; 
							}
					}
				}
		}
		else
		{
				//Searching in Leads
				$sqluserQuery="SELECT id,first_name,last_name from leads where (phone_home='".$PhoneNumber."' or phone_mobile='".$PhoneNumber."' or phone_work='".$PhoneNumber."' or phone_other='".$PhoneNumber."' or phone_fax='".$PhoneNumber."') and deleted = 0 order by date_modified desc limit 1";
				$BeanModule   ='Leads';
				$leadLoader = new Lead(); 
				$CallResult = $leadLoader->db->query($sqluserQuery);
				
				while($dbrow = $leadLoader->db->fetchByAssoc($CallResult)) 
				{ 
					$BeanModuleID=$dbrow['id'];  
					$Beanfirst_name=$dbrow['first_name'];  
					$Beanlast_name=$dbrow['last_name'];  
				}
		
					if(!$BeanModuleID)
					{
						
						
						//Searching in accounts
						$BeanModule   ='Accounts';
						$sqluserQuery="SELECT id,name from accounts where (phone_office='".$PhoneNumber."' or phone_alternate='".$PhoneNumber."'  or phone_fax='".$PhoneNumber."') and deleted = 0 order by date_modified desc limit 1";
						$acountLoader = new Account(); 
						$CallResult = $acountLoader->db->query($sqluserQuery);
						
						while($dbrow = $acountLoader->db->fetchByAssoc($CallResult)) 
						{ 
							$BeanModuleID=$dbrow['id']; 
							$ParentName=$dbrow['name'];  
						}

						$BeanModuleName=$ParentName;
						
					if(!$callDescription)
				{
		$sqlCallDescription="select description from calls where parent_id = '".$BeanModuleID."'and deleted=0  order by date_entered desc limit 1";
						$callLoader = new Call(); 
						$CallResult = $callLoader->db->query($sqlCallDescription);
						
						while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
						{ 
							$callDescription=$dbrow['description']; 
						}
						}
					
					}
			else
			{
				$sqluserQuery="select * from leads where id = '".$BeanModuleID."'and deleted=0  order by date_entered desc limit 1";
				$CallResult = $leadLoader->db->query($sqluserQuery);

				while($dbrow = $leadLoader->db->fetchByAssoc($CallResult)) 
				{ 
				$ParentName=$dbrow['account_name'];  
				$Beanfirst_name=$dbrow['first_name'];  
					$Beanlast_name=$dbrow['last_name'];  
				}
				$BeanModuleName=$Beanfirst_name.' '.$Beanlast_name;
					
		if(!$callDescription)
				{
		$sqlCallDescription="select description from calls where parent_id = '".$BeanModuleID."'and deleted=0  order by date_entered desc limit 1";
						$callLoader = new Call(); 
						$CallResult = $callLoader->db->query($sqlCallDescription);
						
						while($dbrow = $callLoader->db->fetchByAssoc($CallResult)) 
						{ 
							$callDescription=$dbrow['description']; 
						}
						}
			}
		}

}
       
$popupDetectFlag = "0";
		$beans = array();
		if($BeanModuleID)
		{
			if(!$ParentName)
			{
				$ParentName = 'No Parent Account';
			}
			if(!$BeanModuleName or $BeanModuleName==' ')
			{
				$BeanModuleName = 'Unknown For CRM';
			}
			$bean = array(
					'bean_module' => $BeanModule,
					'bean_id' =>     $BeanModuleID,
					'bean_name' => 	$BeanModuleName." - Student",
					'bean_description' => $BeanModuleDescription,
					'bean_link' => build_link($BeanModule,$BeanModuleID),
					'parent_name' => $ParentName,
					'parent_module' => $parentBeanModule,
					'description' => $callDescription,
					'parent_id' => $account_id,
					'parent_link' => build_link($parentBeanModule,$account_id)
				);
				$popupDetectFlag = "1";
			$beans[] = $bean;
		}
		if($BeanModuleIDLead)
		{
		
			if(!$ParentNameLead)
			{
				$ParentNameLead = 'No Parent Account';
			}
			if(!$BeanModuleNameLead or $BeanModuleNameLead==' ')
			{
				$BeanModuleNameLead = 'Unknown For CRM';
			}
			$beanLead = array(
				'bean_module' => $BeanModuleLead,
				'bean_id' =>     $BeanModuleIDLead,
				'bean_name' => 	$BeanModuleNameLead." - Lead",
				'bean_description' => $BeanModuleDescription,
				'bean_link' => build_link($BeanModuleLead,$BeanModuleIDLead),
				'parent_name' => $ParentNameLead,
				'parent_module' => "",
				'description' => $callDescriptionLead,
				'parent_id' => $account_id,
				'parent_link' => build_link($parentBeanModule,$account_id)
			);
		$popupDetectFlag = "1";
		$beans[] = $beanLead;
		}
		
		if($BeanModuleIDAccount)
		{
		
			if(!$ParentNameAccount)
			{
				$ParentNameAccount = 'No Parent Account';
			}
			if(!$BeanModuleNameAccount or $BeanModuleNameAccount==' ')
			{
				$BeanModuleNameAccount = 'Unknown For CRM';
			}
			$beanLead = array(
				'bean_module' => $BeanModuleAccount,
				'bean_id' =>     $BeanModuleIDAccount,
				'bean_name' => 	$BeanModuleNameAccount." - Account",
				'bean_description' => $BeanModuleDescription,
				'bean_link' => build_link($BeanModuleAccount,$BeanModuleIDAccount),
				'parent_name' => $ParentNameAccount,
				'parent_module' => "",
				'description' => $callDescriptionAccount,
				'parent_id' => $account_id,
				'parent_link' => build_link($parentBeanModule,$account_id)
			);
		$popupDetectFlag = "1";
		$beans[] = $beanLead;
		}
		
		if($popupDetectFlag == "0")
		{
		
		if(!$ParentNameAccount)
			{
				$ParentNameAccount = 'No Parent Account';
			}
			if(!$BeanModuleNameAccount or $BeanModuleNameAccount==' ')
			{
				$BeanModuleNameAccount = 'Unknown For CRM';
			}
			$beanEmpty = array(
				'bean_module' => $BeanModuleAccount,
				'bean_id' =>     $BeanModuleIDAccount,
				'bean_name' => 	$BeanModuleNameAccount,
				'bean_description' => $BeanModuleDescription,
				'bean_link' => build_link($BeanModuleAccount,$BeanModuleIDAccount),
				'parent_name' => $ParentNameAccount,
				'parent_module' => "",
				'description' => $callDescriptionAccount,
				'parent_id' => $account_id,
				'parent_link' => build_link($parentBeanModule,$account_id)
			);
			$beans[] = $beanEmpty;
		}
		
    
	//$beans[] = "";
    return $beans;
}
function build_link($moduleName, $id) 
{
    global $sugar_config;
    if( !empty($moduleName) && !empty($id) ) {
        $moduleName = ucfirst($moduleName);
        return $sugar_config['site_url'] . "/index.php?module=$moduleName&action=DetailView&record={$id}";
    }
    return null;
}
function get_title($beans, $phone_number, $state, $mod_strings) {
    switch (count($beans)) {
        case 0:
        
            $title = $phone_number;
            break;

        case 1:
			if($title = $beans[0]['bean_name'] != "")
			{
				$title = $beans[0]['bean_name'];
			}
			else
			{
				$title = $phone_number;
			}
            break;

        default:
            $title = $mod_strings['TECHEXTENSION']['ASTERISKLBL_MULTIPLE_MATCHES'];
            break;
    }
    $title = $state;

    // Limit title length (to prevent X from overflowing)
    // TODO: handle this with CSS instead
    if(strlen($title) > 24) {
        $title = substr($title,0,24) . "...";
    }


    return $title;
}

?>
