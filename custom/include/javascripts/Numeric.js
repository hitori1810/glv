/*
*   Numeric.js
*   Author: Hieu Nguyen
*   Date: 2015-07-15
*   Purpose: to format numeric fields   
*/

// Util object
var Numeric = {};

// Util function to parse a formatted number into a raw number   
Numeric.parse = function(value) {
    var value = (value != '' || isNaN) ? value : 0;
    value = unformatNumber(value, num_grp_sep, dec_sep);
    return value;    
};

// Util function to convert a raw numer into a formatted integer number   
Numeric.toInt = function(value) {
    if(value == 0 || isNaN(value)) {
        return 0;
    }
    return formatNumber(value, num_grp_sep, dec_sep, 0, 0);    
};

// Util function to convert a raw numer into a formatted float number    
Numeric.toFloat = function(value, sigDigits) {
    if(typeof sigDigits == 'undefined') sigDigits = 2;
    if(value == 0 || isNaN(value)){
        value = '0' + dec_sep + Array(sigDigits + 1).join('0');  
    }
    value = formatNumber(value, num_grp_sep, dec_sep, sigDigits, sigDigits);
    if(value == 0) {
        value = '0' + dec_sep + Array(sigDigits + 1).join('0');    
    } else if(value.toString().indexOf(dec_sep) == 0) {
        value = '0' + value;
    }
    return value;        
};

// jQuery plugin
(function($) {

    $.fn.numeric = function(option) {
        var numGrpSepRegex = new RegExp(num_grp_sep.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&"), 'g');
        var decSepRegex = new RegExp(dec_sep.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&"), 'g');
        var navigateKeys = [35, 36, 37, 38, 39, 40];
        var decimalKeys = [110, 190];
        var backspaceKey = 8;
        var deleteKey = 46;

        var settings = $.extend({
            type: 'float',
            sigDigits: 2   // Get default signification number from user's preferences.
            }, option);

        if(typeof option == 'object') {
            if(option.type == 'int') { 
                settings.sigDigits = 0;    
            }
        }

        if(typeof option == 'string') {
            // Call method only
            if(option == 'parse') {
                if($(this).val() != '')
                    return Numeric.parse($(this).val());    
            }
        } else {
            // Bind to numeric fields
            this.each(function(){
                // Format number while typing
                $(this).keydown(function(e){
                    if(e.keyCode == 189 && !settings.negative){
                        e.preventDefault();
                    }
                    if($(this).val().indexOf('-') >= 0 && e.keyCode == 189){
                        e.preventDefault();
                    }
                    
                    if(this.selectionStart > 0 && e.keyCode == 189){
                        e.preventDefault();
                    }
                    
                });

                $(this).keyup(function(e){
                    
                    // Prevent format for action ctrl + a (A)
                    if((e.ctrlKey == true && (e.keyCode == 65 || e.keyCode == 97) || e.keyCode == 17)){
                        
                        return false;
                        
                    }else{

                        var cursorPos = cursorPosBefore = this.selectionStart;
                        var value = valueBefore = '';

                        if($(this).val() != '') {
                            value = valueBefore = $(this).val();
                            // Count numer of number group seperators before it is changed
                            var numCountBefore = valueBefore.toString().length;
                            var numGroupSepCountBefore = valueBefore.toString().length - (valueBefore.toString().replace(numGrpSepRegex, '')).length;

                            // Decimal key is not allowed for int type
                            if(settings.type == 'int' && $.inArray(e.keyCode, decimalKeys) >= 0) {
                                value = value.toString().replace(decSepRegex, '');    
                            }

                            if($.inArray(e.keyCode, decimalKeys) < 0 && $.inArray(e.keyCode, navigateKeys) < 0 && (cursorPos <= value.toString().indexOf(dec_sep) || cursorPos - value.toString().indexOf(dec_sep) >= settings.sigDigits) && e.keyCode != 189) {

                                value = unformatNumber(value, num_grp_sep, dec_sep);
                                value = (settings.type == 'float') ? Numeric.toFloat(value, settings.sigDigits) : Numeric.toInt(value);
                                
                                // Count numer of number group seperators after it is changed
                                var numCountAfter = value.toString().length;

                                var numGroupSepCountAfter = value.toString().length - (value.toString().replace(numGrpSepRegex, '')).length;

                                // Value has 1 digits but then is formatted
                                if(numCountBefore == 1 && numCountAfter > 1) {
                                    cursorPos = 1;    
                                } else {
                                    // If the number group seperators count is different then the cursor posision should be changed 
                                    if(numGroupSepCountAfter != numGroupSepCountBefore) {
                                        cursorPos += (numGroupSepCountAfter - numGroupSepCountBefore);
                                    }    
                                }
                            }
                        }

                        // If user deleted a digit then leave it blank so that they can enter the new one
                        if((e.keyCode == backspaceKey || e.keyCode == deleteKey)) {
                            if(valueBefore == '') {
                                // All digits are deleted
                                value = '';
                            } else if(settings.type == 'float' && valueBefore.toString().indexOf(dec_sep) == 0 && cursorPosBefore == 0) {
                                // The integer digit is deleted
                                value = valueBefore;
                                cursorPos = 0;
                            } else if(settings.type == 'float' && valueBefore.toString().length > 0 && valueBefore.toString().indexOf(dec_sep) < 0) {
                                // No decimal seperator
                                value  = Numeric.parse(value);
                                value = Numeric.toFloat(value, settings.sigDigits);
                                cursorPos = cursorPosBefore; 
                            } else if(settings.type == 'float' && cursorPosBefore > valueBefore.toString().indexOf(dec_sep)) {
                                // The decimal digit is deleted
                                value = valueBefore;
                            }
                        }
                        
                        if((value == '-0' && e.keyCode == 189) || value == '0-'){
                            value = '-';
                        }

                        $(this).val(value).setCursorPosition(cursorPos);
                    }
                });

                // Format number after changing
                $(this).blur(function(e){
                    var value = 0;

                    if($(this).val() != '') {
                        value = $(this).val();
                        value = unformatNumber(value, num_grp_sep, dec_sep);
                    }

                    value = (settings.type == 'float') ? Numeric.toFloat(value, settings.sigDigits) : Numeric.toInt(value);
                    $(this).val(value);
                });
            });   
        }
    };

    }(jQuery));