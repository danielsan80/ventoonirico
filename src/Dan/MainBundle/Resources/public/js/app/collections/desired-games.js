define([
    'backbone-loader',
    'app/models/game',
    'app/util/prefix',
], function(Backbone, Game, prefix){
    var DesiredGameCollection = Backbone.Collection.extend({
        url: prefix + '/api/games?filter=desired',
        model: Game
    });
    return DesiredGameCollection;
});
