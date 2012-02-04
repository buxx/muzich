/*
 * Scripts de Muzi.ch
 * Rédigé et propriété de Sevajol Bastien (http://www.bux.fr) sauf si mention
 * contraire sur la fonction.
 * 
 */

// Controle du focus sur la page
function onBlur() {
  document.body.className = 'blurred';
}

function onFocus(){
    document.body.className = 'focused';
}

if (/*@cc_on!@*/false) { // check for Internet Explorer
    document.onfocusin = onFocus;
    document.onfocusout = onBlur;
} else {
    window.onfocus = onFocus;
    window.onblur = onBlur;
}

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
    var emptyArray = {        0: ''
    };
 
    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {        return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;    }
 
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
    var splitted = string.toString().split(delimiter.toString());    var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
}

$(document).ready(function(){
    
  
  // Bouton de personalisation du filtre
  // pour le moment ce ne sotn que des redirection vers des actions
  $('.tags_prompt input.clear, a.filter_clear_url').live("click", function(){
    $(location).attr('href', $('input.filter_clear_url').val());
  });
  $('.tags_prompt input.mytags').live("click", function(){
    $(location).attr('href', $('input.filter_mytags_url').val());
  });

  // Affichage un/des embed
  // 1328283150_media-playback-start.png
  // 1328283201_emblem-symbolic-link.png
  $('a.element_embed_open_link').live("click", function(){
    
     li = $(this).parent('td').parent('tr').parent().parent().parent('li.element');
     
     li.find('a.element_embed_close_link').show();
     li.find('a.element_embed_open_link_text').hide();
     li.find('div.element_embed').show();
     
     return false;
  });
  
  $('a.element_name_embed_open_link').live("click", function(){
    
     li = $(this).parent('span').parent('td').parent('tr').parent().parent().parent('li.element');
     
     li.find('a.element_embed_close_link').show();
     li.find('a.element_embed_open_link_text').hide();
     li.find('div.element_embed').show();
     
     return false;
  });

  // Fermeture du embed si demandé
  $('a.element_embed_close_link').live("click", function(){
    
     li = $(this).parent('td').parent('tr').parent().parent().parent('li.element');
    
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
        td = $(this).parent('td');
        a = td.find('a.a_thumbnail');
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
        td = $(this).parent('td');
        a = td.find('a.a_thumbnail');
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
     link = $(this);
     $.getJSON($(this).attr('href'), function(response) {
       img = link.find('img');
       link.attr('href', response.link_new_url);
       img.attr('src', response.img_new_src);
       img.attr('title', response.img_new_title);
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
   last_id = null;
   $('a.elements_more').click(function(){
     link = $(this);
     last_element = $('ul.elements li.element:last-child');
     id_last = str_replace('element_', '', last_element.attr('id'));
     invertcolor = 0;
     if (last_element.hasClass('even'))
     {
       invertcolor = 1;
     }
     $('img.elements_more_loader').show();
     $.getJSON(link.attr('href')+'/'+id_last+'/'+invertcolor, function(response) {
       if (response.count)
       {
         $('ul.elements').append(response.html);
         $('img.elements_more_loader').hide();
       }
       
       if (response.end || response.count < 1)
       {
         $('img.elements_more_loader').hide();
         $('ul.elements').after('<div class="no_elements"><p class="no-elements">'+
           response.message+'</p></div>');
         link.hide();
       }
     });
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
    question : "Vraiment supprimer ?", 
    yesAnswer : "Oui", 
    cancelAnswer : "Non",
    onYes: function(link){
      
      li = link.parent('td').parent('tr').parent().parent().parent('li.element');
      li.find('img.element_loader').show();
      $.getJSON(link.attr('href'), function(response){
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
      li = link.parent('td').parent('tr').parent().parent().parent('li.element');
      li.find('a.element_edit_link').addClass('mustBeDisplayed');
      li.find('a.element_remove_link').addClass('mustBeDisplayed');
    },
    onClose: function(link){
      li = link.parent('td').parent('tr').parent().parent().parent('li.element');
      li.find('a.element_edit_link').removeClass('mustBeDisplayed');
      li.find('a.element_remove_link').removeClass('mustBeDisplayed');
      li.find('a.element_edit_link').hide();
      li.find('a.element_remove_link').hide();
    }
  });

 elements_edited = new Array();
 // Ouverture du formulaire de modification
  $('a.element_edit_link').live('click', function(){
    
    link = $(this);
    li = link.parent('td').parent('tr').parent().parent().parent('li.element');
    // On garde en mémoire l'élément édité en cas d'annulation
    elements_edited[li.attr('id')] = li.html();
    div_loader = li.find('div.loader');
    li.html(div_loader);
    li.find('img.element_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      
      // On prépare le tagBox
      li.html(response.html);
     
      var options = new Array();
      options.form_name  = response.form_name;
      options.tag_init   = response.tags;

      ajax_query_timestamp = null;
      
      $("#tags_prompt_list_"+response.form_name).tagBox(options);
      
      // On rend ce formulaire ajaxFormable
      $('form[name="'+response.form_name+'"] input[type="submit"]').live('click', function(){
        li.prepend(div_loader);
        li.find('img.element_loader').show();
      });
      $('form[name="'+response.form_name+'"]').ajaxForm(function(response){
        
        if (response.status == 'success')
        {
          li.html(response.html);
          delete(elements_edited[li.attr('id')]);
        }
        else if (response.status == 'error')
        {
          li.find('img.element_loader').hide();
          li.find('ul.error_list').remove();
          ul_errors = $('<ul>').addClass('error_list');
          
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
    var li = $(this).parent('form').parent('li');
    li.html(elements_edited[li.attr('id')]);
    delete(elements_edited[li.attr('id')]);
  });
 
  ////////////////// TAG PROMPT ///////////////
 
  ajax_query_timestamp = null;
  tag_text_help = $('input.tag_text_help').val();
 
  // Les deux clicks ci-dessous permettent de faire disparaitre
  // la div de tags lorsque l'on clique ailleurs
  $('html').click(function() {
    if ($("div.search_tag_list").is(':visible'))
    {
      $("div.search_tag_list").hide();
    }
  });

  $("div.search_tag_list").live('click', function(event){
    event.stopPropagation();
  });

  function autocomplete_tag(input, form_name)
  {
    // Il doit y avoir au moin un caractère
    if (input.val().length > 0) 
    {

      // on met en variable l'input
      inputTag = input;
      
      // On récupére la div de tags
      divtags = $("#search_tag_"+form_name);

      // Si la fenêtre de tags est caché
      if (!divtags.is(':visible'))
      {
        // On la replace
        position = input.position();
        divtags.css('left', Math.round(position.left) + 5);
        divtags.css('top', Math.round(position.top) + 28);
        // Et on l'affiche
        divtags.show();
      }
      // On affiche le loader
      $('#tag_loader_'+form_name).show();
      // On cache la liste de tags
      search_tag_list = divtags.find('ul.search_tag_list');
      // On supprime les anciens li
      search_tag_list.find('li').remove();
      search_tag_list.hide();
      // Et on affiche une info
      span_info = divtags.find('span.info');
      span_info.show();
      span_info.text("Recherche des tags correspondants à \""+input.val()+"\" ...");

      // C'est en fonction du nb de resultats qu'il sera affiché
      divtags.find('a.more').hide();

      // On récupère le timestamp pour reconnaitre la dernière requête effectué
      ajax_query_timestamp = new Date().getTime();

      // Récupération des tags correspondants
      $.getJSON('/app_dev.php/fr/search/tag/'+input.val()+'/'+ajax_query_timestamp, function(data) {
        // Ce contrôle permet de ne pas continuer si une requete
        // ajax a été faite depuis.
        if (data.timestamp == ajax_query_timestamp)
        {
          status = data.status;
          tags   = data.data;

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
              more = false;
              // Pour chaque tags retournés
              for (i in tags)
              {
                var tag_name = tags[i]['name'];
                var tag_id = tags[i]['id'];
                var t_string = tag_name
                // On construit un li
                
                string_exploded = explode(' ', $.trim(input.val()));
                for (n in string_exploded)
                {
                  r_string = string_exploded[n];
                  var re = new RegExp(r_string, "i");
                  t_string = t_string.replace(re,"<strong>" + r_string + "</strong>");
                }
                                
                li_tag = 
                  $('<li>').append(
                    $('<a>').attr('href','#'+tag_id+'#'+tag_name)
                    // qui réagit quand on clique dessus
                    .click(function(e){
                      // On récupère le nom du tag
                      name = $(this).attr('href').substr(1,$(this).attr('href').length);
                      name = name.substr(strpos(name, '#')+1, name.length);
                                            
                      id = $(this).attr('href').substr(1,$(this).attr('href').length);
                      id = str_replace(name, '', id);
                      id = str_replace('#', '', id);
                                            
                      $('input#tags_selected_tag_'+form_name).val(id);
                      inputTag.val(name);
                      // Et on execute l'évènement selectTag de l'input
                      inputTag.trigger("selectTag");
                      // On cache la liste puisque le choix vient d'être fait
                      divtags.hide();
                      inputTag.val(tag_text_help); 
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

              // On cache l'info
              span_info.hide();
              // Et on affiche la liste
              search_tag_list.show();
            }
            else
            {
              span_info.text("Aucun tag de trouvé pour \""+inputTag.val()+"\"");
            }
            
          }

          // On cache le loader
          $('#tag_loader_'+form_name).hide();
        }
      });
      
    }
  }
 
 
  last_keypress = 0;
  
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
    
    var form_name = $(this).parent('li').parent('ul.tagbox')
      .parent('div.tags_prompt').parent('form').attr('name')
    ;
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
    return false;
  });
  
  $('ul.tagbox li.input input[type="text"]').val(tag_text_help);
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
    divSelect = $(this).parent('div');
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

  // Ajout d'un element
  $('form[name="add"] input[type="submit"]').live('click', function(){
    $('form[name="add"]').find('img.tag_loader').show();
  });
  $('form[name="add"]').ajaxForm(function(response) {
    $('form[name="add"] img.tag_loader').hide();
    if (response.status == 'success')
    {
      $('form[name="add"]').find('ul.error_list').remove();
      $('ul.elements').prepend(response.html);
      $('form[name="add"] input[type="text"]').val('');
      $('div#element_add_box').slideUp();
      $('a#element_add_link').show();
      if ($('form[name="search"]').length)
      {
        $('form[name="search"]').slideDown();
      }
    }
    else if (response.status == 'error')
    {
      $('form[name="add"]').find('ul.error_list').remove();
      ul_errors = $('<ul>').addClass('error_list');
      
      for (i in response.errors)
      {
        ul_errors.append($('<li>').append(response.errors[i]));
      }
      
      $('form[name="add"]').prepend(ul_errors);
    }
    
    return false;
  });
  
  // Check périodique 
  // TODO.

 /////////////////////
 // Filtre par tags (show, favorite)
 function refresh_elements_with_tags_selected(link)
  {
    
    
    // Puis on fait notre rekékéte ajax.
    $('ul.elements').html('');
    $('div.no_elements').hide();
    $('img.elements_more_loader').show();
    $.getJSON($('input#get_elements_url').val()+'/'+array2json(tags_ids), function(response){
      
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
   
  $('ul#favorite_tags a.tag').click(function(){
    // Ensuite on l'active ou le désactive'
    if ($(this).hasClass('active'))
    {
      $(this).removeClass('active');
    }
    else
    {
      $(this).addClass('active');
    }
    
    // On construit notre liste de tags
    tags_ids = new Array();
    $('ul#favorite_tags a.tag.active').each(function(index){
      id = str_replace('#', '', $(this).attr('href'));
      tags_ids[id] = id;
    });
    
    // On adapte le lien afficher plus de résultats
    a_more = $('a.elements_more');
    a_more.attr('href', $('input#more_elements_url').val()+'/'+array2json(tags_ids));
    
    return check_timelaps_and_find_with_tags($(this), new Date().getTime(), false);
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
   
 });