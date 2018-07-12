
var TECHEXTENSION = {
    nextHeight : '0',
    callboxFocus : [],
    newMessages : [],
    callBoxes : [],
    sugarUserID : window.current_user_id,
    phoneExtension : window.PhoneExtension,
    
    showHoldButton : window.CallHold,
    showHangupButton : window.CallHangup,
    showTransferButton : window.CallTransfer,
    filteredCallStates : [''], //['Ringing'], 

   checkForNewStates : function(AsteriskCallID,PhoneNumber,CurrentRequest,Direction,Extension,Duration){
        // Note: once the user gets logged out, the ajax requests will get redirected to the login page.
        // Originally, the setTimeout method was in this method.  But, no way to detect the redirect without server side
        // changes.  See: http://stackoverflow.com/questions/199099/how-to-manage-a-redirect-request-after-a-jquery-ajax-call
        // So, now I only schedule a setTimeout upon a successful AJAX call.  The only downside of this is if there is a legit reason
        // the call does fail it'll never try again..
        //TECHEXTENSION.log("index.php?entryPoint=getCallStatus&AsteriskCallID="+AsteriskCallID+"&PhoneNumber="+PhoneNumber+"&CurrentRequest="+CurrentRequest+"&Direction="+Direction+"&Extension="+Extension+"&RecordLink="+RecordLink);
        $.ajax({
            url:"index.php?entryPoint=getCallStatus&action=CallUpdate&AsteriskCallID="+AsteriskCallID+"&PhoneNumber="+PhoneNumber+"&CurrentRequest="+CurrentRequest+"&Direction="+Direction+"&Extension="+Extension+"&Duration="+Duration,
            cache: false,
            type: "GET",
            success: function(data){
				console.log("index.php?entryPoint=getCallStatus&action=CallUpdate&AsteriskCallID="+AsteriskCallID+"&PhoneNumber="+PhoneNumber+"&CurrentRequest="+CurrentRequest+"&Direction="+Direction+"&Extension="+Extension+"&Duration="+Duration);
                TECHEXTENSION.log(data);
                data = $.parseJSON(data);
                var callboxids = [];

                //if the loop variable is true then setup the loop, if it is false then don't, because a one-time refresh was called'
                //~ if(loop){
                    //~ setTimeout('TECHEXTENSION.checkForNewStates(true)', TECHEXTENSION.pollRate);
                //~ }
                TECHEXTENSION.log(data);
                TECHEXTENSION.log('start render' + Date(Date.now() * 1000));
                if( data != ".") {
                    $.each(data, function(entryIndex, entry){
                        if(TECHEXTENSION.callStateIsNotFiltered(entry)){
                            if(entry['asterisk_id']!=null)
							{
								var callboxid = TECHEXTENSION.getAsteriskID(entry['asterisk_id']);
								callboxids.push(callboxid);
						    }

if(CurrentRequest=="HANGUP")
{
	var callboxid = TECHEXTENSION.getAsteriskID(entry['asterisk_id']);
	TECHEXTENSION.saveMemo(callboxid, entry['call_record_id'], entry['phone_number'], entry['direction']);  
}



                            if(TECHEXTENSION.callBoxHasNotAlreadyBeenCreated(callboxid)) {
								//console.log('call box going to create');
                                TECHEXTENSION.createCallBox(callboxid, entry);
                                //console.log('call box going to created');
                                TECHEXTENSION.log('create');
								TECHEXTENSION.updateCallBox(callboxid, entry);
                            }
                            else {
								 //console.log('call box going to update');
                                TECHEXTENSION.updateCallBox(callboxid, entry);
                               
                                //TECHEXTENSION.log('update');
                                //console.log('call box going to updated');
                            }
                        }
                    });
                }

                TECHEXTENSION.wasCallBoxClosedInAnotherBrowserWindow(callboxids);
            },
            error: function (jqXHR, textStatus, thrownError){
				// console.log('Error In Opening box : '+thrownError);
                TECHEXTENSION.log('There is a problem with getJSON in checkForNewStates()');
            }
        });
    },

    // CREATE
    
    createCallBox : function (callboxid, entry, modstrings) {
       if($('#callbox_'+callboxid).attr('id') == undefined){
           var html;
           var template = '';

           if( false ) {
               TECHEXTENSION.log("WARNING: TECHEXTENSION Developer Mode is enabled, this should not be used in production!");
               var source   = $("#handlebars-dev-template").html();
               template = Handlebars.compile(source);
           }
           else {
               template = Handlebars.templates['call-template.html'];
           }

            // Creates the modstrings needed by the template
            var context = {
                callbox_id : 'callbox_' + callboxid,
                title : entry['title'],
                asterisk_state : entry['state'],
                call_type : entry['call_type'],
                duration : entry['duration'] ,
				description : entry['description'] ,
                phone_number: entry['phone_number'],
                caller_id: entry['caller_id'],
                call_record_id: entry['call_record_id'],
                select_contact_label: entry['mod_strings']['ASTERISKLBL_SELECTCONTACT'],
                select_account_label: entry['mod_strings']['ASTERISKLBL_SELECTACCOUNT'], // Removed
                name_label: entry['mod_strings']['ASTERISKLBL_NAME'],
                company_label: entry['mod_strings']['ASTERISKLBL_COMPANY'],
                create_label: entry['mod_strings']['CREATE'],
                relate_to_label: entry['mod_strings']['RELATE_TO'],
                caller_id_label: entry['mod_strings']['ASTERISKLBL_CALLERID'],
                phone_number_label: entry['mod_strings']['CALL_DESCRIPTION_PHONE_NUMBER'],
                duration_label: entry['mod_strings']['ASTERISKLBL_DURATION'],
				description_label: entry['mod_strings']['ASTERISKLBL_DESCRIPTION'],
                save_label: entry['mod_strings']['SAVE'],
                create_new_contact_label : entry['mod_strings']['CREATE_NEW_CONTACT'],
                create_new_account_label : entry['mod_strings']['CREATE_NEW_ACCOUNT'],
                open_new_account_label   : entry['mod_strings']['OPEN_NEW_ACCOUNT'],
                open_new_lead_label   : entry['mod_strings']['OPEN_NEW_LEAD'],
                open_new_contact_label   : entry['mod_strings']['OPEN_NEW_CONTACT'],
                
                relate_to_contact_label : entry['mod_strings']['RELATE_TO_CONTACT'],
                relate_to_account_label : entry['mod_strings']['RELATE_TO_ACCOUNT'],
                //relate_to_case_label : entry['mod_strings']['RELATE_TO_CASE'],
                
                

                block_label: entry['mod_strings']['BLOCK'],
                block_number_label : entry['mod_strings']['BLOCK_NUMBER'],
                create_new_lead_label : entry['mod_strings']['CREATE_NEW_LEAD'],
				create_new_schedule_label : entry['mod_strings']['CREATE_NEW_SCHEDULE'],
				create_new_task_label : entry['mod_strings']['CREATE_NEW_TASK'],
                create_new_case_label : entry['mod_strings']['CREATE_NEW_CASE'],
                relate_to_lead_label : entry['mod_strings']['RELATE_TO_LEAD']
            };

            var numMatches = 0;
            if( entry['beans'] != null ) {
                numMatches = entry['beans'].length;
            }
            TECHEXTENSION.log("Matches: " + numMatches);

            switch(numMatches){
                case 0 :
                    html = template(context);
                   // console.log(html);
                    $('body').append(html);
                    TECHEXTENSION.setupHandlebarsContextNoMatchingCase(callboxid, context, entry);
                    $('#callbox_'+callboxid).find('.nomatchingcontact').show();
                    break;

                case 1 :
                    context = TECHEXTENSION.setupHandlebarsContextForSingleMatchingCase(callboxid, context, entry);
                    html = template(context);
                    $('body').append(html);
                    TECHEXTENSION.bindOpenPopupSingleMatchingContact(callboxid, entry);
                    $('#callbox_'+callboxid).find('.singlematchingcontact').show();
                    // TODO this should set the account display right away if it's available instead of waiting till 2nd poll refresh
                    break;

                default : // Matches > 1
                    context = TECHEXTENSION.setupHandlebarsContextForMultipleMatchingCase(callboxid, context, entry);
                    html = template(context);
                    $('body').append(html);
                    TECHEXTENSION.bindSetBeanID(callboxid, entry);
                    $('#callbox_'+callboxid).find('.multiplematchingcontacts').show();
                    break;
            }


            TECHEXTENSION.bindActionDropdown(callboxid,entry);

		if( this.showTransferButton ) 
		{
			this.bindTransferButton(callboxid,entry);
		}
		if( this.showHoldButton ) 
		{
			this.bindHoldButton(callboxid,entry);
		}	
		if( this.showHangupButton ) 
		{
			this.bindHangupButton(callboxid,entry);
		}	
            //bind user actions
            TECHEXTENSION.bindCheckCallBoxInputKey(callboxid, entry['call_record_id'], entry['phone_number'], entry['direction']);
            TECHEXTENSION.bindCloseCallBox(callboxid, entry['call_record_id']);
            TECHEXTENSION.bindToggleCallBoxGrowth(callboxid);
            TECHEXTENSION.bindSaveMemo(callboxid, entry['call_record_id'], entry['phone_number'], entry['direction']);

            //draw
            TECHEXTENSION.showCallerIDWhenAvailable(entry);
            TECHEXTENSION.minimizeExistingCallboxesWhenNewCallComesIn();
            TECHEXTENSION.startVerticalEndVertical(callboxid);  //procedurally this must go after minimizeExistingCallboxesWhenNewCallComesIn
            TECHEXTENSION.checkMinimizeCookie(callboxid);
            TECHEXTENSION.setupCallBoxFocusAndBlurSettings(callboxid);
            TECHEXTENSION.setCallBoxHeadColor(callboxid, entry);

            TECHEXTENSION.checkForErrors(entry);

            $('.callbox').show();
            $("#callbox_"+callboxid).show();
           //console.log('finished render ' + Date(Date.now() * 1000));
       }
    },
    
    // UPDATE
    
    updateCallBox : function (callboxid, entry){
        $("#callbox_"+callboxid).find('.callboxtitle').text(entry['title']);
        $("#callbox_"+callboxid).find('.phone_number').text(entry['phone_number']); // Needed for AMI v1.0, outbound calls.

        TECHEXTENSION.setCallBoxHeadColor(callboxid, entry);
        TECHEXTENSION.setTransferButton(callboxid,entry);
        TECHEXTENSION.setHangupButton(callboxid,entry);
        TECHEXTENSION.setHoldButton(callboxid,entry);
				
        $(".call_duration", "#callbox_"+callboxid+" .callboxcontent").text( entry['duration'] ); // Updates duration

        TECHEXTENSION.refreshSingleMatchView(callboxid, entry);
        
    },
    
    // CLEANUP
    
    wasCallBoxClosedInAnotherBrowserWindow : function  (callboxids){
        for(var i=0; i < TECHEXTENSION.callBoxes.length; i++ ) {
            if( -1 == $.inArray(TECHEXTENSION.callBoxes[i], callboxids) ) {
                if( TECHEXTENSION.callboxFocus[i]) {
                // Don't auto close the callbox b/c there is something entered or it has focus.
                }
                else {
                    TECHEXTENSION.closeCallBox( TECHEXTENSION.callBoxes[i] );
                    TECHEXTENSION.restructureCallBoxes();
                    TECHEXTENSION.callBoxes.splice(i,1); // todo is callBoxes.length above evaluated dynamically?
                }
            }
        }
    },

    bindToggleCallBoxGrowth : function (callboxid){
        $('#callbox_'+callboxid).find('.callboxhead').on("click",  function(){
            TECHEXTENSION.toggleCallBoxGrowth(callboxid);
        });
    },
    
    bindCloseCallBox : function(callboxid, call_record_id){
        $('#callbox_'+callboxid).find('.callboxoptions a').on("click", function(){
            TECHEXTENSION.closeCallBox(callboxid, call_record_id);
        });  
    },
    
    bindSaveMemo : function(callboxid, call_record_id, phone_number, direction){
        $('#callbox_'+callboxid).find('.save_memo').button().on("click", function(){
            TECHEXTENSION.saveMemo(callboxid, call_record_id, phone_number, direction);  
        });
    },

    bindTransferButton : function(callboxid, entry){
        $('#callbox_'+callboxid).find('.transfer_panel').button( {
            icons: {
                primary: 'ui-icon-transfer',
                secondary: null
            }
        }).on("click", function() {
                TECHEXTENSION.log("Binding Transfer Button action");
                TECHEXTENSION.showTransferMenu(entry);
        });
    },

  bindHoldButton : function(callboxid, entry){
        $('#callbox_'+callboxid).find('.hold_panel').button( {
            icons: {
                primary: 'ui-icon-hold',
                secondary: null
            }
        }).on("click", function() {
                TECHEXTENSION.log("Binding Hold Button action");
                TECHEXTENSION.showHoldMenu(entry);
        });
    },
  bindHangupButton : function(callboxid, entry){
	$('#callbox_'+callboxid).find('.hangup_panel').button( {
		icons: {
			primary: 'ui-icon-hangup',
			secondary: null
		}
	}).on("click", function() {
			TECHEXTENSION.log("Binding Hangup Button action");
			//console.log('Hangup clicked....');
			TECHEXTENSION.showHangupMenu(entry);
	});
},
    showTransferButton : function(callboxid,entry) {
        $('#callbox_'+callboxid).find('.transfer_panel').show();
    },
    showHoldButton : function(callboxid,entry) {
        $('#callbox_'+callboxid).find('.hold_panel').show();
    },
	
	showHangupButton : function(callboxid,entry) {
	$('#callbox_'+callboxid).find('.hangup_panel').show();
	},
	hideHangupButton : function(callboxid, entry) {
	$('#callbox_'+callboxid).find('.hangup_panel').hide();
	},

    hideTransferButton : function(callboxid, entry) {
        $('#callbox_'+callboxid).find('.transfer_panel').hide();
    },
	hideHoldButton : function(callboxid, entry) {
	$('#callbox_'+callboxid).find('.hold_panel').hide();
    },
    bindCheckCallBoxInputKey : function(callboxid, entry){
        $('#callbox_'+callboxid).find('.transfer_button').keydown(function(event){
            TECHEXTENSION.checkCallBoxInputKey(event, callboxid, entry);
        });

    },

    bindActionDropdown : function(callboxid,entry){
        TECHEXTENSION.log("Binding Action Dropdown for "+ callboxid);

        var dropdownDiv = "#dropdown-1_callbox_"+callboxid;

         $('#callbox_'+callboxid).find('.callbox_action').button({
                icons: {
                    primary: "ui-icon-flag",
                    secondary: "ui-icon-triangle-1-s"
                },
                text: false
         })
         .show()
         .on("click",function() {
             $(dropdownDiv).slideDown("fast");
             $(dropdownDiv).css( "margin-left", "50px");
         })
         .on("mouseenter",function() {
             $(dropdownDiv).css( "margin-left", "50px"); // Needed in ie8 only...
             clearTimeout($(dropdownDiv).data('timeoutId1'));
             clearTimeout($(dropdownDiv).data('timeoutId2'));
             //TECHEXTENSION.log("clearing timeouts... button");
         })
         .on("mouseleave", function () {
             var timeoutId1 = setTimeout(hideDropDown,600);
             $(dropdownDiv).data('timeoutId1', timeoutId1);
             //TECHEXTENSION.log("set timeouts... button");
         });

        // This is for mouse events over the actual dropdowns...
        $(dropdownDiv).mouseleave(function() {
            var timeoutId2 = setTimeout(hideDropDown, 600);
            $(dropdownDiv).data('timeoutId2', timeoutId2);
            //TECHEXTENSION.log("set timeouts... div");
        });
        $(dropdownDiv).mouseenter(function() {
            clearTimeout($(dropdownDiv).data('timeoutId1'));
            clearTimeout($(dropdownDiv).data('timeoutId2'));
            //TECHEXTENSION.log("clearing timeouts... div");
        });

        function hideDropDown() {
            //TECHEXTENSION.log("firing hideDropDown");
            $(dropdownDiv).slideUp("fast");
        }
        // Here we show them all...

        if( window.RelateContact ) {
            //TECHEXTENSION.log("  Adding Relate to Contact");
            $(dropdownDiv+" ul li.ul_relate_to_contact").show();
            $(dropdownDiv+" ul li a.relate_to_contact").on("click", entry, function() {
                TECHEXTENSION.openContactRelatePopup(entry);
            });
        }

 if( window.RelateCase ) {
            //TECHEXTENSION.log("  Adding Relate to Contact");
         //   $(dropdownDiv+" ul li.ul_relate_to_case").show();
           // $(dropdownDiv+" ul li a.relate_to_case").on("click", entry, function() {
             //   TECHEXTENSION.openCaseRelatePopup(entry);
           // });
        }

        if( window.RelateAccount ) {
           // TECHEXTENSION.log("  Adding Relate to Account");
            $(dropdownDiv+" ul li.ul_relate_to_account").show();
            $(dropdownDiv+" ul li a.relate_to_account").on("click", entry, function() {
                TECHEXTENSION.openAccountRelatePopup(entry);
            });
        }

        if( window.CreateContact=='1' ) {
            TECHEXTENSION.log("  Adding Create New Contact " + dropdownDiv+" ul li.li_create_new_contact");
            $(dropdownDiv+" ul li.ul_create_contact").show();
            $(dropdownDiv+" ul li a.create_contact").on("click", entry, function() {
            TECHEXTENSION.createContact(entry);
            });
        }
        
         if(window.CreateLead=='1' ) {
			// alert(window.CreateLead);
            //TECHEXTENSION.log("  Adding Create New Contact " + dropdownDiv+" ul li.li_create_new_contact");
            $(dropdownDiv+" ul li.ul_create_lead").show();
            $(dropdownDiv+" ul li.ul_create_lead").on("click", entry, function() {
            TECHEXTENSION.createLead(entry);
            });
        }
		
		if(window.scheduleCall=='1' ) {
			
            $(dropdownDiv+" ul li.ul_create_schedule").show();
            $(dropdownDiv+" ul li.ul_create_schedule").on("click", entry, function() {
            TECHEXTENSION.createSchedule(entry);
            });
        }
		
		if(window.taskCall=='1' ) {
		
		$(dropdownDiv+" ul li.ul_create_task").show();
		$(dropdownDiv+" ul li.ul_create_task").on("click", entry, function() {
		TECHEXTENSION.createTask(entry);
		});
	}
		
        
         if(window.createCase=='1' ) {
			// alert(window.CreateLead);
            //TECHEXTENSION.log("  Adding Create New Contact " + dropdownDiv+" ul li.li_create_new_contact");
            $(dropdownDiv+" ul li.ul_create_case").show();
            $(dropdownDiv+" ul li.ul_create_case").on("click", entry, function() {
            TECHEXTENSION.createCase(entry);
            });
        }
        
        
          if( window.CreateAccount=='1' ) {
            //TECHEXTENSION.log("  Adding Create New Contact " + dropdownDiv+" ul li.li_create_new_contact");
            $(dropdownDiv+" ul li.ul_create_account").show();
            $(dropdownDiv+" ul li.ul_create_account").on("click", entry, function() {
            TECHEXTENSION.createAccount(entry);
            });
        }
            //~ if( window.OpenAccount ) {
            //~ //TECHEXTENSION.log("  Adding Create New Contact " + dropdownDiv+" ul li.li_create_new_contact");
            //~ $(dropdownDiv+" ul li.ul_open_account").show();
            //~ $(dropdownDiv+" ul li.ul_open_account").on("click", entry, function() {
            //~ TECHEXTENSION.openAccount(entry);
            //~ });
        //~ }
        //~ 
        //~ if( window.OpenContact ) {
			//~ //console.log(entry['beans']['0']['bean_id']);
			//~ //console.log(entry['bean_id']);
            //~ //TECHEXTENSION.log("  Adding Create New Contact " + dropdownDiv+" ul li.li_create_new_contact");
            //~ $(dropdownDiv+" ul li.ul_open_contact").show();
            //~ $(dropdownDiv+" ul li.ul_open_contact").on("click", entry, function() {
            //~ TECHEXTENSION.openContact(entry);
            //~ });
        //~ }
        //~ 
        //~ if( window.OpenLead ) {
            //~ //TECHEXTENSION.log("  Adding Create New Contact " + dropdownDiv+" ul li.li_create_new_contact");
            //~ $(dropdownDiv+" ul li.ul_open_lead").show();
            //~ $(dropdownDiv+" ul li.ul_open_lead").on("click", entry, function() {
            //~ TECHEXTENSION.openLead(entry);
            //~ });
        //~ }
      
        
	 
	  //~ if( window.CreateLead ) {
		//~ TECHEXTENSION.log("  Adding Create New Lead " + dropdownDiv+" ul li.li_create_new_lead");
		//~ console.log('lead popup called');
		//~ $(dropdownDiv+" ul li.ul_create_lead").show();
		//~ console.log('lead popup called end');
		//~ $(dropdownDiv+" ul li a.create_lead").on("click", entry, function() {
			//~ TECHEXTENSION.createLead(entry);
		//~ });
	//~ }

        /*
         if( window.callinize_relate_to_contact_enabled ) {
         TECHEXTENSION.log("  Adding Relate to Contact");
         // TODO Remove line below... for debugging
         TECHEXTENSION.log( $(dropdownDiv+" ul").length + " was found?");

         $(dropdownDiv+" ul").append("<li><a href='#' class='relate_to_contact'>"+entry['mod_strings']['RELATE_TO_CONTACT']+"</a></li>");
         $(dropdownDiv+" ul a.relate_to_contact").on("click", entry, function() {
         TECHEXTENSION.openContactRelatePopup(entry)
         });
         }
         // TODO create
         if( window.callinize_relate_to_account_enabled ) {
         TECHEXTENSION.log("  Adding Relate to Account");
         $(dropdownDiv+" ul").append("<li><a href='#' class='relate_to_account'>"+entry['mod_strings']['RELATE_TO_ACCOUNT']+"</a></li>");
         $(dropdownDiv+" ul a.relate_to_account").on("click", entry, function() {
         TECHEXTENSION.openAccountRelatePopup(entry);
         });
         }

         if( window.callinize_create_new_contact_enabled ) {
         TECHEXTENSION.log("  Adding Create New Contact");
         $(dropdownDiv+" ul").append("<li><a href='#' class='create_contact'>"+entry['mod_strings']['CREATE_NEW_CONTACT']+"</a></li>");
         $(dropdownDiv+" ul a.create_contact").on("click", entry, function() {
         TECHEXTENSION.createContact(entry)
         });
         }

         if( window.callinize_block_button_enabled ) {
         TECHEXTENSION.log("  Adding Block Button Enabled");
         $(dropdownDiv+" ul").append("<li><a href='#' class='block_number'>"+entry['mod_strings']['BLOCK_NUMBER']+"</a></li>");
         $(dropdownDiv+" ul a.block_number").on("click", {
         entry: entry,
         callboxid: callboxid
         }, function() {
         TECHEXTENSION.showBlockNumberDialog(callboxid, entry)
         });
         }
         */
    },

    // This is the icon next to the name.
    bindOpenPopupSingleMatchingContact : function(callboxid, entry){
        $('#callbox_'+callboxid).find('.singlematchingcontact .unrelate_contact').button({
            icons: {
                primary: 'ui-icon-custom-unrelate',
		        secondary: null
            },
            text: false
        }).on("click", function(){
            TECHEXTENSION.openPopup(entry);
        });  
    },

    // Not going to have this...
    bindOpenPopupSingleMatchingAccount : function(callboxid, entry){
        $('#callbox_'+callboxid).find('.singlematchingcontact .unrelate_contact').button({
            icons: {
                primary: 'ui-icon-custom-unrelate',
                secondary: null
            },
            text: false
        }).on("click", function(){
                TECHEXTENSION.openPopup(entry);
            });
    },
    
    bindSetBeanID : function(callboxid, entry){
        //console.log("in bind "+ bean_module + " is what beanmodule is");
        $('#callbox_'+callboxid).find('.multiplematchingcontacts td p').on("click", "input",  function(){
            TECHEXTENSION.setBeanID(entry['call_record_id'], this.className, this.value);
        })
    },
    
    /// USER ACTIONS
    closeCallBox : function(callboxid, call_record_id) {
        if( !TECHEXTENSION.isCallBoxClosed(callboxid) ) {
            $('#callbox_'+callboxid).remove();
            $('#block-number-callbox_'+callboxid).remove();
            $('#dropdown-1_callbox_'+callboxid).remove();

			$.ajax({ 
			type: 'get',
			url: 'index.php?entryPoint=getCallStatus&action=closebox',
			data: '',
			success: function(data,status)
			{
			//alert(data);
//				alert('close call box');
			} 

			});
			
			
			
			
			
			
			
			
			
			
            TECHEXTENSION.restructureCallBoxes();  
           

        }
    },
    toggleCallBoxGrowth : function(callboxid) {
        if (TECHEXTENSION.isCallBoxMinimized(callboxid) ) {  
            TECHEXTENSION.maximizeCallBox(callboxid);
        } 
        else {	
            TECHEXTENSION.minimizeCallBox(callboxid);
        }
        TECHEXTENSION.restructureCallBoxes(); // BR added... only needed for vertical stack method.
    },
    
    setBeanID : function( callRecordId, beanModule, beanId) {

        //console.log('index.php?entryPoint=getCallStatus&action=relateBean&callRecordId='+callRecordId+'&beanModule='+beanModule+'&beanId='+beanId);
           $.ajax({ 
            type: 'get',
            url: 'index.php?entryPoint=getCallStatus&action=relateBean&callRecordId='+callRecordId+'&beanModule='+beanModule+'&beanId='+beanId,
            data: '',
            success: function(data,status)
            {
				
				//alert(data);	
			} 
           
        });
        var loop = false;
       // TECHEXTENSION.checkForNewStates(loop);
        
    },
    
    saveMemo : function(callboxid, call_record_id, phone_number, direction) {
        var message = TECHEXTENSION.getMemoText(callboxid);
    
    
       if (message != '') {
			//console.log('index.php?entryPoint=getCallStatus&action=savememo&call_record_id='+call_record_id+'&description='+message);
            $.ajax({ 
            type: 'get',
            url: 'index.php?entryPoint=getCallStatus&action=savememo&call_record_id='+call_record_id+'&description='+message,
            data: '',
            success: function(data,status)
            {
				//alert(data);
				TINY.box.show({html:''+data,animate:true,close:false,width:300,height:100,mask:false,boxid:'success',autohide:4,top:200,left:750});			
			} 
           
        });
    
    
}
//TECHEXTENSION.clearMemoText(callboxid);

    },

    openContactRelatePopup : function (entry){
        open_popup( "Contacts", 600, 400, "", true, true, {
            "call_back_function":"TECHEXTENSION.relate_contact_popup_callback",
            "form_name": entry['call_record_id'],
            "phoneNr": entry['phone_number'],
            "field_to_name_array":{
                "id":"relateContactId",
                "first_name":"relateContactFirstName",
                "phoneNr":"phoneNr",
                "last_name":"relateContactLastName"
            }
        },"single",true);   
    },
    
        openCaseRelatePopup : function (entry){
        open_popup( "Cases", 600, 400, "", true, true, {
            "call_back_function":"TECHEXTENSION.relate_case_popup_callback",
            "form_name": entry['call_record_id'],
            "field_to_name_array":{
                "id":"relateCaseId",
                //~ "first_name":"relateContactFirstName",
                //~ "last_name":"relateContactLastName"
            }
        },"single",true);   
    },

    openAccountRelatePopup : function (entry){
        open_popup( "Accounts", 600, 400, "", true, true, {
            "call_back_function":"TECHEXTENSION.relate_account_popup_callback",
            "form_name": entry['call_record_id'],
            "field_to_name_array":{
                "id":"relateAccountId",
                "name":"relateAccountName"
            }
        },"single",true);
    },

    showTransferMenu : function(entry, callboxid, exten ) {
        if( callboxid != '' ) {
            exten = prompt("Please enter the extension number you'd like to transfer to:\n(Leave Blank to cancel)","");
		
            if( exten != null && exten != '') 
            {
				//console.log('index.php?entryPoint=getCallStatus&action=DialPlan&extension='+exten);
				//ajax
			$.ajax({ 
            type: 'get',
            url: 'index.php?entryPoint=getCallStatus&action=DialPlan&extension='+exten,
            data: '',
            success: function(exten_dialplan,status)
            {
				//alert(data);	
				TransferCall(entry['id'],exten,exten_dialplan);			
			} 
           
        });
				
				
				
				
				
            }
        }
    }, 
    
     showHoldMenu : function(entry, callboxid, exten ) {
        if( callboxid != '' ) {
          alert('call hold done');
        }
    },
      showHangupMenu : function(entry, callboxid, exten ) {
		  //console.log('Hangup Called....');
        if( callboxid != '' ) {
			//console.log('Hangup Called enters....');
			//console.log(entry['id']);
			window.asteriskID = entry['id'];
			HangUpCall(window.asteriskID);
          //alert('call Hangup done'+entry['id']);
        }
    },
    
    /*
 * Relate Contact Callback method.
 * This is called by the open_popup sugar call when a contact is selected.
 *
 * I basically copied the set_return method and added some stuff onto the bottom.  I couldn't figure out how to add
 * change events to my form elements.  This method wouldn't be needed if I figured that out.
 */
    relate_contact_popup_callback : function(popup_reply_data){
        var from_popup_return2 = true;
        var form_name = popup_reply_data.form_name;
         var phone_number = popup_reply_data.phoneNr;
         console.log(phone_number);
        var name_to_value_array = popup_reply_data.name_to_value_array;

        for (var the_key in name_to_value_array)
        {
            if(the_key == 'toJSON')
            {
            /* just ignore */
            }
            else
            {
                var displayValue=name_to_value_array[the_key].replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
                ;
                if(window.document.forms[form_name] && window.document.forms[form_name].elements[the_key])
                {
                    window.document.forms[form_name].elements[the_key].value = displayValue;
                   // SUGAR.util.callOnChangeListers(window.document.forms[form_name].elements[the_key]);
                }
            }
        }

        // Everything above is from the default set_return method in parent_popup_helper.
        
        var contactId = window.document.forms[form_name].elements['relateContactId'].value;
        if( contactId != null ) {
            TECHEXTENSION.setBeanID(form_name,'contacts',contactId);
        }
        else {
            alert("Error updating related Contact");
        }
    },
    
    
    
    
    
     relate_case_popup_callback : function(popup_reply_data){
        var from_popup_return2 = true;
        var form_name = popup_reply_data.form_name;
        var name_to_value_array = popup_reply_data.name_to_value_array;

        for (var the_key in name_to_value_array)
        {
            if(the_key == 'toJSON')
            {
            /* just ignore */
            }
            else
            {
				
                var displayValue=name_to_value_array[the_key].replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
               console.log("Innnnnn : "+form_name);
                //~ if(window.document.forms[form_name] && window.document.forms[form_name].elements[the_key])
                //~ {
					//~ console.log("cases detail : "+displayValue);
                    //~ window.document.forms[form_name].elements[the_key].value = displayValue;
                   //~ // SUGAR.util.callOnChangeListers(window.document.forms[form_name].elements[the_key]);
                //~ }
            }
        }

        // Everything above is from the default set_return method in parent_popup_helper.
        
        var caseId =displayValue;
        if( caseId != null ) {
            TECHEXTENSION.setBeanID(form_name,'cases',caseId);
        }
        else {
            alert("Error updating related Contact");
        }
    },
    
    
    

    /*
     * Relate Account Callback method.
     * This is called by the open_popup sugar call when a contact is selected.
     *
     * I basically copied the set_return method and added some stuff onto the bottom.  I couldn't figure out how to add
     * change events to my form elements.  This method wouldn't be needed if I figured that out.
     */
    relate_account_popup_callback : function(popup_reply_data){
        var from_popup_return2 = true;
        var form_name = popup_reply_data.form_name;
        var name_to_value_array = popup_reply_data.name_to_value_array;

        for (var the_key in name_to_value_array)
        {
            if(the_key == 'toJSON')
            {
                /* just ignore */
            }
            else
            {
                var displayValue=name_to_value_array[the_key].replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"');
                ;
                if(window.document.forms[form_name] && window.document.forms[form_name].elements[the_key])
                {
                    window.document.forms[form_name].elements[the_key].value = displayValue;
                    //SUGAR.util.callOnChangeListers(window.document.forms[form_name].elements[the_key]);
                }
            }
        }

        // Everything above is from the default set_return method in parent_popup_helper.

        var accountId = window.document.forms[form_name].elements['relateAccountId'].value;
        //console.log('mahavir'+accountId);
        if( accountId != null ) {
            TECHEXTENSION.setBeanID(form_name,'accounts',accountId);
        }
        else {
            alert("Error updating related Account");
        }

    },


    // -=-=-=-= DRAWING/UI FUNCTIONS =-=-=-=-=-=-=-=-=- //

    restructureCallBoxes : function(callboxid) {
		//console.log('Called restructureCallBoxes');
        var currHeight = 0;
        for(var i=0; i < TECHEXTENSION.callBoxes.length; i++ ) {
            var callboxid = TECHEXTENSION.callBoxes[i];
				//console.log('Before Callbox checked closed or not');
            if( !TECHEXTENSION.isCallBoxClosed( callboxid ) ) {   
				//console.log('Called isCallBoxClosed');
                //put first box at 0 height - bottom of page
                $("#callbox_"+callboxid).css('bottom', currHeight+'px');       
                //then grab the height of the box - this will tell if it is open or not
                currHeight += $("#callbox_"+callboxid).height();  
            }
        }
        TECHEXTENSION.nextHeight = currHeight;
	
    },
    
    minimizeExistingCallboxesWhenNewCallComesIn : function(){
		//console.log('Called minimizeExistingCallboxesWhenNewCallComesIn');
        for(var x=0; x < TECHEXTENSION.callBoxes.length; x++ ) {
            TECHEXTENSION.minimizeCallBox( TECHEXTENSION.callBoxes[x] ); // updates a cookie each time... perhaps check first.
        }
    },
    
    startVerticalEndVertical : function(callboxid){
        // START VERTICAL
        //console.log('Called startVerticalEndVertical');
        TECHEXTENSION.restructureCallBoxes();
        $("#callbox_"+callboxid).css('right', '20px');
        $("#callbox_"+callboxid).css('bottom', TECHEXTENSION.nextHeight+'px');
        // END VERTICAL
        TECHEXTENSION.callBoxes.push(callboxid);
    },
    
    setupCallBoxFocusAndBlurSettings : function(callboxid){
        TECHEXTENSION.callboxFocus[callboxid] = false;
        $("#callbox_"+callboxid+" .callboxtextarea").blur(function(){
            TECHEXTENSION.callboxFocus[callboxid] = false;
            $("#callbox_"+callboxid+" .callboxtextarea").removeClass('callboxtextareaselected');
        }).focus(function(){
            TECHEXTENSION.callboxFocus[callboxid] = true;
            TECHEXTENSION.newMessages[callboxid] = false;
            $('#callbox_'+callboxid+' .callboxhead').removeClass('callboxblink');
            $("#callbox_"+callboxid+" .callboxtextarea").addClass('callboxtextareaselected');
        });
    },

    maximizeCallBox : function(callboxid) {
        $('#callbox_'+callboxid+' .control_panel').css('display', 'block');
        $('#callbox_'+callboxid+' .callboxcontent').css('display','block');
        $('#callbox_'+callboxid+' .callboxinput').css('display','block');
        //$("#callbox_"+callboxid+" .callboxcontent").scrollTop($("#callbox_"+callboxid+" .callboxcontent")[0].scrollHeight);
				
        if( TECHEXTENSION.isCallBoxMinimized( callboxid ) ) {
            TECHEXTENSION.log( callboxid + " minimize state cookie fail (should be maximized)");
        }
		
        TECHEXTENSION.updateMinimizeCookie();
    },

    minimizeCallBox : function(callboxid) {
		//console.log('Minimise call box called : '+callboxid);
        $('#callbox_'+callboxid+' .control_panel').css('display', 'none');
        $('#callbox_'+callboxid+' .callboxcontent').css('display','none');
        $('#callbox_'+callboxid+' .callboxinput').css('display','none');
		//console.log('Done Minimise call box called');
        if( !TECHEXTENSION.isCallBoxMinimized( callboxid ) ) {
            TECHEXTENSION.log( callboxid + " minimize state cookie fail");
        }
		
        TECHEXTENSION.updateMinimizeCookie();
    },
    
    showCallerIDWhenAvailable : function(entry){
        if(entry['caller_id']){
            $('#caller_id').show();
        }
    },
    
    refreshSingleMatchView : function (callboxid, entry){

        //console.log("Refreshing single match");
        var singlematching = $('#callbox_'+callboxid).find('.singlematchingcontact');

        // 1 Match --> 1 Match Different Contact or Account
        //check if a single contacts match has had changes - must do this here because using SugarCRMs function we lose control of the callboxid that initated the callback
        if( entry['beans'].length == 1 && singlematching.is(':visible')){

            // TODO REFACTOR
            if( entry['beans'].length == 1 ) {
                   //check on id, because name could be duplicate
               var old_contact_id = $('#callbox_'+callboxid).find('.contact_id').attr('href').substr(-36);
               var new_contact_id = entry['beans'][0]['bean_id'];
               var old_company_id = $('#callbox_'+callboxid).find('.company_id').attr('href') == undefined ? null : $('#callbox_'+callboxid).find('.company_id').attr('href').substr(-36)
               var new_company_id = entry['beans'][0]['parent_id'];
               if(old_contact_id != new_contact_id || old_company_id != new_company_id){
                   TECHEXTENSION.refreshSingleMatchingContact(callboxid, entry);
                   TECHEXTENSION.log('Refreshing ' + callboxid);
               }
            }
        }
        
        // MULTIPLE OR NO MATCH --> Single case
        if( entry['beans'].length == 1 && singlematching.is(':hidden') ){
            //bind back the unrelate button
            TECHEXTENSION.bindOpenPopupSingleMatchingContact(callboxid, entry);
            
            $('#callbox_'+callboxid).find('.nomatchingcontact').hide();
            $('#callbox_'+callboxid).find('.multiplematchingcontacts').hide();
            TECHEXTENSION.refreshSingleMatchingContact(callboxid, entry);
        }
    },
    
    refreshSingleMatchingContact : function(callboxid, entry){
        var bean = entry['beans'][0];

        $('#callbox_'+callboxid).find('.singlematchingcontact').show();
        $('#callbox_'+callboxid).find('.singlematchingcontact td a.contact_id').attr('href', bean['bean_link']);
        $('#callbox_'+callboxid).find('.singlematchingcontact td span.call_contacts').text(bean['bean_name']);
        
        //check if new contact has an account
        if(bean['parent_name'] == null || bean['parent_name'].length <= 0 ) {
            $('#callbox_'+callboxid).find('.parent_name_box').show();
        }else{
            if( bean['parent_link'] != null ) {
                $('#callbox_'+callboxid).find('.parent_name_box td a.company').attr('href', bean['parent_link']);
            }
            else {
                $('#callbox_'+callboxid).find('.parent_name_box td a.company').attr('href', '#');
            }
            $('#callbox_'+callboxid).find('.parent_name_box td a.company').text(bean['parent_name']);
            $('#callbox_'+callboxid).find('.parent_name_box').show();
        }
    },

    // Saves what is placed in the input box whenever call is saved.
    checkCallBoxInputKey : function(event, callboxid, call_record_id, phone_number, direction) {
	 
        // 13 == Enter
        if(event.keyCode == 13)  {
            // CTRL + ENTER == quick save + close shortcut
            if( event.ctrlKey == 1 ) {
                TECHEXTENSION.saveMemo(call_record_id, phone_number, direction);
                TECHEXTENSION.closeCallBox(callboxid, call_record_id);
                return false;
            }
            else if( event.shiftKey != 0 ) {
                TECHEXTENSION.saveMemo(call_record_id, phone_number, direction);
                //return false; // Returning false prevents return from adding a break.
            }
        }

    },

    setupHandlebarsContextNoMatchingCase : function(callboxid, context, entry){

    },

    /**
     * Sets up the handlebars context for the single matching Account or Contact Case.
     *
     * @param callboxid
     * @param context
     * @param entry
     * @return {*}
     */
    setupHandlebarsContextForSingleMatchingCase : function(callboxid, context, entry){
        var bean = entry['beans'][0];
      //  console.log(bean);
        context['bean_id'] = bean['bean_id'];
        context['bean_module'] = bean['bean_module'];
        context['bean_name'] = bean['bean_name'];
        context['bean_link'] = bean['bean_link'];
        context['parent_name'] = bean['parent_name'];
        context['parent_id'] = bean['parent_id'];
        context['parent_link'] = bean['parent_link'];

        return context;
    },
    setupHandlebarsContextForMultipleMatchingCase : function(callboxid, context, entry){
        context['beans'] = entry['beans'];

        Handlebars.registerHelper('each', function(context, options) {
            if(typeof context != "undefined"){
                var ret = "";
                for(var i=0, j=context.length; i<j; i++) {
                    ret = ret + options.fn(context[i]);
                }
                return ret;
            }
        });
    
        return context;
    },
    
    setCallBoxHeadColor : function (callboxid, entry){
        if( entry['is_hangup']  ) {
            $("#callbox_"+callboxid+" .callboxhead").css("background-color", "#8A0829"); // an Red color
        }
        else {
            $("#callbox_"+callboxid+" .callboxhead").css("background-color", "#0B3B0B"); // a Green color
        }
    },

	setTransferButton : function(callboxid, entry ) {
        if( entry['state'] == "Connected" ) {
            if(window.CallTransfer=='1')
			{
				if( this.showTransferButton )
				{
					this.showTransferButton(callboxid,entry);
				}
			}
			
        }
        else {
			
			 this.hideTransferButton(callboxid,entry);
        }
    },
    
    
	setHangupButton : function(callboxid, entry ) {
		//alert(window.CallHangup);
	if( entry['is_hangup'] ) {
		this.hideHangupButton(callboxid,entry);
	}
	else {
		//alert(window.CallHangup);
		if(window.CallHangup=='1')
		{
			if( this.showHangupButton )
			{
				this.showHangupButton(callboxid,entry);
			}
		}
	}
},
    
    setHoldButton : function(callboxid, entry ) {
        if( entry['is_hangup'] ) {
            this.hideHoldButton(callboxid,entry);
        }
        else {
			if(window.CallHold)
			{
				if( this.showHoldButton )
				{
					//this.showHoldButton(callboxid,entry);
				}
			}
        }
    },

    //UTILITY FUNCTIONS
    createContact : function (entry) {
		//console.log(entry);
        var full_name = entry['beans'][0]['bean_name'];
        var account_name = entry['beans'][0]['parent_name'];
        var account_id = entry['beans'][0]['parent_id'];
        var name_array = full_name.split(" ");
        var first_name = name_array[0];
        var last_name = name_array[1];
        var phone_number = entry['phone_number'];
		
        if (!full_name)
        {
			window.location = "index.php?module=Contacts&action=EditView&phone_work="+phone_number;
		}
		else
		{
			if(!account_name)
			{
				window.location = "index.php?module=Contacts&action=EditView&phone_work="+phone_number+"&first_name="+first_name+"&last_name="+last_name;
			}
			else
			{
				window.location = "index.php?module=Contacts&action=EditView&phone_work="+phone_number+"&first_name="+first_name+"&last_name="+last_name+"&account_name="+account_name+"&account_id="+account_id;
			}
		}
    }, 
      createLead : function (entry) {
		  
        
        var full_name = entry['beans'][0]['bean_name'];
        var account_name = entry['beans'][0]['parent_name'];
        var account_id = entry['beans'][0]['parent_id'];
        var name_array = full_name.split(" ");
        var first_name = name_array[0];
        var last_name = name_array[1];
        var phone_number = entry['phone_number'];
		/*
		(phone_number.length >9)	
{
		phone_number=phone_number.substring(0, 3)+"-"phone_number.substring(3, 6)+"-"phone_number.substring(6, 10);
}		
*/
        if (!full_name)
        {
			 window.location = "index.php?module=Leads&action=EditView&phone_work="+phone_number;
		}
		else
		{
			if(!account_name)
			{
				window.location = "index.php?module=Leads&action=EditView&phone_work="+phone_number+"&first_name="+first_name+"&last_name="+last_name;
			}
			else
			{
				window.location = "index.php?module=Leads&action=EditView&phone_work="+phone_number+"&first_name="+first_name+"&last_name="+last_name+"&account_name="+account_name+"&account_id="+account_id;
			}
		}
       
    }, 
	
	createSchedule : function (entry) {
		  
       // alert('need to schedul call');
		//http://192.168.1.12/sugarcrm/index.php?module=Calls&action=EditView&return_module=Calls&return_action=DetailView
		
        //console.log("Module Name : "+entry['beans'][0]['bean_module']);
		var moduleName = entry['beans'][0]['bean_module'];
		var moduleId = entry['beans'][0]['bean_id'];
		var contactName = entry['beans'][0]['bean_name'];
        var phone_number = entry['phone_number'];
        
        window.location = "index.php?module=Calls&action=EditView&name=Scheduled Call : "+phone_number+"&status=Planned"+"&parent_type="+moduleName+"&call_source_c="+window.PhoneExtension+"&call_destination_c="+phone_number+"&parent_name="+contactName+"&parent_id="+moduleId+"&direction=Outbound"+"&call_entrysource_c=Asterisk Connector";
		
		},
		
		
		createTask : function (entry) {
		  
       // alert('need to schedul call');
		//http://192.168.1.12/sugarcrm/index.php?module=Calls&action=EditView&return_module=Calls&return_action=DetailView
		
        //console.log("Module Name : "+entry['beans'][0]['bean_module']);
		var moduleName = entry['beans'][0]['bean_module'];
		var moduleId = entry['beans'][0]['bean_id'];
		var contactName = entry['beans'][0]['bean_name'];
        var phone_number = entry['phone_number'];
		if(moduleName=="Contacts")
		{
		window.location = "index.php?module=Tasks&action=EditView&name=Task For : "+phone_number+"&parent_type="+moduleName+"&parent_name="+contactName+"&parent_id="+moduleId+"&contact_name="+contactName+"&contact_id="+moduleId;
		}
		else{
        //http://192.168.1.12/sugarcrm/index.php?module=Tasks&action=EditView&return_module=Tasks&return_action=DetailView
        window.location = "index.php?module=Tasks&action=EditView&name=Task For : "+phone_number+"&parent_type="+moduleName+"&parent_name="+contactName+"&parent_id="+moduleId;
		}
		},
	
	
    
    createCase : function (entry) {
		
        var full_name = entry['beans'][0]['bean_name'];
        var account_name = entry['beans'][0]['parent_name'];
        var account_id = entry['beans'][0]['parent_id'];
        var name_array = full_name.split(" ");
        var first_name = name_array[0];
        var last_name = name_array[1];
        var phone_number = entry['phone_number'];
        
       
    
    		 window.location = "index.php?module=Cases&action=EditView&account_name="+account_name+"&account_id="+account_id;
	   
    },
     
       createAccount : function (entry) {
        var phone_number = entry['phone_number'];
        window.location = "index.php?module=Accounts&action=EditView&phone_office="+phone_number;
    },  
    openAccount : function (entry) {
        var phone_number = entry['phone_number'];
        window.location = "index.php?action=DetailView&module=Accounts&record="+entry['beans']['0']['bean_id'];
    }, 
    
    openContact : function (entry) {
		//console.log(entry);
        var phone_number = entry['phone_number'];
        window.location = "index.php?action=DetailView&module=Contacts&record="+entry['beans']['0']['bean_id'];
    },
    
    openLead : function (entry) {
        var phone_number = entry['phone_number'];
        window.location = "index.php?action=DetailView&module=Leads&record="+entry['beans']['0']['bean_id'];
    },
    // Updates the cookie which stores the state of all the callboxes (whether minimized or maximized)
    // Only problem with this approach is on second browser window you might have them open differently... and this would save the state as such.
    updateMinimizeCookie : function() {
        var cookieVal="";
        for( var i=0; i< TECHEXTENSION.callBoxes.length; i++ ) {
		
            if( TECHEXTENSION.isCallBoxMinimized( TECHEXTENSION.callBoxes[i] ) ) {
                cookieVal = TECHEXTENSION.callBoxes[i] + "|";
            }
        }
	
        cookieVal = cookieVal.substr(0, cookieVal.length - 1 ); // remove trailing "|"
	
        $.cookie('callbox_minimized', cookieVal);
    },
    checkMinimizeCookie : function (callboxid){
        // Check by looking at the cookie to see if it should be minimized or not.
        var minimizedCallBoxes = new Array();

        if ($.cookie('callbox_minimized')) {
            minimizedCallBoxes = $.cookie('callbox_minimized').split(/\|/);
        }
        var minimize = 0;
        for (var j=0;j < minimizedCallBoxes.length;j++) {
            if (minimizedCallBoxes[j] == callboxid) {
                minimize = 1;
            }
        }

        if (minimize == 1) {
            $('#callbox_'+callboxid+' .control_panel').css('display', 'none');
            $('#callbox_'+callboxid+' .callboxcontent').css('display','none');
            $('#callbox_'+callboxid+' .callboxinput').css('display','none');
        }
    },
    
    getAsteriskID : function(astId){
    
        var asterisk_id = astId.replace(/\./g,'-'); // ran into issues with jquery not liking '.' chars in id's so converted . -> -BR //this should be handled in PHP
    
        return asterisk_id;
    }, 

    isCallBoxClosed : function(callboxid) {
		//console.log('checked isCallBoxClosed');
        return $('#callbox_'+callboxid).length == 0;
    },
    
    isCallBoxMinimized : function( callboxid ) {

        return $('#callbox_'+callboxid+' .callboxcontent').css('display') == 'none';

    },
    
    callBoxHasNotAlreadyBeenCreated : function(callboxid){
        var open = (-1 == $.inArray(callboxid, TECHEXTENSION.callBoxes));
        
        if ($("#callbox_"+callboxid).length > 0) {
            if ($("#callbox_"+callboxid).css('display') == 'none') {
                $("#callbox_"+callboxid).css('display','block');
                TECHEXTENSION.restructureCallBoxes(callboxid);
            }
        }
        
        return open;
    },
    
    checkForErrors : function(entry){
        if( entry['call_record_id'] == "-1" ) {
            TECHEXTENSION.log( "Call Record ID returned from server is -1, unable to save call notes for " + entry['title'] ); // TODO: disable the input box instead of this alert.
        }  
    },
 
    getMemoText : function( callboxid ) {
        var message = "";
        message = $('#callbox_'+callboxid+' .callboxinput .callboxtextarea').val();
        message = message.replace(/^\s+|\s+$/g,""); // Trims message
	
        return message;
    },
     //~ clearMemoText : function( callboxid ) {
        //~ console.log('clear set');
        //~ document.getElementsByClassName("callboxtextarea").value = '';
       //~ },
 
    getCookies : function(){
        var pairs = document.cookie.split(";");
        var cookies = {};
        for (var i=0; i<pairs.length; i++){
            var pair = pairs[i].split("=");
            cookies[pair[0]] = unescape(pair[1]);
        }
        return cookies;
    },
    
    log : function(message) {
        if (window.callinize_debug == 1) {
            //console.log(message);
        }
    },
    callStateIsNotFiltered : function(entry){
      //this is required to filter call states that would change to Hangup but have an answered state of 0
      
      if(TECHEXTENSION.filteredCallStates == 'Ringing' || TECHEXTENSION.filteredCallStates == 'Dial'){
          if(entry.answered == '0'){
              return false;
          }
      }
      
      return ($.inArray(entry.state, TECHEXTENSION.filteredCallStates) == -1);
    }

}


/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

$(document).ready(function(){
    var isAjaxUiEnabled=/ajaxUI/gi.test(window.location.search.substring(1));
    //TECHEXTENSION.log('ready() hist_loaded: ' + SUGAR.ajaxUI.hist_loaded + " ajaxUIEnabled = " + isAjaxUiEnabled);

    // if ajaxui in url... and SUGAR.ajaxUI.hist_loaded is true. -- include
    // or if ajax isn't in url --- include
    if( !isAjaxUiEnabled || SUGAR.ajaxUI.hist_loaded ) {
        TECHEXTENSION.log('loading TECHEXTENSION...');
        var loop = true;
		if(window.LastCall=='1')
		{
			//console.log('Reload Popup Called....' +window.uservalue);
			TECHEXTENSION.checkForNewStates(null,null,"Reload",null,null,window.uservalue);
		}
                else
		{
		TECHEXTENSION.checkForNewStates(null,null,"test",null,null,window.uservalue);
		}

//AsteriskCallID,PhoneNumber,CurrentRequest,Direction,Extension,RecordLink
        }
});
