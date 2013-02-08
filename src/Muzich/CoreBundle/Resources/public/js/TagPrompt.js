function TagPrompt(select_tag_callback, tag_prompt_connector)
{
  // En plus je change une ligne !
  /* @var tags_selected array of Tag */
  var tags_selected = [];
  /* @var tags_proposed array of Tag */
  var tags_proposed = [];
  var _select_tag_callback = select_tag_callback;
  var _tag_prompt_connector = tag_prompt_connector;
  
  this.getProposedTagsForString = function(search_string, callback_success, callback_error)
  {
    tags_proposed = [];
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
            response.data[i].name
          );
          tags_proposed.push(tag);
        }
        callback_success(tags_proposed, search_string, response.message, response.same_found);
      }
    });
  }
  
  this.selectProposedTag = function (tag_id, tag_name)
  {
    if (!tag_id)
    {
      this.openTagSubmission(tag_name);
    }
    else
    {
      addTagToSelectedTags(findTagInProposedList(tag_id));
      _select_tag_callback(tags_selected);
    }
  }
  
  this.openTagSubmission = function (tag_name)
  {
    // TODO : Cette partie du code n'est pas encore refactorisé
    
    // Effet fade-in du fond opaque
    $('body').append($('<div>').attr('id', 'fade')); 
    //Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
    $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
    
    // En premier lieux on fait apparaître la fenêtre de confirmation
    var popup = $('<div>')
    .attr('id', 'add_tag')
    .addClass('popin_block')
    .css('width', '400px')
      //.append($('<h2>').append(string_tag_add_title))
    .append($('<form>')
      .attr('action', url_add_tag)
      .attr('method', 'post')
      .attr('name', 'add_tag')
      .ajaxForm(function(response) {
        /*
        *
        */
  
        if (response.status == 'mustbeconnected')
        {
          $(location).attr('href', url_index);
        }
  
        if (response.status == 'success')
        {
          var tag = new Tag(response.tag_id, response.tag_name);
          addTagToProposedTags(tag);
          addTagToSelectedTags(tag);
          _tag_prompt_connector.updateOutput(tags_selected);
  
          $('#fade').fadeOut(400, function(){$('#fade').remove();});
          $('#add_tag').remove();
        }
  
        if (response.status == 'error')
        {
          $('form[name="add_tag"]').find('ul.error_list').remove();
          var ul_errors = $('<ul>').addClass('error_list');
  
          for (i in response.errors)
          {
            ul_errors.append($('<li>').append(response.errors[i]));
          }
  
          $('form[name="add_tag"]').prepend(ul_errors);
        }
  
        return false;
      })
  
      .append($('<div>').addClass('tag')
        .append($('<ul>')
          .append($('<li>').addClass('button')
            .append(tag_name))))
      .append($('<p>').append(string_tag_add_text))
      .append($('<p>').append(string_tag_add_argument))
      .append($('<textarea>').attr('name', 'argument'))
      .append($('<div>').addClass('inputs')
        .append($('<input>')
          .attr('type', 'button')
          .attr('value', string_tag_add_inputs_cancel)
          .addClass('button')
          .click(function(){
            $('#fade').fadeOut(1000, function(){$('#fade').remove();});
            $('#add_tag').remove();
  
            return false;
          })
        )
        .append($('<input>')
          .attr('type', 'submit')
          .attr('value', string_tag_add_inputs_submit)
          .addClass('button')
          .click(function(){
  
            // TODO: loader gif
  
          })
        )
        .append($('<input>').attr('type', 'hidden').attr('name', 'tag_name').val(tag_name))
      ))
    ;
  
    // Il faut ajouter le popup au dom avant de le positionner en css
    // Sinon la valeur height n'est pas encore calculable
    $('body').prepend(popup);
  
    //Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
    var popMargTop = (popup.height() + 50) / 2;
    var popMargLeft = (popup.width() + 50) / 2;
  
    //On affecte le margin
    $(popup).css({
      'margin-top' : -popMargTop,
      'margin-left' : -popMargLeft
    });
  
    return false;
  }
  
  var addTagToSelectedTags = function(tag)
  {
    var found = false;
    for (i in tags_selected)
    {
      if (tags_selected[i].id == tag.id)
      {
        found = true;
      }
    }
    if (!found)
    {
      tags_selected.push(tag);
    }
  }
  
  var addTagToProposedTags = function(tag)
  {
    var found = false;
    for (i in tags_proposed)
    {
      if (tags_proposed[i].id == tag.id)
      {
        found = true;
      }
    }
    if (!found)
    {
      tags_proposed.push(tag);
    }
  }
  
  this.addTag = function(tag)
  {
    addTagToSelectedTags(tag);
    addTagToProposedTags(tag);
  }
  
  var findTagInProposedList = function(tag_id)
  {
    for (i in tags_proposed)
    {
      if (tags_proposed[i].id == tag_id)
      {
        return tags_proposed[i];
      }
    }
    throw new Error("Unable to find the tag !")
  }
  
  this.removeSelectedTag = function(tag_id)
  {
    var new_tags_selected = [];
    for (i in tags_selected)
    {
      if (tags_selected[i].id != tag_id)
      {
        new_tags_selected.push(tags_selected[i]);
      }
    }
    tags_selected = new_tags_selected;
  }
  
  this.getSelectedTags = function()
  {
    return tags_selected;
  }
  
  this.setSelectedTags = function(tags)
  {
    tags_selected = tags;
  }
  
}

