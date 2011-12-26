$(document).ready(function(){
   
   $('#element_add_link').click(function(){
     $('#element_add_box').slideDown("slow");
     $('#element_add_link').hide();
     return false;
   });   
   
   $('#element_add_close_link').click(function(){
     $('#element_add_box').slideUp("slow");
     $('#element_add_link').show();
     return false;
   }); 
   
   
 });