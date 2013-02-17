function DynamicPlayer()
{
  
  this.play = function(object_for_player, player_type, ref_id, element_id, autoplay, finish_callback)
  {
    autoplay = typeof autoplay !== 'undefined' ? autoplay : false;
    finish_callback = typeof finish_callback !== 'undefined' ? finish_callback : $.noop;
    if ((player = getPlayerObjectForElementType(object_for_player, player_type, ref_id, element_id, autoplay, finish_callback)))
    {
      player.play();
      return player;
    }
    
    return false;
  }
  
  var getPlayerObjectForElementType = function(object_for_player, player_type, ref_id, element_id, autoplay, finish_callback)
  {
    if (player_type == 'youtube.com' || player_type == 'youtu.be')
    {
      return new YoutubePlayer(ref_id, object_for_player, finish_callback);
    }
    if (player_type == 'soundcloud.com')
    {
      return new SoundCloudPlayer(ref_id, object_for_player, finish_callback, autoplay);
    }
    if (player_type == 'jamendo.com')
    {
      return new JamendoPlayer(element_id, object_for_player, finish_callback);
    }
    
    if (!autoplay && element_id)
    {
      return new GenericPlayer(element_id, object_for_player);
    }
    
    return false;
  }
  
}

function PlayersManager()
{
  var _players = new Array();
  
  this.add = function(player, key)
  {
    _players[key] = player;
  }
  
  this.get = function(key)
  {
    return _players[key];
  }
  
  this.remove = function(key)
  {
    delete _players[key];
  }
  
  this.getAll = function()
  {
    return _players;
  }
}

$(document).ready(function() {
  window.dynamic_player = new DynamicPlayer();
  window.players_manager = new PlayersManager();
});