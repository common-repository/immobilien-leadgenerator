<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.makler-anfragen.immo
 * @since      1.0.0
 *
 * @package    Prt
 * @subpackage Prt/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Prt
 * @subpackage Prt/includes
 * @author     Andreas Konopka <info@makler-anfragen.immo>
 */
class Prt {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Prt_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $prt    The string used to uniquely identify this plugin.
	 */
	protected $prt;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $settings;

	protected $styler;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PRT_VERSION' ) ) {
			$this->version = PRT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->prt = 'prt';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * This is a util function.
	 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.
	 * @param str $hex Colour as hexadecimal (with or without hash);
	 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
	 * @return str Lightened/Darkend colour as hexadecimal (with hash);
	 */
	private function color_luminance( $hex, $percent ) {
		// validate hex string
		$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
		$new_hex = '#';
		
		if ( strlen( $hex ) < 6 ) {
			$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
		}
		
		// convert to decimal and change luminosity
		for ($i = 0; $i < 3; $i++) {
			$dec = hexdec( substr( $hex, $i*2, 2 ) );
			$dec = min( max( 0, $dec + $dec * $percent ), 255 ); 
			$new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
		}		
		
		return $new_hex;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Prt_Loader. Orchestrates the hooks of the plugin.
	 * - Prt_i18n. Defines internationalization functionality.
	 * - Prt_Admin. Defines all hooks for the admin area.
	 * - Prt_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prt-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prt-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-prt-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-prt-public.php';

		/**
		 * The class responsible for the Settings Page in the admin face
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prt-settings.php';

		/**
		 * The class responsible for the Settings Page in the admin face
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prt-styler.php';

		$this->loader = new Prt_Loader();

		if(defined('PRT_DEBUG') && PRT_DEBUG) $this->debug();

		// Plugin version.
		if (!defined( 'PRT_SETTINGS_TITLE' )) define( 'PRT_SETTINGS_TITLE', 'Property Rating Tool' );
		$this->settings = new Prt_Settings(true); //true -> runSettings()

		$this->styler = new Prt_Styler();

		$toBeReplaced = [
			'$primary_color' => $this->settings->get_option('primary_color', 'prt_settings_color', '#0A0A0A'),
			'$secondary_color' => $this->settings->get_option('secondary_color', 'prt_settings_color', '#ffffff'),
			'$secondary_color_dark' => $this->color_luminance($this->settings->get_option('secondary_color', 'prt_settings_color', '#ffffff'), -0.2),
			'$progress_color' => $this->settings->get_option('progress_color', 'prt_settings_color', '#0A0A0A'),
			'$progress_text_color' => $this->settings->get_option('progress_text_color', 'prt_settings_color', '#ffffff'),
			'$default_font_size' => $this->settings->get_option('default_font_size', 'prt_settings_font', '14px'),
			'$default_font_color' => $this->settings->get_option('default_font_color', 'prt_settings_font', '#0A0A0A'),
			'$default_font_weight' => $this->settings->get_option('default_font_weight', 'prt_settings_font', '400'),
			'$default_font_transform' => $this->settings->get_option('default_font_transform', 'prt_settings_font', 'none'),
			'$default_font_family' => $this->settings->get_option('default_font_family', 'prt_settings_font', 'Montserrat'),
			'$default_font_subset' => $this->settings->get_option('default_font_subset', 'prt_settings_font', 'latin'),
			'$button_prev_color' => $this->settings->get_option('button_prev_color', 'prt_settings_color', '#fc5c65'),
			'$button_next_color' => $this->settings->get_option('button_next_color', 'prt_settings_color', '#20bf6b'),
			'$button_finish_color' => $this->settings->get_option('button_finish_color', 'prt_settings_color', '#20bf6b'),
			'$h1_size' => $this->settings->get_option('h1_size', 'prt_settings_titles', 'none'),
			'$h1_color' => $this->settings->get_option('h1_color', 'prt_settings_titles', '#0A0A0A'),
			'$h2_size' => $this->settings->get_option('h2_size', 'prt_settings_titles', 'none'),
			'$h2_color' => $this->settings->get_option('h2_color', 'prt_settings_titles', '#0A0A0A'),
			'$h3_size' => $this->settings->get_option('h3_size', 'prt_settings_titles', 'none'),
			'$h3_color' => $this->settings->get_option('h3_color', 'prt_settings_titles', '#0A0A0A'),
			'$h4_size' => $this->settings->get_option('h4_size', 'prt_settings_titles', 'none'),
			'$h4_color' => $this->settings->get_option('h4_color', 'prt_settings_titles', '#0A0A0A'),
			'$h5_size' => $this->settings->get_option('h5_size', 'prt_settings_titles', 'none'),
			'$h5_color' => $this->settings->get_option('h5_color', 'prt_settings_titles', '#0A0A0A'),
			'$h6_size' => $this->settings->get_option('h6_size', 'prt_settings_titles', 'none'),
			'$h6_color' => $this->settings->get_option('h6_color', 'prt_settings_titles', '#0A0A0A'),
			'$custom_css' => $this->settings->get_option('custom_css', 'prt_settings_css', ''),
		];

		$theme = $this->settings->get_option('theme', 'prt_settings_general', 'modern');

		$sampleCss = PRT_DIR_HOME . 'includes/prt-sample-'.$theme.'.css';
		$newCss = PRT_DIR_HOME . 'public/css/prt-'.$theme.'.css';
		$this->styler->generateCss($sampleCss, $newCss, $toBeReplaced);
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Prt_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Prt_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function debug() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new Prt_Admin( $this->get_prt(), $this->get_version(), $this->settings );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'action_menu' );

		$prt_importer = $plugin_admin->getPrtImporter();

		$this->loader->add_action( 'wp_ajax_prt_export', $prt_importer, 'ajax_prt_export' );
		$this->loader->add_action( 'wp_ajax_prt_import', $prt_importer, 'ajax_prt_import' );
	
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Prt_Public( $this->get_prt(), $this->get_version(), $this->settings, true );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_scripts' );

		$this->loader->add_action( 'wp_ajax_prt_getsteps', $plugin_public, 'ajax_prt_getsteps' );
		$this->loader->add_action( 'wp_ajax_nopriv_prt_getsteps', $plugin_public, 'ajax_prt_getsteps' );

		$this->loader->add_action( 'wp_ajax_prt_geo', $plugin_public, 'ajax_prt_geo' );
		$this->loader->add_action( 'wp_ajax_nopriv_prt_geo', $plugin_public, 'ajax_prt_geo' );

		$this->loader->add_action( 'wp_ajax_prt_submit', $plugin_public, 'ajax_prt_submit' );
		$this->loader->add_action( 'wp_ajax_nopriv_prt_submit', $plugin_public, 'ajax_prt_submit' );

		$this->loader->add_action( 'wp_ajax_prt_statistic', $plugin_public, 'ajax_prt_statistic' );
		$this->loader->add_action( 'wp_ajax_nopriv_prt_statistic', $plugin_public, 'ajax_prt_statistic' );
		
		$this->loader->add_action( 'wp_ajax_prt_sendmails', $plugin_public, 'ajax_prt_sendmails' );
		$this->loader->add_action( 'wp_ajax_nopriv_prt_sendmails', $plugin_public, 'ajax_prt_sendmails' );

		$shortcode_name = $this->settings->get_option('shortcode', 'prt_settings_general');
		$shortcode_name = empty($shortcode_name) ? 'PRT_INCLUDE' : substr($shortcode_name, 1, -1);
		$this->loader->add_shortcode($shortcode_name, $plugin_public, 'shortcode_prt_include');


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_prt() {
		return $this->prt;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Prt_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

/**
 * This private util function verifys the validation of the mysql table
 * If the table is invalid it will add the missing columns to table so that the table
 * is always be valid.
 * 
 * @since 1.2
 */
function prt_verify_mysql_table() {
	global $wpdb;

	$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}prt_requests (
		`id` INT NOT NULL AUTO_INCREMENT,
		`type` VARCHAR(45) NOT NULL,
		`salutation` VARCHAR(45) NOT NULL,
		`firstname` VARCHAR(150) NULL,
		`lastname` VARCHAR(150) NULL,
		`phone` VARCHAR(60) NULL,
		`email` VARCHAR(150) NULL,
		`address` VARCHAR(300) NULL,
		`wohnflache` INT NULL,
		`zimmer` DECIMAL(1) NULL,
		`baujahr` VARCHAR(45) NULL,
		`grundflache` INT NULL,
		`etage` INT NULL,
		`erschlossen` VARCHAR(45) NULL,
		`bebauung` VARCHAR(45) NULL,
		`zuschnitt` VARCHAR(45) NULL,
		`resultAbsolute` DECIMAL(12,2) NULL,
		`lowAbsolute` DECIMAL(12,2) NULL,
		`highAbsolute` DECIMAL(12,2) NULL,
		`resultPerSqm` DECIMAL(12,2) NULL,
		`lowPerSqm` DECIMAL(12,2) NULL,
		`highPerSqm` DECIMAL(12,2) NULL,
		`date` TIMESTAMP NOT NULL,
		`objectCategory` VARCHAR(100) NULL,
		`status` VARCHAR(100) NULL,
		PRIMARY KEY (`id`));");

	$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}prt_statistics (
		`id` VARCHAR(100) NOT NULL,
		`wp_username` VARCHAR(45) NULL,
		`ip` VARCHAR(45) NULL,
		`country` VARCHAR(4) NULL,
		`city` VARCHAR(150) NULL,
		`user_agent` VARCHAR(150) NULL,
		`user_browser` VARCHAR(60) NULL,
		`user_os` VARCHAR(45) NULL,
		`user_device` VARCHAR(45) NULL,
		`user_lang` VARCHAR(10) NULL,
		`last_activity` VARCHAR(200) NULL,
		`finished` TINYINT NULL,
		`date` DATETIME NOT NULL,
		PRIMARY KEY (`id`));");
	
