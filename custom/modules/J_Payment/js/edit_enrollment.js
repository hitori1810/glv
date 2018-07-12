var record_id       = $('input[name=record]').val();
var student_id      = $('#contacts_j_payment_1contacts_ida').val();
var payment_type_begin    = $('#payment_type').val();
var book_index      = 1;
var lock_coursefee = false;
var aims_id         = $('input[name=aims_id]').val();
$(document).ready(function() {
    //ngan chan loi load trang lien tuc
    if(payment_type_begin == '0' || payment_type_begin == 'Array' || payment_type_begin == '' || typeof payment_type_begin == 'undefined')
        location.reload();

    $('#table_sponsor').multifield({
        section :   '.row_tpl_sponsor', // First element is template
        addTo   :   '#tbodysponsor', // Append new section to position
        btnAdd  :   '#btnAddSponsor', // Button Add id
        btnRemove:  '.btnRemoveSponsor', // Buton remove id
    });
    // FIELD STUDENT
    // Đổi type input SAVE thành button
    changeTypeInputSubmit($("#SAVE_HEADER"));
    changeTypeInputSubmit($("#SAVE_FOOTER"));
    generateStudentSelector();
    addToValidate('EditView', 'contacts_j_payment_1contacts_ida', 'varchar', true,'Student' );

    // removeFromValidate('EditView','description');
    //Open Popup
    $('#btn_select_student').click(function(){
        open_popup('Contacts', 600, 400, "", true, false, {"call_back_function":"set_contact_return","form_name":"EditView","field_to_name_array":{"id":"contacts_j_payment_1contacts_ida","name":"name","phone_mobile":"phone_mobile","birthdate":"birthdate"}}, "single", true);
    });

    $('#btn_clr_select_student').click(function(){
        $('.filter-option,.pull-left').text('');
        $("#contacts_j_payment_1contacts_ida").val('');
        $('input#json_student_info').val('');
        $('#tbodypayment').html("");
        $('#student_picker').html('');
        $('#student_picker').selectpicker("refresh");
        showDialogStudent();
    });

    $('#student_picker').live('change',function(){
        $('#contacts_j_payment_1contacts_ida').val($(this).val());
        var stuText = $("#student_picker option:selected").attr("data-subtext");
        if(stuText.search("Lead")!= -1){
            $('#lead_id').val($(this).val());
        }
        else $('#lead_id').val("");
        ajaxGetStudentInfo(true);
    });

    $('#eye_dialog_123').live('click',function(){
        showDialogStudent(true);
    });

    //FIELD DISCOUNT AND GET DISCOUNT
    $('#btn_get_loyalty').live('click',function(){
        $("body").css({ overflow: 'hidden' });
        calLoyalty();
        showDialogLoyalty();
    });
    $("input#loy_loyalty_points").keyup(function(e){
        calLoyalty();
    });

    $("input#loy_loyalty_points").live('change',function(){
        if($(this).val() > 0){

        }else{
            $(this).val(0);
        }
    });

    $('input.currency').live('change',function(){
        check_currency(this);
    });

    $('#btn_get_discount').live('click',function(){
        $("body").css({ overflow: 'hidden' });
        reloadDiscount();
        showDialogDiscount();
        calDiscount();
    });
    $('#btn_add_sponsor').live('click',function(){
        $("body").css({ overflow: 'hidden' });
        calSponsor();
        showDialogSponsor();
    });
    //Click table rows to select checkbox
    $('#table_discount tr').click(function(event) {
        if ($(this).hasClass("locked")) return;
        if (event.target.type !== 'checkbox' && event.target.type !== 'select-one' && $(this).find(".dis_name").text() != 'Atlantic Reward'){
            $(':checkbox', this).trigger('click');
        }
    });
    $('.dis_check, .discount_partnership, #discount_reward, input.dis_hours').live('change',function(){
        calDiscount();
    });
    //Live Change Course Fee
    $('#coursefee').live('change',function(){                             
        var tuition_hours       = Numeric.parse($('#tuition_hours').val());
        var tuition_fee         = Numeric.toInt(calCourseFee(tuition_hours));
        $('#tuition_fee').val(tuition_fee);
        caculated();
        submitSponsor();
        submitDiscount();
        submitLoyalty();
        caculated();
    });
    if(payment_type_begin == 'Enrollment' || payment_type_begin == 'Cashholder'){
        $('#coursefee').select2({width: '250px'});
    }
    if(payment_type_begin == 'Cashholder' || payment_type_begin == 'Deposit'){
        $('#kind_of_course').select2({width: '150px'});
        if(record_id == '')
            $('#payment_type').select2({minimumResultsForSearch: Infinity,width: '150px'});
    }
    //HANDLE ENROLLMENT
    $('#payment_date').live('change',function(){
        if(!checkDataLockDate($(this).attr('id'),false))
            return ;
        var outstanding_list      = $('input[name=outstanding_list]').val();
        if(!isEmpty(outstanding_list) && !$.isEmptyObject($.parseJSON(outstanding_list))){
            ajaxGetStudentInfo(false);
            outstandingChecking($('#classes').val(), true);
        }
        var number_of_payment   = $('#number_of_payment').val();
        var payment_date       = $('#payment_date').val();
        for(i = 1; i <= number_of_payment; i++ ){
            if($('#pay_dtl_invoice_date_'+i).val() == '' || record_id == '')  //In Case Create
                $('#pay_dtl_invoice_date_'+i).val(payment_date).effect("highlight", {color: '#ff9933'}, 1000);

        }
    });
    if(payment_type_begin == 'Enrollment'){
        addToValidate('EditView', 'classes', 'multienum', true,'Class' );
        addToValidate('EditView', 'j_coursefee_j_payment_1j_coursefee_ida', 'enum', true,'Course Fee' );
        generateClassMultiSelect();

        $('#start_study, #end_study').on('change',function(){
            if(!checkDataLockDate($(this).attr('id'),false))
                return ;

            validateStart();
            validateEnd();

            //Kiểm tra ngày được chọn có nằm trong lịch không
            rs2 = isInSchedule($(this).val());
            if(!rs2) {
                $(this).val('').effect("highlight", {color: 'red'}, 1000);
                return ;
            }

            calSession(true);
        });
        if(record_id != ''){//In Case edit Enrollment - thong bao nhap lai ngay Start - End
            $('#start_study, #end_study').effect("highlight", {color: 'red'}, 2000);
            alertify.alert(SUGAR.language.get('J_Payment', 'LBL_EDIT_ENROLLMENT'));
            $('#classes').multiselect('deselectAll', false).multiselect('refresh');
        }

        $('#payment_list_div').closest('td').attr('colspan','4');

        $('.pay_check').live('change',function(){
            //#Bug PAY0037
            //if($(this).is(':checked')){
            //                var type        = $(this).closest('tr').find('.pay_payment_type').text();
            //                var course_fee_id  = $(this).closest('tr').find('.pay_course_fee_id').val();
            //                if(type == 'Cashholder'){
            //                    $('#coursefee').val(course_fee_id).trigger('change');
            //                    lock_coursefee = true;
            //                }else lock_coursefee = false;
            //            }
            //Comment by Tung Bui - Disable logic lock_coursefee

            //tinh toan lai before discount
            caculated();
            //            //Nap lai discount
            submitSponsor();
            submitDiscount();
            submitLoyalty();
            //            //tinh toan lai so cuoi
            caculated();
        });

        //Waiting class
        $('#tuition_hours').live('change',function(){
            var tuition_hours = Numeric.parse($('#tuition_hours').val());
            var tuition_fee    = Numeric.toInt(calCourseFee(tuition_hours));
            $('#tuition_fee').val(tuition_fee);
            caculated();
            submitSponsor();
            //Nap lai discount
            submitDiscount();
            submitLoyalty();
            //tinh toan lai so cuoi
            caculated();
        });
        //Outing Trip
        $('#tuition_fee').live('blur',function(){
            caculated();
            submitSponsor();
            submitDiscount();
            submitLoyalty();
            caculated();
        });

    }
    else{              
        //HANDLE PAYMENT
        $('#amount_bef_discount').on('blur', function(){
            var amount_bef_discount = Numeric.parse($(this).val());
            $('#tuition_fee').val(Numeric.toInt(amount_bef_discount));
            caculated();
            submitSponsor();
            submitDiscount();
            submitLoyalty();
            caculated();
        });
        $('#tuition_hours').live('change',function(){ // Edit by Tung Bui
            $('#total_hours').val($('#tuition_hours').val());
            var total_hours = Numeric.parse($('#total_hours').val());
            suggestCourseFee();
            var tuition_fee    = Numeric.toInt(calCourseFee(total_hours));
            $('#tuition_fee').val(tuition_fee);
            caculated();
            submitSponsor();
            submitDiscount();
            caculated();
        });
        switchPaymentType();
        $('select#payment_type').on('change',function(){
            switchPaymentType();
        });

        $('#tblbook').multifield({
            section :   '.row_tpl', // First element is template
            addTo   :   '#tbodybook', // Append new section to position
            btnAdd  :   '#btnAddrow', // Button Add id
            btnRemove:  '.btnRemove', // Buton remove id
        });

        $('.book_quantity, #payment_type, #is_free_book').live('change',function(){
            calBookPayment();
        });
        $('.book_id').live('change',function(){
            if($(this).val() == 'full-set'){
                var arrSet = [];
                $(this).find(":selected").closest('optgroup').find('option').each(function () {
                    if($(this).val() != 'full-set')
                        arrSet.push($(this).val());
                });
                //Xu ly add row
                var countRow = $('select.book_id').length - 1;
                var rowEq    = $(this).closest('tr').index();
                var remainRow= (countRow - rowEq) + 1;
                var countArrSet = arrSet.length;
                if(countArrSet > remainRow)
                    for (i = 0; i < (countArrSet - remainRow); i++)
                        $('#btnAddrow').trigger('click');
                $(this).val('');//Clear option
                var startAdd = rowEq;
                $.each(arrSet, function( index, value ){
                    $('select.book_id:eq('+startAdd+')').val(value);
                    $('select.book_quantity:eq('+startAdd+')').val('1');
                    startAdd++;
                });
            }
            calBookPayment();
        });
    }

    if(student_id != '' && student_id != null){
        //Load Student Info agian
        ajaxGetStudentInfo(true);
    }
    $('input.sponsor_percent, input.sponsor_amount').live('blur',function(){
        calSponsor();
    });

    $('.sponsor_number').live('change',function(){
        var std_id = $('#contacts_j_payment_1contacts_ida').val();
        if( (std_id == '' || typeof std_id == 'undefined')){                               
            alertify.alert(SUGAR.language.get('J_Payment', 'LBL_PLEASE_CHOOSE_STUDENT_BEFORE_ADD_SPONSOR'));
            $(this).closest('tr').find('.sponsor_amount, .sponsor_percent, .voucher_id, .sponsor_number, .foc_type').val('');
            return ;
        }
        $('.foc_type').find('[value="Referral"]').attr('disabled', 'disabled');
        $('.foc_type').find('[value="Referral"]').addClass('input_readonly');
        ajaxCheckVoucherCode($(this).closest('tr'), std_id);
    });

    $('.foc_type').live('change',function(){
        //        if($(this).val() == 'Referral')
        //            $(this).closest('tr').find('.campaign_code').show();
        //        else
        //            $(this).closest('tr').find('.campaign_code').hide();
        var std_id = $('#contacts_j_payment_1contacts_ida').val();
        if( (std_id == '' || typeof std_id == 'undefined')){                                 
            alertify.alert(SUGAR.language.get('J_Payment', 'LBL_PLEASE_CHOOSE_STUDENT_BEFORE_ADD_SPONSOR'));
            $(this).closest('tr').find('.sponsor_amount, .sponsor_percent, .voucher_id, .sponsor_number, .foc_type').val('');
            return ;
        }
        calSponsor();
    });

    $('#payment_amount').live('blur',function(){
        var typeArr = ['Deposit','Placement Test','Book/Gift','Cambridge','Outing Trip'];
        if(jQuery.inArray($('#payment_type').val(), typeArr) !== -1){
            var pay_amount = Math.ceil(Numeric.parse($("#payment_amount").val()) / 1000) * 1000;
            $("#payment_amount").val(Numeric.toInt(pay_amount));

            //Bổ sung hàm tự động tính tiền Split Payment
            autoGeneratePayment();
            setLoyaltyLevel();
        }
    });

    generateClassSchedule();

    if($('#is_outing').val() == '1'){
        $('#tuition_fee').removeClass('input_readonly').prop('readonly', false).effect("highlight", {color: '#ff9933'}, 3000);
        removeFromValidate('EditView', 'j_coursefee_j_payment_1j_coursefee_ida');
        $('label[for=j_coursefee_j_payment_1j_coursefee_ida]').find('.required').hide();
        $('#coursefee').val('').prop("disabled", true).next(".select2-container").hide();
    }
    //Disable Sponsor Type
    $('.foc_type').each(function(index, brand){
        if($(this).val() != 'Referral')
            $(this).find('option[value=Referral]').prop('disabled', true).addClass('input_readonly');
    });                 

    //show default list price             
    calCourseFee(Numeric.parse($('#tuition_hours').val()));
});
// Generate Dropdown Search Student
function generateStudentSelector(){
    $('#student_picker')
    .selectpicker()
    .ajaxSelectPicker({
        ajax: {
            url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
            type: "POST",
            dataType: "json",
            data: {
                type        : 'ajaxLoadStudent',
                payment_type: $("#payment_type").val(),
                q           : '{{{q}}}'
            },
        },
        locale: {
            emptyTitle: ' '
        },
        preprocessData: function (data) {
            var i, l = data.length, array = [];
            if (l) {
                for(i = 0; i < l; i++){
                    var showLead = false;
                    var typeArr = ['Placement Test','Cambridge','Outing Trip'];
                    if(jQuery.inArray($('#payment_type').val(), typeArr ) !== -1){
                        showLead = true;
                    }
                    if(showLead || data[i].type == "Student"){
                        array.push($.extend(true, data[i], {
                            text: data[i].name,
                            value: data[i].id,
                            data: {
                                subtext: "<br> Phone: "+data[i].phone_mobile + "<br>Birthdate: "+data[i].birthdate + "<br>Type: "+data[i].type
                            }
                        }));
                    }
                }
            }
            return array;
        },
        requestDelay: 0,
        restoreOnError  : true
    });
}

