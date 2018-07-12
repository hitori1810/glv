var datePattern = /(0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])[- \/.](20)[0-9]{2}/; // mm/dd/yyyy
var datePickerDateFormat = "mm/dd/yy";
var datePickerTimeFormat = "mm/dd/yy ";
var dbDateTimeFormat = "yyyy-MM-dd HH:mm:ss";

function center(targetElmnt, containerElmnt){
    targetElmnt.position({
        of: containerElmnt,
        my: 'center center',
        at: 'center center',
    }); 
    
    var marginTop = parseInt(targetElmnt.css('top'));
    if(marginTop < 0) {
        targetElmnt.css('top', '0px');        
    }
}

// Sometimes whe need to use native elements
function getElmnt(id) { 
    return document.getElementById(id); 
}

function clearForm(formID){
    jQuery1_7_1('#' + formID).find(':input').val('');
    jQuery1_7_1('#' + formID).find(':checkbox').attr('checked', false);  
    jQuery1_7_1('#' + formID).find('textarea').val(''); 
    jQuery1_7_1('#' + formID).find('select').val(''); 
    jQuery1_7_1('#' + formID).find('.errorContainer').hide(); 
}

function toTime(minutes){
    var minute = minutes % 60;
    var hour = (minutes - minute) / 60;
    var date = new Date();
    date.setHours(hour);
    date.setMinutes(minute);
    var hh = date.toString('hh');
    if(hour == 0) hh = 12;    // Convert from 00:00 am to 12:00 am 
    if(hour == 24) hh = 12;    // Convert from 24:00 pm to 12:00 pm
    var mm = date.toString('mm');
    var tt = date.toString('tt');    // Convert from 24:00 am to 12:00 pm 
    return hh + ':' + mm + ' ' + tt;        
}

function getDaysDiff(from, to) {
    from.setHours(0);
    from.setMinutes(0);
    from.setSeconds(0);
    from.setMilliseconds(0);
    to.setHours(0);
    to.setMinutes(0);
    to.setSeconds(0);
    to.setMilliseconds(0);
    var timeDiff = Math.abs(to.getTime() - from.getTime());
    return timeDiff / (1000 * 3600 * 24);     
}

// Override Date object
(function() {
    var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

    var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    Date.prototype.getMonthName = function() {
        return months[ this.getMonth() ];
    };
    Date.prototype.getDayName = function() {
        return days[ this.getDay() ];
    };
})();