$(document).ready(function(){
   // Ouverture de la zone "ajouter un group""
   $('#group_add_link').click(function(){
     $('#group_add_box').slideDown("slow");
     $('#group_add_link').hide();
     $('#group_add_close_link').show();
     return false;
   });   
   
   // Fermeture de la zone "ajouter un group""
   $('#group_add_close_link').click(function(){
     $('#group_add_box').slideUp("slow");
     $('#group_add_link').show();
     $(this).hide();
     return false;
   });
 });