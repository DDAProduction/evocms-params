<?php
/**
 * default config
 */

global $_lang;

use EvolutionCMS\Models\SiteTmplvar;
use Illuminate\Support\Facades\DB;


$evo = EvolutionCMS();
$parents = $evo->getParentIds($_GET['id']);
$parents = array_values($parents);
$paramsTemplateEditor = [];
if (count($parents) > 0) {
    $categoryTv = SiteTmplvar::where('name', 'category')->first();
    $parentString = implode(',', $parents);
    $result = \EvolutionCMS\Models\SiteTmplvarContentvalue::where('tmplvarid', $categoryTv->getKey())
        ->whereIn('contentid', $parents)
        ->orderBy(DB::raw('FIELD(contentid,' . $parentString . ')'))->first();

    $globalParams = $evo->getConfig('dda_filter_global_product_params');
    if (is_array($globalParams)) {
        foreach ($globalParams as $key => $globalParam) {
            $paramsTemplateEditor[$key] = $globalParam;
        }
    }
    if (is_null($result)) {
        $default = true;
    } else {
        $default = false;
        $params = \EvolutionCMS\Ddafilters\Models\FilterParamsCategory::where('category_id', $result->value)->orderBy('order')->pluck('param_id');

        $paramsOutput = \EvolutionCMS\Ddafilters\Models\FilterParams::whereIn('id', $params)->get();
        foreach ($paramsOutput as $item) {
            $paramsTemplateEditor[$item->alias] = [];

        }
    }
}else {
    $default = false;
}
$tabs = [
    'General' => [
        'default' => $default,
        'title' => $_lang['settings_general'],
        'fields' => [
            'pagetitle' => [
                'class' => 'form-control-lg'
            ],
            'longtitle' => [],
            'description' => [],
            'menutitle' => [],
            'parent' => [],
            'weblink' => [],
            'template' => [],
            'category' => []
        ]
    ],
    'Content' => [
        'title' => $_lang['description'],
        'fields' => [
            'introtext' => [
                'titleClass' => 'col-xs-12',
                'fieldClass' => 'col-xs-12',
                'rows' => 5
            ],
            'content' => [
                'titleClass' => 'col-xs-12 form-row pt-1',
                'fieldClass' => 'col-xs-12',
                'selectClass' => 'float-xs-right',
                'rows' => 15
            ],
            'richtext' => [],
        ]
    ],
    'Products' => [
        'title' => 'Параметры товаров',
        'fields' => $paramsTemplateEditor
    ],
    'Seo' => [
        'title' => 'SEO',
        'fields' => [
            'metaTitle' => [],
            'titl' => [],
            'metaDescription' => [],
            'desc' => [],
            'metaKeywords' => [],
            'keyw' => [],
            'alias' => [],
            'link_attributes' => [],
            'menuindex' => [],
            'hidemenu' => [],
            'noIndex' => [],
            'sitemap_exclude' => [],
            'sitemap_priority' => [],
            'sitemap_changefreq' => []
        ]
    ],
    'Settings' => [
        'title' => $_lang['settings_page_settings'],
        'fields' => [
            'published' => [],
            'alias_visible' => [],
            'isfolder' => [],
            'donthit' => [],
            'contentType' => [],
            'type' => [],
            'content_dispo' => [],
            'pub_date' => [],
            'unpub_date' => [],
            'createdon' => [],
            'editedon' => [],
            'searchable' => [],
            'cacheable' => [],
            'syncsite' => []
        ]
    ]
];
$customTabs = [];
$customTabs = $evo->getConfig('dda_filter_custom_tabs');
if (is_array($customTabs))
    $tabs = array_merge($tabs, $customTabs);

$customTabs = $evo->getConfig('dda_filter_tabs_sort');
if (is_array($customTabs)) {
    $new_tabs = [];
    foreach ($customTabs as $key => $customTab) {
        if (isset($tabs[$key])) {
            if ($customTab != '') {
                $tabs[$key]['title'] = $customTab;
            }
            $new_tabs[$key] = $tabs[$key];
        }
    }
    $tabs = $new_tabs;
}

return $tabs;