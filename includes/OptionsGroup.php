<?php
/**
 * The OptionsGroup class is a helper that allow manage set of options
 * generating commonly used HTML tags
 *
 * @package       JPToolkit
 * @subpackage    Settings
 */

namespace JPToolkit\Settings;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * OptionsGroup class
 *
 * @package        JPToolkit
 * @subpackage     Settings
 * @since          0.1.0
 * @author         Javier Prieto
 */
class OptionsGroup {

  /**
   * Options group name
   *
   * @since   0.1.0
   * @var     string
   */
  public $option_group_name = '';

  /**
   * Option data
   *
   * @since   0.1.0
   * @var     array
   */
  protected $options = [];

  /**
   * Class constructor
   *
   * @since   0.1.0
   * @param   string    $option_group    Option group option name
   */
  public function __construct( $option_group ) {
    $this->option_group_name = trim( $option_group );
    $this->options            = (array) get_option( $this->option_group_name, [] );
    add_action( 'admin_init', [ $this, 'register_setting' ] );
  }

  /**
   * Set option value in group option
   *
   * @since   0.1.0
   * @param   string    $option   Name of option to set. Expected to not be SQL-escaped.
   * @param   mixed     $value    Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.
   * @return  bool
   */
  public function set_option( $option, $value ) {
    $this->options[$option] = $value;
    return update_option( $this->option_group_name, $this->options );
  }

  /**
   * Get option value in option group.
   *
   * @since   0.1.0
   * @param   string    $option     Name of option to retrieve. Expected to not be SQL-escaped.
   * @param   mixed     $default    Optional. Default value to return if the option does not exist.
   * @return  mixed
   */
  public function get_option( $option, $default = false ) {
    $value = $this->options[$option] ?? $default;
    return $value;
  }

  /**
   * Get boolean option value in option group.
   *
   * @since   0.1.0
   * @param   string    $option     Name of option to retrieve. Expected to not be SQL-escaped.
   * @param   boolean   $default    Optional. Default value to return if the option does not exist.
   * @return  boolean
   */
  public function get_bool_option( $option, $default = false ) {
    $value = $this->get_option( $option, $default );
    return (bool) in_array( strtolower( $value ), [ 'yes', 'true', '1' ] );
  }

  /**
   * Get integer option value in option group.
   *
   * @since   0.1.0
   * @param   string    $option     Name of option to retrieve. Expected to not be SQL-escaped.
   * @param   int       $default    Optional. Default value to return if the option does not exist.
   * @return  int
   */
  public function get_int_option( $option, $default = 0 ) {
    $value = $this->get_option( $option, $default );
    return intval( $value );
  }

  /**
   * Get absolute integer option value in option group.
   *
   * @since   0.1.0
   * @param   string    $option     Name of option to retrieve. Expected to not be SQL-escaped.
   * @param   int       $default    Optional. Default value to return if the option does not exist.
   * @return  int
   */
  public function get_absint_option( $option, $default = 0 ) {
    $value = $this->get_option( $option, $default );
    return absint( $value );
  }

  /**
   * Get absolute numeric option value in option group.
   *
   * @since   0.1.0
   * @param   string      $option     Name of option to retrieve. Expected to not be SQL-escaped.
   * @param   int|float   $default    Optional. Default value to return if the option does not exist.
   * @return  int|float
   */
  public function get_abs_option( $option, $default = 0 ) {
    $value = $this->get_option( $option, $default );
    return abs( $value );
  }

  /**
   * Get float option value in option group.
   *
   * @since   0.1.0
   * @param   string    $option     Name of option to retrieve. Expected to not be SQL-escaped.
   * @param   int       $default    Optional. Default value to return if the option does not exist.
   * @return  int
   */
  public function get_float_option( $option, $default = 0 ) {
    $value = $this->get_option( $option, $default );
    return floatval( $value );
  }

  /**
   * Merge options before saving
   *
   * @since   0.1.0
   * @param   array     $new_value
   * @return  array
   */
  public function pre_update_option( $new_value ) {
    if ( is_serialized( $new_value ) ) {
      $new_value = unserialize( $new_value );
    }

    $this->options = array_merge( $this->options, (array) $new_value );

    foreach ( $this->options as $key => $value ) {
      if ( is_array( $value ) ) {
        $this->options[$key] = $this->_clean_options( $value );
      }

      if ( empty( $value ) || $value == '_unset_' ) {
        unset( $this->options[$key] );
      }
    }

    return $this->options;
  }

  /**
   * Register a setting. Must be called in admin_init hook.
   *
   * @since   0.1.0
   */
  public function register_setting() {
    register_setting( $this->option_group_name, $this->option_group_name, [ $this, 'pre_update_option' ] );
  }

  /**
   * Clean empty or _unset_ options
   *
   * @since   0.1.0
   * @param   array     $new_value
   * @return  array
   */
  private function _clean_options( $new_value ) {
    foreach ( $new_value as $key => $value ) {
      if ( is_array( $value ) ) {
        $new_value[$key] = $this->_clean_options( $value );
      }

      if ( empty( $value ) || $value == '_unset_' ) {
        unset( $new_value[$key] );
      }
    }
    return $new_value;
  }

}
