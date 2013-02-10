function GenericStreamingPlayer(ref_id, object_for_player,
  callback_event_play,
  callback_event_end,
  callback_event_error,
  callback_event_finish_playlist
)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _playlist = new Array();
  var _player = null;
  
  var _callback_event_play = callback_event_play;
  var _callback_event_end = callback_event_end;
  var _callback_event_error = callback_event_error;
  var _callback_event_finish_playlist = callback_event_finish_playlist;
  
  var _current_index = 0;
  
  this.create_player = function()
  {
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
        
        _player = new jPlayerPlaylist
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
        
        var player = $('#jplayer_player_element_'+ref_id);
        player.bind($.jPlayer.event.play, event_play);
        player.bind($.jPlayer.event.ended, event_end);
        player.bind($.jPlayer.event.error, event_error);
        
      }
    });
  }
  
  var event_play = function(event)
  {
    _current_index = _player.current;
  
    _callback_event_play(event);
  }
  
  var event_end = function(event)
  {
    _callback_event_end(event);
    if (_current_index+1 == _playlist.length)
    {
      event_finish_playlist(event);
    }
  }
  
  var event_error = function(event)
  {
    _callback_event_error(event);
  }
  
  var event_finish_playlist = function(event)
  {
    _callback_event_finish_playlist(event);
  }
  
  this.play = function()
  {
    _player.jPlayer("play");
  }
  
  this.stop = function()
  {
    _player.jPlayer("stop");
  }
  
  this.pause = function()
  {
    _player.jPlayer("pause");
  }
}

function GenericSong(title, mp3)
{
  this.title = title;
  this.mp3   = mp3;
}