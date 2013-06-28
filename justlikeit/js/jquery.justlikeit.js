(function($) {
    $(function() {
		//alert('Test');
		$link_like_post = $('.just-like-post-link');
		$link_like_comment = $('.just-like-comment-link');
		
		// Ссылки на лайк
		if ($link_like_post.length > 0) {
			// Для каждой ссылки...
			$link_like_post.each(function() {
			
				$(this).click(function() { 
					var actionLike = ($(this).hasClass('doLike')) ? 'doLike' : ($(this).hasClass('doUnlike')) ? 'doUnlike' : 'doAuth';
					if (actionLike == 'doAuth') {
						//alert('123');
					}
					else {
						var relLikeLink = $(this).attr('rel');
						var theLink = $(this);
						var theCount = $(this).next();
						var postId = parseInt(relLikeLink.split('_')[1]);
						
						$.post(ajaxurl, {
						action:	'like_post',
						post_id:	postId,
						actionLike:	actionLike

						}, function (response) {
							if (response.success) {
							//$.each(response, function(key, value) {
							//	  alert(key + ': ' + value);
							//	});
								if(theLink.hasClass('doLike')) {
									theLink.removeClass('doLike');
									theLink.addClass('doUnlike');
									theLink.html(unLikeLabel);
								} else {
									theLink.removeClass('doUnlike');
									theLink.addClass('doLike');
									theLink.html(likeLabel);
								}
								theCount.html(response.counttext);
							}
						});
					}
					
					return false;
				});
			
			});
		}
		
		if ($link_like_comment.length > 0) {
			// Для каждой ссылки...
			$link_like_comment.each(function() {
			
				$(this).click(function() {
					var actionLike = ($(this).hasClass('doLike')) ? 'doLike' : ($(this).hasClass('doUnlike')) ? 'doUnlike' : 'doAuth';
					if (actionLike == 'doAuth') {
					//	doAuth();
						//alert('123');
					}
					else {
						var relLikeLink = $(this).attr('rel');
						var theLink = $(this);
						var theCount = $(this).next();
						var commentId = parseInt(relLikeLink.split('_')[1]);
						
						//alert(commentId);
						$.post(ajaxurl, {
						action:	'like_comment',
						comment_id:	commentId,
						actionLike:	actionLike

						}, function (response) {
							//alert(response['success']);
							if (response.success) {
							//$.each(response, function(key, value) {
							//	  alert(key + ': ' + value);
							//	});
								if (theLink.hasClass('doLike')) {
									theLink.removeClass('doLike');
									theLink.addClass('doUnlike');
									theLink.html(unLikeLabel);
								} else {
									theLink.removeClass('doUnlike');
									theLink.addClass('doLike');
									theLink.html(likeLabel);
								}
								theCount.html(response.counttext);
							}
						});
					}
					
					return false;
				});
			
			});
		}
		
	});
}(jQuery));
