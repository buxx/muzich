/* Librairie Autoplay pour muzi.ch  */
$(document).ready(function(){
 
  /*
   * Section commune
   */
  
  var autoplay_list = new Array;
  var autoplay_player = null;
  var autoplay_player_div_id = "autoplay_player";
  var autoplay_player_id     = "autoplay_player_id";
  var autoplay_step = 0;
  
  $('a#autoplay_launch').click(function(){
    
    // on fake l'ajax pour les tests
    
    var firdtvidz = new Array;
    firdtvidz['element_ref_id'] = 'tq4DjQK7nsM';
    firdtvidz['element_type']   = 'youtu.be';
    firdtvidz['element_id']     = '99989';
    firdtvidz['element_name']   = 'Ed Cox - La fanfare des teuffeurs (Hardcordian)';
    
    var secondvidz = new Array;
    secondvidz['element_ref_id'] = 'bIAFB4vRdGw';
    secondvidz['element_type']   = 'youtube.com';
    secondvidz['element_id']     = '2345';
    secondvidz['element_name']   = 'Babylon Pression - Des Tasers et des Pauvres';
    
    autoplay_list[0] = firdtvidz;
    autoplay_list[1] = secondvidz;
  
    open_popin_dialog('autoplay');
    autoplay_run(0);
  });
  
  function autoplay_run(step)
  {
    $('div#'+autoplay_player_div_id+'_container').html('<div id="'+autoplay_player_div_id+'"></div>');
    
    if (autoplay_list[step].element_type == 'youtube.com' || autoplay_list[step].element_type == 'youtu.be')
    {
      $('img#autoplay_loader').show();
      $('div#autoplay_title').text(autoplay_list[step].element_name);
      youtube_create_player(autoplay_list[step].element_ref_id);
    }
  }
  
  function autoplay_next()
  {
    autoplay_step++;
    if (array_key_exists(autoplay_step, autoplay_list))
    {
      autoplay_run(autoplay_step);
    }
    else
    {
      autoplay_step--;
    }
  }
  
  function autoplay_previous()
  {
    autoplay_step--;
    if (array_key_exists(autoplay_step, autoplay_list))
    {
      autoplay_run(autoplay_step);
    }
    else
    {
      autoplay_step++;
    }
  }
  
  
  $('a#autoplay_previous').click(function(){ autoplay_previous(); });
  $('a#autoplay_next').click(function(){ autoplay_next(); });
  $('a#autoplay_close').click(function(){
    $('#fade').fadeOut(1000, function(){$('#fade').remove();});
    $('#autoplay').hide();
  });
   
  
  /*
   * Fonction youtube.com et youtu.be
   */
    
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
  
  window.onYouTubePlayerReady = function()
  {
    autoplay_player = document.getElementById(autoplay_player_id);
    autoplay_player.addEventListener("onStateChange", "youtube_StateChange");
    $('img#autoplay_loader').hide();
    youtube_play();
  }
  
  window.youtube_StateChange = function(newState)
  {
    if (newState === 0)
    {
      autoplay_next();
    }
  }

  function youtube_play()
  {
    if (autoplay_player)
    {
      autoplay_player.playVideo();
    }
  }
  
});