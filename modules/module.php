<?php

use EvolutionCMS\Ddafilters\Controllers\HelperController;
use EvolutionCMS\Ddafilters\Controllers\FilterCategoryController;
use EvolutionCMS\Ddafilters\Controllers\FilterParamsController;
use EvolutionCMS\Ddafilters\Controllers\FilterParamValuesController;
use \EvolutionCMS\Ddafilters\Controllers\FilterParamsCategoryController;
use \EvolutionCMS\Ddafilters\Controllers\FilterSettingsController;

$filesystem = new Illuminate\Filesystem\Filesystem;
$dir = EVO_CORE_PATH . 'custom/packages/ddafilters/modules/views';
if (!is_dir($dir)) {
    $dir = EVO_CORE_PATH . 'vendor/ddaproduction/evocms-params/modules/views/';
}
$viewFinder = new Illuminate\View\FileViewFinder($filesystem, [$dir]);
\Illuminate\Support\Facades\View::setFinder($viewFinder);

$action = '';
if (isset($_GET['action']))
    $action = $_GET['action'];


switch ($action) {
    case 'crudCategories':
        FilterCategoryController::crudCategory($_POST);
        break;
    case 'crudParams':
        FilterParamsController::crudParams($_POST);
        break;
    case 'crudParamValues':
        FilterParamValuesController::crudParams($_POST);
        break;
    case 'crudParamsCategory':
        FilterParamsCategoryController::crudParams($_POST);
        break;
    case 'getAvailableParamsForCategory':
        FilterParamsCategoryController::getAvailableParamsForCategory($_POST);
        break;
    case 'getSettings':
        FilterSettingsController::getSettings();
        break;
    case 'setSettings':
        FilterSettingsController::setSettings($_POST);
        break;
    default:
        echo \Illuminate\Support\Facades\View::make('categorylist');
        break;
}
