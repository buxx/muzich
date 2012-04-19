/*
 * jQuery Plugin : jConfirmAction
 * 
 * by Hidayat Sagita
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
      onClose: function(){}
		}, options);
					
			$(this).live('click', function(e) {

        $('div.question').remove();

        link = $(this);
        options.onOpen(link);

				e.preventDefault();
				thisHref	= $(this).attr('href');
				
				if($(this).next('.question').length <= 0)
					$(this).after('<div class="question">'+theOptions.question
          +'<br/> <span class="yes">'+theOptions.yesAnswer
          +'</span><span class="cancel">'+theOptions.cancelAnswer
          +'</span></div>');
				
				$(this).next('.question').animate({opacity: 1}, 300);
				
				$('.yes').bind('click', function(){
					options.onYes(link);
				});
		
				$('.cancel').bind('click', function(){
					$(this).parents('.question').fadeOut(300, function() {
            options.onClose(link);
						$(this).remove();
					});
				});
				
			});
	}
	
})(jQuery);