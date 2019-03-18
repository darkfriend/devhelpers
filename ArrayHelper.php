<?php
/**
 * Class helper for array
 * @author darkfriend <hi@darkfriend.ru>
 * @version 1.2.1
 */

namespace darkfriend\devhelpers;

class ArrayHelper
{
	/**
	 * Check value exists in an array.
	 * Highload method searches haystack for needle.
	 * @param string $needle
	 * @param array $haystack
	 * @return bool
	 */
	public static function in_array($needle, $haystack) {
		$newHaystack = [];
		foreach(array_values($haystack) as $v)
			$newHaystack[$v] = true;
		return isset($newHaystack[$needle]);
	}

	/**
	 * Check multiple array
	 * @param array $arr
	 * @return bool
	 */
	public static function isMulti($arr) {
		rsort($arr);
		return isset($arr[0]) && is_array($arr[0]);
	}
}