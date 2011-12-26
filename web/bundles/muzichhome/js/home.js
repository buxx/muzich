$(document).ready(function(){
   
   // Ouverture de la zone "ajouter un element""
   $('#element_add_link').click(function(){
     $('#element_add_box').slideDown("slow");
     $('#element_add_link').hide();
     return false;
   });   
   
   // Fermeture de la zone "ajouter un element""
   $('#element_add_close_link').click(function(){
     $('#element_add_box').slideUp("slow");
     $('#element_add_link').show();
     return false;
   }); 
   
//   // Affichage du/des embed si existant
//   $('li.element').hover(function(){     
//     $(this).find('div.element_embed').each(function(i){ 
//       if ($(this).css('display') == 'none')
//       {
//         $(this).show();        
//       }
//     });
//   }, function(){});

   // Affichage du/des embed
   $('a.element_embed_open_link').click(function(){
     $('a.element_embed_open_link').hide();
     $('a.element_embed_close_link').show();
     $(this).parent('li.element').find('div.element_embed').show();
     return false;
   });
   
   // Fermeture du embed si demandé
   $('a.element_embed_close_link').click(function(){
     $('a.element_embed_open_link').show();
     $('a.element_embed_close_link').hide();
     $(this).parent('li.element').find('div.element_embed').hide();
     return false;
   });
   
 });