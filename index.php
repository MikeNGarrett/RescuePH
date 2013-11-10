<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Live Tweets for Rescue Coordination #RescuePH</title>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

		<link href="css/styles.css" rel="stylesheet" type="text/css" media="all">

		<script type="text/javascript" src="js/make-it-live.js"></script>
		<script type="text/javascript">
		$(function() {
			window.statuses = []
			doConnect(0);
			window.setInterval(doConnect, 30000);
			window.setInterval(displayTweet, 1000);
			$('#load').click(function(e){
				e.preventDefault();
				getArchives();
			});
		});
		</script>
	</head>
	<body>
		<div class="wrap">
			<h1>Rescue Coordination #RescuePH</h1>
			<div class="info">
				<h2>Searching for RescurPH, YolandaPH, or Haiyan.</h2>
			</div>
			<div id="results"></div>
			<div id="load"><a href="#">Load Archives</a></div>
		</div>
	</body>
</html>
