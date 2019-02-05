<?php
/**
 * Class helper for array
 * @author darkfriend <hi@darkfriend.ru>
 * @version 1.2.0
 */

namespace darkfriend\devhelpers;

class ArrayHelper
{
	/**
	 * Checks if a value exists in an array.
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
}