$(document).ready(function(){
   
  $('ul#moderate_tags li.tag a.accept, ul#moderate_tags li.tag a.refuse').click(function(){
    link = $(this);
    $.getJSON($(this).attr('href'), function(response) {
     
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'success')
      {
        link.parent('li').remove();
      }
      
      if (response.status == 'error')
      {
        alert(response.message);
      }
      
    });
      
    return false;
  });
  
   
  $('ul#moderate_tags li.tag a.replace').click(function(){
    link = $(this);
    
    newtag = link.parent('li').find('input.tagBox_tags_ids').val();
    
    $.getJSON($(this).attr('href')+'/'+newtag, function(response) {
     
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'error')
      {
        alert(response.message);
      }
      
      if (response.status == 'success')
      {
        link.parent('li').remove();
      }
      
    });
      
    return false;
  });
   
});