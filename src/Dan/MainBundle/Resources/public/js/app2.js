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
        url: '/api/user',
        isLogged: function() {
            return this.get('id');
        }
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
    
    $.ventoonirico.GameStatusView = Backbone.View.extend({
        initialize: function() {
            this.listenTo(this.model.game, 'change', this.render);
            this.listenTo(this.model.user, 'change', this.render);
            this.render()
        },
        render: function() {
            var desire = this.model.game.get('desire');
            if (!this.model.user.isLogged()) {
                if (!desire) {
                    this.template = _.template($('#game-status-nouser-nodesire').html()),
                    this.$el.html(this.template(this.model));
                    return this;
                }
                if (desire) {
                    this.template = _.template($('#game-status-nouser-desire').html()),
                    this.$el.html(this.template(this.model));
                    return this;
                }
            }
            if (this.model.user.isLogged()) {
                if (!desire) {
                    this.template = _.template($('#game-status-user-nodesire').html()),
                    this.$el.html(this.template(this.model));
                    return this;
                }
                if (desire) {
                    if (desire.get('owner')==this.model.user) {
                        this.template = _.template($('#game-status-user-desire-owner').html()),
                        this.$el.html(this.template(this.model));
                        return this;
                    } else {
                        this.template = _.template($('#game-status-user-desire-noowner').html()),
                        this.$el.html(this.template(this.model));
                        return this;
                    }
                }
            }
        }
    });

    $.ventoonirico.GameView = Backbone.View.extend({
        tagName: 'tr',
        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
        },
        template: _.template($('#game').html()),
        render: function() {
            this.setElement(this.template({game: this.model.toJSON()}));
            var gameStatusView = new $.ventoonirico.GameStatusView({
                el: this.$el.find(".game-status"),
                model: {
                    game: this.model,
                    user: $.ventoonirico.user
                }
            });
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
    });

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
