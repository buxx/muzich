function TagPrompt()
{
  
  /* @var tags_selected array of Tag */
  var tags_selected = new Array();
  /* @var tags_proposed array of Tag */
  var tags_proposed = new Array();
  
  this.getProposedTagsForString = function(search_string, callback_success, callback_error)
  {
    JQueryJson(url_search_tag, {'string_search': search_string}, function(response){
      if (response.status == 'error')
      {
        callback_error(response.error);
      }
      else if (response.status == 'success')
      {
        for (i in response.data)
        {
          var tag = new Tag(
            response.data[i].id,
            response.data[i].name,
            true
          );
          tags_proposed.push(tag);
        }
        callback_success(tags_proposed, search_string);
      }
    });
  }
  
  this.selectProposedTag = function (tag_id)
  {
    // Selection d'un tag dans la liste de propositions
  }
  
  this.openTagSubmission = function (tag_string)
  {
    // Ouverture du dialogue pour proposer un nouveau tag
  }
  
  this.submitTag = function (tag_string, tag_arguments)
  {
    // Propotion d'un tag
  }
}

function TagPromptConnector(input, output, proposition_list)
{
  var _input = input;
  var _output = output;
  var _tag_prompt = new TagPrompt();
  var _tag_proposition_list = new TagPromptPropositionList(proposition_list);
  
  var launchSearchTagsIdLastKeystroke = function(search_string)
  {
    if (search_string == _input.val())
    {
      displayTagsProposedSearchTags();
    }
  }
  
  var displayTagsProposedSearchTags = function()
  {
    var string_search = _input.val();
    tags = _tag_prompt.getProposedTagsForString(
      string_search,
      _tag_proposition_list.displayTagsPropositions,
      _tag_proposition_list.displayError
    );
  }
  
  $(_input).bind('keyup', function() {
    setTimeout(launchSearchTagsIdLastKeystroke, 1000, [_input.val()]);
  });
}

function TagPromptPropositionList(proposition_list, string_search)
{
  var _proposition_list = proposition_list;
  var _list;
  
  this.displayError = function(error_string)
  {
    initializeList();
    var span_info = _proposition_list.find('span.info');
    span_info.text(error_string);
  }
  
  this.displayTagsPropositions = function(tags, string_search)
  {
    initializeList();
    displayMessage(str_replace('%string_search%', string_search, string_search_tag_title));
    for (i in tags)
    {
      addTagToList(tags[i]);
      // Bouton afficher tout les tags, tag hiddent quoi
    }
  }
  
  var initializeList = function()
  {
    $(_proposition_list).show();
    _list = _proposition_list.find('ul.search_tag_list');
    _list.find('li').remove();
    _proposition_list.find('a.more').hide();
  }
  
  var displayMessage = function(message)
  {
    var span_info = _proposition_list.find('span.info');
    span_info.text(message);
  }
  
  var addTagToList = function(tag)
  {
    _list.append(getListLine(tag));
  }
  
  var getListLine = function(tag)
  {
    return '<li>'+tag.name+'</li>';
  }
  
}

function Tag(id, name, knew)
{
  /* @var _id int */
  this.id = id;
  /* @var _name string */
  this.name = name;
  /* @var _knew boolean */
  this.knew = knew;
}

Tag.prototype =
{
  isKnew: function()
  {
    if (this.knew)
    {
      return true;
    }
    return false;
  }
}

$(document).ready(function(){
  $('input.tag_prompt').each(function(){
    new TagPromptConnector(
      $(this),
      $(this).parents('form').find('input.tag_prompt_selected_ids'),
      $(this).parents('form').find('div.search_tag_list')
   );
  });
});
