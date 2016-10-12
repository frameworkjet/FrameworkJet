// Set the App object
var App = {
    controller: {},
    config: {
        interval: 10000,
        listener: null
    },
    ajaxCalls: 0,
    currentLang: null,
    currentLangCode: 0,
    defaultLang: 'en_UK',
    defaultLangCode: 1,
    pageConfig: {} // this will contain different config depends on the page where we  are!
};

// System configurations and global listeners
$(function() {
    // Initial configurations
    Router.config({
        mode: 'history'
    });
    dataManager.config({
        main: 'http://www.frameworkjet.com',
        api: 'http://api.frameworkjet.com'
    });
    App.currentLang = dataManager.getCookie('lang') ? dataManager.getCookie('lang') : App.defaultLang;
    App.currentLangCode = dataManager.getCookie('lang_code') ? dataManager.getCookie('lang_code') : App.defaultLangCode;

    // Links
    $("a.pg-link").on('click', function(e){
        e.preventDefault();

        var href = $(this).attr('href');

        // we don't need to redirect # or empty url
        if (href == "#" || href == "") {
            return;
        }

        Router.navigate(href);
    });

    // Buttons and links
    $(".pg-action").on('click', function(e){
        e.preventDefault();

        // Call the corresponding controller
        var data = $(this).attr('data').split('-');
        var action = data[0];
        data.shift();
        for (i in data) {
            action += data[i].charAt(0).toUpperCase() + data[i].slice(1);
        }
        App.controller[action]();
    });

    //Switch lang
    $("select.lang-switcher").on('change', function(e){
        window.location = '/'+Router.getFragment()+'?lang='+this.value;
    });

    // Periodical listener
    var fn = function() {
        console.log('Checked!');
    };
    clearInterval(App.config.listener);
    App.config.listener = setInterval(fn, App.config.interval);
});