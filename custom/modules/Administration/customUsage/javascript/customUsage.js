/* javascript */

$(document).ready(function() { 
		
	/* for some reason, the jQ click() shorthand suddenly didn't work so i had to convert this into a crappy function instead.. duh!  */
	move_to = function (direction) {

		$("input[type=button]#save").attr("disabled", false);
		if (direction == 'right') {
		var items = ''; 
			$.each($("div#original div.item_sel"), function() {
				items += "<div class='item' id='" + $(this).attr('id') + "' onclick='select(this);' title='Click to select or deselect'>" + $(this).text() + "</div>";
			});
			$("div#selection").html( $("div#selection").html() + items );
			$("div#original div.item_sel").remove();
		} else {
			var items = ''; 
			$.each($("div#selection div.item_sel"), function() {
				items += "<div class='item' id='" + $(this).attr('id') + "' onclick='select(this);' title='Click to select or deselect'>" + $(this).text() + "</div>";
			});
			$("div#original").html( $("div#original").html() + items );
			$("div#selection div.item_sel").remove();
		}
	}
 
	$("input[type=button]#save").click (function() { 
		var fields = Array(); 
		$("input[type=button]#save").hide();
        $("table#mod_tbl").hide(); 
		$.each($("div#selection div"), function() {
			fields.push($(this).attr('id')); 
		}); 
		$("#field_panels").html("Processing... Please wait!");
		$.get("./index.php?module=Administration&action=customUsage&sugar_body_only=1", { 'fields[]':fields }, function(data) {	
			$("#field_panels").html(data);
		});
			 
	}); 

	select = function(obj) { 
		 obj.className = obj.className=="item" ? "item_sel" : "item"; 
	}
	
});

