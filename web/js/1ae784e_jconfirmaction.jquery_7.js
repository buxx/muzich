/*
 * jQuery Plugin : jConfirmAction
 * 
 * by Hidayat Sagita, modified by Sevajol Bastien (http://blog.bux.fr/tag/jconfirmaction/)
 * http://www.webstuffshare.com
 * Licensed Under GPL version 2 license.
 *
 */
(function($){

	jQuery.fn.jConfirmAction = function (options) {
		
		// Some jConfirmAction options (limited to customize language) :
		// question : a text for your question.
		// yesAnswer : a text for Yes answer.
		// cancelAnswer : a text for Cancel/No answer.
		var theOptions = jQuery.extend ({
			question: "Are You Sure ?",
			yesAnswer: "Yes",
			cancelAnswer: "Cancel",
      onYes: function(){},
      onOpen: function(){},
      onClose: function(){},
      justOneAtTime: true
		}, options);
					
			$(this).live('click', function(e) {

        if (theOptions.justOneAtTime)
        {
          $('div.question').remove();
        }

        theOptions.onOpen($(this));

				e.preventDefault();
				
				if($(this).next('.question').length <= 0)
					$(this).after(
            '<div class="question">'+theOptions.question
            +'<br/> <span class="yes">'+theOptions.yesAnswer
            +'</span><span class="cancel">'+theOptions.cancelAnswer
            +'</span></div>'
          );
				
				$(this).next('.question').animate({opacity: 1}, 300);
				
				$(this).next('.question').find('.yes').bind('click', function(){
					theOptions.onYes($(this).parents('div.question').prev('a'));
				});
		
				$(this).next('.question').find('.cancel').bind('click', function(){
					$(this).parents('.question').fadeOut(300, function() {
            theOptions.onClose($(this).parents('div.question').prev('a'));
					});
				});
				
			});
	}
	
})(jQuery);