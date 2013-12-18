<?php
require_once('csv.php');
setlocale(LC_TIME, "fi_FI");
date_default_timezone_set('Europe/Helsinki');

/**
  * Counts the number of hours in an hour range.
  *
  * @param string $dayInterval String in the format "HH:MM-HH:MM".
  * @author Lauri Jalonen, tapani108@gmail.com
  * @return int Returns the number of hours.
  * 
  */
function calculateHours($hourInterval){

		$hoursArray = preg_split("#-#", $hourInterval);
		$datetime1 = new DateTime($hoursArray[0]);
		$datetime2 = new DateTime($hoursArray[1]);
		$interval = $datetime1->diff($datetime2);

		$minutesToHours = $interval->format('%i')/60;

		$hoursAndMinutesCombined = $minutesToHours + $interval->format('%h');

		return $hoursAndMinutesCombined;

	} 


/**
  * Counts the number of days in a day range.
  *
  * @param string $dayInterval String in the format "Mon-Wed".
  * @author Lauri Jalonen, tapani108@gmail.com
  * @return int Returns the number of elements.
  * 
  */
  
function calculateDaysRange($dayInterval){

		// used for calculating the difference of two weekdays
		$weekdayNumbers = array("Ma"=>"1", "Ti"=>"2", "Ke"=>"3", "To"=>"4", "Pe"=>"5","La"=>"6","Su"=>"7");
		$totalDays = null;
		
		$daysOfWeekArray = preg_split("#-#", $dayInterval);
		
		$firstDay = $daysOfWeekArray[0];
		$secondDay = $daysOfWeekArray[1];
		
		$totalDays = $weekdayNumbers[$secondDay] - $weekdayNumbers[$firstDay];

		$totalDays += 1;

		return $totalDays;

	} 



// Parse CSV
$lines = new CsvReader(dirname(__FILE__).'/ravintolat.csv', $separator=";");

// Set vars for min and max opening times
$leastOpeningHours = 10000;
$leastOpenRestaurantName = "";

$mostOpenRestaurantName = "";
$mostOpeningHours = 0;

foreach ($lines as $key => $values) {

	// the master hour counter. This is restarted at every iteration.
	// at the end of the iteration, the $lines array needs to have a 
	// new key containing this count.
	$hourCount = 0;
	

	//get both day and opening times into an array
	$openingTimes = preg_split("(, )", $values[4]);

	foreach ($openingTimes as $key => $openingTime) {
		
		$dayAndTimeArray = preg_split("# #", $openingTime);
		
		// first we extract the day(s).
		// by default it's one day.
		
		$thereIsAnOpeningDaysRange = false;
		$openingDayMultiplier = 1;
		$openingDaysMatch = array();
		$totalHours = 0;
		
		// this RegExp checks for a day range instead of one day
		
		$moreOpeningDaysRegEx = "#^[a-zA-Z]{2}-[a-zA-Z]{2}$#";
		$openingDaysMatch = preg_grep($moreOpeningDaysRegEx, $dayAndTimeArray);
		
		$thereIsAnOpeningDaysRange = count($openingDaysMatch) == 0 ? false : true;
	
		if($thereIsAnOpeningDaysRange)
		{
			$openingDayMultiplier = calculateDaysRange($openingDaysMatch[0]);
		}
		
		$allOpeningTimePeriods = preg_grep("#[0-9]{2}:[0-9]{2}-[0-9]{2}:[0-9]{2}#", $dayAndTimeArray);
		
		foreach ($allOpeningTimePeriods as $key => $openingTimePeriod) {
			$totalHours += calculateHours($openingTimePeriod);
		}
			
		$hoursOpenTotal = $totalHours * $openingDayMultiplier;
		
		$hourCount += $hoursOpenTotal;

	}

	if($hourCount > $mostOpeningHours){
		
		$mostOpeningHours = $hourCount;
		$mostOpenRestaurantName = $values[1];
		
	}
	
	if($hourCount < $leastOpeningHours){
		
		$leastOpeningHours = $hourCount;
		$leastOpenRestaurantName = $values[1];
		
	}
		
	$foo = "bar"; // breakpoint
}

print_r("---------" . PHP_EOL);
print_r($mostOpenRestaurantName . ", open " . $mostOpeningHours . " hours a week");
print_r(PHP_EOL);
print_r($leastOpenRestaurantName . ", open " . $leastOpeningHours . " hours a week");
print_r(PHP_EOL . "---------");

?>


