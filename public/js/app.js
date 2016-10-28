// Set the App object
var App = {
    controller: {},
    listener: null,
    lang: null,
    langCode: 0,
    pageConfig: {} // this will contain different config depends on the page where we  are!
};

// System configurations and global listeners
$(function() {
    // Initial configurations
    Router.config({
        mode: 'history'
    });
    dataManager.config({
        main: config['data_manager']['main'],
        mapper: config['data_manager']['mapper'],
        api: config['data_manager']['api']
    });
    App.lang = dataManager.getCookie('lang') ? dataManager.getCookie('lang') : config['app']['default_lang'];
    App.langCode = dataManager.getCookie('lang_code') ? dataManager.getCookie('lang_code') : config['app']['default_lang_code'];

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
        if (dataManager.getCookie('is_logged') == 1) {
            console.log('User logged!');
        }
    };
    clearInterval(App.listener);
    App.listener = setInterval(fn, config['app']['interval']);
});