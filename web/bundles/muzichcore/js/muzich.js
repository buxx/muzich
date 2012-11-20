/*
 * Scripts de Muzi.ch
 * Rédigé et propriété de Sevajol Bastien (http://www.bux.fr) sauf si mention
 * contraire sur la fonction.
 * 
 */

// Messages flashs
var myMessages = ['info','warning','error','success']; // define the messages types	

function hideAllMessages()
{
  var messagesHeights = new Array(); // this array will store height for each
	 
 for (i=0; i<myMessages.length; i++)
 {
    messagesHeights[i] = $('.' + myMessages[i]).outerHeight();
    $('.' + myMessages[i]).css('top', -messagesHeights[i]); //move element outside viewport	  
 }
}

$(document).ready(function(){
		 
  // Initially, hide them all
  hideAllMessages();

  $('.message').animate({top:"0"}, 500);

  // When message is clicked, hide it
  $('.message a.message-close').click(function(){			  
    $(this).parent('.message').animate({top: -$(this).outerHeight()-50}, 700);
    return false;
  });		 
		 
});   

function findKeyWithValue(arrayt, value)
{
  for(i in arrayt)
  {
    if (arrayt[i] == value)
    {
      return i;
    }
  }
  return "";
}

function array_key_exists (key, search) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Felix Geisendoerfer (http://www.debuggable.com/felix)
  // *     example 1: array_key_exists('kevin', {'kevin': 'van Zonneveld'});
  // *     returns 1: true
  // input sanitation
  if (!search || (search.constructor !== Array && search.constructor !== Object)) {
    return false;
  }

  return key in search;
}


function json_to_array(json_string)
{
  if (json_string.length)
  {
    return eval("(" + json_string + ")");
  }
  return new Array();
}

function strpos (haystack, needle, offset) {
    // Finds position of first occurrence of a string within another  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/strpos    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Onno Marsman    
    // +   bugfixed by: Daniel Esteban
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: strpos('Kevin van Zonneveld', 'e', 5);    // *     returns 1: 14
    var i = (haystack + '').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}

/**
 * Converts the given data structure to a JSON string.
 * Argument: arr - The data structure that must be converted to JSON
 * Example: var json_string = array2json(['e', {pluribus: 'unum'}]);
 * 			var json = array2json({"success":"Sweet","failure":false,"empty_array":[],"numbers":[1,2,3],"info":{"name":"Binny","site":"http:\/\/www.openjs.com\/"}});
 * http://www.openjs.com/scripts/data/json_encode.php
 */
function array2json(arr) {
    var parts = [];
    var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

    for(var key in arr) {
    	var value = arr[key];
        if(typeof value == "object") { //Custom handling for arrays
            if(is_list) parts.push(array2json(value)); /* :RECURSION: */
            else parts[key] = array2json(value); /* :RECURSION: */
        } else {
            var str = "";
            if(!is_list) str = '"' + key + '":';

            //Custom handling for multiple data types
            if(typeof value == "number") str += value; //Numbers
            else if(value === false) str += 'false'; //The booleans
            else if(value === true) str += 'true';
            else str += '"' + value + '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Functions?)

            parts.push(str);
        }
    }
    var json = parts.join(",");
    
    if(is_list) return '[' + json + ']';//Return numerical JSON
    return '{' + json + '}';//Return associative JSON
}

function isInteger(s) {
  return (s.toString().search(/^-?[0-9]+$/) == 0);
}

function inArray(array, p_val) {
    var l = array.length;
    for(var i = 0; i < l; i++) {
        if(array[i] == p_val) {
            return true;
        }
    }
    return false;
}

if(typeof(String.prototype.trim) === "undefined")
{
    String.prototype.trim = function() 
    {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}

function str_replace (search, replace, subject, count) {
    // Replaces all occurrences of search in haystack with replace  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/str_replace    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'    var i = 0,
        j = 0,
        temp = '',
        repl = '',
        sl = 0,        fl = 0,
        f = [].concat(search),
        r = [].concat(replace),
        s = subject,
        ra = Object.prototype.toString.call(r) === '[object Array]',        sa = Object.prototype.toString.call(s) === '[object Array]';
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    } 
    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];
}

function explode (delimiter, string, limit) {
    // Splits a string on string separator and return array of components. If limit is positive only limit number of components is returned. If limit is negative all components except the last abs(limit) are returned.  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/explode    // +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: kenneth
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: d3x
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
    var emptyArray = {0: ''
    };
 
    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;}
 
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    } 
    if (delimiter === true) {
        delimiter = '1';
    }
     if (!limit) {
        return string.toString().split(delimiter.toString());
    }
    // support for limit argument
    var splitted = string.toString().split(delimiter.toString());var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
}

// fonction de nettoyage des tags
function remove_tags(form_name)
{
  //tagsAddeds[form_name] = new Array();
  $('form[name="'+form_name+'"] ul.tagbox li.tag').remove();
  $('form[name="'+form_name+'"] input.tagBox_tags_ids').val('');
  
}

