<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('grundstuck_step3', 'prt_settings_texts', 'Wie sind die <b>Bebauungsmöglichkeiten</b>?'); ?></h2>
    <hr>
    <section class="data">
        <div class="prt-row prt-justy-center options data" data-key="bebauung" data-has-input="false">
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="Kurzfristig bebaubar">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/tick.svg') ?>
                    </div>
                    <div class="option-text">Kurzfristig</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="Eingeschränkt bebaubar">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/tick.svg') ?>
                    </div>
                    <div class="option-text">Eingeschränkt</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="Nicht bebaubar">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/tick.svg') ?>
                    </div>
                    <div class="option-text">Nicht bebaubar</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="Weiß nicht">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/tick.svg') ?>
                    </div>
                    <div class="option-text">Weiß nicht</div>
                </div>
            </div>
        </div>
    </section>
    <div class="prt-button-section prt-center-section prt-mt10">
        <button type="button" class="prt-button hvr-bounce-to-left prt-left prev">Zurück</button>
        <div class="prt-clearfix"></div>
    </div>
</div>