<?php

	/**
	* A helper class to organize restaurant information
	*
	* @param Array $arrayItem An array containing restaurant elements:
	 * ID;RESTAURANT NAME;POSTCODE;CITY;OPEN HOURS;LATITUDE;LONGITUDE
	 *
	* @author Lauri Jalonen, tapani108@gmail.com
	*/

    class Restaurant {
			
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
			$this->_openingHoursPerWeekTotal = $this->calculateTotalOpeningHoursPerWeek($arrayItem[4]);
			
			if($this->_restaurantName == null)
				throw new Exception("Restaurant with id {$this->_id}: name not available");
				
			if($this->_openingHoursPerWeekTotal == 0)
				throw new Exception("Couldn't parse the opening time for restaurant {$this->_restaurantName}");

		}
	
		public function __destruct() {
			$this->close();
		}
		
		public function close() {
	
		}
		
		public function getName(){
			
			return $this->_restaurantName;
			
		}
		
		public function getOpeningHoursPerWeekTotal (){
			
			return $this->_openingHoursPerWeekTotal;
			
		}
		
		public function setOpeningHoursPerWeekTotal ($amountOfHours) {
			
			$this->_openingHoursPerWeekTotal = $amountOfHours;
			
		}
		
		public function setName ($nameToUse) {
			
			$this->_restaurantName = $nameToUse;
			
		}
		
		/**
		 * Uses another Restaurant to set name and opening hour values of this restaurant
		 */
		
		public function updateNameAndOpeningHours(Restaurant $restaurantToUseForValues){
			
			$this->setOpeningHoursPerWeekTotal($restaurantToUseForValues->getOpeningHoursPerWeekTotal());
			$this->setName($restaurantToUseForValues->getName());	
		}
		
		/**
		* Translates an opening days and hours string into a weekly hour total.
		*
		* @param string $openingTimesString String in the format 
		 * "DD[-DD] HH:MM-HH:MM [ja HH:MM-HH:MM][,DD[-DD] HH:MM-HH:MM [ja HH:MM-HH:MM]]".
		* @author Lauri Jalonen, tapani108@gmail.com
		* @return int Returns the total number of weekly opening hours.
		* 
		*/
		public function calculateTotalOpeningHoursPerWeek($openingTimesString){
			
			
			if(($openingTimesString == null) || ($openingTimesString == "")) {
				
				throw new Exception("No opening times string given for Restaurant {$this->getName()}");
				
			}
				
			$openingHoursTotal = null;
			
			//get all opening day+time snippets into an array
			$openingDayTimes = preg_split("(, )", $openingTimesString);
			
			foreach ($openingDayTimes as $key => $openingTime) {
		
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
			
			if($hourInterval == null)
				throw new Exception("No hour interval given");
				
			$hoursArray = preg_split("#-#", $hourInterval);
			
			$firstHoursAndMinutesArray = preg_split("#:#",$hoursArray[0]);
			$firstHour = $firstHoursAndMinutesArray[0] + $firstHoursAndMinutesArray[1]/60;
			
			$secondHoursAndMinutesArray = preg_split("#:#",$hoursArray[1]);
			$secondHour = $secondHoursAndMinutesArray[0] + $secondHoursAndMinutesArray[1]/60;
							
			$hoursAndMinutesCombined = $secondHour - $firstHour;
			
			if($hoursAndMinutesCombined == 0)
				throw new Exception("Restaurant {$this->getName()} has an opening time of 0 hours");
				
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
			
			if($dayInterval == null)
				throw new Exception("No day interval given");
				
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