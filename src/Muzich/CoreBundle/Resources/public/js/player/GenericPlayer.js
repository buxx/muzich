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