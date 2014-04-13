define([
    'module',
    'backbone-loader',
    'app/models/desire',
], function(module, Backbone, Desire) {

    var Game = Backbone.RelationalModel.extend({
        urlRoot: module.config().urlRoot,
        relations: [
            {
                type: Backbone.HasOne,
                key: 'desire',
                relatedModel: Desire,
                reverseRelation: {
			key: 'game',
			includeInJSON: 'id',
                        type: Backbone.HasOne,
		}
            }
        ],
        createDesire: function(user) {
            var desire = new Desire({owner: user, game: this});
            desire.save();
            this.set('desire', desire);
            user.notifyCreateDesire();
        },
        removeDesire: function() {
            var desire = this.get('desire');
            this.set('desire', false);
            desire.destroy();
        },
        takeDesire: function(user) {
            var desire = this.get('desire');
            desire.set('owner', user);
            desire.save();
            user.notifyCreateDesire();
        },
        leaveDesire: function(user) {
            var desire = this.get('desire');
            if (desire.get('owner').id == user.id) {
                desire.set('owner', null);
                user.notifyRemoveDesire();
            } else {
                desire.removeJoin(user);
            }
            if (!desire.get('joins').length) {
                this.removeDesire();
            } else {
                desire.save();
            }
        }
    });

    return Game;
});
