//$.ventoonirico.GameCollection = Backbone.Collection.extend({
//    url: '/api/games',
//    model: $.ventoonirico.Game
//});
//
//$.ventoonirico.Game = Backbone.RelationalModel.extend({
//    urlRoot: '/api/games',
//    idAttribute: 'id',
//    relations: [{
//        type: Backbone.HasOne,
//        key: 'desire',
//        relatedModel: '$.ventoonirico.Desire',
//        reverseRelation: {
//            key: 'game',
//            includeInJSON: 'id'
//        }
//    }]
//});
$.ventoonirico = {};

$.ventoonirico.Desire = Backbone.RelationalModel.extend({
    urlRoot: '/api/desires',
    idAttribute: 'game_id',
    relations: [
//        {
//        type: Backbone.HasOne,
//        key: 'user',
//        relatedModel: '$.ventoonirico.User'
//    },{
//        type: Backbone.HasMany,
//        key: 'joinedUsers',
//        relatedModel: '$.ventoonirico.User'
//    }
]
});

$.ventoonirico.AppUser = Backbone.RelationalModel.extend({
    urlRoot: '/api/user',
    idAttribute: 'id',
    relations: []
});

$.ventoonirico.User = Backbone.RelationalModel.extend({
    urlRoot: '/api/users',
    idAttribute: 'id',
    relations: []
});

$.ventoonirico.DesireView = Backbone.View.extend({
    tagName: 'div',
    className: 'desire_view',
    initialize: function(){
        _.bindAll(this, 'render');
        this.model.bind('change', this.render);
        this.render();
    },
    events: {
    },
    render: function() {
        return $(this.el).html(_.template($('#game-status').html(),{desire: this.model.toJSON()}));
    }
});

$.ventoonirico.AppView = Backbone.View.extend({
    el: $("body"),
    initialize: function(){
    },        
    events: {
        "click div.game-status":    "showStatus",
        "click .create-desire":     "createDesire"
    },

    showStatus: function(el) {
        var id = $(el.srcElement).parents('tr').attr('id').substr(5);
        var desire = new $.ventoonirico.Desire({
            game_id: id
        });
        var appUser = new $.ventoonirico.AppUser({
        });
        appUser.fetch();
        var desireView = new $.ventoonirico.DesireView({el: el.srcElement, model: desire});
        
    },
    createDesire: function(el) {
        var id = $(el.srcElement).parents('tr').attr('id').substr(5);
        var desire = new Desire({
            game_id: id
        });
        desire.save({}, {
            success: function (desire) {
                var actions = $('table.games-collection tr#game_'+(desire.get('game_id'))+' td .actions');
                actions.html(_.template($("#user_image").html(),{}));   
            }
        });
        return false;
    }

});

(function ($) {
    var appview = new $.ventoonirico.AppView();
})(jQuery);

	
