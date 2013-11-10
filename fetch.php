<?php
/*
 *  Check to see if a new request needs to be made, else return the cache.
 */

// Make sure this is only being accessed via AJAX
if(empty($_GET['ajax'])) {
	print 'nope';
	return false;
}

require_once('process.php');
$since_id = 0;
// Get the id passed from our app if it exists. This will be passed to regerate the cache.
if($_GET['id'] && $_GET['id'] > 0) {
	$since_id = $_GET['id'];
}
$last = 'last_request.txt';
if(!file_exists($last)) {
	touch($last);
	chmod($last, 0664);
	file_put_contents($last, time() + 1800, LOCK_EX);
	print_r(process($since_id));
} else {
	// Check last time requested
	$last_time = filemtime($last);
	if($last_time < time() + 1800) {
		$check_last = file_get_contents($last) or die('Cannot open file:  '.$last);
		// Double check time (may not be needed
		if($check_last < time() + 1800) {
			print_r(process($since_id));
			die;
		}
	}

	// Go get cache since you're not allowed to fetch yet.
	$cache_file = 'cache.txt';
	// Had problems with cache file sometimes being blank
	if(filesize($cache) < 1) {
		unlink($cache);
	} else {
		$cache = file_get_contents($cache_file) or die('Cannot open file:  '.$cache_file);
	}


	if(strlen($cache) < 10) {
		$archive = 'archive.txt';
		if(file_exists($archive)) {
			$cache = file_get_contents($archive) or die('Cannot open file:  '.$archive);
		}
	}


	print_r($cache);

}




?>