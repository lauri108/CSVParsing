<?php

// CSV parser from https://github.com/ockam/php-csv
require_once('csv.php');

require_once('Restaurant.php');

setlocale(LC_TIME, "fi_FI");
date_default_timezone_set('Europe/Helsinki');

// Parse CSV
$lines = new CsvReader(dirname(__FILE__).'/ravintolat.csv', $separator=";");

// Set vars for min and max opening times
$leastOpeningHours = 10000;
$leastOpenRestaurantName = "";

$mostOpeningHours = 0;
$mostOpenRestaurantName = "";

$csvstring = "";

foreach ($lines as $key => $values) {

	// used for comparing the current Restaurant total hours to whole array min and max
	$restaurantTotalWeeklyOpeningHours = 0;
	
	$currentRestaurant = new Restaurant($values);
 	$restaurantTotalWeeklyOpeningHours = $currentRestaurant->getOpeningHoursPerWeekTotal();
	$restaurantName = $currentRestaurant->getName();
	
	if($restaurantTotalWeeklyOpeningHours > $mostOpeningHours){
		
		$mostOpeningHours = $restaurantTotalWeeklyOpeningHours;
		$mostOpenRestaurantName = $currentRestaurant->getName();
		
	}
	
	if($restaurantTotalWeeklyOpeningHours < $leastOpeningHours){
		
		$leastOpeningHours = $restaurantTotalWeeklyOpeningHours;
		$leastOpenRestaurantName = $currentRestaurant->getName();
		
	}
	
	// output a CSV string with only the names and counts
	// $csvstring .= $restaurantName . ";" . $hourCount . ";\n";
}

//print_r($csvstring);

print_r("---------" . PHP_EOL);
print_r($mostOpenRestaurantName . ", open " . $mostOpeningHours . " hours a week");
print_r(PHP_EOL);
print_r($leastOpenRestaurantName . ", open " . $leastOpeningHours . " hours a week");
print_r(PHP_EOL . "---------");

?>


