var formname = "GradeConfig";
$(document).ready(function(){

    addToValidate(formname,'team_id','text',true, SUGAR.language.languages.J_GradebookConfig.LBL_CENTER);
    addToValidate(formname,'koc_id','text',true, SUGAR.language.languages.J_GradebookConfig.LBL_KOC_NAME);

    addToValidate(formname,'type','text',true, SUGAR.language.languages.J_GradebookConfig.LBL_TYPE);
    addToValidate(formname,'weight','decimal',true, SUGAR.language.languages.J_GradebookConfig.LBL_WEIGHT);

    $(".max_mark, .weight").live('keydown',function (e) {
        this.value = numberFormat(this.value);
    });

    $(".max_mark, .weight, .formula, .visible").live("blur", function(){
        $(".visible:checked").each(function(){
            var _thisvisible = $(this);
            var alias = _thisvisible.attr('alias');
            if($('.cf_readonly[alias="'+alias+'"]').is(":checked")) {
                var _thisformula = $('.formula[alias="'+alias+'"]');
                var _thismaxmark = $('.max_mark[alias="'+alias+'"]');
                var _thisconfig_type = $('.visible[alias="'+alias+'"]').attr('config_type');
                var formula = _thisformula.val().toUpperCase();

                formula = formula.replace("=",'');
                var formula_list = [];
                for(var i = 0; i < formula.length; i++) {
                    if(formula[i].charCodeAt() >= 65 && formula[i].charCodeAt() <=90) { //form A->Z
                        var max_mark= Numeric.parse($('input.max_mark[alias="'+formula[i]+'"]').val());
                        var weight  = Numeric.parse($('input.weight[alias="'+formula[i]+'"]').val());
                        if(_thisconfig_type == 'percent')
                            formula_list.push(Numeric.parse(weight));
                        else
                            formula_list.push(Numeric.parse(max_mark * (weight/100)));
                    } else {
                        formula_list.push(formula[i]);
                    }
                }
                _thismaxmark.val(Numeric.toFloat(eval(formula_list.join('')),2,2));
            }
        });
    });

    $('#team_id').live('change',function(){
        ajaxStatus.showStatus('Proccessing...');
        $("select#koc_id").parent().append('<span id = "config_loading" ><img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
        jQuery.ajax({
            url: "index.php?module=J_GradebookConfig&sugar_body_only=true&action=ajaxGradebookConfig",
            type: "POST",
            async: true,
            data:{
                process_type: "getKOCOfCenter",
                center_id:  $('#team_id').val(),
            },
            success: function(data){
                ajaxStatus.hideStatus();
                alertify.success("Completed!");
                $("select#koc_id").html(data);
                $("#config_loading").remove();
                $("select#koc_id").trigger("change");
            },
            error: function(){
                ajaxStatus.hideStatus();
                alertify.error("There are errors. Please try again!");
                $("#config_loading").remove();
                $("select#koc_id").html('');
                $("select#koc_id").trigger("change");
            },
        });
    });
    //
    $("select#koc_id").live('change',function(){
        var koc_id = $('#koc_id').val();

        if(koc_id != '' && typeof koc_id != 'undefined' ){
            ajaxStatus.showStatus('Proccessing...');
            $("select#type").parent().append('<span id = "config_loading" ><img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
            jQuery.ajax({
                url: "index.php?module=J_GradebookConfig&sugar_body_only=true&action=ajaxGradebookConfig",
                type: "POST",
                async: true,
                data:{
                    process_type: "getTypeOfKOC",
                    koc_id      :  $('#koc_id').val(),
                },
                success: function(data){
                    data = JSON.parse(data);
                    ajaxStatus.hideStatus();
                    alertify.success("Completed!");
                    $("select#type").html(data.html);
                    $("#config_loading").remove();
                },
                error: function(){
                    ajaxStatus.hideStatus();
                    alertify.error("There are errors. Please try again!");
                    $("#config_loading").remove();
                    $("select#type").html('');
                },
            });
        }
    });
    $("select#type").live('change',function(){
        var type = $('select#type').val();
        if(type == 'Progress')
            $("select#minitest").show();
        else $("select#minitest").hide().val('');
    });


    $('#find').live('click',function(){
        var center_id   = $('#team_id').val();
        var koc_id      = $('#koc_id').val();
        var config_type = $('#type').val();
        if(center_id == '' || koc_id == '' || config_type == '')
            return false;
        else{
            $('.content select').addClass('readonly');
            $('.content select option:not(:selected)').hide();
        }
        ajaxStatus.showStatus('Proccessing...');
        $("#find").parent().append('<span id = "config_loading" ><img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
        jQuery.ajax({
            url: "index.php?module=J_GradebookConfig&sugar_body_only=true&action=ajaxGradebookConfig",
            type: "POST",
            async: true,
            data:{
                process_type    : "getConfigContent",
                center_id       :  $('#team_id').val(),
                koc_id          :  $('#koc_id').val(),
                config_type     :  $('#type').val(),
                minitest        :  $('#minitest').val(),
            },
            success: function(data){
                data = JSON.parse(data);
                ajaxStatus.hideStatus();
                $('input[name=record]').val(data.record);
                $('input[name=weight]').val(data.weight);
                $('input[name=gradebook_name]').val(data.name);
                alertify.success("Completed!");
                $(".config").html(data.html);
                $("input[name=save]").show();
                $("#config_loading").remove();
            },
            error: function(){
                ajaxStatus.hideStatus();
                alertify.error("There are errors. Please try again!");
                $('input[name=record]').val("") ;
                $(".config").html("");
                $("#config_loading").remove();

                $('.content select option:not(:selected)').show();
                $('.content select').removeClass('readonly');
            },
        });
    });
    if($('input[name=record]').val() != '')
        $('#find').trigger('click');

    $('.visible').live("click", function() {
        var show    = $(this).is(':checked');
        var _name   = $(this).attr('_name');
        var _arrR    = ['max_mark','weight','label','group','formula'];
        var _arrD    = ['readonly','comment_list'];
        if(show){
            $.each(_arrR , function(index, val) {
                $("[name=" +_name+ "_"+val+"]").prop('readonly',false).removeClass("readonly");
            });
            $.each(_arrD , function(index, val) {
                $("[name=" +_name+ "_"+val+"]").prop('disabled',false).removeClass("readonly");
            });
        }else{
            $.each(_arrR , function(index, val) {
                $("[name=" +_name+ "_"+val+"]").prop('readonly',true).addClass("readonly");
            });
            $.each(_arrD , function(index, val) {
                $("[name=" +_name+ "_"+val+"]").prop('disabled',true).addClass("readonly");
            });
        }
    });

    $(".cf_readonly").live("click",function(){
        var show = $(this).is(':checked');
        var _name   = $(this).attr('_name');

        if(show){
            $("[name=" +_name+ "_formula]").show();
            $("[name=" +_name+ "_max_mark]").prop('readonly',true).addClass("readonly").val('');
        }else{
            $("[name=" +_name+ "_formula]").hide();
            if($("[name=" +_name+ "_visible]").is(':checked')) {
                $("[name=" +_name+ "_max_mark]").prop('readonly',false).removeClass("readonly");
            }
        }
    });
    $("#save").live('click', function() {
        var form    = jQuery("#"+formname);
        var weight  = $('#weight').val();
        if(weight == ''){
            $('#weight').effect("highlight", {color: '#FF0000'}, 2000);
            alertify.error("Please! Enter weight(%) of this gradebook.");
            return false;
        }
        if(check_form(formname) && confirm("Are you sure to save change?")){
            jQuery("#save").html('<img src="custom/include/images/loading.gif" align="absmiddle">&nbsp;Saving data...');
            jQuery.ajax({  //Make the Ajax Request
                url: "index.php?module=J_GradebookConfig&sugar_body_only=true&action=ajaxGradebookConfig",
                type: "POST",
                async: false,
                data: form.serialize(),
                error: function(){
                    alert( "AJAX - error()" );
                    $('.timecard-popup').remove();
                },
                success: function(data){
                    if(data){
                        alertify.success("Saved successfully! ");
                        $('input[name=record]').val(data)
                        window.onbeforeunload = null;
                        //  location.href = "index.php?module=J_GradebookConfig&action=DetailView&record=" +data;
                    } else {
                        jQuery("#result").html(data.msg);
                        alertify.error("Error! Please try again!");
                    }
                }
            });
        }
    });

    $('#clear').live('click',function(){
        $('.content select option:not(:selected)').show();
        $('.content select').removeClass('readonly');
        $(".config").html('');
        $("input[name=save]").hide();
    });
});