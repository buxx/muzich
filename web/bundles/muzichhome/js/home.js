$(document).ready(function(){
   
   // Ouverture de la zone "ajouter un element""
   $('#element_add_link').click(function(){
     $('#element_add_box').slideDown("slow");
     $('#element_add_link').hide();
     $('form[name="search"]').slideUp();
     // Au cas ou firefox garde la valeur step 2:
        $('input#form_add_step').val('1');
     return false;
   });   
   
   // Fermeture de la zone "ajouter un element""
   $('#element_add_close_link').click(function(){
     $('#element_add_box').slideUp("slow");
     $('#element_add_link').show();
     $('form[name="search"]').slideDown();
     //form_add_reinit();
     // copie du contenu de la fonction ci dessus, arrive pas a l'appeler ... huh
     $('div#element_add_box').slideUp();
    $('div#form_add_first_part').show();
    $('div#form_add_second_part').hide();
    $('ul#form_add_prop_tags').hide();
    $('ul#form_add_prop_tags_text').hide();
    $('input#element_add_url').val('');
    $('input#element_add_name').val('');
     
     return false;
   }); 
   
   // Bouton suivre
   $('div.show_options a.following').live({
    mouseenter:
      function()
      {
        $(this).html(string_follow_stop);
      },
    mouseleave:
      function()
      {
       $(this).html(string_follow_following);
      }
    }
  );
    
  $('div.show_options a.follow_link').live('click', function(){
    link = $(this);
    $.getJSON(link.attr('href'), function(response) {
      
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
       if (response.status == 'success')
       {
         if (response.following)
         {
           link.html(string_follow_following);
           link.removeClass('notfollowing');
           link.addClass('following');
         }
         else
         {
           link.html(string_follow_follow);
           link.addClass('notfollowing');
           link.removeClass('following');
         }
       }
     });
     return false;
  });
   
 });