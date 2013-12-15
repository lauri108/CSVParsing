<?php
require_once('csv.php');
setlocale(LC_TIME, "fi_FI");
date_default_timezone_set('Europe/Helsinki');


// get it into an array
$lines = new CsvReader(dirname(__FILE__).'/ravintolat.csv', $separator=";");

/*

This block parses the opening times into arrays and sorts out dates.

*/

foreach ($lines as $line_number => $values) {

	//get the opening times into an array
	$openingTimes = explode(", ", $values[4]);
	//var_dump($openingTimes);

	foreach ($openingTimes as $key => $openingTime) {
	
		$datesArray = explode(" ", $openingTime);
		//var_dump($datesArray);

		// array_values($datesArray)[0];

		// less to parse since we only need the first item
		$datesArray = array_shift(array_slice($datesArray, 0, 1));

		//var_dump($datesArray);

		$daysOfWeekArray = explode("-", $datesArray);

		var_dump($daysOfWeekArray);

		if (count($daysOfWeekArray) > 1)
		{

			//replace these with parsed day differences
			$openingDay1 = DateTime::createFromFormat('D', "Mon");
			$openingDay2 = DateTime::createFromFormat('D', "Wed");

			$interval = $openingDay1->diff($openingDay2);
			echo $interval->format('%R%a days');

		}

		//foreach ($datesArray as $key => $value) {
			
			// take the first item in this array, and 
			// split it using the "-" separator two days of week and 
			// run them through a date parser,
			// finding out the amount of days it contains.

			//$daysOfWeekArray = explode("-", $value);

			//foreach ($daysOfWeekArray as $key => $value) {
			
				//$day1 = DateTime::createFromFormat('D', $value[0]);
				//$day2 = 
			
			//}


			//var_dump($daysOfWeekArray);

		//}

	}
	
	//var_dump($values);
}


?>


