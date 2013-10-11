<?php

$ROOT_DIRECTORY = '/lp-miniseries-example-php/';

$DESCRIPTIONS = array(
  'Satyre',
  'Porcuspine or Porcupine',
  'Lamia',
  'Man Ape',
  'Arabian or Egyptian Land Crocodile',
  'Camelopardals',
  'Boa',
  'Unicorn',
  'Beaver',
  'Aegopithecus',
  'Badger',
  'Hydra',
  'Ape',
  'Mantichora',
  'Squirrel',
  'Scythian Wolf',
  'Beaver',
  'Cepus or Martime monkey',
  'The mole or want',
  'Spinga or Sphinx',
  'Bear Ape Arctopithecus',
  'Cat',
  'Winged Dragon',
  'Prasyan Ape',
  'A wilde beaste in the New Found World called SU',
  'Bear',
  'Sagoin, called Galeopithecus',
  'Lion',
);


// Called to generate the sample shown on BERG Cloud Remote.
function display_sample() {
	global $ROOT_DIRECTORY, $DESCRIPTIONS;

	$delivery_count = 0;
	$description = $DESCRIPTIONS[$delivery_count];
	header("Content-Type: text/html; charset=utf-8");
	header('ETag: "' . md5('sample' . gmdate('dmY')) . '"');
	require $_SERVER['DOCUMENT_ROOT'] . $ROOT_DIRECTORY . 'template.php';
}


// Called by BERG Cloud to generate publication output to print.
function display_edition() {
	global $ROOT_DIRECTORY, $DESCRIPTIONS;

	// We ignore timezones, but have to set a timezone or PHP will complain.
	date_default_timezone_set('UTC');

	if (array_key_exists('delivery_count', $_GET)) {
		$delivery_count = (int) $_GET['delivery_count'];
	} else {
		// A sensible default.	
		$delivery_count = 0;
	}

	if (array_key_exists('local_delivery_time', $_GET)) {
		$local_delivery_time = $_GET['local_delivery_time'];
	} else {
		// Default to now.
		$local_delivery_time = gmdate('Y-m-d\TH:i:s.0+00:00');
	}

	// Trim the final part of the time string, and get the day of week.
	$day_of_week = date('l', strtotime(substr($local_delivery_time, 0, -6)));
	
	if ($day_of_week !== 'Wednesday') {
		// No content is delivered this day.
		header('ETag: "' . md5('empty' . gmdate('dmY')) . '"');
		http_response_code(204);

	} elseif (($delivery_count + 1) > count($DESCRIPTIONS)) {
		// The publication has finished, so unsubscribe this subscriber.
		http_response_code(410);

	} else {
		// It's all good, so display the publication.
		$description = $DESCRIPTIONS[$delivery_count];
		header("Content-Type: text/html; charset=utf-8");
		header('ETag: "' . md5($delivery_count . gmdate('dmY')) . '"');
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
