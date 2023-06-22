<?php

namespace ACAT\Utils;

/**
 *
 */
class StringUtils {

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    static function startsWith(string $haystack, string $needle) : bool {
        return $needle === '' || strripos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    static function endsWith(string $haystack, string $needle) : bool {
        if ($needle === '') {
            return true;
        }
        $diff = strlen($haystack) - strlen($needle);
        return $diff >= 0 && stripos($haystack, $needle, $diff) !== false;

    }

	/**
	 * @param string $haystack
	 * @param string $needle
	 *
	 * @deprecated
	 *
	 * @return bool
	 */
    static function contains(string $haystack, string $needle) : bool {
		return str_contains($haystack, $needle);
    }

    /**
     * @param string|null $value
     * @return bool
     */
    static function isEmpty(?string $value) : bool {
        return $value == null || strlen($value) == 0;
    }

}