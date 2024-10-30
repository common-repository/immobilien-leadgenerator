<?php

class Prt_Requests {
   
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
	private $listTable;

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

		require_once plugin_dir_path(__FILE__) . 'requests/class-table-list-requests.php';
    }
    

	public function run() {
		?>
		<div class="wrap">
			
			<div id="icon-users" class="icon32"><br/></div>
			<h2>Leadgenerator - Eingegangene Leads</h2>
			
			<form id="requests-filter" method="get">
				<input type="hidden" name="page" value="<?php echo esc_html($_REQUEST['page']) ?>" />

				<?php
				$action = null;
				if(isset($_GET['action'])) $action = sanitize_text_field($_GET['action']);
				if(!isset($action) || empty($action) || $action == '-1') $action = sanitize_text_field($_GET['action2']);

					switch($action) {
						case "show":
							$this->print_show();
							break;
						case "delete":
							$this->print_delete();
							break;
						case "force_delete":
							$this->force_delete();
							break;
						case "bulk_delete":
							$this->bulk_delete();
							break;
						case "openimmo_download":
							$this->export_immo();
							break;
						default: 
							$listTable = new Prt_List_Table_Requests();
							global $wpdb;
							$requests = $wpdb->get_results( "SELECT `id`, `salutation`, `type`, `firstname`, `lastname`, `phone`, `email`, `address`, `date` FROM {$wpdb->prefix}prt_requests ORDER BY id ASC", ARRAY_A );
							$listTable->setData($requests);
							$listTable->prepare_items();
							$this->if_basic_show_upgrade_notice();
							$listTable->display();
					}
					
				?>

			</form>

			<script type="text/javascript">
				$('.vs.resultAbsolute, .vs.lowAbsolute, .vs.highAbsolute, .vs.resultPerSqm, .vs.lowPerSqm, .vs.highPerSqm').append(' €');
			</script>
			
		</div>
		<?php
	}

	public function print_show() {
		global $wpdb;
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			echo "The given ID is invalid";
			return;
		}
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}prt_requests WHERE id = %d LIMIT 1", array($_GET['id']));
		$requests = $wpdb->get_results($sql, ARRAY_A )[0];
		$this->if_basic_show_upgrade_notice();
		?><a style="float:right;" href="<?php echo esc_html( sprintf('?page=%s&action=%s&id=%s&oid=%s', $_REQUEST['page'], 'openimmo_download', $_GET['id'], $this->settings->get_option('objectnumber', 'prt_settings_general')) ); ?>"><button class="button button-secondary" type="button">Export to OpenImmo XML</button></a><?php
		?>
		<table class="form-table"><tbody><?php
		foreach ($requests as $key => $value) {
			if(empty($value)) continue;
			?><tr><th scope="row"><label><?php echo  __($key, 'prt'); ?></label></th><td><div class="vs <?php echo esc_html($key); ?>"><?php echo esc_html($value); ?></div></td></tr><?php
		}
		?></tbody></table><?php
	}

	public function print_delete() {
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			echo "The given ID is invalid";
			return;
		}
		?>
		<h2><?php echo __('Do you want to delete this request?'); ?><h2>
		<a href="<?php echo esc_html( sprintf('?page=%s',$_REQUEST['page']) ); ?>"><button class="button button-secondary" type="button">Back</button></a>
		<a href="<?php echo esc_html( sprintf('?page=%s&action=%s&id=%s',$_REQUEST['page'],'force_delete',$_GET['id']) ); ?>"><button class="button button-primary" type="button">Delete</button></a>
		<?php
	}

	public function bulk_delete() {
		if(!isset($_GET['prt-request']) || !is_array($_GET['prt-request'])) {
			echo "The given IDs are invalid";
			return;
		}
		$requests = $_GET['prt-request'];
		$requests = array_map( 'sanitize_text_field', $requests ); // sanitizing all array values
		
		global $wpdb;
		$sql = $wpdb->prepare("DELETE FROM {$wpdb->prefix}prt_requests WHERE id IN (".implode(',', $requests).")", array());
		$wpdb->query($sql);
		?>
		<div class="notice notice-success"><p>Erfolgreich gelöscht!</p></div>
		<a href="<?php echo esc_html( sprintf('?page=%s',$_REQUEST['page']) ); ?>"><button class="button button-secondary" type="button">Back</button></a>
		<?php
	}

	public function force_delete() {
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			echo "The given ID is invalid";
			return;
		}
		global $wpdb;
		$sql = $wpdb->prepare("DELETE FROM {$wpdb->prefix}prt_requests WHERE id = %d;", $_GET['id']);
		$wpdb->query($sql);
		?>
		<h2>Erfolgreich gelöscht!</h2>
		<a href="<?php echo esc_html( sprintf('?page=%s',$_REQUEST['page']) ); ?>"><button class="button button-secondary" type="button">Back</button></a><?php
	}
	
	public function export_immo() {
		global $wpdb;
		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			echo "The given ID is invalid";
			return;
		}

		$dir = PRT_HOME_DIR . 'admin/openimmo_exports/';
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}prt_requests WHERE id = %d LIMIT 1", array($_GET['id']));
		$request = $wpdb->get_results($sql, ARRAY_A )[0];

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><openimmo_feedback/>');
		$xml->addChild('version', '1.2.5');
		$x_objekt = $xml->addChild('objekt');
		$x_objekt->addChild('oobj_id', $this->settings->get_option('objectnumber', 'prt_settings_general'));
		$x_interessent = $x_objekt->addChild('interessent');
		$x_interessent->addChild('anrede', $request['salutation']);
		$x_interessent->addChild('vorname', $request['firstname']);
		$x_interessent->addChild('nachname', $request['lastname']);
		$x_interessent->addChild('plz', $request['address']);
		$x_interessent->addChild('ort', $request['salutation']);
		$x_interessent->addChild('tel', $request['phone']);
		$x_interessent->addChild('email', $request['email']);
		$x_interessent->addChild('bevorzugt', 'TEL');
		$x_interessent->addChild('wunsch', 'ANRUF');
		$x_interessent->addChild('anfrage', $this->dataToAnfrage($request));

		$file_path = $dir.'openimmo_'.time().'.xml';
		file_put_contents($file_path, "\xEF\xBB\xBF".$xml->asXML());

		?><textarea style="width:50%;height:400px;"><?php echo $xml->asXML(); ?></textarea><?php
	}

	private function dataToAnfrage($data) {
		$response = "";
		foreach ($data as $key => $value) {
			$response .= __($key, 'prt') . ' ' . $value . PHP_EOL;
		}
		return $response;
	}

	public function if_basic_show_upgrade_notice() {
		?><div class="notice notice-error" id="PRT_RQ_Basic" style="padding: 20px;">
		<strong>UPGRADE:</strong> Um die Bewertungen seitens ImmobilienScout24 zu aktivieren und den PDF Generator nutzen zu können, müssen Sie auf die <b>Professional</b> Version upgraden.
		<br><a href="<?php echo PRT_PRO_LINK; ?>">Mehr Informationen</a>
		</div><?php
	}


}