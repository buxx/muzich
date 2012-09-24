/*  */

$(document).ready(function(){
    
  $('form#address_update input[type="submit"]').click(function(){
    $('form#address_update img.loader').show();
  });
    
  $('form#address_update').ajaxForm(function(response){
      
      $('form#address_update img.loader').hide();
      form = $('form#address_update');
      
      form.find('ul.error_list').remove();
      if (response.status == 'error')
      {
        ul_errors = $('<ul>').addClass('error_list');

        for (i in response.errors)
        {
          ul_errors.append($('<li>').append(response.errors[i]));
        }

        form.prepend(ul_errors);
      }
      
  });
   
});