<?php
/**
 * Anythig Set Upper APIs
 */

/**
 * compare the variable
 * @param mixed(string|int|boolean) $var
 * @param mixed(string|int|boolean) $compare
 * @return boolean
 */
function atsu_compare_var($var, $compare=null) {
	if ((string)$var === (string)$compare) {
		return true;
	} else {
		return false;
	}
}

/**
 * return boolean
 * @param string $string
 * @return boolean
 */
function atsu_get_boolean($string) {
	if (is_bool($string)) {
		return $string;
	} else {
		if (empty($string)) {
			return false;
		} else if (is_string($string)) {
			return ($string == 'false' || $string == '0') ? false : true;
		} else if (is_int($string)) {
			return $string == 0 ? false : true;
		} else {
			return false;
		}
	}
}

/**
 * return translated strings
 * @param string $string
 * @return string
 */
function atsu__($string) {
	return __($string, PLUGIN_SLUG);
}

/**
 * output for echo translated strings
 * @param string $string
 * @return void
 */
function atsu_e($string) {
	_e($string, PLUGIN_SLUG);
}
