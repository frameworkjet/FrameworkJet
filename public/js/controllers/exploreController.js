$(function() {
    // Explore page
    App.controller.exploreExplore = function()
    {
        console.log('Explore controller');

        // Example AJAX call
        dataManager.connect('main').ajax('/explore/2', 'GET', function(data, code){
            console.log('Yes! Ajax successful.');

            // HTTP code of the response
            //console.log(code);

            // Example usage of handlebars
            var template = Handlebars.templates.custom;
            var input_data = {}; // this can be "data"
            $('#ajax-content').html(template($.extend(App[App.currentLang], input_data)));
        });
    };

    // Explore page with id
    App.controller.exploreExploreId = function(arguments)
    {
        console.log('Explore controller with id');

        // Example usage of arguments
        //console.log(arguments);
    };
});