<?php

namespace EvolutionCMS\Ddafilters\Controllers;


use EvolutionCMS\Ddafilters\Models\FilterCategory;
use EvolutionCMS\Ddafilters\Models\FilterParams;
use EvolutionCMS\Ddafilters\Models\FilterParamsCategory;
use EvolutionCMS\Ddafilters\Models\FilterParamsUnits;
use EvolutionCMS\Ddafilters\Models\FilterParamValues;
use EvolutionCMS\Models\SiteTmplvar;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use EvolutionCMS\Models\SystemSetting;

class FilterParamsUnitsController
{
    public static function crudParams($request)
    {
        if (!isset($request['webix_operation'])) $request['webix_operation'] = 'default';
        switch ($request['webix_operation']) {
            case 'insert':
                self::createParamUnit($_POST);
                break;
            case 'update':
                HelperController::answerParamsUnits(self::updateParamUnit($_POST));
                break;
            case 'delete':
                HelperController::answerParamsUnits(self::deleteParamUnit($_POST));
                break;
            default:
                HelperController::answerParamsUnits(true);
                break;
        }
    }

    public static function createParamUnit($request)
    {

        $id = FilterParamsUnits::create($request)->getKey();
        $request['id'] = $id;
        HelperController::response(['id' => $id]);


    }

    public static function updateParamUnit($request)
    {

        FilterParamsUnits::find($request['id'])->update($request);
        return true;
    }

    public static function deleteParamUnit($request)
    {
        $filterParam = FilterParamsUnits::find($request['id']);
        $filterParam->delete();
        return true;

    }
	
	public static function getAllUnits()
	{
		$params = FilterParamsUnits::select('id', 'desc as value')->get()->toArray();
		HelperController::response($params);
		
	}
	
	public static function getAllUnitsClear()
	{
		header('Content-Type: application/json');
		echo FilterParamsUnits::select('id', 'desc as value')->get()->toJson();
		exit();
		
	}
	

    public static function validateParam($request)
    {
        if ($request['prefix'] == '') {
            return 'Поле prefix обязательно к заполнению';
        }
        if ($request['desc'] == '') {
            return 'Поле desc обязательно к заполнению';
        }
        if ($request['typeinput'] == '') {
            return 'Поле typeinput обязательно к заполнению';
        }
        if (isset($request['id'])) {
            if (!is_null(FilterParams::where('desc', $request['desc'])->where('id', '!=', $request['id'])->first())) {
                return 'Это имя уже занято';
            }
            if (!is_null(FilterParams::where('alias', $request['alias'])->where('id', '!=', $request['id'])->first())) {
                return 'Это имя/alias уже занято';
            }
            if (!is_null(FilterParams::where('prefix', $request['prefix'])->where('id', '!=', $request['id'])->first())) {
                return 'Этот prefix уже использует другой параметр';
            }
        } else {
            if (!is_null(FilterParams::where('desc', $request['desc'])->first())) {
                return 'Это имя уже занято';
            }
            if (!is_null(FilterParams::where('prefix', $request['prefix'])->first())) {
                return 'Этот prefix уже использует другой параметр';
            }
            if (!is_null(FilterParams::where('alias', $request['alias'])->first())) {
                return 'Это имя/alias уже занято';
            }
        }
        return true;
    }

}

