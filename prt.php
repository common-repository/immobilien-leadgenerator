<?php

/**
 *
 * @link              https://www.makler-anfragen.immo
 * @since             1.0
 * @package           prt
 *
 * @wordpress-plugin
 * Plugin Name:       PRT (Property Rating Tool) - Basic
 * Plugin URI:        http://www.makler-anfragen.immo/immobilienbewertung-demo/
 * Description:       This plugin rates property by their characteristics via the IS24 API.
 * Version:           1.6.8.1
 * Author:            Andreas Konopka
 * Author URI:        https://www.makler-anfragen.immo
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       prt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'PRT_VERSION', '1.6.8.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-prt-activator.php
 */
function activate_prt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prt-activator.php';
	Prt_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-prt-deactivator.php
 */
function deactivate_prt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-prt-deactivator.php';
	Prt_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_prt' );
register_deactivation_hook( __FILE__, 'deactivate_prt' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-prt.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_prt() {
	// Uncomment this below to DEBUG.
	// define('PRT_DEBUG', true );
	define( 'PRT_DIR_HOME_FILE', __FILE__ );
	define( 'PRT_DIR_HOME', plugin_dir_path( __FILE__ ) );
	define( 'PRT_DIR_HOME_URL', plugin_dir_url( __FILE__ ) );
	define( 'PRT_PRO_LINK', 'https://www.makler-anfragen.immo/' );
	$plugin = new Prt();
	$plugin->run();
	
}

function prt_dataToOIAnfrage( $data ) {
	$response = '';
	foreach ( $data as $key => $value ) {
		if ( ! is_array( $value ) ) {
			$response .= __( $key, 'prt' ) . ': ' . $value . PHP_EOL;
		}
	}
	return $response;
}

run_prt();

/**
 * Hack for force download
 */
if ( isset( $_GET['page'] ) && $_GET['page'] === 'prt_requests' && isset( $_GET['action'] ) && isset( $_GET['oid'] ) && $_GET['action'] === 'openimmo_download' ) {

	global $wpdb;
	if ( ! isset( $_GET['id'] ) || ! is_numeric( $_GET['id'] ) ) {
		echo 'The given ID is invalid';
		exit();
	}

	$id = sanitize_text_field( $_GET['id'] );

	$sql     = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}prt_requests WHERE id = %d LIMIT 1", array( $id ) );
	$request = $wpdb->get_results( $sql, ARRAY_A )[0];

	$xml = new SimpleXMLElement( '<?xml version="1.0" encoding="UTF-8"?><openimmo_feedback/>' );
	$xml->addChild( 'version', '1.2.5' );
	$x_objekt = $xml->addChild( 'objekt' );
	$x_objekt->addChild( 'oobj_id', sanitize_text_field($_GET['oid']) );
	$x_interessent = $x_objekt->addChild( 'interessent' );
	$x_interessent->addChild( 'anrede', $request['salutation'] );
	$x_interessent->addChild( 'vorname', $request['firstname'] );
	$x_interessent->addChild( 'nachname', $request['lastname'] );
	$x_interessent->addChild( 'plz', $request['address'] );
	$x_interessent->addChild( 'ort', $request['salutation'] );
	$x_interessent->addChild( 'tel', $request['phone'] );
	$x_interessent->addChild( 'email', $request['email'] );
	$x_interessent->addChild( 'bevorzugt', 'TEL' );
	$x_interessent->addChild( 'wunsch', 'ANRUF' );
	$x_interessent->addChild( 'anfrage', prt_dataToOIAnfrage( $request ) );

	header( 'Pragma: public' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Cache-Control: private', false );
	header( 'Content-Type: application/octet-stream' );
	header( 'Content-Disposition: attachment; filename="openimmo_export_' . $id . '.xml";' );
	header( 'Content-Transfer-Encoding: binary' );
	echo $xml->asXML();
	exit();

}