function TagPromptConnector(input, output, proposition_list, tag_box, prompt_loader)
{
  var _input = input;
  var _output = output;
  var _tag_box_manager = new TagBoxManager(tag_box, this);
  var _prompt_loader = prompt_loader;
  
  this.updateOutput = function(tags)
  {
    _output.val(array2json(tagsToArrayIds(tags)));
    _tag_proposition_list.hide();
    _tag_box_manager.update(tags);
    cleanInput();
  }
  
  var _tag_prompt = new TagPrompt(this.updateOutput, this);
  var _tag_proposition_list = new TagPromptPropositionList(proposition_list, _tag_prompt.selectProposedTag, this);
  
  var cleanInput = function()
  {
    _input.val('');
  }
  
  var showPromptLoader = function()
  {
    _prompt_loader.show();
  }
  
  this.hidePromptLoader = function()
  {
    _prompt_loader.hide();
  }
  
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
    _tag_prompt.getProposedTagsForString(
      string_search,
      _tag_proposition_list.displayTagsPropositions,
      _tag_proposition_list.displayError
    );
  }
  
  $(_input).bind('keyup', function() {
    if ($(this).val().length > 0)
    {
      showPromptLoader();
      setTimeout(launchSearchTagsIdLastKeystroke, 1000, [_input.val()]);
    }
  });
  
  var tagsToArrayIds = function(tags)
  {
    var tags_ids = [];
    for (i in tags)
    {
      tags_ids.push(tags[i].id);
    }
    return tags_ids;
  }
  
  this.removeSelectedTag = function(tag_id)
  {
    _tag_prompt.removeSelectedTag(tag_id);
    this.updateOutput(_tag_prompt.getSelectedTags());
  }
  
  this.initializeTags = function(tags)
  {
    _tag_prompt.setSelectedTags(tags);
    this.updateOutput(_tag_prompt.getSelectedTags());
  }
  
  this.addTagToTagPrompt = function(tag)
  {
    _tag_prompt.addTag(tag);
    this.updateOutput(_tag_prompt.getSelectedTags());
  }
  
  this.openTagSubmission = function(tag_name)
  {
    _tag_prompt.openTagSubmission(tag_name);
  }
  
}

