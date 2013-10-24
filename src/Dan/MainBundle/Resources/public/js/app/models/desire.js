define([
    'backbone-loader',
    'app/util/prefix',
    'app/models/game',
    'app/models/user',
    'app/models/join',
    'app/collections/joins',
], function(Backbone, prefix, Game, User, Join, JoinCollection) {

    var Desire = Backbone.RelationalModel.extend({
        urlRoot: prefix + '/api/desires',
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
        }
    });

    return Desire;
});
