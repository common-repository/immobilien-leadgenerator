<?php

//load BrowserDetector
require_once PRT_DIR_HOME . 'vendor/autoload.php';
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Language;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.makler-anfragen.immo
 * @since      1.0.0
 *
 * @package    Prt
 * @subpackage Prt/public
 */

/**
 * This interface represents the Steps for 'Wohnung', 'Haus' and 'Grundstuck'.
 */
interface Steps {
    public function getResponse();
	public function validateData();
	public function collectData();
}

class PRT_Exception extends Exception {

	protected $message = 'Unknown exception';
	protected $extraMessage;
	
	public function __construct($message = null, $extraMessage = null) {
		$this->message = $message;
		$this->extraMessage = $extraMessage;
	}

	public function getExtraMessage() {
		return $this->extraMessage;
	}

	public function __toString() {
		return $message . ' extra: ' . $extraMessage;
	}
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Prt
 * @subpackage Prt/public
 * @author     Emre Isik
 */
class Prt_Public {

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

	private $activeTheme;

	private $log;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $prt       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $prt, $version, $settings ) {

		$this->prt = $prt;
		$this->version = $version;
		$this->settings = $settings;
		$this->activeTheme = $settings->get_option('theme', 'prt_settings_general', 'modern');

		// create a log channel
		$this->log = new Logger('prt');
		$this->log->pushHandler(new StreamHandler(PRT_DIR_HOME.'logs/prt-public.log', Logger::INFO));
		
	}

	/**
	 * Returns a Step class.
	 * @param string $type
	 * @return Step step
	 */
	private function loadStepClass($type) {
		$theme = $this->activeTheme;
		$type = sanitize_text_field($_POST['type']);
		switch($type) {
			case 'wohnung': {
				require plugin_dir_path(__FILE__) . "theme/$theme/steps/wohnung/class.WohnungSteps.php";
				return new WohnungSteps($this->settings);
			}
			case 'haus': {
				require plugin_dir_path(__FILE__) . "theme/$theme/steps/haus/class.HausSteps.php";
				return new HausSteps($this->settings);
			}
			case 'grundstuck': {
				require plugin_dir_path(__FILE__) . "theme/$theme/steps/grundstuck/class.GrundstuckSteps.php";
				return new GrundstuckSteps($this->settings);
			}
			case 'miete': {
				require plugin_dir_path(__FILE__) . "theme/$theme/steps/miete/class.MieteSteps.php";
				return new MieteSteps($this->settings);
			}
			default: throw new Exception("Step type not valid");
		}
	}

	private function parseRoomCountToId($roomCount) {
		switch($roomCount) {
			case '1': return 1;
			case '1.5': return 2;
			case '2': return 3;
			case '2.5': return 4;
			case '3': return 5;
			case '3.5': return 6;
			case '4': return 7;
			case '4.5': return 7;
			case '5': return 8;
			case '5.5': return 8;
			case '6': return 9;
		}
		return 3;
	}

	private function parseData(&$data) {

		// -- YEAR PARSING --
		if(!isset($data['year']) || $data['year'] == null) $data['year'] = 1980;
		else {
			$year = $data['year'];
			$num_length = strlen((string) $year);
			if($num_length != 4) {
				$year = explode('-', $year);
				$year = round(($year[0] + $year[1]) / 2);
			}
			$data['year'] = $year;
		}

		$data['objectCategoryName'] = null;
		
		if(isset($data['objectCategory'])) {
			$oc = explode('-', $data['objectCategory']);
			$data['objectCategory'] = $oc[0];
			$data['objectCategoryName'] = $oc[1];
		}

		if(isset($data['realEstateTypeMiete'])) {
			$retm = intval($data['realEstateTypeMiete']);
			if($retm === 0) {
				$data['realEstateTypeMieteName'] = "Wohnung";
			} else {
				$data['realEstateTypeMieteName'] = "Haus";
			}
			$data['realEstateTypeMiete'] = $retm;
		}

		// PARSING finish
	}

