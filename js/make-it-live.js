function doConnect() {
	if(window.ID) {
		lastId = window.ID;
	} else {
		lastId = 0;
	}
	var ajaxer = $.ajax({
		url: "fetch.php",
		dataType: "json",
		data: {id: lastId, ajax: true}
		})
		.done( function(data) {
			console.log(data);
			if(data.errors) {
				data.errors.forEach(function(element, index, array) {
					$("#results").prepend('<div class="error"><p><strong>ERROR</strong> '+element.message+'</p></div>');
				});
			} else {
				if(data.length > 0) {
					var status, user, last_id;
					var x = window.statuses.length;

					window.ID = data[0].id_str;
					for (var i=0;i<data.length;i++) {
//						if(data.statuses[i].retweeted_status === undefined) {
							window.statuses[x+i] = data[i];
//						}
					}
				} else {
				}
			}
		})
		.fail( function(data) {
			console.log('something went wrong');
			console.log(data);
		});
}
function displayTweet() {
	if(window.statuses.length > 0) {
		var status = window.statuses.pop();
		if(status !== undefined) {
			if($('#'+status.id_str).length > 0) {
				return false
			}
			user = status.user;
			var content = '<div class="tweet" id="'+status.id_str+'" style="display: none;">';
			//content += '<img src="'+user.profile_image_url+'" class="profile">';
			content += '<p>'+status.text+' <br>';
			content += '<span>&mdash;<strong>'+user.name+'</strong> <a href="http://twitter.com/'+user.screen_name+'">@'+user.screen_name+'</a></span></p>';
			content += '<p><a href="https://twitter.com/'+user.screen_name+'/status/'+status.id_str+'">View Status</a> | <a href="https://twitter.com/intent/tweet?in_reply_to='+status.id_str+'">Reply to tweet</a></p>';
			if(status.entities.media !== undefined){
				status.entities.media.forEach(function(element, index, array) {
					if(element.type == 'photo') {
						content += '<a href="https://twitter.com/'+user.screen_name+'/status/'+status.id_str+'"><img src="'+element.media_url+'" class="entities"></a>';
					}
				});
			}
			content += '</div>';
/*
			$('#results div').fadeOut(500, function(content) {
				$(this).remove();
			});
*/

			$(content).prependTo('#results').delay(600).fadeIn(500);

/*
			if($('#results .tweet').length > 15) {
				$('#results .tweet').last().remove();
			}
*/
		}
	}
}
