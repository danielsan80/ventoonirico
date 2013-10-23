define([
    'backbone-loader',
    'app/util/prefix',
], function(Backbone, prefix){
    var User = Backbone.RelationalModel.extend({
        urlRoot: prefix + '/api/users'
    });
    
    return User;
});
