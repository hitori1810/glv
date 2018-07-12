$(document).ready(function(){
    $(".edit_tr").click(function(){
        var ID=$(this).attr('id');

        $("#span_listening").hide();
        $("#span_reading").hide();
        $("#span_writing").hide();
        $("#span_speaking").hide();
        $("#span_gpa").hide();
        $("#span_stage").hide();
        $("#span_level").hide();
        $("a.edit_tr").hide();

        $("#input_listening").show();
        $("#input_reading").show();
        $("#input_writing").show();
        $("#input_speaking").show();
        $("#input_gpa").show();
        $("#stage").show();
        $("#level").show();
        $("a.save_tr").show();

    });

    $(".save_tr").click(function(){
        debugger;
        var ID=$(this).attr('id');     
        var listening   =   check_format_number($("#input_listening").val());
        var reading     =   check_format_number($("#input_reading").val());
        var writing     =   check_format_number($("#input_writing").val());
        var speaking    =   check_format_number($("#input_speaking").val());
        var gpa         =   check_format_number($("#input_gpa").val());
        var level       =   $("#level").val();
        var stage       =   $("#stage").val();

        var dataString = 'id='+ ID +'&listening='+listening+'&reading='+reading+'&writing='+writing+'&speaking='+speaking+'&gpa='+gpa+'&stage='+stage+'&level='+level;
        if((listening >=0 || listening < 0) && (reading >=0 || reading < 0) && (writing >=0 || writing < 0) && (speaking >=0 || speaking < 0) && (gpa >=0 || gpa < 0)){
            ajaxStatus.showStatus('Saving'); //Sugar alert
            $.ajax({
                url: "index.php?module=Leads&action=save_testing&sugar_body_only=true",
                type: "POST",
                data: dataString,
                dataType: "json",
                success: function(res){
                    if(res.success == "1"){
                        $("#span_listening").text(res.listening);
                        $("#span_reading").text(res.reading);
                        $("#span_writing").text(res.writing);
                        $("#span_speaking").text(res.speaking);
                        $("#span_gpa").text(res.gpa);
                        $("#span_stage").text(res.stage);
                        $("#span_level").text(res.level);


                        $("#input_listening").val(res.listening);
                        $("#input_reading").val(res.reading);  
                        $("#input_speaking").val(res.speaking);  
                        $("#input_writing").val(res.writing);  
                        $("#input_gpa").val(res.gpa);
                        $('#stage option[value="'+res.stage+'"]').prop('selected', true);
                        $('#level option[value="'+res.level+'"]').prop('selected', true); 

                    }
                },
                complete: function(){
                    ajaxStatus.hideStatus(); //END:Sugar alert
                    $("#span_listening").show();
                    $("#span_reading").show();
                    $("#span_writing").show();
                    $("#span_speaking").show();
                    $("#span_gpa").show();
                    $("#span_stage").show();
                    $("#span_level").show();
                    $("a.edit_tr").show();

                    $("#input_listening").hide();
                    $("#input_reading").hide();
                    $("#input_writing").hide();
                    $("#input_speaking").hide();
                    $("#input_gpa").hide();
                    $("#stage").hide();
                    $("#level").hide();
                    $("a.save_tr").hide();
                },
            });
        }else{
            alert('Enter something.');
        }
    });
});

function check_format_number(focus){
    value = unformatNumber(focus,num_grp_sep, dec_sep);
    if(focus == '')
        return 0;
    else
        return value; 
}