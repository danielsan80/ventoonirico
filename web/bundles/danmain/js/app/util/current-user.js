define([
    'app/util/prefix',
    'app/models/user'
], function(prefix, User){
    var CurrentUser = User.extend({
        url: prefix + '/api/user',
        isLogged: function() {
            return this.get('id');
        }
    });
    
    return new CurrentUser();
});
