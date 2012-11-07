<?php

/**
 * For WooCommerce 1.4.2 and above
 * HypnoticZoo shipping utilities
 * Author Andy Zhang
 * Company: Hypnotic Zoo
 * Company Url: hypnoticzoo.com
 */
/**
 *
 * Normalise dimensions, unify to cm then convert to wanted unit value
 * $unit: 'in', 'm', 'cm', 'm'
 * Usage: wooDimNormal(55, 'in');
 *
 */
if (!function_exists('hzDimNormal')) {

	function hzDimNormal($dim, $unit) {

		$wooDimUnit = strtolower($current_unit = get_option('woocommerce_dimension_unit'));
		$unit = strtolower($unit);

		if ($wooDimUnit !== $unit) {
//Unify all units to cm first
			switch ($wooDimUnit) {
				case 'in':
					$dim *= 2.54;
					break;
				case 'm':
					$dim *= 100;
					break;
				case 'mm':
					$dim *= 0.1;
					break;
			}

//Output desired unit
			switch ($unit) {
				case 'in':
					$dim *= 0.3937;
					break;
				case 'm':
					$dim *= 0.01;
					break;
				case 'mm':
					$dim *= 10;
					break;
			}
		}
		return ($dim < 0.001) ? 0.001 : $dim;
	}

}

/**
 *
 * Normalise weight, unify to kg then convert to wanted to unit
 * $unit: 'g', 'kg', 'lbs'
 * Useage: wooWeightNormal(55,'lbs');
 *
 */
if (!function_exists('hzWeightNormal')) {

	function hzWeightNormal($weight, $unit) {

		$wooWeightUnit = strtolower($current_unit = get_option('woocommerce_weight_unit'));
		$unit = strtolower($unit);

		if ($wooWeightUnit !== $unit) {
			//Unify all units to kg first
			switch ($wooWeightUnit) {
				case 'g':
					$weight *= 0.001;
					break;
				case 'lbs':
					$weight *= 0.4535;
					break;
			}

			//Output desired unit
			switch ($unit) {
				case 'g':
					$weight *= 1000;
					break;
				case 'lbs':
					$weight *= 2.204;
					break;
			}
		}
		return ($weight < 0.1) ? 0.1 : $weight;
	}

}
?>