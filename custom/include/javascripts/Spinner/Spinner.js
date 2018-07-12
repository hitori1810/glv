/*
*	Spinner.js
*	Author: Hieu Nguyen
*	Date: 2015-06-10
*	Purpose: To handle the loading spinner effect
*	Requires: Spinner.css
*   Modify : Trung Nguyen
*/

function Spinner () {

    this.init = function() {
        if($('#spinner')[0] == null) {
            var html = '<div id="spinner"><div id="spinner-background"></div><div id="slide-wrapper"><div id="slide"><div id="loading-container"><div id="loading"></div><div id="message"></div></div></div><div id="align"></div></div></div>';
            $('body').append(html);
        }
    }

    this.init();

    this.show = function(message) {
        $('#spinner').find('#message').html(message);
        $('#spinner').show();
    }

    this.hide = function() {
        $('#spinner').hide();
    }

    this.changeMessage = function(message) {
        $('#spinner').find('#message').html(message);
    }

    this.remove = function() {
        $('#spinner').remove();
    }
}