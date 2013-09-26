$(function($) {

    $.ventoonirico = {};

    $.ventoonirico.Game = Backbone.RelationalModel.extend({
        urlRoot: '/api/games',
        idAttribute: 'id',
        relations: [
        {
            type: Backbone.HasOne,
            key: 'desire',
            relatedModel: '$.ventoonirico.Desire',
            reverseRelation: {
                key: 'game',
                includeInJSON: 'id'
            }
        }
        ]
    });

    $.ventoonirico.Desire = Backbone.RelationalModel.extend({
        urlRoot: '/api/desires',
        idAttribute: 'id'
    });

    $.ventoonirico.User = Backbone.RelationalModel.extend({
        urlRoot: '/api/users',
        idAttribute: 'id',
        relations: [
        {
            type: Backbone.HasMany,
            key: 'desires',
            relatedModel: '$.ventoonirico.Desire',
            reverseRelation: {
                key: 'owner',
                includeInJSON: 'id'
            }
        }
        ]
    });

    $.ventoonirico.GameCollection = Backbone.Collection.extend({
        url: '/api/games',
        model: $.ventoonirico.Game
    });

    $.ventoonirico.GameListView = Backbone.View.extend({
        initialize: function(){
            //_.bindAll(this, 'render', 'renderGame');
            this.model.bind('reset', this.render);
            this.model.bind('change', this.render);
        },
        template: _.template($('#game-list').html()),
        render: function() {
            $(this.el).html(this.template(this.model));
            this.model.forEach(this.renderGame);
            return $(this.el).html();
        },
        renderGame: function(game){
            
            var gameView = $.ventoonirico.GameView({
                model: game
            });
            this.$('table.game-list').prepend($gameView.render());
        }
    });

    $.ventoonirico.GameView = Backbone.View.extend({
        tagName: 'div',
        className: 'game-view',
        initialize: function(){
            _.bindAll(this, 'render');
            this.model.bind('change', this.render);
        },
        template: _.template($('#game').html()),
        render: function() {
            return $(this.el).html(this.template(this.model.toJSON()));
        }
    });
    
    $.ventoonirico.Router = Backbone.Router.extend({
        routes: {
            "":"showGameList"
        },
        showGameList: function() {
            //var mygame = new $.ventoonirico.Game({id:1,name:"Pappa"});
            var gameCollection = new $.ventoonirico.GameCollection();
            var gameListView = new $.ventoonirico.GameListView({
                el: $('.content'), 
                model: gameCollection
            });
            gameCollection.fetch();
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