	$columns = $wpdb->get_results("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS 
						WHERE table_name = '{$wpdb->prefix}prt_requests' 
						AND table_schema = DATABASE()", ARRAY_N);
	$colsInMySQL = array();
	foreach($columns as $col) {
		$colsInMySQL[] = $col[0];
	}

	$colsNeeded = array(
		"id" => "ADD COLUMN `id` INT(11) NOT NULL",
		"type" => "ADD COLUMN `type` VARCHAR(45) NOT NULL",
		"salutation" => "ADD COLUMN `salutation` VARCHAR(45) NOT NULL",
		"firstname" => "ADD COLUMN `firstname` VARCHAR(150)",
		"lastname" => "ADD COLUMN `lastname` VARCHAR(150)",
		"phone" => "ADD COLUMN `phone` VARCHAR(60)",
		"email" => "ADD COLUMN `email` VARCHAR(150)",
		"address" => "ADD COLUMN `address` VARCHAR(300)",
		"wohnflache" => "ADD COLUMN `wohnflache` INT(11)",
		"zimmer" => "ADD COLUMN `zimmer` DECIMAL(1,0)",
		"baujahr" => "ADD COLUMN `baujahr` VARCHAR(45)",
		"grundflache" => "ADD COLUMN `grundflache` INT(11)",
		"etage" => "ADD COLUMN `etage` INT(11)",
		"erschlossen" => "ADD COLUMN `erschlossen` VARCHAR(45)",
		"bebauung" => "ADD COLUMN `bebauung` VARCHAR(45)",
		"zuschnitt" => "ADD COLUMN `zuschnitt` VARCHAR(45)",
		"resultAbsolute" => "ADD COLUMN `resultAbsolute` DECIMAL(12,2)",
		"lowAbsolute" => "ADD COLUMN `lowAbsolute` DECIMAL(12,2)",
		"highAbsolute" => "ADD COLUMN `highAbsolute` DECIMAL(12,2)",
		"resultPerSqm" => "ADD COLUMN `resultPerSqm` DECIMAL(12,2)",
		"lowPerSqm" => "ADD COLUMN `lowPerSqm` DECIMAL(12,2)",
		"highPerSqm" => "ADD COLUMN `highPerSqm` DECIMAL(12,2)",
		"date" => "ADD COLUMN `date` DATETIME NOT NULL",
		"objectCategory" => "ADD COLUMN `objectCategory` VARCHAR(100)",
		"status" => "ADD COLUMN `status` VARCHAR(50)"
	);

	$diff = array_diff(array_keys($colsNeeded), $colsInMySQL);
	$sqlCols = array();
	foreach($diff as $key => $val) {
		$sqlCols[] = $colsNeeded[$val];
	}

	if(!empty($sqlCols)) {
		$sqlQuery = "ALTER TABLE `{$wpdb->prefix}prt_requests` " . implode($sqlCols, ', ') . ';';
		$wpdb->query($sqlQuery);
	}
}