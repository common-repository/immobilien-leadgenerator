<?php

require_once PRT_DIR_HOME . 'vendor/autoload.php';
use Respect\Validation\Validator as v;

class HausSteps implements Steps {

    private $settings;
    private $response;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    public function getStepByFile($file) {
        ob_start();
        require $file;
        return ob_get_clean();
    }

    public function prepareResponse() {
        if(empty($this->response)) {
            $dir = plugin_dir_path(__FILE__);
            $files = glob($dir . '/step-*.php');

            foreach ($files as $file) {
                $this->response[] = $this->getStepByFile($file);
            }
        }
    }

    public function getResponse() {
        $this->prepareResponse();
        return $this->response;
    }

    public function validateData() {
        if (!v::intVal()->notEmpty()->validate($_POST['grundflache'])) throw new Exception("Grundfläche wurde nicht gesetzt");
		if (!v::intVal()->notEmpty()->validate($_POST['wohnflache'])) throw new Exception("Wohnfläche wurde nicht gesetzt");
		if (!v::intVal()->notEmpty()->validate($_POST['etage'])) throw new Exception("Etage wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['zimmer'])) throw new Exception("Zimmer wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['baujahr'])) throw new Exception("Baujahr wurde nicht gesetzt");
        if (!v::stringType()->notEmpty()->validate($_POST['address'])) throw new Exception("Adresse wurde nicht gesetzt");
        if (!v::stringType()->notEmpty()->validate($_POST['salutation'])) throw new Exception("Anrede wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['firstname'])) throw new Exception("Vorname wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['lastname'])) throw new Exception("Nachname wurde nicht gesetzt");
		if (!v::phone()->validate($_POST['phone'])) throw new Exception("Telefonnummer ist ungültig");
        if (!v::email()->validate($_POST['email'])) throw new Exception("Email ist ungültig");
    }

    public function collectData() {
        return [
            "type" => "haus",
            "realEstateTypeId" => 1,
            "footprint" => intval($_POST['grundflache']),
            "living_space" => intval($_POST['wohnflache']),
            "floor" => sanitize_text_field($_POST['etage']),
            "rooms" => sanitize_text_field($_POST['zimmer']),
            "construction_year" => sanitize_text_field($_POST['baujahr']),
            "opened_up" => null,
            "building" => null,
            "cut" => null,
            "address" => sanitize_text_field($_POST['address']),
            "salutation" => sanitize_text_field($_POST['salutation']),
            "firstname" => sanitize_text_field($_POST['firstname']),
            "lastname" => sanitize_text_field($_POST['lastname']),
            "phone" => sanitize_text_field($_POST['phone']),
            "email" => sanitize_text_field($_POST['email'])
        ];
    }

}