// Đối type của nút SAVE thành button (để không tự động save form khi user ấn enter trong input)
function changeTypeInputSubmit(inputItem){
    var newInput = inputItem.clone();
    newInput.attr("type", "button");
    newInput.insertBefore(inputItem);
    inputItem.remove();
}

//Open Popup
function set_contact_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'name':
                    var student_name = val;
                    break;
                case 'contacts_j_payment_1contacts_ida':
                    var student_id = val;
                    $("#contacts_j_payment_1contacts_ida").val(val);
                    break;
                case 'phone_mobile':
                    var phone_mobile = val;
                    break;
                case 'birthdate':
                    var birthdate = val;
                    break;
            }
        }
    }
    $('#student_picker').html('');
    $('#student_picker').selectpicker("refresh");
    // Add option to "student" select and refresh ajax bootstrap select
    $("#student_picker").html('<option selected value="'+student_id+'" data-subtext="<br>Phone: '+phone_mobile+'<br> Birthdate: '+birthdate+'">'+student_name+'</option>');
    $('.filter-option,.pull-left').text(student_name);
    ajaxGetStudentInfo(true);
}
//Show Dialog
function showDialogStudent(show){
    if (show === undefined)
        show = false;

    var json = $('input#json_student_info').val();
    var count = 0;
    var htm = '';
    if(json != '' && json != null ){
        obj = JSON.parse(json);
        //Assign to First EC
        if(obj['info']['assigned_user_id'] != null && obj['info']['assigned_user_id'] != ''){
            $('#assigned_user_name').val(obj['info']['assigned_user_name']);
            $('#assigned_user_id').val(obj['info']['assigned_user_id']);
        }

        $('#team_type').val(obj['info']['team_type']);
        //Set Loyalty Point
        $('#loy_total_points').val(Numeric.toFloat(obj.loyalty_points));
        $('#loy_loyalty_rate_out_value').val(Numeric.toFloat(obj.loyalty_rate_out_value));

        $('#loy_loyalty_rate_out_id').val(obj.loyalty_rate_out_id);
        $('.loy_student_name').html('<span style="font-weight: bold;" >'+obj['info']['student_name']+'</span>:');
        $('#loy_loyalty_mem_level').val(obj['mem_level']);
        $('#loy_net_amount').val(Numeric.toFloat(obj['net_amount']));
        //End: Set Loyalty Point
        //        if (!(typeof collection === "undefined")){
        //            $('input[name='+collection['EditView_team_name'].primary_field+']').val(obj['info']['team_name']);
        //            $('input[name=id_'+collection['EditView_team_name'].primary_field+']').val(obj['info']['team_id']);
        //        }

        htm +=  "Name: <span id='student_name'>"+ obj['info']['student_name']+"</span><br>";
        htm +=  "Mobile: "+ obj['info']['phone']+"<br>";
        htm +=  "Birthday: "+ obj['info']['birthday']+"<br>";
        htm +=  "Relative: ";
        if(obj.info.relative.length == 0)
            htm += "<br>-none-<br>";
        $.each(obj.info.relative, function( key, value ) {
            count++;
            if(count == 1)
                htm +=  "<a target='_blank' style='text-decoration: none;' href='index.php?module=Contacts&action=DetailView&record="+key+"'>"+value+"</a><br>";
            else
                htm +=  "&nbsp;&nbsp;&nbsp;&nbsp;<a target='_blank' style='text-decoration: none;' href='index.php?module=Contacts&action=DetailView&record="+key+"'>"+value+"</a><br>";
        });
        htm +=  "<hr>";

        htm +=  "<b>Study</b><br>";
        if(obj.class_list == 0)
            htm += "-none-<br>";
        var outstanding_list = {};
        $.each(obj.class_list, function(key, value){
            //Handle Log Outstanding
            if(value.type == 'OutStanding'){
                outstanding_list[key] = {}
                outstanding_list[key]['student_name']    =  value.student_name;
                outstanding_list[key]['class_id']        =  value.class_id;
                outstanding_list[key]['class_name']      =  value.class_name;
                outstanding_list[key]['total_hour']      =  unformatNumber(value.total_hour, num_grp_sep, dec_sep);
                outstanding_list[key]['total_revenue_util_now']      =  unformatNumber(value.total_revenue_util_now, num_grp_sep, dec_sep);
                outstanding_list[key]['start_study']     =  value.start_study;
                outstanding_list[key]['end_study']       =  value.end_study;
            }

            htm +=  "<b><a target='_blank' style='text-decoration: none;' href='index.php?module=J_Class&action=DetailView&record="+value.class_id+"'>"+value.class_name+"</a></b>";
            htm +=  "<p>Type: <b style='color: #FF8C00;'>"+value.type+"</b></p>";
            htm +=  "<p style='margin-left:10px;'>Hour: "+value.total_hour+" hours</p>";
            htm +=  "<p style='margin-left:10px;'>Start: "+ SUGAR.util.DateUtils.formatDate(new Date(value.start_study)) +"</p>";
            htm +=  "<p style='margin-left:10px;'>Finish: "+ SUGAR.util.DateUtils.formatDate(new Date(value.end_study))+"</p>";
        });
        $('input[name=outstanding_list]').val(JSON.stringify(outstanding_list));
        htm +=  "<hr>";
        htm +=  "<b>Payment</b><br>";
        if(obj.left_list == 0)
            htm += "-none-<br>";
        $.each(obj.left_list, function( key, value ) {
            htm +=  "<p><a target='_blank' style='text-decoration: none;' href='index.php?module=J_Payment&action=DetailView&record="+key+"'>"+value+"</a></p>";
        });
        htm +=  "<hr>";
        htm +=  "<a target='_blank' style='text-decoration: none; float: right; font-weight: bold;' href='index.php?module=Contacts&action=DetailView&record="+obj['info']['id']+"'>More Info >></a><br>";
    }else
        htm += "<em font-style:normal;'> -- No Infomation -- </em>";

    if (show) {
        $('#dialog_student_info').html(htm);
        $('#dialog_student_info').attr('title','Student Information')

        $('#dialog_student_info').dialog({
            resizable: false,
            width:'auto',
            height:'500',
            modal: false,
            visible: true,
            position: ['right',70],
        });

        $('#dialog_student_info').effect("highlight", {color: '#ff9933'}, 1000);
    }

    //Show Payment List
    var html    = '';
    var count   = 0;
    var totalAvailableHour = 0;
    var totalAvailableAmount = 0;
    
    $.each(obj.top_list, function( key, value ) {
        html += "<tr>";
        if(value['is_expired'])
            html += "<td align='right'>";
        else
            html += "<td align='right'><input type='checkbox' style='vertical-align: baseline;zoom: 1.2;' class='pay_check' value='"+value['payment_id']+"'"+value['checked']+">";
        html += "<input type='hidden' class='used_discount' value='"+value['used_discount']+"'/>";
        html += "<input type='hidden' class='use_type' value='"+value['use_type']+"'/><input type='hidden' class='pay_course_fee_id' value='"+value['course_fee_id']+"'/></td>";
        html += "<td align='center'><a target='_blank' style='text-decoration: none;font-weight: bold;' href='index.php?module=J_Payment&action=DetailView&record="+value['payment_id']+"'>"+value['payment_code']+"</a></td>";
        html += "<td align='center' class='pay_payment_type'>"+value['payment_type']+"</td>";
        html += "<td align='center'>"+value['payment_date']+"</td>";
        if(value['is_expired'])
            html += "<td align='center' style='color: red;'>"+value['payment_expired']+"</td>";
        else html += "<td align='center'>"+value['payment_expired']+"</td>";

        html += "<td align='center' class='pay_payment_amount'>"+Numeric.toInt(value['payment_amount'])+"</td>";
        html += "<td align='center' class='pay_total_hours'>"+Numeric.toFloat(value['total_hours'],2,2)+"</td>";
        html += "<td align='center' class='pay_remain_amount'>"+Numeric.toInt(value['remain_amount'])+"</td>";
        html += "<td align='center' class='pay_remain_hours'>"+Numeric.toFloat(value['remain_hours'],2,2)+"</td>";
        html += "<td align='center' class='pay_course_fee'>"+value['course_fee']+"</td>";
        html += "<td align='center'><a target='_blank' style='text-decoration: none;' href='index.php?module=Users&action=DetailView&record="+value['assigned_user_id']+"'>"+value['assigned_user_name']+"</a></td>";
        html += "</tr>";
        count++
                   
        if(value['use_type'] == 'Hour'){
            totalAvailableHour += Numeric.parse(value['total_hours']);    
        }
        else{
            totalAvailableAmount += Numeric.parse(value['payment_amount']);    
        }
    });
    if(count == 0){
        html += '<tr><td colspan="11" style="text-align: center;">No Payment Avaiable</td></tr>';
    }
    $('#tbodypayment').html(html);
    if(totalAvailableHour == 0){
        $("#btn_select_balance_hour").hide();    
    }
    else $("#btn_select_balance_hour").show();
    if(totalAvailableAmount == 0){
        $("#btn_select_balance_amount").hide(); 
    }
    else $("#btn_select_balance_amount").show(); 
    
    $("#lbl_avai_balance_hour").text(Numeric.toFloat(totalAvailableHour,2,2));
    $("#lbl_avai_balance_amount").text(Numeric.toInt(totalAvailableAmount));
}

