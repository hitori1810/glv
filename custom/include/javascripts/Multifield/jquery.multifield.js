/**
* jQuery Multifield plugin
*
* https://github.com/maxkostinevich/jquery-multifield
*/


// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, document, undefined ) {

    /*
    * Plugin Options
    * section (string) -  selector of the section which is located inside of the parent wrapper
    * max (int) - Maximum sections
    * btnAdd (string) - selector of the "Add section" button - can be located everywhere on the page
    * btnRemove (string) - selector of the "Remove section" button - should be located INSIDE of the "section"
    * locale (string) - language to use, default is english
    */

    // our plugin constructor
    var multiField = function( elem, options ){
        this.elem = elem;
        this.$elem = $(elem);
        this.options = options;
        // Localization
        this.localize_i18n='';

        // This next line takes advantage of HTML5 data attributes
        // to support customization of the plugin on a per-element
        // basis. For example,
        // <div class=item' data-mfield-options='{"section":".group"}'></div>
        this.metadata = this.$elem.data( 'mfield-options' );
    };

    // the plugin prototype
    multiField.prototype = {

        defaults: {
            max: 0,
            min: 1, //add by Trung Nguyen 2015.12.29
            locale: 'default'
        },


        init: function() {
            var $this = this; //Plugin object
            // Introduce defaults that can be extended either
            // globally or using an object literal.
            this.config = $.extend({}, this.defaults, this.options,
                this.metadata);
            // Hide 'Remove' buttons if only one section exists

            if(this.getSectionsCount() < this.config.min + 2) { //modify by Trung Nguyen 2015.12.29
                $(this.config.btnRemove, this.$elem).hide();       
            }

            // Add section
            this.$elem.on('click',this.config.btnAdd,function(e){
                e.preventDefault();
                $this.cloneSection();
            });
            // Remove section
            this.$elem.on('click',this.config.btnRemove,function(e){

                e.preventDefault();
                var currentSection=$(e.target.closest($this.config.section));
                $this.removeSection(currentSection);
            });

            return this;
        },

        /*
        * Load localization file via AJAX
        */
        loadLocale: function(){
            return $.ajax({
                url: 'locale/'+this.config.locale+'.json',
                dataType: 'json',
                async: true,
                error: function( data ) {
                    console.log("Localization file not found");
                }
            });
        },

        /*
        * Add new section
        */
        cloneSection : function() {
            if(typeof this.options.fnCloneSection == 'function') {
                this.options.fnCloneSection();
                return;
            }

            // Allow to add only allowed max count of sections
            if((this.config.max!==0)&&(this.getSectionsCount()+1)>this.config.max){
                return false;
            }

            // Clone last section   
            var newChild = $(this.config.section, this.$elem).first().clone().attr('style', '').attr('id', '').fadeIn('fast');

            // Clear input values
            $('input[type=text],input[type=hidden],textarea', newChild).each(function () {
                $(this).val('');
            });

            // Fix radio buttons: update name [i] to [i+1]
            newChild.find('input[type="radio"]').each(function(){var name=$(this).attr('name');$(this).attr('name',name.replace(/([0-9]+)/g,1*(name.match(/([0-9]+)/g))+1));});
            // Reset radio button selection
            $('input[type=radio]',newChild).attr('checked', false);

            // Clear images src with reset-image-src class
            $('img.reset-image-src', newChild).each(function () {
                $(this).attr('src', '');
            });
            // Append new section
            if(this.config.addTo == '' || this.config.addTo == null)
                this.$elem.append(newChild);
            else
                $(this.config.addTo).append(newChild);

            // Show 'remove' button  - By Tung Bui
            var sectionsCount = this.getSectionsCount();
            if(sectionsCount > this.config.min + 1){ // modify by Trung Nguyen 2015.12.29
                $(this.config.btnRemove, this.$elem).show();
            }
            // Handle add row
            handleAddRow(this.$elem); 
        },

        /*
        * Remove existing section
        */
        removeSection : function(section){
            var prompt = this.config.prompt;
            if (typeof prompt === "undefined" || prompt === null)
                prompt = true; 
            if(prompt){
                if(!confirm("Are you sure you want to remove this row?"))
                    return false;
            }
            
            if(!beforeRemoveSection(section)) return;    //add by Trung Nguyen for before remove row 2015.12.29
            var sectionsCount = this.getSectionsCount();

            if(sectionsCount <= this.config.min + 2){
                $(this.config.btnRemove,this.$elem).hide();
            }
            $(this).addClass("deleted");
            section.fadeOut(300, function () {
                $(this).detach();
                handleRemoveRow(this.$elem);
            });

        },

        /*
        * Get sections count
        */
        getSectionsCount: function(){
            if(this.config.addTo == '' || this.config.addTo == null)
                return this.$elem.children(this.config.section).length;
            else
                return $(this.config.addTo).children(this.config.section).length;
        }

    };

    multiField.defaults = multiField.prototype.defaults;
    $.fn.multifield = function(options) {
        return this.each(function() {
            new multiField(this, options).init();
        });
    };



})( jQuery, window, document );

function handleAddRow(_tbl){
}

function handleRemoveRow(_tbl){
}
function beforeRemoveSection(section) {
    return true;
}
