Router
    .add('explore\/(.*)', function(){
        App.controller.exploreWithId(arguments);
    })
    .add('explore', function(){
        App.controller.explore();
    })
    .check().listen();