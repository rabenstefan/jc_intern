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