	private function requestGeolocation($address) {
		$googleKey = $this->settings->get_option('google-api-key', 'prt_settings_general'); //TODO: check null
		$urlcodedAddress = urlencode($address);
		$url = "https://maps.googleapis.com/maps/api/geocode/xml?address=".$urlcodedAddress."&language=de&key=$googleKey";
		$response = wp_remote_retrieve_body(wp_remote_get( $url ));
		$xml = simplexml_load_string($response);

		$status = $xml->xpath("/GeocodeResponse/status/text()")[0];
		if(strval($status) === "OK") {
			$is_partial_match = $xml->xpath("/GeocodeResponse/result/partial_match/text()")[0];
			$street = $xml->xpath("/GeocodeResponse/result/address_component[type = 'route']/short_name/text()")[0];
			$street_number = $xml->xpath("/GeocodeResponse/result/address_component[type = 'street_number']/long_name/text()")[0];
			$city = $xml->xpath("/GeocodeResponse/result/address_component[type = 'locality']/long_name/text()")[0];
			$plz = $xml->xpath("/GeocodeResponse/result/address_component[type = 'postal_code']/long_name/text()")[0];
		} else {
			return false;
		}

		if(!empty($is_partial_match) && strval($is_partial_match) === 'true') $is_partial_match = true;

		if(empty($street) && !$is_partial_match) return 'Bitte geben Sie eine korrekte Straße an.';
		if(empty($street_number) && !$is_partial_match) return 'Bitte geben Sie eine korrekte Hausnummer an.';
		if(empty($city) && !$is_partial_match) return 'Bitte geben Sie eine korrekte Stadt an.';
		if(empty($plz) && !$is_partial_match) return 'Bitte geben Sie eine korrekte Postleitzahl an.';

		$lat = floatval($xml->xpath("/GeocodeResponse/result/geometry/location/lat/text()")[0]);
		$lng = floatval($xml->xpath("/GeocodeResponse/result/geometry/location/lng/text()")[0]);

		$administrative_area_level_1 = $xml->xpath("/GeocodeResponse/result/address_component[type = 'administrative_area_level_1']/long_name/text()")[0];
		$locality = $xml->xpath("/GeocodeResponse/result/address_component[type = 'locality']/long_name/text()")[0];
		$sublocality_level_1 = $xml->xpath("/GeocodeResponse/result/address_component[type = 'sublocality_level_1']/long_name/text()")[0];
		
		return ['lat' => $lat, 'lng' => $lng, 'street' => (string) $street, 
			'street_number' => (string) $street_number,'city' => (string) $city, 'plz' => (string) $plz,
			'administrative_area_level_1' => (string) $administrative_area_level_1,
			'locality' => (string) $locality,
			'sublocality_level_1' => (string) $sublocality_level_1,
			'partial_match' => ($is_partial_match === true)
		];
	}

	private function saveToDatabase(&$data) {
		global $wpdb;
		date_default_timezone_set(get_option('timezone_string'));

		prt_verify_mysql_table();

		$sql = $wpdb->prepare("INSERT INTO 
			{$wpdb->prefix}prt_requests 
			(`type`, `salutation`, `firstname`, `lastname`, `phone`, `email`, `address`, `wohnflache`, `zimmer`, `baujahr`, `grundflache`, `etage`, `erschlossen`, `bebauung`, `zuschnitt`, `objectCategory`, `resultAbsolute`, `lowAbsolute`, `highAbsolute`, `resultPerSqm`, `lowPerSqm`, `highPerSqm`, `date`, `status`)
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s');",
			[
				$data['type'],
				$data['salutation'],
				$data['firstname'],
				$data['lastname'],
				$data['phone'],
				$data['email'],
				$data['address'],
				$data['living_space'],
				$data['rooms'],
				$data['construction_year'],
				$data['footprint'],
				$data['floor'],
				$data['opened_up'],
				$data['building'],
				$data['cut'],
				$data['objectCategoryName'],
				$data['response']['resultAbsolute'],
				$data['response']['lowAbsolute'],
				$data['response']['highAbsolute'],
				$data['response']['resultPerSqm'],
				$data['response']['lowPerSqm'],
				$data['response']['highPerSqm'],
				date("Y-m-d H:i:s"),
				$data['objectCategoryName'],
				strtoupper($data['status'])

		]);
		$sql_success = $wpdb->query($sql);
		if($sql_success == false) {
			throw new PRT_Exception("Error while inserting into db", $wpdb->last_error);
		}
		$data['_id'] = $wpdb->insert_id;
	}

