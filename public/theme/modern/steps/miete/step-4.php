<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('miete_step4', 'prt_settings_texts', 'Wann wurde das Wohnhaus <b>gebaut?</b>'); ?></h2>
    <hr>
    <section>
        <!--<input type="range" name="baujahr-range" min="1" max="10" value="3" step="1"
            data-values='["1930", "1931-1947", "1948-1967", "1968-1980", "1981-1992", "1993-1996", "1997-2001", "2002-2005", "2006-2009", "2010-2012", "2013-2018"]' />-->
        <div class="prt-row prt-justy-center">
            <div class="prt-col-2 prt-col-xs-12 prt-flex prt-justy-center">
            <?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/construction-year.svg') ?>
            </div>
            <div class="prt-col-10 prt-col-xs-12">
                <div class="prt-mrl6em">
                    <div class="prt-range-slider"></div>

                    <div class="prt-label"><label for="baujahr">Alternativ eintippen</label></div>
                    <div class="prt-row prt-under-range">
                        <div class="prt-col prt-col-xs-12 prt-col-nomargin">
                            <div class="range-display prt-input-container">
                                <input required="required" type="number" name="baujahr" min="1900" max="<?php echo date('Y'); ?>" value="1980" step="1" class="range-show-value baujahr r-center">
                                <span class="prt-metersquare">Baujahr</span>
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