/* Librairie Autoplay pour muzi.ch  */
$(document).ready(function(){
 
  /*
   * Section commune
   */
  
  // Liste de données pour la lecture
  var autoplay_list = new Array;
  // object player
  var autoplay_player = null;
  // Ritournelle pour le lecteur soundcloud (une seule variable sinon bug)
  var autoplay_player_soundcloud = null;
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
    
    
    ////// TMP to check
    if (autoplay_player_soundcloud)
    {
      autoplay_player_soundcloud.pause();
    }
    $('div#autoplay_player_soundcloud').hide();
    
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
            
            if (autoplay_list[step].element_type == 'soundcloud.com')
            {
              soundcloud_create_player(autoplay_list[step].element_ref_id, autoplay_list[step].element_normalized_url);
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
    if (autoplay_player_soundcloud)
    {
      $('div#autoplay_player_soundcloud').hide();
      autoplay_player_soundcloud.pause();
    }
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
  $('a#autoplay_previous').click(function(){autoplay_previous();});
  // bouton suivant
  $('a#autoplay_next').click(function(){autoplay_next();});
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
   *
   *
   *
   * Fonction youtube.com et youtu.be
   * 
   * 
   * 
   */
    
  // Création du lecteur FLASH youtube
  function youtube_create_player(ref_id)
  {
    var playerapiid = "ytplayerapiid";
    var params = {allowScriptAccess: "always"};
    var atts = {id: autoplay_player_id};
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
  
  /*
   * 
   * 
   * Fonctions soundcloud
   * 
   * 
   * 
   */
  
  function soundcloud_create_player(ref_id, ref_url)
  {
    
    // Variable dans lequelle on met l'index dela track lu précedamment
    var index_track_previous = 0;
     
    if (!$('iframe#soundcloud_iframe_player').length)
    {
      $('div#autoplay_player_soundcloud').show();

        // TODO: on garde ?
        SC.initialize({
          client_id: '39946ea18e3d78d64c0ac95a025794e1'
        });

      // Aucun lecteur soundcloud n'a été ouvert pour le moment
      $('#autoplay_player_soundcloud').html(
        '<iframe id="soundcloud_iframe_player" src="http://w.soundcloud.com/player/?url='
          +ref_url+'&show_artwork=false&auto_play=true" width="100%" '
          +'height="370" scrolling="no" frameborder="no"></iframe>'
      );

      //console.debug(document.getElementById('sc-widget_'+ref_id));
      var widgetIframe = document.getElementById('soundcloud_iframe_player');
      autoplay_player_soundcloud = SC.Widget(widgetIframe);

      // Lorsque le lecteur est prêt on lance la lecture
      autoplay_player_soundcloud.bind(SC.Widget.Events.READY, function ()
      {
        autoplay_player_soundcloud.play();
        $('img#autoplay_loader').hide();

      });

      autoplay_player_soundcloud.bind(SC.Widget.Events.PLAY, function ()
      {
        // Lorsque le lecteur commence une lecture
        // On garde en mémoire l'index de la lecture en cours
        autoplay_player_soundcloud.getCurrentSoundIndex(function(value){
            index_track_previous = value;
        });
      });

      // Lorsque le lecteur a terminé sa lecture, on passe au suivant
      autoplay_player_soundcloud.bind(SC.Widget.Events.FINISH, function ()
      {

        // Cette variable contient le nombre de pistes dans la liste de lecture
        var track_count = 1;
        autoplay_player_soundcloud.getSounds(function(value){
          // On récupère le nomre de pistes
          track_count = value.length;
        });

        autoplay_player_soundcloud.getCurrentSoundIndex(function(value){

          // Si la index_track_previous est la même maintenant que la piste
          // est terminé, c'est que l'on est arrivé en fin de liste.
          // Cependant, si c'est une liste avec une piste unique, on passe 
          // tout de suite a la suite
          if (value == index_track_previous || track_count == 1)
          {
            autoplay_next();
          }

          // Sinon on prend al nouvelle valeur
          index_track_previous = value;

        });

      });

    }
    else
    {
      // Le lecteur iframe existait déjà
    $('div#autoplay_player_soundcloud').show();
      autoplay_player_soundcloud.load(ref_url+'&show_artwork=false&auto_play=true');
      //autoplay_player_soundcloud.play();
    $('img#autoplay_loader').hide();
    }
         
  }
  
});
