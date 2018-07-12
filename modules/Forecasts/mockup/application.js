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
 ********************************************************************************/!function($){$(function(){$('.collapse').on('show',function(){$(this).parent().find('.icon-chevron-up').remove();$(this).parent().find('.icon-chevron-down').remove();$(this).parent().find('h4').append('<i class="icon-chevron-up icon-sm pull-right"></i>');})
$('.collapse').on('hide',function(){$(this).parent().find('.icon-chevron-down').remove();$(this).parent().find('.icon-chevron-up').remove();$(this).parent().find('h4').append('<i class="icon-chevron-down icon-sm pull-right"></i>');})
$('.drawerTrig').on('click',function(){$(this).toggleClass('pull-right').toggleClass('pull-left');$(this).find('i').toggleClass('icon-chevron-left').toggleClass('icon-chevron-right');$('#drawer').toggleClass('span2');$('.bordered').toggleClass('hide');$('#charts').toggleClass('span10').toggleClass('span12');return false;})
$('.loading').click(function(){var btn=$(this)
btn.button('loading')
setTimeout(function(){btn.button('reset');$('.modal').modal('hide')},2000)})
$('#tour').on('click',function(e){$('.pointsolight').prependTo('body');})
$('#folded').find('[data-toggle=tab]').on('click',function(e){$('.nav-tabs').find('li').removeClass('active');})
$('.btngroup .btn').button()
$('.dblclick').hover(function(){$(this).before('<span class="span2" style="position: absolute; left: -20px; width: 15px"><i class="icon-pencil icon-sm"></i></span>');},function(){$('span.span2').remove();})})
$('.addit').on('click',function(){$(this).toggleClass('active');$(this).parent().parent().parent().find('.form-addit').toggleClass('hide');return false;})
$('.search').on('click',function(){$(this).toggleClass('active');$(this).parent().parent().parent().find('.dataTables_filter').toggle();$(this).parent().parent().parent().find('.form-search').toggleClass('hide');return false;})
$(".chzn-select").chosen()
$(".chzn-select-deselect").chosen({allow_single_deselect:true})}(window.jQuery)