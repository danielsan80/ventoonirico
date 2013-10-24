define([
    'backbone-loader',
    'app/models/game',
    'app/util/prefix',
], function(Backbone, Game, prefix){
    var GameCollection = Backbone.Collection.extend({
        url: prefix + '/api/games',
        model: Game
    });
    return GameCollection;
});