$(document).ready(function(){
    
  // Controle du focus sur la page
  function onBlur() {
    document.body.className = 'blurred';
  }

  function onFocus(){
      document.body.className = 'focused';
      do_action_body_focused();
  }

  if (/*@cc_on!@*/false) { // check for Internet Explorer
      document.onfocusin = onFocus;
      document.onfocusout = onBlur;
  } else {
      window.onfocus = onFocus;
      window.onblur = onBlur;
  }
  
  // Bouton de personalisation du filtre
  // Aucun tags
  $('.tags_prompt input.clear, a.filter_clear_url').live("click", function(){
    $('img.elements_more_loader').show();
    $('ul.elements').html('');
    
    var form = $('form[name="search"]');
    remove_tags(form.attr('name'));
    form.submit();
  });
  
  // tags préférés
  $('.tags_prompt input.mytags').live("click", function(){
    
    $('img.elements_more_loader').show();
    $('ul.elements').html('');
    
    var form = $(this).parents('form[name="search"]');
    
    $.getJSON(url_get_favorites_tags, function(response) {
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      remove_tags(form.attr('name'));
//      if (tags.length)
//      {
        var inputTag = $("div#tags_prompt_"+form.attr('name')+" input.form-default-value-processed");
        for (i in response.tags)
        {
          $('input#tags_selected_tag_'+form.attr('name')).val(i);
          inputTag.val(response.tags[i]);
                                        
          // Et on execute l'évènement selectTag de l'input
          inputTag.trigger("selectTag");
        }
        
        form.submit();
      //}
      
    });
  });
  
  // Tag cliqué dans la liste d'éléments
  $('ul.element_tags li a.element_tag').live('click', function(){
    // Si il y a une liste de tags (comme sur la page favoris, profil)
    var id;
    if ($('ul#favorite_tags').length)
    {
      id = str_replace('element_tag_', '', $(this).attr('id'));
      var link = $('a#filtering_tag_'+id);
      list_tag_clicked(link, true);
    }
    
    if ($('form[name="search"]').length)
    {
      $('img.elements_more_loader').show();
      $('ul.elements').html('');
      var form = $('form[name="search"]');
      id = str_replace('element_tag_', '', $(this).attr('id'));
      remove_tags('search');
      var inputTag = $("div#tags_prompt_search input.form-default-value-processed");
      $('input#tags_selected_tag_search').val(id);
      inputTag.val($(this).html());
      inputTag.trigger("selectTag");
      form.submit();
    }
  });
  
  function element_last_opened(li)
  {
    $('li.element').removeClass('shadows');
    li.addClass('shadows');
  }

  // Affichage un/des embed
  // 1328283150_media-playback-start.png
  // 1328283201_emblem-symbolic-link.png
  $('a.element_embed_open_link').live("click", function(){
    
     var li = $(this).parents('li.element');
     
     element_last_opened(li);
     li.find('a.element_embed_close_link').show();
     li.find('a.element_embed_open_link_text').hide();
     li.find('div.element_embed').show();
     
     return false;
  });
  
  $('a.element_name_embed_open_link').live("click", function(){
    
     var li = $(this).parents('li.element');
     
     element_last_opened(li);
     li.find('a.element_embed_close_link').show();
     li.find('a.element_embed_open_link_text').hide();
     li.find('div.element_embed').show();
     
     return false;
  });

  // Fermeture du embed si demandé
  $('a.element_embed_close_link').live("click", function(){
    
     var li = $(this).parents('li.element');
    
     li.removeClass('shadows');
     li.find('div.element_embed').hide();
     li.find('a.element_embed_open_link_text').show();
     $(this).hide();
     
     return false;
  });
  
  // Affichage du "play" ou du "open" (image png)
  $('li.element a.a_thumbnail, li.element img.open, li.element img.play').live({
    mouseenter:
      function()
      {
        var td = $(this).parent('td');
        var a = td.find('a.a_thumbnail');
        if (a.hasClass('embed'))
        {
          td.find('img.play').show();
        }
        else
        {
          td.find('img.open').show();
        }
      },
    mouseleave:
      function()
      {
        var td = $(this).parent('td');
        var a = td.find('a.a_thumbnail');
        if (a.hasClass('embed'))
        {
          td.find('img.play').hide();
        }
        else
        {
          td.find('img.open').hide();
        }
      }
    }
  );

  // Mise en favoris
  $('a.favorite_link').live("click", function(){
    var link = $(this);
    
    // Pour ne pas attendre la fin du chargement ajax:
    var img = link.find('img');
    if (!link.hasClass('loading'))
    {
      if (img.attr('src') == '/bundles/muzichcore/img/favorite_bw.png')
      {
        img.attr('src', '/bundles/muzichcore/img/favorite.png');
      }
      else
      {
        img.attr('src', '/bundles/muzichcore/img/favorite_bw.png');
      }
    }
    
    link.addClass('loading');
    
    $.getJSON($(this).attr('href'), function(response) {
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      var img = link.find('img');
      link.attr('href', response.link_new_url);
      img.attr('src', response.img_new_src);
      img.attr('title', response.img_new_title);
      link.removeClass('loading');
    });
    return false;
  });
    
  // Affichage du bouton Modifier et Supprimer
  $('ul.elements li.element').live({
    mouseenter:
      function()
      {
        $(this).find('a.element_edit_link').show();
        $(this).find('a.element_remove_link').show();
      },
    mouseleave:
      function()
      {
        if (!$(this).find('a.element_edit_link').hasClass('mustBeDisplayed'))
        {
          $(this).find('a.element_edit_link').hide();
        }
        if (!$(this).find('a.element_remove_link').hasClass('mustBeDisplayed'))
        {
          $(this).find('a.element_remove_link').hide();
        }
      }
    }
  );
    
   // Plus d'éléments
   var last_id = null;
   $('a.elements_more').click(function(){
     var link = $(this);
     var last_element = $('ul.elements li.element:last-child');
     var id_last = str_replace('element_', '', last_element.attr('id'));
     var invertcolor = 0;
     if (last_element.hasClass('even'))
     {
       invertcolor = 1;
     }
     $('img.elements_more_loader').show();
     
     var url = link.attr('href')+'/'+id_last+'/'+invertcolor;
     // Cas exeptionel si on se trouve sur la global_search
     if ($('div#results_search_form').length)
     {
       url = link.attr('href')+id_last+'/'+$('div#results_search_form form input[type="text"]').val();
     }
     
     var old_form_action = $('form[name="search"]').attr('action');
     $('form[name="search"]').attr('action', url);
         
     $.ajax({
       type: 'POST',
       url: url,
       data: $('form[name="search"]').serialize(),
       success: function(response) {
          if (response.status == 'mustbeconnected')
           {
             $(location).attr('href', url_index);
           }

          if (response.count)
          {
            $('ul.elements').append(response.html);
            $('img.elements_more_loader').hide();
            recolorize_element_list();
          }

          if (response.end || response.count < 1)
          {
            $('img.elements_more_loader').hide();
            $('ul.elements').after('<div class="no_elements"><p class="no-elements">'+
              response.message+'</p></div>');
            link.hide();
          }
        },
       dataType: "json"
     });
         
     $('form[name="search"]').attr('action', old_form_action);
     
//     $.getJSON(url, function(response) {
//       if (response.status == 'mustbeconnected')
//        {
//          $(location).attr('href', url_index);
//        }
//       
//       if (response.count)
//       {
//         $('ul.elements').append(response.html);
//         $('img.elements_more_loader').hide();
//         recolorize_element_list();
//       }
//       
//       if (response.end || response.count < 1)
//       {
//         $('img.elements_more_loader').hide();
//         $('ul.elements').after('<div class="no_elements"><p class="no-elements">'+
//           response.message+'</p></div>');
//         link.hide();
//       }
//     });

     return false;
   });
   
  tag_box_input_value = $('ul.tagbox input[type="text"]').val();
   
  // Filtre et affichage éléments ajax
  $('form[name="search"] input[type="submit"]').click(function(){
    $('ul.elements').html('');
    $('div.no_elements').hide();
    $('img.elements_more_loader').show();
  });
  
  $('form[name="search"]').ajaxForm(function(response) { 
    
    if (response.status == 'mustbeconnected')
    {
      $(location).attr('href', url_index);
    }
    
    $('ul.elements').html(response.html);
    
    if (response.count)
     {
       $('img.elements_more_loader').hide();
       $('span.elements_more').show();
       $('a.elements_more').show();
     }

     if (response.count < 1)
     {
       $('img.elements_more_loader').hide();
       $('ul.elements').after('<div class="no_elements"><p class="no-elements">'+
         response.message+'</p></div>');
         $('a.elements_more').hide()
       ;
     }
     
     $('ul.tagbox input[type="text"]').val($('ul.tagbox input[type="text"]').val());
    
  }); 
  
 // Suppression d'un element
  $('a.element_remove_link').jConfirmAction({
    question : string_element_delete_confirm_sentence, 
    yesAnswer : string_element_delete_confirm_yes, 
    cancelAnswer : string_element_delete_confirm_no,
    onYes: function(link){
      
      var li = link.parents('li.element');
      li.find('img.element_loader').show();
      $.getJSON(link.attr('href'), function(response){
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        if (response.status == 'success')
        {
          li.remove();
        }
        else
        {
          li.find('img.element_loader').hide();
        }
      });

      return false;
    },
    onOpen: function(link){
      var li = link.parents('li.element');
      li.find('a.element_edit_link').addClass('mustBeDisplayed');
      li.find('a.element_remove_link').addClass('mustBeDisplayed');
    },
    onClose: function(link){
      var li = link.parents('li.element');
      li.find('a.element_edit_link').removeClass('mustBeDisplayed');
      li.find('a.element_remove_link').removeClass('mustBeDisplayed');
      li.find('a.element_edit_link').hide();
      li.find('a.element_remove_link').hide();
    }
  });

 var elements_edited = new Array();
 // Ouverture du formulaire de modification
  $('a.element_edit_link').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.element');
    // On garde en mémoire l'élément édité en cas d'annulation
    elements_edited[li.attr('id')] = li.html();
    var div_loader = li.find('div.loader');
    li.html(div_loader);
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      // On prépare le tagBox
      li.html(response.html);
      // Pour le click sur l'input de saisie de tag
      li.find('ul.tagbox li.input input[type="text"]').formDefaults();
     
      var options = new Array();
      options.form_name  = response.form_name;
      options.tag_init   = response.tags;

      ajax_query_timestamp = null;
      
      $("#tags_prompt_list_"+response.form_name).tagBox(options);
      
      // On rend ce formulaire ajaxFormable
      $('form[name="'+response.form_name+'"] input[type="submit"]').live('click', function(){
        var li = $(this).parents('li.element');
        li.prepend(div_loader);
        li.find('img.element_loader').show();
      });
      $('form[name="'+response.form_name+'"]').ajaxForm(function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        var li = $('li#'+response.dom_id);
        
        if (response.status == 'success')
        {
          li.html(response.html);
          delete(elements_edited[li.attr('id')]);
        }
        else if (response.status == 'error')
        {
          li.find('img.element_loader').hide();
          li.find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
          
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
          
          li.prepend(ul_errors);
        }
      });
      
    });
    return false;
  });
  
  // Annulation d'un formulaire de modification d'élément
  $('form.edit_element input.cancel_edit').live('click', function(){
    var li = $(this).parents('li.element');
    li.html(elements_edited[li.attr('id')]);
    delete(elements_edited[li.attr('id')]);
  });
 
  ////////////////// TAG PROMPT ///////////////
 
  var ajax_query_timestamp = null;
 
  // Les deux clicks ci-dessous permettent de faire disparaitre
  // la div de tags lorsque l'on clique ailleurs
  $('html').click(function() {
    if ($("div.search_tag_list").is(':visible'))
    {
      $("div.search_tag_list").hide();
    }
  });

  $("div.search_tag_list, div.search_tag_list a.more").live('click', function(event){
    event.stopPropagation();
    $("div.search_tag_list").show();
  });

  function autocomplete_tag(input, form_name)
  {
    // Il doit y avoir au moin un caractère
    if (input.val().length > 0) 
    {

      // on met en variable l'input
      var inputTag = input;
      
      // On récupére la div de tags
      var divtags = $("#search_tag_"+form_name);

      // Si la fenêtre de tags est caché
      if (!divtags.is(':visible'))
      {
        // On la replace
        var position = input.position();
        divtags.css('left', Math.round(position.left) + 5);
        divtags.css('top', Math.round(position.top) + 28);
        // Et on l'affiche
        divtags.show();
      }
      // On affiche le loader
      $('#tag_loader_'+form_name).show();
      // On cache la liste de tags
      var search_tag_list = divtags.find('ul.search_tag_list');
      // On supprime les anciens li
      search_tag_list.find('li').remove();
      search_tag_list.hide();
      // Et on affiche une info
      var span_info = divtags.find('span.info');
      span_info.show();
      // TODO: multilingue !
      span_info.text(str_replace('%string_search%', input.val(), string_search_tag_title));

      // C'est en fonction du nb de resultats qu'il sera affiché
      divtags.find('a.more').hide();

      // On récupère le timestamp pour reconnaitre la dernière requête effectué
      ajax_query_timestamp = new Date().getTime();
      
      // Récupération des tags correspondants
      $.ajax({
        type: 'POST',
        url: url_search_tag+'/'+ajax_query_timestamp,
        dataType: 'json',
        data: {'string_search':input.val()},
        success: function(data) {
        if (data.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        // Ce contrôle permet de ne pas continuer si une requete
        // ajax a été faite depuis.
        if (data.timestamp == ajax_query_timestamp)
        {
          var status = data.status;
          var tags   = data.data;

          // Si on spécifie une erreur
          if (status == 'error')
          {
            // On l'affiche a l'utilisateur
            span_info.text(data.error);
          }
          // Si c'est un succés
          else if (status == 'success')
          {
            if (tags.length > 0)
            {
              var more = false;
              // Pour chaque tags retournés
              for (i in tags)
              {
                var tag_name = tags[i]['name'];
                var tag_id = tags[i]['id'];
                var t_string = tag_name
                // On construit un li
                
                var r_string = $.trim(input.val());
                var re = new RegExp(r_string, "i");
                t_string = t_string.replace(re,"<strong>" + r_string + "</strong>");
                
                                
                var li_tag = 
                  $('<li>').append(
                    $('<a>').attr('id','searched_tag_'+tag_id+'_'+tag_name)
                      .attr('href', '#')
                    // qui réagit quand on clique dessus
                    .click(function(e){
                      // On récupère le nom du tag
                      var name = $(this).attr('id').substr(13,$(this).attr('id').length);
                      name = name.substr(strpos(name, '_')+1, name.length);
                      
                      var id = $(this).attr('id').substr(13,$(this).attr('id').length);
                      id = str_replace(name, '', id);
                      id = str_replace('_', '', id);
                      
                      $('input#tags_selected_tag_'+form_name).val(id);
                      inputTag.val(name);
                      // Et on execute l'évènement selectTag de l'input
                      inputTag.trigger("selectTag");
                      // On cache la liste puisque le choix vient d'être fait
                      divtags.hide();
                      // On vide le champs de saisie du tag
                      $('input.form-default-value-processed').val('');
                      return false;
                    })
                    .append(t_string)
                );

                // Si on depasse les 30 tags
                if (i > 30)
                {
                  more = true;
                  // On le cache
                  li_tag.hide();
                }

                // On ajout ce li a la liste
                search_tag_list.append(li_tag);
              } 

              if (more)
              {
                divtags.find('a.more').show();
              }
              
              span_info.show();
              span_info.text(data.message);
              // Et on affiche la liste
              search_tag_list.show();
            }
            else
            {
              span_info.show();
              span_info.text(data.message);
              search_tag_list.show();
              
              // Dans ce cas ou aucun tag n'a été trouvé, la proposition 
              // d'ajout s'affichecf en dessous
              
              //span_info.text("Aucun tag de trouvé pour \""+inputTag.val()+"\"");
            }
            
            // Si le tag ne semble pas connu en base
            if (!data.same_found)
            {
              li_tag = 
                $('<li>').addClass('new').append(
                  $('<a>').attr('href','#new#'+$.trim(input.val()))
                  // qui réagit quand on clique dessus
                  .click({
                    inputTag: inputTag,
                    form_name: form_name,
                    divtags: divtags
                  }, event_click_new_tag_proposition)
                  .append($.trim(input.val()))
              );
              search_tag_list.append(li_tag);
            }
            
          }

          // On cache le loader
          $('#tag_loader_'+form_name).hide();
        }
      }
      });
      
      
      //$.getJSON(url_search_tag+'/'+input.val()+'/'+ajax_query_timestamp, );
      
    }
  }
  
  function event_click_new_tag_proposition(event)
  {
    form_add_open_dialog_for_new_tag($(event.target), event.data.inputTag, event.data.form_name, event.data.divtags);
  }
 
  function form_add_open_dialog_for_new_tag(link_add_tag, inputTag, form_name, divtags)
  {       
    
    
    // Effet fade-in du fond opaque
    $('body').append($('<div>').attr('id', 'fade')); 
    //Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
    $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
    
    // En premier lieux on fait apparaître la fenêtre de confirmation
    var popup = $('<div>')
    .attr('id', 'add_tag')
    .addClass('popin_block')
    .css('width', '400px')
      //.append($('<h2>').append(string_tag_add_title))
    .append($('<form>')
      .attr('action', url_add_tag)
      .attr('method', 'post')
      .attr('name', 'add_tag')
      .ajaxForm(function(response) {
        /*
        *
        */

        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }

        if (response.status == 'success')
        {
          var tag_id   = response.tag_id;
          var tag_name = response.tag_name;

          $('input#tags_selected_tag_'+form_name).val(tag_id);
          inputTag.val(tag_name);
          // Et on execute l'évènement selectTag de l'input
          inputTag.trigger("selectTag");
          // On cache la liste puisque le choix vient d'être fait
          divtags.hide();

          link_add_tag.parents('div.search_tag_list').find('img.tag_loader').hide();

          $('#fade').fadeOut(400, function(){$('#fade').remove();});
          $('#add_tag').remove();
        }

        if (response.status == 'error')
        {
          $('form[name="add_tag"]').find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');

          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }

          $('form[name="add_tag"]').prepend(ul_errors);
        }

        return false;
      })

      .append($('<div>').addClass('tag')
        .append($('<ul>')
          .append($('<li>').addClass('button')
            .append(link_add_tag.text()))))
      .append($('<p>').append(string_tag_add_text))
      .append($('<p>').append(string_tag_add_argument))
      .append($('<textarea>').attr('name', 'argument'))
      .append($('<div>').addClass('inputs')
        .append($('<input>')
          .attr('type', 'button')
          .attr('value', string_tag_add_inputs_cancel)
          .addClass('button')
          .click(function(){
            $('#fade').fadeOut(1000, function(){$('#fade').remove();});
            $('#add_tag').remove();

            return false;
          })
        )
        .append($('<input>')
          .attr('type', 'submit')
          .attr('value', string_tag_add_inputs_submit)
          .addClass('button')
          .click(function(){

            link_add_tag.parents('div.search_tag_list').find('img.tag_loader').show();

          })
        )
        .append($('<input>').attr('type', 'hidden').attr('name', 'tag_name').val(link_add_tag.text()))
      ))
    ;

    // Il faut ajouter le popup au dom avant de le positionner en css
    // Sinon la valeur height n'est pas encore calculable
    $('body').prepend(popup);

    //Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
    var popMargTop = (popup.height() + 50) / 2;
    var popMargLeft = (popup.width() + 50) / 2;

    //On affecte le margin
    $(popup).css({
      'margin-top' : -popMargTop,
      'margin-left' : -popMargLeft
    });

    return false;
  }
 
  var last_keypress = 0;
  
  function check_timelaps_and_search(input, form_name, time_id, timed, info)
  {
    if (!timed)
    {
      // C'est une nouvelle touche (pas redirigé) on lui donne un id
      // et on met a jour l'id de la dernière pressé
      last_keypress = new Date().getTime(); 
      var this_time_id = last_keypress;
    }
    else
    {
      // Si elle a été redirigé, on met son id dans cette variable
      var this_time_id = time_id;
    }
    
    // C'est une touche redirigé dans le temps qui a été suivit d'une autre touche
    if (time_id != last_keypress && timed)
    {
      // elle disparait
    }
    else
    {
      //
      if ((new Date().getTime() - last_keypress) < 600 || timed == false)
      {
        // Si elle vient d'être tapé (timed == false) elle doit attendre (au cas ou une autre touche soit tapé)
        // Si c'est une redirigé qui n'a pas été remplacé par une nouvelle lettre
        // elle doit attendre au cas ou soit pressé.
        setTimeout(function(){check_timelaps_and_search(input, form_name, this_time_id, true, info)}, 700);
      }
      else
      {
        // il n'y a plus a attendre, on envoie la demande de tag.
        autocomplete_tag(input, form_name);
      }
    }
  }
  
  // Autocompletion de tags
  $("div.tags_prompt ul.tagbox li.input input").live('keypress', function(e){
    
    var form_name = $(this).parents('form').attr('name');
    var code = (e.keyCode ? e.keyCode : e.which);

    if ((e.which !== 0 && e.charCode !== 0) || (code == 8 || code == 46))
    {
      check_timelaps_and_search($(this), form_name, new Date().getTime(), false, $(this).val());
    }
     
  });
  
  // Un click sur ce lien affiche tout les tags cachés de la liste
  $('div.search_tag_list a.more').live('click', function(){
    jQuery.each( $(this).parent('div').find('ul.search_tag_list li') , function(){
      $(this).show();
    });
    $(this).hide();
    return false;
  });
  
  $('ul.tagbox li.input input[type="text"]').formDefaults();
 
  ////////////////// FIN TAG PROMPT ///////////////
 
  // Suppression d'un element
  $('a.group_remove_link').jConfirmAction({
    question : "Supprimer ce groupe ?", 
    yesAnswer : "Oui", 
    cancelAnswer : "Non",
    onYes: function(link){
      window.location = link.attr('href');
      return false;
    },
    onOpen: function(){},
    onClose: function(){}
  });
  
  // Selection Réseau global / Mon réseau
  $('div.select_network a').live('click', function(){
    var divSelect = $(this).parent('div');
    if ($(this).hasClass('all_network'))
    {
      divSelect.find('a.all_network').addClass('active');
      divSelect.find('a.my_network').removeClass('active');
      divSelect.find('select').val('network_public');
    }
    else
    {
      divSelect.find('a.my_network').addClass('active');
      divSelect.find('a.all_network').removeClass('active');
      divSelect.find('select').val('network_personal');
    }
  });
  
  function element_add_proceed_json_response(response)
  {
    if (response.status == 'success')
    {
      $('form[name="add"]').find('ul.error_list').remove();
      $('ul.elements').prepend(response.html);
      $('form[name="add"] input[type="text"]').val('');
      
      if ($('form[name="search"]').length)
      {
        $('form[name="search"]').slideDown();
      }
      remove_tags('add');
      recolorize_element_list();
      
      $('div#element_add_box').slideUp();
      
      if (response.groups.length)
      {
        // Des groupes sont proposés pour diffuser cet élément
        $('div#added_element_to_group').slideDown();
        for (i in response.groups)
        {
          var group = response.groups[i];
          $('ul#groups_to_add_element').html('');
          $('ul#groups_to_add_element')
            .append($('<li>')
              .append($('<a>')
                .addClass('added_element_add_to_group')
                .attr('href', group.url)
                .append(group.name)
              )
            )
          ;
        }
      }
      else
      {
        $('a#element_add_link').show();
      }
      
      form_add_hide_errors();
      
      return true;
    }
    else if (response.status == 'error')
    {
      form_add_display_errors(response.errors);
      $('#form_add_loader').hide();
      return false;
    }
    
    return false;
  }
  
  function form_add_hide_errors()
  {
    $('form[name="add"]').find('ul.error_list').remove();
  }
  
  // Affichage des erreurs lors de laprocédure d'ajout d'un élément
  function form_add_display_errors(errors)
  {
    $('form[name="add"]').find('ul.error_list').remove();
    var ul_errors = $('<ul>').addClass('error_list');

    for (i in errors)
    {
      ul_errors.append($('<li>').append(errors[i]));
    }
    
    $('form[name="add"]').prepend(ul_errors);
  }

  // Ajout d'un element #ajouter (première partie)
  
