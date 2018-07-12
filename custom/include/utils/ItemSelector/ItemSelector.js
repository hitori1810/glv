$(function(){
    // Init item selector table
    var oTable = $('#tblItemSelector').dataTable({
        "aLengthMenu": [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]],
        "aoColumnDefs": [{bSortable: false, aTargets: [0]}],
        "sPaginationType": "full_numbers",
        "bInfo" : false,
        "oLanguage": {
            "sLengthMenu": "Display _MENU_ records",
            "sSearch": "Search all columns:",
            "sEmptyTable": "No data available in table",
            "sInfo": "Got a total of _TOTAL_ entries to show (_START_ to _END_)",
            "sInfoFiltered": " - filtering from _MAX_ records",
            "sInfoEmpty": "No entries to show",
            "sZeroRecords": "No records to display",
        }
    });

    // Init filtering
    $("#filter").find(".filter").each(function() {
        filter(oTable, $(this));
    });
    
    // Filtering
    $("#filter").find(".filter").keyup(function() {
        filter(oTable, $(this));
    });
    
    $("#tblItemSelector").freezeHeader({
        'marginTop': '-10px'
    });
    
    // Scroll to top when user clicked on the header
    $('.topFreezeHeader').click(function(){
        var offsetTop = $('.itemSelector').offset().top - 10;
        $('body, html').animate({scrollTop : offsetTop}, 500);
    });
    
    $(window).scroll(function() {
        // Hide filters when the freeze header is displaying.
        if($(".topFreezeHeader").is(':visible')) {
            $(".topFreezeHeader").find(':checkbox').hide();    
            $(".topFreezeHeader").css({'height':'40px', 'overflow':'hidden'});
        }
        
        displayBtnAddItems();
    });

    // Init dialog
    $("#itemSelector").dialog({
        autoOpen: false,
        modal: true,
        width: 800,
        open: function(event, ui) {
            easierCheckbox();
            displayBtnAddItems();
        },
    });
    
    // Click on the #cbxCheckAll
    $('.itemSelector').on('click', '#cbxCheckAll', function(){
        if($(this).is(':checked')) {
            $('.cbxItemId').prop('checked', 'checked');   
        } else {
            $('.cbxItemId').prop('checked', false);
        }
    });
    
    // Click on individual checkbox
    $('.itemSelector').on('click', '.cbxItemId', function(){
        if(!$(this).is(':checked')) {
            $('#cbxCheckAll').prop('checked', false);   
        } else if($('.itemSelector').find('.cbxItemId').length == $('.itemSelector').find('.cbxItemId:checked').length) {
            $('#cbxCheckAll').prop('checked', 'checked');
        }
    });
    
    // Close dialog
    $('#btnClose').click(function(){
        $("#itemSelector").dialog('close');    
    });
});

function filter(oTable, input) {
    var keyword = input.val();
    var forCol = input.attr('for-col');
    oTable.fnFilter(keyword, forCol);    
}

// Display button add item based on the selector table state
function displayBtnAddItems() {
    if($(".itemSelector").height() > $(window).height()) {
        $(".itemSelector").find('#btnHolder').removeClass('normal').addClass('fixed');    
    } else {
        $(".itemSelector").find('#btnHolder').removeClass('fixed').addClass('normal');
    }
}

// Easier to check
function easierCheckbox() {
    $('.cbxHolder').find('input[type="checkbox"]').click(function(e){
        e.stopPropagation();    
    });
    
    $('.cbxHolder').click(function(){
        var checkbox = $(this).find('input[type="checkbox"]');
        setTimeout(function(){
            checkbox.attr('checked', !checkbox.is(':checked'));
        }, 100);
    });
}