//Ajax get Student Info
function ajaxGetStudentInfo(async){
    $('#classes').multiselect('disable');
    $('#payment_type').prop('disabled',true).addClass('input_readonly'); 
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        type: "POST",
        async: async,
        data:  {
            type            : 'ajaxGetStudentInfo',
            enrollment_id   : record_id,
            current_team_id : $('input[id=current_team_id]').val(),
            payment_type    : payment_type_begin,
            student_id      : $('#contacts_j_payment_1contacts_ida').val(),
            payment_date    : $('#payment_date').val(),
        },
        dataType: "json",
        success: function(res){
            ajaxStatus.hideStatus();
            if(res.success == "1"){
                $('input#json_student_info').val(res.content);
                showDialogStudent();
                caculated();
                submitSponsor();
                submitDiscount();
                submitLoyalty();
                caculated();
            }else
                $('input#json_student_info').val('');
            $('#classes').multiselect('enable');
            $('#payment_type').prop('disabled',false).removeClass('input_readonly');
        },
    });
}

function generateClassMultiSelect(){
    $('#classes').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '225px',
        maxHeight: 400,
        enableHTML : true,
        optionLabel: function(element)
        {
            var start_date  = $(element).attr("start_date");
            var end_date    = $(element).attr("end_date");
            var class_name  = $(element).attr("class_name");
            var class_type  = $(element).attr("class_type");
            var sub_text = "<small>";
            sub_text += "<br>"+SUGAR.language.get('J_Payment', 'LBL_CLASS_NAME')+": " + class_name;
            if(class_type == 'Normal Class'){
                sub_text += "<br>"+SUGAR.language.get('J_Payment', 'LBL_START_DATE')+": " + start_date;
                sub_text += "<br>"+SUGAR.language.get('J_Payment', 'LBL_END_DAY')+": " + end_date;
                sub_text += "<br>"+SUGAR.language.get('J_Payment', 'LBL_CLASS_TYPE')+":  <span class='textbg_green'>" + class_type + "</span>";
            }else{
                sub_text += "<br>"+SUGAR.language.get('J_Payment', 'LBL_EXPECTED_OPEN_DATE')+": " + start_date;
                sub_text += "<br>"+SUGAR.language.get('J_Payment', 'LBL_CLASS_TYPE')+":  <span class='textbg_green'>" + class_type + "</span>";
            }

            sub_text += "</small>";
            return $(element).html() + sub_text;
        },
        onChange: function(option, checked, select) {
            //Calculate End Date - Start Date
            var minStartDate = 1602288000000; //Year 2020
            var maxEndDate = 939513600000;    //Year 1999
            var countSelected   = 0;
            var countWaiting    = 0;
            var countOuting     = 0;
            var idWaiting       = '';
            //Clear Waiting class
            if(option.attr("class_type") == "Waiting Class" && checked)
                idWaiting = option.val();
            else
                $('#classes option:selected').each(function(index, brand){
                    if($(this).attr("class_type") == 'Waiting Class')
                        idWaiting = $(this).val();
                });

            if(idWaiting != ''){
                $('#classes option:selected').each(function(index, brand){
                    if(idWaiting != $(this).val())
                        $('#classes').multiselect('deselect', $(this).val(), true);
                });
            }

            $('#classes option:selected').each(function(index, brand){
                var start = SUGAR.util.DateUtils.parse($(this).attr("start_date"),cal_date_format).getTime();
                var end = SUGAR.util.DateUtils.parse($(this).attr("end_date"),cal_date_format).getTime();
                if(minStartDate > start)
                    minStartDate = start;
                if(maxEndDate < end)
                    maxEndDate = end;
                // Open readonly Tuition Hours - WAITING CLASS
                countSelected++;
                var class_type = $(this).attr("class_type");
                if(class_type == 'Waiting Class')
                    countWaiting++;

                //Count Kind of course
                var kind_of_course = $(this).attr("kind_of_course");
                if(kind_of_course == 'Outing Trip' || kind_of_course == 'Short Course')
                    countOuting++;
            });

            if(countWaiting == countSelected && countSelected == 1)
                $('#tuition_hours').removeClass('input_readonly').prop('readonly', false).effect("highlight", {color: '#ff9933'}, 3000);
            else
                $('#tuition_hours').addClass('input_readonly').prop('readonly', true);

            if(countOuting == countSelected && countSelected == 1){
                $('#tuition_fee').removeClass('input_readonly').prop('readonly', false).effect("highlight", {color: '#ff9933'}, 3000);
                removeFromValidate('EditView', 'j_coursefee_j_payment_1j_coursefee_ida');
                $('label[for=j_coursefee_j_payment_1j_coursefee_ida]').hide();
                $('#coursefee').val('').prop("disabled", true).next(".select2-container").hide();
                var is_outing = true;
            }else{
                $('#tuition_fee').addClass('input_readonly').prop('readonly', true);
                addToValidate('EditView', 'j_coursefee_j_payment_1j_coursefee_ida', 'enum', true,'Course Fee ID' );
                $('label[for=j_coursefee_j_payment_1j_coursefee_ida]').show();
                $('#coursefee').val('').prop("disabled", false).next(".select2-container").show();
                var is_outing = false;
            }

            if($('#classes option:selected').length == 0)
                $('#class_start, #class_end, #start_study, #end_study').val('');
            else{
                $('#start_study').val(SUGAR.util.DateUtils.formatDate(new Date()));
                $('#class_start').val(SUGAR.util.DateUtils.formatDate(new Date(minStartDate)));
                $('#end_study, #class_end').val(SUGAR.util.DateUtils.formatDate(new Date(maxEndDate)));
            }
            //Check Outstanding
            var oustanding = outstandingChecking(option.val(), checked);
            calSession(true);
            if(countSelected > 0 && !is_outing){
                suggestCourseFee();
                $("#coursefee").trigger('change');     //Mới thay đổi
            }else{
                $("#coursefee").val('');
            }
            if(is_outing) $('#is_outing').val('1');
            else $('#is_outing').val('0');
            generateClassSchedule();    
        },
        filterPlaceholder: SUGAR.language.get('J_Payment', 'LBL_SELECT_CLASS')
    });
}

function outstandingChecking(class_id, checked){
    //Check Outstanding
    var outstanding_list = $('input[name=outstanding_list]').val();
    if(outstanding_list != '')
        var obj_outstanding = JSON.parse(outstanding_list);
    else obj_outstanding = '';

    var countOst = 0;
    $.each(obj_outstanding, function( key, value ) {
        if(class_id == value.class_id){
            countOst++;
            classOst    = value.class_id;
            student_name= value.student_name;
            classNameOst= value.class_name;
            startOst    = SUGAR.util.DateUtils.formatDate(new Date(value.start_study));
            endOst      = SUGAR.util.DateUtils.formatDate(new Date(value.end_study));
            hourOst     = Numeric.toFloat(value.total_hour,2,2);
            hourOstUtilNow     = Numeric.toFloat(value.total_revenue_util_now,2,2);
            hourRest     = Numeric.toFloat(value.total_hour - value.total_revenue_util_now,2,2);
        }
    });
    if(countOst > 0 && checked){
        alertify.confirm('Student <b>'+student_name+'</b> has been added outstanding <br>in class <b>'+classNameOst+'</b><br><br>Total Outstanding Hours(until '+$('#payment_date').val()+'): <b>'+hourOstUtilNow+' hours</b>.<br>(Change Payment Date to calculate Total Outstanding Hours)<br> Total continuing hours: <b>'+hourRest+' hours</b>.<br>Total hours: <b>'+hourOst+' hours</b>.<br>Does he/she want to pay now ?', function (e) {
            if (e) {
                $('#classes').multiselect('select', class_id, false);
                $('#classes').removeAttr('multiple').val(class_id).multiselect("destroy").addClass('input_readonly').find('option:not(:selected)').prop('disabled', true);
                $('#start_study').val(startOst).addClass('input_readonly').prop('readonly', true);
                $('#end_study').val(endOst).addClass('input_readonly').prop('readonly', true);
                $('#start_study_trigger, #end_study_trigger').hide();
                $('input[name=is_outstanding]').val('1');
                calSession(false);
                return true;             //not generate start date
            }else{
                $('#classes').val('').attr('multiple','multiple').multiselect("destroy").find('option:not(:selected)').prop('disabled', false);
                generateClassMultiSelect();
                $('#classes').multiselect('deselect', class_id).multiselect('refresh');
                $('#start_study').val('').removeClass('input_readonly').prop('readonly', false);
                $('#end_study').val('').removeClass('input_readonly').prop('readonly', false);
                $('#start_study_trigger, #end_study_trigger').show();
                $('input[name=is_outstanding]').val('0');

                alertify.success(SUGAR.language.get('J_Payment', 'LBL_PLEASE_SELECT_ANOTHER_CLASS'));  
                calSession(true);
                return true;
            }
        });
    }else
        return false;

}

function calSession(generate_start_date){
    if($('#start_study').val() != '' && $('#end_study').val() != ''){
        var start_study         = SUGAR.util.DateUtils.parse($('#start_study').val(),cal_date_format);
        var end_study           = SUGAR.util.DateUtils.parse($('#end_study').val(),cal_date_format);
        var is_outstanding      = $('input[name=is_outstanding]').val();
        YAHOO.widget.DateMath._addDays(end_study,1) //+ 1days
        var now_date = Date.today();
        if(start_study < now_date && is_outstanding != '1')
        alertify.success(SUGAR.language.get('J_Payment', 'LBL_MAKE_SURE_ADD_STUDENT_TO_THIS_CLASS'));  

        var count = 0
        var tuition_hours= 0;
        var class_list = {};
        if(generate_start_date)
            $('#start_study').val(SUGAR.util.DateUtils.formatDate(new Date()));
        $('#classes option:selected').each(function(index, brand){
            var class_ = $(this);
            var seleted_class_id = $(this).val();
            isHistory(seleted_class_id);
            class_list[seleted_class_id] =  {};
            var start_obj   = '';
            var end_obj     = '';
            var hour_obj    = 0;
            var count_inside= 0;

            // Calculate tuition hours
            obj = JSON.parse(class_.attr("json_ss"));
            $.each(obj, function( key, value ) {
                if((new Date(key) <= end_study) && (new Date(key) >= start_study)){
                    count++;
                    if(count == 1 && generate_start_date) //Set start study is first date schedule
                        $('#start_study').val(SUGAR.util.DateUtils.formatDate(new Date(key)));
                    count_inside++;
                    tuition_hours   = tuition_hours + parseFloat(value);
                    hour_obj        = hour_obj + parseFloat(value);
                    if(count_inside == 1) start_obj = key;
                    end_obj = key;
                }
            });
            class_list[$(this).val()]['class_id']       = $(this).val();
            class_list[$(this).val()]['total_hour']     = hour_obj;
            class_list[$(this).val()]['start_study']    = start_obj;
            class_list[$(this).val()]['end_study']      = end_obj;
        });
        $('#sessions').val(count);
        $('#tuition_hours').val(Numeric.toFloat(tuition_hours,2,2));

        var class_content = JSON.stringify(class_list);
        $('input[name=content]').val(class_content);

        var tuition_fee = Numeric.toInt(calCourseFee(tuition_hours));
        $('#tuition_fee').val(tuition_fee);
    }
    else{
        $('#sessions').val(0);
        $('#tuition_hours').val(0);
        $('#tuition_fee').val(0);
    }

    //tinh toan lai before discount
    caculated();
    //Nap lai discount
    submitSponsor();
    submitDiscount();
    submitLoyalty();
    //tinh toan lai so cuoi
    caculated();
}
//Check Class is history Class
function isHistory(class_id){
    var json_class = $('#class_list').val();
    var found = 0;
    if(json_class != '' && json_class != null){
        obj_class = JSON.parse(json_class);

        $.each(obj_class, function( key, idclass ) {
            if(idclass.id == class_id)
                found++;
        });
    }
    if(found > 0){
        alertify.error(SUGAR.language.get('J_Payment', 'LBL_ERROR_CLASS_HAS_STARTED'));     
        return true;
    }
    else return false;
}