	private function emailTo($data, $toType = 'customer') {
		$email_content = $this->settings->get_option('email-content-'.$toType, 'prt_settings_email');
		$email_subject = $this->settings->get_option('email-subject-'.$toType, 'prt_settings_email');
		$email_header = array('Content-Type: text/html; charset=UTF-8');
		$toBeReplaced = [
			"{{IMMOBILIEN_TYP}}" => $data['type'],
			"{{ANREDE}}" => $data['salutation'],
			"{{VORNAME}}" => $data['firstname'],
			"{{NACHNAME}}" => $data['lastname'],
			"{{TELEFON}}" => $data['phone'],
			"{{EMAIL}}" => $data['email'],
			"{{ADRESSE}}" => $data['address'],
			"{{WOHNFLACHE}}" => $data['living_space'],
			"{{ZIMMER}}" =>	$data['rooms'],
			"{{BAUJAHR}}" => $data['construction_year'],
			"{{GRUNDFLACHE}}" => $data['footprint'],
			"{{ETAGE}}" => $data['floor'],
			"{{ERSCHLOSSEN}}" => $data['opened_up'],
			"{{BEBAUUNG}}" => $data['building'],
			"{{ZUSCHNITT}}" => $data['cut'],
			"{{GESAMT_ERGEBNIS}}" => @number_format($data['response']['resultAbsolute'], 2, ',', '.') .' €',
			"{{MIN_GESAMT_ERGEBNIS}}" => @number_format($data['response']['lowAbsolute'], 2, ',', '.').' €',
			"{{MAX_GESAMT_ERGEBNIS}}" => @number_format($data['response']['highAbsolute'], 2, ',', '.') .' €',
			"{{ERGEBNIS_PRO_QM}}" => @number_format($data['response']['resultPerSqm'], 2, ',', '.') .' €',
			"{{MIN_ERGEBNIS_PRO_QM}}" => @number_format($data['response']['lowPerSqm'], 2, ',', '.') .' €',
			"{{MAX_ERGEBNIS_PRO_QM}}" => @number_format($data['response']['highPerSqm'], 2, ',', '.') .' €'
		];
		$email_content = strtr($email_content, $toBeReplaced);
		$email_subject = strtr($email_subject, $toBeReplaced);

		if($toType == 'customer') {
			$attachment = array();

			$extra_attachment = $this->settings->get_option('email-attachment-customer', 'prt_settings_email', null);
			if(!empty($extra_attachment)) {
				$res = wp_remote_get( $extra_attachment );
				$http_code = strval( wp_remote_retrieve_response_code($res) );
				$res = wp_remote_retrieve_body($res);
				if(@substr($http_code, 0, 1) == '2') { // startWith 2xx
					$filename = basename($extra_attachment); //gets filename after last slash
					$new_tmp_dir = sys_get_temp_dir() . '/' . uniqid('prt_', true);
					@mkdir($new_tmp_dir, 0777, true);
					$data['extraAttachment_dir'] = $new_tmp_dir;
					$data['extraAttachment'] = tempnam($new_tmp_dir, 'prt');
					chmod($data['extraAttachment'], 0777);
					rename($data['extraAttachment'], $new_tmp_dir . '/' . $filename);
					$data['extraAttachment'] = $new_tmp_dir . '/' . $filename;
					$fop = fopen($data['extraAttachment'], 'w+');
					fwrite($fop, $res['content']);
					array_push($attachment, $data['extraAttachment']);
				}
			}

			$this->log->info('Trying to send admin-mail: ', 
				[
					'receiver' => $data['email'],
					'subject' => $email_subject,
					'content_length' => strlen($email_content),
					'email_header' => $email_header,
					'attachment' => $attachment
				]
			);
			$mail_send = wp_mail($data['email'], $email_subject, $email_content, $email_header, $attachment);
			if(!$mail_send) throw new PRT_Exception("Mail (customer) couldn't be sent");
			else $this->log->info('Customer Mail send successfully');

			if(!empty($data['extraAttachment'])) {
				unlink($data['extraAttachment']);
				rmdir($data['extraAttachment_dir']);
				$data['extraAttachment'] = null;
				$data['extraAttachment_dir'] = null;
			}
		} else {
			$dir = PRT_DIR_HOME. 'admin/openimmo_exports/';
			if(!file_exists($dir)) mkdir($dir, 0777, true);
			@$this->generateOpenImmoXML($data, $dir);
			$attachment = array($data['openimmo_file']);
			if(empty($this->settings->get_option('admin-email', 'prt_settings_email'))) throw new PRT_Exception("Admin Mail not setted");
			$this->log->info('Trying to send admin-mail: ', 
				[
					'receiver' => $this->settings->get_option('admin-email', 'prt_settings_email'),
					'subject' => $email_subject,
					'content_length' => strlen($email_content),
					'email_header' => $email_header,
					'attachment' => $attachment
				]
			);
			$mail_send = wp_mail($this->settings->get_option('admin-email', 'prt_settings_email'), 
					$email_subject, $email_content, $email_header, $attachment);
			if(!$mail_send) throw new PRT_Exception("Mail couldn't be sent");
			else $this->log->info('Admin Mail send successfully');

			unlink($data['openimmo_file']);
		}
	}

