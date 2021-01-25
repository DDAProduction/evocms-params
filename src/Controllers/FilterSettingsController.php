<?php

namespace EvolutionCMS\Ddafilters\Controllers;


use EvolutionCMS\Ddafilters\Models\FilterCategory;
use EvolutionCMS\Ddafilters\Models\FilterParams;
use EvolutionCMS\Ddafilters\Models\FilterParamsCategory;
use EvolutionCMS\Ddafilters\Models\FilterParamValues;
use EvolutionCMS\Models\SiteTmplvar;
use EvolutionCMS\Models\SiteTmplvarTemplate;
use EvolutionCMS\Models\SystemSetting;

class FilterSettingsController
{
    public static function getSettings()
    {
        $answer = ['tvs_category' => SystemSetting::find('tvs_category')->setting_value,
            'template_category' => SystemSetting::find('template_category')->setting_value,
            'template_products' => SystemSetting::find('template_products')->setting_value];
        HelperController::response($answer);
    }

    public static function setSettings($request)
    {
        if (isset($request['tvs_category'])) {
            SystemSetting::updateOrCreate(['setting_name' => 'tvs_category'],
                ['setting_value' => $request['tvs_category']]);
        }
        if (isset($request['template_category']) && $request['template_category'] != '') {

            $categoryTv = SiteTmplvar::where('name', 'category')->first();
            if (is_null($categoryTv)) {
                $categoryTv = SiteTmplvar::create(['name' => 'category', 'caption' => 'Категории', 'type' => 'custom_tv:selectdirectory']);
            }
            $categories = explode(',', $request['template_category']);
            $categories = array_map('trim', $categories);
            SiteTmplvarTemplate::where('tmplvarid', $categoryTv->getKey())->delete();
            foreach ($categories as $category) {
                if (is_numeric($category)) {
                    SiteTmplvarTemplate::firstOrCreate(['tmplvarid' => $categoryTv->getKey(), 'templateid' => $category, 'rank' => 0]);
                }
            }

            SystemSetting::updateOrCreate(['setting_name' => 'template_category'],
                ['setting_value' => $request['template_category']]);
        }
        if (isset($request['template_products'])) {
            $products = explode(',', $request['template_products']);
            $products = array_map('trim', $products);
            $tvs = FilterParams::all()->pluck('tv_id');
            SiteTmplvarTemplate::whereIn('tmplvarid', $tvs)->delete();
            foreach ($products as $product) {
                if (is_numeric($product)) {
                    $productTemplate = EVO_CORE_PATH . 'custom/config/cms/settings/dda_params_products_template._php';
                    if (!file_exists($productTemplate)) {
                        $productTemplate = EVO_CORE_PATH . 'custom/packages/ddafilters/modules/template/productTemplate.php';
                    }
                    if (!file_exists($productTemplate)) {
                        $productTemplate = EVO_CORE_PATH . 'vendor/ddaproduction/evocms-params/modules/template/productTemplate.php';
                    }
                    $default_config = MODX_BASE_PATH . '/assets/plugins/templatesedit/configs/template__' . $product . '__1.json';
                    if (file_exists($default_config)) {
                        unlink($default_config);
                    }
                    file_put_contents(MODX_BASE_PATH . '/assets/plugins/templatesedit/configs/template__' . $product . '.php', file_get_contents($productTemplate));
                    foreach ($tvs as $tv)
                        SiteTmplvarTemplate::firstOrCreate(['tmplvarid' => $tv, 'templateid' => $product, 'rank' => 0]);
                }
            }
            SystemSetting::updateOrCreate(['setting_name' => 'template_products'],
                ['setting_value' => $request['template_products']]);
        }
        self::getSettings();
    }

}

