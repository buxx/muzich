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
        
        // Ce tableau contiendra les tags déjà ajoutés
        if(typeof(tagsAddeds) === "undefined")
        {
          tagsAddeds = new Array();
        }
        // On permet de faire un tableau de tags ajoutés par formulaires
        // TODO: Par 'champ' ce serais mieux.
        tagsAddeds[options.form_name] = new Array();
        
        self.options = options
        self.delimit_key = 188
        self.delimit_expr = /\s*,\s*/

        if(options.delimit_by_space) {
            self.delimit_key = 32 
            self.delimit_expr = /\s+/
        }
 
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
        
        self.tagInput.bind("selectTag", function(event) {
          if(!$(this).val()) {
              return;
          }
          
          input = $(this);
          
          // Ici il faut faire un ajax q pour connaitre l'id, on a que le string
          // ajax loader gif
          $('#tag_loader_'+options.form_name).css('display', 'block');
          // On bloque le submit le temps de la validation du tag
          $('form[name="'+options.form_name+'"] input[type="submit"]').attr('disabled', 'disabled');
          $.getJSON('/app_dev.php/fr/search/tagid/'+$(this).val(), function(data) {
            if (isInteger(data))
            {
              self.addTag(input.val(), data, options.form_name, false);
              input.val("");
              $('#tag_loader_'+options.form_name).css('display', 'none');
              $('form[name="'+options.form_name+'"] input[type="submit"]').removeAttr('disabled');
            }
          });

          
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
        
        // On désactive l'ajout de tags basé sur le contenu du input
        // ona notre propre système (taginit)
        //for(tag in tags) {
        //    self.addTag(tags[tag], findKeyWithValue(values, tags[tag]), options.form_name, true);
        //}
        
        if (options.tag_init.length)
        {
          for(i in options.tag_init) {
            tag = options.tag_init[i];
            self.addTag(tag.name, tag.id, options.form_name, true);
          }
        }
        
    }
    
    TagBox.prototype = {
        
        addTag : function(label, id, form_name, force) {
          
          // On n'ajoute pas deux fois le même tag
          if (id && !findKeyWithValue(tagsAddeds[form_name], id))
          {
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
                    self.removeTag(index, id, form_name);
                }
            }));

            // si on force, on ne touche pas l'inpu (il contient déjà ces valeurs)
            if (!force)
            {
              var input_tags = $('input#'+form_name+'_tags');
              if (input_tags.length)
              {
                // array des ids de tags
                var input_values = json_to_array(input_tags.val());
                if (!inArray(input_values, id))
                {
                  input_values[input_values.length] = parseInt(id);
                  $('input#'+form_name+'_tags').val(array2json(input_values));
                }
              }
            }
            //

            tagsAddeds[form_name][id] = id;
            self.inputHolder.before(tag);
            self.updateInput();
             
          }
        },
        removeTag : function(index, id, form_name) {
            
            this.tagbox.find("li").eq(index).remove();
            this.tags.splice(index, 1);
            this.updateInput();
            
            input_tags = $('input#'+form_name+'_tags');
            if (input_tags.length)
            {
              // array des ids de tags
              input_values = json_to_array(input_tags.val());
              for(var i = 0; i < input_values.length; i++)
              {
                if (input_values[i] == id)
                {
                  delete input_values[i];
                }
              }
              $('input#'+form_name+'_tags').val(array2json(input_values));
            }
            
            // Suppression tu tableau js
            if (inArray(tagsAddeds[form_name], id))
            {
              delete tagsAddeds[form_name][id];
            }
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

