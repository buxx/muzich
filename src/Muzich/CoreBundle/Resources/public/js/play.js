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
  // Pour savoir si le lecteur générique a déjà été lancé
  var autoplay_generic_player_launched = false;
  // contient l'objet playlist du lecteur générique
  var autoplay_generic_player_playlist = null;
  // contient le nombre de tracks dans la playlist du lecteur générique
  var autoplay_generic_player_playlist_count = 0;
  // Index dernière piste lu par lecteur générique
  var autoplay_generic_player_index_track_previous = 0;
  // pour notre hack: les données previous track, current track sont
  // interprété différemment lorsque on est a notre 2ème lecture ...
  var autoplay_generic_player_first_launch = null;
  
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
        
        // hack
          autoplay_generic_player_playlist = new jPlayerPlaylist({
            jPlayer: "#jquery_jplayer_1",
            cssSelectorAncestor: "#jp_container_1"
          },
          []
          , {
             playlistOptions: {
              autoPlay: true,
              enableRemoveControls: true
            },
            swfPath: "/jplayer/js",
            supplied: "mp3",
            wmode: "window"
          });
        
        if (response.data.length)
        {
          // On récupère la liste d'élèments
          autoplay_list = response.data;
          // On renseigne l'id de l'élèment en cours de demande de lecture
          autoplay_last_element_id = autoplay_list[0].element_id;
          // On par sur l'index premier de la liste de lecture
          autoplay_step = 0;
          // On lance la lecture auo
          autoplay_run(autoplay_step, false);
        }
        else
        {
          autoplay_display_nomore();
        }
      }
      
    });
    return false;
  });
  
  /**
   * Cette fonction est chargé de récupérer le bloc élent avec le nom, la note etc ...
   * @param {int} element_id
   * @param {boolean} timed
   */
  function autoplay_load_element(element_id, timed)
  {
    // Si on a pas retardé cette demande
    if (!timed)
    {
      // Alors il faut que l'on attende un peu, au cas ou il y avait plein de clics simultanés
      $('#autoplay_element_loader').show();
      $('li#autoplay_element_container').html('');
      // On relance le bousin dans x millisecondes
      setTimeout(autoplay_load_element, 500, element_id, true);
    }
    else
    {
      // On ne lance la procédure que si il n'y as pas eu de nouvelle demande depuis
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
  
  /**
   * Lancement d'une lecture d'élément
   * @param {int} step
   * @param {boolean} timed
   */
  function autoplay_run(step, timed)
  {
    
    if (!timed)
    {     
      // En premier lieu on réinitialise le lecteur en détruisant le dom qui a
      // pu être créé par la lecture précedente.
      autoplay_clean_for_player_creation();
      
      // Pause des lecteurs potentiels
      autoplay_pause_all_players();
      
      // On la relance au cas ou il y a eu de multiples clicks
      setTimeout(autoplay_run, 500, step, true);
    }
    else if (autoplay_last_element_id == autoplay_list[step].element_id)
    {
      
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
  
          if (autoplay_list[step].element_type == 'jamendo.com')
          {
            autoplay_load_element(autoplay_list[step].element_id, false);
            jamendo_create_player(autoplay_list[step].element_id);
          }
          
        }
      
      }
      else
      {
        autoplay_display_nomore();
      }
    
    }
  }
  
  /**
   * Nettoyage du lecteur autoplay pour l'ouverture d'un nouvel élément
   *
   */
  function autoplay_clean_for_player_creation(clean_element)
  {
    autoplay_pause_all_players();
    
    $('div#'+autoplay_player_div_id+'_container').html('<div id="'+autoplay_player_div_id+'"></div>');
    $('#autoplay_noelements_text').hide();
    $('div#autoplay_player_generic').hide();
    
    if (clean_element)
    {
      $('li#autoplay_element_container').html('');
    }
    $('img#autoplay_loader').show();
  }
  
  /**
   * Fonction qui regroupe les méthode de mise en pause de tout les lecteurs
   *
   */
  function autoplay_pause_all_players()
  {
    // Pas youtube car on détruit son lecteur
    soundcloud_stop_player();
    jamendo_stop_player();
  }
  
  /**
   * Affichage du message indiquant qu'il n'y a plus rien a lire
   */
  function autoplay_display_nomore()
  {
    autoplay_no_more = true;
    $('div#'+autoplay_player_div_id+'_container').html('<div id="'+autoplay_player_div_id+'"></div>');
    $('li#autoplay_element_container').html('');
    $('#autoplay_noelements_text').show();
    $('img#autoplay_loader').hide();
    $('img#autoplay_element_loader').hide();
    if (autoplay_player_soundcloud)
    {
      $('div#autoplay_player_soundcloud').hide();
      autoplay_player_soundcloud.pause();
    }
  }
  
  // Avancer d'un éleement dans la liste
  function autoplay_next()
  {
    autoplay_no_more = false;
    autoplay_step++;
    if (array_key_exists(autoplay_step, autoplay_list))
    {
      autoplay_last_element_id = autoplay_list[autoplay_step].element_id;
      autoplay_run(autoplay_step, false);
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
      autoplay_run(autoplay_step, false);
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
    // TODO: Pour le moment c'est surtout pour détruire la variable du lecteur générique
    autoplay_clean_for_player_creation();
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
  
  /*
   * 
   *
   * 
   * Jamendo.com (utilise le lecteur générique)
   * 
   * 
   */
  
  function jamendo_create_player(element_id)
  {
    autoplay_generic_player_load(element_id);
  }
  
  function jamendo_stop_player()
  {
    autoplay_generic_player_stop();
  }
  
  
  /*
   * 
   * 
   * Lecteur générique
   * 
   */
  
   /**
   * Objet son
   * @param {string} title Nom du morceau qui sera affiché dans la liste de lecture
   * @param {string} mp3 adresse du flux sonore
   */
   // EN COMMENTAIRE CAR CODE COPIE VERS play2.js
  //function GenericSong(title, mp3)
  //{
  //  this.title = title;
  //  this.mp3   = mp3;
  //}
  
  /**
   * Fonction de lecture d'un élèment avec le lecteur générique
   *
   * @param {int} element_id identifiant internet de l'élément
   */
  function autoplay_generic_player_load(element_id)
  {
    // On doit récupérer les informations pour la lecture streaming
    $.getJSON(url_element_get_stream_data+'/'+element_id, function(response) {
          
      if (response.status == 'mustbeconnected')
      {
        $(location).attr('href', url_index);
      }

      if (response.status == 'success')
      {
        if (response.data)
        {
          
          // Pour stocker localement les données de notre base
          var autoplay_generic_playlist_data = new Array;
          
          for(var i = 0; i < response.data.length; i++)
          {
            // On construit un objet son pour constituer une bibliothèque (autoplay_generic_playlist_data)
            var song = new GenericSong(response.data[i].name, response.data[i].url);
            autoplay_generic_playlist_data[i] = song;
          }
          
          // On garde en mémoire le nombre de piste que l'on à, ca nous sera utlie pour
          // savoir si on est en fin de lecture par exemple
          autoplay_generic_player_playlist_count = autoplay_generic_playlist_data.length;
          
          $('div#autoplay_player_generic').show();
          
          // On a besoin de savoir si c'est la première fois que l'on charge ce lecteur
          if (autoplay_generic_player_first_launch === null)
          {
            autoplay_generic_player_first_launch = true;
          }
          else if (autoplay_generic_player_first_launch === true)
          {
            autoplay_generic_player_first_launch = false;
          }
          
          // On construit l'objet de jPlayerv avec une playlist
          autoplay_generic_player_playlist = new jPlayerPlaylist({
            jPlayer: "#jquery_jplayer_1",
            cssSelectorAncestor: "#jp_container_1"
          },
          // On stransmet la plsylist a cette position
          autoplay_generic_playlist_data
          , {
             playlistOptions: {
              autoPlay: true,
              enableRemoveControls: true
            },
            swfPath: "/jplayer/js",
            supplied: "mp3",
            wmode: "window"
          });
          
          // On ne bind qu'un seule fois les functions
          if (autoplay_generic_player_launched == false)
          {
            
            // On garde en mémoire la piste lu en ce moment
            $("#jquery_jplayer_1").bind($.jPlayer.event.play, function(event) { 
              autoplay_generic_player_index_track_previous = autoplay_generic_player_playlist.current;
              $('img#autoplay_loader').hide();
            });
            
            $("#jquery_jplayer_1").bind($.jPlayer.event.ended, function(event) { 
              
              // Si la index_track_previous est la même maintenant que la piste
              // est terminé, c'est que l'on est arrivé en fin de liste.
              // Cependant, si c'est une liste avec une piste unique, on passe 
              if (
                  (
                    // Si c'est la première fois il faut qu'après la lecture ce soit les mêmes index
                    ( autoplay_generic_player_first_launch &&
                      autoplay_generic_player_index_track_previous == autoplay_generic_player_playlist.current )
                    ||
                    // Si c'est pas la première fois, on obtient le current avant de passer a al suitante
                    // du coup on peut regarder si on était a la dernière piste
                    ( !autoplay_generic_player_first_launch &&
                      autoplay_generic_player_playlist.current == autoplay_generic_player_playlist_count -1 )
                  )
                || autoplay_generic_player_playlist_count == 1
              )
              {
                console.log('next...')
                autoplay_next();
              }
              
            });
            
            $("#jquery_jplayer_1").bind($.jPlayer.event.error, function(event)
            {
              autoplay_next();
            });
          
          }
          
          autoplay_generic_player_launched = true;
        }
        else
        {
          autoplay_next(); 
        }
      }

    });
  }
  
  function autoplay_generic_player_stop()
  {
    $("#jquery_jplayer_1").jPlayer("destroy");
  }
  
});
