<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('wohnung_step1', 'prt_settings_texts', 'Wie ist die <b>Wohnfläche</b> der Wohnung?'); ?></h2>
    <hr>
    <section class="data">
        <div class="prt-row prt-justy-center">
            <div class="prt-col-2 prt-col-xs-12 prt-flex prt-justy-center">
            <?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/wohnflache.svg') ?>
            </div>
            <div class="prt-col-10 prt-col-xs-12">
                <div class="prt-mrl6em">
                    <div class="prt-range-slider" data-showUnderAbove="true" data-showM2="true"></div>

                    <div class="prt-label"><label for="wohnflache">Alternativ eintippen</label></div>
                    <div class="prt-row prt-under-range">
                        <div class="prt-col prt-col-xs-12 prt-col-nomargin">
                            <div class="range-display prt-input-container">
                                <input required="required" type="number" name="wohnflache" min="20" max="250" step="10" value="100" class="range-show-value">
                                <span class="prt-metersquare">m²</span>
                            </div>
                        </div>
                        <div class="prt-col prt-col-xs-12">
                            <button type="button" class="prt-button hvr-bounce-to-right prt-button-fluid next">Weiter</button>
                        </div>
                    </div>
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