/*
 CropZoom v1.2
 Release Date: April 17, 2010

 Copyright (c) 2010 Gaston Robledo
 */
;
(function (jQuery1_7_1) {

    jQuery1_7_1.fn.cropzoom = function (options) {

        return this
            .each(function () {

                var _self = null;
                var tMovement = null;

                var jQuery1_7_1selector = null;
                var jQuery1_7_1image = null;
                var jQuery1_7_1svg = null;

                var defaults = {
                    width: 500,
                    height: 375,
                    bgColor: '#000',
                    overlayColor: '#000',
                    selector: {
                        x: 0,
                        y: 0,
                        w: 229,
                        h: 100,
                        aspectRatio: false,
                        centered: false,
                        borderColor: 'yellow',
                        borderColorHover: 'red',
                        bgInfoLayer: '#FFF',
                        infoFontSize: 10,
                        infoFontColor: 'blue',
                        showPositionsOnDrag: true,
                        showDimetionsOnDrag: true,
                        maxHeight: null,
                        maxWidth: null,
                        startWithOverlay: false,
                        hideOverlayOnDragAndResize: true,
                        onSelectorDrag: null,
                        onSelectorDragStop: null,
                        onSelectorResize: null,
                        onSelectorResizeStop: null
                    },
                    image: {
                        source: '',
                        rotation: 0,
                        width: 0,
                        height: 0,
                        minZoom: 10,
                        maxZoom: 150,
                        startZoom: 0,
                        x: 0,
                        y: 0,
                        useStartZoomAsMinZoom: false,
                        snapToContainer: false,
                        onZoom: null,
                        onRotate: null,
                        onImageDrag: null
                    },
                    enableRotation: true,
                    enableZoom: true,
                    zoomSteps: 1,
                    rotationSteps: 5,
                    expose: {
                        slidersOrientation: 'vertical',
                        zoomElement: '',
                        rotationElement: '',
                        elementMovement: '',
                        movementSteps: 5
                    }
                };

                var jQuery1_7_1options = jQuery1_7_1.extend(true, defaults, options);


                // Check for the plugins needed
                if (!jQuery1_7_1.isFunction(jQuery1_7_1.fn.draggable)
                    || !jQuery1_7_1.isFunction(jQuery1_7_1.fn.resizable)
                    || !jQuery1_7_1.isFunction(jQuery1_7_1.fn.slider)) {
                    //alert("You must include ui.draggable, ui.resizable and ui.slider to use cropZoom");
                    return;
                }

                if (jQuery1_7_1options.image.source == ''
                    || jQuery1_7_1options.image.width == 0
                    || jQuery1_7_1options.image.height == 0) {
                    //alert('You must set the source, witdth and height of the image element');
                    return;
                }

                _self = jQuery1_7_1(this);
                //Preserve options
                setData('options', jQuery1_7_1options);
                _self.empty();
                _self.css({
                    'width': jQuery1_7_1options.width,
                    'height': jQuery1_7_1options.height,
                    'background-color': jQuery1_7_1options.bgColor,
                    'overflow': 'hidden',
                    'position': 'relative',
                    'border': '2px solid #333'
                });

                setData('image', {
                    h: jQuery1_7_1options.image.height,
                    w: jQuery1_7_1options.image.width,
                    posY: jQuery1_7_1options.image.y,
                    posX: jQuery1_7_1options.image.x,
                    scaleX: 0,
                    scaleY: 0,
                    rotation: jQuery1_7_1options.image.rotation,
                    source: jQuery1_7_1options.image.source,
                    bounds: [ 0, 0, 0, 0 ],
                    id: 'image_to_crop_' + _self[0].id
                });

                calculateFactor();
                getCorrectSizes();

                setData(
                    'selector',
                    {
                        x: jQuery1_7_1options.selector.x,
                        y: jQuery1_7_1options.selector.y,
                        w: (jQuery1_7_1options.selector.maxWidth != null ? (jQuery1_7_1options.selector.w > jQuery1_7_1options.selector.maxWidth ? jQuery1_7_1options.selector.maxWidth
                            : jQuery1_7_1options.selector.w)
                            : jQuery1_7_1options.selector.w),
                        h: (jQuery1_7_1options.selector.maxHeight != null ? (jQuery1_7_1options.selector.h > jQuery1_7_1options.selector.maxHeight ? jQuery1_7_1options.selector.maxHeight
                            : jQuery1_7_1options.selector.h)
                            : jQuery1_7_1options.selector.h)
                    });


                jQuery1_7_1container = jQuery1_7_1("<div />").attr("id", "k").css({
                    'width': jQuery1_7_1options.width,
                    'height': jQuery1_7_1options.height,
                    'position': 'absolute'
                });

                jQuery1_7_1image = jQuery1_7_1('<img />');

                jQuery1_7_1image.attr('src', jQuery1_7_1options.image.source);

                jQuery1_7_1(jQuery1_7_1image).css({
                    'position': 'absolute',
                    'left': getData('image').posX,
                    'top': getData('image').posY,
                    'width': getData('image').w,
                    'height': getData('image').h
                });

                var ext = getExtensionSource();
                //if (ext == 'png' || ext == 'gif')
//                    jQuery1_7_1image.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"
//                        + jQuery1_7_1options.image.source
//                        + "',sizingMethod='scale');";

                jQuery1_7_1container.append(jQuery1_7_1image);
                _self.append(jQuery1_7_1container);

                calculateTranslationAndRotation();

                // adding draggable to the image
                jQuery1_7_1(jQuery1_7_1image).draggable({
                    refreshPositions: true,
                    drag: function (event, ui) {

                        getData('image').posY = ui.position.top
                        getData('image').posX = ui.position.left

                        if (jQuery1_7_1options.image.snapToContainer)
                            limitBounds(ui);
                        else
                            calculateTranslationAndRotation();
                        // Fire the callback
                        if (jQuery1_7_1options.image.onImageDrag != null)
                            jQuery1_7_1options.image.onImageDrag(jQuery1_7_1image);

                    },
                    stop: function (event, ui) {
                        if (jQuery1_7_1options.image.snapToContainer)
                            limitBounds(ui);
                    }
                });

                // Create the selector
                createSelector();
                // Add solid color to the selector
                _self.find('.ui-icon-gripsmall-diagonal-se').css({
                    'background': '#FFF',
                    'border': '1px solid #000',
                    'width': 8,
                    'height': 8
                });
                // Create the dark overlay
                createOverlay();

                if (jQuery1_7_1options.selector.startWithOverlay) {
                    /* Make Overlays at Start */
                    var ui_object = {
                        position: {
                            top: jQuery1_7_1selector.position().top,
                            left: jQuery1_7_1selector.position().left
                        }
                    };
                    makeOverlayPositions(ui_object);
                }
                /* End Make Overlay at start */

                // Create zoom control
                if (jQuery1_7_1options.enableZoom)
                    createZoomSlider();
                // Create rotation control
                if (jQuery1_7_1options.enableRotation)
                    createRotationSlider();
                if (jQuery1_7_1options.expose.elementMovement != '')
                    createMovementControls();

                function limitBounds(ui) {
                    if (ui.position.top > 0)
                        getData('image').posY = 0;
                    if (ui.position.left > 0)
                        getData('image').posX = 0;

                    var bottom = -(getData('image').h - ui.helper.parent()
                        .parent().height()), right = -(getData('image').w - ui.helper
                        .parent().parent().width());
                    if (ui.position.top < bottom)
                        getData('image').posY = bottom;
                    if (ui.position.left < right)
                        getData('image').posX = right;
                    calculateTranslationAndRotation();
                }

                function getExtensionSource() {
                    var parts = jQuery1_7_1options.image.source.split('.');
                    return parts[parts.length - 1];
                }
                ;

                function calculateFactor() {
                    getData('image').scaleX = (jQuery1_7_1options.width / getData('image').w);
                    getData('image').scaleY = (jQuery1_7_1options.height / getData('image').h);
                }
                ;

                function getCorrectSizes() {
                    if (jQuery1_7_1options.image.startZoom != 0) {
                        var zoomInPx_width = ((jQuery1_7_1options.image.width * Math
                            .abs(jQuery1_7_1options.image.startZoom)) / 100);
                        var zoomInPx_height = ((jQuery1_7_1options.image.height * Math
                            .abs(jQuery1_7_1options.image.startZoom)) / 100);
                        getData('image').h = zoomInPx_height;
                        getData('image').w = zoomInPx_width;
                        //Checking if the position was set before
                        if (getData('image').posY != 0
                            && getData('image').posX != 0) {
                            if (getData('image').h > jQuery1_7_1options.height)
                                getData('image').posY = Math
                                    .abs((jQuery1_7_1options.height / 2)
                                        - (getData('image').h / 2));
                            else
                                getData('image').posY = ((jQuery1_7_1options.height / 2) - (getData('image').h / 2));
                            if (getData('image').w > jQuery1_7_1options.width)
                                getData('image').posX = Math
                                    .abs((jQuery1_7_1options.width / 2)
                                        - (getData('image').w / 2));
                            else
                                getData('image').posX = ((jQuery1_7_1options.width / 2) - (getData('image').w / 2));
                        }
                    } else {
                        var scaleX = getData('image').scaleX;
                        var scaleY = getData('image').scaleY;
                        if (scaleY < scaleX) {
                            getData('image').h = jQuery1_7_1options.height;
                            getData('image').w = Math
                                .round(getData('image').w * scaleY);
                        } else {
                            getData('image').h = Math
                                .round(getData('image').h * scaleX);
                            getData('image').w = jQuery1_7_1options.width;
                        }
                    }

                    // Disable snap to container if is little
                    if (getData('image').w < jQuery1_7_1options.width
                        && getData('image').h < jQuery1_7_1options.height) {
                        jQuery1_7_1options.image.snapToContainer = false;
                    }
                    calculateTranslationAndRotation();

                }
                ;

                function calculateTranslationAndRotation() {

                    jQuery1_7_1(function () {
                        adjustingSizesInRotation();
                        // console.log(imageData.id);
                        rotation = "rotate(" + getData('image').rotation + "deg)";

                        jQuery1_7_1(jQuery1_7_1image).css({
                            'transform': rotation,
                            '-webkit-transform': rotation,
                            '-ms-transform': rotation,
                            'msTransform': rotation,
                            'top': getData('image').posY,
                            'left': getData('image').posX
                        });
                    });
                }
                ;

                function createRotationSlider() {

                    var rotationContainerSlider = jQuery1_7_1("<div />").attr('id',
                            'rotationContainer').mouseover(function () {
                            jQuery1_7_1(this).css('opacity', 1);
                        }).mouseout(function () {
                            jQuery1_7_1(this).css('opacity', 0.6);
                        });

                    var rotMin = jQuery1_7_1('<div />').attr('id', 'rotationMin')
                        .html("0");
                    var rotMax = jQuery1_7_1('<div />').attr('id', 'rotationMax')
                        .html("360");

                    var jQuery1_7_1slider = jQuery1_7_1("<div />").attr('id', 'rotationSlider');

                    // Apply slider
                    var orientation = 'vertical';
                    var value = Math.abs(360 - jQuery1_7_1options.image.rotation);

                    if (jQuery1_7_1options.expose.slidersOrientation == 'horizontal') {
                        orientation = 'horizontal';
                        value = jQuery1_7_1options.image.rotation;
                    }

                    jQuery1_7_1slider
                        .slider({
                            orientation: orientation,
                            value: value,
                            range: "max",
                            min: 0,
                            max: 360,
                            step: ((jQuery1_7_1options.rotationSteps > 360 || jQuery1_7_1options.rotationSteps < 0) ? 1
                                : jQuery1_7_1options.rotationSteps),
                            slide: function (event, ui) {
                                getData('image').rotation = (value == 360 ? Math
                                    .abs(360 - ui.value)
                                    : Math.abs(ui.value));
                                calculateTranslationAndRotation();
                                if (jQuery1_7_1options.image.onRotate != null)
                                    jQuery1_7_1options.image.onRotate(jQuery1_7_1slider,
                                        getData('image').rotation);
                            }
                        });

                    rotationContainerSlider.append(rotMin);
                    rotationContainerSlider.append(jQuery1_7_1slider);
                    rotationContainerSlider.append(rotMax);

                    if (jQuery1_7_1options.expose.rotationElement != '') {
                        jQuery1_7_1slider
                            .addClass(jQuery1_7_1options.expose.slidersOrientation);
                        rotationContainerSlider
                            .addClass(jQuery1_7_1options.expose.slidersOrientation);
                        rotMin.addClass(jQuery1_7_1options.expose.slidersOrientation);
                        rotMax.addClass(jQuery1_7_1options.expose.slidersOrientation);
                        jQuery1_7_1(jQuery1_7_1options.expose.rotationElement).empty().append(
                            rotationContainerSlider);
                    } else {
                        jQuery1_7_1slider.addClass('vertical');
                        rotationContainerSlider.addClass('vertical');
                        rotMin.addClass('vertical');
                        rotMax.addClass('vertical');
                        rotationContainerSlider.css({
                            'position': 'absolute',
                            'top': 5,
                            'left': 5,
                            'opacity': 0.6
                        });
                        _self.append(rotationContainerSlider);
                    }
                }
                ;

                function createZoomSlider() {

                    var zoomContainerSlider = jQuery1_7_1("<div />").attr('id',
                            'zoomContainer').mouseover(function () {
                            jQuery1_7_1(this).css('opacity', 1);
                        }).mouseout(function () {
                            jQuery1_7_1(this).css('opacity', 0.6);
                        });

                    var zoomMin = jQuery1_7_1('<div />').attr('id', 'zoomMin').html(
                        "<b>-</b>");
                    var zoomMax = jQuery1_7_1('<div />').attr('id', 'zoomMax').html(
                        "<b>+</b>");

                    var jQuery1_7_1slider = jQuery1_7_1("<div />").attr('id', 'zoomSlider');

                    // Apply Slider
                    jQuery1_7_1slider
                        .slider({
                            orientation: (jQuery1_7_1options.expose.zoomElement != '' ? jQuery1_7_1options.expose.slidersOrientation
                                : 'vertical'),
                            value: (jQuery1_7_1options.image.startZoom != 0 ? jQuery1_7_1options.image.startZoom
                                : getPercentOfZoom(getData('image'))),
                            min: (jQuery1_7_1options.image.useStartZoomAsMinZoom ? jQuery1_7_1options.image.startZoom
                                : jQuery1_7_1options.image.minZoom),
                            max: jQuery1_7_1options.image.maxZoom,
                            step: ((jQuery1_7_1options.zoomSteps > jQuery1_7_1options.image.maxZoom || jQuery1_7_1options.zoomSteps < 0) ? 1
                                : jQuery1_7_1options.zoomSteps),
                            slide: function (event, ui) {
                                var value = (jQuery1_7_1options.expose.slidersOrientation == 'vertical' ? (jQuery1_7_1options.image.maxZoom - ui.value)
                                    : ui.value);
                                var zoomInPx_width = (jQuery1_7_1options.image.width * Math.abs(value) / 100);
                                var zoomInPx_height = (jQuery1_7_1options.image.height * Math.abs(value) / 100);

                                jQuery1_7_1(jQuery1_7_1image).css({
                                    'width': zoomInPx_width + "px",
                                    'height': zoomInPx_height + "px"
                                });
                                var difX = (getData('image').w / 2) - (zoomInPx_width / 2);
                                var difY = (getData('image').h / 2) - (zoomInPx_height / 2);

                                var newX = (difX > 0 ? getData('image').posX
                                    + Math.abs(difX)
                                    : getData('image').posX
                                    - Math.abs(difX));
                                var newY = (difY > 0 ? getData('image').posY
                                    + Math.abs(difY)
                                    : getData('image').posY
                                    - Math.abs(difY));
                                getData('image').posX = newX;
                                getData('image').posY = newY;
                                getData('image').w = zoomInPx_width;
                                getData('image').h = zoomInPx_height;
                                calculateFactor();
                                calculateTranslationAndRotation();
                                if (jQuery1_7_1options.image.onZoom != null) {
                                    jQuery1_7_1options.image.onZoom(jQuery1_7_1image,
                                        getData('image'));
                                }
                            }
                        });

                    if (jQuery1_7_1options.slidersOrientation == 'vertical') {
                        zoomContainerSlider.append(zoomMax);
                        zoomContainerSlider.append(jQuery1_7_1slider);
                        zoomContainerSlider.append(zoomMin);
                    } else {
                        zoomContainerSlider.append(zoomMin);
                        zoomContainerSlider.append(jQuery1_7_1slider);
                        zoomContainerSlider.append(zoomMax);
                    }

                    if (jQuery1_7_1options.expose.zoomElement != '') {
                        zoomMin
                            .addClass(jQuery1_7_1options.expose.slidersOrientation);
                        zoomMax
                            .addClass(jQuery1_7_1options.expose.slidersOrientation);
                        jQuery1_7_1slider
                            .addClass(jQuery1_7_1options.expose.slidersOrientation);
                        zoomContainerSlider
                            .addClass(jQuery1_7_1options.expose.slidersOrientation);
                        jQuery1_7_1(jQuery1_7_1options.expose.zoomElement).empty().append(
                            zoomContainerSlider);
                    } else {
                        zoomMin.addClass('vertical');
                        zoomMax.addClass('vertical');
                        jQuery1_7_1slider.addClass('vertical');
                        zoomContainerSlider.addClass('vertical');
                        zoomContainerSlider.css({
                            'position': 'absolute',
                            'top': 5,
                            'right': 5,
                            'opacity': 0.6
                        });
                        _self.append(zoomContainerSlider);
                    }
                }
                ;

                function getPercentOfZoom() {
                    var percent = 0;
                    if (getData('image').w > getData('image').h) {
                        percent = jQuery1_7_1options.image.maxZoom
                            - ((getData('image').w * 100) / jQuery1_7_1options.image.width);
                    } else {
                        percent = jQuery1_7_1options.image.maxZoom
                            - ((getData('image').h * 100) / jQuery1_7_1options.image.height);
                    }
                    return percent;
                }
                ;

                function createSelector() {
                    if (jQuery1_7_1options.selector.centered) {
                        getData('selector').y = (jQuery1_7_1options.height / 2)
                            - (getData('selector').h / 2);
                        getData('selector').x = (jQuery1_7_1options.width / 2)
                            - (getData('selector').w / 2);
                    }

                    jQuery1_7_1selector = jQuery1_7_1('<div/>')
                        .attr('id', _self[0].id + '_selector')
                        .css(
                        {
                            'width': getData('selector').w,
                            'height': getData('selector').h,
                            'top': getData('selector').y
                                + 'px',
                            'left': getData('selector').x
                                + 'px',
                            'border': '1px dashed '
                                + jQuery1_7_1options.selector.borderColor,
                            'position': 'absolute',
                            'cursor': 'move'
                        })
                        .mouseover(
                        function () {
                            jQuery1_7_1(this)
                                .css(
                                {
                                    'border': '1px dashed '
                                        + jQuery1_7_1options.selector.borderColorHover
                                })
                        })
                        .mouseout(
                        function () {
                            jQuery1_7_1(this)
                                .css(
                                {
                                    'border': '1px dashed '
                                        + jQuery1_7_1options.selector.borderColor
                                })
                        });
                    // Add draggable to the selector
                    jQuery1_7_1selector
                        .draggable({
                            containment: 'parent',
                            iframeFix: true,
                            refreshPositions: true,
                            drag: function (event, ui) {
                                // Update position of the overlay
                                getData('selector').x = ui.position.left;
                                getData('selector').y = ui.position.top;
                                makeOverlayPositions(ui);
                                showInfo();
                                if (jQuery1_7_1options.selector.onSelectorDrag != null)
                                    jQuery1_7_1options.selector.onSelectorDrag(
                                        jQuery1_7_1selector,
                                        getData('selector'));
                            },
                            stop: function (event, ui) {
                                // hide overlay
                                if (jQuery1_7_1options.selector.hideOverlayOnDragAndResize)
                                    hideOverlay();
                                if (jQuery1_7_1options.selector.onSelectorDragStop != null)
                                    jQuery1_7_1options.selector
                                        .onSelectorDragStop(
                                            jQuery1_7_1selector,
                                            getData('selector'));
                            }
                        });
                    jQuery1_7_1selector
                        .resizable({
                            aspectRatio: jQuery1_7_1options.selector.aspectRatio,
                            maxHeight: jQuery1_7_1options.selector.maxHeight,
                            maxWidth: jQuery1_7_1options.selector.maxWidth,
                            minHeight: jQuery1_7_1options.selector.h,
                            minWidth: jQuery1_7_1options.selector.w,
                            containment: 'parent',
                            resize: function (event, ui) {
                                // update ovelay position
                                getData('selector').w = jQuery1_7_1selector
                                    .width();
                                getData('selector').h = jQuery1_7_1selector
                                    .height();
                                makeOverlayPositions(ui);
                                showInfo();
                                if (jQuery1_7_1options.selector.onSelectorResize != null)
                                    jQuery1_7_1options.selector.onSelectorResize(
                                        jQuery1_7_1selector,
                                        getData('selector'));
                            },
                            stop: function (event, ui) {
                                if (jQuery1_7_1options.selector.hideOverlayOnDragAndResize)
                                    hideOverlay();
                                if (jQuery1_7_1options.selector.onSelectorResizeStop != null)
                                    jQuery1_7_1options.selector
                                        .onSelectorResizeStop(
                                            jQuery1_7_1selector,
                                            getData('selector'));
                            }
                        });

                    showInfo(jQuery1_7_1selector);
                    // add selector to the main container
                    _self.append(jQuery1_7_1selector);
                }
                ;

                function showInfo() {

                    var _infoView = null;
                    var alreadyAdded = false;
                    if (jQuery1_7_1selector.find("#infoSelector").length > 0) {
                        _infoView = jQuery1_7_1selector.find("#infoSelector");
                    } else {
                        _infoView = jQuery1_7_1('<div />')
                            .attr('id', 'infoSelector')
                            .css(
                            {
                                'position': 'absolute',
                                'top': 0,
                                'left': 0,
                                'background': jQuery1_7_1options.selector.bgInfoLayer,
                                'opacity': 0.6,
                                'font-size': jQuery1_7_1options.selector.infoFontSize
                                    + 'px',
                                'font-family': 'Arial',
                                'color': jQuery1_7_1options.selector.infoFontColor,
                                'width': '100%'
                            });
                    }
                    if (jQuery1_7_1options.selector.showPositionsOnDrag) {
                        _infoView.html("X:" + Math.round(getData('selector').x)
                            + "px - Y:" + Math.round(getData('selector').y) + "px");
                        alreadyAdded = true;
                    }
                    if (jQuery1_7_1options.selector.showDimetionsOnDrag) {
                        if (alreadyAdded) {
                            _infoView.html(_infoView.html() + " | W:"
                                + getData('selector').w + "px - H:"
                                + getData('selector').h + "px");
                        } else {
                            _infoView.html("W:" + getData('selector').w
                                + "px - H:" + getData('selector').h
                                + "px");
                        }
                    }
                    jQuery1_7_1selector.append(_infoView);
                }
                ;

                function createOverlay() {
                    var arr = [ 't', 'b', 'l', 'r' ];
                    jQuery1_7_1.each(arr, function () {
                        var divO = jQuery1_7_1("<div />").attr("id", this).css({
                            'overflow': 'hidden',
                            'background': jQuery1_7_1options.overlayColor,
                            'opacity': 0.6,
                            'position': 'absolute',
                            'z-index': 2,
                            'visibility': 'visible'
                        });
                        _self.append(divO);
                    });
                }
                ;

                function makeOverlayPositions(ui) {

                    _self.find("#t").css({
                        "display": "block",
                        "width": jQuery1_7_1options.width,
                        'height': ui.position.top,
                        'left': 0,
                        'top': 0
                    });
                    _self.find("#b").css(
                        {
                            "display": "block",
                            "width": jQuery1_7_1options.width,
                            'height': jQuery1_7_1options.height,
                            'top': (ui.position.top + jQuery1_7_1selector
                                .height())
                                + "px",
                            'left': 0
                        });
                    _self.find("#l").css({
                        "display": "block",
                        'left': 0,
                        'top': ui.position.top,
                        'width': ui.position.left,
                        'height': jQuery1_7_1selector.height()
                    });
                    _self.find("#r").css(
                        {
                            "display": "block",
                            'top': ui.position.top,
                            'left': (ui.position.left + jQuery1_7_1selector
                                .width())
                                + "px",
                            'width': jQuery1_7_1options.width,
                            'height': jQuery1_7_1selector.height() + "px"
                        });
                }
                ;

                function hideOverlay() {
                    _self.find("#t").hide();
                    _self.find("#b").hide();
                    _self.find("#l").hide();
                    _self.find("#r").hide();
                }

                function setData(key, data) {
                    _self.data(key, data);
                }
                ;

                function getData(key) {
                    return _self.data(key);
                }
                ;

                function adjustingSizesInRotation() {
                    var angle = getData('image').rotation * Math.PI / 180;
                    var sin = Math.sin(angle);
                    var cos = Math.cos(angle);

                    // (0,0) stays as (0, 0)

                    // (w,0) rotation
                    var x1 = cos * getData('image').w;
                    var y1 = sin * getData('image').w;

                    // (0,h) rotation
                    var x2 = -sin * getData('image').h;
                    var y2 = cos * getData('image').h;

                    // (w,h) rotation
                    var x3 = cos * getData('image').w - sin * getData('image').h;
                    var y3 = sin * getData('image').w + cos * getData('image').h;

                    var minX = Math.min(0, x1, x2, x3);
                    var maxX = Math.max(0, x1, x2, x3);
                    var minY = Math.min(0, y1, y2, y3);
                    var maxY = Math.max(0, y1, y2, y3);

                    getData('image').rotW = maxX - minX;
                    getData('image').rotH = maxY - minY;
                    getData('image').rotY = minY;
                    getData('image').rotX = minX;
                };

                function createMovementControls() {
                    var table = jQuery1_7_1('<table>\
                                    <tr>\
                                    <td></td>\
                                    <td></td>\
                                    <td></td>\
                                    </tr>\
                                    <tr>\
                                    <td></td>\
                                    <td></td>\
                                    <td></td>\
                                    </tr>\
                                    <tr>\
                                    <td></td>\
                                    <td></td>\
                                    <td></td>\
                                    </tr>\
                                    </table>');
                    var btns = [];
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_no mvn'));
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_n mvn'));
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_ne mvn'));
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_o mvn'));
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_c'));
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_e mvn'));
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_so mvn'));
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_s mvn'));
                    btns.push(jQuery1_7_1('<div />').addClass('mvn_se mvn'));
                    for (var i = 0; i < btns.length; i++) {
                        btns[i].mousedown(function () {
                            moveImage(this);
                        }).mouseup(function () {
                                clearTimeout(tMovement);
                            }).mouseout(function () {
                                clearTimeout(tMovement);
                            });
                        table.find('td:eq(' + i + ')').append(btns[i]);
                        jQuery1_7_1(jQuery1_7_1options.expose.elementMovement).empty().append(table);

                    }
                }
                ;

                function moveImage(obj) {

                    if (jQuery1_7_1(obj).hasClass('mvn_no')) {
                        getData('image').posX = (getData('image').posX - jQuery1_7_1options.expose.movementSteps);
                        getData('image').posY = (getData('image').posY - jQuery1_7_1options.expose.movementSteps);
                    } else if (jQuery1_7_1(obj).hasClass('mvn_n')) {
                        getData('image').posY = (getData('image').posY - jQuery1_7_1options.expose.movementSteps);
                    } else if (jQuery1_7_1(obj).hasClass('mvn_ne')) {
                        getData('image').posX = (getData('image').posX + jQuery1_7_1options.expose.movementSteps);
                        getData('image').posY = (getData('image').posY - jQuery1_7_1options.expose.movementSteps);
                    } else if (jQuery1_7_1(obj).hasClass('mvn_o')) {
                        getData('image').posX = (getData('image').posX - jQuery1_7_1options.expose.movementSteps);
                    } else if (jQuery1_7_1(obj).hasClass('mvn_c')) {
                        getData('image').posX = (jQuery1_7_1options.width / 2)
                            - (getData('image').w / 2);
                        getData('image').posY = (jQuery1_7_1options.height / 2)
                            - (getData('image').h / 2);
                    } else if (jQuery1_7_1(obj).hasClass('mvn_e')) {
                        getData('image').posX = (getData('image').posX + jQuery1_7_1options.expose.movementSteps);
                    } else if (jQuery1_7_1(obj).hasClass('mvn_so')) {
                        getData('image').posX = (getData('image').posX - jQuery1_7_1options.expose.movementSteps);
                        getData('image').posY = (getData('image').posY + jQuery1_7_1options.expose.movementSteps);
                    } else if (jQuery1_7_1(obj).hasClass('mvn_s')) {
                        getData('image').posY = (getData('image').posY + jQuery1_7_1options.expose.movementSteps);
                    } else if (jQuery1_7_1(obj).hasClass('mvn_se')) {
                        getData('image').posX = (getData('image').posX + jQuery1_7_1options.expose.movementSteps);
                        getData('image').posY = (getData('image').posY + jQuery1_7_1options.expose.movementSteps);
                    }
                    if (jQuery1_7_1options.image.snapToContainer) {
                        if (getData('image').posY > 0) {
                            getData('image').posY = 0;
                        }
                        if (getData('image').posX > 0) {
                            getData('image').posX = 0;
                        }

                        var bottom = -(getData('image').h - _self.height());
                        var right = -(getData('image').w - _self.width());
                        if (getData('image').posY < bottom) {
                            getData('image').posY = bottom;
                        }
                        if (getData('image').posX < right) {
                            getData('image').posX = right;
                        }
                    }
                    calculateTranslationAndRotation();
                    tMovement = setTimeout(function () {
                        moveImage(obj);
                    }, 100);
                }

                jQuery1_7_1.fn.cropzoom.getParameters = function (_self, custom) {
                    var image = _self.data('image');
                    var selector = _self.data('selector');
                    var fixed_data = {
                        'viewPortW': _self.width(),
                        'viewPortH': _self.height(),
                        'imageX': image.posX,
                        'imageY': image.posY,
                        'imageRotate': image.rotation,
                        'imageW': image.w,
                        'imageH': image.h,
                        'imageSource': image.source,
                        'selectorX': selector.x,
                        'selectorY': selector.y,
                        'selectorW': selector.w,
                        'selectorH': selector.h
                    };
                    return jQuery1_7_1.extend(fixed_data, custom);
                };

                jQuery1_7_1.fn.cropzoom.getSelf = function () {
                    return _self;
                }
                /*jQuery1_7_1.fn.cropzoom.getOptions = function() {
                 return _self.getData('options');
                 }*/

                // Maintein Chaining
                return this;
            });

    };


    // Css Hooks
    /*
     * jQuery1_7_1.cssHooks["MsTransform"] = { set: function( elem, value ) {
     * elem.style.msTransform = value; } };
     */

    jQuery1_7_1.fn.extend({
        // Function to set the selector position and sizes
        setSelector: function (x, y, w, h, animate) {

            var _self = jQuery1_7_1(this);
            if (animate != undefined && animate == true) {
                _self.find('#' + _self[0].id + '_selector').animate({
                    'top': y,
                    'left': x,
                    'width': w,
                    'height': h
                }, 'slow');
            } else {
                _self.find('#' + _self[0].id + '_selector').css({
                    'top': y,
                    'left': x,
                    'width': w,
                    'height': h
                });
            }

            _self.data('selector', {
                x: x,
                y: y,
                w: w,
                h: h
            });

        },
        // Restore the Plugin
        restore: function () {
            var obj = jQuery1_7_1(this);
            var jQuery1_7_1options = obj.data('options');
            obj.empty();
            obj.data('image', {});
            obj.data('selector', {});
            if (jQuery1_7_1options.expose.zoomElement != "") {
                jQuery1_7_1(jQuery1_7_1options.expose.zoomElement).empty();
            }
            if (jQuery1_7_1options.expose.rotationElement != "") {
                jQuery1_7_1(jQuery1_7_1options.expose.rotationElement).empty();
            }
            if (jQuery1_7_1options.expose.elementMovement != "") {
                jQuery1_7_1(jQuery1_7_1options.expose.elementMovement).empty();
            }
            obj.cropzoom(jQuery1_7_1options);

        },
        // Send the Data to the Server
        send: function (url, type, custom, onSuccess) {
            var _self = jQuery1_7_1(this);
            var response = "";
            jQuery1_7_1.ajax({
                url: url,
                type: type,
                data: (_self.cropzoom.getParameters(_self, custom)),
                success: function (r) {
                    _self.data('imageResult', r);
                    if (onSuccess !== undefined && onSuccess != null)
                        onSuccess(r);
                }
            });
        }
    });

})(jQuery1_7_1);

