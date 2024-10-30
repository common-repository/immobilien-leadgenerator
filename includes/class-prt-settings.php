<?php

// Required
require_once PRT_DIR_HOME . 'includes/class-wposa.php';

class Prt_Settings {

	private $wposa;

    public function __construct($run) {
        if($run) $this->runSettings();
    }

    public function runSettings() {
        $this->wposa = new WP_OSA();
        $this->section_general();
		$this->section_font();
		$this->section_titles();
		$this->section_css();
		$this->section_color();
		$this->section_email();
		$this->section_texts();
	}

	public function print_settings() {
		$this->wposa->plugin_page();
	}

    public function section_general() {
        //Section
        $this->wposa->add_section(
			array(
				'id'    => 'prt_settings_general',
				'title' => __( 'Grundeinstellungen', 'prt' ),
			)
        );
		
		//Field: Company Name
		$this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'company-name',
				'type'    => 'text',
				'name'    => __( 'Firmenname', 'prt' ),
				'desc'    => '<span class="dashicons dashicons-info" aria-hidden="true"></span> Geben Sie hier bitte Ihren Firmennamen ein, dieser wird über dem Kontaktformular im letzten Schritt angezeigt.',
				'placeholder' => 'Firmenname',
			)
        );

        //Field: GOOGLE API KEY
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'google-api-key',
				'type'    => 'text',
				'name'    => __( 'Google Maps API', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Geben Sie hier bitte den Schlüssel (Key) für die Google API ein. Zur Erstellung der API Zugangsdaten, <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key">klicken Sie bitte hier</a>', 'prt' ),
				'placeholder' => '',
			)
        );
       
        //Field: Advantages
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'advantages',
				'type'    => 'wysiwyg',
				'name'    => __( 'Vorteile', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Tragen Sie hier Ihre Alleinstellungsmerkmale ein, diese werden im letzten Schritt angezeigt. HTML ist erlaubt.<br>Benutzen Sie <b>&#x3C;ul class=&#x22;haken&#x22;&#x3E;</b> um die Punkte in der Auflistung durch Haken zu ersetzten.', 'prt' ),
				'default' => '<ul class="haken">
				<li>Regionale Kompetenz</li>
				<li>Garantiert kostenlose Immobilienbewertung</li>
				<li>Hohe Kundenzufriedenheit der Eigentümer</li>
			</ul>',
			)
		);
		//Field: Privacy Policy (Datenschutz)
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'privacy-policy',
				'type'    => 'wysiwyg',
				'name'    => __( 'Datenschutz', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Tragen Sie hier Ihre Datenschutzerklärung ein. HTML ist erlaubt.', 'prt' ),
				'default' => '<h1>Datenschutzerklärung</h1>
							<h2>Datenschutz</h2>
							Die Betreiber dieser Seiten nehmen
							den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln Ihre personenbezogenen Daten
							vertraulich und entsprechend der gesetzlichen Datenschutzvorschriften sowie dieser
							Datenschutzerklärung.

							Die Nutzung unserer Webseite ist in der Regel ohne Angabe
							personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten
							(beispielsweise Name, Anschrift oder E-Mail-Adressen) erhoben werden, erfolgt dies, soweit
							möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung
							nicht an Dritte weitergegeben.

							Wir weisen darauf hin, dass die Datenübertragung im Internet
							(z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser
							Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.

							&nbsp;
							<h2>Cookies</h2>
							Die Internetseiten verwenden teilweise so genannte Cookies. Cookies richten auf Ihrem
							Rechner keinen Schaden an und enthalten keine Viren. Cookies dienen dazu, unser Angebot
							nutzerfreundlicher, effektiver und sicherer zu machen. Cookies sind kleine Textdateien, die auf Ihrem
							Rechner abgelegt werden und die Ihr Browser speichert.

							Die meisten der von uns verwendeten
							Cookies sind so genannte „Session-Cookies“. Sie werden nach Ende Ihres Besuchs automatisch
							gelöscht. Andere Cookies bleiben auf Ihrem Endgerät gespeichert, bis Sie diese löschen.
							Diese Cookies ermöglichen es uns, Ihren Browser beim nächsten Besuch
							wiederzuerkennen.

							Sie können Ihren Browser so einstellen, dass Sie über das Setzen
							von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für
							bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der
							Cookies beim Schließen des Browser aktivieren. Bei der Deaktivierung von Cookies kann die
							Funktionalität dieser Website eingeschränkt sein.

							&nbsp;
							<h2>Server-Log-
							Files</h2>
							Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten
							Server-Log Files, die Ihr Browser automatisch an uns übermittelt. Dies sind:
							<ul>
								<li>Browsertyp und Browserversion</li>
								<li>verwendetes Betriebssystem</li>
								<li>Referrer URL</li>
								<li>Hostname des zugreifenden Rechners</li>
								<li>Uhrzeit der Serveranfrage</li>
							</ul>
							Diese Daten sind
							nicht bestimmten Personen zuordenbar. Eine Zusammenführung dieser Daten mit anderen
							Datenquellen wird nicht vorgenommen. Wir behalten uns vor, diese Daten nachträglich zu
							prüfen, wenn uns konkrete Anhaltspunkte für eine rechtswidrige Nutzung bekannt werden.

							&nbsp;
							<h2>Kontaktformular</h2>
							Wenn Sie uns per Kontaktformular Anfragen zukommen
							lassen, werden Ihre Angaben aus dem Anfrageformular inklusive der von Ihnen dort angegebenen
							Kontaktdaten zwecks Bearbeitung der Anfrage und für den Fall von Anschlussfragen bei uns
							gespeichert. Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.

							&nbsp;
							<h2>Facebook-Plugins (Like-Button)</h2>
							Auf unseren Seiten sind Plugins des sozialen Netzwerks
							Facebook, Anbieter Facebook Inc., 1 Hacker Way, Menlo Park, California 94025, USA, integriert. Die
							Facebook-Plugins erkennen Sie an dem Facebook-Logo oder dem "Like-Button" ("Gefällt mir") auf
							unserer Seite. Eine Übersicht über die Facebook-Plugins finden Sie hier: <a href="https://developers.facebook.com/docs/plugins/">https://developers.facebook.com/docs/plugins/</a>
							.

							Wenn Sie unsere Seiten besuchen, wird über das Plugin eine direkte Verbindung zwischen
							Ihrem Browser und dem Facebook-Server hergestellt. Facebook erhält dadurch die Information,
							dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Facebook "Like-Button"
							anklicken während Sie in Ihrem Facebook-Account eingeloggt sind, können Sie die Inhalte
							unserer Seiten auf Ihrem Facebook-Profil verlinken. Dadurch kann Facebook den Besuch unserer Seiten
							Ihrem Benutzerkonto zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis
							vom Inhalt der übermittelten Daten sowie deren Nutzung durch Facebook erhalten. Weitere
							Informationen hierzu finden Sie in der Datenschutzerklärung von Facebook unter <a href="https://dede. facebook.com/policy.php">https://de-de.facebook.com/policy.php</a>.

							Wenn Sie nicht
							wünschen, dass Facebook den Besuch unserer Seiten Ihrem Facebook-Nutzerkonto zuordnen
							kann, loggen Sie sich bitte aus Ihrem Facebook-Benutzerkonto aus.

							&nbsp;
							<h2>Twitter</h2>
							Auf unseren Seiten sind Funktionen des Dienstes Twitter eingebunden. Diese Funktionen werden
							angeboten durch die Twitter Inc., 1355 Market Street, Suite 900, San Francisco, CA 94103, USA. Durch
							das Benutzen von Twitter und der Funktion "Re-Tweet" werden die von Ihnen besuchten Webseiten mit
							Ihrem Twitter-Account verknüpft und anderen Nutzern bekannt gegeben. Dabei werden auch Daten
							an Twitter übertragen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom
							Inhalt der übermittelten Daten sowie deren Nutzung durch Twitter erhalten. Weitere Informationen
							hierzu finden Sie in der Datenschutzerklärung von Twitter unter <a href="https://twitter.com/privacy">
							https://twitter.com/privacy</a>.

							Ihre Datenschutzeinstellungen bei Twitter können Sie in den
							Konto-Einstellungen unter: <a href="https://twitter.com/account/settings">
							https://twitter.com/account/settings</a> ändern.

							&nbsp;
							<h2>Google+</h2>
							Unsere
							Seiten nutzen Funktionen von Google+. Anbieter ist die Google Inc., 1600 Amphitheatre Parkway
							Mountain View, CA 94043, USA.

							Erfassung und Weitergabe von Informationen: Mithilfe der
							Google+-Schaltfläche können Sie Informationen weltweit veröffentlichen. Über die
							Google+-Schaltfläche erhalten Sie und andere Nutzer personalisierte Inhalte von Google und
							unseren Partnern. Google speichert sowohl die Information, dass Sie für einen Inhalt +1 gegeben
							haben, als auch Informationen über die Seite, die Sie beim Klicken auf +1 angesehen haben. Ihre
							+1 können als Hinweise zusammen mit Ihrem Profilnamen und Ihrem Foto in Google-Diensten, wie
							etwa in Suchergebnissen oder in Ihrem Google-Profil, oder an anderen Stellen auf Websites und
							Anzeigen im Internet eingeblendet werden.

							Google zeichnet Informationen über Ihre +1-
							Aktivitäten auf, um die Google-Dienste für Sie und andere zu verbessern. Um die Google+-
							Schaltfläche verwenden zu können, benötigen Sie ein weltweit sichtbares,
							öffentliches Google-Profil, das zumindest den für das Profil gewählten Namen enthalten
							muss. Dieser Name wird in allen Google-Diensten verwendet. In manchen Fällen kann dieser Name
							auch einen anderen Namen ersetzen, den Sie beim Teilen von Inhalten über Ihr Google-Konto
							verwendet haben. Die Identität Ihres Google-Profils kann Nutzern angezeigt werden, die Ihre E-Mail-
							Adresse kennen oder über andere identifizierende Informationen von Ihnen verfügen.

							Verwendung der erfassten Informationen: Neben den oben erläuterten Verwendungszwecken
							werden die von Ihnen bereitgestellten Informationen gemäß den geltenden Google-
							Datenschutzbestimmungen genutzt. Google veröffentlicht möglicherweise zusammengefasste
							Statistiken über die +1-Aktivitäten der Nutzer bzw. gibt diese an Nutzer und Partner weiter, wie
							etwa Publisher, Inserenten oder verbundene Websites.

							&nbsp;
							<h2>XING</h2>
							Unsere
							Webseite nutzt Funktionen des Netzwerks XING. Anbieter ist die XING AG, Dammtorstraße 29-32,
							20354 Hamburg, Deutschland. Bei jedem Abruf einer unserer Seiten, die Funktionen von XING
							enthält, wird eine Verbindung zu Servern von XING hergestellt. Eine Speicherung von
							personenbezogenen Daten erfolgt dabei nach unserer Kenntnis nicht. Insbesondere werden keine IPAdressen
							gespeichert oder das Nutzungsverhalten ausgewertet.

							Weitere Information zum
							Datenschutz und dem XING Share-Button finden Sie in der Datenschutzerklärung von XING unter:
							<a href="https://www.xing.com/app/share?op=data_protection">
							https://www.xing.com/app/share?op=data_protection</a>

							&nbsp;
							<h2>Recht auf Auskunft,
							Löschung, Sperrung</h2>
							Sie haben jederzeit das Recht auf unentgeltliche Auskunft über
							Ihre gespeicherten personenbezogenen Daten, deren Herkunft und Empfänger und den Zweck der
							Datenverarbeitung sowie ein Recht auf Berichtigung, Sperrung oder Löschung dieser Daten. Hierzu
							sowie zu weiteren Fragen zum Thema personenbezogene Daten können Sie sich jederzeit unter der
							im Impressum angegebenen Adresse an uns wenden.

							&nbsp;
							<h2>Widerspruch Werbe-
							Mails</h2>
							Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten
							zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien
							wird hiermit widersprochen. Die Betreiber der Seiten behalten sich ausdrücklich rechtliche Schritte
							im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-E-Mails, vor.

							&nbsp;

							Quelle: <a href="https://www.e-recht24.de/muster-datenschutzerklaerung.html">
							https://www.e-recht24.de/muster-datenschutzerklaerung.html</a>',
			)
		);
		//Field: Objektnummer
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'objectnumber',
				'type'    => 'text',
				'name'    => __( 'Objektnummer', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Diese Nummer wird zur Zuordnung des Leads beim Import der OpenImmo XML in Ihrer Immobiliensoftware benötigt', 'prt' ),
				'placeholder' => 'Objektnummer'
			)
		);
		//Field: Makler Name für OpenImmo
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'makler_name',
				'type'    => 'text',
				'name'    => __( 'Makler Name', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Dieser Name wird beim Import der OpenImmo XML in Ihrer Immobiliensoftware benötigt', 'prt' ),
				'placeholder' => 'Makler Name'
			)
		);
		//Field: Shortcode
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'shortcode',
				'type'    => 'text',
				'name'    => __( 'Shortcode', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie den Shortcode austauschen. Bitte geben Sie die eckigen Klammern mit ein.<br>Beispiele: <code>[BEWERTUNG]</code> oder <code>[LEADGENERATOR]</code>', 'prt' ),
				'default' => '[PRT_INCLUDE]'
			)
		);

		//Field: Disable Step Options
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'active_steps',
				'type'    => 'multicheck',
				'name'    => __( 'Aktive Immobilientypen', 'prt' ),
				'desc'    => __( 'Hier können Sie einstellen welche Immobilientypen angezeigt werden.', 'prt' ),
				'options' => array(
					'wohnung' => 'Wohnung',
					'haus'  => 'Haus',
					'grundstuck'  => 'Grundstück',
					'miete'  => 'Miete'
				),
				'default' => array(
					'wohnung' => 'wohnung',
					'haus'  => 'haus',
					'grundstuck'  => 'grundstuck',
					'miete'  => 'miete'
				)
			)
		);

		//Field: Google Tracking via Adwords or Analytics
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'ga_adwords_analytics',
				'type'    => 'multicheck',
				'name'    => __( 'Tracking via', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie einstellen welche Trackingmethode Sie benutzen möchten.', 'prt' ),
				'options' => array(
					'google_analytics' => 'Google Analytics',
					'google_adwords'  => 'Google AdWords'
				),
				'default' => array(
					'google_analytics' => 'google_analytics'
				)
			)
		);

		//Field: Google AdWords: Conv ID
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'ga_adwords_conv_id',
				'type'    => 'text',
				'name'    => __( 'AdWords: Conversion ID', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Geben Sie ein welche Conversion ID sie für die Wertermittlung in AdWords nutzen.', 'prt' ),
				'placeholder' => "Conversion ID"
			)
		);
		//Field: Google AdWords: Conv Label
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'ga_adwords_conv_label',
				'type'    => 'text',
				'name'    => __( 'AdWords: Conversion Label', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Geben Sie ein welche Conversion Label sie für die Wertermittlung in AdWords nutzen.', 'prt' ),
				'placeholder' => "Conversion Label"
			)
		);
		//Field: Truncate Stats
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'truncate_stats',
				'type'    => 'button',
				'name'    => __( 'Statisiken', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Leeren Sie alle lokale Statistiken.', 'prt' ),
				'default' => "Statistiken leeren",
				'href'    => '?page=prt_statistics&action=truncate_stats'
			)
		);

		//Field: Theme
        $this->wposa->add_field(
			'prt_settings_general',
			array(
				'id'      => 'theme',
				'type'    => 'radio',
				'name'    => __( 'Theme', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Welches Theme soll genutzt werden?', 'prt' ),
				'options' => array(
					'modern' => 'Modern'
				),
				'default' => 'modern'
			)
		);
		
    }

    public function section_color() {
        //Section
        $this->wposa->add_section(
			array(
				'id'    => 'prt_settings_color',
				'title' => __( 'Farben', 'prt' ),
			)
        );
        
        //Field: Primary Color
        $this->wposa->add_field(
			'prt_settings_color',
			array(
				'id'      => 'primary_color',
				'type'    => 'color',
				'name'    => __( 'Primäre Farbe', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die primäre Farbe ändern.', 'prt' ),
				'default' => '#0A0A0A'
			)
		);
		//Field: Secondary Color
		$this->wposa->add_field(
			'prt_settings_color',
			array(
				'id'      => 'secondary_color',
				'type'    => 'color',
				'name'    => __( 'Sekundäre Farbe', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die sekundäre Farbe ändern.', 'prt' ),
				'default' => '#ffffff'
			)
		);
		//Field: Progress Color
		$this->wposa->add_field(
			'prt_settings_color',
			array(
				'id'      => 'progress_color',
				'type'    => 'color',
				'name'    => __( 'Forschrittsbalken: Farbe', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die Farbe des Forschrittsbalken abändern.', 'prt' ),
				'default' => '#0A0A0A'
			)
		);
		//Field: Progress Text Color
		$this->wposa->add_field(
			'prt_settings_color',
			array(
				'id'      => 'progress_text_color',
				'type'    => 'color',
				'name'    => __( 'Forschrittsbalken: Schriftfarbe', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die Schriftfarbe des Forschrittsbalken abändern.', 'prt' ),
				'default' => '#ffffff'
			)
		);

		//Field: Button Prev Color
		$this->wposa->add_field(
			'prt_settings_color',
			array(
				'id'      => 'button_prev_color',
				'type'    => 'color',
				'name'    => __( 'Button (Vorheriges):  Farbe', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die Button-Farbe für "Zurück" abändern', 'prt' ),
				'default' => '#fc5c65'
			)
		);
		//Field: Button Next Color
		$this->wposa->add_field(
			'prt_settings_color',
			array(
				'id'      => 'button_next_color',
				'type'    => 'color',
				'name'    => __( 'Button (Nächstes):  Farbe', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die Button-Farbe für "Weiter" abändern', 'prt' ),
				'default' => '#20bf6b'
			)
		);
		//Field: Button Submit Color
		$this->wposa->add_field(
			'prt_settings_color',
			array(
				'id'      => 'button_finish_color',
				'type'    => 'color',
				'name'    => __( 'Button (Absenden):  Farbe', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die Button-Farbe für "Absenden" abändern', 'prt' ),
				'default' => '#20bf6b'
			)
		);
	}	
		
	public function section_font() {
        //Section
        $this->wposa->add_section(
			array(
				'id'    => 'prt_settings_font',
				'title' => __( 'Schriften', 'prt' ),
			)
        );
		
		//Field: Font Size
		$this->wposa->add_field(
			'prt_settings_font',
			array(
				'id'      => 'default_font_size',
				'type'    => 'text',
				'name'    => __( 'Allgemein: Schriftgröße', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die allgemeine Schriftgröße abändern', 'prt' ),
				'default' => '14px'
			)
		);
		//Field: Font Color
		$this->wposa->add_field(
			'prt_settings_font',
			array(
				'id'      => 'default_font_color',
				'type'    => 'color',
				'name'    => __( 'Allgemein: Schriftfarbe', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die allgemeine Schriftfarbe abändern.', 'prt' ),
				'default' => '#0A0A0A'
			)
		);
		//Field: Font Transform
		$this->wposa->add_field(
			'prt_settings_font',
			array(
				'id'      => 'default_font_transform',
				'type'    => 'radio',
				'name'    => __( 'Allgemein: Schrifttransformation', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie bestimmen wie der Text transformiert weden soll', 'prt' ),
				'options' => array(
					'uppercase' => 'GROSSBUCHSTABEN',
					'lowercase' => 'kleinbuchstaben',
					'capitalize'=> 'Erster Buchstabe Groß',
					'none'		=> 'Keine Transformation'
				),
				'default' => 'none'
			)
		);
		//Field: Font Familiy
		$this->wposa->add_field(
			'prt_settings_font',
			array(
				'id'      => 'default_font_family',
				'type'    => 'googlefonts',
				'name'    => __( 'Allgemein: Schriftart', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Wählen Sie eine allgemeine Schriftart aus. Hierbei wird <a href="https://fonts.google.com">Google Fonts</a> verwendet', 'prt' ),
				'default' => 'Open Sans'
			)
		);
		//Field: Font Subset
		$this->wposa->add_field(
			'prt_settings_font',
			array(
				'id'      => 'default_font_subset',
				'type'    => 'select',
				'name'    => __( 'Allgemein: Zeichensatz', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie die den Zeichensatz der gewählten Schriftart ab.', 'prt' ),
				'default' => 'latin',
				'options' => array(
					'latin' => 'Latin (Empfohlen)',
					'latin-ext'  => 'Latin Extended',
					'greek'  => 'Greek',
					'greek-ext'  => 'Greek Extended',
					'cyrillic'  => 'Cyrillic',
					'cyrillic-ext'  => 'Cyrillic Extended',
					'vietnamese'  => 'Vietnamese'
				)
			)
		);
		//Field: Font Weight
		$this->wposa->add_field(
			'prt_settings_font',
			array(
				'id'      => 'default_font_weight',
				'type'    => 'radio',
				'name'    => __( 'Allgemein: Schriftstärke', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Hier können Sie die allgemeine Schriftstärke abändern.', 'prt' ),
				'options' => array(
					'300' => '<span style="font-weight:300">Light (300)</span>',
					'400' => '<span style="font-weight:400">Normal (400)</span>',
					'700' => '<span style="font-weight:700">Bold (700)</span>'
				),
				'default' => '400'
			)
		);
	}

	public function section_titles() {
		//Section
        $this->wposa->add_section(
			array(
				'id'    => 'prt_settings_titles',
				'title' => __( 'Überschriften', 'prt' ),
			)
		);
		
		//Field: H1 Size
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h1_size',
				'type'    => 'text',
				'name'    => __( 'Titel - H1: Schriftgröße', 'prt' ),
				'default' => 'none'
			)
		);
		//Field: H1 Color
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h1_color',
				'type'    => 'color',
				'name'    => __( 'Titel - H1: Farbe', 'prt' ),
				'default' => '#0A0A0A'
			)
		);

		$this->wposa->add_seperator('prt_settings_titles');

		//Field: H2 Size
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h2_size',
				'type'    => 'text',
				'name'    => __( 'Titel - H2: Schriftgröße', 'prt' ),
				'default' => 'none'
			)
		);
		//Field: H2 Color
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h2_color',
				'type'    => 'color',
				'name'    => __( 'Titel - H2: Farbe', 'prt' ),
				'default' => '#0A0A0A'
			)
		);
		
		$this->wposa->add_seperator('prt_settings_titles');

		//Field: H3 Size
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h3_size',
				'type'    => 'text',
				'name'    => __( 'Titel - H3: Schriftgröße', 'prt' ),
				'default' => 'none'
			)
		);
		//Field: H3 Color
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h3_color',
				'type'    => 'color',
				'name'    => __( 'Titel - H3: Farbe', 'prt' ),
				'default' => '#0A0A0A'
			)
		);
		
		$this->wposa->add_seperator('prt_settings_titles');
		
		//Field: H4 Size
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h4_size',
				'type'    => 'text',
				'name'    => __( 'Titel - H4: Schriftgröße', 'prt' ),
				'default' => 'none'
			)
		);
		//Field: H4 Color
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h4_color',
				'type'    => 'color',
				'name'    => __( 'Titel - H4: Farbe', 'prt' ),
				'default' => '#0A0A0A'
			)
		);
		
		$this->wposa->add_seperator('prt_settings_titles');
		
		//Field: H5 Size
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h5_size',
				'type'    => 'text',
				'name'    => __( 'Titel - H5: Schriftgröße', 'prt' ),
				'default' => 'none'
			)
		);
		//Field: H5 Color
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h5_color',
				'type'    => 'color',
				'name'    => __( 'Titel - H5: Farbe', 'prt' ),
				'default' => '#0A0A0A'
			)
		);
		
		$this->wposa->add_seperator('prt_settings_titles');
		
		//Field: H6 Size
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h6_size',
				'type'    => 'text',
				'name'    => __( 'Titel - H6: Schriftgröße', 'prt' ),
				'default' => 'none'
			)
		);
		//Field: H6 Color
		$this->wposa->add_field(
			'prt_settings_titles',
			array(
				'id'      => 'h6_color',
				'type'    => 'color',
				'name'    => __( 'Titel - H6: Farbe', 'prt' ),
				'default' => '#0A0A0A'
			)
		);
	}

    public function section_email() {
        //Section
        $this->wposa->add_section(
			array(
				'id'    => 'prt_settings_email',
				'title' => __( 'E-Mail ', 'prt' ),
			)
		);

		$this->wposa->add_field(
			'prt_settings_email',
			array(
				'id'      => 'admin-email',
				'type'    => 'text',
				'name'    => __( 'Admin E-Mail Adresse', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> An diese E-Mail werden Kundenanfragen mit der OpenImmo XML Datei als Anhang gesendet.', 'prt' )
			)
		);

		//Fields
		$this->wposa->add_field(
			'prt_settings_email',
			array(
				'id'      => 'email-subject-customer',
				'type'    => 'text',
				'name'    => __( 'E-Mail: Betreff (Kunde)', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Betreff der E-Mail, welche an den Kunden versendet wird. Die <b>Variablen</b> (unten) sind erlaubt.', 'prt' ),
				'default' => 'Ihre Immobilienbewertung {{ANREDE}} {{NACHNAME}}'
			)
		);
        
        $this->wposa->add_field(
			'prt_settings_email',
			array(
				'id'      => 'email-content-customer',
				'type'    => 'wysiwyg',
				'name'    => __( 'E-Mail: Inhalt (Kunde)', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Inhalt der E-Mail, welche an den Kunden versendet wird. Sie können diese <b>Variablen</b> nutzen:<br><br>
				{{IMMOBILIEN_TYP}}, {{ANREDE}}, {{VORNAME}}, {{NACHNAME}}, {{TELEFON}},<br>{{EMAIL}}, {{ADRESSE}}, {{WOHNFLACHE}}, {{ZIMMER}},<br>{{BAUJAHR}}, {{GRUNDFLACHE}}, {{ETAGE}}, {{ERSCHLOSSEN}},<br>{{BEBAUUNG}}, {{ZUSCHNITT}}', 'prt' ),
				'default' => '<p>Hallo {{ANREDE}} {{NACHNAME}},</p>
								<p>vielen Dank für Ihre Anfrage.</p>

								<p><strong>Wie geht es weiter?</strong><br></p>
								<ol>
								  <li>Wir rufen Sie in Kürze an, um die individuellen Gegebenheiten Ihrer Immobilie zu ermitteln.</li>
								  <li>Sie erhalten anschließend eine detaillierte Immobilienbewertung von uns per E-Mail zugeschickt.</li>
								  <li>Sie prüfen die Immobilienbewertung und entscheiden selbst, ob Sie uns mit der Vermarktung beauftragen.</li>
								</ol>
								<p><strong>Unsere Immobilienbewertung ist für Sie selbstverständlich kostenlos.</strong></p>
								<p>Für Rückfragen stehen wir Ihnen sehr gerne zur Verfügung.</p>
								<p>Mit freundlichen Grüßen<br>
								 Ihr Team von Max Mustermakler</p>',
			)
		);
		
		$this->wposa->add_field(
			'prt_settings_email',
			array(
				'id'      => 'email-attachment-customer',
				'type'    => 'file',
				'name'    => __( 'E-Mail: Anhang (Kunde)', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Fügen Sie einen Anhang hinzu, welches an den Kunden versendet wird.', 'prt' ),
			)
		);

		$this->wposa->add_seperator('prt_settings_email');

		$this->wposa->add_field(
			'prt_settings_email',
			array(
				'id'      => 'email-subject-admin',
				'type'    => 'text',
				'name'    => __( 'E-Mail: Betreff (Admin)', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Betreff der E-Mail, welche an den Kunden versendet wird. Die <b>Variablen</b> (unten) sind erlaubt.', 'prt' ),
				'default' => 'Neuer Lead: {{VORNAME}} {{NACHNAME}}'
			)
		);
        
        $this->wposa->add_field(
			'prt_settings_email',
			array(
				'id'      => 'email-content-admin',
				'type'    => 'wysiwyg',
				'name'    => __( 'E-Mail: Inhalt (Admin)', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Inhalt der E-Mail, welche an den Administrator versendet wird. Sie können diese <b>Variablen</b> nutzen:<br><br>
				{{IMMOBILIEN_TYP}}, {{ANREDE}}, {{VORNAME}}, {{NACHNAME}}, {{TELEFON}},<br>{{EMAIL}}, {{ADRESSE}}, {{WOHNFLACHE}}, {{ZIMMER}},<br>{{BAUJAHR}}, {{GRUNDFLACHE}}, {{ETAGE}}, {{ERSCHLOSSEN}},<br>{{BEBAUUNG}}, {{ZUSCHNITT}}', 'prt' ),
                'default' => '<p>Anbei erhalten Sie eine Eigentümer-Anfrage von der Immobilienbewertung Ihrer Homepage.<br>
							  <br>
							  Bitte setzen Sie sich schnellstmöglich mit dem Eigentümer in Verbindung. Der Eigentümer  hat der Kontaktaufnahme ausdrücklich zugestimmt.<br>
							</p>
							<p><strong>Kontaktinformationen</strong><br>
							  Kontakt: {{ANREDE}} {{VORNAME}} {{NACHNAME}}<br>
							  Telefonnummer: {{TELEFON}}<br>
							  E-Mail-Adresse: {{EMAIL}}
							</p>

							<p><strong>Objektinformationen</strong><br>
							  Objektadresse: {{ADRESSE}}<br>
							  Immobilientyp: {{IMMOBILIEN_TYP}}<br>
							  Baujahr: {{BAUJAHR}}<br>
							  Wohnfläche: {{WOHNFLACHE}}m²<br>
							  Grundstücksfläche: {{GRUNDFLACHE}}m²<br>
							  Etagenanzahl: {{ETAGE}}<br>
							  Zimmeranzahl: {{ZIMMER}}<br>
							  Gebäudeart: {{BEBAUUNG}}</p>',
			)
		);

	}
	
	public function section_css() {
        //Section
        $this->wposa->add_section(
			array(
				'id'    => 'prt_settings_css',
				'title' => __( 'Eigenes CSS', 'prt' ),
			)
		);
		
		//Field: Custom CSS
        $this->wposa->add_field(
			'prt_settings_css',
			array(
				'id'      => 'custom_css',
				'type'    => 'textarea',
				'name'    => __( 'Eigenes CSS', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Individuelles CSS Angaben überschreiben das Theme-Stylesheet. Nutzen Sie !important, falls notwendig.', 'prt' ),
				'default' => '#thanks.for.using {}',
			)
		);
	}
	
	public function section_texts() {
		//Section
        $this->wposa->add_section(
			array(
				'id'    => 'prt_settings_texts',
				'title' => __( 'Textanpassung', 'prt' ),
			)
		);

		//!Field: Start
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'start',
				'type'    => 'text',
				'name'    => __( 'Start', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text auf der <b>Startseite</b>', 'prt' ),
				'default' => 'Welche Immobilie möchten Sie <b>verkaufen?</b>',
		));

		//Field: Start Subtext
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'slogan1',
				'type'    => 'text',
				'name'    => __( 'Start: Slogan 1', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Slogan auf der <b>Startseite</b>', 'prt' ),
				'default' => '100% <span class="prt-color-secondary">kostenlos</span> bewerten lassen',
		));

		//Field: Start Subtext
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'slogan2',
				'type'    => 'text',
				'name'    => __( 'Start: Slogan 2', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Slogan auf der <b>Startseite</b>', 'prt' ),
				'default' => 'Welche Immobilie möchten Sie bewerten?',
		));
		
		$this->wposa->add_seperator('prt_settings_texts');

		//!Field: Region
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'region',
				'type'    => 'text',
				'name'    => __( 'Region', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für den Standort der <b>Immobilie</b>', 'prt' ),
				'default' => 'In welcher <b>Region</b> befindet sich die Immobilie?',
		));
		
		$this->wposa->add_seperator('prt_settings_texts');
		
		//!Field: Forderung
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'forderung',
				'type'    => 'text',
				'name'    => __( 'Formular', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text über dem <b>Kontaktformular</b>. <i>Nutzen Sie {{COMPANY}} um Ihren Firmennamen anzuzeigen.', 'prt' ),
				'default' => 'Fordern Sie jetzt Ihr Angebot von {{COMPANY}} an.',
			));		

		$this->wposa->add_seperator('prt_settings_texts');

		//!Field: No_rate 1
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'no_rate_1',
				'type'    => 'text',
				'name'    => __( 'Schlusssatz: Zeile 1', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Dankeschön Satz der Zeile 1 auf der letzten Seite.<b></b>', 'prt' ),
				'default' => 'Danke für Ihr Vertrauen!',
		));
		//!Field: No_rate 1
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'no_rate_2',
				'type'    => 'text',
				'name'    => __( 'Schlusssatz: Zeile 2', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Dankeschön Satz der Zeile 2 auf der letzten Seite.<b></b>', 'prt' ),
				'default' => 'Wir melden uns so bald wie möglich',
		));
		
		$this->wposa->add_seperator('prt_settings_texts');

		//#Field: Wohnung: Step 1
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'wohnung_step1',
				'type'    => 'text',
				'name'    => __( 'Wohnung: Schritt 1', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 1</b> bei dem Immobilientyp <b>Wohnung</b>', 'prt' ),
				'default' => 'Wie ist die <b>Wohnfläche</b> der Wohnung?',
		));
		//Field: Wohnung: Step 2
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'wohnung_step2',
				'type'    => 'text',
				'name'    => __( 'Wohnung: Schritt 2', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 2</b> bei dem Immobilientyp <b>Wohnung</b>', 'prt' ),
				'default' => 'In welcher <b>Etage</b> befindet sich die Wohnung?',
		));
		//Field: Wohnung: Step 3
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'wohnung_step3',
				'type'    => 'text',
				'name'    => __( 'Wohnung: Schritt 3', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 3</b> bei dem Immobilientyp <b>Wohnung</b>', 'prt' ),
				'default' => 'Wie viele <b>Zimmer</b> hat die Wohnung?',
		));
		//Field: Wohnung: Step 3 Sub
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'wohnung_step3_sub',
				'type'    => 'text',
				'name'    => __( 'Wohnung: Schritt 3 (Subtext)', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Sub-Text für <b>Schritt 3</b> bei dem Immobilientyp <b>Wohnung</b>', 'prt' ),
				'default' => '(ohne Küche und Bad)',
		));
		//Field: Wohnung: Step 4
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'wohnung_step4',
				'type'    => 'text',
				'name'    => __( 'Wohnung: Schritt 4', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 4</b> bei dem Immobilientyp <b>Wohnung</b>', 'prt' ),
				'default' => 'Wann wurde das Wohnhaus <b>gebaut?</b>',
		));
		
		$this->wposa->add_seperator('prt_settings_texts');

		//#Field: Haus: Step 1
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'haus_step1',
				'type'    => 'text',
				'name'    => __( 'Haus: Schritt 1', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 1</b> bei dem Immobilientyp <b>Haus</b>', 'prt' ),
				'default' => 'Welche Fläche hat das <b>Grundstück</b> des Hauses?',
		));
		//Field: Haus: Step 2
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'haus_step2',
				'type'    => 'text',
				'name'    => __( 'Haus: Schritt 2', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 2</b> bei dem Immobilientyp <b>Haus</b>', 'prt' ),
				'default' => 'Wie ist die gesamte <b>Wohnfläche</b> des Hauses?',
		));
		//Field: Haus: Step 3
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'haus_step3',
				'type'    => 'text',
				'name'    => __( 'Haus: Schritt 3', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 3</b> bei dem Immobilientyp <b>Haus</b>', 'prt' ),
				'default' => 'Wie viele <b>Etagen</b> hat das Haus?',
		));
		//Field: Haus: Step 4
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'haus_step4',
				'type'    => 'text',
				'name'    => __( 'Haus: Schritt 4', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 4</b> bei dem Immobilientyp <b>Haus</b>', 'prt' ),
				'default' => 'Wie viele <b>Zimmer</b> hat das Haus?',
		));
		//Field: Haus: Step 4 Sub
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'haus_step4_sub',
				'type'    => 'text',
				'name'    => __( 'Haus: Schritt 4 (Subtext)', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Subtext für <b>Schritt 4</b> bei dem Immobilientyp <b>Haus</b>', 'prt' ),
				'default' => '(ohne Küche und Bad)',
		));
		//Field: Haus: Step 5
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'haus_step5',
				'type'    => 'text',
				'name'    => __( 'Haus: Schritt 5', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 5</b> bei dem Immobilientyp <b>Haus</b>', 'prt' ),
				'default' => 'Wann wurde das <b>Haus</b> gebaut?',
		));
		
		$this->wposa->add_seperator('prt_settings_texts');

		//#Field: Grundstuck: Step 1
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'grundstuck_step1',
				'type'    => 'text',
				'name'    => __( 'Grundstück: Schritt 1', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 1</b> bei dem Immobilientyp <b>Grundstück</b>', 'prt' ),
				'default' => 'Welche Fläche hat das <b>Grundstück</b>?',
		));
		//Field: Grundstuck: Step 2
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'grundstuck_step2',
				'type'    => 'text',
				'name'    => __( 'Grundstück: Schritt 2', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 2</b> bei dem Immobilientyp <b>Grundstück</b>', 'prt' ),
				'default' => 'Ist das Grundstück <b>erschlossen</b>?',
		));
		//Field: Grundstuck: Step 3
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'grundstuck_step3',
				'type'    => 'text',
				'name'    => __( 'Grundstück: Schritt 3', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 3</b> bei dem Immobilientyp <b>Grundstück</b>', 'prt' ),
				'default' => 'Wie sind die <b>Bebauungsmöglichkeiten</b>?',
		));
		//Field: Grundstuck: Step 4
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'grundstuck_step4',
				'type'    => 'text',
				'name'    => __( 'Grundstück: Schritt 4', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 4</b> bei dem Immobilientyp <b>Grundstück</b>', 'prt' ),
				'default' => 'Wie ist der <b>Grundstückszuschnitt</b>?',
		));
		
		$this->wposa->add_seperator('prt_settings_texts');

		//#Field: Miete: Step 1
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'miete_step1',
				'type'    => 'text',
				'name'    => __( 'Miete: Schritt 1', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 1</b> bei dem Immobilientyp <b>Miete</b>', 'prt' ),
				'default' => 'Wie ist die <b>Wohnfläche</b> der Wohnung?',
		));
		//Field: Miete: Step 2
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'miete_step2',
				'type'    => 'text',
				'name'    => __( 'Miete: Schritt 2', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 2</b> bei dem Immobilientyp <b>Miete</b>', 'prt' ),
				'default' => 'Um was <b>handelt</b> es sich hierbei?',
		));
		//Field: Miete: Step 3
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'miete_step3',
				'type'    => 'text',
				'name'    => __( 'Miete: Schritt 3', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 3</b> bei dem Immobilientyp <b>Miete</b>', 'prt' ),
				'default' => 'Welcher <b>Typ</b> passt zur Ihrer Immobilie?',
		));
		//Field: Miete: Step 4
        $this->wposa->add_field(
			'prt_settings_texts',
			array(
				'id'      => 'miete_step4',
				'type'    => 'text',
				'name'    => __( 'Miete: Schritt 4', 'prt' ),
				'desc'    => __( '<span class="dashicons dashicons-info" aria-hidden="true"></span> Ändern Sie den Text für <b>Schritt 4</b> bei dem Immobilientyp <b>Miete</b>', 'prt' ),
				'default' => 'Wann wurde das Wohnhaus <b>gebaut?</b>',
		));
		
		$this->wposa->add_seperator('prt_settings_texts');
	}
	
	/**
	 * Get the value of a settings field
	 *
	 * @param string  $option  settings field name
	 * @param string  $section the section name this field belongs to
	 * @param string  $default default text if it's not found
	 * @return string
	 */
	public function get_option( $option, $section, $default = '' ) {
	    return $this->wposa->get_option($option, $section, $default);
	}

}