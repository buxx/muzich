/* Librairie Autoplay pour muzi.ch  */
$(document).ready(function(){
 
  /*
   * Section commune
   */
  
  // Liste de données pour la lecture
  var autoplay_list = new Array;
  // object player
  var autoplay_player = null;
  // identifiant de la division du lecteur
  var autoplay_player_div_id = "autoplay_player";
  // identifiant du lecteur
  var autoplay_player_id     = "autoplay_player_id";
  // étape de lecture, on commence naturellement a 0
  var autoplay_step = 0;
  
  // En cas de click sur un bouton de lecture
  $('a#autoplay_launch').click(function(){
    
    // On ouvre la boite de dialogue pendant la demande ajax
    open_popin_dialog('autoplay');
    $('img#autoplay_loader').show();
    
    $.getJSON($(this).attr('href'), function(response) {
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }
      
      if (response.status == 'success')
      {
        // On récupère la liste d'élèments
        autoplay_list = response.data;
        console.debug(autoplay_list);
        autoplay_run(0);
      }
      
    });
    return false;
  });
  
  // Lancement de l'élèment suivant
  function autoplay_run(step)
  {
    // En premier lieu on réinitialise le lecteur en détruisant le dom qui a
    // pu être créé par la lecture précedente.
    $('div#'+autoplay_player_div_id+'_container').html('<div id="'+autoplay_player_div_id+'"></div>');
    $('#autoplay_noelements_text').hide();
    $('li#autoplay_element_container').html('');
    $('img#autoplay_loader').show();
    
    if (autoplay_list.length)
    {
    
      if (array_key_exists(step, autoplay_list))
      {
        
        // Récupération du dom d'un élement
        $.getJSON(url_element_dom_get_one_autoplay+'/'+autoplay_list[step].element_id, function(response) {
          if (response.status == 'mustbeconnected')
          {
            $(location).attr('href', url_index);
          }

          if (response.status == 'success')
          {
            // On récupère la liste d'élèments
            $('li#autoplay_element_container').html(response.data);
            
            // Youtube case
            if (autoplay_list[step].element_type == 'youtube.com' || autoplay_list[step].element_type == 'youtu.be')
            {
              youtube_create_player(autoplay_list[step].element_ref_id);
            }
            
          }

        });
        
        
      }
    
    }
    else
    {
      autoplay_display_nomore();
    }
  }
  
  function autoplay_display_nomore()
  {
    $('div#'+autoplay_player_div_id+'_container').html('<div id="'+autoplay_player_div_id+'"></div>');
    $('li#autoplay_element_container').html('');
    $('#autoplay_noelements_text').show();
    $('img#autoplay_loader').hide();
  }
  
  // Avancer d'un élelement dans la liste
  function autoplay_next()
  {
    autoplay_step++;
    if (array_key_exists(autoplay_step, autoplay_list))
    {
      autoplay_run(autoplay_step);
    }
    else
    {
      autoplay_display_nomore();
      autoplay_step = autoplay_list.length;
    }
  }
  
  // Reculer d'un élement dans la liste
  function autoplay_previous()
  {
    autoplay_step--;
    if (array_key_exists(autoplay_step, autoplay_list))
    {
      autoplay_run(autoplay_step);
    }
    else
    {
      autoplay_display_nomore();
      autoplay_step = -1;
    }
  }
  
  // bouton précedent
  $('a#autoplay_previous').click(function(){ autoplay_previous(); });
  // bouton suivant
  $('a#autoplay_next').click(function(){ autoplay_next(); });
  // Fermeture de la lecture auto
  $('a#autoplay_close').click(function(){
    // Fond gris
    $('#fade').fadeOut(1000, function(){$('#fade').remove();});
    // On cache le lecteur
    $('#autoplay').hide();
    // On vide le dom du lecteur
    $('div#'+autoplay_player_div_id+'_container').html('<div id="'+autoplay_player_div_id+'"></div>');
  });
   
  
  /*
   * Fonction youtube.com et youtu.be
   */
    
  // Création du lecteur FLASH youtube
  function youtube_create_player(ref_id)
  {
    var playerapiid = "ytplayerapiid";
    var params = { allowScriptAccess: "always" };
    var atts = { id: autoplay_player_id };
    swfobject.embedSWF(
      "http://www.youtube.com/v/"+ref_id+"?enablejsapi=1&playerapiid="+playerapiid+"&version=3",
      autoplay_player_div_id,
      "425",
      "356",
      "8",
      null,
      null,
      params,
      atts
    );
  }
  
  // Fonction appelé par l'ActionScript (flash) du lecteur youtube quand il est prêt
  window.onYouTubePlayerReady = function()
  {
    autoplay_player = document.getElementById(autoplay_player_id);
    autoplay_player.addEventListener("onStateChange", "youtube_StateChange");
    $('img#autoplay_loader').hide();
    youtube_play();
  }
  
  // Fonction appelé par le lecteur youtube quand il change d'état
  window.youtube_StateChange = function(newState)
  {
    // Lorsque la lecture est terminé
    if (newState === 0)
    {
      autoplay_next();
    }
  }

  // Lecture
  function youtube_play()
  {
    if (autoplay_player)
    {
      autoplay_player.playVideo();
    }
  }
  
});