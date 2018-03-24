<?php
/**
 * Some custom helper functions
 */


/**
 * If $value is in array, remove it, otherwise add it.
 *
 * @param array $array
 * @param $value
 * @return array
 */
function array_xor_value(array $array, $value, bool $strict = true) {
    $position = array_search($value, $array, $strict);
    if (false === $position) {
        $array[] = $value;
    } else {
        $array = array_except($array, $position);
    }
    return $array;
}


/**
 * Returns the power set of a one dimensional array, a 2-D array.
 * [a,b,c] -> [ [a], [b], [c], [a, b], [a, c], [b, c], [a, b, c] ]
 *
 * @param array $in
 * @param int $minLength
 * @return array
 */
function power_set(array $in, int $minLength = 1) {
    $count = count($in);
    $members = pow(2,$count);
    $return = array();
    for ($i = 0; $i < $members; $i++) {
        $b = sprintf("%0".$count."b",$i);
        $out = array();
        for ($j = 0; $j < $count; $j++) {
            if ($b{$j} == '1') $out[] = $in[$j];
        }
        if (count($out) >= $minLength) {
            $return[] = $out;
        }
    }
    return $return;
}