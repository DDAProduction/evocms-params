<?php

namespace EvolutionCMS\Ddafilters\Controllers;


use EvolutionCMS\Ddafilters\Models\FilterCategory;
use EvolutionCMS\Ddafilters\Models\FilterParams;
use EvolutionCMS\Ddafilters\Models\FilterParamsCategory;
use EvolutionCMS\Ddafilters\Models\FilterParamValues;

class FilterParamsCategoryController
{
    public static function crudParams($request)
    {
        if (!isset($_GET['category_id'])) {
            HelperController::response([], 'error', 'Параметр param_id обязателен');
        }
        $request['category_id'] = $_GET['category_id'];
        if(!isset($request['webix_operation'])) $request['webix_operation'] = 'default';
        switch ($request['webix_operation']) {
            case 'insert':
                self::createParamsCategory($_POST);
                break;
            case 'update':
                HelperController::answerParamsCategory(self::updateParamValues($_POST));
                break;
            case 'delete':
                HelperController::answerParamsCategory(self::deleteParamValues($_POST));
                break;
            default:
                HelperController::answerParamsCategory(true);
                break;
        }
    }

    public static function createParamsCategory($request)
    {
        $valid = self::validateParamValues($request);
        if ($valid === true) {
            unset($request['id']);

            HelperController::response(['id' => FilterParamsCategory::create($request)->getKey()]);
        } else {
            HelperController::answerParamValues($valid);
        }
    }

    public static function updateParamValues($request)
    {
        $valid = self::validateParamValues($request);
        if ($valid === true) {
            FilterParamsCategory::find($request['id'])->update($request);
        }

        return $valid;
    }

    public static function deleteParamValues($request)
    {
        FilterParamsCategory::find($request['id'])->delete();
        return true;
    }

    public static function validateParamValues($request)
    {
        if ($request['param_id'] == '') {
            return 'Поле param_id обязательно к заполнению';
        }


        if (isset($request['id'])) {
            if (!is_null(FilterParamsCategory::where('param_id', $request['param_id'])->where('category_id', $request['category_id'])->where('id', '!=', $request['id'])->first())) {
                return 'Этот param_id уже есть в категории';
            }

        } else {
            if (!is_null(FilterParamsCategory::where('param_id', $request['param_id'])->where('category_id', $request['category_id'])->first())) {
                return 'Этот param_id уже есть в категории';
            }
        }
        return true;
    }

    public static function getAvailableParamsForCategory($request)
    {
        $paramsIsset = FilterParamsCategory::where('category_id', $_GET['category_id'])->pluck('param_id')->toArray();

        $result = FilterParams::whereNotIn('id', $paramsIsset)->get();
        HelperController::response($result);
    }
}

