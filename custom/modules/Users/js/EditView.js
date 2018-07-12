jQuery1_7_1 = $.noConflict(true);
jQuery1_7_1(document).ready(function() {
    
    //init the picture editor plugin
    jQuery1_7_1('#btnUploadPicture').pictureEditor({
        imgPreview : jQuery1_7_1('div.imagePreview img'), 
        pictureDialog : jQuery1_7_1('#pictureDialog'), 
        showLightbox : true, 
        centerDialog : true
    });
    //trigger remove button
    jQuery1_7_1('#btnRemove').on('click', function() {
        jQuery1_7_1('#btnRemove').remove();  //remove button
        jQuery1_7_1('#remove_imagefile_picture').val(1); //mark picture is deleted
        jQuery1_7_1('.pictureContainer img').attr('src', 'index.php?entryPoint=getImage&themeName=default&imageName=default-picture.png');  //set default picture
        jQuery1_7_1('.imagePreview img').attr('src', 'index.php?entryPoint=getImage&themeName=default&imageName=default-picture.png');     //set default picture
    });
    
    //add by Trung Nguyen 2015.04.25
    $('#worklog_reminder_recipient').multipleSelect({
         minimumCountSelected: 200,
    });
    $('#leaving_request_confirmer').multipleSelect({
         minimumCountSelected: 200,
    });
});