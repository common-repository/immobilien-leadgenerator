<?php /* uncommented lines doesn't work with IS24 API - tested */ ?>
<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('miete_step3', 'prt_settings_texts', 'Welcher <b>Typ</b> passt zur Ihrer Immobilie?'); ?></h2>
    <hr>
    <section>
        
        <div class="prt-row prt-justy-center">
            <div class="prt-col-2 prt-col-xs-12 prt-flex prt-justy-center">
            <?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/immo-type.svg') ?>
            </div>
            <div class="prt-col-10 prt-col-xs-12">
                <select name="kategorie" required="required">
                    <option class="opt-cat-haus opt-cat-wohnung" value="0-Keine Informationen">Keine Informationen</option>
                    <option class="opt-cat-wohnung" value="3-Dachgeschoss">Dachgeschoss</option>
                    <option class="opt-cat-wohnung" value="7-Maisonette">Maisonette</option>
                    <option class="opt-cat-haus" value="15-Bungalow">Bungalow</option>
                    <option class="opt-cat-haus" value="17-Doppelhaushälfte">Doppelhaushälfte</option>
                    <option class="opt-cat-haus" value="18-Einfamilienhaus">Einfamilienhaus</option>
                    <option class="opt-cat-haus" value="20-Landhaus">Landhaus</option>
                    <!--<option class="opt-cat-haus" value="24-Villa">Villa</option>-->
                    <option class="opt-cat-haus" value="25-Reihenhaus">Reihenhaus</option>
                    <!--<option class="opt-cat-haus" value="27-Holzhaus">Holzhaus</option>-->
                    <option class="opt-cat-wohnung" value="38-Eigentumswohnung">Eigentumswohnung</option>
                    <option class="opt-cat-wohnung" value="40-Reihenwohnung">Reihenwohnung</option>
                    <option class="opt-cat-wohnung" value="117-Erdgeschoss">Erdgeschoss</option>
                    <option class="opt-cat-wohnung" value="118-Wohnung">Wohnung</option>
                    <option class="opt-cat-haus opt-cat-wohnung" value="119-spezielle Immobilien">spezielle Immobilien</option>
                    <option class="opt-cat-haus" value="122-Einfamilienhaus (freistehend)">Einfamilienhaus (freistehend)</option>
                    <option class="opt-cat-haus" value="123-Mitte Reihenhaus"> Reihenmittelhaus</option>
                    <option class="opt-cat-haus" value="124-Ende Reihenhaus">Reihenendhaus</option>
                    <!--<option class="opt-cat-wohnung" value="126-Wohnung (andere)">Wohnung (andere)</option>-->
                    <option class="opt-cat-haus opt-cat-wohnung" value="127-Hochparterre">Hochparterre</option>
                    <option class="opt-cat-haus opt-cat-wohnung" value="128-halber Keller">Souterrain</option>
                </select>
            </div>
        </div>

    </section>
    <div class="prt-button-section prt-center-section prt-mt10">
        <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
        <button type="button" class="prt-button hvr-bounce-to-right prt-right next">Weiter</button>
        <div class="prt-clearfix"></div>
    </div>
</div>