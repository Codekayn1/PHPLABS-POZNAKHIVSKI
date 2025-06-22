<?php
function celsiusToFahrenheit($celsius) {
    return $celsius * 9/5 + 32;
}

// Приклад використання
$celsius = 25;
$fahrenheit = celsiusToFahrenheit($celsius);
echo "$celsius °C = $fahrenheit °F";
?>
