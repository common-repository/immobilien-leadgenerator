<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.makler-anfragen.immo
 * @since      1.0.0
 *
 * @package    Prt
 * @subpackage Prt/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Prt
 * @subpackage Prt/admin
 * @author     Andreas Konopka <info@makler-anfragen.immo>
 */
class Prt_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $prt    The ID of this plugin.
	 */
	private $prt;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $settings;

	private $prt_requests;
	private $prt_statistics;
	private $prt_importer;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $prt       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $prt, $version, $settings ) {
		$this->prt = $prt;
		$this->version = $version;
		$this->settings = $settings;

		require_once plugin_dir_path(__FILE__) . 'class-prt-requests.php';
		require_once plugin_dir_path(__FILE__) . 'class-prt-statistics.php';
		require_once plugin_dir_path(__FILE__) . 'class-prt-importer.php';

		$this->prt_requests = new Prt_Requests($this->prt, $this->version, $this->settings);
		$this->prt_statistics = new Prt_Statistics($this->prt, $this->version, $this->settings);
		$this->prt_importer = new Prt_Importer($this->prt, $this->version, $this->settings);

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.3.13
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->prt, plugin_dir_url( __FILE__ ) . 'css/prt-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script('prt-admin-js', plugin_dir_url( __FILE__ ) . 'js/prt-admin.js', false, $this->version);
		wp_enqueue_script('prt-admin-js');

	}
	
	public function action_menu() {
		add_menu_page('Anfragen', 'Property Ratings', 'moderate_comments', 'prt_menu', array($this->prt_requests, 'run'), 'dashicons-editor-ul', 90 );
		add_submenu_page('prt_menu', 'Statistiken', 'Statistiken', 'moderate_comments', 'prt_statistics', array($this->prt_statistics, 'run'));
		add_submenu_page('prt_menu', 'Einstellungen', 'Einstellungen', 'moderate_comments', 'prt_settings', array( $this->settings, 'print_settings'));
		add_submenu_page('prt_menu', 'Import/Export', 'Import/Export', 'moderate_comments', 'prt_importer', array( $this->prt_importer, 'render'));
	}

	public function getPrtRequestsMenu() {
		return $this->prt_requests;
	}

	public function getPrtImporter() {
		return $this->prt_importer;
	}
}
