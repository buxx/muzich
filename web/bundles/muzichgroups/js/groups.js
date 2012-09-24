$(document).ready(function(){
   
   // Ouverture de la zone "ajouter un group""
   $('#group_add_link').click(function(){
     $('#group_add_box').slideDown("slow");
     $('#group_add_link').hide();
     return false;
   });   
   
   // Fermeture de la zone "ajouter un group""
   $('#group_add_close_link').click(function(){
     $('#group_add_box').slideUp("slow");
     $('#group_add_link').show();
     return false;
   }); 
   
 });