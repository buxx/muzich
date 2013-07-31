function SoundCloudPlayer(ref_id, object_for_player, finish_callback, autoplay)
{
  autoplay = typeof autoplay !== 'undefined' ? autoplay : false;
  var _autoplay = autoplay;
  var _iframe_id = '';
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _player = null;
  var _sounds_count = 0;
  var _current_sound_index = 0;
  var _finish_callback = finish_callback;
  
  this.play = function()
  {
    createPlayer(
      event_ready,
      event_play,
      event_finish
    );
  }
  
  var createPlayer = function(callback_ready, callback_play, callback_finish)
  {
    _iframe_id = 'soundcloud_player_'+new Date().getTime();
    if (!_autoplay)
    {
      _object_for_player.html('<iframe id="'+_iframe_id+'" src="http://w.soundcloud.com/player/?url='
            +_ref_id+'&show_artwork=false&auto_play=true" width="100%" '
            +'height="350" scrolling="no" frameborder="no"></iframe>');
    }
    else
    {
      $('#autoplay_player_soundcloud').append('<iframe id="'+_iframe_id+'" src="http://w.soundcloud.com/player/?url='
        +_ref_id+'&show_artwork=false&auto_play=true" width="100%" '
        +'height="350" scrolling="no" frameborder="no"></iframe>');
    }
    
    SC.initialize({
      client_id: '39946ea18e3d78d64c0ac95a025794e1'
    });
    SC.get(_ref_id, function(track, error)
    {
      if (!error)
      {
        _player = SC.Widget(document.getElementById(_iframe_id));
        _player.bind(SC.Widget.Events.READY, callback_ready);
        _player.bind(SC.Widget.Events.PLAY, callback_play);
        _player.bind(SC.Widget.Events.FINISH, callback_finish);
      }
      else
      {
        _finish_callback();
      }
    });
    
  }
  
  var event_ready = function()
  {
    _player.getSounds(function(value){
      _sounds_count = value.length;
    });
  }
  
  var event_play = function()
  {
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
    _finish_callback();
  }
  
  this.stop = function()
  {
    
  }
  
  this.destroy = function(force)
  {
    if (_player && !_autoplay)
    {
      _object_for_player.html('');
    }
    if (_autoplay || force)
    {
      $('#autoplay_player_soundcloud').html('');
    }
  }
  
  this.stopAndDestroy = function()
  {
    //this.stop();
    this.destroy(false);
  }
  
  this.close = function()
  {
    this.destroy(true);
  }
}