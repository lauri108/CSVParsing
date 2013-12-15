<?php
require_once('csv.php');
require_once('daySwapper.php');
require_once('array_column.php');
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

	protected function _parseOpeningTime($timeString){


		// determine which one of these 3 types this openingtime is
		// 1. Ma 13:00-14:00
		// 2. Ma-Ke 13:00-14:00
		// 3. Ma 13:00-14:00 ja 14:30-16:00

		$type = null;



	}

}


// get it into an array
$lines = new CsvReader(dirname(__FILE__).'/ravintolatPieni.csv', $separator=";");

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

*/

foreach ($lines as $key => $values) {

	//var_dump($values);

	//get both day and opening times into an array
	$openingTimes = preg_split("(, )", $values[4]);

	//var_dump($openingTimes);

	foreach ($openingTimes as $key => $openingTime) {


		$openingTimeRegOne = "#[a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+#";
		$openingTimeRegTwo = "#[a-zA-Z]{2}-[a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+#";
		$openingTimeRegThree = "#[a-zA-Z]{2} [0-9]+:[0-9]+-[0-9]+:[0-9]+ ja [0-9]+:[0-9]+-[0-9]+:[0-9]+#";

		if(preg_match($openingTimeRegOne, $openingTime, $matches) == 1){

			// what to do with openingtime of type 1?
			// split it with the " " pattern.
			// we know these hours are only for one day, so it's pretty simple.

		}

		if(preg_match($openingTimeRegTwo, $openingTime, $matches) == 1)
		{

			// type 2?
			// we need to split this in half by the "-" pattern.
			// this is trickier, since we know that these days can be one or more.

			$dayAndTimeArray = preg_split("# #", $matches[0]);
			$daysOfWeekArray = preg_split("#-#", $dayAndTimeArray[0]);
			$daysOfWeekArray = translateDayName($daysOfWeekArray, 'en');

			var_dump($daysOfWeekArray);

			$hoursArray = preg_split("#-#", $dayAndTimeArray[1]);

			var_dump($hoursArray);

			
			// get multiple day
			$openingDay1 = DateTime::createFromFormat('D', $daysOfWeekArray[0]);
			$openingDay2 = DateTime::createFromFormat('D', $daysOfWeekArray[1]);

			$interval = $openingDay1->diff($openingDay2);
			$daysString = $interval->format('%a') . "\n";
			
			//diff interprets Mon-Wed as 2 days difference, although in our case it's 3. 
			$daysString += 1;
			$hoursmultiplier = $daysString;
			
			$daysString .= " times the opening time in the next array " . $daysOfWeekArray[0] . "-" . $daysOfWeekArray[1] . "  \n";
			print_r($daysString);

			// TODO: get the hours from $hoursArray and do some datetime math to get duration.
			// then, just multiply it with $hoursMultiplier.
			
		}

		if(preg_match($openingTimeRegThree, $openingTime, $matches) == 1){


			// type 3?
			// here we have to get the two hour units separately.
			// so, we divide them with the " ja " pattern.

			
		}


	}
	
	//var_dump($values);
}


?>


