<?php
require_once('csv.php');
require_once('Restaurant.php');

setlocale(LC_TIME, "fi_FI");
date_default_timezone_set('Europe/Helsinki');

// Parse CSV
$lines = new CsvReader(dirname(__FILE__).'/ravintolat.csv', $separator=";");

// Set vars for min and max opening times
$leastOpeningHours = 10000;
$leastOpenRestaurantName = "";

$mostOpenRestaurantName = "";
$mostOpeningHours = 0;

$csvstring = "";

foreach ($lines as $key => $values) {

	// the master hour counter. This is restarted at every iteration.
	// at the end of the iteration, the $lines array needs to have a 
	// new key containing this count.
	$hourCount = 0;
	
	$currentRestaurant = new Restaurant($values);
 	$hourCount = $currentRestaurant->getOpeningHoursPerWeekTotal();
	$restaurantName = $currentRestaurant->getName();

	// $numberFormatter = new NumberFormatter('fi', NumberFormatter::DECIMAL);
	// $formattedHoursTotal = $numberFormatter->format($hoursOpenTotal);
	
	$csvstring .= $restaurantName . ";" . $hourCount . ";\n";
	
	if($hourCount > $mostOpeningHours){
		
		$mostOpeningHours = $hourCount;
		$mostOpenRestaurantName = $currentRestaurant->getName();
		
	}
	
	if($hourCount < $leastOpeningHours){
		
		$leastOpeningHours = $hourCount;
		$leastOpenRestaurantName = $currentRestaurant->getName();
		
	}
		
	$foo = "bar"; // breakpoint
}

//print_r($csvstring);

print_r("---------" . PHP_EOL);
print_r($mostOpenRestaurantName . ", open " . $mostOpeningHours . " hours a week");
print_r(PHP_EOL);
print_r($leastOpenRestaurantName . ", open " . $leastOpeningHours . " hours a week");
print_r(PHP_EOL . "---------");

?>