function showDialogDiscount(){
    $( "#dialog_discount" ).dialog({
        resizable: false,
        width: 900,
        modal: true,
        hideCloseButton: true,
        buttons: {
            "Submit":{
                click:function() {
                    submitSponsor();
                    submitDiscount();
                    submitLoyalty();
                    caculated();
                    $(this).dialog('close');
                },
                class: 'button primary',
                text: SUGAR.language.get('J_Payment', 'LBL_SUBMIT'),
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                text: SUGAR.language.get('J_Payment', 'LBL_CANCEL'),
            },
        }, 
        beforeClose: function(event, ui) {
            $("body").css({ overflow: 'inherit' });
        },
    });
}
function showDialogSponsor(){
    $( "#dialog_sponsor" ).dialog({
        resizable: false,
        width: 700,
        modal: true,
        hideCloseButton: true,
        buttons: {
            "Submit":{
                click:function() {
                    submitSponsor();
                    submitDiscount();
                    submitLoyalty();
                    caculated();
                },
                class: 'button primary',
                text: SUGAR.language.get('J_Payment', 'LBL_SUBMIT'),
            },
            "Cancel": {
                click:function() {
                    var sponsor_list = $('#sponsor_list').val();
                    if(sponsor_list == '' || sponsor_list == '{}' || sponsor_list == '[]'){
                        $('.sponsor_number').not(':eq(0)').val('');
                        $('.foc_type').not(':eq(0)').val('');
                        $('.sponsor_amount').not(':eq(0)').val('');
                        $('.sponsor_percent').not(':eq(0)').val('');
                        $('.btnRemoveSponsor').not(':eq(1)').not(':eq(0)').trigger('click');
                        $(this).dialog('close');
                    }else{
                        submitSponsor();
                        submitDiscount();
                        submitLoyalty();
                        caculated();
                    }
                },
                text: SUGAR.language.get('J_Payment', 'LBL_CANCEL'),
            },
        },
        beforeClose: function(event, ui) {
            $("body").css({ overflow: 'inherit' });
        },
    });
}
function showDialogLoyalty(){
    $( "#dialog_loyalty" ).dialog({
        resizable: false,
        width: 600,
        modal: true,
        hideCloseButton: true,
        buttons: {
            "Submit":{
                click:function() {
                    submitSponsor();
                    submitDiscount();
                    submitLoyalty();
                    caculated();
                    $(this).dialog('close');
                },
                class: 'button primary',
                text: 'Submit',
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                text: SUGAR.language.get('J_Payment', 'LBL_CANCEL'),
            },
        },
        beforeClose: function(event, ui) {
            $("body").css({ overflow: 'inherit' });
        },
    });
}
//Suggest Course Fee
function suggestCourseFee(){                     
    if(!lock_coursefee){
        var total_hours     = Numeric.parse($('#total_hours').val()) + Numeric.parse($('#paid_hours').val());
        var payment_type    = $('#payment_type').val();
        var kind_of_course = [];                                        

        $('#classes option:selected').each(function(){   
            if(jQuery.inArray($(this).attr("kind_of_course"), kind_of_course) < 0){
                kind_of_course.push($(this).attr("kind_of_course"));   
            }    
        });    

        //De xuat lua chon
        if(kind_of_course.length > 0){  
            var first_koc = kind_of_course[0];  

            $("#coursefee option").each(function(){  
                if($(this).attr("apply_for") !== undefined && $(this).attr("apply_for") != ''){
                    var apply_for = $(this).attr("apply_for").split(',');
                    if(jQuery.inArray(first_koc, apply_for) >= 0){
                        $("#coursefee").val($(this).val());
                        $("#coursefee").trigger("change");
                        return;
                    }    
                }     
            });  
        }     
    }
}
//Calculate Course Fee
function calCourseFee(tuition_hours){
    var course_fee = parseFloat($("#coursefee option:selected").attr('price'));
    var course_hour = parseInt($("#coursefee option:selected").attr('type'));

    price = course_fee / course_hour;                    

    $("#list_price_per_hour").val(Numeric.toInt(price));

    return tuition_hours * price;
}

function setLoyaltyLevel(){
    var current_level        = $('#loy_loyalty_mem_level').val();
    var net_amount           = Numeric.parse($('#loy_net_amount').val());
    var amount_bef_discount  = Numeric.parse($('#amount_bef_discount').val());
    var discount_amount      = Numeric.parse($('#discount_amount').val());
    var final_sponsor        = Numeric.parse($('#final_sponsor').val());
    var loyalty_amount       = Numeric.parse($('#loyalty_amount').val());
    var payment_amount       = Numeric.parse($('#payment_amount').val());
    var sum_current_amount   = net_amount + payment_amount + discount_amount + final_sponsor + loyalty_amount;
    if(payment_amount <= 0 || payment_amount == '')
        sum_current_amount   = net_amount;

    var rank_link            = [];
    var level                = 'N/A';
    var html                 = '';
    if(typeof SUGAR.language.languages['app_list_strings'] != 'undefined')
        rank_link = SUGAR.language.languages['app_list_strings']['loyalty_rank_list'];

    if(typeof rank_link != 'undefined'){
        if(sum_current_amount >= parseInt(rank_link['Blue']))
            level = 'Blue';
        if(sum_current_amount >= parseInt(rank_link['Gold']))
            level = 'Gold';
        if(sum_current_amount >= parseInt(rank_link['Platinum']))
            level = 'Platinum';
    }
    //Set HTML
    if(level == 'Platinum'){
        html = '<label><span class="textbg_black">'+level+'</span></label>';
    }else if(level == 'Gold'){
        html = '<label><span class="textbg_yellow">'+level+'</span></label>';
    }else if(level == 'Blue'){
        html = '<label><span class="textbg_bluelight">'+level+'</span></label>';
    }else{
        html = '<label><span class="textbg_nocolor">'+level+'</span></label>';
    }
    $('.loy_loyalty_mem_level').html(html);
    $('#loy_loyalty_mem_level').val(level);
    return level;
}

function caculated(){     
    // Caculate Payment list
    var payment_type = $('#payment_type').val();

    var payment_list = {};
    payment_list['paid_list']       =  {};
    payment_list['deposit_list']    =  {};
    var total_used_amount  = 0;
    var total_used_hours  = 0;
    var total_deposit_amount  = 0;
    var tuition_fee         = Numeric.parse($('#tuition_fee').val());
    var amount_bef_discount = Numeric.parse($('#amount_bef_discount').val());
    var discount_amount     = Numeric.parse($('#discount_amount').val());
    var tuition_hours       = Numeric.parse($('#tuition_hours').val());
    var discount_percent    = Numeric.parse($('#discount_percent').val());
    var loyalty_amount      = Numeric.parse($('#loyalty_amount').val());
    var loyalty_percent     = Numeric.parse($('#loyalty_percent').val());

    var payment_amount      = 0;
    var remaining_freebalace      = 0;

    var final_sponsor           = Numeric.parse($('#final_sponsor').val());
    var final_sponsor_percent   = Numeric.parse($('#final_sponsor_percent').val());

    var price_enroll = (tuition_fee) / (tuition_hours); // đơn giá tổng
    if (!isFinite(price_enroll)) price_enroll = 0;
    var total_hours = parseFloat(tuition_hours);

    // add paid payment to json
    var count_pay = 0;

    $('#tblpayment').find('.pay_check:checked').each(function(index, brand){
        var payment_related_type = $(this).closest('tr').find('.pay_payment_type').text();
        var use_type    = $(this).closest('tr').find('.use_type').val();
        var paid_type   = ["Cashholder"];
        var used_amount = Numeric.parse($(this).closest('tr').find('.pay_remain_amount').text());
        var used_hours  = Numeric.parse($(this).closest('tr').find('.pay_remain_hours').text());
        if(($.inArray(payment_related_type,paid_type) >= 0 || use_type == "Hour") && (total_hours > 0) && (used_hours > 0)){
            var temp_price = used_amount / used_hours;
            total_hours = total_hours - used_hours;
            if(total_hours < 0){
                used_hours = used_hours + total_hours;
                total_hours = 0;
            }
            used_amount = temp_price * used_hours;

            total_used_amount += used_amount;
            total_used_hours += used_hours;

            payment_list['paid_list'][$(this).val()] = {};
            payment_list['paid_list'][$(this).val()]["id"] = $(this).val();
            payment_list['paid_list'][$(this).val()]["used_amount"] = used_amount;
            payment_list['paid_list'][$(this).val()]["used_hours"] = used_hours;
            count_pay++;

        }
    });
    if (payment_type == "Cashholder") amount_bef_discount = calCourseFee(total_hours);
    else if (payment_type == "Enrollment") amount_bef_discount = price_enroll * total_hours;

    //Get Ratio - Ratio áp dụng cho Discount và Sponsor
    var coursefee_hour = parseInt($("#coursefee option:selected").attr('type'));
    var ratio = 1;
    if(total_hours < coursefee_hour && total_hours != 0 && coursefee_hour != 0)
        ratio = total_hours / coursefee_hour;

    var total_after_discount = amount_bef_discount - discount_amount - final_sponsor;

    payment_amount = total_after_discount - loyalty_amount;
    // add deposit payment to json
    $('#tblpayment').find('.pay_check:checked').each(function(index, brand){
        var payment_type = $(this).closest('tr').find('.pay_payment_type').text();
        var use_type = $(this).closest('tr').find('.use_type').val();
        var used_amount = Numeric.parse($(this).closest('tr').find('.pay_remain_amount').text());
        if((payment_type == 'Deposit' || use_type == "Amount") && (payment_amount > 0) && (used_amount > 0)){
            payment_amount = payment_amount - used_amount;
            if(payment_amount < 0){
                used_amount = used_amount + payment_amount;
                remaining_freebalace += payment_amount;
                payment_amount = 0;
            }
            total_deposit_amount += used_amount;

            payment_list['deposit_list'][$(this).val()] = {};
            payment_list['deposit_list'][$(this).val()]["id"] = $(this).val();
            payment_list['deposit_list'][$(this).val()]["used_amount"] = used_amount;
            payment_list['deposit_list'][$(this).val()]["used_hours"] = 0;
            count_pay++;
        }
    });
    var str_json_payment_list = '';
    if(count_pay > 0)
        str_json_payment_list = JSON.stringify(payment_list);

    $('#payment_list').val(str_json_payment_list);      

    //Assign money
    $('#tuition_fee').val(Numeric.toInt(tuition_fee));
    //không làm tròn 2 số này
    $('#paid_amount').val(Numeric.toInt(total_used_amount));
    $('#deposit_amount').val(Numeric.toInt(total_deposit_amount));

    $('#amount_bef_discount').val(Numeric.toInt(amount_bef_discount));
    $('#total_after_discount').val(Numeric.toInt(total_after_discount));
    $('#remaining_freebalace').val(Numeric.toInt(Math.abs(remaining_freebalace)));
    $('#payment_amount').val(Numeric.toInt(payment_amount));

    //Assign hour
    $('#tuition_hours').val(Numeric.toFloat(tuition_hours,2,2));
    $('#paid_hours').val(Numeric.toFloat(total_used_hours,2,2));

    $('#total_hours').val(Numeric.toFloat(total_hours,2,2));
    $('#ratio').val(Numeric.toFloat(ratio,3,3));
    var accrual_rate_value = $('input#accrual_rate_value').val();
    $('input#total_rewards_amount').val(Numeric.toInt(Math.floor ( (payment_amount * accrual_rate_value) / 1000 ) * 1000));
    //Bổ sung hàm tự động tính tiền Split Payment
    autoGeneratePayment();
    setLoyaltyLevel();
}

