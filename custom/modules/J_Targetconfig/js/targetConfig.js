$(document).ready(function(){

    $('#tg_refresh').live('click',function(){
        $('#tg_center, #tg_type, #tg_year, #tg_frequency, #tg_unit_from, #tg_unit_to').prop('disabled',false).removeClass('input_readonly');
        $('div#result_table').html('');
    });
    $('#tg_clearconfig').live('click',function(){
        $('input.configVal').each(function(){
            $(this).val('');
        });
    });

    $('#tg_saveconfig').live('click',function(){
        var configVal = {};
        $('input.configVal').each(function(){
            var team_id     = $(this).closest('tr').find('input.tg_team_id').val();
            configVal[team_id] = ( typeof configVal[team_id] != 'undefined' && configVal[team_id] instanceof Object ) ? configVal[team_id] : {};
            var time_unit   = $(this).attr('time_unit');
            configVal[team_id][time_unit]= $(this).val();
            if($(this).val() == 0) $(this).val('');
        });
        SUGAR.ajaxUI.showLoadingPanel();
        $.ajax({
            url: "index.php?module=J_Targetconfig&action=ajaxTargetConfig&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  {
                type            : 'saveConfig',
                tg_center       : $('#tg_center').val(),
                tg_type         : $('#tg_type').val(),
                tg_year         : $('#tg_year').val(),
                tg_frequency    : $('#tg_frequency').val(),
                tg_unit_from    : $('#tg_unit_from').val(),
                tg_unit_to      : $('#tg_unit_to').val(),
                configVal       : JSON.stringify(configVal),
            },
            dataType: "json",
            success: function(res){
                SUGAR.ajaxUI.hideLoadingPanel();
                if(res.success == "1")
                    alertify.success('Target config saved successfully!');
                else
                    alertify.error('Something Wrong. Please, Try again!');
            },
        });
    });

    $('#tg_loadconfig').live('click',function(){
        if(validateTargetConfig()){
            $('div#result_table').html('');
            //Disable Selects
            $('#tg_center, #tg_type, #tg_year, #tg_frequency, #tg_unit_from, #tg_unit_to').prop('disabled',true).addClass('input_readonly');
            //Run Ajax
            ajaxStatus.showStatus('Loading');
            $.ajax({
                url: "index.php?module=J_Targetconfig&action=ajaxTargetConfig&sugar_body_only=true",
                type: "POST",
                async: true,
                data:  {
                    type            : 'loadConfig',
                    tg_center       : $('#tg_center').val(),
                    tg_type         : $('#tg_type').val(),
                    tg_year         : $('#tg_year').val(),
                    tg_frequency    : $('#tg_frequency').val(),
                    tg_unit_from    : $('#tg_unit_from').val(),
                    tg_unit_to      : $('#tg_unit_to').val(),
                },
                dataType: "json",
                success: function(res){
                    if(res.success == "1"){
                        $('div#result_table').html(res.html);
                    }else{
                        alertify.error('Something Wrong. Please, Try again!');
                        $('#tg_center, #tg_type, #tg_year, #tg_frequency, #tg_unit_from, #tg_unit_to').prop('disabled',false).removeClass('input_readonly');
                    }
                    ajaxStatus.hideStatus();
                },
            });
        }else{
            alertify.error('Missing required fields!');
        }
    });

    $('#tg_frequency, #tg_year').live('change',function(){
        var frequency   = $('#tg_frequency').val();
        var year        = $('#tg_year').val();
        var list        = '<option value="">-none-</option>';
        var months      = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        var tg_unit_from = $('#tg_unit_from');
        tg_unit_from.empty();

        var tg_unit_to = $('#tg_unit_to');
        tg_unit_to.empty();

        switch(frequency) {
            case 'Weekly':
                for (var j = 1; j <= weeksInYear(year); j++)
                    list += "<option value='" +j+ "'>W " +j+ "</option>";
                break;
            case 'Monthly':
                for (var j = 0; j < months.length; j++)
                    list += "<option value='" +(j+1)+ "'>" +months[j]+ "</option>";
                break;
            case 'Quarterly':
                for (var j = 1; j < 5; j++)
                    list += "<option value='" +j+ "'>Q " +j+ "</option>";
                break;
            case 'Yearly':
                    list += "<option value='" +year+ "'>" +year+ "</option>";
                break;
            default:
            list += "<option value='1'>1</option>";
        }
        tg_unit_from.html(list).effect("highlight", {color: '#ff9933'}, 1000);
        tg_unit_to.html(list).effect("highlight", {color: '#ff9933'}, 1000);

        switch(frequency) {
            case 'Weekly':
                var currentWeek = getWeekNumber(new Date())[1];
                $('#tg_unit_from').val(currentWeek);
                if(currentWeek + 6 > $('#tg_unit_to option:last').val())
                    $('#tg_unit_to option:last').prop('selected', true);
                else
                    $('#tg_unit_to').val(currentWeek + 6);
                break;
            case 'Monthly':
                var d = new Date();
                var currentMonth = d.getMonth() + 1;
                $('#tg_unit_from').val(currentMonth);
                if(currentMonth + 6 > $('#tg_unit_to option:last').val())
                    $('#tg_unit_to option:last').prop('selected', true);
                else
                    $('#tg_unit_to').val(currentMonth + 6);
                break;
            case 'Quarterly':
            case 'Yearly':
            default:
                $('#tg_unit_from option:eq(1)').prop('selected', true);
                $('#tg_unit_to option:last').prop('selected', true);
                break;
        }
    });

});
function validateTargetConfig(){
    var validate_arr=  ['tg_center','tg_type','tg_year','tg_frequency','tg_unit_from','tg_unit_to'];
    this.count      = 0;
    var self        = this;
    $.each(validate_arr, function(index, item) {
        if($('#'+item).val() == ''){
            self.count++;
            $('#'+item).effect("highlight", {color: '#FF0000'}, 2000);
        }else{
            if($('#tg_unit_to').val() === parseInt($('#tg_unit_to').val(), 10))
                if(item == 'tg_unit_from' && $('#tg_unit_to').val() != '' && $('#'+item).val() > $('#tg_unit_to').val()){
                    self.count++;
                    $('#'+item+', #tg_unit_to').effect("highlight", {color: '#FF0000'}, 2000);
                    alertify.error('Invalid Time Unit!');
                }
        }
    });
    if(this.count > 0) return false;
    else return true;
}
function weeksInYear(year) {
    var month = 11, day = 31, week;

    // Find week that 31 Dec is in. If is first week, reduce date until
    // get previous week.
    do {
        d = new Date(year, month, day--);
        week = getWeekNumber(d)[1];
    } while (week == 1);

    return week;
}
function getWeekNumber(d) {
    // Copy date so don't modify original
    d = new Date(+d);
    d.setHours(0,0,0);
    // Set to nearest Thursday: current date + 4 - current day number
    // Make Sunday's day number 7
    d.setDate(d.getDate() + 4 - (d.getDay()||7));
    // Get first day of year
    var yearStart = new Date(d.getFullYear(),0,1);
    // Calculate full weeks to nearest Thursday
    var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7)
    // Return array of year and week number
    return [d.getFullYear(), weekNo];
}