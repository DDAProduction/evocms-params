<?php

namespace EvolutionCMS\Ddafilters\Controllers;


use EvolutionCMS\Ddafilters\Models\FilterCategory;
use EvolutionCMS\Ddafilters\Models\FilterParamsCategory;
use EvolutionCMS\Ddafilters\Models\FilterParamValues;

class FilterCategoryController
{
    public static function crudCategory($request)
    {
        if(!isset($request['webix_operation'])) $request['webix_operation'] = 'default';
        switch ($request['webix_operation']) {
            case 'insert':
                HelperController::response(self::createCategory($_POST));
                break;
            case 'update':
                HelperController::answerCategory(self::updateCategory($_POST));
                break;
            case 'delete':
                HelperController::answerCategory(self::deleteCategory($_POST));
                break;
            default:
                HelperController::answerCategory(true);
                break;
        }
    }

    public static function createCategory($request)
    {
        $valid = self::validateCategory($request);
        if ($valid === true) {
            unset($request['id']);
            HelperController::response( ['id' => FilterCategory::create($request)->getKey()]);
        }else {
            HelperController::answerCategory($valid);
        }

    }

    public static function updateCategory($request)
    {
        $valid = self::validateCategory($request);
        if ($valid === true) {
            FilterCategory::find($request['id'])->update($request);
        }
        return $valid;
    }

    public static function deleteCategory($request)
    {
        FilterParamsCategory::where('category_id', $request['id'])->delete();
        FilterCategory::find($request['id'])->delete();
        return true;
    }

    public static function validateCategory($request)
    {
        if ($request['name'] == '') {
            return 'Поле name обязательно к заполнению';
        }
        if (isset($request['id'])) {
            if (!is_null(FilterCategory::where('name', $request['name'])->where('id', '!=', $request['id'])->first())) {
                return 'Это имя уже занято';
            }
        } else {
            if (!is_null(FilterCategory::where('name', $request['name'])->first())) {
                return 'Это имя уже занято';
            }
        }
        return true;
    }
}

