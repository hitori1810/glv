$( document ).ready(function() { 
	$('#btn_undo').live('click',function(){
		if(confirm("Are sure you want to Undo ?"))
			ajaxUndo();	
	});
});

function ajaxUndo(){
	var situation_id = $('input[name=record]').val();
	if(situation_id == '') return ;

	ajaxStatus.showStatus('Processing...');
	$.ajax({
		url: "index.php?module=J_StudentSituations&action=handleAjaxStudentSituations&sugar_body_only=true",
		type: "POST",
		async: true,
		data:  {
			type         : 'ajaxUndo',
			situation_id   : situation_id,
		},
		dataType: "json",
		success: function(res){
			ajaxStatus.hideStatus();
			if(res.success == "1"){                           
				window.location.href = "index.php?module=J_StudentSituations&action=EditView&return_module=J_StudentSituations&return_action=DetailView&student_id="+res.student_id+"&type=Moving Out";
			}
		},        
	});
}