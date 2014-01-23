define([
    'backbone-loader',
    'app/util/prefix',
], function(Backbone, prefix){
    var User = Backbone.RelationalModel.extend({
        urlRoot: prefix + '/api/users',
        desiresLimit: 3,
        notifyRemoveDesire: function() {
            this.set('desires_count', this.get('desires_count')-1);
        },
        notifyCreateDesire: function() {
            this.set('desires_count', this.get('desires_count')+1);
        },
        canCreateDesire: function() {
            return this.get('desires_count') < this.desiresLimit;
        }
        
    });
    
    return User;
});
