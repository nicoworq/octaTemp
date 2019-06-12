<?php

include_once 'Temperature.php';

$temperature = floatval(str_replace(",", '.', filter_input(INPUT_GET, 'temp', FILTER_SANITIZE_STRING)));


if ($temperature) {

    $Temp = new Temperature();

    if ($Temp->storeTemperature($temperature) > 0) {
        echo "INTERNAL STORED OK<br/>";
    } else {
        echo "INTERNAL STORED ERROR<br/>";
    }

    $externalTemp = $Temp->getOutsideTemperature();
    
    
    if ($Temp->storeOutsideTemperature($externalTemp['temperatura']) > 0) {
        echo "EXT STORED OK<br/>";
    } else {
        echo "EXT STORED ERROR<br/>";
    }
}

