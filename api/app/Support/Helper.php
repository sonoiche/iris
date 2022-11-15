<?php

function validDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $date != '0000-00-00' && $d && $d->format($format) === $date;
}