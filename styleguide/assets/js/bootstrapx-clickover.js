/* ==========================================================
 * bootstrapx-clickover.js
 * https://github.com/lecar-red/bootstrapx-clickover
 * ==========================================================
 *
 * Based on work from Twitter Bootstrap and
 * from Popover library http://twitter.github.com/bootstrap/javascript.html#popover
 * from the great guys at Twitter.
 *
 * ========================================================== */!function($){"use strict"
var Clickover=function(element,options){this.cinit('clickover',element,options);}
Clickover.prototype=$.extend({},$.fn.popover.Constructor.prototype,{constructor:Clickover,cinit:function(type,element,options){this.attr={};this.attr.me=+(new Date);this.attr.click_event_ns="click."+this.attr.me;if(!options)options={};options.trigger='manual';this.init(type,element,options);this.$element.on('click',this.options.selector,$.proxy(this.clickery,this));},clickery:function(e){e.preventDefault();e.stopPropagation();this.options.width&&this.tip().find('.popover-inner').width(this.options.width);this.options.height&&this.tip().find('.popover-inner').height(this.options.height);this.options.tip_id&&this.tip().attr('id',this.options.tip_id);this.toggle();if(this.isShown()){this.options.global_close&&$('body').one(this.attr.click_event_ns,$.proxy(this.hide,this));this.tip().on('click',function(e){e.stopPropagation();});this.tip().on('click','[data-dismiss="clickover"]',$.proxy(this.hide,this));if(this.options.auto_close&&this.options.auto_close>0){this.attr.tid=setTimeout($.proxy(this.hide,this),this.options.auto_close);}
typeof this.options.onShown=='function'&&this.options.onShown.call(this);this.$element.trigger('shown');}
else{$('body').off(this.attr.click_event_ns);if(typeof this.attr.tid=="number"){clearTimeout(this.attr.tid);delete this.attr.tid;}
typeof this.options.onHidden=='function'&&this.options.onHidden.call(this);this.$element.trigger('hidden');}},isShown:function(){return this.tip().hasClass('in');},debughide:function(){var dt=new Date().toString();console.log(dt+": clickover hide");this.hide();}})
$.fn.clickover=function(option){return this.each(function(){var $this=$(this),data=$this.data('clickover'),options=typeof option=='object'&&option
if(!data)$this.data('clickover',(data=new Clickover(this,options)))
if(typeof option=='string')data[option]()})}
$.fn.clickover.Constructor=Clickover
$.fn.clickover.defaults=$.extend({},$.fn.popover.defaults,{trigger:'manual',auto_close:0,global_close:1,onShown:null,onHidden:null,width:null,height:null,tip_id:null})}(window.jQuery);