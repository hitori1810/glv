jQuery(document).ready(function(){
    //Select all anchor tag with rel set to tooltip
    jQuery('.tooltip').live('mouseover',function(e) {
        //Grab the title attribute's value and assign it to a variable
        var tip = jQuery(this).attr('tooltip_content');
        //Remove the title attribute's to avoid the native tooltip from the browser
        jQuery(this).append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + tip + '</div><div class="tipFooter"></div></div>');
        //Show the tooltip with faceIn effect
        jQuery('#tooltip').fadeIn('500');
        jQuery('#tooltip').fadeTo('10',0.9);
    }).live('mousemove',function(e){
        //Keep changing the X and Y axis for the tooltip, thus, the tooltip move along with the mouse

        jQuery('#tooltip').css('top', e.pageY + 5 );
        if((jQuery(window).width() - (jQuery(this).offset().left)) < jQuery('#tooltip').width()){
            jQuery('#tooltip').css('right', jQuery(window).width() - (jQuery(this).offset().left));
        }
        else {
            jQuery('#tooltip').css('left', e.pageX + 5 );
        }
    }).live('mouseout',function() {
        //Put back the title attribute's value
        //jQuery(this).attr('title',jQuery('.tipBody').html());
        //Remove the appended tooltip template
         jQuery(this).children('div#tooltip').remove();
    });
});