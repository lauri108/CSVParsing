<?php

function translateDayName($dayName, $lang=LANG)
{
    $days['Ma'] = array('en'=>'Mon');
    $days['Ti'] = array('en'=>'Tue');
    $days['Ke'] = array('en'=>'Wed');
    $days['To'] = array('en'=>'Thu');
    $days['Pe'] = array('en'=>'Fri');
    $days['La'] = array('en'=>'Sat');
    $days['Su'] = array('en'=>'Sun');

    foreach ($days as $key => $val)
        $dayName = str_replace($key, $val[$lang], $dayName);

    return $dayName;
}

?>