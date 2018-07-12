/**
 * validate survey logo size 
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$(document).ready(function () {
    $("#remove_button").click(function () {
        var recordId = $('input[name=record]').val();
        if (confirm("Are you sure you want to remove logo?")) {
            $.ajax({
                url: "index.php",
                data: {
                    module: 'bc_survey',
                    action: 'removeImageFromSurveyEdit',
                    record: recordId,
                },
                success: function (result) {
                    $("#logo_new").show();
                     $("#logo_old").remove();
                }});
        }
    });
    
$("#logo_file").change(function () {
        validate_logosize(this.files);
});

});
function previewSurvey(survey_id, site_url) {
    window.location = site_url + "/preview_survey.php?survey_id=" + survey_id;
}
var _URL = window.URL || window.webkitURL;
function validate_logosize(files){  
    $('#logo_validate').html('');
    var file, img ,img_width ,img_height;
    if ((file = files[0])) {
        var ext = file.type.split('/');
        if($.inArray(ext[1], ['png','jpg','jpeg']) == -1) {
            alert('Please upload only png , jpg and jpeg image file.');
            $("#logo_file").val('');
        }
        img = new Image();
        img.onload = function () {
            img_width = img.width;
            img_height =  img.height;
  
            if(img_width > 300 || img_height > 300){
                // $('#logo_validate').html('You can upload logo with maximum 200px width and 60px height.');
                alert('The logo selected seems to be of higher resolution than specified in the upload note, please choose appropriate one.');
                // $("#logo_width").val(img_width);
                // $("#logo_height").val(img_height);
                $("#logo_file").val('');
            }else{
                $("#logo_width").val('');
                $("#logo_height").val('');
            }
        };
        img.src = _URL.createObjectURL(file);
    }
}