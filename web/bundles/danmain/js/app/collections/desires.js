define([
    'backbone-loader',
    'app/models/desire',
    'app/util/prefix',
], function(Backbone, Desire, prefix){
    var DesireCollection = Backbone.Collection.extend({
        url: prefix + '/api/desires',
        model: Desire
    });
    return DesireCollection;
});
