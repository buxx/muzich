/*
*   jQuery tagbox
*   -------------
*   Released under the MIT, BSD, and GPL Licenses.
*   Copyright 2011 Daniel Stocks
*
*   Dependencies:
*   ------------
*   jquery.autoGrowInput.js
*  
*/

(function($) {
    
    function TagBox(input, options) {

        var self = this;
        
        self.options = options
        self.delimit_key = 188
        self.delimit_expr = /\s*,\s*/

        if(options.delimit_by_space) {
            self.delimit_key = 32 
            self.delimit_expr = /\s+/
        }

        var values = options.tag_values;
 
        var val = input.val();
        var tags = []
        if(val) { 
            tags = input.val().split(self.delimit_expr);
        }
        self.input = input
        self.tagInput = $('<input>', {
            'type' : 'text',
            'keydown' : function(e) {
                if(e.keyCode == 13 || e.keyCode == self.delimit_key ) {
                    $(this).trigger("selectTag");
                    e.preventDefault();
                }
            },
            'blur' : function(e) {
                $(this).val("");
            }
        });
        
        self.tagInput.bind("selectTag", function() {
            if(!$(this).val()) {
                return;
            }
            self.addTag($(this).val(), findKeyWithValue(values, $(this).val()), options.form_name);
            $(this).val("");
        });
        
        self.tagbox = $('<ul>', {
            "class" : "tagbox",
            click : function(e) {
                self.tagInput.focus();
            }
        });

        self.tags = []
        
        input.after(self.tagbox).hide();

        self.inputHolder = $('<li class="input">');
        self.tagbox.append(self.inputHolder);
        self.inputHolder.append(self.tagInput);
        self.tagInput.autoGrowInput();
        
        for(tag in tags) {
            self.addTag(tags[tag], findKeyWithValue(values, tags[tag]), options.form_name);
        }
        
        if (options.tag_init.length)
        {
          for(tagid in options.tag_init) {
              self.addTag(values[tagid], tagid, options.form_name);
          }
        }
    }
    
    TagBox.prototype = {
        
        addTag : function(label, id, form_name) {
            
            var self = this;
            var tag = $('<li class="tag">' + $('<div>').text(label).remove().html() + '</li>');
            
            this.tags.push(label);

            tag.append($('<a>', {
                "href" : "#",
                "class": "close",
                "text": "close",
                click: function(e) {
                    e.preventDefault();
                    var index = self.tagbox.find("li").index($(this).parent());
                    self.removeTag(index);
                }
            })).append($('<input>', {
              'type'   : 'checkbox',
              'style'  : 'display: none;',
              'value'  : id,
              'name'   : form_name+'[tags]['+id+']',
              'id'     : form_name+'_tags_'+id,
              'checked': 'checked'
            }));
            
            // <input type="checkbox" value="6373" name="group[tags][6373]" id="group_tags_6373">
            
            self.inputHolder.before(tag);
            self.updateInput();
        },
        removeTag : function(index) {
            
            this.tagbox.find("li").eq(index).remove();
            this.tags.splice(index, 1);
            this.updateInput();
        },
        updateInput : function() {
            
            var tags;
            if(this.options.delimit_by_space) {
                tags = this.tags.join(" ");
            } else {
                tags = this.tags.join(",");
            }
            this.input.val(tags);
        }
    }
    
    $.fn.tagBox = function(options) {

        var defaults = {
            delimit_by_space : false 
        }
        var options = $.extend(defaults, options);
        return this.each(function() {
            
            var input = $(this);
            var tagbox = new TagBox(input, options);
        });
    }
})(jQuery);

