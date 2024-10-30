<?php

require_once PRT_DIR_HOME . 'vendor/autoload.php';
use Respect\Validation\Validator as v;

class GrundstuckSteps implements Steps {

    private $settings;
    private $response;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    private function getStepByFile($file) {
        ob_start();
        require $file;
        return ob_get_clean();
    }

    private function prepareResponse() {
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
		if (!v::stringType()->notEmpty()->validate($_POST['erschlossen'])) throw new Exception("Erschlossen-Wert wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['bebauung'])) throw new Exception("Bebaung wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['zuschnitt'])) throw new Exception("Zuschnitt wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['address'])) throw new Exception("Adresse wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['salutation'])) throw new Exception("Anrede wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['firstname'])) throw new Exception("Vorname wurde nicht gesetzt");
		if (!v::stringType()->notEmpty()->validate($_POST['lastname'])) throw new Exception("Nachname wurde nicht gesetzt");
		if (!v::phone()->validate($_POST['phone'])) throw new Exception("Telefonnummer ist ungültig");
        if (!v::email()->validate($_POST['email'])) throw new Exception("Email ist ungültig");
        
        return true;
    }

    public function collectData() {
        return [
            "type" => "grundstuck",
            "realEstateTypeId" => 1,
            "footprint" => intval($_POST['grundflache']),
            "living_space" => null,
            "floor" => null,
            "rooms" => null,
            "construction_year" => null,
            "opened_up" => sanitize_text_field($_POST['erschlossen']),
            "building" => sanitize_text_field($_POST['bebauung']),
            "cut" => sanitize_text_field($_POST['zuschnitt']),
            "address" => sanitize_text_field($_POST['address']),
            "salutation" => sanitize_text_field($_POST['salutation']),
            "firstname" => sanitize_text_field($_POST['firstname']),
            "lastname" => sanitize_text_field($_POST['lastname']),
            "phone" => sanitize_text_field($_POST['phone']),
            "email" => sanitize_text_field($_POST['email'])
        ];
    }

}