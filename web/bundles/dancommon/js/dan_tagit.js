(function($) {
    $.widget('ui.dan_tagit', {
        options: {
            addUrl: null,
            removeUrl: null
        },

        tagging: false,

        _getObjectId: function(tag) {
            var input = $(tag).find('input[type="hidden"]');
            var id = input.attr('name');
            
            id = id.replace('][]','');
            id = id.split('[');
            id = id.pop();
            return id;
        },

        _getTagName: function(tag) {
            var input = $(tag).find('input[type="hidden"]');
            var tagName = input.val();
            return tagName;
        },

        _applyTagit: function (ul) {
            // for handling static scoping inside callbacks
            var that = this;
            if (that.tagging){
                return;
            }
            that.tagging = true;

            if (ul.offset().top > ($(window).scrollTop()+1500)) {
                that.tagging = false;
                return;
            }
            var itemName = ul.attr('id').split('_');
            var id = itemName.pop();
            itemName = itemName.join('_');
            
            var config = {
                itemName: itemName,
                fieldName: id,
                allowSpaces: true,
                caseSensitive: false
            };
            
            if (that.options.addUrl) {
                config.onTagAdded = function(event, tag) {
                    var url = that.options.addUrl;
                    var id = that._getObjectId(tag);
                    var tagName = that._getTagName(tag);
                    url = url.replace('ID',id);
                    $.ajax({
                        url: url,
                        data: {'tag': tagName},
                        type: 'POST' 
                    });
                };
            }
            
            if (that.options.removeUrl) {
                config.onTagRemoved = function(event, tag) {
                    var url = that.options.removeUrl;
                    var id = that._getObjectId(tag);
                    var tagName = that._getTagName(tag);
                    url = url.replace('ID',id);
                    $.ajax({
                        url: url,
                        data: {'tag': tagName},
                        type: 'POST' 
                    });
                };
            }
            
            ul.tagit(config);
            ul.removeClass('ui-widget-content');
            ul.find('.tagit-new ul').bind('focus', function(){$(this).addClass('hover');});
            that.tagging = false;
        },
        _create: function() {
            // for handling static scoping inside callbacks
            var that = this;
            var i;
            $(window).scroll(function() {
                var uls = that.element.find("ul.dan_tagit:not(.tagit)")
                if (uls.length==0) {
                    $(window).unbind('scroll');
                };
                for (i=0; i<uls.length; i++) {
                    that._applyTagit(uls.eq(i));
                }
            });
            var uls = this.element.find("ul.dan_tagit:not(.tagit)");
            for (i=0; i<uls.length; i++) {
                this._applyTagit(uls.eq(i));
            }
        }
    });
})(jQuery);