<?php

function formatMinutes($minutes) {
    $lastDigit = $minutes % 10;
    $lastTwoDigits = $minutes % 100;
    
    if ($lastTwoDigits >= 11 && $lastTwoDigits <= 14) {
        $word = 'минут';
    } elseif ($lastDigit == 1) {
        $word = 'минуту';
    } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
        $word = 'минуты';
    } else {
        $word = 'минут';
    }
    
    return $minutes . ' ' . $word;
}