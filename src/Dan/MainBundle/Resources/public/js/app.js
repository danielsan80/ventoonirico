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
                success: function (model) {
                   console.log(model);
//                    var a = $('tr#transaction_'+model.id+' a.isTransfer');
//                    if (model.attributes.isTransfer) {
//                        a.replaceWith(_.template($("#isTransfer_true").html(),{}));                    
//                    } else {
//                        a.replaceWith(_.template($("#isTransfer_false").html(),{}));                    
//                    }
                }
            });
            return false;
        }
        
    });
    
    var appview = new AppView;

})(jQuery);

	
