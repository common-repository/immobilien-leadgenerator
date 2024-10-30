<div class="step">
    <h2 class="prt-center"><?php echo $this->settings->get_option('wohnung_step2', 'prt_settings_texts', 'In welcher <b>Etage</b> befindet sich die Wohnung?'); ?></h2>
    <hr>
    <section class="data">
        <!--<div class="prt-row prt-justy-center">
            <div class="prt-col-2 prt-col-prt-flex prt-justy-center">
                <img class="prt-icon" src="<?php echo PRT_DIR_HOME_URL . 'public/img/modern/floor-1.svg' ?>" alt="">
            </div>
            <div class="prt-col-10 prt-col-xs-12">
                <select name="etage" required="required">
                    <option value="Souterrain/Untergeschoss">Souterrain/Untergeschoss</option>
                    <option value="Erdgeschoss">Erdgeschoss</option>
                    <option value="1. Stockwerk">1. Stockwerk</option>
                    <option value="2. Stockwerk">2. Stockwerk</option>
                    <option value="3. Stockwerk">3. Stockwerk</option>
                    <option value="4. Stockwerk">4. Stockwerk</option>
                    <option value="5. Stockwerk">5. Stockwerk</option>
                    <option value="6. Stockwerk oder höher">6. Stockwerk oder höher</option>
                </select>
            </div>
        </div>-->
        <div class="prt-row prt-justy-center options data" data-key="etage" data-has-input="false">
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="Untergeschoss">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-minus-1.svg') ?>
                    </div>
                    <div class="option-text">Untergeschoss</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="Erdgeschoss">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-0.svg') ?>
                    </div>
                    <div class="option-text">Erdgeschoss</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="1. Stockwerk">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-1.svg') ?>
                    </div>
                    <div class="option-text">1. Stockwerk</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="2. Stockwerk">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-2.svg') ?>
                    </div>
                    <div class="option-text">2. Stockwerk</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="3. Stockwerk">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-3.svg') ?>
                    </div>
                    <div class="option-text">3. Stockwerk</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="4. Stockwerk">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-4.svg') ?>
                    </div>
                    <div class="option-text">4. Stockwerk</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="5. Stockwerk">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-5.svg') ?>
                    </div>
                    <div class="option-text">5. Stockwerk</div>
                </div>
            </div>
            <div class="prt-col-3 prt-col-sm-6">
                <div class="option-box" data-value="6. Stockwerk oder höher">
                    <div class="option-img">
                        <?php echo file_get_contents(PRT_DIR_HOME . 'public/img/modern/floor-6.svg') ?>
                    </div>
                    <div class="option-text">6. Stw. oder höher</div>
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