function ajaxCheckVoucherCode(self ,student_id){
    var voucher_code    = self.find('.sponsor_number').val();
    if(voucher_code != ''){
        //Ajax check Sponsor code
        ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
        $.ajax({
            url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  {
                type            : 'ajaxCheckVoucherCode',
                voucher_code    : voucher_code,
                student_id      : student_id,
                payment_id      : record_id,
                payment_date    : $('#payment_date').val(),
            },
            dataType: "json",
            success: function(res){
                ajaxStatus.hideStatus();
                if(res.success == "1"){
                    var status          = ' <b style="color:green;"> '+SUGAR.language.get('app_list_strings','voucher_status_dom')['Activated']+'</b>';
                    var discount_amount = '<br>'+SUGAR.language.get('J_Payment', 'LBL_DISCOUNT_AMOUNT')+': '+res.discount_amount;
                    var discount_percent= '<br>'+SUGAR.language.get('J_Payment', 'LBL_DISCOUNT_PERCENT')+': '+res.discount_percent;
                    if(res.discount_amount == 0) discount_amount = '';
                    if(res.discount_percent == 0) discount_percent = '';

                    var description = ''
                    if(res.description != '') description = ' ('+res.description+')';

                    var owner = ''
                    if(res.student_name != '') owner = '<br>'+SUGAR.language.get('J_Payment', 'LBL_OWNER')+': ' + res.student_name;

                    if(res.status == 'Expired' || res.status == 'Inactive' ){
                        status = ' <b style="color:red;"> '+SUGAR.language.get('app_list_strings','voucher_status_dom')[res.status]+'</b>';
                        self.find('.sponsor_amount, .sponsor_percent, .voucher_id, .sponsor_number, .foc_type').val('');
                    }else{
                        self.find('.sponsor_amount').val(res.discount_amount);
                        self.find('.sponsor_percent').val(res.discount_percent);
                        self.find('.voucher_id').val(res.voucher_id);
                        self.find('.foc_type').val(res.foc_type);
                    }
                    self.find('.foc_type').trigger('change');

                    alertify.alert(SUGAR.language.get('J_Payment', 'LBL_SPONSOR_NAME')+': '+ res.voucher_code +'<br>'+ description +'<br>'+SUGAR.language.get('J_Payment', 'LBL_EXPIRES')+': ' + res.end_date + status + owner  + discount_amount + discount_percent + '<br>'+SUGAR.language.get('J_Payment', 'LBL_TOTAL_REFERRALS')+': '+ res.used_time + ' / '+ res.use_time + '<br>'+SUGAR.language.get('J_Payment', 'LBL_TOTAL_REDEMPTION')+': '+res.redempt_time);

                    // Remove disable Referral when input code Referral
                    if(res.foc_type == 'Referral') {
                        $('.foc_type').find('[value="Referral"]').attr('disabled', false);
                        $('.foc_type').find('[value="Referral"]').removeClass('input_readonly');
                    }
                }else if(res.success == "0"){
                    var old_voucher_id = self.find('.voucher_id').val();
                    // if(old_voucher_id != '') Edit by Nguyen Tùng
                    self.find('.sponsor_amount, .sponsor_percent, .voucher_id, .sponsor_number, .foc_type').val('');
                    alertify.alert(SUGAR.language.get('J_Payment', 'LBL_SPONSOR_NOT_FOULD'));
                }else if(res.success == "-1"){
                    self.find('.sponsor_amount, .sponsor_percent, .voucher_id, .sponsor_number, .foc_type').val('');
                    alertify.alert(SUGAR.language.get('J_Payment', 'LBL_CODE_USED'));
                }
                calSponsor();
            },
        });
        //END
    }

}

function calLoyalty(){
    var points              = Number(Numeric.parse($('#loy_loyalty_points').val()));
    var max_points          = Number(Numeric.parse($('#loy_total_points').val()));
    var rate_out            = Numeric.parse($('#loy_loyalty_rate_out_value').val());
    var rate_out_id         = $('#loy_loyalty_rate_out_id').val();
    var total_after_discount= Numeric.parse( $('#total_after_discount').val());
    var catch_limit         = $('#catch_limit').val();
    var limited_discount    = Numeric.parse($('#limited_discount_amount').val());    //limit discount
    var discount_amount     = Numeric.parse($('#discount_amount').val());
    var max_policy_points   = Number((limited_discount - discount_amount) / rate_out);
    if(max_points <= 0){
        $('#loy_loyalty_points').val(0).prop('disabled',true).addClass('input_readonly');
        $('#loy_error').text('Note: This student have no points to use.');
    }else if(catch_limit == '1'){
        $('#loy_loyalty_points').val(0).prop('disabled',true).addClass('input_readonly');
        $('#loy_error').text('Note: Limited Discount '+limit_discount_percent+'%. This student can not use loyalty points!');
    }else{
        $('#loy_loyalty_points').prop('disabled',false).removeClass('input_readonly');
        $('#loy_error').text('');
    }

    if( (points < min_points || points > max_points || isNaN(points)) && max_points > 0 ){
        alertify.error('Invalid Value: '+points+'. Loyalty Point should be within the valid range '+min_points+' -> '+max_points);
        $('#loy_loyalty_points').val(1).effect("highlight", {color: '#ff9933'}, 1000);
        calLoyalty();
        return ;
    }

    if(max_policy_points > 0 && points > max_policy_points){
        alertify.error('Note: Limited Discount '+limit_discount_percent+'%. Maximum loyalty points can be use: '+max_policy_points+' points.');
        $('#loy_loyalty_points').val(max_policy_points).effect("highlight", {color: '#ff9933'}, 1000);
        calLoyalty();
        return ;
    }

    //Tính Loyalty
    var amount_to_spend = points * rate_out;
    var blend_balance   = amount_to_spend - total_after_discount;
    var blend_point     = Math.floor(blend_balance/rate_out);
    if( blend_point > 0 ){
        alertify.error(SUGAR.language.get('J_Payment', 'LBL_ERROR_TOTAL_LOYALTY_MUST_GRATE_THAN_TOTAL_AMOUNT'));      
        $('#loy_loyalty_points').val(points - blend_point);
        calLoyalty();
        return ;
    }

    $('.loy_points_to_spend').text(Numeric.toFloat(points) + ' points ('+ Numeric.toFloat(amount_to_spend) +' VND)' );
    $('#loy_points_to_spend').val(Numeric.toFloat(points));
    $('#loy_amount_to_spend').val(Numeric.toFloat(amount_to_spend));
    $('.loy_total_points').text(Numeric.toFloat(max_points) + ' points');
    $('.loy_loyalty_rate_out_value').text(Numeric.toFloat(rate_out));
}

function submitLoyalty(){
    calLoyalty();
    var amount_bef_discount     = Numeric.parse($('#amount_bef_discount').val());
    var loy_loyalty_mem_level   = $('#loy_loyalty_mem_level').val();
    var loyalty_list            = {};
    loyalty_list['points_to_spend'] = $('#loy_points_to_spend').val();
    loyalty_list['amount_to_spend'] = $('#loy_amount_to_spend').val();
    loyalty_list['max_points']  = $('#loy_total_points').val();
    loyalty_list['min_points']  = min_points;
    loyalty_list['rate_out']    = $('#loy_loyalty_rate_out_value').val();
    loyalty_list['rate_out_id'] = $('#loy_loyalty_rate_out_id').val();
    if(Numeric.parse(loyalty_list['amount_to_spend']) > 0){
        var loyalty_percent = (Numeric.parse(loyalty_list['amount_to_spend']) / amount_bef_discount) * 100;
        $('#loyalty_list').val(JSON.stringify(loyalty_list));
        $('#loyalty_amount').val(loyalty_list['amount_to_spend']);
        $('#loyalty_percent').val(Numeric.toFloat(loyalty_percent,2,2));
    }else{
        $('#loyalty_list').val('');
        $('#loyalty_amount').val(0);
        $('#loyalty_percent').val(0);
    }
    $( "#dialog_loyalty" ).dialog('close');
}


function calSponsor(){
    var total_sponsor_percent       = 0;
    var total_sponsor_amount        = 0;
    var count_referal               = 0;
    var ratio                       = Numeric.parse($('#ratio').val());
    var amount_bef_discount         = Numeric.parse($('#amount_bef_discount').val());
    $('.row_tpl_sponsor').not(":eq(0)").each(function(index, brand){
        var sponsor_amount = Numeric.parse($(this).find('.sponsor_amount').val());
        var sponsor_percent = Numeric.parse($(this).find('.sponsor_percent').val());
        var foc_type = $(this).find('.foc_type').val();
        if(foc_type == 'Referral' || foc_type == 'Staff children' || foc_type == 'Management' || foc_type == 'Retake' || foc_type == 'BUV, BEP'){
            count_referal++;
            total_sponsor_amount += sponsor_amount
        }else
            total_sponsor_amount += (sponsor_amount * ratio)

        total_sponsor_percent  += sponsor_percent;
    });

    //Tính Sponsor
    $('.sponsor_amount_bef_discount').text(Numeric.toInt(amount_bef_discount));
    $('.total_sponsor_amount').text(Numeric.toInt(total_sponsor_amount));
    $('.total_sponsor_percent').text(Numeric.toFloat(total_sponsor_percent,2,2));
    $('#sponsor_ratio').html('&nbsp;&nbsp;&nbsp;Ratio: <b>'+Numeric.toFloat(ratio,3,3)+'</b>');

    var total_sponsor_percent_to_amount = ((amount_bef_discount - total_sponsor_amount) * total_sponsor_percent / 100);

    var final_sponsor = total_sponsor_amount + total_sponsor_percent_to_amount;
    var final_sponsor_percent = (final_sponsor / amount_bef_discount) * 100;

    $('.final_sponsor').text(Numeric.toInt(final_sponsor));
    $('.final_sponsor_percent').val(Numeric.toFloat(final_sponsor_percent,2,2));
    $('.total_sponsor_percent_to_amount').val(Numeric.toInt(total_sponsor_percent_to_amount));

}

function submitSponsor(){
    calSponsor();
    var sponsor_list = {};
    var count = 0;
    var count_error = 0;
    var ratio = Numeric.parse($('#ratio').val());
    var total_sponsor_percent_to_amount  = Numeric.parse($('.total_sponsor_percent_to_amount').val());
    var total_sponsor_percent            = Numeric.parse($('.total_sponsor_percent').text());
    $('.row_tpl_sponsor').not(":eq(0)").each(function(index, brand){
        var total_sponsor_down = 0;
        var sponsor_number  = $(this).find('.sponsor_number').val();
        var foc_type        = $(this).find('.foc_type').val();
        var campaign_code   = $(this).find('.campaign_code').val();
        var voucher_id      = $(this).find('.voucher_id').val();
        var sponsor_amount  = Numeric.parse($(this).find('.sponsor_amount').val());
        var sponsor_percent = Numeric.parse($(this).find('.sponsor_percent').val());
        if(sponsor_amount != 0 || sponsor_percent != 0 ){
            if(foc_type == ''){      
                $(this).find('.foc_type').effect("highlight", {color: '#FF0000'}, 2000);
                count_error++;
                return;
            }
            if(sponsor_number == ''){
                $(this).find('.sponsor_number').effect("highlight", {color: '#FF0000'}, 2000);
                count_error++;
                return;
            }
        }
        if(sponsor_percent > 100){
            count_error++;
            return;
        }
        if(foc_type == 'Referral'){
            total_sponsor_down += sponsor_amount;
        }else
            total_sponsor_down += (sponsor_amount * ratio)
        if(total_sponsor_percent != 0)
            total_sponsor_down += total_sponsor_percent_to_amount * (sponsor_percent/total_sponsor_percent);
        if(sponsor_number != '' && foc_type != ''){
            sponsor_list[count]                     = {};
            sponsor_list[count]['voucher_id']       = voucher_id;
            sponsor_list[count]['sponsor_number']   = sponsor_number;
            sponsor_list[count]['foc_type']         = foc_type;
            sponsor_list[count]['campaign_code']    = campaign_code;
            sponsor_list[count]['sponsor_amount']   = Numeric.toInt(sponsor_amount);
            sponsor_list[count]['sponsor_percent']  = Numeric.toFloat(sponsor_percent,2,2);
            sponsor_list[count]['total_down']       = Numeric.toInt(total_sponsor_down);
            count++;
        }   
    });
    if(count_error > 0){                                                
        alertify.error(SUGAR.language.get('J_Payment', 'LBL_PLEASE_FILL_OUT_INFO_COMPLETELY'));  
        return false;
    }
    $('#sponsor_list').val(JSON.stringify(sponsor_list));
    $('#final_sponsor').val($('.final_sponsor').text());
    $('#final_sponsor_percent').val($('.final_sponsor_percent').val());
    $( "#dialog_sponsor" ).dialog('close');
}

