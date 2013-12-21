<?php

// CSV reader and parser from https://github.com/ockam/php-csv
require_once('CSVParser.php');
require_once('CSVReader.php');

// Restaurant class
require_once('Restaurant.php');

setlocale(LC_TIME, "fi_FI");
date_default_timezone_set('Europe/Helsinki');

// Read and parse CSV
$arrayOfRestaurants = new CSVReader(dirname(__FILE__).'/ravintolat.csv', $separator=";");

// Set vars for min and max opening times
$leastOpeningHours = 10000;
$leastOpenRestaurantName = "";

$mostOpeningHours = 0;
$mostOpenRestaurantName = "";

// This var is used for optional CSV output for testing purposes
$csvstring = "";

foreach ($arrayOfRestaurants as $key => $currentRestaurantValues) {

	// used for comparing the current Restaurant total hours to whole array min and max
	$restaurantTotalWeeklyOpeningHours = 0;
	
	try {
		
		// attempt to create a new Restaurant
		$currentRestaurant = new Restaurant($currentRestaurantValues);
			
		// get the Restaurant information
	 	$restaurantTotalWeeklyOpeningHours = $currentRestaurant->getOpeningHoursPerWeekTotal();
		$restaurantName = $currentRestaurant->getName();
		
		// check whether current Restaurant has the most hours so far
		if($restaurantTotalWeeklyOpeningHours > $mostOpeningHours){
			
			$mostOpeningHours = $restaurantTotalWeeklyOpeningHours;
			$mostOpenRestaurantName = $currentRestaurant->getName();
			
		}
		
		// check whether current Restaurant has the least hours so far
		if($restaurantTotalWeeklyOpeningHours < $leastOpeningHours){
			
			$leastOpeningHours = $restaurantTotalWeeklyOpeningHours;
			$leastOpenRestaurantName = $currentRestaurant->getName();
			
		}
	
	} catch (Exception $e) {
		
    	echo "There was an exception: {$e->getMessage()} \n";
	
	}
	// output a CSV string with only the names and counts
	$csvstring .= "{$restaurantName};{$hourCount};\n";
}

//print_r($csvstring);

print_r("--------- {PHP_EOL}");
print_r("{$mostOpenRestaurantName}, open {$mostOpeningHours} hours a week");
print_r(PHP_EOL);
print_r("{$leastOpenRestaurantName} , open {$leastOpeningHours} hours a week");
print_r("{PHP_EOL}---------");

?>


