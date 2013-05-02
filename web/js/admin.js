$(document).ready(function(){
  
  $('a.moderate_accept, a.moderate_refuse').click(function(){
    $.getJSON($(this).attr('href'), function(response) {
      // Do query ...
    });
    $(this).parents('tr.list_trow').remove();
    $('div.tooltip').remove();
    return false;
  });
  
});