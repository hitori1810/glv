/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

!function ($) {
  $(function(){
         /*
    // make code pretty (styleguide only)
    window.prettyPrint && prettyPrint()

    // add tipsies to grid for scaffolding (styleguide only)
    if ($('#grid-system').length) {
      $('#grid-system').tooltip({
          selector: '.show-grid > div'
        , title: function () { return $(this).width() + 'px' }
      })
    }
    
    // toggle all stars
    $('.toggle-all-stars').on('click', function (e) {
    		$(this).closest('table').toggleClass('active'); 
    		return false;
    })

    // toggle all checkboxes
    $('.toggle-all').on('click', function (e) {
    		$('table').find(':checkbox').attr('checked', this.checked);      
    })
    
    // timeout the alerts
    setTimeout(function(){$('.timeten').fadeOut().remove();},9000)

    // toggle star
    $('.icon-star').on('click', function (e) {
    		$(this).parent().toggleClass('active');
    		return false;  
    })

    // toggle more hide
    $('.more').toggle(
      function (e) {
    		$(this).parent().prev('.extend').removeClass('hide');
    		$(this).html('Less &nbsp;<i class="icon-chevron-up"></i>');
    		return false;  
      },
      function (e) {
      		$(this).parent().prev('.extend').addClass('hide');
      		$(this).html('More &nbsp;<i class="icon-chevron-down"></i>');
      		return false;  
    })

    // editable
    $('td .dblclick').hover( 
      function () {$(this).before('<i class="icon-pencil icon-sm"></i>');},
      function () {$('.icon-pencil').remove();}
  	)
    $("a[rel=popover]")
      .popover()
      .click(function(e) {
        e.preventDefault()
      })
    $("a[rel=popoverTop]")
      .popover({
        placement: "top"
      })
      .click(function(e) {
        e.preventDefault()
      })
      
    // fix sub nav on scroll
    var $win = $(window)
      , $nav = $('.subnav')
      , navTop = $('.subnav').length && $('.subnav').offset().top - 40
      , isFixed = 0

    processScroll()
    $win.on('scroll', processScroll)

    function processScroll() {
      var i, scrollTop = $win.scrollTop()
      if (scrollTop >= navTop && !isFixed) {
        isFixed = 1
        $nav.addClass('subnav-fixed')
      } else if (scrollTop <= navTop && isFixed) {
        isFixed = 0
        $nav.removeClass('subnav-fixed')
      }
    }

    // do this if greater than 768px page width
    if ( $(window).width() > 768) {		
    // tooltip demo
    $('body').tooltip({
      selector: "[rel=tooltip]"
    })
    $('table').tooltip({
			delay: { show: 500, hide: 10 },
      selector: "[rel=tooltip]"
    })
    $('.block, .thumbnail').tooltip({
      selector: "a[rel=tooltip]",
			placement: "bottom"
    })
    $('.navbar, .subnav').tooltip({
      selector: "a[rel=tooltip]",
			placement: "bottom"
    })  
    }                            */
    
    $('.collapse').on('show', function () {
      $(this).parent().find('.icon-chevron-up').remove();
            $(this).parent().find('.icon-chevron-down').remove();
      $(this).parent().find('h4').append('<i class="icon-chevron-up icon-sm pull-right"></i>');
    })
    
    $('.collapse').on('hide', function () {
      $(this).parent().find('.icon-chevron-down').remove();
            $(this).parent().find('.icon-chevron-up').remove();
      $(this).parent().find('h4').append('<i class="icon-chevron-down icon-sm pull-right"></i>');
    })

    // column collapse
    $('.drawerTrig').on('click',
    function () {
      $(this).toggleClass('pull-right').toggleClass('pull-left');
      $(this).find('i').toggleClass('icon-chevron-left').toggleClass('icon-chevron-right');
      $('#drawer').toggleClass('span2');
      $('.bordered').toggleClass('hide');
      $('#charts').toggleClass('span10').toggleClass('span12');
      return false;
    })
    
    // button state demo
    $('.loading')
      .click(function () {
        var btn = $(this)
        btn.button('loading')
        setTimeout(function () {
          btn.button('reset');
					$('.modal').modal('hide')
        }, 2000)
      })

		// tour
    $('#tour').on('click', function (e) {
			$('.pointsolight').prependTo('body');
    })
    
    // remove a close item
    $('#folded').find('[data-toggle=tab]').on('click', function (e) {
			$('.nav-tabs').find('li').removeClass('active');
    })
    
    $('.btngroup .btn').button()

    // datepicker
    //$('[rel=datepicker]').datepicker({
      //format: 'mm-dd-yyyy'
    //})

    // colorpicker
    //$('[rel=colorpicker]').colorpicker({
  	//	format: 'hex'
  	//})

    // editable example
    $('.dblclick').hover(
      function () {$(this).before('<span class="span2" style="position: absolute; left: -20px; width: 15px"><i class="icon-pencil icon-sm"></i></span>');},
      function () {$('span.span2').remove();}
  	)
  	
  })
  
  // toggle module search (needs tap logic for mobile)
	$('.addit').on('click', function () {
	    $(this).toggleClass('active');
	    $(this).parent().parent().parent().find('.form-addit').toggleClass('hide');
	    return false;
	})
	$('.search').on('click', function () {
	    $(this).toggleClass('active');
	    $(this).parent().parent().parent().find('.dataTables_filter').toggle();
	    $(this).parent().parent().parent().find('.form-search').toggleClass('hide');
	    return false;
	})
//  $('#moduleTwitter.filtered input').quicksearch('#moduleTwitter article')
//  $('#moduleLog.filtered input').quicksearch('#moduleLog article')
//  $('#moduleRelated.filtered input').quicksearch('#moduleRelated article')
//  $('#moduleActivity.filtered input').quicksearch('#moduleActivity article')
//  $('#moduleActivity.filtered input').quicksearch('#moduleActivity .results li')
 
  // datagrid
//  $('table.datatable').dataTable({
//    "bPaginate": false,
//    "bFilter": true,
//    "bInfo": false,
//    "bAutoWidth": true
//  })
  
  // Select widget
  $(".chzn-select").chosen()
  $(".chzn-select-deselect").chosen({allow_single_deselect:true})
}(window.jQuery)