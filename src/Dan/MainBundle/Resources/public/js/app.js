(function ($) {
    
    Request = Backbone.Model.extend({
        urlRoot: '/requests'
    });
    
    AppView = Backbone.View.extend({
        el: $("body"),
        initialize: function(){
        },        
        events: {
            "click .create-request":    "createRequest"
        },
        
        createRequest: function(el) {
            var id = $(el.srcElement).parents('tr').attr('id').substr(5);
            var request = new Request({
                game_id: id
            });
            request.save({}, {
                success: function (model) {
                    alert(model);
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

	