	private function generateOpenImmoXML(&$data, $dir) {
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><openimmo_feedback/>');
        $xml->addChild('version', '1.2.5');

        // Sender
        $x_sender = $xml->addChild('sender');
        $x_sender->addChild('name', $this->settings->get_option('makler_name', 'prt_settings_general'));
        $x_sender->addChild('datum', date('d.m.Y'));
        $x_sender->addChild('makler_id', '');
        $x_sender->addChild('regi_id', $this->settings->get_option('makler_name', 'prt_settings_general'));

        // Objekt
        $x_objekt = $xml->addChild('objekt');
        $x_objekt->addChild('portal_obj_id', '');
        $x_objekt->addChild('oobj_id', $this->settings->get_option('objectnumber', 'prt_settings_general'));

        // Objekt > Interessent
        $x_interessent = $x_objekt->addChild('interessent');
		$x_interessent->addChild('anrede', $data['salutation']);
		$x_interessent->addChild('vorname', $data['firstname']);
		$x_interessent->addChild('nachname', $data['lastname']);
		$x_interessent->addChild('strasse', @$data['geo']['street']);
		$x_interessent->addChild('hausnummer', @$data['geo']['street_number']);
		$x_interessent->addChild('ort', @$data['geo']['city']);
		$x_interessent->addChild('plz', @$data['geo']['plz']);
		$x_interessent->addChild('tel', $data['phone']);
		$x_interessent->addChild('email', $data['email']);
		$x_interessent->addChild('bevorzugt', 'TEL');
		$x_interessent->addChild('wunsch', 'ANRUF');
		$x_interessent->addChild('anfrage', $this->dataToOIAnfrage($data));

		$file_path = $dir.'openimmo_'.time().'.xml';
		file_put_contents($file_path, "\xEF\xBB\xBF".$xml->asXML());
		$data['openimmo_file'] = $file_path;
	}

	private function dataToOIAnfrage($data) {
		$response = "";
		foreach ($data as $key => $value) {
			if(!is_array($value)) $response .= __($key, 'prt') . ': ' . $value . PHP_EOL;
		}
		return $response;
	}

	private function jsonErrorResponse($msg, $extraMessage = null) {
		$res = ['success' => false, 'msg' => $msg];
		if($extraMessage != null) {
			$res['extra_message'] = $extraMessage;
		}
		echo json_encode($res);
	}

	private function jsonErrorResponseAsArray($msg, $extraMessage = null) {
		$res = ['success' => false, 'msg' => $msg];
		if($extraMessage != null) {
			$res['extra_message'] = $extraMessage;
		}
		return $res;
	}

	public function ajax_prt_getsteps() {
		$type = sanitize_text_field($_POST['type']);
		if($type) {
			$r = $this->loadStepClass($type);
			echo json_encode($r->getResponse());
			wp_die();
		}
		echo 'AJAX Called without POST Data.';
		wp_die();
	}

	public function ajax_prt_geo() {
		$response = ['status' => 'fail'];
		$address = sanitize_text_field($_POST['address']);

		if($address && !empty($address)) {
			$geo = $this->requestGeolocation($address);
			if(is_array($geo) && !empty($geo)) {
				$response = [
					'status' => 'success',
					'data' => $geo,
					'partial_match' => $geo['partial_match'],
					'full_address' => $geo['street'].' '.$geo['street_number'].', '.$geo['plz'].' '.$geo['city']
				];
			} else {
				if ($geo === false) {
					$response['error'] = 'Wir konnten Ihre eingegebene Adresse nicht finden. Möchten Sie dennoch fortfahren?';
					$response['do_confirm'] = true;
				} else {
					$response['error'] = $geo;
				}
			}
		}

		echo json_encode($response);
		wp_die();
	}

