<?php

class Prt_Importer {

    /**
	 * The ID of this plugin.
	 *
	 * @since    1.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $prt;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
	 * The settings of this plugin.
	 *
	 * @since    1.1
	 * @access   private
	 * @var      object    $settings    The current version of this plugin.
	 */
	private $settings;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      object    $settings    The settings of this plugin.
	 */
	public function __construct( $prt, $version, $settings) {
		$this->prt = $prt;
		$this->version = $version;
		$this->settings = $settings;
	}
	
	private function getErrorFromImport($code) {
		$errorCodes = [1 => 'Uploaded file is invalid.', 2 => 'JSON parsing error.'];
		if (isset($code) && array_key_exists($code, $errorCodes)) {
			return $errorCodes[$code];
		} else {
			return "Fehler beim Importieren.";
		}
	}

    public function render() {
		if (isset($_GET['success']) ) {
			if (@intval($_GET['success']) === 1) {
				echo '<div class="notice notice-success"><p>Erfolgreich impotiert!</p></div>';
			} else {
				$msg = $this->getErrorFromImport(sanitize_text_field( $_GET['errorCode'] ));
				echo "<div class=\"notice notice-error\"><p>$msg</p></div>";
			}
			
		}

		?>

        <div class="prt-importer-exporter">
            <div class="prt-import">
                <h1>Import</h1>
                <h4>Hier können Sie all Ihre Einstellungen mit einer JSON-Datei importieren welches Sie auf einer anderen PRT-aktivierten WordPress Instanz exportiert haben.</h4>
                <form id="prt_import_json" action="<?php echo admin_url( 'admin-ajax.php' ) ?>" method="POST" enctype="multipart/form-data">
                    <input name="action" type="hidden" value="prt_import">
					<input name="import_file" type="file">
                    <button class="button button-primary" type="submit">Importieren</button>
                </form>
            </div>
            <div class="prt-export">
                <h1>Export</h1>
                <h4>Hier können Sie all Ihre Einstellungen mit einem Klick exportieren und auf einer anderen PRT-aktivierten WordPress Instanz importieren.</h4>
                <form id="prt_download_exported_json" action="<?php echo admin_url( 'admin-ajax.php' ) ?>" method="POST">
                    <input name="action" type="hidden" value="prt_export">
                    <button class="button button-primary" type="submit">Exportieren</button>
                </form>
            </div>
        </div>

        <?php
    }
    
    public function ajax_prt_import() {
		if ($_FILES['import_file']['error'] != UPLOAD_ERR_OK || !is_uploaded_file($_FILES['import_file']['tmp_name'])) {
			wp_redirect( admin_url('admin.php?page=prt_importer&success=0&errorCode=1')); //no success
			exit();
		}
		$opts = json_decode( file_get_contents($_FILES['import_file']['tmp_name']) , true); //for JSON data as File

		if (json_last_error() != 0) {
			wp_redirect( admin_url('admin.php?page=prt_importer&success=0&errorCode=2&errorMsg=' . json_last_error())); //no success
			exit();
		}

        $count = 0;

        $opts['prt_settings_email']['email-content-customer'] = $this->extract_urls_and_download($opts['prt_settings_email']['email-content-customer']);
        $opts['prt_settings_email']['email-content-admin'] = $this->extract_urls_and_download($opts['prt_settings_email']['email-content-admin']);

        $opts['prt_settings_email']['email-attachment-customer'] = $this->download_image_to_media($opts['prt_settings_email']['email-attachment-customer']);

        $opts['prt_settings_general']['advantages'] = $this->extract_urls_and_download($opts['prt_settings_general']['advantages']);
        $opts['prt_settings_general']['privacy-policy'] = $this->extract_urls_and_download($opts['prt_settings_general']['privacy-policy']);

        foreach ($opts as $section => $sectionArr) {

            if (is_array($sectionArr)) {
                update_option($section, $sectionArr);
                $count++;
            }
        }

		$toLog = "Successfully imported $count objects! : " . json_last_error();
		$resp = [];
		$resp['toLog'] = $toLog;
		$resp['json'] = $opts;

		#header('Content-Type: application/json');
		#echo json_encode($resp, JSON_PRETTY_PRINT);

		wp_redirect( admin_url('admin.php?page=prt_importer&success=1') ); // success!
		exit();
    }
    
    public function ajax_prt_export() {
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"prt_settings.json\"");

        $opts = [];
        $opts['prt_settings_general'] = get_option( 'prt_settings_general' );
        $opts['prt_settings_font'] = get_option( 'prt_settings_font' );
        $opts['prt_settings_color'] = get_option( 'prt_settings_color' );
        $opts['prt_settings_titles'] = get_option( 'prt_settings_titles' );
        $opts['prt_settings_email'] = get_option( 'prt_settings_email' );
        $opts['prt_settings_css'] = get_option( 'prt_settings_css' );
        $opts['prt_settings_texts'] = get_option( 'prt_settings_texts' );

		echo json_encode($opts, JSON_PRETTY_PRINT);
		wp_die();
    }

	/**
	 * This function downloads a image and adds it into the WP media.
	 * After that it will return the new URL to the image. Or empty string if it has a error
	 */
	public function download_image_to_media($url) {
		$filename = basename($url);
		$file = download_url($url);

		// Check if download was successful.
		if (is_wp_error($file)) return $url; //fallback

		// Check file type
		$allowed_mime_types = ['image/jpeg', 'image/svg+xml', 'image/gif', 'image/png', 'application/pdf'];
		$filetype = wp_check_filetype($filename);
		if (!isset($filetype['type']) || !in_array($filetype['type'], $allowed_mime_types)) return $url; //fallback
		
		// Add to media and return url
		$upload_file = wp_upload_bits($filename, null, file_get_contents($file));
		if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent' => $parent_post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
			}

			return wp_get_attachment_url( $attachment_id ); //new URL
		} else {
			return $url; //fallback
		}
	}

	public function extract_urls_and_download($text) {
		$reg_exUrl = "#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#";
		$allowed_mime_types = ['image/jpeg', 'image/svg+xml', 'image/gif', 'image/png', 'application/pdf'];
		
		preg_match_all($reg_exUrl, $text, $founds);

		foreach (@$founds[0] as $url) {
			$filetype = wp_check_filetype($url);
			if (isset($filetype['type']) && in_array($filetype['type'], $allowed_mime_types)) {
				$newUrl = $this->download_image_to_media($url);
				$text = str_replace($url, $newUrl, $text);
			}
		}

		return $text;
	}

}