function reloadDiscount(){
    //Remove all selected
    $("#discount_reward option:selected").prop("selected", false);
    $("select.discount_partnership option:selected").prop("selected", false);
    $('.dis_check').prop("checked", false);
    //Parse and Ship json
    var json = $('input#discount_list').val();
    if(json != '' && json != null){
        obj = JSON.parse(json);

        $.each(obj, function(id, dis_obj) {
            if(dis_obj.type == 'Reward')
                $('#discount_reward option[value=' + id + ']').prop('selected',true);
            else if(dis_obj.type == 'Partnership'){
                $.each($('input.dis_check'), function(index, brand) {
                    if($(this).val() == dis_obj.id){
                        $(this).closest('tr').find('select.discount_partnership').val(dis_obj.partnership_id);
                        $(this).closest('tr').find('.dis_check').prop('checked',true);
                    }
                });
            }
            else
                $('.dis_check[value=' + id + ']').prop('checked',true);
        });
    }
}

function calDiscount(){
    checkAvailableDiscount();
    //Handle schema apply with discount
    $('.dis_amount_bef_discount').text($('#amount_bef_discount').val());
    var dis_amount_bef_discount = Numeric.parse($('#amount_bef_discount').val());
    var dis_total_hours     = Numeric.parse($('#total_hours').val());
    var dis_tuition_hours   = Numeric.parse($('#tuition_hours').val());
    var current_loyalty     = $('#loy_loyalty_mem_level').val();
    var ratio = Numeric.parse($('#ratio').val());
    var final_sponsor   = Numeric.parse($('#final_sponsor').val());
    var loyalty_amount  = Numeric.parse($('#loyalty_amount').val());
    var loyalty_points  = Numeric.parse($('#loy_loyalty_points').val());
    var accrual_rate_value = 0;
    var is_accumulate = $("#coursefee option:selected").attr('is_accumulate');
    if(is_accumulate == 1){
        if(typeof SUGAR.language.languages['app_list_strings'] != 'undefined')
            accrual_rate_value = Numeric.parse(SUGAR.language.languages['app_list_strings']['default_loyalty_rate']['Accrual Rate ('+current_loyalty+')']);
    }

    var dis_discount_amount     = 0;
    var dis_discount_percent    = 0;
    var dis_discount_percent_p  = 0;
    var dis_discount_percent_o  = 0;
    var total_reward_amount     = 0;
    var total_reward_percent    = 0;
    var partnership_amount      = 0;
    var partnership_percent     = 0;
    var dis_not_count_limit_amount  = 0;
    var dis_total_discount      = 0;
    var dis_total_discount_amount      = 0;
    var dis_total_discount_percent     = 0;
    var dis_chain_discount = 0;
    var dis_discount_on_top = 0;

    // calculate Reward
    var reward_array = [];
    reward_array = $("#discount_reward").val();
    total_reward_amount = 0;
    total_reward_percent = 0;
    if (reward_array != null){
        $.each(reward_array, function(index,value) {
            if(total_reward_amount < Numeric.parse($("#discount_reward option[value='"+value+"']").attr("dis_amount"))){
                reward_is_ratio     = $("#discount_reward option[value='"+value+"']").attr("dis_is_ratio");
                dis_is_catch_limit  = $("#discount_reward option[value='"+value+"']").attr("dis_is_catch_limit");
                if(reward_is_ratio == 1)
                    total_reward_amount = (total_reward_amount * ratio);
                if(dis_is_catch_limit == 0)
                    dis_not_count_limit_amount += total_reward_amount;
            }
            total_reward_amount  =  Numeric.parse($("#discount_reward option[value='"+value+"']").attr("dis_amount"));
            if(total_reward_percent < Numeric.parse($("#discount_reward option[value='"+value+"']").attr("dis_percent")))
                total_reward_percent =  Numeric.parse($("#discount_reward option[value='"+value+"']").attr("dis_percent"));
            var is_chain_discount = $("#discount_reward option[value='"+value+"']").attr("is_chain_discount");
            if(is_chain_discount == 1)
                dis_chain_discount++;

            var is_discount_on_top = $("#discount_reward option[value='"+value+"']").attr("is_discount_on_top");
            if(is_discount_on_top == 1)
                dis_discount_on_top++;
        });

        dis_discount_amount += total_reward_amount;
        if(dis_discount_percent > 0) //Chain Discount
            dis_discount_percent *= (1 - (total_reward_percent/100));
        else
            dis_discount_percent = (1 - (total_reward_percent/100));


        if(dis_discount_on_top > 0)
            dis_discount_percent_o+= (total_reward_percent/ 100);
        else
            dis_discount_percent_p += (total_reward_percent/ 100);
    }

    var maximum_percent = 0;
    $('.dis_check').each(function(index, brand){
        var dis_type            = $(this).closest('tr').find('input.dis_type').val();
        if(dis_type == 'Other' || dis_type == 'Hour'){
            var dis_content_json    = $(this).closest('tr').find('input.dis_content').val();
            if(dis_content_json != '' && dis_content_json != null )
                var dis_obj = JSON.parse(dis_content_json);

            var cr_maximum = Numeric.parse($(this).attr('maximum_percent'));
            if(cr_maximum > maximum_percent && $(this).is(":checked"))
                maximum_percent = cr_maximum;

            var dis_amount         = Numeric.parse($(this).closest('tr').find('.dis_amount').val());
            var dis_percent        = Numeric.parse($(this).closest('tr').find('.dis_percent').val());
            var dis_is_ratio       = $(this).closest('tr').find('input.dis_is_ratio').val();
            var dis_is_catch_limit = $(this).closest('tr').find('input.dis_is_catch_limit').val();
            var has_class          = $(this).closest('tr').find(".dis_hours").hasClass("input_readonly");
            var dis_hours          = Numeric.parse($(this).closest('tr').find(".dis_hours").val());
            if(dis_type == 'Hour'){
                if(typeof dis_obj != 'undefined' && has_class){
                    var catch_hour  = 0;
                    var rph = 0;
                    var pmh = 0;
                    $.each(dis_obj.discount_by_hour, function(index, value){
                        if(dis_tuition_hours >= parseFloat(value.hours)){
                            catch_hour++;
                            rph = dis_tuition_hours - parseFloat(value.hours);
                            pmh = parseFloat(value.promotion_hours);
                        }
                    });
                    if(catch_hour == 0 || rph > 0){
                        $.each(dis_obj.discount_by_range, function(index, value){
                            if(dis_tuition_hours >= value.start_hour && dis_tuition_hours <= value.to_hour){
                                rph = dis_tuition_hours / value.block;
                                pmh = rph;
                            }
                        });
                    }
                    dis_hours = pmh;
                    dis_amount = calCourseFee(dis_hours);
                }else{
                    dis_amount = calCourseFee(dis_hours);
                }
                $(this).closest('tr').find("td:eq(3)").text(dis_amount == "0"? ""  : Numeric.toInt(dis_amount));
                $(this).closest('tr').find(".dis_amount").val(dis_amount == "0" ? ""  : Numeric.toInt(dis_amount));
                $(this).closest('tr').find(".dis_hours").val(Numeric.toFloat(dis_hours,2,2));
            }
            if(dis_type == 'Other' && $(this).is(":checked")){
                if(dis_is_ratio == 1)
                    dis_amount = (dis_amount * ratio);
                if(dis_is_catch_limit == 0)
                    dis_not_count_limit_amount += dis_amount;
                dis_percent = Numeric.parse($(this).closest('tr').find('.dis_percent').val());
            }
            if($(this).is(":checked")){         
                var is_chain_discount  = $(this).attr('is_chain_discount');
                var is_discount_on_top  = $(this).attr('is_discount_on_top');
                if(is_chain_discount == 1)
                    dis_chain_discount++;
                dis_discount_amount     += dis_amount;
                if(dis_discount_percent > 0) //Chain Discount
                    dis_discount_percent *= (1 - (dis_percent/100));
                else
                    dis_discount_percent = (1 - (dis_percent/100));

                if(is_discount_on_top == 1)
                    dis_discount_percent_o+= (dis_percent / 100);
                else
                    dis_discount_percent_p += (dis_percent / 100);
            }
        }
    });

    // calculate Partnership
    var dis_amount_not_reward = dis_discount_amount;
    var dis_percent_not_reward = dis_discount_percent;
    var dis_percent_not_reward_p = dis_discount_percent_p;
    $('select.discount_partnership').each(function(index, brand){
        var partnership_percent = 0;
        var partnership_amount  = 0;
        var is_auto_set         = $(this).closest('tr').find('.dis_check').attr('is_auto_set');

        //Auto set Partnership
        if(is_auto_set == '1'){
            var value_set = '';
            $(this).find('option').each(function(index, value){
                var apply_with_loyalty  = $(this).attr("apply_with_loyalty");
                var apply_with_hour     = parseFloat($(this).attr("apply_with_hour"));
                if( ((current_loyalty == apply_with_loyalty) && (apply_with_loyalty != '')) || (dis_tuition_hours >= apply_with_hour && apply_with_hour > 0)){
                    value_set = value.value;
                }
            });
            $(this).val(value_set);
        }

        if(($(this).val() != '') && ($(this).val() != null)){
            partnership_amount  = Numeric.parse($(this).find('option:selected').attr("dis_amount"));
            partnership_percent = Numeric.parse($(this).find('option:selected').attr("dis_percent"));
            apply_with_loyalty  = $(this).find('option:selected').attr("apply_with_loyalty");
            if(($(this).closest('tr').find('.dis_check').is(":checked"))){
                var is_chain_discount   = $(this).closest('tr').find('.dis_check').attr('is_chain_discount');
                var is_discount_on_top   = $(this).closest('tr').find('.dis_check').attr('is_discount_on_top');
                if(is_chain_discount == 1)
                    dis_chain_discount++;
                partnership_is_ratio    = $(this).closest('tr').find('input.dis_is_ratio').val();
                dis_is_catch_limit      = $(this).closest('tr').find('input.dis_is_catch_limit').val();
                if(partnership_is_ratio == 1)
                    partnership_amount = (partnership_amount * ratio);
                if(dis_is_catch_limit == 0)
                    dis_not_count_limit_amount += partnership_amount;

                dis_discount_amount  += partnership_amount;

                if(dis_discount_percent > 0) //Chain Discount
                    dis_discount_percent *= (1 - (partnership_percent/100));
                else
                    dis_discount_percent = (1 - (partnership_percent/100));

                if(is_discount_on_top == 1)
                    dis_discount_percent_o+= (partnership_percent/ 100);
                else
                    dis_discount_percent_p += (partnership_percent/ 100);

                if(current_loyalty == apply_with_loyalty && apply_with_loyalty != ''){

                }else{
                    dis_amount_not_reward     += partnership_amount;

                    if(dis_percent_not_reward > 0) //Chain Discount
                        dis_percent_not_reward *= (1 - (partnership_percent/100));
                    else
                        dis_percent_not_reward = (1 - (partnership_percent/100));
                    dis_percent_not_reward_p += (partnership_percent/ 100);
                }
            }
        }

        //assign
        $(this).parent().parent().find("td:eq(2)").text(partnership_percent == "0"? "" : Numeric.toFloat(partnership_percent),2,2);
        $(this).parent().parent().find("td:eq(3)").text(partnership_amount == "0"? ""  : Numeric.toInt(partnership_amount));
    });

    if(dis_discount_percent > 0)
        dis_discount_percent = (1 - dis_discount_percent);
    else dis_discount_percent = 0;

    if(dis_percent_not_reward > 0)
        dis_percent_not_reward = (1 - dis_percent_not_reward);
    else dis_percent_not_reward = 0;


    //Apply tam
    if(dis_chain_discount == 0){
        dis_discount_percent = dis_discount_percent_p;
        dis_percent_not_reward = dis_percent_not_reward_p;
    }

    //END: Apply tam

    dis_total_discount_amount       = dis_discount_amount;
    dis_total_discount_percent      = (dis_discount_percent_o * dis_amount_bef_discount) + (dis_discount_percent_p*(dis_amount_bef_discount - (dis_discount_percent_o * dis_amount_bef_discount) - dis_total_discount_amount - final_sponsor - loyalty_amount + dis_not_count_limit_amount));
    dis_total_discount              = dis_total_discount_amount + dis_total_discount_percent;
    var total_dis_not_reward        = dis_amount_not_reward +  ((dis_percent_not_reward)*(dis_amount_bef_discount - dis_amount_not_reward - final_sponsor - loyalty_amount + dis_not_count_limit_amount));

    //assign
    $('.dis_total_discount').text(Numeric.toInt(dis_total_discount));
    $('.total_dis_not_reward').val(Numeric.toInt(total_dis_not_reward));
    $('.dis_discount_percent').text(Numeric.toFloat((dis_discount_percent_p + dis_discount_percent_o)*100,2,2));
    $('.dis_discount_percent_p').val(Numeric.toFloat( (dis_discount_percent_p + dis_discount_percent_o)*100,2,2));
    $('#dis_ratio').html('&nbsp;&nbsp;&nbsp;'+SUGAR.language.get('J_Payment', 'LBL_RATIO')+': <b>'+Numeric.toFloat(ratio,3,3)+'</b>');
    $('.dis_discount_amount').text(Numeric.toInt(dis_total_discount_amount));
    $('#discount_reward').parent().parent().find("td:eq(2)").text(total_reward_percent == "0"? ""  : Numeric.toFloat(total_reward_percent),2,2);
    $('#discount_reward').parent().parent().find("td:eq(3)").text(total_reward_amount  == "0"? ""  : Numeric.toInt(total_reward_amount));

    var limit_percent = limit_discount_percent;
    if(maximum_percent > 0)           //Bo dien kien thay doi limit
        limit_percent = maximum_percent;

    //Compare with limit - Limited Discount chỉ áp dụng cho Discount
    var limited_discount    = (((((limit_percent/100) - accrual_rate_value) * dis_amount_bef_discount) + (accrual_rate_value * final_sponsor)) / (1 - accrual_rate_value) ) + dis_not_count_limit_amount;    //limit discount

    var catch_limit = false;
    $('#catch_limit').val('0');
    if( (dis_total_discount) >= limited_discount){
        dis_total_discount = limited_discount;
        catch_limit = true;
        $('#catch_limit').val('1');
    }
    var dis_final_discount_percent = Numeric.toFloat((dis_total_discount / (dis_amount_bef_discount)) * 100,2,2);

    if(catch_limit)
        $('.dis_alert_discount').html("&nbsp;&nbsp;&nbsp;("+SUGAR.language.get('J_Payment', 'LBL_LIMITED')+' '+limit_percent+"%)").show();
    else $('.dis_alert_discount').hide();
    //assign final discount
    $('.dis_final_discount').text(Numeric.toInt(dis_total_discount));
    $('.dis_final_discount_percent').val(dis_final_discount_percent);
    $('.dis_discount_percent_to_amount').val(Numeric.toInt(dis_total_discount_percent));
    $('input.dis_not_count_limit_amount').val(Numeric.toInt(dis_not_count_limit_amount));
    $('#limited_discount_amount').val();
    $('span#accrual_rate_label').text( '('+(accrual_rate_value * 100)+'%)' );
    $('input#accrual_rate_value').val(accrual_rate_value);

    //Xu ly clear loyalty
    var limited_discount_amount = Numeric.toInt(limited_discount);
    $('#limited_discount_amount').val(limited_discount_amount);
}

