$(function($) {

    $.ventoonirico = {};
    
    $.ventoonirico.user = null;
    $.ventoonirico.urlPrefix = window.location.pathname.substring(1);

    $.ventoonirico.Game = Backbone.RelationalModel.extend({
        urlRoot: $.ventoonirico.urlPrefix + '/api/games',
        relations: [
            {
                type: Backbone.HasOne,
                key: 'desire',
                relatedModel: '$.ventoonirico.Desire',
                includeInJSON: 'id'
            }
        ],
        createDesire: function(user) {
            var desire = new $.ventoonirico.Desire({owner: user, game: this});
            desire.save();
            this.set('desire', desire);
        },
        removeDesire: function() {
            var desire = this.get('desire');
            this.set('desire', false);
            desire.destroy();
        },
    });

    $.ventoonirico.GameCollection = Backbone.Collection.extend({
        url: $.ventoonirico.urlPrefix + '/api/games',
        model: $.ventoonirico.Game
    });
    
    $.ventoonirico.Desire = Backbone.RelationalModel.extend({
        urlRoot: $.ventoonirico.urlPrefix + '/api/desires',
        relations: [
            {
                type: Backbone.HasOne,
                key: 'game',
                relatedModel: '$.ventoonirico.Game',
                includeInJSON: 'id'
            },
            {
                type: Backbone.HasOne,
                key: 'owner',
                relatedModel: '$.ventoonirico.User',
                includeInJSON: 'id'
            },
            {
                type: Backbone.HasMany,
                key: 'joins',
                relatedModel: '$.ventoonirico.Join',
                collectionType: '$.ventoonirico.JoinCollection'
            }
        ],
        addJoin: function(user) {
            var join = new $.ventoonirico.Join({user: user, desire: this});
            join.save();
            this.get('joins').add(join);
        },
        removeJoin: function(user) {
            var joins = this.get('joins');
            var i=0;
            while (i<joins.length) {
                var join = joins.at(i);
                if (join.get('user').id == user.id) {
                    join.destroy();
                    joins.remove(join);
                    this.set('joins',joins);
                    break;
                }
                i++;
            }
        }
    });
    
    $.ventoonirico.DesireCollection = Backbone.Collection.extend({
        url: $.ventoonirico.urlPrefix + '/api/desires',
        model: $.ventoonirico.Desire
    });

    $.ventoonirico.Join = Backbone.RelationalModel.extend({
        urlRoot: $.ventoonirico.urlPrefix + '/api/joins',
        relations: [
            {
                type: Backbone.HasOne,
                key: 'user',
                relatedModel: '$.ventoonirico.User',
                includeInJSON: 'id'
            },
            {
                type: Backbone.HasOne,
                key: 'desire',
                relatedModel: '$.ventoonirico.Desire',
                includeInJSON: 'id'
            }
        ]
    });
 
    $.ventoonirico.JoinCollection = Backbone.Collection.extend({
        model: $.ventoonirico.Join
    });
    
    $.ventoonirico.User = Backbone.RelationalModel.extend({
        urlRoot: $.ventoonirico.urlPrefix + '/api/users'
    });
    
    $.ventoonirico.CurrentUser = $.ventoonirico.User.extend({
        url: $.ventoonirico.urlPrefix + '/api/user',
        isLogged: function() {
            return this.get('id');
        }
    });

    $.ventoonirico.UserCollection = Backbone.Collection.extend({
        model: $.ventoonirico.User
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
            this.$el.parents().find(".loading").hide();
            this.$el.html(this.template(this.model));
            this.model.forEach(this.renderGame);
            return this;
        },
        renderGame: function(game) {
            var gameView = new $.ventoonirico.GameView({
                model: game
            });
            this.$('table.game-list').append(gameView.render().el);
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
    
    $.ventoonirico.GameStatusView = Backbone.View.extend({
        initialize: function() {
            this.listenTo(this.model.game, 'change', this.render);
            this.listenTo(this.model.user, 'change', this.render);
            this.render()
        },
        events: {
            "click .desire-create": "createDesire",
            "click .desire-remove": "removeDesire",
        },
        render: function() {
            var desire = this.model.game.get('desire');
            if (!desire) {
                if (this.model.user.isLogged()) {
                    this.template = _.template($('#game-status-user-nodesire').html()),
                    this.$el.html(this.template(this.model));
                    return this;
                } else {
                    this.template = _.template($('#game-status-nouser-nodesire').html()),
                    this.$el.html(this.template({}));
                    return this;
                }
            }
            
            var desireView = new $.ventoonirico.DesireView({
                model: {
                    user: this.model.user,
                    game: this.model.game,
                    desire: desire
                }                    
            });
            this.$el.html(desireView.el);
        },
        createDesire: function() {
            this.model.game.createDesire(this.model.user);
            return false;
        },        
        removeDesire: function() {
            this.model.game.removeDesire();
            return false;
        }
    });
    
    $.ventoonirico.DesireView = Backbone.View.extend({
        initialize: function() {
//            this.listenTo(this.model.desire, 'change', this.render);
            this.listenTo(this.model.desire, 'add:joins', this.render);
            this.listenTo(this.model.desire, 'remove:joins', this.render);
            this.setElement($('#game-status-desire').html());
            this.render();
        },
        events: {
            "click .join-add": "addJoin",
            "click .join-remove": "removeJoin"
        },
        render: function() {
            var user = this.model.user;
            var game = this.model.game;
            var desire = this.model.game.get('desire');
            
            this.$el.html(_.template($('#game-status-desire-player_main').html(), {user: user, owner: desire.get('owner')}));

            var joins = desire.get('joins');
            var users = new $.ventoonirico.UserCollection([desire.get('owner')]);
            
            for(var i=0; i<game.get('maxPlayers')-1; i++) {
                var join  = joins.at(i);
                if ( join) {
                    var guest = users.get(join.get('user')) != undefined;
                    users.push(join.get('user'));
                    this.$el.append(_.template($('#game-status-desire-player_joined').html(), {user: user, join: join, guest:guest}));
                } else {
                    this.$el.append(_.template($('#game-status-desire-player_nobody').html(), {user: user}));
                }
            }
    
            return this;
        },
        addJoin: function() {
            this.model.desire.addJoin(this.model.user);
            return false;
        },
        removeJoin: function() {
            this.model.desire.removeJoin(this.model.user);
            return false;
        }
    });
    
    $.ventoonirico.DesiredGameListView = Backbone.View.extend({
        initialize: function() {
            this.listenTo(this.model, 'sync', this.render);
        },
        template: _.template($('#desired-game-list').html()),
        render: function() {
            var desiredGames = this.model.filter(function(game){
                return (game.get('desire') != null)
            });
            desiredGames = _.sortBy(desiredGames, function(game) {
                return game.get("desire").get('created_at');
            });
            this.$el.parents().find(".loading").hide();
            this.$el.html(this.template(desiredGames));
            desiredGames.forEach(this.renderGame);
            return this;
        },
        renderGame: function(game, index, games) {
            var desiredGameView = new $.ventoonirico.DesiredGameView({
                model: game
            });
            var el = desiredGameView.render().el;
            this.$('div.desired-game-list').append(el);
            
            if (index==games.length-1) {
                this.$('.masonry').masonry({
                    itemSelector: '.item',
                    "gutter": 10
                });
                setTimeout(function() {
                    this.$('.masonry').masonry('layout');
                },5000);
            }
        }
    });
    
    $.ventoonirico.DesiredGameView = Backbone.View.extend({
        tagName: 'div',
        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
        },
        template: _.template($('#desired-game').html()),
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

            var gameCountView = new $.ventoonirico.GameCountView({'model': gameCollection});
            var desiredGameListView = new $.ventoonirico.DesiredGameListView({'model': gameCollection});
            var gameListView = new $.ventoonirico.GameListView({'model': gameCollection});

            this.$("#game-list").append(gameListView.el);
            this.$("#desired-game-list").append(desiredGameListView.el);
            this.$("#game-count").append(gameCountView.el);
            
            gameCollection.fetch();
            $.ventoonirico.user.fetch();
        },
    });

    $.ventoonirico.user = null;

    $.ventoonirico.Router = Backbone.Router.extend({
        routes: function(){
            var routes = new Array();
            routes[$.ventoonirico.urlPrefix + ""] = "index";
            return routes;
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
