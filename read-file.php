<?php
require_once('csv.php');

// get it into an array
$lines = new CsvReader(dirname(__FILE__).'/ravintolat.csv');


function test_print($item2, $key)
{
    echo "$key. $item2<br />\n";
}


foreach ($lines as $line_number => $values) {

	//get the opening times into an array
	$openingTimes = explode(", ", $values[4]);
	var_dump($openingTimes);

	foreach ($values as $key => $rowValues) {
	
		//var_dump($rowValues);

	}
	
	
	//var_dump($values);
}


