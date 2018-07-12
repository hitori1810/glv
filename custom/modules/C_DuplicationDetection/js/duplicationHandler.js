SUGAR.DuplicationDetection = {};
SUGAR.DuplicationDetection.config = [];
SUGAR.DuplicationDetection.asynchronous = true;

function ajaxCheckDuplication() {};
$(function() {
    var quickCreateLinks = $('#quickCreateULSubnav').find('li').find('a');
    var quickCreateButtons = $('.cardDetails').find('.cardActions').find('button');
    quickCreateLinks.add(quickCreateButtons).click(function() {
        if ($(this).prop('tagName') == 'A' && $(this).attr('href').indexOf('Emails') >= 0) {
            return;
        }
        SUGAR.util.doWhen(function() {
            return $('form[id*="form_DCQuickCreate"]')[0] != null && $('#dcmenu_close_link').attr('href') != 'javascript:closeEmailOverlay();';
            }, function() {
                var form = $('form[id*="form_DCQuickCreate"]');
                initDuplicationDetection(form);
        });
    });
    if ($('form#EditView')[0] != null) {
        var form = $('form#EditView');
        initDuplicationDetection(form);
    }
    if ($('#formDetailView')[0] != null) {
        $('input[id*="create"][id*="create"]').click(function() {
            SUGAR.util.doWhen(function() {
                return $('form[id*="form_SubpanelQuickCreate"]')[0] != null;
                }, function() {
                    var form = $('form[id*="form_SubpanelQuickCreate"]');
                    initDuplicationDetection(form);
            });
        });
    }
    ajaxCheckDuplication = function(form) {
        var formId = form.attr('id');
        var noDuplication = true;
        var record_id = $('input[name=record]').val();
        if(typeof record_id === 'undefined' || record_id == undefined)
            record_id = '';

        if (isValueChanged(form) || $('input[name="duplicateSave"]').val() == 'true' || record_id == '') {
            if (SUGAR.DuplicationDetection.config[formId] != null) {
                form.find('.duplicationStatus').removeClass('duplicated verified');
                form.find('.duplicationStatus').addClass('loading');
                form.find('#tblDuplication').hide();
                var moduleName = form.find('input[name="module"]').val();
                var targetFields = SUGAR.DuplicationDetection.config[formId].targetFields;
                var fieldData = {};
                for (i = 0; i < targetFields.length; i++) {
                    var targetFieldName = targetFields[i];
                    if (targetFieldName != 'email') {
                        var targetFieldInput = form.find(':input[name="' + targetFieldName + '"]');
                        if (targetFieldInput[0] != null)
                            fieldData[targetFieldName] = targetFieldInput.val().trim();
                    } else {
                        var emails = form.find('.emailaddresses');
                        if (emails[0]) {
                            var selectedEmails = [];
                            emails.find('input[type="text"][id*="emailAddress"]').each(function(i) {
                                selectedEmails[i] = $(this).val();
                            });
                            fieldData[targetFieldName] = selectedEmails;
                            fieldData['id'] = form.find('input[name="record"]').val();
                        }
                    }
                }
                $.ajax({
                    'url': 'index.php?module=C_DuplicationDetection&action=ajaxcheckduplication&sugar_body_only=true',
                    'type': 'POST',
                    'async': SUGAR.DuplicationDetection.asynchronous,
                    'data': {
                        'moduleName': moduleName,
                        'record_id' : record_id,
                        'fieldData' : JSON.stringify(fieldData)
                    },
                    'success': function(duplication) {
                        form.find('.duplicationStatus').removeClass('loading');
                        if (duplication != '') {
                            form.find('.duplicationStatus').addClass('duplicated');
                            form.find('#dialogDuplication').find('#dialogContent').html(duplication);
                            form.find('#dialogDuplication').show();
                            noDuplication = false;
                        } else {
                            form.find('.duplicationStatus').addClass('verified');
                            form.find('#dialogDuplication').hide();
                        }
                    }
                });
            }
        }
        return noDuplication;
    }
    $('#btnCloseDialog').live('click', function() {
        var form = $(this).closest('form');
        form.find('#dialogDuplication').find('#dialogContent').html('');
        form.find('#dialogDuplication').hide();
    });
});

