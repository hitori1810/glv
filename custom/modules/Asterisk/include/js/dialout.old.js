var lWSC = null;
var dialNumber ='';
var UniqueID ='';
var transferTo = '';
var dialPlan= '';
initConnectServer();
$(document).ready(function () 
{

if (window.rebootaddon =="1")
{
TINY.box.show({html:'Please ask to Administarter to restart SugarCRM Asterisk Add-on..<br>',animate:true,width:400,height:70,close:false,mask:false,boxid:'success',autohide:12,top:200,left:750})


}

	
    //.asterisk_phoneNumber is the deprecated v1.x class
    $('.phone,#phone_work,#phone_other,#phone_mobile').each(function () {
        var phoneNr = $(this).text();
        phoneNr = $.trim(phoneNr); // IE needs trim on separate line.

        // Regex searches the inner html to see if a child element has phone class,
        // this prevents a given number having more then one click to dial icon.
        // ? after the " is required for IE compatibility.  IE strips the " around the class names apparently.
        // The /EDV.show_edit/ regex allows it to work with Letrium's Edit Detail View module.
        if (phoneNr.length > 1 && ( !/(class="?phone"?|id="?#phone|class="?asterisk_placeCall"?)/.test($(this).html()) || /EDV.show_edit/.test($(this).html()) )) 
        {
            var contactId = $('input[name="record"]', document.forms['DetailView']).attr('value');
            if (!contactId) 
            {
                contactId = $('input[name="mass[]"]', $(this).parents('tr:first')).attr('value');
            }
		tellJarToDoSomething(window.no_Of_users);
            if (window.PhoneExtension) 
            {
                $(this).append('<img title="Click To Call from extension is: ' + window.PhoneExtension + '" src="custom/modules/Asterisk/include/images/call.png" class= "activeCall" record="' + contactId + '" value="anrufen" style="cursor: pointer;"></div>');
                
                //$(this).append('&nbsp;&nbsp;<img title="Click To Call from extension is: ' + window.PhoneExtension + '" src="custom/modules/TECHEXTENSION/include/images/click-to-dial-calling.gif" class="asterisk_placeCall" value="anrufen" style="cursor: pointer;"/>&nbsp;');
                //$(this).append('<div title="Extension Configured for Click To Dial is: ' + window.PhoneExtension + '" class="asterisk_placeCall activeCall" record="' + contactId + '" value="anrufen"></div>');
            }
            else {
                $(this).append('&nbsp;&nbsp;<img title="No extension configured!  Go to user preferences to set your extension" src="custom/modules/Asterisk/include/images/call_noextset.gif" class="asterisk_placeCall" value="anrufen" style="cursor: pointer;"/>&nbsp;');
                //alert('Please Register your Extension First on Asterisk/Asterisk IP :'+window.AsteriskIP);
                //RegisterExtension(window.AsteriskIP);
                var a = $("<a>").attr("href", "index.php?module=Users&action=EditView&record=1");
				$(this).wrap(a);
            }


$(this).click(function () 
{

	var record = $(this).attr('record');
	//change to spinner
	$("img").find("[record='" + record + "']").removeClass('activeCall').addClass('dialedCall');

	var call = null;
	phoneNr = phoneNr.replace(/[^a-zA-Z0-9]/g,'');
	DialNumber(phoneNr);
});

            
        }

        function getModule(){
            if(window.module_sugar_grp1 == undefined){
                return getUrlParam('module');
            }else{
                return window.module_sugar_grp1;
            }
        }

        //internal function to assist in getting sugarcrm url parameters
        function getUrlParam(name) {

            var decodedUri = decodeURIComponent(window.location.href);
            var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(decodedUri);
            if (!results) {
                return null;
            } else {
                return results[1];
            }
        }
    });
});
//~ function RegisterExtension(AsteriskIP)
//~ {
	//~ alert('Please Register Your Extension on Asterisk/Asterisk IP : '+AsteriskIP);
//~ }

function tellJarToDoSomething(no)
{
setTimeout(function(){
    console.log("THIS IS");

	
	var lMessageToken =
	{
	ns: lWSC.NS,
	type: "techinfo",
	user:no,
	extension:window.PhoneExtension,
	ip:window.AsteriskIP
	};
					
	var lCallbacks = 
	{
	OnFailure: function( aToken )
	{
		alert("fail");
	}
	};
	var lRes=lWSC.sendToken( lMessageToken, lCallbacks );
console.log(lRes);
}, 2000);
}

function DialNumber(callto)
{
	if(window.DialoutPrefix =='' || !window.DialoutPrefix)
	{
		window.DialoutPrefix=null;
		//window.DialPlan='default';
	}
	if(window.DialPlan =='' || !window.DialPlan)
	{
		//window.DialPlan='SIP/';
		window.DialPlan='from-internal';
	}
	window.dialNumber = callto;
//console.log(lWSC);

var lMessageToken = 
{
	ns: lWSC.NS,
	type: "call",
	cphonenumber:callto, 
	CustomContext:window.DialPlan,
	CustomChannel:window.DialoutPrefix,
	extension:window.PhoneExtension,
	ip:window.AsteriskIP
};
					
var lCallbacks = 
{
OnFailure: function( aToken )
{
	alert("fail");
}};

	
	var lRes=lWSC.sendToken( lMessageToken, lCallbacks );
	console.log(lRes.code);
	if(lRes.code=='-1')
	{
		alert('Request cannot sent to Asterisk to make call');
	}
	
}

function HangUpCall(UniqueID)
{
	window.UniqueID = UniqueID;
			//console.log(lWSC);
			var lMessageToken =
			{
					ns: lWSC.NS,
					type: "HangUp",
					UniqueID:UniqueID, 
					extension:window.PhoneExtension,
					ip:window.AsteriskIP
			};
					
			var lCallbacks = 
			{
					OnFailure: function( aToken )
					{
								alert("fail");
					}
			};

			var lRes=lWSC.sendToken( lMessageToken, lCallbacks );

}

function TransferCall(UniqueID,transferTo,dialPlan)
{
	
	if(dialPlan =='' || !dialPlan)
	{
		//window.DialPlan='SIP/';
		CustomContext='default';
	}
	window.transferto= transferTo;
	window.dialplan= dialPlan;
	window.UniqueID= UniqueID;
			//console.log(lWSC);
			var lMessageToken =
			{
					ns: lWSC.NS,
					type: "TransferCall",
					UniqueID:UniqueID, 
					TransferTo:transferTo, 
					extension:window.PhoneExtension,
					ip:window.AsteriskIP,
					CustomContext:dialPlan,
					transfer : "null",
					identifier : "identifier"
			};
					
			var lCallbacks = 
			{
					OnFailure: function( aToken )
					{
								alert("fail");
					}
			};

			var lRes=lWSC.sendToken( lMessageToken, lCallbacks );
//console.log(lRes);
}

 function initConnectServer()
{

	if (jws.browserSupportsWebSockets())
	{
		lWSC = new jws.jWebSocketJSONClient(
				{
				});
	}
	ConnectServer();
}

function ConnectServer()
{
	var hangupId='';
	var lURL = jws.getDefaultServerURL();
	var lRes = lWSC.logon(lURL,window.PhoneExtension, window.PhoneExtension,
			{
				OnOpen: function(aEvent)
				{
				},
				OnGoodBye: function(aEvent)
				{
				},
				OnMessage: function(aToken, aEvent)
				{
					
					if(aEvent.action != undefined)
					{
						//console.log(aEvent.action);
						//console.log(aEvent);
						if(aEvent.action=="DialEvent")
						{ 
							var phoneNumber = aEvent.PhoneNumber;
							var extension = aEvent.Extension;
							
							
							var phoneLength = phoneNumber.length;
							var extensionLength = extension.length;
							console.log("index.php?entryPoint=getCallStatus&AsteriskCallID="+aEvent.uniqueid+"&PhoneNumber="+aEvent.PhoneNumber+"&CurrentRequest="+aEvent.action+"&Direction="+aEvent.Direction);
							
							
							TECHEXTENSION.checkForNewStates(aEvent.uniqueid,aEvent.PhoneNumber,aEvent.action,aEvent.Direction,aEvent.Extension,null);
						
						}
						if(aEvent.action=="CONNECTED")
						{ 
							//console.log("index.php?entryPoint=getCallStatus&AsteriskCallID="+aEvent.uniqueid+"&PhoneNumber="+aEvent.PhoneNumber+"&CurrentRequest="+aEvent.action+"&Direction="+aEvent.Direction);
							var phoneNumber = aEvent.PhoneNumber;
							var extension = aEvent.Extension;


							var phoneLength = phoneNumber.length;
							var extensionLength = extension.length;
							//console.log("index.php?entryPoint=getCallStatus&AsteriskCallID="+aEvent.uniqueid+"&PhoneNumber="+aEvent.PhoneNumber+"&CurrentRequest="+aEvent.action+"&Direction="+aEvent.Direction);
							if(phoneLength<5 && extensionLength<5 )
							{

							}
							else
							{
								if(!hangupId)
								{
								TECHEXTENSION.checkForNewStates(aEvent.uniqueid,aEvent.PhoneNumber,aEvent.action,aEvent.Direction,aEvent.Extension,null);
								}
							}
						}
						if(aEvent.action=="HANGUP")
						{
							//console.log('In Hangup');
							hangupId = hangupId;
							var phoneNumber = aEvent.PhoneNumber;
							var extension = aEvent.Extension;
							
							
							var phoneLength = phoneNumber.length;
							var extensionLength = extension.length;
							
							if(phoneLength<5 && extensionLength<5 )
							{
							
							}
							else
							{
								TECHEXTENSION.checkForNewStates(aEvent.UniqueId,aEvent.PhoneNumber,aEvent.action,aEvent.Direction,aEvent.Extension,aEvent.Duration);
							}
						}
						
						
								
						if(aEvent.action=="UnregisterExtension")
						{ 
							console.log("Unregister");
							
TINY.box.show({html:'Your Extension ( '+aEvent.Extension+' ) is Not registered at Asterisk IP<br>( '+aEvent.AsteriskIP+' ) ! Please Register your Extension in SoftPhone<br><br>NOTE : After Registration of Your Extension Please Refresh this Page  ',animate:true,width:500,height:100,close:false,mask:false,boxid:'success',autohide:12,top:200,left:750})
							//alert('Please Register Your Extension ( '+aEvent.Extension+' ) on Asterisk IP :'+aEvent.AsteriskIP);
							
							//TECHEXTENSION.checkForNewStates(null,null,aEvent.action,aEvent.AsteriskIP,aEvent.Extension,null);
						}




						if(aEvent.action=="maxUsers")
						{ 
							console.log("MaxUser");
							TINY.box.show({html:'You have Reached Maximum Users Limit '+aEvent.User+',You need to buy More User Lisence<br><br>NOTE : Please Contact Us at support@techextension.com  ',animate:true,width:500,height:100,close:false,mask:false,boxid:'success',autohide:12,top:200,left:750})

						}









						
					}
				},
				OnClose: function(aEvent)
				{
					//alert('Closed');
				}
			});

}
