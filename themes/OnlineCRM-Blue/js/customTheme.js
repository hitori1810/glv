$(document).ready(function(){
    //Collapse and expand supanel - Add by MTN: 09/02/2015
    $('#subpanel_list h3 span').click(function(){
        $(this).find('span').find('a:visible').trigger('onclick');
    })
    //END: Collapse and expand supanel - Add by MTN: 09/02/2015

    //Change color icon search advance in top menu - add by MTN: 09/02/2015
    $('#sugar_spot_search').focus(function(){
        $('#glblSearchBtn .btn-group .searchIconLink').addClass('focusIn');
        $('#glblSearchBtn .btn-group .searchIconLink').removeClass('focusOut');
    })

    $('#sugar_spot_search').focusout(function(){
        $('#glblSearchBtn .btn-group .searchIconLink').addClass('focusOut');
        $('#glblSearchBtn .btn-group .searchIconLink').removeClass('focusIn');
    })
    //END: Change color icon search advance in top menu - add by MTN: 09/02/2015
    
});