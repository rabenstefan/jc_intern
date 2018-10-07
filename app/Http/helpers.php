<?php
/**
 * Some custom helper functions
 */


/**
 * If $value is in array, remove it, otherwise add it.
 *
 * @param array $array
 * @param $value
 * @param boolean strict
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
 * LaTeX: $bigger \setMinus $smaller
 *
 * shows the difference between $bigger and $smaller, but ignores everything in $smaller which is not in $bigger
 *
 * @param array $bigger
 * @param array $smaller
 * @return array
 */
function set_minus(array $bigger, array $smaller) {
    return array_diff($bigger, array_intersect($bigger, $smaller));
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

/**
 * Shorten a string if it is longer than $length. If a string has been shortened, $indicator will be appended.
 *
 * Input and ouput will be trimmed.
 *
 * @param String $string
 * @param int $length
 * @param String $indicator
 * @return string
 */
function str_shorten(String $string, int $length, String $indicator = '') {
    $trimmed_input = trim($string);
    $result = trim(mb_substr($trimmed_input, 0, $length));
    if ($result != $trimmed_input) {
        $result .= $indicator;
    }
    return $result;
}


/**
 * Compare the given date types to the available ones and return the inverse.
 * If a date type is unknown, it will be dropped.
 *
 * @param array $date_types
 * @return array
 */
function invert_date_types (array $date_types) {
    return set_minus(\App\Http\Controllers\DateController::getDateTypes(), $date_types);
}

/**
 * Compare the given date statuses to the available ones and return the inverse.
 * If a date status is unknown, it will be dropped.
 *
 * @param array $date_statuses
 * @return array
 */
function invert_date_statuses (array $date_statuses) {
    return set_minus(\App\Http\Controllers\DateController::getDateStatuses(), $date_statuses);
}

const ATOMIC_LOCK_STRING = "___ATOMIC_LOCK___";

/**
 * Provides simple atomic locks for the cache.
 *
 *
 * If a value is stored under the given key, it will be returned (and $generate_new_result will not be executed).
 *
 * If no value is stored under the given key, a new value will be generated using $generate_new_result which will be saved in cache and returned.
 * If $generate_new_result throws an exception, the atomic lock will be released and the exception will be re-thrown.
 *
 * If another instance already claimed the atomic lock, abort(500) will be called (and $generate_new_result will not be executed).
 *
 * Upon defining $generate_new_result, you may use pass-by-reference for its parameters.
 * This is especially useful to change $cache_expiry_time. $cache_expiry time can be a number of minutes or a DateTime-object of the expiration time.
 * Be aware that changing $cache_key may make your cache unusable. Changing $atomic_lock_time will have no effect.
 *
 * Example:
 *
 * $cached_result = cache_atomic_lock_provider($my_key, function ($cache_key, &$cache_expiry_time, $atomic_lock_time) {
 *      // Generate your $uncached_result. Upon generating it, you find it will be valid until $my_valid_time
 *      $cache_expiry_time = $my_valid_time; // Can be a DateTime object or a number (float/int) of minutes.
 *      return $uncached_result;
 * });
 *
 * @param $cache_key key, under which our value is stored
 * @param callable $generate_new_result function to generate a new value
 * @param \DateTime|float|int $cache_expiry_time in minutes, defaults to 60
 * @param \DateTime|float|int $atomic_lock_time in minutes, should be at least as long as an execution of $generate_new_result, defaults to 1
 * @return mixed the cached or newly generated value
 */
function cache_atomic_lock_provider($cache_key, callable $generate_new_result, $cache_expiry_time = 60, $atomic_lock_time = 1) {
    if (!\Cache::add($cache_key, ATOMIC_LOCK_STRING, $atomic_lock_time)) {
        $result = \Cache::get($cache_key);
        if (ATOMIC_LOCK_STRING === $result) {
            // Cache has been locked by another instance
            abort(500,"We are under heavy load. Please try again in one minute.");
        }
    } else {
        // Cache was empty, we have the atomic lock on the cache.
        try {
            $result = $generate_new_result($cache_key, $cache_expiry_time, $atomic_lock_time);
            \Cache::put($cache_key, $result, $cache_expiry_time);
        } catch (\Throwable $e) {
            // Release lock and re-throw exception
            \Cache::forget($cache_key);
            throw $e;
        }
    }

    return $result;
}

