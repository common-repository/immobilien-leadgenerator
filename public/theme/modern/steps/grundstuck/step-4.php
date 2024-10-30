<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('grundstuck_step4', 'prt_settings_texts', 'Wie ist der <b>Grundstückszuschnitt</b>?'); ?></h2>
    <hr>
    <section class="data">
        <div class="prt-row prt-justy-center options data" data-key="zuschnitt" data-has-input="false">
            <div class="prt-col-4 prt-col-sm-12">
                <div class="option-box" data-value="Eckgrundstück">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/tick.svg') ?>
                    </div>
                    <div class="option-text">Eckgrundstück</div>
                </div>
            </div>
            <div class="prt-col-4 prt-col-sm-12">
                <div class="option-box" data-value="Rechteckiger Zuschnitt">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/tick.svg') ?>
                    </div>
                    <div class="option-text">Rechteckiger</div>
                </div>
            </div>
            <div class="prt-col-4 prt-col-sm-12">
                <div class="option-box" data-value="Sonstiges">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/tick.svg') ?>
                    </div>
                    <div class="option-text">Sonstiges</div>
                </div>
            </div>
        </div>
    </section>
    <div class="prt-button-section prt-center-section prt-mt10">
        <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
        <div class="prt-clearfix"></div>
    </div>
</div>