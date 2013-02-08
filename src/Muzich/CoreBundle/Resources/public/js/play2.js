
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

function YoutubePlayer(ref_id, object_for_player)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _yt_player;
  
  this.play = function()
  { 
    create_player();
  }
  
  var create_player = function()
  {
    var div_for_iframe = $('<div>').attr('id', _object_for_player.attr('id')+'_iframe');
    _object_for_player.append(div_for_iframe);
    
    _yt_player = new YT.Player(_object_for_player.attr('id')+'_iframe', {
      height  : config_player_youtube_height,
      width   : config_player_youtube_width,
      videoId : _ref_id,
      events  : {
        'onReady': onPlayerReady,
        'onStateChange': onPlayerStateChange
      }
    });
    
    
  }
  
  var onPlayerReady = function(event)
  {
    _yt_player.playVideo();
  }
  
  var onPlayerStateChange = function(event)
  {
    if (event.data == YT.PlayerState.PLAYING)
    {
      
    }
    if (event.data == YT.PlayerState.ENDED)
    {
      
    }
    if (event.data == YT.PlayerState.PAUSED)
    {
      
    }
    if (event.data == YT.PlayerState.BUFFERING)
    {
      
    }
    if (event.data == YT.PlayerState.CUED)
    {
      
    }
  }
  
  this.pause = function()
  {
    _yt_player.pauseVideo();
  }
  
  this.stop = function()
  {
    _yt_player.stopVideo();
    _object_for_player.html('');
  }
}

function GenericPlayer(ref_id, object_for_player)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  
  this.play = function()
  {
    JQueryJson(url_get_embed_for_element+'/'+_ref_id, {}, function(response){
      if (response.status == 'success')
      {
        object_for_player.html(response.data);
      }
    });
  }
  
  this.stop = function()
  {
    _object_for_player.html('');
  }
}

function SoundCloudPlayer(ref_id, object_for_player)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _player = null;
  var _sounds_count = 0;
  var _current_sound_index = 0;
  
  this.play = function()
  {
    if (!_player)
    {
      createPlayer();
    }
  }
  
  var createPlayer = function()
  {
    var div_for_iframe = $('<div>').attr('id', _object_for_player.attr('id')+'_iframe');
    _object_for_player.append(div_for_iframe);
    
    SC.initialize({
      client_id: '39946ea18e3d78d64c0ac95a025794e1'
    });
    
    SC.oEmbed(_ref_id, {
      show_artwork: false
    }, function(oembed){
      _object_for_player.html(oembed.html)
      var iframe = _object_for_player.find('iframe')[0];
      _player = SC.Widget(iframe);
      _player.bind(SC.Widget.Events.READY, event_ready);
      _player.bind(SC.Widget.Events.PLAY, event_play);
      _player.bind(SC.Widget.Events.FINISH, event_finish);
    });
    
    var event_ready = function()
    {
      console.log('sc ready');
      _player.play();
      _player.getSounds(function(value){
        _sounds_count = value.length;
      });
    }
    
    var event_play = function()
    {
      console.log('sc play');
      _player.getSounds(function(value){
        _sounds_count = value.length;
      });
      _player.getCurrentSoundIndex(function(value){
        _current_sound_index = value;
      });
    }
    
    var event_finish = function()
    {
      if (_sounds_count == _current_sound_index+1)
      {
        event_finish_playlist();
      }
    }
    
    var event_finish_playlist = function()
    {
      // Fin de lecture
    }
    
  }
}

function GenericSong(title, mp3)
{
  this.title = title;
  this.mp3   = mp3;
}

function JamendoPlayer(ref_id, object_for_player)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _playlist = new Array();
  
  this.play = function()
  {
    create_player();
  }
  
  var create_player = function()
  {
    // TODO efactoriser dans un objet lecteur generique
    var jplayer_player  = $('#jquery_jplayer_1').clone();
    var jplayer_content = $('#jp_container_1').clone();
    
    jplayer_player.attr ('id', 'jplayer_player_element_'+ref_id);
    jplayer_content.attr('id', 'jplayer_content_element_'+ref_id);
    
    _object_for_player.html('');
    _object_for_player.append(jplayer_player);
    _object_for_player.append(jplayer_content);
    
    JQueryJson(url_element_get_stream_data+'/'+ref_id, {}, function(response){
      if (response.status == 'success')
      {
        
        for(var i = 0; i < response.data.length; i++)
        {
          var song = new GenericSong(response.data[i].name, response.data[i].url);
          _playlist[i] = song;
        }
        
        autoplay_generic_player_playlist = new jPlayerPlaylist
        (
          {
            jPlayer: '#jplayer_player_element_'+ref_id,
            cssSelectorAncestor: '#jplayer_content_element_'+ref_id
          },
          _playlist,
          {
            playlistOptions:
            {
              autoPlay: true,
              enableRemoveControls: true
            },
            swfPath: "/jplayer/js",
            supplied: "mp3",
            wmode: "window"
          }
        );
        
        $('#jplayer_player_element_'+ref_id).bind($.jPlayer.event.play, event_play);
        $('#jplayer_player_element_'+ref_id).bind($.jPlayer.event.ended, event_end);
        $('#jplayer_player_element_'+ref_id).bind($.jPlayer.event.error, event_error);
        
      }
    });
  }
  
  var event_play = function(event)
  {
    
  }
  
  var event_end = function(event)
  {
    
  }
  
  var event_error = function(event)
  {
    
  }
  
  this.stop = function()
  {
    
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