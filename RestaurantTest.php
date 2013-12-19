<?php

require_once('Restaurant.php');

class RestaurantTest extends PHPUnit_Framework_TestCase
{
	
	//test array
	public $testRestaurant;
	public $restaurantObject;
	public $restaurantName;
	public $totalHours;
	
    public function setup()
    {
    	$testRestaurant = array('500700','ALAVUDEN RAVINTOLA', '63300', 'ALAVUS', 'Ma 09:30-12:30 ja 13:00-16:30, Ke 09:30-12:30 ja 13:00-16:30, Pe 09:30-12:30 ja 13:00-16:30','12.1231321', '23.232423');
        $this->restaurantObject = new Restaurant($testRestaurant);
    }
	
	public function testName (){
		
		$restaurantName = $this->restaurantObject->getName();
		$this->assertTrue($restaurantName == "ALAVUDEN RAVINTOLA");
		
	}
	
	public function testOpeningHoursPerWeek (){
		
		$totalHours = $this->restaurantObject->getOpeningHoursPerWeekTotal();
		$this->assertTrue($totalHours == "19.5");
		
	}
	
	public function testOpeningHoursParser (){
			
		// this should return 35
		$openingTimes1 = "Ma-Pe 09:30-16:30"; // should return 35
		
		// this should return 19.5
		$openingTimes2 = "Ma 09:30-12:30 ja 13:00-16:30, Ke 09:30-12:30 ja 13:00-16:30, Pe 09:30-12:30 ja 13:00-16:30"; 
		
		$totalHours = $this->restaurantObject->createTotalOpeningHoursPerWeek($openingTimes1);
		$this->assertTrue($totalHours == "35");
		
		$totalHours = $this->restaurantObject->createTotalOpeningHoursPerWeek($openingTimes2);
		$this->assertTrue($totalHours == "19.5");
		
	}
	
	public function testCalculateHours (){
			
		// this should return 7
		$hourInterval = "09:30-16:30"; 
		
		$calculatedHours = $this->restaurantObject->calculateHours($hourInterval);
		$this->assertTrue($calculatedHours == "7");
		
		
	}
	
	public function testCalculateDaysRange (){
			
		// this should return 4
		$daysInterval = "Ma-To"; 
		
		$calculatedDays = $this->restaurantObject->calculateDaysRange($daysInterval);
		$this->assertTrue($calculatedDays == "4");
		
		
	}
	
	
}
?>