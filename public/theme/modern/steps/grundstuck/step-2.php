<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('grundstuck_step2', 'prt_settings_texts', 'Ist das Grundstück <b>erschlossen</b>?'); ?></h2>
    <hr>
    <section class="data">
        <div class="prt-row prt-justy-center options data" data-key="erschlossen" data-has-input="false">
            <div class="prt-col-4 prt-col-sm-12">
                <div class="option-box" data-value="Erschlossen">
                    <div class="option-img">
                        <?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/erschlossen.svg') ?>
                    </div>
                    <div class="option-text">Erschlossen</div>
                </div>
            </div>
            <div class="prt-col-4 prt-col-sm-12">
                <div class="option-box" data-value="Teilerschlossen">
                    <div class="option-img">
                        <?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/teilerschlossen.svg') ?>
                    </div>
                    <div class="option-text">Teilerschlossen</div>
                </div>
            </div>
            <div class="prt-col-4 prt-col-sm-12">
                <div class="option-box" data-value="Unerschlossen">
                    <div class="option-img">
                        <?php echo @file_get_contents(PRT_DIR_HOME . 'public/img/modern/unerschlossen.svg') ?>
                    </div>
                    <div class="option-text">Nicht erschlossen</div>
                </div>
            </div>
        </div>
    </section>
    <div class="prt-button-section prt-center-section prt-mt10">
        <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
        <div class="prt-clearfix"></div>
    </div>
</div>