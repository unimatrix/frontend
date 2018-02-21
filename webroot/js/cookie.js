/**
 * Cookie Policy
 * Will create the notice html for your cookie policy
 *
 * @author Flavius
 * @version 1.2
 */
Cookies.policy = function() { 'use strict';
    var store = {
        parent: $('body')

    // html5 sticky footer
    }, _build = function() {
        // do html
        var html = '<div class="cookie-policy">' +
            '<div>' +
                '<span>' + store.message + '</span>' +
                '<div>' +
                    '<a href="javascript:void(0);">' + store.accept + '</a>' +
                    '<a href="' + store.url + '" target="_blank" rel="nofollow">' + store.details + '</a>' +
                '</div>' +
            '</div>' +
        '</div>';

        // append to parent
        store.parent.append(html);

        // define policy and spacer
        var policy = $('div.cookie-policy', store.parent);

        // on i agree click
        $('div.cookie-policy > div > div > a:first-child', store.parent).on('click', function() {
            // hide element
            policy.fadeOut('fast', function() {
                $('body').removeAttr('style');
                $('body > footer').removeAttr('style');
            });

            // set cookie
            Cookies.set('cookie-accept', '1', {
                expires: 30,
                path: '/'
            });
        });

    // init
    }, __construct = function(cfg) {
        // set configuration
        store = $.extend({}, store, cfg);

        // build cookie if all conditions are met
        if(store.url && store.message && store.accept && store.details && Cookies.get('cookie-accept') !== '1')
            _build();
    };

    // public, yay
    return {
        init: __construct
    };
}();
