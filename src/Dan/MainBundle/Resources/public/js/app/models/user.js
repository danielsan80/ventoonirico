define([
    'backbone-loader',
    'app/util/prefix',
], function(Backbone, prefix){
    var User = Backbone.RelationalModel.extend({
        urlRoot: prefix + '/api/users',
        notifyRemoveDesire: function() {
            this.set('desires_count', this.get('desires_count')-1);
        },
        notifyCreateDesire: function() {
            this.set('desires_count', this.get('desires_count')+1);
        }
    });
    
    return User;
});
