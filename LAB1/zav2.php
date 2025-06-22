<?php
$year = 2024;

if (($year % 4 == 0 && $year % 100 != 0) || ($year % 400 == 0)) {
    echo "$year рік є високосним.";
} else {
    echo "$year рік не є високосним.";
}
?>
