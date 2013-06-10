$.ventoonirico = {};

$.ventoonirico.Game = Backbone.RelationalModel.extend({
    urlRoot: '/api/games',
    idAttribute: 'id',
    relations: [
    {
        type: Backbone.HasOne,
        key: 'desire',
        relatedModel: '$.ventoonirico.Desire',
        autoFetch: true,
        reverseRelation: {
            key: 'game',
            includeInJSON: 'id'
        }
    }
    ]
});

$.ventoonirico.Desire = Backbone.RelationalModel.extend({
    urlRoot: '/api/desires',
    idAttribute: 'id',
    relations: [
    {
        type: Backbone.HasOne,
        key: 'game',
        relatedModel: '$.ventoonirico.Game'
    },
    {
        type: Backbone.HasOne,
        key: 'owner',
        relatedModel: '$.ventoonirico.User'
    },{
        type: Backbone.HasMany,
        key: 'joinedUsers',
        relatedModel: '$.ventoonirico.User'
    }
    ]
});

$.ventoonirico.User = Backbone.RelationalModel.extend({
    urlRoot: '/api/users',
    idAttribute: 'id'
});

$.ventoonirico.AppView = Backbone.View.extend({
    el: $("body"),
    initialize: function(){
        $.ventoonirico.appUser = new $.ventoonirico.User();
        $.ajax({
            url: '/api/user',
            dataType: 'json',
            success: function(data) {
                $.ventoonirico.appUser = new $.ventoonirico.User(data);
            }
            
        });
        
    },        
    events: {
        "click div.game-status":    "showStatus"        
    },

    showStatus: function(el) {
        var id = $(el.srcElement).parents('tr').attr('id').substr(5);
        var game = new $.ventoonirico.Game({
            id: id
        });
        console.log(
        game.fetch()
    );
        console.log(game);
        var desireView = new $.ventoonirico.DesireView({
            el: el.srcElement,
            model: game
        });
    }

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
        "click .create-desire":     "createDesire"
    },
    render: function() {
        if ( $.ventoonirico.appUser.get('id')) {
//            console.log(this.model.toJSON());
            return $(this.el).html(
                _.template(
                    $('#game-status-logged').html(),
                    this.model.toJSON()
                )
            );
        } else {
            return $(this.el).html(
                _.template(
                    $('#game-status-unlogged').html(),
                    this.model.toJSON()
                )
            );
        }
    },
    createDesire: function() {
        var desire = new $.ventoonirico.Desire({
            owner: $.ventoonirico.appUser,
            game: this.model
        });
//        desire.save({}, {
//            success: function (desire) {
//                $(this.el).html(_.template($("#user_image").html(),{user: desire.owner.toJSON()}));   
//            }
//        });
//        return false;
    }
    
});



(function ($) {
    var appview = new $.ventoonirico.AppView();
})(jQuery);

	
