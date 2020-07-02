<?php

namespace EvolutionCMS\Ddafilters\Controllers;


use EvolutionCMS\Ddafilters\Models\FilterCategory;
use EvolutionCMS\Ddafilters\Models\FilterParams;
use EvolutionCMS\Ddafilters\Models\FilterParamsCategory;
use EvolutionCMS\Ddafilters\Models\FilterParamValues;
use EvolutionCMS\Models\SiteTmplvar;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use EvolutionCMS\Models\SystemSetting;

class FilterParamsController
{
    public static function crudParams($request)
    {
        if (!isset($request['webix_operation'])) $request['webix_operation'] = 'default';
        switch ($request['webix_operation']) {
            case 'insert':
                HelperController::response(self::createParam($_POST));
                break;
            case 'update':
                HelperController::answerParams(self::updateParam($_POST));
                break;
            case 'delete':
                HelperController::answerParams(self::deleteParam($_POST));
                break;
            default:
                HelperController::answerParams(true);
                break;
        }
    }

    public static function createParam($request)
    {
        $valid = self::validateParam($request);
        if ($valid === true) {
            unset($request['id']);
            $id = FilterParams::create($request)->getKey();

            $request['id'] = $id;
            self::updateSelector($request);
            HelperController::response(['id' => $id]);
        } else {
            HelperController::answerParams($valid);
        }


    }

    public static function updateParam($request)
    {
        $valid = self::validateParam($request);
        if ($valid === true) {
            self::updateSelector($request);
            unset($request['tv_id']);
            FilterParams::find($request['id'])->update($request);

        }

        return $valid;
    }

    public static function deleteParam($request)
    {
        $check = FilterParamsCategory::where('param_id', $request['id'])->first();
        if (!is_null($check)) {
            return 'Удалите параметр из привязки к категории';
        } else {
            FilterParamValues::where('param_id', $request['id'])->delete();
            $filterParam = FilterParams::find($request['id']);
            SiteTmplvarTemplate::where('tmplvarid', $filterParam->tv_id)->delete();
            SiteTmplvar::find($filterParam->tv_id)->delete();
            $filterParam->delete();
            return true;
        }

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

    static function updateSelector($request)
    {
        $filterParam = FilterParams::find($request['id']);
        $oldName = $filterParam->alias;

        if ($oldName != $request['alias']) {
            if (file_exists(MODX_BASE_PATH . 'assets/tvs/ddaparams/lib/' . $oldName . '.controller.class.php')) {
                unlink(MODX_BASE_PATH . 'assets/tvs/ddaparams/lib/' . $oldName . '.controller.class.php');
            }
        }
        $tvsCategory = (int)SystemSetting::find('tvs_category')->setting_value;
        if ($filterParam->tv_id > 0) {
            $tv = SiteTmplvar::find($filterParam->tv_id);
            if ($request['typeinput'] == 'select') {
                $tv->type = 'custom_tv:ddaparams';
            }else {
                $tv->type = 'text';
            }
            $tv->caption = $request['desc'];
            $tv->category = $tvsCategory;
            $tv->save();
        } else {
            if ($request['typeinput'] == 'select') {
                $tv = SiteTmplvar::create(['type' => 'custom_tv:ddaparams', 'name' => $filterParam->alias, 'caption' => $filterParam->desc, 'category'=>$tvsCategory]);
            } else {
                $tv = SiteTmplvar::create(['type' => 'text', 'name' => $filterParam->alias, 'caption' => $filterParam->desc, 'category'=>$tvsCategory]);
            }
        }
        $filterParam->tv_id = $tv->id;
        $filterParam->save();

        if ($request['typeinput'] == 'select') {
            $selectorTemplate = EVO_CORE_PATH . 'custom/packages/ddafilters/modules/template/selectorTemplate.php';
            if (!file_exists($selectorTemplate)) {
                $selectorTemplate = EVO_CORE_PATH . 'vendor/ddaproduction/evocms-params/modules/template/selectorTemplate.php';
            }
            $selectorTemplate = file_get_contents($selectorTemplate);
            $alias_small = strtolower($request['alias']);
            $alias_small_up_first = ucfirst($alias_small);
            $selectorTemplate = str_replace('__ALIAS_BIG_FIRST__', $alias_small_up_first, $selectorTemplate);
            $selectorTemplate = str_replace('__TV__ID__', $filterParam->getKey(), $selectorTemplate);
            file_put_contents(MODX_BASE_PATH . 'assets/tvs/ddaparams/lib/' . $alias_small . '.controller.class.php', $selectorTemplate);
        }
        $products = SystemSetting::find('template_products')->setting_value;
        if (!is_null($products)) {
            $products = explode(',', $products);
            $products = array_map('trim', $products);
            SiteTmplvarTemplate::where('tmplvarid', $filterParam->tv_id)->delete();
            foreach ($products as $product) {
                SiteTmplvarTemplate::firstOrCreate(['tmplvarid' => $filterParam->tv_id, 'templateid' => $product, 'rank' => 0]);
            }

        }

    }
}

