<?php

/**
 * Description of MPCEUtils
 * 
 */
class MPCEUtils {
	
	/**
	 * Add a leading space if it is missing.
	 * @param string $string
	 * @return string 
	 */
	static function addLeadingSpace($string){	
		return (!empty($string) && $string[0] !== ' ') ? ' ' . $string : $string;
	}
	
	/**
	 * Concatenate classes and whitespace-separated class groups into one string.
	 * @param string[] $classesGroups Array of whiteseparated classes strings.
	 * @param bool $withLeadingWhitespace
	 * @return string Whitespace-separated string of classes
	 */
	static function concatClassesGroups($classesGroups) {
		return join('', array_map(array('MPCEUtils', 'addLeadingSpace'), $classesGroups));
	}
	
	/**
	 * Generate style rule
	 * @param string $value 
	 * @param string $property Style property.
	 * @return string
	 */
	static function generateStyleRule($value, $property) {
		return $property . ':' . $value . ';';
	}
	
	/**
	 * 
	 * @param string[] $stylesArray Array, where keys are style properties
	 * @return string
	 */
	static function generateStylesString($stylesArray){
		return join('', array_map(array('MPCEUtils', 'generateStyleRule'), $stylesArray, array_keys($stylesArray)));
	}
	
	/**
	 * 
	 * @param string $attrValue
	 * @param string $attrName
	 * @return string Attribute with escaped value.
	 */
	static function generateAttribute($attrValue, $attrName) {
		switch($attrName) {
			case 'class':
			case 'id':
				$attrValueEscaped = esc_attr(trim($attrValue)); break;
			case 'href':
				$attrValueEscaped = esc_attr(trim($attrValue)); break;
			default: 
				$attrValueEscaped = esc_attr($attrValue);
		}
		return  $attrName . '="' . $attrValueEscaped . '"';
	}
	
	/**
	 * 
	 * @param string[] $attrs Where keys are attribute names
	 * @param bol $withLeadingWhitespace  Whether to output string of attributes with leading whitespace. Optional.
	 * @return string Whitespace-separated string of escaped attributes.
	 */
	static function generateAttrsString($attrs, $withLeadingWhitespace = false) {
		$attrsString = join(' ', array_map(array('MPCEUtils', 'generateAttribute'), $attrs, array_keys($attrs)));
		return $withLeadingWhitespace ? self::addLeadingSpace($attrsString) : $attrsString;
	}
	
}
