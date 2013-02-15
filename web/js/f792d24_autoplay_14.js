function Autoplay()
{
  
  var _playlist = new Array();
  var _player = null;
  var _current_index = 0;
  
  this.start = function()
  {
    open_popin_dialog('autoplay');
    initializePlaylist(this.play);
  }
  
  var initializePlaylist = function(callback)
  {
    JQueryJson($('a#autoplay_launch').attr('href'), {}, function(response){
      if (response.status == 'success')
      {
        if (response.data.length)
        {
          _playlist = response.data;
        }
      }
      callback(0);
    });
  }
  
  this.play = function(index_to_play, timed)
  {
    window.autoplay.stopAndClearAllPlayers();
    if (array_key_exists(index_to_play, _playlist))
    {
      if (!timed)
      {
        _current_index = index_to_play;
        $('#autoplay_element_loader').show();
        window.setTimeout(function(){
          window.autoplay.play(index_to_play, true);
        });
      }
      else if (_current_index == index_to_play)
      {
        loadAndDisplayInfos(_playlist[index_to_play].element_id);
        if (!createPlayer(_playlist[index_to_play], window.autoplay.playNext))
        {
          this.play(index_to_play+1);
        }
        else
        {
          _current_index = index_to_play;
        }
      }
    }
    else
    {
      window.autoplay.nothingToPlay();
    }
  }
  
  this.stopAndClearAllPlayers = function()
  {
    players = window.players_manager.getAll();
    for (var i in players)
    {
      players[i].stopAndDestroy();
      window.players_manager.remove(i);
    }
  }
  
  var loadAndDisplayInfos = function(element_id)
  {
    $('#autoplay_element_loader').show();
    JQueryJson(url_element_dom_get_one_autoplay+'/'+element_id, {}, function(response){
      if (response.status == 'success')
      {
        $('li#autoplay_element_container').html(response.data);
        $('#autoplay_element_loader').hide();
      }
    });
  }
  
  var createPlayer = function(play_data, finish_callback)
  {
    $('#autoplay_loader').show();
    if ((player = window.dynamic_player.play(
      $('#autoplay_player'),
      play_data.element_type,
      play_data.element_ref_id,
      play_data.element_id,
      true,
      finish_callback
    )))
    {
      $('#autoplay_loader').hide();
      window.players_manager.add(player, 'autoplay_'+play_data.element_id);
      return true;
    }
    else
    {
      return false;
    }
  }
  
  this.playNext = function()
  {
    window.autoplay.play(_current_index+1);
  }
  
  this.playPrevious = function()
  {
    if (array_key_exists(_current_index-1, _playlist))
    {
      window.autoplay.play(_current_index-1);
    }
    return false;
  }
  
  this.nothingToPlay = function()
  {
    this.stopAndClearAllPlayers();
    $('#autoplay_noelements_text').show();
    $('div#autoplay_player_container').html('<div id="autoplay_player"></div>');
    $('li#autoplay_element_container').html('');
    $('#autoplay iframe').hide();
    $('#autoplay img[alt="loader"]').hide();
  }
  
}

$(document).ready(function() {
  
  window.autoplay = new Autoplay();
  
  $('a#autoplay_launch').click(function(){
    window.autoplay.start();
    return false;
  });
  
  $('a#autoplay_previous').click(function(){window.autoplay.playPrevious()});
  
  $('a#autoplay_next').click(function(){window.autoplay.playNext()});
  
  $('a#autoplay_close').click(function(){
    // Fond gris
    $('#fade').fadeOut(1000, function(){$('#fade').remove();});
    // On cache le lecteur
    $('#autoplay').hide();
    window.autoplay.stopAndClearAllPlayers();
  });
  
});