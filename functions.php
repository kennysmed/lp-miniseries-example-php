<?php

$ROOT_DIRECTORY = '/lp-miniseries-example-php/';

// Data for each edition: image filename, description.
$EDITIONS = array(
	array('adder.png', 'Adder'),
	array('aegopithecus.png', 'Aegopithecus'),
	array('african_bugil.png', 'African Bugil'),
	array('allocamelus.png', 'Allocamelus'),
	array('alpine_mouse.png', 'Alpine Mouse'),
	array('another_monster.png', 'Another Monster'),
	array('antalope.png', 'Antalope'),
	array('ape.png', 'Ape'),
	array('ape_calitrich.png', 'Ape Calitrich'),
	array('arabian_crocodile.png', 'Arabian or Egyptian Land Crocodile'),
	array('arabian_sheep_broad.png', 'Arabian Sheep with a broad tail'),
	array('arabian_sheep_long.png', 'Arabian Sheep with a long tail'),
	array('aspes.png', 'Aspes'),
	array('asse.png', 'Asse'),
	array('badger.png', 'Badger'),
	array('bear.png', 'Bear'),
	array('bear_ape.png', 'Bear Ape Arctopithecus'),
	array('beaver.png', 'Beaver'),
	array('boa.png', 'Boa'),
	array('camelopardals.png', 'Camelopardals'),
	array('cat.png', 'Cat'),
	array('cepus_monkey.png', 'Cepus or Martime monkey'),
	array('hydra.png', 'Hydra'),
	array('lamia.png', 'Lamia'),
	array('lion.png', 'Lion'),
	array('man_ape.png', 'Man Ape'),
	array('mantichora.png', 'Mantichora'),
	array('mole.png', 'The mole or want'),
	array('porcupine.png', 'Porcuspine or Porcupine'),
	array('prasyan_ape.png', 'Prasyan Ape'),
	array('sagoin.png', 'Sagoin, called Galeopithecus'),
	array('satyre.png', 'Satyre'),
	array('scythian_wolf.png', 'Scythian Wolf'),
	array('sphinx.png', 'Spinga or Sphinx'),
	array('squirrel.png', 'Squirrel'),
	array('su.png', 'A wilde beaste in the New Found World called SU'),
	array('unicorn.png', 'Unicorn'),
	array('winged_dragon.png', 'Winged Dragon'),
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