	public function sendEmails(&$data) {
		//Send email to admin
		$this->emailTo($data, 'admin');

		//Send email to customer
		$this->emailTo($data, 'customer');
	}

	public function ajax_prt_submit() {
		$type = sanitize_text_field($_POST['type']);
		if(!empty($type)) {
			$r = $this->loadStepClass($type);
			$data = null;
			try {
				// Validate data and create structure
				$r->validateData();
				$data = $r->collectData();
				// force_no_rate will not be used, because this is basic, it's always no_rate
				$data['force_no_rate'] = strval( sanitize_text_field($_POST['force_no_rate']) ) === 'true';

				// Parsing some required data
				$this->parseData($data);

				$response = ['status' => 'no_rate'];
				$data['response'] = $response;
				
				if(isset($data['no_show_span']) && $data['no_show_span'] === true) {
					$response['no_show_span'] = $data['no_show_span'];
				}

				$data = array_merge($response, $data);

				// Save to Database
				$this->saveToDatabase($data);

				// save the data object as a transient for 10 minutes,
				// until it will manually deleted by the ajax_prt_sendmails method
				set_transient('prt_request_' . $data['_id'], $data, 60*10);

				$response['_id'] = $data['_id'];

				// Return response to Client
				echo json_encode($response);
			} catch(PRT_Exception $prt_e) {
				$this->jsonErrorResponse($prt_e->getMessage(), $prt_e->getExtraMessage());
				$this->log->error('PRT_Exception: ' . $prt_e);
			} catch(Exception $e) {
				$this->jsonErrorResponse($e->getMessage());
				$this->log->error('Exception: ' . $e->getMessage());
			}
		}

		wp_die();
	}

	public function ajax_prt_sendmails() {
		$id = sanitize_text_field($_POST['_id']);
		$data = get_transient( 'prt_request_' . $id );
		
		$errors = [];

		try {
			if ($data === false || !is_array($data) || empty($data)) {
				throw new PRT_Exception('No data found in transient database');
			}

			//Save to Database and send mails
			$this->sendEmails($data);

			echo json_encode(['status' => 'success']);
		} catch(PRT_Exception $prt_e) {
			$errors[] = $this->jsonErrorResponseAsArray($prt_e->getMessage(), $prt_e->getExtraMessage());
			$this->log->error('PRT_Exception: ' . $prt_e);
			echo json_encode($errors);
		} catch(Exception $e) {
			$errors[] = $this->jsonErrorResponseAsArray($e->getMessage());
			$this->log->error('Exception: ' . $e->getMessage());
			echo json_encode($errors);
		} finally {
			delete_transient( 'prt_request_' . $id );
		}

		wp_die();
	}

