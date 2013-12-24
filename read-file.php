<?php

// CSV reader and parser from https://github.com/ockam/php-csv
require_once('CSVParser.php');
require_once('CSVReader.php');

// Restaurant class
require_once('Restaurant.php');

setlocale(LC_TIME, "fi_FI");
date_default_timezone_set('Europe/Helsinki');

function outputError (Exception $errorObject) {
	
	echo "An exception with one of the restaurants: {$errorObject->getMessage()} \n";	
	
}



// Read and parse CSV
$arrayOfRestaurants = new CSVReader(dirname(__FILE__).'/ravintolat.csv', $separator=";");

// Set vars for min and max opening times
$maxAndMinOpens = array("leastOpenHours"=>100000, "leastOpenName"=>"", "mostOpenHours"=>0, "mostOpenName");

// This var is used for optional CSV output for testing purposes
$csvstring = "";

foreach ($arrayOfRestaurants as $key => $currentRestaurantValues) {

	// used for comparing the current Restaurant total hours to whole array min and max
	$restaurantTotalWeeklyOpeningHours = 0;
	
	try {
		
		// attempt to create a new Restaurant
		$currentRestaurant = new Restaurant($currentRestaurantValues);
			
		// get the Restaurant information
	 	$currentRestaurantTotalWeeklyOpeningHours = $currentRestaurant->getOpeningHoursPerWeekTotal();
		$currentRestaurantName = $currentRestaurant->getName();
		
		// check whether current Restaurant has the most hours so far
		if($currentRestaurantTotalWeeklyOpeningHours > $maxAndMinOpens["mostOpenHours"]){
							
			// if so, update the most opened entry with current Restaurant values
			
			$maxAndMinOpens["mostOpenHours"] = $currentRestaurantTotalWeeklyOpeningHours;
			$maxAndMinOpens["mostOpenName"] = $currentRestaurantName;
			
		}
		
		// check whether current Restaurant has the least hours so far
		if($currentRestaurantTotalWeeklyOpeningHours < $maxAndMinOpens["leastOpenHours"]){
			
			// if so, update the least opened entry with current Restaurant values
			$maxAndMinOpens["leastOpenHours"] = $currentRestaurantTotalWeeklyOpeningHours;
			$maxAndMinOpens["leastOpenName"] = $currentRestaurantName;
			
		}
	
	// output a CSV string with only the names and counts
	// $csvstring .= "{$restaurantName};{$restaurantTotalWeeklyOpeningHours};\n";

	} catch (Exception $returnedError) {
		
    	outputError($returnedError);
		
	}
}

//print_r($csvstring);

echo "--------- \n";
echo "{$maxAndMinOpens["mostOpenName"]}, open {$maxAndMinOpens["mostOpenHours"]} hours a week";
echo "\n";
echo "{$maxAndMinOpens["leastOpenName"]} , open {$maxAndMinOpens["leastOpenHours"]} hours a week";
echo "\n---------";

?>


