$(document).ready(function(){
   
   // Ouverture de la zone "ajouter un element""
   $('#element_add_link').click(function(){
     $('#element_add_box').slideDown("slow");
     $('#element_add_link').hide();
     $('form[name="search"]').slideUp();
     return false;
   });   
   
   // Fermeture de la zone "ajouter un element""
   $('#element_add_close_link').click(function(){
     $('#element_add_box').slideUp("slow");
     $('#element_add_link').show();
     $('form[name="search"]').slideDown();
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