function TagBoxManager(tag_box, tag_prompt_connector)
{
  var _tag_prompt_connector = tag_prompt_connector;
  var _tag_box = tag_box;
  
  this.update = function(tags)
  {
    _tag_box.find('li').remove();
    for (i in tags)
    {
      _tag_box.append(getTagLine(tags[i]));
    }
  }
  
  var getTagLine = function (tag)
  {
    var line = $('<li>');
    line.addClass('tag');
    line.text(tag.name);
    line.append(getCloseLink(tag));
    
    return line;
  }
  
  var getCloseLink = function(tag)
  {
    var close_link = $('<a>');
    close_link.addClass('close');
    close_link.attr('href', '#');
    close_link.data('tag_id', tag.id);
    close_link.data('tag_name', tag.name);
    close_link.text('close');
    close_link.bind('click', function(){
      _tag_prompt_connector.removeSelectedTag($(this).data('tag_id'));
      return false;
    });
    
    return close_link;
  }
  
}

function TagPromptPropositionList(proposition_list, click_tag_callback, tag_prompt_connector)
{
  var _proposition_list = proposition_list;
  var _list;
  var _limit_display_tags = 30;
  var _click_tag_callback = click_tag_callback;
  var _tag_prompt_connector = tag_prompt_connector;
  
  this.displayError = function(error_string)
  {
    initializeList();
    var span_info = _proposition_list.find('span.info');
    span_info.text(error_string);
  }
  
  this.displayTagsPropositions = function(tags, search_string, message, same_found)
  {
    initializeList();
    displayMessage(message);
    for (i in tags)
    {
      addTagToList(tags[i], search_string);
    }
    if (!same_found)
    {
      addTagPropositionToList(new Tag(null, search_string));
    }
  }
  
  var initializeList = function()
  {
    _tag_prompt_connector.hidePromptLoader();
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
  
  var addTagToList = function(tag, search_string)
  {
    var line = '';
    if (_list.find('li').length > _limit_display_tags)
    {
      line = getListLine(tag, true);
      _proposition_list.find('a.more').show();
    }
    else
    {
      line = getListLine(tag, false);
    }
    
    line = strongifySearchedLetters(line, search_string);
    _list.append(line);
  }
  
  var getListLine = function(tag, hide)
  {
    if (hide)
    {
      var line = $('<li style="display: none;">');
    }
    else
    {
      var line = $('<li>');
    }
    
    return line.append(getTagLink(tag));
  }
  
  var getTagLink = function(tag)
  {
    link = $('<a>');
    link.attr('href', '#');
    link.data('tag_id', tag.id);
    link.data('tag_name', tag.name);
    link.text(tag.name);
    link.bind('click', function(){
      _click_tag_callback($(this).data('tag_id'), $(this).data('tag_name'));
      return false;
    });
    return link;
  }
  
  var strongifySearchedLetters = function(line, search_string)
  {
    var name = line.find('a').text();
    line.find('a').html(name.replace(new RegExp(search_string, "i"), "<strong>" + search_string + "</strong>"));
    return line;
  }
  
  var addTagPropositionToList = function(tag)
  {
    var line = getListLine(tag);
    line.addClass('new');
    _list.append(line);
  }
  
  this.hide = function()
  {
    _proposition_list.hide();
  }
  
}

function Tag(id, name)
{
  /* @var _id int */
  this.id = id;
  /* @var _name string */
  this.name = name;
}

Tag.prototype =
{
  isKnew: function()
  {
    if (this.id)
    {
      return true;
    }
    return false;
  }
}

$(document).ready(function(){
  // Ce code permet la fermeture de la propositions de tags lors d'un click sur la page
  $('html').click(function() {
    if ($("div.search_tag_list").is(':visible'))
    {
      $("div.search_tag_list").hide();
    }
  });
  $("div.search_tag_list, div.search_tag_list a.more").live('click', function(event){
    event.stopPropagation();
    $("div.search_tag_list").show();
  });
  
  $('div.search_tag_list a.more').live('click', function(){
    $(this).parents('div.search_tag_list ').find('ul.search_tag_list li').show();
    $(this).hide();
    return false;
  });
  
});

// loaders