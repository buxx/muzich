function DynamicPlayer()
{
  
  this.play = function(object_for_player, player_type, ref_id, element_id)
  {
    if ((player = getPlayerObjectForElementType(object_for_player, player_type, ref_id, element_id)))
    {
      player.play();
      return player;
    }
    
    return false;
  }
  
  var getPlayerObjectForElementType = function(object_for_player, player_type, ref_id, element_id)
  {
    if (player_type == 'youtube.com' || player_type == 'youtu.be')
    {
      return new YoutubePlayer(ref_id, object_for_player);
    }
    if (player_type == 'soundcloud.com')
    {
      return new SoundCloudPlayer(ref_id, object_for_player);
    }
    if (player_type == 'jamendo.com')
    {
      return new JamendoPlayer(element_id, object_for_player);
    }
    
    return new GenericPlayer(element_id, object_for_player);
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
}

$(document).ready(function() {
  window.dynamic_player = new DynamicPlayer();
  window.players_manager = new PlayersManager();
});