Router
    .add('explore\/(.*)', function(){
        App.controller.exploreExploreId(arguments);
    })
    .add('explore', function(){
        App.controller.exploreExplore();
    })
    .add('contact-us', function(){
        App.controller.pageContactUs();
    })
    .check().listen();