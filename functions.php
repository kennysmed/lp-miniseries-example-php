<?php

// Should be the common directory path from the URL, eg 
// '/lp-miniseries-example-php/'
$ROOT_DIRECTORY = preg_replace(
		'/(sample|edition)\/(index.php)?(\?.*?)?$/', '', $_SERVER['REQUEST_URI']);

// $EDITIONS will be an array of arrays.
// Each sub-array be like array("ape.png", "Ape")
$EDITIONS = json_decode(file_get_contents(getcwd().'/../editions.json'));


// Called to generate the sample shown on BERG Cloud Remote.
function display_sample() {
	global $ROOT_DIRECTORY, $EDITIONS;

	// We can choose which edition we want as the sample:
	$edition_number = 0;
	$image_name = $EDITIONS[$edition_number][0];
	$description = $EDITIONS[$edition_number][1];
	header("Content-Type: text/html; charset=utf-8");
	header('ETag: "' . md5('sample' . gmdate('dmY')) . '"');
	require $_SERVER['DOCUMENT_ROOT'] . $ROOT_DIRECTORY . 'template.php';
}


// Called by BERG Cloud to generate publication output to print.
function display_edition() {
	global $ROOT_DIRECTORY, $EDITIONS;

	// We ignore timezones, but have to set a timezone or PHP will complain.
	date_default_timezone_set('UTC');

	if (array_key_exists('delivery_count', $_GET)) {
		$edition_number = (int) $_GET['delivery_count'];
	} else {
		// A sensible default.
		$edition_number = 0;
	}

	if (array_key_exists('local_delivery_time', $_GET)) {
		// local_delivery_time is like '2013-10-16T23:20:30-08:00'.
		// We strip off the timezone, as we only need to know the day.
		$date = strtotime(substr($_GET['local_delivery_time'], 0, -6));
	} else {
		// Default to now.
		$date = gmmktime();
	}
	
	if (($edition_number + 1) > count($EDITIONS)) {
		// The publication has finished, so unsubscribe this subscriber.
		http_response_code(410);

	} else if (in_array(date('l', $date), array('Saturday', 'Sunday'))) {
		// No content is delivered this day.
		http_response_code(204);

	} else {
		// It's all good, so display the publication.
		$image_name = $EDITIONS[$edition_number][0];
		$description = $EDITIONS[$edition_number][1];
		header("Content-Type: text/html; charset=utf-8");
		header('ETag: "' . md5($edition_number . gmdate('dmY')) . '"');
		require $_SERVER['DOCUMENT_ROOT'] . $ROOT_DIRECTORY . 'template.php';
	}
}


/**
 * For 4.3.0 <= PHP <= 5.4.0
 * PHP >= 5.4 already has a http_response_code() function.
 */
if ( ! function_exists('http_response_code')) {
	function http_response_code($newcode = NULL) {
		static $code = 200;
		if ($newcode !== NULL) {
			header('X-PHP-Response-Code: '.$newcode, true, $newcode);
			if ( ! headers_sent()) {
				$code = $newcode;
			}
		}
		return $code;
	}
}

?>
