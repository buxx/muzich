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

$(document).ready(function(){
  
   // Affichage un/des embed
   $('a.element_embed_open_link').click(function(){
     $(this).parent().parent('li.element').find('a.element_embed_open_link').hide();
     $(this).parent().parent('li.element').find('a.element_embed_close_link').show();
     $(this).parent().parent('li.element').find('div.element_embed').show();
     return false;
   });

   // Fermeture du embed si demandé
   $('a.element_embed_close_link').click(function(){
     $(this).parent().parent('li.element').find('a.element_embed_open_link').show();
     $(this).parent().parent('li.element').find('a.element_embed_close_link').hide();
     $(this).parent().parent('li.element').find('div.element_embed').hide();
     return false;
   });
   
   // Bouton de personalisation du filtre
   // pour le moment ce ne sotn que des redirection vers des actions
   $('.tags_prompt input.clear').click(function(){
     $(location).attr('href', $('input.filter_clear_url').val());
   });
   $('.tags_prompt input.mytags').click(function(){
     $(location).attr('href', $('input.filter_mytags_url').val());
   });
   
 });
 
 