/*
 * Scripts de Muzi.ch
 * Rédigé et propriété de Sevajol Bastien (http://www.bux.fr)
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

function json_to_array(json_string)
{
  if (json_string.length)
  {
    return eval("(" + json_string + ")");
  }
  return new Array();
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
  $('a.element_embed_open_link').live("click", function(){
     $(this).parent().parent('li.element').find('a.element_embed_open_link').hide();
     $(this).parent().parent('li.element').find('a.element_embed_close_link').show();
     $(this).parent().parent('li.element').find('div.element_embed').show();
     return false;
  });

  // Fermeture du embed si demandé
  $('a.element_embed_close_link').live("click", function(){
     $(this).parent().parent('li.element').find('a.element_embed_open_link').show();
     $(this).parent().parent('li.element').find('a.element_embed_close_link').hide();
     $(this).parent().parent('li.element').find('div.element_embed').hide();
     return false;
  });

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
  $('form[name="element_search_form"] input[type="submit"]').click(function(){
    $('ul.elements').html('');
    $('div.no_elements').hide();
    $('img.elements_more_loader').show();
  });
  
  $('form[name="element_search_form"]').ajaxForm(function(response) { 
    
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
     
     $('ul.tagbox input[type="text"]').val(tag_box_input_value);
    
  }); 
   
 });
 
 