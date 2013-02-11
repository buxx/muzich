function JamendoPlayer(ref_id, object_for_player, finish_callback)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _player = null;
  var _finish_callback = finish_callback;
  
  this.play = function()
  {
    _player = new GenericStreamingPlayer(_ref_id, _object_for_player,
      event_play,
      event_end,
      event_error,
      event_finish_playlist);
    _player.create_player();
  }
  
  var event_play = function()
  {
    
  }
  
  var event_end = function()
  {
    
  }
  
  var event_error = function()
  {
    _finish_callback();
  }
  
  var event_finish_playlist = function()
  {
    _finish_callback();
  }
  
  this.stop = function()
  {
    _player.stop();
  }
  
  this.pause = function()
  {
    _player.pause();
  }
  
  this.destroy = function()
  {
     _player.destroy();
  }
  
  this.stopAndDestroy = function()
  {
    this.stop();
    this.destroy();
  }
  
  this.close = function()
  {
    this.stopAndDestroy();
  }
}