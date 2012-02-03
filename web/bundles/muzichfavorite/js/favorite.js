$(document).ready(function(){
   
  function refresh_elements_with_tags_selected(link)
  {
    
    
    // Puis on fait notre rekékéte ajax.
    $('ul.elements').html('');
    $('div.no_elements').hide();
    $('img.elements_more_loader').show();
    $.getJSON($('input#get_elements_url').val()+'/'+array2json(tags_ids), function(response){
      
      $('ul.elements').html(response.html);
      
      if (response.count)
       {
         $('img.elements_more_loader').hide();
         $('span.elements_more').show();
         $('a.elements_more').show();
       }
    });
    
    return false;
  }
   
  $('ul#favorite_tags a.tag').click(function(){
    // Ensuite on l'active ou le désactive'
    if ($(this).hasClass('active'))
    {
      $(this).removeClass('active');
    }
    else
    {
      $(this).addClass('active');
    }
    
    // On construit notre liste de tags
    tags_ids = new Array();
    $('ul#favorite_tags a.tag.active').each(function(index){
      id = str_replace('#', '', $(this).attr('href'));
      tags_ids[id] = id;
    });
    
    // On adapte le lien afficher plus de résultats
    a_more = $('a.elements_more');
    a_more.attr('href', $('input#more_elements_url').val()+'/'+array2json(tags_ids));
    
    return check_timelaps_and_find_with_tags($(this), new Date().getTime(), false);
  });
  
  last_keypress = 0;
  function check_timelaps_and_find_with_tags(link, time_id, timed)
  {
    if (!timed)
    {
      // C'est une nouvelle touche (pas redirigé) on lui donne un id
      // et on met a jour l'id de la dernière pressé
      last_keypress = new Date().getTime(); 
      var this_time_id = last_keypress;
    }
    else
    {
      // Si elle a été redirigé, on met son id dans cette variable
      var this_time_id = time_id;
    }
    
    // C'est une touche redirigé dans le temps qui a été suivit d'une autre touche
    if (time_id != last_keypress && timed)
    {
      // elle disparait
    }
    else
    {
      //
      if ((new Date().getTime() - last_keypress) < 800 || timed == false)
      {
        // Si elle vient d'être tapé (timed == false) elle doit attendre (au cas ou une autre touche soit tapé)
        // Si c'est une redirigé qui n'a pas été remplacé par une nouvelle lettre
        // elle doit attendre au cas ou soit pressé.
        setTimeout(function(){check_timelaps_and_find_with_tags(link, this_time_id, true)}, 900);
      }
      else
      {
        // il n'y a plus a attendre, on envoie la demande de tag.
        return refresh_elements_with_tags_selected(link);
      }
    }
    
    return null;
  }
   
});