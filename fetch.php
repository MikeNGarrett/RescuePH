<?php
/*
 * Grab twitter info and send it back via json
 */

require_once('TwitterAPIExchange.php');

$settings = array(
    'oauth_access_token' => "3974551-SpSe5fmk1SDpxkznb2Ct5BVf4p60hlXGQo1aSS3m9a",
    'oauth_access_token_secret' => "QsDCfKhiWvrqZYJ2XFL2QiUm7duqOppFZjAsUdjxFJdjG",
    'consumer_key' => "RH4lVgwonrYUFc8kJrmtDQ",
    'consumer_secret' => "h12noEtloq1ReRnWi7rfBDgoMevHcccRcyVe8sqeuw"
);
$url = 'https://api.twitter.com/1.1/search/tweets.json';

// Search for this query
$q = urlencode('yolandaph OR rescueph OR haiyan');
// Do a recent search and include entities
$getfield = '?q='.$q.'&result_type=recent&include_entities=true'; //&count=100&since_id='' since_id should be the last result returned
// If there's an ID passed append it to the query. This is used to only get the latest results.
if($_GET['id'] && $_GET['id'] > 0) {
	$getfield .= '&since_id='.$_GET['id'];
}
$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);
$response = $twitter->setGetfield($getfield)
                    ->buildOauth($url, $requestMethod)
                    ->performRequest();

$response = json_decode($response);
$final = array();

foreach($response->statuses as $key => $stat) {
	// kick out any results that are retweets, PSAs or less than 15 characters (usually people tweeting the hashtag)
	if(!isset($stat->retweeted_status) && !isset($stat->in_reply_to_status_id) && count($stat->text) < 15 && count($stat->entities->urls) < 1 ) {
		// Check to see that if a timezone is set it resides in the Philippines
		if(empty($stat->utc_offset) || abs($stat->utc_offset) == 28800) {
			// Run everything through one final test
			if(!array_strpos($stat->text)) {
				$final[] = $stat;
			}
		}
	}
}
$response->statuses = $final;
$response = json_encode($response);

/*
print '<pre>';
print_r( json_decode($response) );
print '</pre>';
*/

print_r($response);

function array_strpos($haystack) {
	$haystack = strtolower($haystack);
	$needles = array('followback','image','photo','missuniverse','donate','donation','tracing request','rt:','news','lol');
    foreach($needles as $needle) {
        if(strpos($haystack, $needle) !== false) {
	        return true;
        }
    }
    return false;
}
?>