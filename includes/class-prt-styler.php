<?php

class Prt_Styler {

    public function __consturct() {}

    public function generateCss($sourceFile, $generatedFile, $toBeReplaced) {
        if(defined('PRT_DEBUG') && PRT_DEBUG && isset($_GET['css_debug'])) var_dump(is_writable($generatedFile));
        file_put_contents($generatedFile, strtr(file_get_contents($sourceFile), $toBeReplaced));
    }

}