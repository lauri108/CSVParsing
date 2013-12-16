<?php
require_once('csv.php');
require_once('daySwapper.php');
setlocale(LC_TIME, "fi_FI");
date_default_timezone_set('Europe/Helsinki');

class OpeningTime {

	protected $startDay = null;
	protected $endDay = null;
	protected $openingTimeString = null;
	protected $openingHours = null;

	public function __construct($startDay, $endDay, $openingTimeString) {
		$this->startDay = $startDay;
		$this->endDay = $endDay;
		$this->openingTimeString = $openingTimeString;
		$this->openingHours = _parseOpeningTime($openingTimeString);
	}

	public function __destruct() {
		$this->close();
	}

	public function parseOpeningTime($timeString){


		// determine which one of these 3 types this openingtime is
		// 1. Ma 13:00-14:00
		// 2. Ma-Ke 13:00-14:00
		// 3. Ma 13:00-14:00 ja 14:30-16:00
		// 4. Ma-Ke 13:00-14:00 ja 14:30-16:00

		$type = null;

	}

	// Takes a string of format hh:mm-hh:mm and calculates
	// the total number of hours


}

function calculateHours($hourInterval){

		$hoursArray = preg_split("#-#", $hourInterval);
		$datetime1 = new DateTime($hoursArray[0]);
		$datetime2 = new DateTime($hoursArray[1]);
		$interval = $datetime1->diff($datetime2);

		// TODO: account for the minutes in calculating total hours 
		//echo $interval->format('%h hours and %i minutes');

		$minutesToHours = $interval->format('%i')/60;

		$hoursAndMinutesCombined = $minutesToHours + $interval->format('%h');

		return $hoursAndMinutesCombined;

		// echo "These opening times amount to " . $hoursAndMinutesCombined . " hours.\n";

		// var_dump($matches);	
		// var_dump($interval);

	} 


function calculateDays($dayInterval){

		$daysOfWeekArray = preg_split("#-#", $dayInterval);
		$daysOfWeekArray = translateDayName($daysOfWeekArray, 'en');

		// find out difference between two DateTime objects

		$openingDay1 = DateTime::createFromFormat('D', $daysOfWeekArray[0]);
		$openingDay2 = DateTime::createFromFormat('D', $daysOfWeekArray[1]);

		$interval = $openingDay1->diff($openingDay2);
		$totalDays = $interval->format('%a') . "\n";
			
		//diff interprets Mon-Wed as 2 days difference, although in our case it's 3. 
		$totalDays += 1;

		return $totalDays;

	} 



// get it into an array
$lines = new CsvReader(dirname(__FILE__).'/ravintolatPieni.csv', $separator=";");

//$lines = new CsvReader(dirname(__FILE__).'/ravintolat.csv', $separator=";");


/*

This block parses the opening times into arrays and sorts out dates.

Put this in a class


different types of dates:

1. Ma 13:00-14:00
Regexp: [a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+

2. Ma-Ke 13:00-14:00
Regexp: [a-zA-Z]{2}-[a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+

3. Ma 13:00-14:00 ja 14:30-16:00
Regexp: [a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+ ja [0-9]+:[0-9]+-[0-9]+:[0-9]+

4. Ma-Ke 13:00-14:00 ja 14:30-16:00
Regexp: [[a-zA-Z]{2}-[a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+ ja [0-9]+:[0-9]+-[0-9]+:[0-9]+

*/

foreach ($lines as $key => $values) {

	// the master hour counter. This is restarted at every iteration.
	// at the end of the iteration, the $lines array needs to have a 
	// new key containing this count.
	$hourCount = 0;

	//var_dump($values);

	//get both day and opening times into an array
	$openingTimes = preg_split("(, )", $values[4]);

	echo $values[4] . "\n"; 
	//var_dump($openingTimes);

	foreach ($openingTimes as $key => $openingTime) {



		$openingTimeRegOne = "#^[a-zA-Z]{2}[\s{1}][0-9]+:[0-9]+-[0-9]+:[0-9]+$#";
		$openingTimeRegTwo = "#[a-zA-Z]{2}-[a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+#";
		$openingTimeRegThree = "#[a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+ ja [0-9]+:[0-9]+-[0-9]+:[0-9]+#";
		$openingTimeRegFour = "#[a-zA-Z]{2}-[a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+ ja [0-9]+:[0-9]+-[0-9]+:[0-9]+#";
		
		if(preg_match($openingTimeRegOne, $openingTime, $matches) == 1){

			//var_dump($matches);

			//echo "match1--";
			// what to do with openingtime of type 1?
			// split it with the " " pattern.
			// we know these hours are only for one day, so it's pretty simple.

			$dayAndTimeArray = preg_split("# #", $matches[0]);
			$hoursOpen = calculateHours($dayAndTimeArray[1]);

			//echo "These opening times amount to " . $hoursOpen . " hours.\n";

			$dayAndTimeArray = null;
			$hoursOpen = null;


		}

		if(preg_match($openingTimeRegTwo, $openingTime, $matches) == 1)
		{

			//var_dump($matches);

			//echo "match2--";
			// type 2?
			// we need to split this in half by the "-" pattern.
			// this is trickier, since we know that these days can be one or more.

			$dayAndTimeArray = preg_split("# #", $matches[0]);
			
			$totalDays = calculateDays($dayAndTimeArray[0]);
			$hoursOpenPerDay = calculateHours($dayAndTimeArray[1]);

			$totalHoursOpen = $hoursOpenPerDay * $totalDays;

			//echo "Total Hours Open: " . $totalHoursOpen . " during " . $totalDays . " days. \n";
			
		}

		if(preg_match($openingTimeRegThree, $openingTime, $matches) == 1){

			echo "match3--";

			$dayAndTimeArray = preg_split("# | ja #", $matches[0]);	
			
			var_dump($dayAndTimeArray);

			
		}

		if(preg_match($openingTimeRegFour, $openingTime, $matches) == 1){

			echo "match4--";

			$dayAndTimeArray = preg_split("# | ja #", $matches[0]);	

			$totalDays = calculateDays($dayAndTimeArray[0]);

			// TODO: figure out hourly difference between two time ranges, 
			// e.g. 13:00-15:00 and 15:30-17:00
			// could use that regexp
			
			var_dump($dayAndTimeArray);

			// type 3?
			// here we have to get the two hour units separately.
			// so, we divide them with the " ja " pattern.

			
		}

		

	}
	
echo "\n";

	//var_dump($values);
}


?>