//  // Click sur "ajouter" (l'url)
//  $('a#form_add_check_url').click(function(){
//    
//    // On fait tourner notre gif loader
//    $('img#form_add_loader').show();
//    
//    $.ajax({
//      type: 'POST',
//      url: url_datas_api,
//      data: {'url':$('input#element_add_url').val()},
//      success: function(response){
//        
//        if (response.status == 'mustbeconnected')
//        {
//          $(location).attr('href', url_index);
//        }
//
//        if (response.status == 'success')
//        {
//          // On cache notre gif loader.
//          $('img#form_add_loader').hide();
//          
//          // On commence par renseigner les champs si on a du concret
//          // name
//          if (response.name)
//          {
//            $('input#element_add_name').val(response.name);
//          }
//          
//          // thumb
//          $('div#form_add_thumb img').attr('src', '/bundles/muzichcore/img/nothumb.png');
//          if (response.thumb)
//          {
//            $('div#form_add_thumb img').attr('src', response.thumb);
//          }
//          
//          // Proposition de tags
//          if (response.tags)
//          {
//            $('ul#form_add_prop_tags li').remove();
//            $('ul#form_add_prop_tags').show();
//            $('ul#form_add_prop_tags_text').show();
//            
//            for (tags_index = 0; tags_index < response.tags.length; tags_index++)
//            {
//              var tag = response.tags[tags_index];
//              var tag_id = '';
//              var tag_name = tag.original_name;
//              // Si il y a des équivalent en base.
//              if (tag.like_found)
//              {
//                tag_id = tag.like.id;
//                tag_name = tag.like.name;
//              }
//                
//              // On aura plus qu'a vérifie le href pour savoir si c'est une demande d'ajout de tags =)
//              $('ul#form_add_prop_tags').append(
//                '<li>'+
//                  '<a href="#'+tag_id+'" class="form_add_prop_tag">'+
//                    tag_name+
//                  '</a>'+
//                '</li>'
//              );
//            }
//          }
//          
//          // On a plus qu'a afficher les champs
//          $('div#form_add_second_part').slideDown();
//          $('div#form_add_first_part').slideUp();
//          form_add_hide_errors();
//        }
//        else if (response.status == 'error')
//        {
//          form_add_display_errors(response.errors);
//          $('#form_add_loader').hide();
//          return false;
//        }
//      },
//      dataType: 'json'
//    });
//    
//  });
  
  function element_add_proceed_data_apis(response)
  {
    if (response.status == 'mustbeconnected')
    {
      $(location).attr('href', url_index);
    }

    if (response.status == 'success')
    {
      // On cache notre gif loader.
      $('img#form_add_loader').hide();

      // On commence par renseigner les champs si on a du concret
      // name
      if (response.name)
      {
        $('input#element_add_name').val(response.name);
      }

      // thumb
      $('div#form_add_thumb img').attr('src', '/bundles/muzichcore/img/nothumb.png');
      if (response.thumb)
      {
        $('div#form_add_thumb img').attr('src', response.thumb);
      }

      // Proposition de tags
      if (response.tags)
      {
        $('ul#form_add_prop_tags li').remove();
        $('ul#form_add_prop_tags_api').show();
        $('p#form_add_prop_tags_text').hide();
        
        if (response.tags.length)
        {
          $('p#form_add_prop_tags_text').show();
        }
        
        $('ul#form_add_prop_tags_api li').remove();
        for (tags_index = 0; tags_index < response.tags.length; tags_index++)
        {
          var tag = response.tags[tags_index];
          var tag_id = '';
          var tag_name = tag.original_name;
          // Si il y a des équivalent en base.
          if (tag.like_found)
          {
            tag_id = tag.like.id;
            tag_name = tag.like.name;
          }

          // On aura plus qu'a vérifie le href pour savoir si c'est une demande d'ajout de tags =)
          $('ul#form_add_prop_tags_api').append(
            '<li>'+
              '<a href="#'+tag_id+'" class="form_add_prop_tag">'+
                tag_name+
              '</a>'+
            '</li>'
          );
        }
      }

      return true;
    }
    else if (response.status == 'error')
    {
      return false;
    }
    
    return true;
  }
  
  /*
   * Formulaire d'ajout: click sur proposition de tags du a une api
   */
  
  $('a.form_add_prop_tag').live('click', function(){
    
    var form_name = "add";
    var tag_id = str_replace('#', '', $(this).attr('href'));
    var input_tag = $("div.tags_prompt ul.tagbox li.input input");
    
    // Si on connait le tag id (pas un nouveau tag donc)
    if (tag_id)
    {
      $('input#tags_selected_tag_'+form_name).val(tag_id);
      input_tag.val($(this).text());
      // Et on execute l'évènement selectTag de l'input
      input_tag.trigger("selectTag");
    }
    else
    {
      // Cette var sert a rien ici, mais c'est la refactorisation qui
      //  pose problmeme (corrigeable cela dit)
      var divtags = $("#search_tag_"+form_name);
      form_add_open_dialog_for_new_tag($(this), input_tag, form_name, divtags);
    }
    
    // On nettoie le champs de saisie des tags
    $('input.form-default-value-processed').val('');
    
  });
  
  // #ajouter ajouter un élément (envoi du formulaire)
  $('form[name="add"] input[type="submit"]').live('click', function(){
    $('form[name="add"]').find('img.tag_loader').show();
  });
  $('form[name="add"]').ajaxForm(function(response) {
    if (response.status == 'mustbeconnected')
    {
      $(location).attr('href', url_index);
    }
    
    $('form[name="add"] img.tag_loader').hide();
    
    // Si on en est a la promière étape la réponse sera des données récupérés auprès
    // des apis
    if ($('input#form_add_step').val() == '1')
    {
      if (element_add_proceed_data_apis(response))
      {
        // On a plus qu'a afficher les champs
        $('div#form_add_second_part').slideDown();
        $('div#form_add_first_part').slideUp();
        form_add_hide_errors();
        $('#form_add_loader').hide();
        $('input#form_add_step').val('2');
        $('form[name="add"]').attr('action', url_element_add);
        $('span#add_url_title_url').html($('input#element_add_url').val());
      }
      else
      {
        form_add_display_errors(response.errors);
        $('#form_add_loader').hide();
      }
    }
    else if ($('input#form_add_step').val() == '2')
    {
      if (element_add_proceed_json_response(response))
      {
        form_add_reinit();
      }
    }

    
    return false;
  });
  
  
  function form_add_reinit()
  {
    $('div#element_add_box').slideUp();
    $('div#form_add_first_part').show();
    $('div#form_add_second_part').hide();
    $('ul#form_add_prop_tags_api').hide();
    $('ul#form_add_prop_tags_text').hide();
    $('input#element_add_url').val('');
    $('input#element_add_name').val('');
    $('input#form_add_step').val(1);
    $('form[name="add"]').attr('action', url_datas_api);
  }

 /////////////////////
 var tags_ids_for_filter = new Array();
 // Filtre par tags (show, favorite)
 function refresh_elements_with_tags_selected(link)
  {
    
    
    // Puis on fait notre rekékéte ajax.
    $('ul.elements').html('');
    $('div.no_elements').hide();
    $('img.elements_more_loader').show();
    $.getJSON($('input#get_elements_url').val()+'/'+array2json(tags_ids_for_filter), function(response){
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      $('ul.elements').html(response.html);
      
      if (response.count)
       {
         $('img.elements_more_loader').hide();
         $('span.elements_more').show();
         $('a.elements_more').show();
       }
    });
    
    return false;
  }
  
  function list_tag_clicked(link, erease)
  {
    if (erease)
    {
      $('ul#favorite_tags a.tag').removeClass('active');
    }
    
    // Ensuite on l'active ou le désactive
    if (link.hasClass('active'))
    {
      link.removeClass('active');
    }
    else
    {
      link.addClass('active');
    }
    
    // On construit notre liste de tags
    tags_ids_for_filter = new Array();
    $('ul#favorite_tags a.tag.active').each(function(index){
      var id = str_replace('filtering_tag_', '', $(this).attr('id'));
      tags_ids_for_filter[id] = id;
    });
    
    // On adapte le lien afficher plus de résultats
    var a_more = $('a.elements_more');
    a_more.attr('href', $('input#more_elements_url').val()+'/'+array2json(tags_ids_for_filter));
    
    return check_timelaps_and_find_with_tags(link, new Date().getTime(), false);
  }
   
  $('ul#favorite_tags a.tag').click(function(){
    list_tag_clicked($(this));
  });
  
  last_keypress = 0;
  function check_timelaps_and_find_with_tags(link, time_id, timed)
  {
    if (!timed)
    {
      // C'est une nouvelle touche (pas redirigé) on lui donne un id
      // et on met a jour l'id de la dernière pressé
      last_keypress = new Date().getTime(); 
      var this_time_id = last_keypress;
    }
    else
    {
      // Si elle a été redirigé, on met son id dans cette variable
      var this_time_id = time_id;
    }
    
    // C'est une touche redirigé dans le temps qui a été suivit d'une autre touche
    if (time_id != last_keypress && timed)
    {
      // elle disparait
    }
    else
    {
      //
      if ((new Date().getTime() - last_keypress) < 800 || timed == false)
      {
        // Si elle vient d'être tapé (timed == false) elle doit attendre (au cas ou une autre touche soit tapé)
        // Si c'est une redirigé qui n'a pas été remplacé par une nouvelle lettre
        // elle doit attendre au cas ou soit pressé.
        setTimeout(function(){check_timelaps_and_find_with_tags(link, this_time_id, true)}, 900);
      }
      else
      {
        // il n'y a plus a attendre, on envoie la demande de tag.
        return refresh_elements_with_tags_selected(link);
      }
    }
    
    return null;
  }
  
  ////////////////////////////////////////
  /// Gestion de nouveaux éléments
  
  var do_check_new_elements = false;
  
  function check_new_elements()
  {
    if ($('ul.elements li').length)
    {
      // Si l'utilisateur a quitté la page on reporte le check
      if ($('body.blurred').length)
      {
        // on passe la variable a vrai de façon a ce que lorsque la page
        // et ré affiché on lance le check
        do_check_new_elements = true;
      }
      else
      {
        var url = url_element_new_count
          +'/'
          +str_replace('element_', '', $('ul.elements li:first').attr('id'))
        ;
        
        $.ajax({
          type: 'POST',
          url: url,
          data: $('form[name="search"]').serialize(),
          success: function(response){
          
            if (response.status == 'mustbeconnected')
            {
              $(location).attr('href', url_index);
            }

            if (response.status == 'success' && response.count)
            {
              $('div.display_more_elements').show();
              $('div.display_more_elements span').html(response.message);
            }

            setTimeout(check_new_elements, 150000);
          },
          dataType: "json"
        });
        
        
//        $.getJSON(url, function(response){
//          
//          if (response.status == 'mustbeconnected')
//          {
//            $(location).attr('href', url_index);
//          }
//
//          if (response.status == 'success' && response.count)
//          {
//            $('div.display_more_elements').show();
//            $('div.display_more_elements span').html(response.message);
//          }
//
//          setTimeout(check_new_elements, 150000);
//        });
        
        do_check_new_elements = false;
      }
      
    }
  }
  
  if ($('div.display_more_elements').length)
  {
    setTimeout(check_new_elements, 150000);
  }
  
  $('a.show_new_elements').live('click', function(){
    var url = url_element_new_get
      +'/'
      +str_replace('element_', '', $('ul.elements li:first').attr('id'))
    ;
    $('img.elements_new_loader').show();
    
    
    $.ajax({
      type: 'POST',
      url: url,
      data: $('form[name="search"]').serialize(),
      success: function(response){
      
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }

        if (response.status == 'success')
        {
          if (response.count)
          {
            $('div.display_more_elements').show();
            $('div.display_more_elements span').html(response.message);
          }
          else
          {
            $('div.display_more_elements').hide();
          }

          $('ul.elements').prepend(response.html);
          recolorize_element_list();
        }

        $('img.elements_new_loader').hide();
      },
      dataType: "json"
    });
    
//    $.getJSON(url, function(response){
//      
//      if (response.status == 'mustbeconnected')
//      {
//        $(location).attr('href', url_index);
//      }
//      
//      if (response.status == 'success')
//      {
//        if (response.count)
//        {
//          $('div.display_more_elements').show();
//          $('div.display_more_elements span').html(response.message);
//        }
//        else
//        {
//          $('div.display_more_elements').hide();
//        }
//        
//        $('ul.elements').prepend(response.html);
//        recolorize_element_list();
//      }
//      
//      $('img.elements_new_loader').hide();
//    });
  });

  function recolorize_element_list()
  {
    $('ul.elements li.element').each(function(index){
      if ((index & 1) == 1)
      {
        $(this).removeClass('even');
        $(this).removeClass('odd');
        $(this).addClass('odd');
      }
      else
      {
        $(this).removeClass('odd');
        $(this).removeClass('even');
        $(this).addClass('even');
      }
    });
  }
  
  /*
   * Action a effectuer lorsque l'utilisateur met le focus sur la page
   */
  function do_action_body_focused()
  {
    if (do_check_new_elements)
    {
      check_new_elements();
    }
  }
  
  /*
   * Commentaires d'élément
   */
  
  // Afficher les commentaires
    $('td.element_content a.display_comments').live('click', function(){
      display_comments($(this).parents('li.element'));
    });
    
    $('td.element_content a.hide_comments').live('click', function(){
      hide_comments($(this).parents('li.element'));
    });
  
    function display_comments(li_element)
    {
      li_element.find('div.comments').slideDown();
      li_element.find('a.display_comments').hide();
      li_element.find('a.hide_comments').show();
    }
  
    function hide_comments(li_element)
    {
      li_element.find('div.comments').slideUp();
      li_element.find('a.display_comments').show();
      li_element.find('a.hide_comments').hide();
    }
    
  // Ajouter un commentaire
    $('li.element a.add_comment').live('click', function(){
      display_add_comment($(this).parents('li.element'));
    });
    
    $('form.add_comment input[type="submit"]').live('click', function(){
      $(this).parents('div.comments').find('img.comments_loader').show();
    });
        
    function display_add_comment(li_element)
    {
      display_comments(li_element);
      li_element.find('a.add_comment').hide();
      li_element.find('form.add_comment').show();
      
      li_element.find('form.add_comment').ajaxForm(function(response) {
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }

        li_element.find('img.comments_loader').hide();
        
        if (response.status == 'success')
        {
          li_element.find('form.add_comment').find('ul.error_list').remove();
          li_element.find('div.comments ul.comments').append(response.html);
          hide_add_comment(li_element);
        }
        else if (response.status == 'error')
        {
          li_element.find('form.add_comment').find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');

          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }

          li_element.find('form.add_comment').prepend(ul_errors);
        }

        return false;
      });
      
    }
    
    $('form.add_comment input.cancel').live('click', function(){
      var li_element = $(this).parents('li.element');
      hide_add_comment(li_element);
    });
    
    function hide_add_comment(li_element)
    {
      li_element.find('a.add_comment').show();
      li_element.find('form.add_comment').hide();
      li_element.find('form.add_comment textarea').val('');
    }
   
   // Modifier et supprimer
   // Affichage du bouton Modifier et Supprimer
    $('ul.comments li.comment').live({
      mouseenter:
        function()
        {
          $(this).find('a.comment_edit_link').show();
          $(this).find('a.comment_remove_link').show();
        },
      mouseleave:
        function()
        {
          if (!$(this).find('a.comment_edit_link').hasClass('mustBeDisplayed'))
          {
            $(this).find('a.comment_edit_link').hide();
          }
          if (!$(this).find('a.comment_remove_link').hasClass('mustBeDisplayed'))
          {
            $(this).find('a.comment_remove_link').hide();
          }
        }
      }
    );
      
    // Supprimer
    $('a.comment_remove_link').jConfirmAction({
    question : string_comment_delete_confirm_sentence, 
    yesAnswer : string_comment_delete_confirm_yes, 
    cancelAnswer : string_comment_delete_confirm_no,
    onYes: function(link){
      
      var li = link.parents('li.comment');
      li.find('img.comment_loader').show();
      
      $.getJSON(link.attr('href'), function(response){
        
        li.find('img.comment_loader').hide();
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        if (response.status == 'success')
        {
          li.remove();
        }
      });

      return false;
    },
    onOpen: function(link){
      var li = link.parents('li.comment');
      li.find('a.comment_edit_link').addClass('mustBeDisplayed');
      li.find('a.comment_remove_link').addClass('mustBeDisplayed');
    },
    onClose: function(link){
      var li = link.parents('li.comment');
      li.find('a.comment_edit_link').removeClass('mustBeDisplayed');
      li.find('a.comment_remove_link').removeClass('mustBeDisplayed');
      li.find('a.comment_edit_link').hide();
      li.find('a.comment_remove_link').hide();
    }
  });
  
  var comments_edited = new Array();
  
  // Modification
  // Ouverture du formulaire de modification
  $('a.comment_edit_link').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.comment');
    // On garde en mémoire l'élément édité en cas d'annulation
    comments_edited[li.attr('id')] = li.html();
    var loader = li.find('img.comment_loader');
    li.html(loader);
    li.find('img.comment_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.html(response.html);
      // On rend ce formulaire ajaxFormable
      $('li#'+li.attr('id')+' form.edit_comment input[type="submit"]').live('click', function(){
        var li_current = $(this).parents('li.comment');
        li_current.prepend(loader);
        li_current.find('img.comment_loader').show();
      });
      
      li.find('form.edit_comment').ajaxForm(function(response){
        
        li = $('li#'+response.dom_id);
        li.find('img.comment_loader').hide();
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        if (response.status == 'success')
        {
          li.html(response.html);
          delete(comments_edited[li.attr('id')]);
        }
        else if (response.status == 'error')
        {
          li.find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
          
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
          
          li.prepend(ul_errors);
        }
      });
      
    });
    return false;
  });
  
  // Annulation d'un formulaire de modification d'un comment
  $('form.edit_comment input.cancel').live('click', function(){
    var li = $(this).parents('li.comment');
    li.html(comments_edited[li.attr('id')]);
    delete(comments_edited[li.attr('id')]);
  });
  
  /*
   * Ajout d'un tag en favoris a partir d'un élément
   */
  
  $('li.element_tag').live({
      mouseenter:
        function()
        {
          $(this).find('a.tag_to_favorites').show();
          $(this).find('a.element_tag').addClass('element_tag_large_for_fav');
        },
      mouseleave:
        function()
        {
          if (!$(this).find('a.tag_to_favorites').hasClass('mustBeDisplayed'))
          {
            $(this).find('a.tag_to_favorites').hide();
            $(this).find('a.element_tag').removeClass('element_tag_large_for_fav');
          }
        }
      }
    );
      
  $('a.tag_to_favorites').jConfirmAction({
    question : string_tag_addtofav_confirm_sentence, 
    yesAnswer : string_tag_addtofav_confirm_yes, 
    cancelAnswer : string_tag_addtofav_confirm_no,
    onYes: function(link){
      
      $.getJSON(link.attr('href'), function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
      });
      
      $('div.question').fadeOut();
      return false;
    },
    onOpen: function(link){
      var li = link.parents('li.element_tag');
      li.find('a.tag_to_favorites').addClass('mustBeDisplayed');
    },
    onClose: function(link){
      var li = link.parents('li.element_tag');
      li.find('a.tag_to_favorites').removeClass('mustBeDisplayed');
      li.find('a.element_tag').removeClass('element_tag_large_for_fav');
      li.find('a.tag_to_favorites').hide();
    }
  });
  
  /*
   * Ajout dans un groupe de l'élément envoyé 
   */
  
  $('a.added_element_add_to_group').live('click', function(){
    
    var loader = $('div#added_element_to_group').find('img.loader');
    loader.show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      loader.hide();
    
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'success')
      {
        $('li#'+response.dom_id).html(response.html);
      }
      
      $('div#added_element_to_group').slideUp();
      $('a#element_add_link').show();
      
    });
    return false;
  });
  
  $('div#added_element_to_group a.cancel').live('click', function(){
    $('div#added_element_to_group').slideUp();
    $('a#element_add_link').show();
    return false;
  });
   
   /*
    * Report / signalement d'un élément
    */
   
   $('a.element_report').jConfirmAction({
    question : string_elementreport_confirm_sentence, 
    yesAnswer : string_elementreport_confirm_yes, 
    cancelAnswer : string_elementreport_confirm_no,
    onYes: function(link){
      
      $.getJSON(link.attr('href'), function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
      });
      
      $('div.question').fadeOut();
      return false;
    },
    onOpen: function(link){
      
    },
    onClose: function(link){
      
    }
  });

  /*
   * Vote sur element
   */
  
  $('li.element a.vote').live('click', function(){
    
    var img = $(this).find('img');
    var link = $(this);
    img.attr('src', url_img_ajax_loader);
    
    $.getJSON(link.attr('href'), function(response){
        
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'success')
      {
        link.attr('href', response.data.a.href);
        img.attr('src', response.data.img.src);
        link.parents('td.right').find('li.score span.score').html(response.data.element.points);
      }
      
    });
    
    return false;
  });
  
  
  // Enlever les ids du ElementSearch
  $('div.more_filters a.new_comments, div.more_filters a.new_favorites, div.more_filters a.new_tags').live('click', function(){
    
    $('img.elements_more_loader').show();
    $('ul.elements').html('');
    var link = $(this);
    
    $.getJSON(link.attr('href'), function(response){
        
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'success')
      {
        $('form[name="search"]').submit();
        $('div.more_filters a.new_comments').hide();
        $('div.more_filters a.new_favorites').hide();
        $('div.more_filters a.new_tags').hide();
      }
      
    });
    
    return false;
  });
  
  /*
   * 
   * Proposition de tags sur un élément
   * 
   */
  
 // Ouverture du formulaire de modification
  $('a.element_propose_tags').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.element');
    
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.find('img.element_loader').hide();
      
      if (response.status == 'success')
      {
        
        // On prépare le tagBox
        var table = li.find('table:first');
        li.find('div.tag_proposition').remove();
        table.after(response.html);

        // Pour le click sur l'input de saisie de tag
        li.find('ul.tagbox li.input input[type="text"]').formDefaults();

        var options = new Array();
        options.form_name  = response.form_name;
        options.tag_init   = response.tags;

        ajax_query_timestamp = null;

        $("#tags_prompt_list_"+response.form_name).tagBox(options);
      
      // On rend ce formulaire ajaxFormable
      $('form[name="'+response.form_name+'"] input[type="submit"]').live('click', function(){
        li = $(this).parents('li.element');
        li.find('img.element_loader').show();
      });
      $('form[name="'+response.form_name+'"]').ajaxForm(function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
                
        if (response.status == 'success')
        {
          li = $('li#'+response.dom_id);
          li.find('img.element_loader').hide();
          li.find('form')
          li.find('div.tag_proposition').remove();
        }
        else if (response.status == 'error')
        {
          li.find('img.element_loader').hide();
          li.find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
          
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
          
          li.find('div.tag_proposition div.tags_prompt').prepend(ul_errors);
        }
        
      });
      
      }
    });
    return false;
  });
  
  // Annulation d'un formulaire de modification d'élément
  $('div.tag_proposition input.cancel').live('click', function(){
    $(this).parents('div.tag_proposition').slideUp();
  });
  
  // Ouvrir les propositions de tags de l'élément
  $('a.element_view_propositions_link').live('click', function(){
    
    var link = $(this);
    li = link.parents('li.element');
    
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.find('img.element_loader').hide();
      
      if (response.status == 'success')
      {
        var table = li.find('table:first');
        li.find('div.tags_proposition_view').remove();
        table.after(response.html);
      }
    });
    
    return false;
  });
  
  $('a.accept_tag_propotision').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.element');
    
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.find('img.element_loader').hide();
      
      if (response.status == 'success')
      {
        li.html(response.html);
      }
    });
    
    return false;
  });
  
  //
  $('a.refuse_tag_propositions').live('click', function(){
    
    var link = $(this);
    var li = link.parents('li.element');
    
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      li.find('img.element_loader').hide();
      
      if (response.status == 'success')
      {
        li.find('div.tags_proposition_view').remove();
      }
    });
    
    return false;
  });
  
  /*
   * Proposition de tag sur un élément FIN
   */
  
  /*
    * Report / signalement d'un commentaire
    */
   
   $('a.comment_report').jConfirmAction({
    question : string_commentreport_confirm_sentence, 
    yesAnswer : string_commentreport_confirm_yes, 
    cancelAnswer : string_commentreport_confirm_no,
    onYes: function(link){
      
      $.getJSON(link.attr('href'), function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
      });
      
      $('div.question').fadeOut();
      return false;
    },
    onOpen: function(link){
      
    },
    onClose: function(link){
      
    }
  });
  
  
  /*
   * reshare repartage
   */
  
  $('a.element_reshare').jConfirmAction({
    question : string_elementreshare_confirm_sentence, 
    yesAnswer : string_elementreshare_confirm_yes, 
    cancelAnswer : string_elementreshare_confirm_no,
    onYes: function(link){
      
      $('div.question').fadeOut();
      $.getJSON(link.attr('href'), function(response){
        
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
        
        // On affiche l'élément que si on voit que le formulaire est sur la page
        // Sinon c'est qu'on est sur une page ou on a pas normalement la possibilité
        // d'ajouetr un élément.
        if ($('form[name="add"]').length)
        {
          element_add_proceed_json_response(response);
        }
        return false;
      });
      
      
      
      return false;
    },
    onOpen: function(link){
      
    },
    onClose: function(link){
      
    }
  });

  /*
   * Cloud tags
   */
  
  $('a#display_all_cloud_tag').click(function(){
    $('ul#favorite_tags li').show();
    $(this).hide();
  });
  
  $('input#cloud_tags_filter').keyup(function(){
    var search_string = $(this).val();
    
    $('ul#favorite_tags li a').removeClass('highlight');
    
    if (search_string.length > 1)
    {
      $('ul#favorite_tags li a').each(function(){

        if ($(this).text().toUpperCase().search(search_string.toUpperCase()) != -1)
        {
          $(this).addClass('highlight')
        }

      });
    }
    
  });

});



/*
 * Ouverture d'une boite avec effet fade et centré
 *   code origine: form_add_open_dialog_for_new_tag
 */

  function open_popin_dialog(object_id)
  {
    
    // Effet fade-in du fond opaque
    $('body').append($('<div>').attr('id', 'fade')); 
    //Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
    $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
    
    $('#'+object_id).css({
      position: 'absolute',
      left: ($(window).width() 
        - $('#'+object_id).width())/2,
//      top: ($(window).height() 
//        - $('#'+object_id).height())/2
        top: '20%'
      });
    $('#'+object_id).show();
    
    console.debug('width: '+$('#'+object_id).width());
  }