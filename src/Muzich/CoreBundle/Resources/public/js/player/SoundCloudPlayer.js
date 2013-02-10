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