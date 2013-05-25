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

$.ventoonirico.User = Backbone.RelationalModel.extend({
    urlRoot: '/api/users',
    idAttribute: 'id'
});

$.ventoonirico.DesireView = Backbone.View.extend({
    tagName: 'div',
    className: 'desire_view',
    initialize: function(){
        _.bindAll(this, 'render');
        this.model.desire.bind('change', this.render);
        this.render();
    },
    events: {
        "click .create-desire":     "createDesire"
    },
    render: function() {
        if ( this.model.appUser.get('id')) {
            return $(this.el).html(
                _.template(
                    $('#game-status-logged').html(),
                    this.model
                )
            );
        } else {
            return $(this.el).html(
                _.template(
                    $('#game-status-unlogged').html(),
                    this.model
                )
            );
        }
    },
    createDesire: function() {
        var desire = this.model.desire;
        var el = this.el;
        desire.save({}, {
            success: function (desire) {
                $(el).html(_.template($("#user_image").html(),{}));   
            }
        });
        return false;
    }
    
});

$.ventoonirico.AppView = Backbone.View.extend({
    el: $("body"),
    initialize: function(){
    },        
    events: {
        "click div.game-status":    "showStatus"        
    },

    showStatus: function(el) {
        var id = $(el.srcElement).parents('tr').attr('id').substr(5);
        var desire = new $.ventoonirico.Desire({
            game_id: id
        });
        var appUser;
        var desireView;
        $.ajax({
            url: '/api/user',
            dataType: 'json',
            success: function(data) {
                appUser = new $.ventoonirico.User(data);
                desireView = new $.ventoonirico.DesireView({
                el: el.srcElement,
                model: {
                    desire: desire,
                    appUser: appUser
                }});
            }
            
        });
        
        
        
    }

});

(function ($) {
    var appview = new $.ventoonirico.AppView();
})(jQuery);

	
