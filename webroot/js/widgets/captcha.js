/**
 * Captcha widget
 *
 * @author Flavius
 * @version 1.1
 */
if(typeof Widgets === 'undefined') var Widgets = {};
Widgets.captcha = function() { 'use strict';
    var store = {

    // render each captcha
    }, render = function() {
        // go through each captcha widget
        $('div.captcha-widget').each(function() {
            let e = $(this).find('div');
            grecaptcha.render(e.get(0), e.data());
        });

    // init
    }, __construct = function() {
        Frontend.load('https://www.google.com/recaptcha/api.js?onload=GoogleRecaptchaOnLoad&render=explicit');
    };

    // public, yay
    return {
        init: __construct,
        render: render
    };
}();

// onload wrapper
var GoogleRecaptchaOnLoad = function() {
    Widgets.captcha.render();
};

// init widget on ready
$(Widgets.captcha.init);
