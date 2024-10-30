<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('miete_step2', 'prt_settings_texts', 'Um was <b>handelt</b> es sich hierbei?'); ?></h2>
    <hr>
    <section class="data">
        <div class="prt-row prt-justy-center options data" data-key="realEstateTypeMiete" data-has-input="false">
            <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                <div class="option-box" data-value="0">
                    <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/wohnung.svg') ?></div>
                    <div class="option-text">Wohnung</div>
                </div>
            </div>
            <div class="prt-col prt-col-xs-12 prt-col-sm-6">
                <div class="option-box" data-value="1">
                    <div class="option-img"><?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/haus.svg') ?></div>
                    <div class="option-text">Haus</div>
                </div>
            </div>
        </div>
    </section>
    <div class="prt-button-section prt-center-section prt-mt10">
        <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
        <div class="prt-clearfix"></div>
    </div>
</div>