<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.makler-anfragen.immo
 * @since      1.0.0
 *
 * @package    Prt
 * @subpackage Prt/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Prt
 * @subpackage Prt/includes
 * @author     Andreas Konopka <info@makler-anfragen.immo>
 */
class Prt_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		prt_verify_mysql_table(); //in class-prt.php (bottom)
	}

}
