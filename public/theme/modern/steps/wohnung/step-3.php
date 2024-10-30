<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('wohnung_step3', 'prt_settings_texts', 'Wie viele <b>Zimmer</b> hat die Wohnung?'); ?></h2>
    <h5 class="prt-center sub-title"><?php echo $this->settings->get_option('wohnung_step3_sub', 'prt_settings_texts', '(ohne Küche und Bad)'); ?></h5>
    <hr>
    <section class="data">
        <div class="prt-row prt-justy-center">
            <div class="prt-col-2 prt-col-xs-12 prt-flex prt-justy-center">
            <?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/room.svg') ?>
            </div>
            <div class="prt-col-10 prt-col-xs-12">
                <div class="prt-mrl6em">
                    <div class="prt-range-slider" data-showUnderAbove="true"></div>

                    <div class="prt-label"><label for="zimmer">Alternativ eintippen</label></div>
                    <div class="prt-row prt-under-range">
                        <div class="prt-col prt-col-xs-12 prt-col-nomargin">
                            <div class="range-display prt-input-container">
                                <input required="required" type="number" name="zimmer" value="3" min="1" max="6" step="0.5" data-pips="6" class="range-show-value">
                                <span class="prt-metersquare">Zimmer</span>
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