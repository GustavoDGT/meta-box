<?php
/**
 * Abstract class for any kind of choice field.
 */
abstract class RWMB_Choice_Field extends RWMB_Field
{
	/**
	 * Walk options
	 *
	 * @param mixed $meta
	 * @param array $field
	 * @param mixed $options
	 * @param mixed $db_fields
	 * @return string
	 */
	public static function walk( $options, $db_fields, $meta, $field )
	{
		return '';
	}

	/**
	 * Get field HTML
	 *
	 * @param mixed $meta
	 * @param array $field
	 * @return string
	 */
	public static function html( $meta, $field )
	{
		$field_class = RW_Meta_Box::get_class_name( $field );
		$meta        = (array) $meta;
		$options     = call_user_func( array( $field_class, 'get_options' ), $field );
		$db_fields   = call_user_func( array( $field_class, 'get_db_fields' ), $field );

		return call_user_func( array( $field_class, 'walk' ), $options, $db_fields, $meta, $field );
	}

	/**
	 * Normalize parameters for field
	 *
	 * @param array $field
	 * @return array
	 */
	public static function normalize( $field )
	{
		$field = parent::normalize( $field );
		$field = wp_parse_args( $field, array(
			'flatten' => true,
			'options' => array(),
		) );

		return $field;
	}

	/**
	 * Get field names of object to be used by walker
	 *
	 * @return array
	 */
	public static function get_db_fields()
	{
		return array(
			'parent' => 'parent',
			'id'     => 'value',
			'label'  => 'label',
		);
	}

	/**
	 * Get options for walker
	 *
	 * @param array $field
	 *
	 * @return array
	 */
	public static function get_options( $field )
	{
		$options = array();
		foreach ( (array) $field['options'] as $value => $label )
		{
			$option = is_array( $label ) ? $label : array( 'label' => (string) $label, 'value' => (string) $value );
			if ( isset( $option['label'] ) && isset( $option['value'] ) )
				$options[$option['value']] = (object) $option;
		}
		return $options;
	}

	/**
	 * Format a single value for the helper functions.
	 * @param array  $field Field parameter
	 * @param string $value The value
	 * @return string
	 */
	public static function format_single_value( $field, $value )
	{
		return call_user_func( array( RW_Meta_Box::get_class_name( $field ), 'get_option_label' ), $value, $field );
	}

	/**
	 * Get option label
	 *
	 * @param string $value Option value
	 * @param array  $field Field parameter
	 *
	 * @return string
	 */
	public static function get_option_label( $value, $field )
	{
		$options = call_user_func( array( RW_Meta_Box::get_class_name( $field ), 'get_options' ), $field );
		return $options[$value]->label;
	}
}