//Adding touch fix

/*!
 * jQuery1_7_1 UI Touch Punch 0.2.2
 *
 * Copyright 2011, Dave Furfero
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Depends:
 *  jQuery1_7_1.ui.widget.js
 *  jQuery1_7_1.ui.mouse.js
 */ (function(jQuery1_7_1) {

    // Detect touch support
    jQuery1_7_1.support.touch = 'ontouchend' in document;

    // Ignore browsers without touch support
    if (!jQuery1_7_1.support.touch) {
        return;
    }

    var mouseProto = jQuery1_7_1.ui.mouse.prototype,
        _mouseInit = mouseProto._mouseInit,
        touchHandled;

    /**
     * Simulate a mouse event based on a corresponding touch event
     * @param {Object} event A touch event
     * @param {String} simulatedType The corresponding mouse event
     */
    function simulateMouseEvent(event, simulatedType) {

        // Ignore multi-touch events
        if (event.originalEvent.touches.length > 1) {
            return;
        }

        event.preventDefault();

        var touch = event.originalEvent.changedTouches[0],
            simulatedEvent = document.createEvent('MouseEvents');

        // Initialize the simulated mouse event using the touch event's coordinates
        simulatedEvent.initMouseEvent(
            simulatedType, // type
            true, // bubbles
            true, // cancelable
            window, // view
            1, // detail
            touch.screenX, // screenX
            touch.screenY, // screenY
            touch.clientX, // clientX
            touch.clientY, // clientY
            false, // ctrlKey
            false, // altKey
            false, // shiftKey
            false, // metaKey
            0, // button
            null // relatedTarget
        );

        // Dispatch the simulated event to the target element
        event.target.dispatchEvent(simulatedEvent);
    }

    /**
     * Handle the jQuery1_7_1 UI widget's touchstart events
     * @param {Object} event The widget element's touchstart event
     */
    mouseProto._touchStart = function(event) {

        var self = this;

        // Ignore the event if another widget is already being handled
        if (touchHandled || !self._mouseCapture(event.originalEvent.changedTouches[0])) {
            return;
        }

        // Set the flag to prevent other widgets from inheriting the touch event
        touchHandled = true;

        // Track movement to determine if interaction was a click
        self._touchMoved = false;

        // Simulate the mouseover event
        simulateMouseEvent(event, 'mouseover');

        // Simulate the mousemove event
        simulateMouseEvent(event, 'mousemove');

        // Simulate the mousedown event
        simulateMouseEvent(event, 'mousedown');
    };

    /**
     * Handle the jQuery1_7_1 UI widget's touchmove events
     * @param {Object} event The document's touchmove event
     */
    mouseProto._touchMove = function(event) {

        // Ignore event if not handled
        if (!touchHandled) {
            return;
        }

        // Interaction was not a click
        this._touchMoved = true;

        // Simulate the mousemove event
        simulateMouseEvent(event, 'mousemove');
    };

    /**
     * Handle the jQuery1_7_1 UI widget's touchend events
     * @param {Object} event The document's touchend event
     */
    mouseProto._touchEnd = function(event) {

        // Ignore event if not handled
        if (!touchHandled) {
            return;
        }

        // Simulate the mouseup event
        simulateMouseEvent(event, 'mouseup');

        // Simulate the mouseout event
        simulateMouseEvent(event, 'mouseout');

        // If the touch interaction did not move, it should trigger a click
        if (!this._touchMoved) {

            // Simulate the click event
            simulateMouseEvent(event, 'click');
        }

        // Unset the flag to allow other widgets to inherit the touch event
        touchHandled = false;
    };

    /**
     * A duck punch of the jQuery1_7_1.ui.mouse _mouseInit method to support touch events.
     * This method extends the widget with bound touch event handlers that
     * translate touch events to mouse events and pass them to the widget's
     * original mouse event handling methods.
     */
    mouseProto._mouseInit = function() {

        var self = this;

        // Delegate the touch handlers to the widget's element
        self.element.on('touchstart', jQuery1_7_1.proxy(self, '_touchStart'))
            .on('touchmove', jQuery1_7_1.proxy(self, '_touchMove'))
            .on('touchend', jQuery1_7_1.proxy(self, '_touchEnd'));

        // Call the original jQuery1_7_1.ui.mouse init method
        _mouseInit.call(self);
    };

})(jQuery1_7_1);