function submitDiscount(){
    calDiscount();
    var discount_list = {};
    var count = 0;
    var description = '- '+SUGAR.language.get('J_Payment', 'LBL_DISCOUNT_NAME')+': ';
    var catch_limit =  $('#catch_limit').val();
    var dis_not_count_limit_amount =  Numeric.parse($('input.dis_not_count_limit_amount').val());

    var ratio = Numeric.parse($('#ratio').val());
    var dis_discount_percent_to_amount  = Numeric.parse($('.dis_discount_percent_to_amount').val());
    var discount_percent                = Numeric.parse($('.dis_discount_percent_p').val());//Chain Discount

    var total_discount = Numeric.parse($('.dis_total_discount').text());
    var final_discount = Numeric.parse($('.dis_final_discount').text());
    // reward
    var reward_array = [];
    reward_array = $("#discount_reward").val();

    if (typeof reward_array != 'undefined'){
        if(reward_array !== null && reward_array.length > 0 && reward_array[0] == '') reward_array.shift();

        if (reward_array !== null && reward_array.length > 0){
            description = description + 'Reward khóa: ';
            var first_reward = true;
            $.each(reward_array, function(index,value) {

                var dis_percent     = Numeric.parse($("#discount_reward option[value='"+value+"']").attr("dis_percent"));
                var dis_amount      = Numeric.parse($("#discount_reward option[value='"+value+"']").attr("dis_amount"));
                var reward_is_ratio = $("#discount_reward option[value='"+value+"']").attr("dis_is_ratio");
                var dis_is_catch_limit = $("#discount_reward option[value='"+value+"']").attr("dis_is_catch_limit");
                var total_down      = dis_amount;
                if(reward_is_ratio == 1)
                    total_down      = (dis_amount * ratio);

                if(discount_percent != 0)
                    total_down += (dis_discount_percent_to_amount * (dis_percent/discount_percent));

                if(catch_limit == '1' && dis_is_catch_limit == 1)
                    total_down = (total_down * (final_discount - dis_not_count_limit_amount) / (total_discount - dis_not_count_limit_amount));

                count++;
                discount_list[value] = {};
                discount_list[value]['id']           = value;
                discount_list[value]['type']         = 'Reward';
                discount_list[value]['dis_percent']  = Numeric.toFloat(dis_percent,2,2);
                discount_list[value]['dis_amount']   = Numeric.toInt(dis_amount);
                discount_list[value]['total_down']   = Numeric.toInt(total_down);

                if (first_reward) {
                    description = description + $("#discount_reward option[value='"+value+"']").text();
                    first_reward = false;
                }
                else description = description +', '+ $("#discount_reward option[value='"+value+"']").text();
            });
        }
    }

    $('.dis_check:checked').each(function(index, brand){
        var dis_type            = $(this).closest('tr').find('input.dis_type').val();
        if(dis_type == 'Other' || dis_type == 'Hour'){
            var dis_percent     = Numeric.parse($(this).closest('tr').find('.dis_percent').val());
            var dis_amount      = Numeric.parse($(this).closest('tr').find('.dis_amount').val());
            var dis_is_ratio    = $(this).closest('tr').find('input.dis_is_ratio').val();
            var dis_is_catch_limit    = $(this).closest('tr').find('input.dis_is_catch_limit').val();
            var total_down      = dis_amount;
            if(dis_is_ratio == 1)
                total_down      = (dis_amount * ratio);

            if(discount_percent != 0)
                total_down += (dis_discount_percent_to_amount * (dis_percent/discount_percent));
            if(catch_limit == '1' && dis_is_catch_limit == 1)
                total_down = (total_down * (final_discount - dis_not_count_limit_amount) / (total_discount - dis_not_count_limit_amount));

            count++;
            discount_list[$(this).val()] =  {};
            discount_list[$(this).val()]['id']          =  $(this).val();
            discount_list[$(this).val()]['type']        =  'Discount';
            discount_list[$(this).val()]['dis_percent']  = Numeric.toFloat(dis_percent,2,2);
            discount_list[$(this).val()]['dis_amount']   = Numeric.toInt(dis_amount);
            discount_list[$(this).val()]['total_down']   = Numeric.toInt(total_down);
            if(count == 1)
                var des =  $(this).closest('tr').find('.dis_name').text();
            else var des = ', '+$(this).closest('tr').find('.dis_name').text()
            description = description + des;
        }
    });

    //partnership
    $('select.discount_partnership').each(function(index, brand){
        if(($(this).val() != '') && ($(this).val() != null) && ($(this).closest('tr').find('.dis_check').is(":checked"))){
            var dis_percent     = Numeric.parse($(this).find('option:selected').attr("dis_percent"));
            var dis_amount      = Numeric.parse($(this).find('option:selected').attr("dis_amount"));
            var partnership_is_ratio    = $(this).closest('tr').find('input.dis_is_ratio').val();
            var dis_is_catch_limit      = $(this).closest('tr').find('input.dis_is_catch_limit').val();
            var total_down      = dis_amount;
            if(partnership_is_ratio == 1)
                total_down      = (dis_amount * ratio);
            var dis_partnership_name= $(this).closest('tr').find(".dis_name").text();

            if(discount_percent != 0)
                total_down  += (dis_discount_percent_to_amount * (dis_percent/discount_percent));

            if(catch_limit == '1' && dis_is_catch_limit == 1)
                total_down = (total_down * (final_discount - dis_not_count_limit_amount) / (total_discount - dis_not_count_limit_amount));

            count++;
            var dis_partnership_id = $(this).closest('tr').find(".dis_check").val();
            discount_list[dis_partnership_id] = {};
            discount_list[dis_partnership_id]['id']           = dis_partnership_id;
            discount_list[dis_partnership_id]['type']         = 'Partnership';
            discount_list[dis_partnership_id]['dis_percent']  = Numeric.toFloat(dis_percent,2,2);
            discount_list[dis_partnership_id]['dis_amount']   = Numeric.toInt(dis_amount);
            discount_list[dis_partnership_id]['total_down']   = Numeric.toInt(total_down);
            discount_list[dis_partnership_id]['partnership_id']= $(this).val();
            if(count == 1)
                description = description;
            else
                description = description + ', ';
            description = description + dis_partnership_name + ': ' + $(this).find('option:selected').text();
        }
    });
    $(this).dialog('close');
    var str_json_discount = '';
    var str_json_discount = JSON.stringify(discount_list);
    if (description == '- '+(SUGAR.language.get('J_Payment', 'LBL_DISCOUNT_NAME')+': ')) description = '';
    //Add Sponsor Description
    var sponsor_list = $('#sponsor_list').val();
    if(sponsor_list != '' && typeof sponsor_list != 'undefined'){
        var sponsor_objs = JSON.parse(sponsor_list);
        $.each(sponsor_objs, function( key, sponsor_obj ) {
            if(sponsor_obj.sponsor_number != ''){
                description = description + "\n- "+SUGAR.language.get('J_Payment', 'LBL_SPONSOR_NAME')+': ' +sponsor_obj.sponsor_number;
            }
        });
    }
    $('#description').val(description);
    $('#discount_list').val(str_json_discount);
    $('#discount_amount').val($('.dis_final_discount').text());
    $('#discount_percent').val($('.dis_final_discount_percent').val());
    $('#sub_discount_amount').val($('.dis_discount_amount').text());
    $('#sub_discount_percent').val($('.dis_discount_percent').text());
}

