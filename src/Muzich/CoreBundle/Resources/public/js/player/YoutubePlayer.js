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