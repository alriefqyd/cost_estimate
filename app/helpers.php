<?php

if (!function_exists('sum_factorial')) {
    function sum_factorial($a, $b)
    {
        if($b == 0 || $b == null) $b = 1;
        $total = toCurrency($a) * $b;
        return number_format($total, 2, '.', ',');
    }
}

if(!function_exists('toCurrency')) {
     function toCurrency($val){
        if(!$val) return '';
        return number_format($val, 2);
    }
}

if(!function_exists('strToCurr')) {
    function strToCurr($val){
        if(!$val) return '';
        $newVal = str_replace(',','',$val);
        return $newVal;
    }
}



