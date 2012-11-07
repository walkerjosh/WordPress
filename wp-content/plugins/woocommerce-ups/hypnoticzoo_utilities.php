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

if(!function_exists('rate_compare')){
	function rate_compare($rate1, $rate2){
		return $rate1->cost < $rate2->cost;
	}
}

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


if (!class_exists('ArrayToXML')) {

	class ArrayToXML {

		/**
		 * The main function for converting to an XML document.
		 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
		 *
		 * @param array $data
		 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
		 * @param SimpleXMLElement $xml - should only be used recursively
		 * @return string XML
		 */
		public static function toXML($data, $rootNodeName = 'ResultSet', &$xml = null) {

			// turn off compatibility mode as simple xml throws a wobbly if you don't.
			if (ini_get('zend.ze1_compatibility_mode') == 1)
				ini_set('zend.ze1_compatibility_mode', 0);
			if (is_null($xml)) {
				$xml = simplexml_load_string("<$rootNodeName />");
				$rootNodeName = rtrim($rootNodeName, 's');
			}
			// loop through the data passed in.
			foreach ($data as $key => $value) {

				// no numeric keys in our xml please!
				$numeric = 0;
				if (is_numeric($key)) {
					$numeric = 1;
					$key = $rootNodeName;
				}

				// delete any char not allowed in XML element names
				$key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

				// if there is another array found recursively call this function
				if (is_array($value)) {
					$node = ( ArrayToXML::isAssoc($value) || $numeric ) ? $xml->addChild($key) : $xml;

					// recursive call.
					if ($numeric)
						$key = 'anon';
					ArrayToXML::toXml($value, $key, $node);
				} else {

					// add single node.
					$value = htmlentities($value);
					$xml->addChild($key, $value);
				}
			}

			// pass back as XML
			return $xml->asXML();

			// if you want the XML to be formatted, use the below instead to return the XML
			//$doc = new DOMDocument('1.0');
			//$doc->preserveWhiteSpace = false;
			//$doc->loadXML( $xml->asXML() );
			//$doc->formatOutput = true;
			//return $doc->saveXML();
		}

		/**
		 * Convert an XML document to a multi dimensional array
		 * Pass in an XML document (or SimpleXMLElement object) and this recrusively loops through and builds a representative array
		 *
		 * @param string $xml - XML document - can optionally be a SimpleXMLElement object
		 * @return array ARRAY
		 */
		public static function toArray($xml) {
			if (is_string($xml))
				$xml = new SimpleXMLElement($xml);
			$children = $xml->children();
			if (!$children)
				return (string) $xml;
			$arr = array();
			foreach ($children as $key => $node) {
				$node = ArrayToXML::toArray($node);

				// support for 'anon' non-associative arrays
				if ($key == 'anon')
					$key = count($arr);

				// if the node is already set, put it into an array
				if (array_key_exists($key, $arr) && isset($arr[$key])) {
					if (!is_array($arr[$key]) || !array_key_exists(0, $arr[$key]) || ( array_key_exists(0, $arr[$key]) && ($arr[$key][0] == null)))
						$arr[$key] = array($arr[$key]);
					$arr[$key][] = $node;
				} else {
					$arr[$key] = $node;
				}
			}
			return $arr;
		}

		// determine if a variable is an associative array
		public static function isAssoc($array) {
			return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
		}

	}

}
?>