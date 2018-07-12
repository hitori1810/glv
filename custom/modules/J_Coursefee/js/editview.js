$(document).ready(function(){
    toggleCourseFee();
    $('input#type').on('change',function(){
        $('#type_of_course_fee').val('');
        toggleCourseFee();
    });

    $('#apply_for').multipleSelect();
    $('#unit_price').numeric({type: "int", negative: false});

    updatePricePerHour();
    $('#unit_price,#type_of_course_fee').on("change",function(){
        updatePricePerHour();    
    });
});
function toggleCourseFee(){
    var type = $('input#type').val();
    $("#type_of_course_fee option:not(:first)").each(function(){
        if(type == 'Days'){
            if($(this).val().indexOf('days') != -1)
                $(this).show();
            else $(this).hide();
        }else{
            if($(this).val().indexOf('days') != -1)
                $(this).hide();
            else $(this).show();
        }
    });                                                                             
}

function updatePricePerHour(){
    var unit_price_per_hour = Numeric.parse($('#unit_price').val());
    var course_type = Numeric.parse($('#type_of_course_fee').val());

    if(course_type > 0){
        $("#unit_price_per_hour").text(Numeric.toInt(unit_price_per_hour / course_type));
    } 
}