$(function($) {

    $.ventoonirico = {};
    
    $.ventoonirico.user = null;

    $.ventoonirico.Game = Backbone.Model.extend({
        urlRoot: '/api/games'
//        idAttribute: 'id',
//        relations: [
//        {
//            type: Backbone.HasOne,
//            key: 'desire',
//            relatedModel: '$.ventoonirico.Desire',
//            reverseRelation: {
//                key: 'game',
//                includeInJSON: 'id'
//            }
//        }
//        ]
    });

    $.ventoonirico.GameCollection = Backbone.Collection.extend({
        url: '/api/games',
        model: $.ventoonirico.Game
    });

    $.ventoonirico.User = Backbone.Model.extend({
        urlRoot: '/api/users'
//        idAttribute: 'id',
//        relations: [
//        {
//            type: Backbone.HasMany,
//            key: 'desires',
//            relatedModel: '$.ventoonirico.Desire',
//            reverseRelation: {
//                key: 'owner',
//                includeInJSON: 'id'
//            }
//        }
//        ]
    });
    
    $.ventoonirico.CurrentUser = $.ventoonirico.User.extend({
        url: '/api/user'
    });

    $.ventoonirico.GameCountView = Backbone.View.extend({
        initialize: function() {
            this.listenTo(this.model, 'sync', this.render);
        },
        template: _.template($('#game-count').html()),
        render: function() {
            this.$el.html(this.template({count: this.model.length}));
            return this;
        },
    });

    $.ventoonirico.GameListView = Backbone.View.extend({
        initialize: function() {
            this.listenTo(this.model, 'sync', this.render);
        },
        template: _.template($('#game-list').html()),
        render: function() {
            this.$el.html(this.template(this.model));
            this.model.forEach(this.renderGame);
            return this;
        },
        renderGame: function(game) {
            var gameView = new $.ventoonirico.GameView({
                model: game
            });
            this.$('table.game-list').prepend(gameView.render().el);
        }
    });

    $.ventoonirico.GameView = Backbone.View.extend({
        tagName: 'tr',
        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
        },
        template: _.template($('#game').html()),
        render: function() {
            this.el = this.template({game: this.model.toJSON()});
            return this;
        }
    });
    
    $.ventoonirico.CurrentUserView = Backbone.View.extend({
        tagName: 'span',
        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
        },
        template: _.template($('#current-user').html()),
        render: function() {
    
            this.$el.html(this.template({user: this.model.toJSON()}));
            console.log(this.el);
            return this;
        }
    });

    $.ventoonirico.IndexView = Backbone.View.extend({
        el: $("#app"),
        template: _.template($('#games').html()),
        events: {
        },
        initialize: function() {
            this.render();
//            this.loadCurrentUser();
        },
        render: function() {
            this.$el.html(this.template({}));

            var gameCollection = new $.ventoonirico.GameCollection();

            var gameListView = new $.ventoonirico.GameListView({'model': gameCollection});
            var gameCountView = new $.ventoonirico.GameCountView({'model': gameCollection});
            var currentUserView = new $.ventoonirico.CurrentUserView({'model': $.ventoonirico.user});


            this.$("#game-list").append(gameListView.el);
            this.$("#game-count").append(gameCountView.el);
            this.$("#current-user").append(currentUserView.el);
            
            gameCollection.fetch();
            $.ventoonirico.user.fetch();
        },
        loadCurrentUser: function() {
            $.ajax({
                url: '/api/user',
                success: function(data) {
                    if (data) {
                        $.ventoonirico.user = new $.ventoonirico.User(data);
                    } else {
                        $.ventoonirico.user = null;
                    }
                }
            });

        }
    });

//    $.ventoonirico.app = new $.ventoonirico.AppView;
    $.ventoonirico.user = null;

    $.ventoonirico.Router = Backbone.Router.extend({
        routes: {
            "": "index"
        },
        initialize: function() {
            $.ventoonirico.user = new $.ventoonirico.CurrentUser();
        },
        index: function() {
            var indexView = new $.ventoonirico.IndexView({});
        }
    });

    $.ventoonirico.app = null;

    $.ventoonirico.bootstrap = function() {
        
        $.ventoonirico.app = new $.ventoonirico.Router();
        Backbone.history.start({
            pushState: true
        });
    };

    $.ventoonirico.bootstrap();

}(jQuery));
