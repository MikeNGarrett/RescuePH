function doConnect() {
	var lastId;
	if(window.ID) {
		lastId = window.ID;
	} else {
		lastId = 0;
	}
	$.ajax({
		url: "fetch.php",
		dataType: "json",
		data: {id: lastId, ajax: true}
		})
		.done( function(data) {
			console.log(data);
			if(data.errors) {
				data.errors.forEach(function(element) {
					$("#results").prepend('<div class="error"><p><strong>ERROR</strong> '+element.message+'</p></div>');
				});
			} else {
				if(data.statuses.length > 0) {
					var x = window.statuses.length;

					window.ID = data.statuses[0].id_str;
					for (var i=0;i<data.statuses.length;i++) {
						window.statuses[x+i] = data.statuses[i];
					}
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
				return false;
			}
			var user = status.user;
			var content = '<div class="tweet" id="'+status.id_str+'" style="display: none;">';
			content += '<p>'+status.text+' <br>';
			content += '<span>&mdash;<strong>'+user.name+'</strong> <a href="http://twitter.com/'+user.screen_name+'">@'+user.screen_name+'</a></span></p>';
			content += '<p><a href="https://twitter.com/'+user.screen_name+'/status/'+status.id_str+'">View Status</a> | <a href="https://twitter.com/intent/tweet?in_reply_to='+status.id_str+'">Reply to tweet</a></p>';
			if(status.entities.media !== undefined){
				status.entities.media.forEach(function(element) {
					if(element.type == 'photo') {
						content += '<a href="https://twitter.com/'+user.screen_name+'/status/'+status.id_str+'"><img src="'+element.media_url+'" class="entities"></a>';
					}
				});
			}
			content += '</div>';

			$(content).prependTo('#results').delay(600).fadeIn(500);

		}
	}
}
function getArchives() {
	$.ajax({
	url: "archives.php",
	dataType: "json",
	data: {ajax: true}
	})
	.done( function(data) {
		console.log(data);
		if(data.statuses.length > 0) {
			var content = '';
			for (var i=0;i<data.statuses.length;i++) {
				content += formatArchives(data.statuses[i]);
			}
			$('<hr>'+content).appendTo('#results');
		}
	})
	.fail( function(data) {
		console.log('something went wrong');
		console.log(data);
	});

}
function formatArchives(data) {
	var content = '';
	var status = data;
	var user = status.user;
	content += '<div class="tweet" id="'+status.id_str+'">';
	content += '<p>'+status.text+' <br>';
	content += '<span>&mdash;<strong>'+user.name+'</strong> <a href="http://twitter.com/'+user.screen_name+'">@'+user.screen_name+'</a></span></p>';
	content += '<p><a href="https://twitter.com/'+user.screen_name+'/status/'+status.id_str+'">View Status</a> | <a href="https://twitter.com/intent/tweet?in_reply_to='+status.id_str+'">Reply to tweet</a></p>';
	if(status.entities.media !== undefined){
		status.entities.media.forEach(function(element) {
			if(element.type == 'photo') {
				content += '<a href="https://twitter.com/'+user.screen_name+'/status/'+status.id_str+'"><img src="'+element.media_url+'" class="entities"></a>';
			}
		});
	}
	content += '</div>';
	return content;
}