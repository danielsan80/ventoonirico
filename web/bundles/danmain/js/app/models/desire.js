define([
    'module',
    'backbone-loader',
    'app/models/game',
    'app/models/user',
    'app/models/join',
    'app/collections/joins',
], function(module, Backbone, Game, User, Join, JoinCollection) {

    var Desire = Backbone.RelationalModel.extend({
        urlRoot: module.config().urlRoot.replace('__id__',''),
        relations: [
            {
                type: Backbone.HasOne,
                key: 'game',
                relatedModel: Game
            },
            {
                type: Backbone.HasOne,
                key: 'owner',
                relatedModel: User
            },
            {
                type: Backbone.HasMany,
                key: 'joins',
                relatedModel: Join,
                collectionType: JoinCollection
            }
        ],
        addJoin: function(user) {
            var join = new Join({user: user, desire: this});
            join.save();
            this.get('joins').add(join);
        },
        removeJoin: function(user) {
            var joins = this.get('joins');
            var i = 0;
            while (i < joins.length) {
                var join = joins.at(i);
                if (join.get('user').id == user.id) {
                    join.destroy();
                    joins.remove(join);
                    this.set('joins', joins);
                    break;
                }
                i++;
            }
            if (!joins.length) {
                this.get('game').removeDesire(this);
            }
        }
    });

    return Desire;
});