	public function ajax_prt_statistic() {

		$cookieSet = null;

		if(!isset($_COOKIE['prt_wp_stat_id']) || empty($_COOKIE['prt_wp_stat_id'])) {
			$cookieSet = uniqid('prt_', true);
			setcookie("prt_wp_stat_id", $cookieSet, time()+ (3600 *24));
		}

		if(!empty($_POST['current']) && ($cookieSet !== null || !empty($_COOKIE['prt_wp_stat_id'])) && !empty($_POST['finished'])) {
			date_default_timezone_set(get_option('timezone_string'));
			$current = sanitize_text_field( $_POST['current'] );
			$statId = $cookieSet === null ? $_COOKIE['prt_wp_stat_id'] : $cookieSet;
			$finished = sanitize_text_field( $_POST['finished'] ) == 'true' ? 1 : 0;
			$date = date("Y-m-d H:i:s");

			global $wpdb;
			$stat_exists = intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM {$wpdb->prefix}prt_statistics WHERE id = %s", [$statId]))) === 1;

			if($stat_exists) {
				$wpdb->update($wpdb->prefix . 'prt_statistics',
					array(
						'last_activity' => $current,
						'finished' => $finished,
						'date' => $date
					),
					array('id' => $statId), array('%s', '%d', '%s'), array('%s')
				);
			} else {
				$user = wp_get_current_user();
				$geo = wp_remote_retrieve_body( wp_remote_get( 'http://geoip.cdnservice.eu/api/'.$_SERVER['REMOTE_ADDR']) );
				$geo = json_decode( $geo );
				$browser = new Browser();
				$os = new Os();
				$device = new Device();
				$language = new Language();

				// DSGVO - safe
				$dsgvoIp = explode('.', $_SERVER['REMOTE_ADDR']);
				array_pop($dsgvoIp);
				$dsgvoIp = implode('.', $dsgvoIp); 

				$sql = $wpdb->prepare("INSERT INTO `{$wpdb->prefix}prt_statistics` (`id`, `wp_username`,`ip`,`country`,`city`,`user_agent`,`user_browser`,`user_os`,`user_device`,`user_lang`,`last_activity`,`finished`,`date`)
										VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s)",
					[
						$statId,
						$user->user_login,
						$dsgvoIp,
						$geo->geoplugin_countryCode,
						$geo->geoplugin_city,
						$_SERVER['HTTP_USER_AGENT'],
						$browser->getName(),
						$os->getName(),
						$device->getName(),
						$language->getLanguage(),
						$current,
						$finished,
						$date
					]
				);
				$sql_success = $wpdb->query($sql);
				if($sql_success == false) {
					echo "Error while inserting statistics into db: " . $wpdb->last_error;
					wp_die();
				}
			}

			if($finished === 1) { //if true
				unset($_COOKIE['prt_wp_stat_id']);
				setcookie("prt_wp_stat_id", '', time() - 3600); //delete cookie
			}

			echo "1";
		} else
			echo "0";

		wp_die();
	}

	public function register_styles() {
		wp_register_style($this->prt, plugin_dir_url( __FILE__ ) . 'css/prt-'.$this->activeTheme.'.css', array(), $this->version, 'all' );
		wp_register_style('nouislider-css', plugin_dir_url( __FILE__ ) . 'css/nouislider.min.css', array(), $this->version, 'all' );
		wp_register_style('owl-carousel', plugin_dir_url( __FILE__ ) . 'css/owl.carousel.min.css', array(), $this->version, 'all' );
		wp_register_style('owl-carousel-theme', plugin_dir_url( __FILE__ ) . 'css/owl.theme.default.css', array(), $this->version, 'all' );
		wp_register_style('hover-css', plugin_dir_url( __FILE__ ) . 'css/hover-min.css', array(), $this->version, 'all' );
	}

	public function register_scripts() {
		wp_register_script('owl-carousel', plugin_dir_url( __FILE__ ) . 'js/owl.carousel.min.js', array(), $this->version);
		wp_register_script('nouislider', plugin_dir_url( __FILE__ ) . 'js/nouislider.min.js', array(), $this->version);
		wp_register_script('tippy-tooltip', plugin_dir_url( __FILE__ ) . 'js/tippy.all.min.js', array(), $this->version);
		wp_register_script($this->prt, plugin_dir_url( __FILE__ ) . 'js/prt-public.js', array( 'owl-carousel', 'tippy-tooltip', 'jquery'), $this->version);
	}

	/**
	 * Enqueue the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->prt);
		wp_enqueue_style('nouislider-css');
		wp_enqueue_style('owl-carousel');
		wp_enqueue_style('owl-carousel-theme');
		wp_enqueue_style('hover-css');
	}

	/**
	 * Will be called if PRT_INCLUDE shotcode will be triggerd.
	 *
	 * @since    1.0.0
	 */
	public function shortcode_prt_include() {
			wp_enqueue_script('owl-carousel');
			wp_enqueue_script('tippy-tooltip');
			wp_enqueue_script('nouislider');
			wp_enqueue_script('jquery');
			wp_enqueue_script($this->prt);

			wp_localize_script( $this->prt, 'prt_ajax_object',
				array('ajax_url' => admin_url( 'admin-ajax.php')));

			ob_start();
			require plugin_dir_path( __FILE__ ) . "theme/". $this->activeTheme ."/shortcode.php";
			$shortcode_response = ob_get_clean();

			return $shortcode_response;
	}

}