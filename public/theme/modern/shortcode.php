<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.makler-anfragen.immo
 * @since      1.0.0
 *
 * @package    Prt
 * @subpackage prt/public/partials
 */

$active_steps = $this->settings->get_option('active_steps', 'prt_settings_general', array('wohnung', 'haus', 'grundstuck', 'miete'));
$tracking_via = $this->settings->get_option('ga_adwords_analytics', 'prt_settings_general', array('google_analytics'));

?>

<!-- Google Analytics -->
<script>
<?php if(in_array('google_analytics', $tracking_via)): ?>

//ga

<?php endif; if(in_array('google_adwords', $tracking_via)): ?>
// PRT - Google Adwords
var prtGAdwordsOn = true;
var prtGAdwords = {
    conversionId: <?php echo esc_html( $this->settings->get_option('ga_adwords_conv_id', 'prt_settings_general', 0) ); ?>,
    conversionLabel: "<?php echo esc_html( $this->settings->get_option('ga_adwords_conv_label', 'prt_settings_general', 0) ); ?>"
};
<?php endif; ?>
</script>
<!-- End Google Analytics -->

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_html( $this->settings->get_option('google-api-key', 'prt_settings_general') ); ?>&libraries=places&sensor=false"></script>

<div id="prt-root" class="theme-modern full-width prt-container">
    
    <div class="prt-overlay"></div>

    <div class="dialog prt-hidden">
        <div class="dialog-header">
            
        </div>
        <div class="dialog-body">
            <div class="dialog-emoji"></div> 
            <div class="text"></div>
        </div>
    </div>

    <div class="loader prt-hidden">
        <img src="<?php echo PRT_DIR_HOME_URL. 'public/img/loader.svg'; ?>" alt="loader" />
    </div>

    <div class="privacy prt-hidden">
        <div class="text"><?php echo $this->settings->get_option('privacy-policy', 'prt_settings_general', ''); ?></div>
    </div>

    <div class="progress-section prt-always-hidden">
        <div class="progress-bar">
            <div style="width:0%" class="progress"></div>
        </div>
    </div>

    <br/>

    <div class="steps owl-carousel">
        <div class="root step">
            <h2 class="prt-center prt-home-title"><?php echo $this->settings->get_option('start', 'prt_settings_texts', 'Welche Immobilie möchten Sie <b>verkaufen?</b>') ?></h2>
            <hr />
            <section>
                <div class="prt-center">
                    <h3 class="prt-home-slogan1"><?php echo $this->settings->get_option('slogan1', 'prt_settings_texts', '100% <span class="prt-color-secondary">kostenlos</span> bewerten lassen') ?></h3>
                    <h2 class="prt-text-light prt-home-slogan2"><?php echo $this->settings->get_option('slogan2', 'prt_settings_texts', 'Welche Immobilie möchten Sie bewerten?') ?></h2>
                </div>
                <div class="prt-row options data prt-justy-center" data-key="type" data-has-input="false" data-isload="true">
                    <?php if(in_array('wohnung', $active_steps)) : ?>
                    <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                        <div class="option-box" data-value="wohnung">
                            <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/wohnung.svg') ?></div>
                            <div class="option-text">Wohnung</div>
                        </div>
                    </div>
                    <?php endif; if(in_array('haus', $active_steps)) : ?>
                    <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                        <div class="option-box" data-value="haus">
                            <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/haus.svg') ?></div>
                            <div class="option-text">Haus</div>
                        </div>
                    </div>
                    <?php endif; if(in_array('grundstuck', $active_steps)) : ?>
                    <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                        <div class="option-box" data-value="grundstuck">
                            <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/grundstuck.svg') ?></div>
                            <div class="option-text">Grundstück</div>
                        </div>
                    </div>
                    <?php endif; if(in_array('miete', $active_steps)) : ?>
                    <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                        <div class="option-box" data-value="miete">
                            <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/miete.svg') ?></div>
                            <div class="option-text">Miete</div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="prt-row">
                    <div class="prt-col-12">
                        <div class="benefits inline">
                            <?php echo $this->settings->get_option('advantages', 'prt_settings_general', '') ?>
                        </div>
                    </div>
                </div>
            </section>
            <hr>
            <!--<div class="prt-center-section">
                <button type="button" class="prt-button hvr-bounce-to-right prt-center load next">Weiter</button>
            </div>-->
        </div>
        <div class="step">
            <h2 class="prt-center"><?php echo $this->settings->get_option('region', 'prt_settings_texts', 'In welcher <b>Region</b> befindet sich die Immobilie?') ?></h2>
            <hr>
            <section class="prt-justy-centy prt-pad10 prt-maps-section">
                <input required="required" value="" type="text" name="address" autocomplete="off" class="prtAdressInputField" placeholder="Straße Nummer, PLZ, Stadt" />
                <div class="google-maps-frame" id="prt-google-map"></div>
            </section>
            <hr>
            <div class="prt-button-section prt-center-section prt-mt10">
                <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
                <button type="button" class="prt-button hvr-bounce-to-right prt-right validateAddress startEmulateTimeout next">Weiter</button>
                <div class="prt-clearfix"></div>
            </div>
        </div>
        <div class="step prt-step-form">
            <section>
                <div class="prt-promt-value-found">
                    <img id="prt_promt_success_icon" class="prt-icon-inline prt-hide-xs prt-hidden-impo" src="<?php echo PRT_DIR_HOME_URL. 'public/img/icon-tick.svg'; ?>" alt="icon">
                    <span>Übersendung der relevanten Daten Ihrer Immobilie...</span>
                    <div id="prt_promt_load_icon" class="prt-lds-ellipsis prt-hidden-impo"><div></div><div></div><div></div><div></div></div>
                </div>
                <div class="prt-row">
                    <div class="prt-col prt-col-xs-12">
                        <div class="prt-form-field">
                            <input type="radio" name="salutation" value="Frau" checked> Frau
                            <input type="radio" name="salutation" value="Herr"> Herr
                        </div>
                        <div class="prt-form-field">
                            <div class="prt-row">
                                <div class="prt-col-6"><div class="prt-mr4"><input type="text" name="firstname" class="prt-form-input" placeholder="Vorname" /></div></div>
                                <div class="prt-col-6"><div class="prt-ml4"><input type="text" name="lastname"  class="prt-form-input" placeholder="Nachname *" required="required" /></div></div>
                            </div>
                        </div>
                        <div class="prt-form-field">
                            <input type="text" name="phone"     class="prt-form-input make-a-tippy" placeholder="Telefonnummer *" required="required" data-tippy-placement="right" data-tippy-arrow="true" title="Ihre Telefonnummer benötigen wir im Falle von Rückfragen bei der Angebotserstellung. Eine Nutzung Ihrer Daten für andere Zwecke wird ausgeschlossen" />
                        </div>
                        <div class="prt-form-field">
                            <input type="email" name="email"     class="prt-form-input" placeholder="Ihre E-Mail-Adresse *" required="required" />
                        </div>
                        <div class="prt-form-field prt-required-message">
                            <p>* diese Felder sind Pflichtfelder.</p>
                        </div>
                        <div class="prt-form-field prt-formular-button">
                            <button type="button" class="prt-button hvr-bounce-to-right prt-button-fluid finish">Weiter</button>
                        </div>
                        
                    </div>
                    <div class="prt-col prt-col-xs-12">
                        <div class="benefits prt-form-benefits">
                            <?php echo $this->settings->get_option('advantages', 'prt_settings_general', '') ?>
                        </div>
                    </div>
                </div>
                <div class="prt-row">
                    <div class="prt-col-12">
                        <div class="privacy-read-text">
                            <input type="checkbox" class="privacy-read" name="privacy-read" required="required" />
                            Ich habe die Hinweise zum <a href="#" class="openPrivacy"><u>Datenschutz</u></a> gelesen und akzeptiere diese, des weiteren willige ich ein, dass meine Angaben zur Kontaktaufnahme, zur Berechnung des Immobilienwertes und Zuordnung für eventuelle Rückfragen dauerhaft gespeichert werden
                        </div>
                    </div>
                </div>
            </section>
            <hr>
            <div class="prt-button-section prt-center-section prt-mt10">
                <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
                <div class="prt-clearfix"></div>
            </div>
        </div>
        <div class="step result-step">
            <section>
                <div class="result" style="display:none;">
                    <h2 class="prt-center prt-result-title prt-onnonmiete">Ermittelter Wert <span class="prt-resultAbsolute"></span></h2>
                    <h2 class="prt-center prt-result-title prt-onmiete">Ermittelte Miete <span class="prt-resultAbsolute"></span></h2>
                    <div class="results">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="prt-onnonmiete">Ermittelter durchschnittlicher Marktwert</td>
                                    <td class="prt-onmiete">Ermittelte durchschnittliche Miete</td>
                                    <td><span class="prt-resultAbsolute"></span></td>
                                </tr>
                                <tr>
                                    <td class="prt-onnonmiete">Durchschnittlicher Wert pro m² Wohnfläche</td>
                                    <td class="prt-onmiete">Durchschnittliche Miete pro m² Wohnfläche</td>
                                    <td><span class="prt-resultPerSqm"></span></td>
                                </tr>
                                <tr class="no-show-span">
                                    <td class="prt-onnonmiete">Resultierende Wertspanne</td>
                                    <td class="prt-onmiete">Resultierende Mietspanne</td>
                                    <td><span class="prt-lowAbsolute"></span> - <span class="prt-highAbsolute"></span></td>
                                </tr>
                                <tr class="no-show-span">
                                    <td class="prt-onnonmiete">Ermittelte Wertspanne pro m²</td>
                                    <td class="prt-onmiete">Ermittelte Mietspanne pro m²</td>
                                    <td><span class="prt-lowPerSqm"></span> - <span class="prt-highPerSqm"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="thanks-text">
                        <?php echo $this->settings->get_option('thanks', 'prt_settings_texts', 'Wir melden uns bei Ihnen, so schnell wie möglich! :)') ?>
                    </div>
                </div>
                <div class="no_rate" style="display:none;">
                    <h2 class="prt-text-light"><?php echo $this->settings->get_option('no_rate_1', 'prt_settings_texts', 'Danke für Ihr Vertrauen!') ?></h2>
                    <h3><?php echo $this->settings->get_option('no_rate_2', 'prt_settings_texts', 'Wir melden uns so bald wie möglich') ?></h3>
                </div>
                <div class="prt_not_found" style="display:none;">
                    <h2 class="prt-text-light"><?php echo $this->settings->get_option('not_found_1', 'prt_settings_texts', 'Leider konnten wir anhand Ihrer Daten keine Bewertung generieren.') ?></h2>
                    <h3><?php echo $this->settings->get_option('not_found_2', 'prt_settings_texts', 'Wir melden uns so bald wie möglich') ?></h3>
                </div>
            </section>
            <div class="prt-button-section prt-center-section prt-mt10">
                <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
                <div class="prt-clearfix"></div>
            </div>
        </div>
    </div>

    <div class="by-ref">&copy; <a href="http://www.makler-anfragen.immo" target="_blank" >makler-anfragen.immo</a></div>
</div>

<!-- PRT JavaScript Root Call (if already available) -->
<script type="text/javascript">
var prt_root_already_called = false;
if(typeof prt_js_root === "function") {
    prt_js_root();
}
</script>