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


// TODO: use Restaurant objects for the max and min comparisons.
// create proper method for the class (update name and openingHours at the same time)


// instantiate two Restaurants for min and max comparisons
$maxRestaurant = new Restaurant(array("","RESTAURANT1","","","Ma 15:00-16:00","",""));
$minRestaurant = new Restaurant(array("","RESTAURANT2","","","Ma-Pe 01:00-23:00","",""));

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
		if($currentRestaurantTotalWeeklyOpeningHours > $maxRestaurant->getOpeningHoursPerWeekTotal()){
							
			// if so, update the most opened entry with current Restaurant values		
			$maxRestaurant->updateNameAndOpeningHours($currentRestaurant);
			
		}
		
		// check whether current Restaurant has the least hours so far
		if($currentRestaurantTotalWeeklyOpeningHours < $minRestaurant->getOpeningHoursPerWeekTotal()){
			
			// if so, update the least opened entry with current Restaurant values
			$minRestaurant->updateNameAndOpeningHours($currentRestaurant);
			
		}
	
	// output a CSV string with only the names and counts
	// $csvstring .= "{$restaurantName};{$restaurantTotalWeeklyOpeningHours};\n";

	} catch (Exception $returnedError) {
		
    	outputError($returnedError);
		
	}
}

//print_r($csvstring);


echo "--------- \n";
echo "{$maxRestaurant->getName()}, open {$maxRestaurant->getOpeningHoursPerWeekTotal()} hours a week";
echo "\n";
echo "{$minRestaurant->getName()} , open {$minRestaurant->getOpeningHoursPerWeekTotal()} hours a week";
echo "\n---------";


/*
echo "--------- \n";
echo "{$maxAndMinOpens["mostOpenName"]}, open {$maxAndMinOpens["mostOpenHours"]} hours a week";
echo "\n";
echo "{$maxAndMinOpens["leastOpenName"]} , open {$maxAndMinOpens["leastOpenHours"]} hours a week";
echo "\n---------";

 * 
 * 
 */
?>


