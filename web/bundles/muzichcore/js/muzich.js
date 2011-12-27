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
   $(this).parent('li.element').find('a.element_embed_open_link').hide();
   $(this).parent('li.element').find('a.element_embed_close_link').show();
   $(this).parent('li.element').find('div.element_embed').show();
   return false;
 });

 // Fermeture du embed si demandé
 $('a.element_embed_close_link').click(function(){
   $(this).parent('li.element').find('a.element_embed_open_link').show();
   $(this).parent('li.element').find('a.element_embed_close_link').hide();
   $(this).parent('li.element').find('div.element_embed').hide();
   return false;
 });

 });