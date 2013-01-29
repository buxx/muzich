$(document).ready(function(){
   
   $('#registration_link').click(function(){
     $('#registration_box').slideDown("slow");
     $('#login_box').slideUp("slow");
     $(this).hide();
     $('#login_link').show();
     return false;
   });
   
   $('#login_link').click(function(){
     $('#login_box').slideDown("slow");
     $('#registration_box').slideUp("slow");
     $('#registration_link').show();
     $(this).hide();
     return false;
   });
   
   
 });