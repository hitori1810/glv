/* javascript */

$(document).ready(function() { 
		
	/* for some reason, the jQ click() shorthand suddenly didn't work so i had to convert this into a crappy function instead.. duh!  */
	move_to = function (direction) {

//		$("input[type=button]#save").attr("disabled", false);
		if (direction == 'right') {
		var items = ''; 
			$.each($("div#original div.item_sel"), function() {
				items += "<div class='item' id='" + $(this).attr('id') + "' onclick='select(this);' title='Click to select or deselect'>" + $(this).text() + "</div>";
			});
			$("div#selection").html( $("div#selection").html() + items );
			$("div#original div.item_sel").remove();
            jQuery('#check_left_all').attr('checked',false);
		} else {
			var items = ''; 
			$.each($("div#selection div.item_sel"), function() {
				items += "<div class='item' id='" + $(this).attr('id') + "' onclick='select(this);' title='Click to select or deselect'>" + $(this).text() + "</div>";
			});
			$("div#original").html( $("div#original").html() + items );
			$("div#selection div.item_sel").remove();
            jQuery('#check_right_all').attr('checked',false);
		}
	}
 
	$("input[type=button]#save").click (function() { 
		var fields = Array(); 
		$("input[type=button]#save").hide();
//        $("table#mod_tbl").hide(); 
		$.each($("div#selection div"), function() {
			fields.push($(this).attr('id')); 
		}); 
        user_a = $('#user_a').val();
        user_b = $('#user_b').val();
		$("#field_panels").html("Processing... Please wait!");
		$.post("./index.php?module=Administration&action=usertranferdata&sugar_body_only=1", {'fields[]':fields,'user_a':user_a,'user_b':user_b }, function(data) {	
			$("#field_panels").html(data);
		});
			 
	}); 

	select = function(obj) { 
		 obj.className = obj.className=="item" ? "item_sel" : "item"; 
	}
    
    jQuery('#check_left_all').live('click',function(){
         if(jQuery(this).is(':checked')){
             jQuery('#original').find('.item').attr('class','item_sel');
         }
         else{
             jQuery('#original').find('.item_sel').attr('class','item');
         }
    });
    
    jQuery('#check_right_all').live('click',function(){
         if(jQuery(this).is(':checked')){
             jQuery('#selection').find('.item').attr('class','item_sel');
         }
         else{
             jQuery('#selection').find('.item_sel').attr('class','item');
         }
    });
	
});

