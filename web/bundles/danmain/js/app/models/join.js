define([
    'backbone-loader',
    'app/util/prefix',
    'app/models/user',
    'app/models/desire',
], function(Backbone, prefix, User, Desire){
     var Join = Backbone.RelationalModel.extend({
        urlRoot: prefix + '/api/joins',
        relations: [
            {
                type: Backbone.HasOne,
                key: 'user',
                relatedModel: User
            },
            {
                type: Backbone.HasOne,
                key: 'desire',
                relatedModel: Desire
            }
        ]
    });
    
    return Join;
});