function ajaxLoadDuplicationConfig(form) {
    var formId = form.attr('id');
    if (form.find('input[name="module"]')[0] != null) {
        var moduleName = form.find('input[name="module"]').val();
        if (moduleName != '') {
            $.ajax({
                'url': 'index.php?module=C_DuplicationDetection&action=ajaxloadconfig&sugar_body_only=true',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'moduleName': moduleName
                },
                'success': function(config) {
                    if (config.targetFields != null) {
                        SUGAR.DuplicationDetection.config[formId] = config;
                        var statusTitle = SUGAR.language.get('app_strings', 'LBL_DUPLICATION_STATUS_TITLE');
                        var duplicationStatus = '<span title="' + statusTitle + '" class="duplicationStatus verified"></span>';
                        var container         = '<div class="duplicationContainer"></div>';

                        var isFirstField = true;
                        for (i = 0; i < config.targetFields.length; i++) {
                            targetField = form.find(':input[name="' + config.targetFields[i] + '"]');
                            if (targetField[0] != null) {

                                targetField.wrap(container);
                                targetField.closest('div.duplicationContainer').append(duplicationStatus);
                                targetField.addClass('checkDuplicateInput');
                                targetField.change(function() {
                                    SUGAR.DuplicationDetection.asynchronous = true;
                                    ajaxCheckDuplication(form);
                                });
                                if (isFirstField) {
                                    var title = SUGAR.language.get('app_strings', 'LBL_DUPLICATION_DIALOG_TITLE');
                                    var holder = '<div id="dialogDuplication" style="width:50%; min-width: 450px;">';
                                    holder += '<div id="dialogTitle">' + title + '<span id="btnCloseDialog"></span></div>';
                                    holder += '<div id="dialogContent"></div>';
                                    holder += '</div>';
                                    if($('#dialogDuplicationLocated').length)
                                        $('#dialogDuplicationLocated').append(holder);
                                    else
                                        targetField.closest('td').append(holder);
                                    isFirstField = false;
                                }
                            }
                        }
                        if ($.inArray('email', config.targetFields) >= 0) {
                            var emails = form.find('.emailaddresses');
                            if (emails[0] != null) {
                                emails.wrap(container);
                                targetField.closest('div.duplicationContainer').append(duplicationStatus);
                                targetField.addClass('checkDuplicateInput');
                                emails.find('input[type="text"][id*="emailAddress"]').live('change', function() {
                                    SUGAR.DuplicationDetection.asynchronous = true;
                                    ajaxCheckDuplication(form);
                                });
                                emails.find('button[id*="email_widget_add"]').click(function() {
                                    emails.find('input[type="text"][id*="emailAddress"]:last').live('change', function() {
                                        SUGAR.DuplicationDetection.asynchronous = true;
                                        ajaxCheckDuplication(form);
                                    });
                                });
                            }
                        }
                        if (config.preventiveType == 'notify_and_prevent') {
                            if (form.attr('id') == 'EditView') {
                                form.find('#SAVE_HEADER, #SAVE_FOOTER, #save_and_continue').each(function() {
                                    var curClickLogic = $(this).attr('onclick');
                                    $(this).attr('onclick', 'SUGAR.DuplicationDetection.asynchronous = false; if(!ajaxCheckDuplication($(this.form))) return false; ' + curClickLogic);
                                });
                            } else if (form.find('input[name*="subpanel_save_button"]')[0] != null) {
                                form.find('input[name*="subpanel_save_button"]').each(function() {
                                    var curClickLogic = $(this).attr('onclick');
                                    $(this).attr('onclick', 'SUGAR.DuplicationDetection.asynchronous = false; if(!ajaxCheckDuplication($(this.form))) return false; ' + curClickLogic);
                                });
                            } else if (form.find('input[id*="dcmenu_save_button"]')[0] != null) {
                                form.find('input[id*="dcmenu_save_button"]').each(function() {
                                    var curClickLogic = $(this).attr('onclick');
                                    $(this).attr('onclick', 'SUGAR.DuplicationDetection.asynchronous = false; if(!ajaxCheckDuplication($(this.form))) return false; ' + curClickLogic);
                                });
                            }
                        }
                    }
                }
            });
        }
    }
}

