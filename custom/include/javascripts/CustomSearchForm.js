/*
* CustomSearchForm.js
* Author: Hieu Nguyen
* Date: 2015-04-20
* Purpose: Customize search form layout
*/

var SearchForm = {}
SearchForm.showAdvancedFields = false;

// Init
SearchForm.init = function() {
    SearchForm.showAdvancedFields = $('#isShowMore').val() == '1';
    
    $('#basic_search_link').removeAttr('onclick');
    SearchForm.display();
    
    $('#basic_search_link').click(function(){
        SearchForm.showAdvancedFields = !SearchForm.showAdvancedFields;
        $('#isShowMore').val(SearchForm.showAdvancedFields ? 1 : 0);
        SearchForm.display();
        return false;    
    });        
}

// Display search form based on showAdvancedFields status
SearchForm.display = function() {
    var searchTable = $('div[id*="advanced_searchSearchForm"]').children('table');
    var advancedFields = searchTable.find('tr').not('tr:first').not('tr:last');
    
    if(SearchForm.showAdvancedFields) {
        advancedFields.show();
        $('#basic_search_link').text(SUGAR.language.get('app_strings', 'LBL_SEARCH_FORM_SHOW_LESS'));
        $('#basic_search_link').removeClass('expanded').addClass('collapsed');   
    } else {
        if($('#showSSDIV').val() == 'yes' && typeof toggleInlineSearch === 'function') toggleInlineSearch();
        advancedFields.hide();
        $('#basic_search_link').text(SUGAR.language.get('app_strings', 'LBL_SEARCH_FORM_SHOW_MORE'));
        $('#basic_search_link').removeClass('collapsed').addClass('expanded');
    }
}

SUGAR.util.doWhen(function () {
    return $('#basic_search_link')[0] != null;
}, function () {
    SearchForm.init();
});


/**
* Add by Kelvin Thang -- Date: 05/01/2018
* fix remove Layout Options in search when clear search
*/
SUGAR.searchForm = function() {
    var url;
    return {         
        // This function is here to clear the form, instead of "resubmitting it
        clear_form: function(form, skipElementNames) {
            var elemList = form.elements;
            var elem;
            var elemType;

            for( var i = 0; i < elemList.length ; i++ ) {
                elem = elemList[i];
                if ( typeof(elem.type) == 'undefined' ) {
                    continue;
                }

                if ( typeof(elem.type) != 'undefined' && typeof(skipElementNames) != 'undefined'
                        && SUGAR.util.arrayIndexOf(skipElementNames, elem.name) != -1 )
                {
                    continue;
                }

                elemType = elem.type.toLowerCase();

                if ( elemType == 'text' || elemType == 'textarea' || elemType == 'password' ) {
                    elem.value = '';
                } else if (elemType == 'select-one') {
                    // We have, what I hope, is a select box, time to unselect all options
                    var optionList = elem.options,
                        selectedIndex = 0;
                    for (var ii = 0; ii < optionList.length; ii++) {
                        if (optionList[ii].value == '') {
                            selectedIndex = ii;
                            break;
                        }
                    }
                    if (optionList.length > 0) {
                        optionList[selectedIndex].selected = "selected";
                    }
                } else if (elemType == 'select-multiple') {
                    var optionList = elem.options;
                    for ( var ii = 0 ; ii < optionList.length ; ii++ ) {
                        optionList[ii].selected = false;
                    }
                }
                else if ( elemType == 'radio' || elemType == 'checkbox' ) {
                    elem.checked = false;
                    elem.selected = false;
                }
                else if ( elemType == 'hidden' ) {
                    if (
                        // For bean selection
                        elem.name.indexOf("_id") != -1
                        // For custom fields
                        || elem.name.indexOf("_c") != -1
                        // For advanced fields, like team collection, or datetime fields
                        || elem.name.indexOf("_advanced") != -1
                        )
                    {
                        elem.value = '';
                    }
                }
            }

            // If there are any collections
            if (typeof(collection) !== 'undefined')
            {
                // Loop through all the collections on the page and run clean_up()
                for (key in collection)
                {
                    // Clean up only removes blank fields, if any
                    collection[key].clean_up();
                }
            }
            //-- Edit by Kelvin Thang - date: 05/01/2018 - fix remove Layout Options in search when clear search
            SUGAR.savedViews.clearColumns = false;
        }
    };
}();


