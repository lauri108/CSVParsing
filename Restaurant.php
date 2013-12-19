<?php

setlocale(LC_TIME, "fi_FI");
date_default_timezone_set('Europe/Helsinki');

	/**
		* Counts the number of hours in an hour range.
		*
		* @param Array $arrayItem An array containing restaurant elements.
		* @author Lauri Jalonen, tapani108@gmail.com
		*/

    class Restaurant {
		
		## ID;RESTAURANT NAME;POSTCODE;CITY;OPEN HOURS;LATITUDE;LONGITUDE
	
		protected $_id = null;
		protected $_restaurantName = null;
		protected $_postcode = null;
		protected $_city = null;
		protected $_openingTimes = null;
		protected $_openingHoursPerWeekTotal = null;
		protected $_latitude = null;
		protected $_longitude = null;
		    	 
		public function __construct($arrayItem) {
			
			$this->_id = $arrayItem[0];
			$this->_restaurantName = $arrayItem[1];
			$this->_postcode = $arrayItem[2];
			$this->_city = $arrayItem[3];
			$this->_openingTimes = $arrayItem[4];
			$this->_latitude = $arrayItem[5];
			$this->_longitude = $arrayItem[6];		
			$this->_openingHoursPerWeekTotal = $this->createTotalOpeningHoursPerWeek($arrayItem[4]);
		
		}
	
		public function __destruct() {
			$this->close();
		}
		
		public function close() {
		//
		}
		
		public function getName(){
			
			return $this->_restaurantName;
			
		}
		
		public function getOpeningHoursPerWeekTotal (){
			
			return $this->_openingHoursPerWeekTotal;
			
		}
		
		/**
		* Gets the opening times.
		*
		* @param string $openingTimesString String in the format "DD[-DD] HH:MM-HH:MM [ja HH:MM-HH:MM]".
		* @author Lauri Jalonen, tapani108@gmail.com
		* @return int Returns the total number of hours.
		* 
		*/
		public function createTotalOpeningHoursPerWeek($openingTimesString){
	
			$openingHoursTotal = null;
			
			//get both day and opening times into an array
			$openingTimes = preg_split("(, )", $openingTimesString);
			
			foreach ($openingTimes as $key => $openingTime) {
		
				// seaparate the opening day(s) and time(s) into an array
				$dayAndTimeArray = preg_split("# #", $openingTime);
								
				// some basic variables
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
					$openingDayMultiplier = $this->calculateDaysRange($openingDaysMatch[0]);
				}
				
				// this RegEx finds an opening time range of format HH:MM-HH:MM 
				$openingTimePeriodRegEx = "#[0-9]{2}:[0-9]{2}-[0-9]{2}:[0-9]{2}#";
				$allOpeningTimePeriods = preg_grep($openingTimePeriodRegEx, $dayAndTimeArray);
				
				foreach ($allOpeningTimePeriods as $key => $openingTimePeriod) {
					$totalHours += $this->calculateHours($openingTimePeriod);
				}
					
				$openingHoursTotal += ($totalHours * $openingDayMultiplier);
					
			}
			
			//$this->_openingHoursPerWeekTotal = $hoursOpenTotal;
			return $openingHoursTotal;
	
		} 
		
		/**
		* Counts the number of hours in an hour range.
		*
		* @param string $dayInterval String in the format "HH:MM-HH:MM".
		* @author Lauri Jalonen, tapani108@gmail.com
		* @return int Returns the number of hours.
		* 
		*/
		public function calculateHours($hourInterval){
	
			$hoursArray = preg_split("#-#", $hourInterval);
			
			$firstHoursAndMinutesArray = preg_split("#:#",$hoursArray[0]);
			$firstHour = $firstHoursAndMinutesArray[0] + $firstHoursAndMinutesArray[1]/60;
			
			$secondHoursAndMinutesArray = preg_split("#:#",$hoursArray[1]);
			$secondHour = $secondHoursAndMinutesArray[0] + $secondHoursAndMinutesArray[1]/60;
							
			$hoursAndMinutesCombined = $secondHour - $firstHour;
	
			return $hoursAndMinutesCombined;
	
		} 


		/**
		  * Counts the number of days in a day range.
		  *
		  * @param string $dayInterval String in the format "Mon-Wed".
		  * @author Lauri Jalonen, tapani108@gmail.com
		  * @return int Returns the number of elements.
		 *  @todo localize the $weekdayNumbers array.
		  * 
		  */
	  
		public function calculateDaysRange($dayInterval){
				
			$weekdayNumbers = array("Ma"=>"1", "Ti"=>"2", "Ke"=>"3", "To"=>"4", "Pe"=>"5","La"=>"6","Su"=>"7");
			$totalDays = null;
			
			$daysOfWeekArray = preg_split("#-#", $dayInterval);
			
			$firstDay = $daysOfWeekArray[0];
			$secondDay = $daysOfWeekArray[1];
			
			$totalDays = $weekdayNumbers[$secondDay] - $weekdayNumbers[$firstDay];
	
			$totalDays += 1;
	
			return $totalDays;
	
		} 
		
    }
?>