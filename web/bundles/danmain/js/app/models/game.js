define([
    'backbone-loader',
    'app/util/prefix',
    'app/models/desire',
], function(Backbone, prefix, Desire) {

    var Game = Backbone.RelationalModel.extend({
        urlRoot: prefix + '/api/games',
        relations: [
            {
                type: Backbone.HasOne,
                key: 'desire',
                relatedModel: Desire,
            }
        ],
        createDesire: function(user) {
            var desire = new Desire({owner: user, game: this});
            desire.save();
            this.set('desire', desire);
        },
        removeDesire: function() {
            var desire = this.get('desire');
            this.set('desire', false);
            desire.destroy();
        },
    });

    return Game;
});
