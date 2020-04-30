<?php
Event::listen('evolution.OnPageNotFound', function ($params) {
    $modx = EvolutionCMS();

    switch ($_GET['q']) {
        case 'ssjetTest':
            include_once(EVO_CORE_PATH . "custom/packages/ddafilters/modules/module.php");
            exit();
            break;
    }
});