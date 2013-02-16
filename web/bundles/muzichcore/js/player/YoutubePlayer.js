function YoutubePlayer(ref_id, object_for_player, finish_callback)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _yt_player;
  var _finish_callback = finish_callback;
  
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
      width   : '100%',
      videoId : _ref_id,
      events  : {
        'onReady': onPlayerReady,
        'onStateChange': onPlayerStateChange,
        'onError': onError
      }
    });
  }
  
  var onPlayerReady = function(event)
  {
    _yt_player.playVideo();
  }
  
  var onError = function(event)
  {
    _finish_callback();
  }
  
  var onPlayerStateChange = function(event)
  {
    if (event.data == YT.PlayerState.PLAYING)
    {
      
    }
    if (event.data == YT.PlayerState.ENDED)
    {
      _finish_callback();
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
    if (_yt_player)
    {
      if(typeof(_yt_player.pauseVideo)!=='undefined')
      {
        _yt_player.pauseVideo();
      }
    }
  }
  
  this.stop = function()
  {
    if (_yt_player)
    {
      if(typeof(_yt_player.stopVideo)!=='undefined')
      {
        _yt_player.stopVideo();
      }
    }
  }
  
  this.destroy = function()
  {
    _object_for_player.html('');
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