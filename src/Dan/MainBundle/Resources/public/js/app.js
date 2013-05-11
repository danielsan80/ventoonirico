(function ($) {
    
    Desire = Backbone.Model.extend({
        urlRoot: '/app_dev.php/desires'
    });
    
    AppView = Backbone.View.extend({
        el: $("body"),
        initialize: function(){
        },        
        events: {
            "click .create-desire":    "createDesire"
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
    
    var appview = new AppView;

})(jQuery);

	
