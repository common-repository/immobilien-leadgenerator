<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('haus_step3', 'prt_settings_texts', 'Wie viele <b>Etagen</b> hat das Haus?'); ?></h2>
    <hr>
    <section>
        <div class="prt-row prt-justy-center options data" data-key="etage" data-has-input="false">
            <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                <div class="option-box" data-value="1">
                    <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-1.svg') ?></div>
                    <div class="option-text">1 Etage</div>
                </div>
            </div>
            <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                <div class="option-box" data-value="2">
                    <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-2.svg') ?></div>
                    <div class="option-text">2 Etagen</div>
                </div>
            </div>
            <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                <div class="option-box" data-value="3">
                    <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-3.svg') ?></div>
                    <div class="option-text">3 Etagen</div>
                </div>
            </div>
        </div>
    </section>
    <div class="prt-button-section prt-center-section prt-mt10">
        <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
        <div class="prt-clearfix"></div>
    </div>
</div>