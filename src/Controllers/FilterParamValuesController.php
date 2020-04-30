<?php

namespace EvolutionCMS\Ddafilters\Controllers;


use EvolutionCMS\Ddafilters\Models\FilterCategory;
use EvolutionCMS\Ddafilters\Models\FilterParams;
use EvolutionCMS\Ddafilters\Models\FilterParamValues;

class FilterParamValuesController
{
    public static function crudParams($request)
    {
        if(!isset($_GET['param_id'])){
            HelperController::response([],'error','Параметр param_id обязателен');
        }
        $request['param_id'] = $_GET['param_id'];
        if(!isset($request['webix_operation'])) $request['webix_operation'] = 'default';
        switch ($request['webix_operation']) {
            case 'insert':
               self::createParamValues($request);
                break;
            case 'update':
                HelperController::answerParamValues(self::updateParamValues($request));
                break;
            case 'delete':
                HelperController::answerParamValues(self::deleteParamValues($request));
                break;
            default:
                HelperController::answerParamValues(true);
                break;
        }
    }

    public static function createParamValues($request)
    {
        $valid = self::validateParamValues($request);
        if ($valid === true) {
            unset($request['id']);

            HelperController::response( ['id' => FilterParamValues::create($request)->getKey()]);
        }else {
            HelperController::answerParamValues($valid);
        }
    }

    public static function updateParamValues($request)
    {
        $valid = self::validateParamValues($request);
        if ($valid === true) {
            FilterParamValues::find($request['id'])->update($request);
        }

        return $valid;
    }

    public static function deleteParamValues($request)
    {
        FilterParamValues::find($request['id'])->delete();
        return true;
    }

    public static function validateParamValues($request)
    {
        if ($request['alias'] == '') {
            return 'Поле alias обязательно к заполнению';
        }
        if ($request['param_id'] == '') {
            return 'Поле param_id обязательно к заполнению';
        }

        if (isset($request['id'])) {
            if (!is_null(FilterParamValues::where('alias', $request['alias'])->where('param_id', $request['param_id'])->where('id', '!=', $request['id'])->first())) {
                return 'Этот alias уже занят';
            }

        } else {
            if (!is_null(FilterParamValues::where('alias', $request['alias'])->where('param_id', $request['param_id'])->first())) {
                return 'Этот alias уже занят';
            }
        }
        return true;
    }
}

