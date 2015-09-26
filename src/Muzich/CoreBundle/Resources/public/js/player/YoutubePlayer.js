function YoutubePlayer(ref_id, object_for_player, finish_callback)
{
  var _ref_id = ref_id;
  var _object_for_player = object_for_player;
  var _yt_player;
  var _finish_callback = finish_callback;
  var that = this;
  
  this.play = function(play_callback)
  { 
    create_player(play_callback);
  }
  
  var create_player = function(play_callback)
  {
    var div_for_iframe = $('<div>').attr('id', _object_for_player.attr('id')+'_iframe_'+ref_id);
    _object_for_player.append(div_for_iframe);

    var onPlayerReady_callback = function(){
      onPlayerReady();
      play_callback(that);
    };

    _yt_player = new YT.Player(_object_for_player.attr('id')+'_iframe_'+ref_id, {
      height  : config_player_youtube_height,
      width   : '100%',
      videoId : _ref_id,
      events  : {
        'onReady': onPlayerReady_callback,
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
        try
        {
          _yt_player.stopVideo();
        }
        catch (e)
        {
          
        }
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
    this.disableFullScreen();
  }
  
  this.close = function()
  {
    this.stopAndDestroy();
  }

  this.enableFullScreen = function() {
    var iframe = $('#'+_object_for_player.attr('id')+'_iframe_'+_ref_id);
    set_full_screen_on(iframe);
  }

  this.disableFullScreen = function() {
    $('#close_full_screen').remove();
  }


}