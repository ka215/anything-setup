<?php
/**
 * Anythig Setup APIs
 */

/**
 * Inserting elements at the specified position in the array, and returns an array of post-insertion
 * 
 * @param array &$base_array : Insert the destination array
 * @param mixed $insert_value : Insert values (string, number, array)
 * @param int $position : Insertion position (Optional. Top 0, default is inserted into the array at the end)
 * @return boolean : Return true when successfully inserted
 **/
function atsu_array_insert(&$base_array, $insert_value, $position=null) {
	if (!is_array($base_array)) 
		return false;
	$position = is_null($position) ? count($base_array) : intval($position);
	$base_keys = array_keys($base_array);
	$base_values = array_values($base_array);
	if (is_array($insert_value)) {
		$insert_keys = array_keys($insert_value);
		$insert_values = array_values($insert_value);
	} else {
		$insert_keys = array(0);
		$insert_values = array($insert_value);
	}
	$insert_keys_after = array_splice($base_keys, $position);
	$insert_values_after = array_splice($base_values, $position);
	foreach ($insert_keys as $insert_keys_value) {
		array_push($base_keys, $insert_keys_value);
	}
	foreach ($insert_values as $insert_values_value) {
		array_push($base_values, $insert_values_value);
	}
	$base_keys = array_merge($base_keys, $insert_keys_after);
	$is_key_numric = true;
	foreach ($base_keys as $key_value) {
		if (!is_integer($key_value)) {
			$is_key_numric = false;
			break;
		}
	}
	$base_values = array_merge($base_values, $insert_values_after);
	if ($is_key_numric) {
		$base_array = $base_values;
	} else {
		$base_array = array_combine($base_keys, $base_values);
	}
	return true;
}

/**
 * To remove the elements of the specified number as length from any position of the array, and returns an array of post-delete
 * 
 * @param array &$base_array : Delete the destination array
 * @param int $delete_position : Element position to start the Delete
 * @param int $delete_items : Remove to the number of elements (remove Optional. Only the default one)
 * @param boolean $reroll_index : Whether rerolling index subscript of the array after the deletion (Optional. The default is true to rerolling. If the target array does not have numerically index, done nothing)
 * @return boolean : Return true when successfully deleted
 **/
function atsu_array_delete(&$base_array, $delete_position=null, $delete_items=1, $reroll_index=true) {
	if (!is_array($base_array)) 
		return false;
	if (is_null($delete_position) || !is_integer($delete_position)) 
		return false;
	if (!is_integer($delete_items) || intval($delete_items) == 0) 
		return false;
	$index_num = 0;
	foreach ($base_array as $key => $value) {
		if ($delete_position == $index_num) {
			unset($base_array[$key]);
			$delete_items--;
			$delete_position++;
		}
		if ($delete_items == 0) {
			break;
		}
		$index_num++;
	}
	$is_key_numric = true;
	foreach (array_keys($base_array) as $key_value) {
		if (!is_integer($key_value)) {
			$is_key_numric = false;
			break;
		}
	}
	if ($is_key_numric && $reroll_index) {
		$base_array = array_merge($base_array, array());
	}
	return true;
}


/**
 * compare the variable
 *
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
 *
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
 *
 * @param string $string
 * @return string
 */
function atsu__($string) {
	return __($string, PLUGIN_SLUG);
}

/**
 * output for echo translated strings
 *
 * @param string $string
 * @return void
 */
function atsu_e($string) {
	_e($string, PLUGIN_SLUG);
}