function switchPaymentType(){
    var type = $('#payment_type').val();
    if(record_id == ''){  //In Case Create
        $('#tuition_hours').val('');
        $('#tuition_fee').val('');
        $('#amount_bef_discount').val('');
        $('#payment_amount').val('');
        $('.dis_check').prop('checked',false);
        submitDiscount();
        caculated();
    }
    switch (type) {
        case 'Cashholder':
            $('#tuition_hours').prop('readonly',false).removeClass('input_readonly');
            $('#amount_bef_discount').prop('readonly',true).addClass('input_readonly');
            $('#payment_amount').prop('readonly',true).addClass('input_readonly');

            $('.tuition_hours, .coursefee').show();

            $('#tblbook').hide();                                  
            $('#list_price_per_hour').closest('tr').show();
            $('#amount_bef_discount').closest('tr').show();
            $('#final_sponsor').closest('tr').show();
            $('#discount_amount').closest('tr').show(); 
            
            $('#payment_amount_label').closest('td').show(); 
            $('#payment_amount').closest('td').show();  
                               
            $('#kind_of_course').closest('td').show();    
            $('#kind_of_course_label').closest('td').show();
            $('#kind_of_course_label').find('.required').show();

            $("[for='payment_amount']").html('<b>Tổng tiền (8):<br><i>(8)=(3)-(5)-(7)</i></b>');
            addToValidate('EditView','kind_of_course','enum',true, SUGAR.language.get('J_Payment', 'LBL_KIND_OF_COURSE'));
            break;
        case 'Deposit':
            $('#amount_bef_discount').prop('readonly',true).addClass('input_readonly');
            $('#payment_amount').prop('readonly',false).removeClass('input_readonly');
            $('#tuition_hours').prop('readonly',true).addClass('input_readonly');
            $('#tblbook').hide();                  
            $('#list_price_per_hour').closest('tr').hide();
            $('#amount_bef_discount').closest('tr').hide();
            $('#final_sponsor').closest('tr').hide();
            $('#discount_amount').closest('tr').hide();    
            
            $('#payment_amount_label').closest('td').show(); 
            $('#payment_amount').closest('td').show();  
                               
            $('#kind_of_course').closest('td').show();
            $('#kind_of_course_label').closest('td').show();
            $('#kind_of_course_label').find('.required').show();
            $("[for='payment_amount']").html('<b>Tổng tiền:</b>');
            addToValidate('EditView','kind_of_course','enum',true,SUGAR.language.get('J_Payment', 'LBL_KIND_OF_COURSE'));
            
            $("#coursefee").val("").trigger('change');
            break;
        case 'Placement Test':
        case 'Cambridge':
            $('#amount_bef_discount').prop('readonly',true).addClass('input_readonly');
            $('#payment_amount').prop('readonly',false).removeClass('input_readonly');
            $('#tuition_hours').prop('readonly',true).addClass('input_readonly');
            $('#tblbook').hide();                          
            $('#list_price_per_hour').closest('tr').hide();
            $('#amount_bef_discount').closest('tr').hide();
            $('#final_sponsor').closest('tr').hide();
            $('#discount_amount').closest('tr').hide(); 
            
            $('#payment_amount_label').closest('td').show(); 
            $('#payment_amount').closest('td').show();  
            
            $('#kind_of_course').closest('td').show();     
            $('#kind_of_course_label').closest('td').show();          
            addToValidate('EditView','kind_of_course','enum',true,SUGAR.language.get('J_Payment', 'LBL_KIND_OF_COURSE'));
            $('#kind_of_course_label').find('.required').show();
            $("#coursefee").val("").trigger('change');
            $("[for='payment_amount']").html('<b>Tổng tiền:</b>');
            break;
        case 'Book/Gift':
            $('#total_after_discount').closest('tr').show();
            $('#tblbook').show();
            $('#total_after_discount, #after_discount_label').hide();
            $('#tuition_hours, #payment_amount').prop('readonly',true).addClass('input_readonly');
            $
            $('#list_price_per_hour').closest('tr').hide();
            $('#amount_bef_discount').closest('tr').hide();
            $('#final_sponsor').closest('tr').hide();
            $('#discount_amount').closest('tr').hide(); 
            
            $('#payment_amount_label').closest('td').hide(); 
            $('#payment_amount').closest('td').hide();   
            
            $('#kind_of_course').closest('td').hide();
            $('#kind_of_course_label').closest('td').hide();
            removeFromValidate('EditView','kind_of_course');     
            $("#coursefee").val("").trigger('change');
            break;
    }
}
function calBookPayment(){
    var total_pay = 0;
    $('#tblbook tbody tr').each(function(index, brand){
        $("select option:selected")
        var book_price      = Numeric.parse($(this).find('select.book_id option:selected').attr('price'));
        var book_unit      = $(this).find('select.book_id option:selected').attr('unit');
        var book_quantity   = parseInt($(this).find('.book_quantity').val());
        var book_cost       = (book_price * book_quantity);
        $(this).find('.book_price').val(Numeric.toInt(book_price));
        $(this).find('.book_amount').val(Numeric.toInt(book_cost));
        $(this).find('.book_unit').text(book_unit);
        total_pay           = total_pay + book_cost;
    });

    if($('#is_free_book').is(':checked'))
        $('#amount_bef_discount, #total_after_discount').val(0);
    else
        $('#amount_bef_discount, #total_after_discount, #total_book_pay').val(Numeric.toInt(total_pay));

    setLoyaltyLevel();
    submitLoyalty();
    caculated();
    autoGeneratePayment();
}
function handleRemoveRow(){
    calBookPayment();
}

//Validate start study date
function validateStart(){
    $classStart   = SUGAR.util.DateUtils.parse($('#class_start').val(),cal_date_format).getTime();
    $classEnd     = SUGAR.util.DateUtils.parse($('#class_end').val(),cal_date_format).getTime();
    //get date start study
    $date_start = SUGAR.util.DateUtils.parse($('#start_study').val(),cal_date_format);
    if($date_start==false){
        alertify.error('Invalid date');
        $('#start_study').val('');
    }else{
        $start = $date_start.getTime();
        if($start < $classStart || $start > $classEnd){
            alertify.error(SUGAR.language.get('J_Payment','LBL_INVALID_DATE_RANGE'));
            $('#start_study').val('');
        }
    }
}

//Validate start study date
function validateEnd(){
    $classStart   = SUGAR.util.DateUtils.parse($('#class_start').val(),cal_date_format).getTime();
    $classEnd     = SUGAR.util.DateUtils.parse($('#class_end').val(),cal_date_format).getTime();
    //get date start study
    $date_end = SUGAR.util.DateUtils.parse($('#end_study').val(),cal_date_format);
    if($date_end==false){
        alertify.error('Invalid date');
        $('#end_study').val('');
    }else{
        $end = $date_end.getTime();
        if($end < $classStart || $end > $classEnd){                              
            alertify.error(SUGAR.language.get('J_Payment','LBL_INVALID_DATE_RANGE'));
        }
    }
}

function isInSchedule(checking_date){
    var checking_date = SUGAR.util.DateUtils.parse(checking_date,cal_date_format);
    var count_err = 0;
    $('#classes option:selected').each(function(index, brand){
        obj = JSON.parse($(this).attr("json_ss"));
        if( checking_date != ''){
            flag = SUGAR.util.DateUtils.formatDate(checking_date,false,"Y-m-d") in obj;
            if(flag)
                count_err++;
        }
    });
    if(count_err>0)
        return true;
    else{                                              
            alertify.error(SUGAR.language.get('J_Payment','LBL_START_STUDY_NOT_IN_SCHEDULE'));  
        return false;
    }

}

//Add by Tung Bui - 04/01/2016 - show Schedule when select Class
function generateClassSchedule(){
    var html = "";
    $("#classes option:selected").each(function(){
        html += "<b>"+$(this).attr("class_name") + "</b>: "+ $(this).attr("start_date");
        if($(this).attr("class_type") == 'Normal Class') html +=  " - "+ $(this).attr("end_date")
        html += $(this).attr("main_schedule");
    });

    $('#div_sclass_schedule').html(html); 
}

function showClassSchedule(){ 
    if($("#classes").val() == null){
        $('#classes').closest("td").find("button").effect("highlight", {color: '#FF0000'}, 5000);
        alertify.error(SUGAR.language.get('J_Payment', 'LBL_PLEASE_SELECT_ANY_CLASS'));
        return false;
    }
  
    $("body").css({ overflow: 'hidden' });
    $('#div_sclass_schedule').dialog({
        resizable: false,
        width:'600',   
        modal: true,            
        position: ['center','center'],
        buttons: {      
            "Close": {
                click:function() {
                    $(this).dialog('close');
                },
                text: SUGAR.language.get('J_Payment', 'BTN_CLOSE'),
            },
        },
        beforeClose: function(event, ui) {
            $("body").css({ overflow: 'inherit' });
        },
    });
}
function isEmpty(str) {
    return typeof str == 'string' && !str.trim() || typeof str == 'undefined' || str === null;
}


// check available discount
function checkAvailableDiscount(){
    //release all
    $('.dis_check').each(function(index, focus){
        var dis_check_id            = $(this).closest('tr').find(".dis_check").val();
        var disable_discount_list   = $(this).closest('tr').find(".disable_discount_list").val();
        var dis_name                = $(this).closest('tr').find(".dis_name").text();

        if(typeof disable_discount_list != 'undefined' && disable_discount_list != '' && disable_discount_list != '[]'){
            var disable_obj = JSON.parse(disable_discount_list);
            $.each(disable_obj, function(index, value){
                $('#row_'+value).removeClass("locked").addClass("unlocked").find("td").css("background","");
                $('#row_'+value).find('.dis_check').show();
                $('#row_'+value).attr("title",'');
            });
        }
    });

    //Check available
    $('.dis_check:checked').each(function(index, focus){
        var dis_check_id            = $(this).closest('tr').find(".dis_check").val();
        var disable_discount_list   = $(this).closest('tr').find(".disable_discount_list").val();
        var dis_name                = $(this).closest('tr').find(".dis_name").text();
        if($(this).is(':checked')){
            if(typeof disable_discount_list != 'undefined' && disable_discount_list != '' && disable_discount_list != '[]'){
                var disable_obj = JSON.parse(disable_discount_list);

                $.each(disable_obj, function(index, value){
                    $('#row_'+value).removeClass("unlocked").addClass("locked").find("td").css("background","bisque");
                    $('#row_'+value).find('.dis_check').prop("checked",false).hide();
                    $('#row_'+value).attr("title",'Do not apply with discount: '+dis_name);
                });

            }
        }
    });
}
function collapseDiscount(id){
    $('a#collapseLink'+id).hide();
    $('a#expandLink'+id).show();
    $('tr.discount_group'+id).hide();
}
function expandDiscount(id){
    $('a#collapseLink'+id).show();
    $('a#expandLink'+id).hide();
    $('tr.discount_group'+id).show();
}
Calendar.setup ({
    inputField : "pay_dtl_invoice_date_1",
    daFormat : cal_date_format,
    button : "pay_dtl_invoice_date_1_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "pay_dtl_invoice_date_2",
    daFormat : cal_date_format,
    button : "pay_dtl_invoice_date_2_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "pay_dtl_invoice_date_3",
    daFormat : cal_date_format,
    button : "pay_dtl_invoice_date_3_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});

function displayDialogSelectBalance(use_type){
    $("#tbodypayment_dialog").html($("#tbodypayment").html());  
    copyBalanceListToDialog(use_type);

    $("body").css({ overflow: 'hidden' });
    $('#payment_list_div_dialog').dialog({
        resizable: false,
        width:'1400',   
        modal: true,            
        position: ['center','center'],
        buttons: {
            "Submit":{
                click:function() {   
                    submitSelectBalance(use_type);

                    $(this).dialog('close');
                },
                class: 'button primary',
                text: SUGAR.language.get('J_Payment', 'LBL_SUBMIT'),
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                text: SUGAR.language.get('J_Payment', 'LBL_CANCEL'),
            },
        },
        beforeClose: function(event, ui) {
            $("body").css({ overflow: 'inherit' });
        },
    });
}       

function copyBalanceListToDialog(use_type){
    var checked_pay = [];
    $('#tblpayment').find('.pay_check:checked').each(function(){
        checked_pay.push($(this).val());            
    });

    //hide row & re-check selected payment
    $("#tbodypayment_dialog tr").each(function(){
        if($(this).find(".use_type").val() != use_type){
            $(this).remove();    
        }   

        if(checked_pay.indexOf($(this).find(".pay_check").val()) >= 0){
            $(this).find(".pay_check").prop('checked', true);    
        } 
        else{
            $(this).find(".pay_check").prop('checked', false);    
        }
    }); 
}

function submitSelectBalance(use_type){
    var checked_pay = [];
    $('#tbodypayment_dialog').find('.pay_check:checked').each(function(){
        checked_pay.push($(this).val());            
    });

    $("#tbodypayment tr").each(function(){ 
        if($(this).find(".use_type").val() == use_type){
            if(checked_pay.indexOf($(this).find(".pay_check").val()) >= 0){
                $(this).find(".pay_check").prop('checked', true);   
            } 
            else{
                $(this).find(".pay_check").prop('checked', false);    
            }
            $(this).find(".pay_check").trigger('change');      
        }   
    });   
}