function initDuplicationDetection(form) {
    ajaxLoadDuplicationConfig(form);
    form.find(':input').each(function() {
        $(this).attr('db-data', $(this).val());
    });
    SUGAR.util.doWhen(function() {
        return form.find('.emailaddresses')[0] != null;
        }, function() {
            var emails = form.find('.emailaddresses');
            if (emails[0] != null) {
                var selectedEmails = [];
                emails.find('input[type="text"][id*="emailAddress"]').each(function(i) {
                    selectedEmails[i] = $(this).val();
                });
                emails.attr('db-data', selectedEmails.sort().join(','));
            }
    });
    if (form.find('.DateTimeCombo')[0] != null) {
        SUGAR.util.doWhen(function() {
            return typeof SugarWidgetScheduler != 'undefined';
            }, function() {
                SugarWidgetScheduler.update_time = function() {
                    var form_name;
                    if (typeof document.EditView != 'undefined')
                        form_name = "EditView";
                    else if (typeof document.CalendarEditView != 'undefined')
                        form_name = "CalendarEditView";
                        else
                            return;
                    if (typeof document.forms[form_name].date_start == 'undefined')
                        return;
                    var date_start = document.forms[form_name].date_start.value;
                    if (date_start.length < 16) {
                        return;
                    }
                    var hour_start = parseInt(date_start.substring(11, 13), 10);
                    var minute_start = parseInt(date_start.substring(14, 16), 10);
                    var has_meridiem = /am|pm/i.test(date_start);
                    if (has_meridiem) {
                        var meridiem = trim(date_start.substring(16));
                    }
                    GLOBAL_REGISTRY.focus.fields.date_start = date_start;
                    if (has_meridiem) {
                        GLOBAL_REGISTRY.focus.fields.time_start = hour_start + time_separator + minute_start + meridiem;
                    } else {
                        GLOBAL_REGISTRY.focus.fields.time_start = hour_start + time_separator + minute_start;
                    }
                    GLOBAL_REGISTRY.focus.fields.duration_hours = document.forms[form_name].duration_hours.value;
                    GLOBAL_REGISTRY.focus.fields.duration_minutes = document.forms[form_name].duration_minutes.value;
                    GLOBAL_REGISTRY.focus.fields.datetime_start = SugarDateTime.mysql2jsDateTime(GLOBAL_REGISTRY.focus.fields.date_start, GLOBAL_REGISTRY.focus.fields.time_start);
                    GLOBAL_REGISTRY.scheduler_attendees_obj.init();
                    GLOBAL_REGISTRY.scheduler_attendees_obj.display();
                    $('#' + form_name).find('.DateTimeCombo').trigger('change');
                }
        });
    }
}

function isValueChanged(form) {
    var valueChanged = false;
    var formId = form.attr('id');
    var targetFields = SUGAR.DuplicationDetection.config[formId].targetFields;
    for (i = 0; i < targetFields.length; i++) {
        targetField = form.find(':input[name="' + targetFields[i] + '"]');
        if (targetField[0] != null) {
            var oldValue = targetField.attr('db-data');
            var curValue = targetField.val();
            if (oldValue != curValue) {
                valueChanged = true;
                break;
            }
        }
    }
    if ($.inArray('email', targetFields) >= 0) {
        var emails = form.find('.emailaddresses');
        if (emails[0] != null) {
            var oldEmails = emails.attr('db-data');
            var curEmails = [];
            emails.find('input[type="text"][id*="emailAddress"]').each(function(i) {
                curEmails[i] = $(this).val();
            });
            if (oldEmails != curEmails.sort().join(','))
                valueChanged = true;
        }
    }
    return valueChanged;
}