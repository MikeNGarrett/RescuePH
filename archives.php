<?php
/*
 *  Check to see if a new request needs to be made, else return the cache.
 */

// Make sure this is only being accessed via AJAX
if(empty($_GET['ajax'])) {
	print 'nope';
	return false;
}

// Go get archive and return
$archive = 'archive.txt';
if(file_exists($archive)) {
	$tweets = file_get_contents($archive) or die('Cannot open file:  '.$archive);
	print_r($tweets);
}
?>