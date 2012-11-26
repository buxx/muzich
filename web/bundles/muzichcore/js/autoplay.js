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
  // On utilise cette variable pour savoir quel est l'élélement choisis en 
  // dernier (cas du brute click)
  var autoplay_last_element_id = null;
  // Pour savoir si on est en bout de ligne
  var autoplay_no_more = false;
  
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
        autoplay_last_element_id = autoplay_list[0].element_id;
        autoplay_step = 0;
        autoplay_run(0);
      }
      
    });
    return false;
  });
  
  function autoplay_load_element(element_id, timeouted)
  {
    // On vérifie ici que c'est le dernier élement demandé, ca evite de multiples
    // requetes en cas de brute click sur les flèches.
    if (!timeouted)
    {
      $('#autoplay_element_loader').show();
      $('li#autoplay_element_container').html('');
      setTimeout(autoplay_load_element, 1000, element_id, true);
    }
    else
    {
      if (autoplay_last_element_id == element_id)
      {
        $.getJSON(url_element_dom_get_one_autoplay+'/'+element_id, function(response) {
          
          $('#autoplay_element_loader').hide();
      
          if (response.status == 'mustbeconnected')
          {
            $(location).attr('href', url_index);
          }

          if (response.status == 'success' && autoplay_last_element_id == element_id)
          {
            if (!autoplay_no_more)
            {
              // On récupère la liste d'élèments
              $('li#autoplay_element_container').html(response.data);
            }
            else
            {
              $('li#autoplay_element_container').html('');
            }
          }

        });
      }
      
    }
  }
  
  // Lancement de l'élèment suivant
  function autoplay_run(step)
  {
    // En premier lieu on réinitialise le lecteur en détruisant le dom qui a
    // pu être créé par la lecture précedente.
    autoplay_clean_for_player_creation();
    
    // Pause des lecteurs potentiels
    autoplay_pause_all_players();
    
    if (autoplay_list.length)
    {
    
      if (array_key_exists(step, autoplay_list))
      {
        
        // Youtube case
        if (autoplay_list[step].element_type == 'youtube.com' || autoplay_list[step].element_type == 'youtu.be')
        {
          autoplay_load_element(autoplay_list[step].element_id, false);
          youtube_create_player(autoplay_list[step].element_ref_id);
        }

        if (autoplay_list[step].element_type == 'soundcloud.com')
        {
          autoplay_load_element(autoplay_list[step].element_id, false);
          soundcloud_create_player(autoplay_list[step].element_ref_id, autoplay_list[step].element_normalized_url);
        }
        
      }
    
    }
    else
    {
      autoplay_display_nomore();
    }
  }
  
  function autoplay_clean_for_player_creation(clean_element)
  {
    autoplay_pause_all_players();
    
    $('div#'+autoplay_player_div_id+'_container').html('<div id="'+autoplay_player_div_id+'"></div>');
    $('#autoplay_noelements_text').hide();
    if (clean_element)
    {
      $('li#autoplay_element_container').html('');
    }
    $('img#autoplay_loader').show();
  }
  
  function autoplay_pause_all_players()
  {
    soundcloud_stop_player();
  }
  
  function autoplay_display_nomore()
  {
    autoplay_no_more = true;
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
    autoplay_no_more = false;
    autoplay_step++;
    if (array_key_exists(autoplay_step, autoplay_list))
    {
      autoplay_last_element_id = autoplay_list[autoplay_step].element_id;
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
    autoplay_no_more = false;
    autoplay_step--;
    if (array_key_exists(autoplay_step, autoplay_list))
    {
      autoplay_last_element_id = autoplay_list[autoplay_step].element_id;
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
    // Plus rien de dois être lu
    autoplay_pause_all_players();
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
    // Préparation du terrain
    autoplay_clean_for_player_creation();
    
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
    autoplay_player.addEventListener("onError", "youtube_error");
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
  
  // Fonction appelé par le lecteur youtube quand il y a une erreur
  window.youtube_error = function(newState)
  {
    autoplay_next();
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
    
    // Préparation du terrain
    autoplay_clean_for_player_creation();
    
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

      SC.get(ref_url, function(track, error) {
        if (error) 
        { 
          // En cas d'erreur on passe a al suivante
          autoplay_next(); 
        }
        else
        {
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
      });

      

    }
    else
    {
      SC.get(ref_url, function(track, error) {
        if (error) 
        { 
          // En cas d'erreur on passe a al suivante
          console.log('Not found!');
          autoplay_next(); 
        }
        else
        {

          // Le lecteur iframe existait déjà
          $('div#autoplay_player_soundcloud').show();
            autoplay_player_soundcloud.load(ref_url+'&show_artwork=false&auto_play=true');
            //autoplay_player_soundcloud.play();
          $('img#autoplay_loader').hide();

        }
      });
    }
         
  }
  
  function soundcloud_stop_player()
  {
    if (autoplay_player_soundcloud)
    {
      autoplay_player_soundcloud.pause();
    }
    $('div#autoplay_player_soundcloud').hide();
  }
  
});
