<?php
/*
 * Grab twitter search
 */

function process($id) {
	// Set the next time this can get requested
	$last = 'last_request.txt';
	file_put_contents($last, time() + 1800, LOCK_EX);

	require_once('TwitterAPIExchange.php');

	$settings = array(
	    'oauth_access_token' => "3974551-SpSe5fmk1SDpxkznb2Ct5BVf4p60hlXGQo1aSS3m9a",
	    'oauth_access_token_secret' => "QsDCfKhiWvrqZYJ2XFL2QiUm7duqOppFZjAsUdjxFJdjG",
	    'consumer_key' => "RH4lVgwonrYUFc8kJrmtDQ",
	    'consumer_secret' => "h12noEtloq1ReRnWi7rfBDgoMevHcccRcyVe8sqeuw"
	);
	$url = 'https://api.twitter.com/1.1/search/tweets.json';

	// Search for this query
	$q = urlencode('rescueph OR tracingph'); //
	// Do a recent search and include entities
	$getfield = '?q='.$q.'&result_type=recent&include_entities=true'; //&count=100&since_id='' since_id should be the last result returned
	// If there's an ID passed append it to the query. This is used to only get the latest results.
	if($id && $id > 0) {
		$getfield .= '&since_id='.$id;
	}
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
	                    ->buildOauth($url, $requestMethod)
	                    ->performRequest();

	$response = json_decode($response);

	$final = array();

	// Check for errors
	if(!isset($response->errors) && count($response->statuses) > 0) {

		$cache = 'cache.txt';
		if(!file_exists($cache)) {
			touch($cache);
			chmod($cache, 0664);
			$old = array();
		} else {
			// Had problems with cache file sometimes being blank
			if(filesize($cache) < 1) {
				unlink($cache);
				$old = array();
			} else {
				$old = file_get_contents($cache) or die('Cannot open file:  '.$cache);
				$old = json_decode($old);
				$archive = 'archive.txt';
				if(!file_exists($archive)) {
					touch($archive);
					chmod($archive, 0664);
				} else {
					$old_archive = file_get_contents($archive);
					$old_archive = json_decode($old_archive);
					$merged = array_merge($old_archive->statuses, $old->statuses);
					$old->statuses = $merged;
				}
				$old = json_encode($old);
				file_put_contents($archive, $old);
			}

		}

		foreach($response->statuses as $key => $stat) {
			// kick out any results that are retweets, PSAs or less than 15 characters (usually people tweeting the hashtag)
			if(empty($stat->retweeted_status) && empty($stat->in_reply_to_status_id) && strlen($stat->text) > 15 && count($stat->entities->urls) < 1 ) {
				// Check to see that if a timezone is set it resides in the Philippines
				if(empty($stat->utc_offset) || abs($stat->utc_offset) == 28800) {
					// Run everything through one final test
					$censor = preg_match('/#?followback|image|photo|missuniverse|donate|donation|tracing\ request|rt\:|news|lol|http/i', $stat->text);
					if($censor < 1) {
						$final[] = $stat;
					}
				}
			}
		}

	} // end check for errors

	if(count($final) > 0 ) {
		$response->statuses = $final;
		$response = json_encode($response);
		// Had problems with cache file sometimes being blank
		if(filesize($cache) < 1) {
			unlink($cache);
		}
		file_put_contents($cache, $response, LOCK_EX);
	} else {
		$response = json_encode($response);
	}

	return $response;
}
?>