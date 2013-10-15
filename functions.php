<?php

$ROOT_DIRECTORY = '/lp-miniseries-example-php/';

// Data for each edition: image filename, description.
$EDITIONS = array(
	array('satyre.png', 'Satyre'),
	array('porcupine.png', 'Porcuspine or Porcupine'),
	array('lamia.png', 'Lamia'),
	array('man_ape.png', 'Man Ape'),
	array('crocodile.png', 'Arabian or Egyptian Land Crocodile'),
	array('camelopardals.png', 'Camelopardals'),
	array('boa.png', 'Boa'),
	array('unicorn.png', 'Unicorn'),
	array('aegopithecus.png', 'Aegopithecus'),
	array('badger.png', 'Badger'),
	array('hydra.png', 'Hydra'),
	array('ape.png', 'Ape'),
	array('mantichora.png', 'Mantichora'),
	array('squirrel.png', 'Squirrel'),
	array('scythian_wolf.png', 'Scythian Wolf'),
	array('beaver.png', 'Beaver'),
	array('cepus_monkey.png', 'Cepus or Martime monkey'),
	array('mole.png', 'The mole or want'),
	array('sphinx.png', 'Spinga or Sphinx'),
	array('bear_ape.png', 'Bear Ape Arctopithecus'),
	array('cat.png', 'Cat'),
	array('dragon.png', 'Winged Dragon'),
	array('prasyan_ape.png', 'Prasyan Ape'),
	array('su.png', 'A wilde beaste in the New Found World called SU'),
	array('bear.png', 'Bear'),
	array('sagoin.png', 'Sagoin, called Galeopithecus'),
	array('lion.png', 'Lion'),
	array('another_monster.png', 'Another Monster'),
	array('adder.png', 'Adder'),
	array('african_bugil.png', 'African Bugil'),
	array('allocamelus.png', 'Allocamelus'),
	array('alpine_mouse.png', 'Alpine Mouse')
);


// Called to generate the sample shown on BERG Cloud Remote.
function display_sample() {
	global $ROOT_DIRECTORY, $EDITIONS;

	// We can choose which edition we want as the sample:
	$delivery_count = 0;
	$image_name = $EDITIONS[$delivery_count][0];
	$description = $EDITIONS[$delivery_count][1];
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
		$delivery_count = (int) $_GET['delivery_count'];
	} else {
		// A sensible default.	
		$delivery_count = 0;
	}

	if (array_key_exists('local_delivery_time', $_GET)) {
		// local_delivery_time is like '2013-10-16T23:20:30-08:00'.
		// We strip off the timezone, as we only need to know what day this
		// date is on.
		$date = strtotime(substr($_GET['local_delivery_time'], 0, -6));
	} else {
		// Default to now.
		$date = gmdate();
	}
	
	if (($delivery_count + 1) > count($EDITIONS)) {
		// The publication has finished, so unsubscribe this subscriber.
		http_response_code(410);

	} else if (date('l', $date) !== 'Wednesday') {
		// No content is delivered this day.
		http_response_code(204);

	} else {
		// It's all good, so display the publication.
		$image_name = $EDITIONS[$delivery_count][0];
		$description = $EDITIONS[$delivery_count][1];
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
