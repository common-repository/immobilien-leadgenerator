<?php

class Prt_Statistics {
   
    /**
	 * The ID of this plugin.
	 *
	 * @since    1.3
	 * @access   private
	 * @var      string    $prt    The ID of this plugin.
	 */
	private $prt;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.3
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    // Settings
	private $settings;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      object    $settings    The settings of this plugin.
	 */
	public function __construct( $prt, $version, $settings ) {
		$this->prt = $prt;
		$this->version = $version;
		$this->settings = $settings;

		require_once plugin_dir_path(__FILE__) . 'statistics/class-table-list-statistics.php';
	}

	public function run() {
		?>
		<div class="wrap">
			
			<div id="icon-users" class="icon32"><br/></div>
			<h2>Leadgenerator - Statistiken</h2>
			
			<form id="requests-filter" method="get">
				<input type="hidden" name="page" value="<?php echo esc_html($_REQUEST['page']) ?>" />

				<?php 
					if(isset($_GET['action'])) {
						$action = sanitize_text_field($_GET['action']);
						switch($action) {
							case 'truncate_stats':
								$this->truncate_stats();
								break;
							default: break;
						}
					} else {
						$this->if_basic_show_upgrade_notice();
						$this->print_table();
					}
				?>

			</form>
			<?php
		 	if($_GET['action'] !== 'truncate_stats') {
				$this->print_truncate_button();
			} else {
				$this->print_back_button();
			}
			?>
			
		</div>
		<?php
	}
	
	public function print_table() {
		$listTable = new Prt_List_Table_Statistics();
		global $wpdb;
		$requests = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}prt_statistics ORDER BY date DESC", ARRAY_A );
		$listTable->setData($requests);
		$listTable->prepare_items();

		$listTable->display();
	}

	public function truncate_stats() {
		global $wpdb;
		$wpdb->query("TRUNCATE `{$wpdb->prefix}prt_statistics`;");
		echo "<h2>Statistiken wurden geleert.</h2>";
	}

	public function print_truncate_button() {
		echo '<a href="?page=prt_statistics&action=truncate_stats"><button class="button button-secondary">Statistiken leeren</button></a>';
	}
	
	public function print_back_button() {
		echo '<a href="?page=prt_statistics"><button class="button button-secondary">'. __('Back') .'</button></a>';
	}

	public function if_basic_show_upgrade_notice() {
		?><div class="notice notice-error" id="PRT_RQ_Basic" style="padding: 20px;">
		<strong>UPGRADE:</strong> Um die Bewertungen seitens ImmobilienScout24 zu aktivieren und den PDF Generator nutzen zu können, müssen Sie auf die <b>Professional</b> Version upgraden.
		<br><a href="<?php echo PRT_PRO_LINK; ?>">Mehr Informationen</a>
		</div><?php
	}

}