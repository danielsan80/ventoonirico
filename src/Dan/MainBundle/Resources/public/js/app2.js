$(function($) {

    $.ventoonirico = {};

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
            console.log(this.model.toJSON());
            this.el = this.template({game: this.model.toJSON()});
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
            
            var gameListView = new $.ventoonirico.GameListView({'model':gameCollection});
            var gameCountView = new $.ventoonirico.GameCountView({'model':gameCollection});
            
            gameCollection.fetch();
            
            this.$("#game-list").append(gameListView.render().el);
            this.$("#game-count").append(gameCountView.render().el);
        },
        
    });

//    $.ventoonirico.app = new $.ventoonirico.AppView;
    
    $.ventoonirico.Router = Backbone.Router.extend({
        routes: {
            "":"index"
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
