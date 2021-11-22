<?php
namespace filter;

function buildDate($type) {
    $time = time();

    switch($type) {
        case 1:
            $date = date("01.m.Y", $time);
            break;
        case 2:
            $date = date(sprintf("01.%s.Y", date('m') - 3), $time);
            break;
        case 3:
            $date = sprintf("01.01.%s", date("Y", $time) - 1);
            break;
    }

    $result = explode('.', $date);
    
    if( $result[1] < 9 && $result[1][0] != '0' ) {
        $result[1] = sprintf("%d%d", 0, $result[1]);
    }

    $result = sprintf("%s-%s-%s", $result[2], $result[1], $result[0]);

    return $result;
}