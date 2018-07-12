(function(jQuery1_7_1) {
    jQuery1_7_1.fn.pictureEditor = function(options) {
        // Override default settings by user options
        var settings = jQuery1_7_1.extend({
            imgPreview: null,
            pictureDialog: null,
            showLightbox: false,
            centerDialog: false
        }, options );
        
        // Global variables
        var fileInput = jQuery1_7_1(settings.pictureDialog).find('.image'); 
        var imgExts = ['jpg', 'png'];
        var allowSize = 30; // 30MB
        var maxSize = allowSize * 1024 * 1024; // Convert to bytes
        var viewPortW = 400;
        var viewPortH = 300;
        var selectorX = 0;
        var selectorY = 0;
        var selectorW = 200;
        var selectorH = 200;
        
        // Show dialog when the button is clicked
        this.on('click', function(){
            if(settings.showLightbox == true) {
                scheduler.startLightbox(null, settings.pictureDialog[0]);
            } else {
                jQuery1_7_1(settings.pictureDialog).show();
            }
            
            if(settings.centerDialog == true) {
                center(settings.pictureDialog, jQuery1_7_1(window));
            }    
        });
        
        // Reset to default
        function setDefault(img, cropzoom){
            cropzoom.setSelector(selectorX, selectorY, selectorW, selectorH, true);
            var curImg = jQuery1_7_1(settings.pictureDialog).find('.pictureContainer img.ui-draggable');
            jQuery1_7_1(settings.pictureDialog).find('.viewPortW').val(viewPortW);    
            jQuery1_7_1(settings.pictureDialog).find('.viewPortH').val(viewPortH);    
            jQuery1_7_1(settings.pictureDialog).find('.selectorX').val(selectorX);    
            jQuery1_7_1(settings.pictureDialog).find('.selectorY').val(selectorY);    
            jQuery1_7_1(settings.pictureDialog).find('.selectorW').val(selectorW);    
            jQuery1_7_1(settings.pictureDialog).find('.selectorH').val(selectorH);
            jQuery1_7_1(settings.pictureDialog).find('.imageRotate').val(0);
            jQuery1_7_1(settings.pictureDialog).find('.imageX').val(0);    
            jQuery1_7_1(settings.pictureDialog).find('.imageY').val(0);    
            jQuery1_7_1(settings.pictureDialog).find('.imageW').val(parseInt(curImg.css('width')));    
            jQuery1_7_1(settings.pictureDialog).find('.imageH').val(parseInt(curImg.css('height')));
        }
        
        // Clear preview image from the editor
        function clearImage() {
            fileInput.val('');    
            jQuery1_7_1(settings.pictureDialog).find('.pictureContainer').removeAttr('style').html('<img src="index.php?entryPoint=getImage&themeName=default&imageName=default-picture.png"/>');
        }
        
        // Init cropzoom plugin
        function initCropZoom(fileInput, imgSrc) {
            var img = new Image();
            img.onload = function() {

                var cropzoom = jQuery1_7_1(settings.pictureDialog).find('.pictureContainer').cropzoom({
                    width: viewPortW,
                    height: viewPortH,
                    bgColor: '#eee',
                    enableRotation: true,
                    enableZoom: true,
                    zoomSteps: 5,
                    rotationSteps: 5,
                    selector: {        
                        centered: true,
                        borderColor: 'blue',
                        borderColorHover: 'red',
                        aspectRatio: true,
                        showPositionsOnDrag: false,
                        showDimetionsOnDrag: false,
                        onSelectorDragStop: function(object, positions){
                            jQuery1_7_1(settings.pictureDialog).find('.selectorX').val(positions.x);    
                            jQuery1_7_1(settings.pictureDialog).find('.selectorY').val(positions.y);    
                            jQuery1_7_1(settings.pictureDialog).find('.selectorW').val(positions.w);    
                            jQuery1_7_1(settings.pictureDialog).find('.selectorH').val(positions.h);    
                        },
                        // Do not allow to resize                    
                        /*onSelectorResizeStop: function(object, positions){
                            jQuery1_7_1('#selectorX').val(positions.x);    
                            jQuery1_7_1('#selectorY').val(positions.y);    
                            jQuery1_7_1('#selectorW').val(positions.w);    
                            jQuery1_7_1('#selectorH').val(positions.h);    
                        },*/
                    },
                    image: {
                        source: imgSrc,
                        width: img.width,
                        height: img.height,
                        minZoom: 20,
                        maxZoom: 200,
                        onRotate: function(object, degrees){
                            jQuery1_7_1(settings.pictureDialog).find('.imageRotate').val(degrees);    
                        },
                        onZoom: function(object){
                            jQuery1_7_1(settings.pictureDialog).find('.imageX').val(parseInt(object.css('left')));    
                            jQuery1_7_1(settings.pictureDialog).find('.imageY').val(parseInt(object.css('top')));    
                            jQuery1_7_1(settings.pictureDialog).find('.imageW').val(parseInt(object.css('width')));    
                            jQuery1_7_1(settings.pictureDialog).find('.imageH').val(parseInt(object.css('height')));    
                        },
                        onImageDrag: function(object){
                            jQuery1_7_1(settings.pictureDialog).find('.imageX').val(parseInt(object.css('left')));    
                            jQuery1_7_1(settings.pictureDialog).find('.imageY').val(parseInt(object.css('top')));    
                            jQuery1_7_1(settings.pictureDialog).find('.imageW').val(parseInt(object.css('width')));    
                            jQuery1_7_1(settings.pictureDialog).find('.imageH').val(parseInt(object.css('height')));    
                        },
                    },
                });

                // Initialize
                jQuery1_7_1(settings.pictureDialog).find('.pictureContainer #_selector').resizable("option", "disabled", true);  // Turn resizable off
                setDefault(img, cropzoom);
                
                jQuery1_7_1(settings.pictureDialog).find('.btnRestore').on('click', function(){
                    if(jQuery1_7_1(fileInput).val() != '') {
                        cropzoom.restore();
                        setDefault(img, cropzoom);
                    }
                });
            };

            img.src = imgSrc;    // Trigger img onload
        }

        // Load image to edit
        function editImage(fileInput) {
            var fileName = jQuery1_7_1(fileInput).val();
            var ext = fileName.substring(fileName.lastIndexOf('.') + 1, fileName.length).toLowerCase();

            if (fileInput.files && fileInput.files[0] && jQuery1_7_1.inArray(ext, imgExts) >= 0 && fileInput.files[0].size <= maxSize) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    initCropZoom(fileInput, reader.result);
                };

                reader.readAsDataURL(fileInput.files[0]);   // Trigger reader onload
            }else{
                alert('Image is invalid. Max image size is ' + allowSize + 'MB and allowed extensions are ' + imgExts.join(', ') + '.');
                clearImage();
            }
        }

        // Select existing photo
        jQuery1_7_1(settings.pictureDialog).find('img.existingPhoto').on('click', function(){
            initCropZoom(jQuery1_7_1(settings.pictureDialog).find('.picture'), jQuery1_7_1(this).attr('src'));
            jQuery1_7_1(settings.pictureDialog).find('.picture').val(jQuery1_7_1(this).attr('src'));
        }); 

        // Select a local image
        fileInput.on('change', function(){ 
            editImage(this);
            jQuery1_7_1(settings.pictureDialog).find('.picture').val('local');
        });
        
        // Save changes
        jQuery1_7_1(settings.pictureDialog).find('.btnSaveImage').on('click', function(){
            // Compute sketch ratio
            var sketch = parseInt(jQuery1_7_1(settings.imgPreview.parent('.imagePreview')).css('width')) / parseInt(jQuery1_7_1(settings.pictureDialog).find('.selectorW').val());

            // Save img source
            var imgSrc = jQuery1_7_1(settings.pictureDialog).find('div#k img').attr('src');
            jQuery1_7_1(settings.imgPreview).attr('src', imgSrc);
            
            // Save img size
            jQuery1_7_1(settings.imgPreview).css({'width': parseInt(jQuery1_7_1(settings.pictureDialog).find('.imageW').val()) * sketch});
            jQuery1_7_1(settings.imgPreview).css({'height': parseInt(jQuery1_7_1(settings.pictureDialog).find('.imageH').val()) * sketch});

            // Save img position
            var imgX = (- Math.abs(parseInt(jQuery1_7_1(settings.pictureDialog).find('.selectorX').val()) - parseInt(jQuery1_7_1(settings.pictureDialog).find('.imageX').val()))) * sketch;
            var imgY = (- Math.abs(parseInt(jQuery1_7_1(settings.pictureDialog).find('.selectorY').val()) - parseInt(jQuery1_7_1(settings.pictureDialog).find('.imageY').val()))) * sketch;
            jQuery1_7_1(settings.imgPreview).css({'left':imgX+'px', 'top':imgY+'px'});

            // Save img rotation
            jQuery1_7_1(settings.imgPreview).css({'transform':'rotate('+jQuery1_7_1(settings.pictureDialog).find('.imageRotate').val()+'deg)'});
            
            scheduler.endLightbox(false, settings.pictureDialog[0]);
        });
        
        // Cancel changes
        jQuery1_7_1(settings.pictureDialog).find('.btnCancelImage').on('click', function(){
            clearImage();
            scheduler.endLightbox(false, settings.pictureDialog[0]);    
        });
    };
}(jQuery1_7_1));